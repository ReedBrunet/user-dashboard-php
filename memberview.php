<?php
//RABRUNET

session_start();
$pageName = "Member View";

require_once "header.php";

//GET ALL USERS
$sql = "SELECT ID, fname, lname FROM users_rabrunet ORDER BY lname, fname";
$stmt = $pdo->query($sql);
$members = $stmt->fetchALL(PDO::FETCH_ASSOC);
?>

<h2>Registered Members</h2>

<?php
if (!isset($_GET['ID'])) {
	echo "<p>Click a member's name to view their profile info!</p>";
	
	$sql = "SELECT ID, fname, lname FROM users_rabrunet ORDER BY lname, fname";
	$stmt = $pdo->query($sql);
	$memebrs = $stmt->fetchALL(PDO::FETCH_ASSOC);
	
	echo "<div class='member-view'>";
	echo "<ul>";
	foreach ($members as $member) {
		echo "<li><a href='memberview.php?ID=" . htmlspecialchars($member['ID']) . "'>" . htmlspecialchars($member['fname']) . " " . htmlspecialchars($member['lname']) . "</a></li>";

	}
	echo "</ul>";
	echo "</div>";
	
}else{
	
	$id = $_GET['ID'];
	
	$sql = "SELECT fname, mi, lname, email, state, bio FROM users_rabrunet WHERE ID = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
	
	  if (!$member) {
        echo "<p class='error'>Member not found.</p>";
    } else {
        echo "<h2>Member Profile</h2>";
        echo "<p>Name: " . htmlspecialchars($member['fname']) . " " . htmlspecialchars($member['mi']) . " " . htmlspecialchars($member['lname']) . "</p>";
        echo "<p>Email: " . htmlspecialchars($member['email']) . "</p>";
        echo "<p>State:	" . htmlspecialchars($member['state']) . "</p>";
		echo"<div class='bio'>";
        echo "<p>Bio:<br> "	. htmlspecialchars($member['bio']) . "</p>";
		echo"</div>";
		
		 $sqlPages = "SELECT title, ID FROM pages_rabrunet WHERE userID = :userID ORDER BY title";
        $stmtPages = $pdo->prepare($sqlPages);
        $stmtPages->bindValue(':userID', $id);
        $stmtPages->execute();
        $pages = $stmtPages->fetchAll(PDO::FETCH_ASSOC);

        if ($pages) {
            echo "<h3>Page Content by This Member</h3>";
            echo "<ul>";
            foreach ($pages as $page) {
                echo "<li><a href='pageview.php?ID=" . htmlspecialchars($page['ID']) . "'>" . htmlspecialchars($page['title']) . "</a></li>";
            }
            echo "</ul>";
        }
    }

    echo "<p><a href='memberview.php'>Back to Member List</a></p>";
}

require_once "footer.php";
?>