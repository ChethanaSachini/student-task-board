<?php

// This is a local operator tool, never a public registration endpoint.
if (PHP_SAPI !== "cli") {
    http_response_code(403);
    exit("This tool is available only from the command line.");
}

if ($argc !== 2 || $argv[1] !== "--create") {
    echo "Creates one new administrator after separate provisioning approval.\n";
    exit("Usage: php scripts/create_admin.php --create\n");
}

function readAdminPassword()
{
    if (PHP_OS_FAMILY !== "Windows") {
        throw new RuntimeException("Hidden password input currently supports Windows only.");
    }

    // Read keys from the console without echoing them. Only the parent PHP
    // process receives the password; it is never placed in command arguments.
    $script = <<<'POWERSHELL'
$ErrorActionPreference = 'Stop'
if ([Console]::IsInputRedirected) { exit 1 }
[Console]::Error.Write('Password (hidden): ')
$secret = New-Object System.Security.SecureString
while ($true) {
    $key = [Console]::ReadKey($true)
    if ($key.Key -eq 'Enter') { break }
    if ($key.Key -eq 'Backspace') {
        if ($secret.Length -gt 0) { $secret.RemoveAt($secret.Length - 1) }
    } elseif (-not [char]::IsControl($key.KeyChar)) {
        $secret.AppendChar($key.KeyChar)
    }
}
[Console]::Error.WriteLine()
$pointer = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($secret)
try {
    $plain = [Runtime.InteropServices.Marshal]::PtrToStringBSTR($pointer)
    [Console]::Out.Write([Convert]::ToBase64String([Text.Encoding]::UTF8.GetBytes($plain)))
} finally {
    [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($pointer)
    $plain = $null
    $secret.Dispose()
}
POWERSHELL;

    $encodedScript = base64_encode(mb_convert_encoding($script, "UTF-16LE", "UTF-8"));
    $process = proc_open(
        ["powershell.exe", "-NoProfile", "-EncodedCommand", $encodedScript],
        [0 => STDIN, 1 => ["pipe", "w"], 2 => STDERR],
        $pipes,
        null,
        null,
        ["bypass_shell" => true]
    );

    if (!is_resource($process)) {
        throw new RuntimeException("Unable to start hidden password input.");
    }

    $encodedPassword = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $exitCode = proc_close($process);
    $password = base64_decode($encodedPassword, true);
    if ($exitCode !== 0 || $password === false) {
        throw new RuntimeException("Hidden password input failed. No account was created.");
    }

    return $password;
}

try {
    $name = trim(readline("Administrator name: "));
    $email = trim(readline("New administrator email: "));
    if ($name === "" || mb_strlen($name) > 100
        || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) {
        throw new RuntimeException("Enter a valid name and email. No account was created.");
    }

    echo "Enter a password of 8 to 72 bytes, then enter it again to confirm.\n";
    $password = readAdminPassword();
    $confirmation = readAdminPassword();
    if (strlen($password) < 8 || strlen($password) > 72
        || str_contains($password, "\0") || !hash_equals($password, $confirmation)) {
        throw new RuntimeException("Password length or confirmation is invalid. No account was created.");
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    unset($password, $confirmation);

    require __DIR__ . "/../db.php";
    require_once __DIR__ . "/../app/Models/UserModel.php";

    if (findUserByEmail($connection, $email)) {
        throw new RuntimeException("That email is already registered. Existing accounts were not changed.");
    }

    $statement = $connection->prepare(
        "INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'admin')"
    );
    $statement->bind_param("sss", $name, $email, $passwordHash);
    try {
        $statement->execute();
    } finally {
        $statement->close();
    }

    echo "Administrator account created. Sign in using the existing login page.\n";
} catch (mysqli_sql_exception $error) {
    // Do not print or log provisioning SQL errors: duplicate-key errors can
    // contain account details. The unique email constraint also handles races.
    fwrite(STDERR, "Unable to create the administrator. Check the database and use a new email.\n");
    exit(1);
} catch (RuntimeException $error) {
    fwrite(STDERR, $error->getMessage() . "\n");
    exit(1);
}
