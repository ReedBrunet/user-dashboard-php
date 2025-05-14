<?php
session_start();
$pageName = "Login";
require_once "header.php"; 

$showForm = true;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = trim(strtolower($_POST['email'] ?? ''));
    $pwd = $_POST['pwd'];

    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($pwd)) {
        $errors['pwd'] = "Password is required.";
    }

    if (empty($errors)) {
        $sql = "SELECT * FROM users_rabrunet WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch();

        if (!$row) {
            echo "<p class='error'>There is no account with that email address.</p>";
        } else {
            if (password_verify($pwd, $row['pwd'])) {
                $_SESSION['ID'] = $row['ID'];
                $_SESSION['fname'] = $row['fname'];
                $_SESSION['status'] = $row['status'];

                header("Location: confirm.php?state=2");
                exit();
            } else {
                echo "<p class='error'>Invalid email or password combination.</p>";
            }
        }
    } 
}

if ($showForm):
?>
    <form name="login" id="login" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
		<?php 
			if(isset($errors['email'])){
						echo "<span class='error'>&#9888; {$errors['email']}</span>";
			}
			?>
		<br>
		
        <label for="pwd">Password:</label>
        <input type="password" name="pwd" id="pwd" >
		<?php 
			if(isset($errors['pwd'])){
						echo "<span class='error'>&#9888; {$errors['pwd']}</span>";
			}
		?>
		<br>
        <button type="submit">Login</button>
    </form>
<?php
endif;
require_once "footer.php";
?>
