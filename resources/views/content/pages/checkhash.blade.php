<?php

use Illuminate\Support\Facades\Hash;

// Sample plain text password
$plainPassword = '12345678';
$rounds = 12;

$usd = Hash::make(trim($plainPassword), ['rounds' => $rounds]);

echo $usd;

?>
