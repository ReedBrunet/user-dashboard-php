<?php
// rabrunet
    $dsn = "mysql:host=localhost;dbname=csci303sp25";
    $user = "csci303sp25";
    $pass = "PHPspring2025!";
    $pdo = new PDO($dsn, $user, $pass);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
