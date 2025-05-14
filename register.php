<?php
//rabrunet

$pageName = "Register";
require "header.php";

//initial Variables
$showForm = true;
$errors = [];

//Form Processing
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	//debugging 
	//print_r($_POST);
	//var_dump($_POST);
	
	//Local Varibables 
	$fname = trim($_POST['fname']);
	$mi = trim($_POST['mi']);
	$lname = trim($_POST['lname']);
	$email = trim($_POST['email']);
	$pwd = $_POST['pwd'];
	if (isset($_POST['choice'])){
		$choice = $_POST['choice'];
	}
	$state = $_POST['state'];
	$bio = trim($_POST['bio']);
	
	//Error Checking
	if (empty($fname)) {
		$errors['fname'] = "First Name is required ";
	}
	
	if (empty($lname)) {
		$errors['lname'] = "Last Name is required";
	}
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$errors['email'] = "Please enter a valid Email";
	} else{
		$sql = "SELECT email FROM users_rabrunet WHERE email = :field";
		$dupEmail = check_duplicates($pdo, $sql, $email);
		if ($dupEmail) {
			$errors['email'] = "This email is already signed up!";
		}	
	}
	
	if (strlen($pwd) < 8) {
		$errors['pwd'] = "Please enter a valid Password";
	}
	
	if (empty($choice)) {
		$errors['choice'] = "please select a communication preference";
	}
	
	if (empty($state)) {
		$errors['state'] = "Please choose a State";
	}
	
	if (empty($bio)) {
		$errors['bio'] = "Please enter a Bio";
	}
	
	//Program Control
	if (!empty($errors)) {
		echo "<p class='error'>There are errors in one of the fields please fill them out!</p>";
	} else {
        $sql = "INSERT INTO users_rabrunet 
                (fname, mi, lname, email, pwd, choice, state, bio, created)
                VALUES 
                (:fname, :mi, :lname, :email, :pwd, :choice, :state, :bio, :created)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':fname', $fname);
        $stmt->bindValue(':mi', $mi);
        $stmt->bindValue(':lname', $lname);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':pwd', password_hash($pwd, PASSWORD_DEFAULT));
        $stmt->bindValue(':choice', $choice);
        $stmt->bindValue(':state', $state);
        $stmt->bindValue(':bio', $bio);
        $stmt->bindValue(':created', date("Y-m-d H:i:s"));
        $stmt->execute();
		
		echo "<p class='success'>Thank you for registering</p>";
		$showForm = false; 
	} // End Program Control
}//End Form Processing

//Form Code
if($showForm) {
?>
	<p>Hello Please Register Here!</p>
	
	<form name="register" id="register" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <label for="fname">First Name:</label>
<input type="text" name="fname" id="fname" maxlength="50" value="<?php if (isset($fname)){echo htmlspecialchars($fname);}?>">
	<?php 
			if(isset($errors['fname'])){
						echo "<span class='error'>&#9888; {$errors['fname']}</span>";
			}
	?>
    <br>
	
	<label for=mi>Middle Initial:</label>
	<input type="text" name="mi" id="mi" maxlength="1" value="<?php if (isset($mi)){echo htmlspecialchars($mi);}?>">
	<br>
	
	<label for="lname">Last Name:</label>
	<input type="text" name="lname" id="lname" maxlength="50" value="<?php if (isset($lname)){echo htmlspecialchars($lname);}?>">
	<?php 
			if(isset($errors['lname'])){
						echo "<span class='error'>&#9888; {$errors['lname']}</span>";
			}
	?>
	<br>
    
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" maxlength="100" value="<?php if (isset($email)){echo htmlspecialchars($email);}?>">
	<?php 
			if(isset($errors['email'])){
						echo "<span class='error'>&#9888; {$errors['email']}</span>";
			}
	?>
    <br>
    
    <label for="pwd">Password:</label>
    <input type="password" name="pwd" id="pwd" placeholder="At least 8 characters">
	<?php 
			if(isset($errors['pwd'])){
						echo "<span class='error'>&#9888; {$errors['pwd']}</span>";
			}
	?>
    <br>
    
    <fieldset><legend>What is your preferred method of communication?</legend>
	<?php 
			if(isset($errors['choice'])){
						echo "<span class='error'>&#9888; {$errors['choice']}</span>";
			}
	?>
	
	<br>
    <input type="radio" name="choice" id="e" value="email" <?php if(isset($choice) && $choice == "email") echo "checked";?>>
	<label for="e">Email</label>
	<br>
	
	<input type="radio" name="choice" id="t" value="text" <?php if(isset($choice) && $choice == "text") echo "checked";?>>
	<label for="t">Text Message</label>
	<br>
	
	<input type="radio" name="choice" id="p" value="phone" <?php if(isset($choice) && $choice == "phone") echo "checked";?>>
	<label for="p">Phone Call</label>
	<br>
    </fieldset>
    
    <label for="state">State</label>
    <select name="state" id="state">
		<option value="" <?php if (isset($state) && $state == "") {echo "selected";}?>>Pick One</option>
	<?php
	$sql = "SELECT abbrev, name FROM statelist ORDER BY priority";
	$result = $pdo->query($sql);
	foreach($result as $row) {
		echo "<option value='" . $row['abbrev'] . "'";
		if (isset($state) && $state == $row['abbrev']) {
			echo "selected";
		}
		echo ">" . $row['name'] . "</option>";
	}
	?>
    </select>
	<?php 
		if(isset($errors['state'])){
			echo "<span class='error'>&#9888; {$errors['state']}</span>";
		}
	?>
	
    <br>
    
    <label for="bio">Bio:</label><br>
	<?php 
			if(isset($errors['bio'])){
						echo "<span class='error'>&#9888; {$errors['bio']}</span>";
			}
	?>
    <textarea name="bio" id="bio" maxlength="1000"><?php if (isset($bio)) {echo htmlspecialchars($bio);}?></textarea>
    <br>
    
    <input type="submit" name="submit" id="submit" value="submit" >
</form>
<?php
}//End Show Form
require "footer.php";
?>