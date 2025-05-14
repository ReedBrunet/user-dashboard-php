<?php
// rabrunet

session_start();
require_once "header.php";
checkLogin();

$pageName = "Update Profile";
$errors = [];
$showForm = true;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $fname = trim($_POST['fname']);
    $mi = trim($_POST['mi']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $choice = $_POST['choice'] ?? '';
    $state = $_POST['state'];
    $bio = trim($_POST['bio']);

    if (empty($fname)) {
        $errors['fname'] = "First Name is required.";
    }

    if (empty($lname)) {
        $errors['lname'] = "Last Name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid Email.";
    }

    if (empty($choice)) {
        $errors['choice'] = "Please select a communication preference.";
    }

    if (empty($state)) {
        $errors['state'] = "Please select a state.";
    }

    if (empty($bio)) {
        $errors['bio'] = "Please enter a bio.";
    }

    if (empty($errors)) {
        $sql = "UPDATE users_rabrunet SET 
                fname = :fname, mi = :mi, lname = :lname,
                email = :email, choice = :choice, state = :state, bio = :bio
                WHERE ID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':fname', $fname);
        $stmt->bindValue(':mi', $mi);
        $stmt->bindValue(':lname', $lname);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':choice', $choice);
        $stmt->bindValue(':state', $state);
        $stmt->bindValue(':bio', $bio);
        $stmt->bindValue(':id', $_SESSION['ID']);
        $stmt->execute();

        header("Location: index.php");
        exit();
    }
} else {
    // GET request: load user info into form
    $sql = "SELECT fname, mi, lname, email, choice, state, bio 
            FROM users_rabrunet WHERE ID = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_SESSION['ID']);
    $stmt->execute();
    $row = $stmt->fetch();

    if ($row) {
        $fname = $row['fname'];
        $mi = $row['mi'];
        $lname = $row['lname'];
        $email = $row['email'];
        $choice = $row['choice'];
        $state = $row['state'];
        $bio = $row['bio'];
    }
}

require_once "header.php";
?>

<h2>Update Your Profile</h2>

<form method="post" action="profileupdate.php">
    <label for="fname">First Name:</label>
    <input type="text" name="fname" id="fname" value="<?php echo htmlspecialchars($fname ?? ''); ?>">
    <?php if (isset($errors['fname'])) echo "<span class='error'>&#9888; {$errors['fname']}</span>"; ?>
    <br>

    <label for="mi">Middle Initial:</label>
    <input type="text" name="mi" id="mi" maxlength="1" value="<?php echo htmlspecialchars($mi ?? ''); ?>">
    <br>

    <label for="lname">Last Name:</label>
    <input type="text" name="lname" id="lname" value="<?php echo htmlspecialchars($lname ?? ''); ?>">
    <?php if (isset($errors['lname'])) echo "<span class='error'>&#9888; {$errors['lname']}</span>"; ?>
    <br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
    <?php if (isset($errors['email'])) echo "<span class='error'>&#9888; {$errors['email']}</span>"; ?>
    <br>

    <fieldset>
        <legend>Preferred Communication:</legend>
        <input type="radio" name="choice" id="emailChoice" value="email" <?php if (($choice ?? '') == "email") echo "checked"; ?>>
        <label for="emailChoice">Email</label><br>

        <input type="radio" name="choice" id="text" value="text" <?php if (($choice ?? '') == "text") echo "checked"; ?>>
        <label for="text">Text</label><br>

        <input type="radio" name="choice" id="phone" value="phone" <?php if (($choice ?? '') == "phone") echo "checked"; ?>>
        <label for="phone">Phone</label>
        <?php if (isset($errors['choice'])) echo "<br><span class='error'>&#9888; {$errors['choice']}</span>"; ?>
    </fieldset>

    <label for="state">State:</label>
    <select name="state" id="state">
        <option value="">Select one</option>
        <?php
        $sql = "SELECT abbrev, name FROM statelist ORDER BY priority";
        $result = $pdo->query($sql);
        foreach ($result as $rowState) {
            echo "<option value='{$rowState['abbrev']}'";
            if (($state ?? '') == $rowState['abbrev']) echo " selected";
            echo ">{$rowState['name']}</option>";
        }
        ?>
    </select>
    <?php if (isset($errors['state'])) echo "<br><span class='error'>&#9888; {$errors['state']}</span>"; ?>
    <br>

    <label for="bio">Bio:</label><br>
    <textarea name="bio" id="bio"><?php echo htmlspecialchars($bio ?? ''); ?></textarea>
    <?php if (isset($errors['bio'])) echo "<br><span class='error'>&#9888; {$errors['bio']}</span>"; ?>
    <br><br>

    <input type="submit" value="Update Profile">
</form>

<?php require_once "footer.php"; ?>
