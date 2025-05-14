<?php
// rabrunet
session_start();
$pageName = "Update Password";
require "header.php";
checkLogin();

$errors = [];
$showForm = true;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $newpwd = trim($_POST['newpwd'] ?? '');
    $confirm = trim($_POST['confirm'] ?? '');

    if (empty($newpwd)) {
        $errors['pwd'] = "Password is required.";
    }

    if (empty($confirm)) {
        $errors['confirm'] = "Confirm password is required.";
    }

    if (!empty($newpwd) && !empty($confirm) && $newpwd !== $confirm) {
        $errors['confirm'] = "Passwords do not match.";
    }

    if (!empty($newpwd) && strlen($newpwd) < 8) {
        $errors['pwd'] = "Password must be at least 8 characters.";
    }

    if (empty($errors)) {
        $hashed = password_hash($newpwd, PASSWORD_DEFAULT);
        $sql = "UPDATE users_rabrunet SET password = :pwd WHERE ID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':pwd', $hashed);
        $stmt->bindValue(':id', $_SESSION['ID']);
        $stmt->execute();

        echo "<p class='success'>Password updated successfully.</p>";
    }
}
?>

<h2>Update Your Password</h2>

<?php if ($showForm): ?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <label for="newpwd">New Password:</label>
    <input type="password" name="newpwd" id="newpwd">
    <?php 
        if (isset($errors['pwd'])) {
            echo "<span class='error'>&#9888; {$errors['pwd']}</span>";
        }
    ?>
    <br>

    <label for="confirm">Confirm Password:</label>
    <input type="password" name="confirm" id="confirm">
    <?php 
        if (isset($errors['confirm'])) {
            echo "<span class='error'>&#9888; {$errors['confirm']}</span>";
        }
    ?>
    <br>

    <input type="submit" value="Update Password">
</form>
<?php endif; ?>

<?php require_once "footer.php"; ?>
