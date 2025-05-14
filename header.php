<?php
// rabrunet
session_start();
require_once "connect.php";
require_once "functions.php";

$currentFile = basename($_SERVER['SCRIPT_FILENAME']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>rabrunet</title>
    <link rel="stylesheet" href="styles.css">

    <script src="/csci303/tinymce/tinymce/js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({ selector:'textarea' });
    </script>
</head>
<body>
    <header>
		<?php
        $files = glob("bannerimages/*.{jpg,png,gif}", GLOB_BRACE);
        if (!empty($files)) {
            $randomBanner = $files[array_rand($files)];
            echo "<img src='$randomBanner' alt='Site Banner' style='width:100%; height:auto;'>";
        }
        ?>
        <nav>
           <?php
            echo ($currentFile == "index.php") ? "Home" : "<a href='index.php'>Home</a>";
            echo " | ";

            echo ($currentFile == "register.php") ? "Register" : "<a href='register.php'>Register</a>";
            echo " | ";

            echo ($currentFile == "search.php") ? "Search" : "<a href='search.php'>Search</a>";
            echo " | ";

            if (isset($_SESSION['ID'])) {
                echo ($currentFile == "pagemanage.php") ? "Manage Page" : "<a href='pagemanage.php'>Manage Page</a>";
                echo " | ";

                echo ($currentFile == "profileupdate.php") ? "Update Profile" : "<a href='profileupdate.php'>Update Profile</a>";
                echo " | ";

                echo ($currentFile == "passwordupdate.php") ? "Update Password" : "<a href='passwordupdate.php'>Update Password</a>";
                echo " | ";

                echo ($currentFile == "memberview.php") ? "View Members" : "<a href='memberview.php'>View Members</a>";
                echo " | ";

                if (isset($_SESSION['status']) && $_SESSION['status'] == 1) {
                    echo ($currentFile == "userstatus.php") ? "Manage Users" : "<a href='userstatus.php'>Manage Users</a>";
                    echo " | ";
                    echo ($currentFile == "addcategory.php") ? "Manage Categories" : "<a href='addcategory.php'>Manage Categories</a>";
                    echo " | ";
                }

                echo "<a href='logout.php'>Logout</a>";
            } else {
                echo ($currentFile == "login.php") ? "Login" : "<a href='login.php'>Login</a>";
            }
           ?>
        </nav>
        <h1>Reed Brunet</h1>
    </header>
    <main>
        <h2><?php echo $pageName; ?></h2>