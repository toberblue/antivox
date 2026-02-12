#!/usr/bin/env php
<?php

/**
 * Generate a password hash for admin authentication
 * 
 * Usage: php generate-admin-password.php your_password_here
 */

if ($argc < 2) {
    echo "Usage: php generate-admin-password.php your_password_here\n";
    exit(1);
}

require __DIR__.'/vendor/autoload.php';

$password = $argv[1];
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "\nGenerated password hash:\n";
echo $hash . "\n\n";
echo "Add this to your .env file:\n";
echo "ADMIN_USERNAME=admin\n";
echo "ADMIN_PASSWORD=\"{$hash}\"\n\n";
