<?php
session_start();
$pageName = "Confirmation";
require_once "header.php";


if (isset($_GET['state'])) {
	$state = $_GET['state'];

    if ($state == 1) {
        echo "<h2>You have successfully logged out.</h2>";
        echo "<p><a href='login.php'>Click here to log back in.</a></p>";
		
    } elseif ($state == 2) {
        if (isset($_SESSION['fname'])) {
            echo "<h2>Welcome, " . htmlspecialchars($_SESSION['fname']) . "!</h2>";
            echo "<p>You are now logged in. </p>";
        } else {
            echo "<h2>Welcome!</h2>";
			echo "<p>We couldn't find your name in the session.</p>";
        }
	
} else {
    echo "<h2>Welcome!</h2>";
	echo "<p>Please log in to access your account.</p>";
	}
}
require_once "footer.php";
?>
