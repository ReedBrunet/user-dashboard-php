<?php
// rabrunet
session_start();

$pageName = "View Page";
require_once "header.php";

$id = $_GET['id'];

$sql = "SELECT p.title, p.details, p.created, u.fname, u.lname
        FROM pages_rabrunet p
        JOIN users_rabrunet u ON p.userID = u.ID
        WHERE p.ID = :id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id);
$stmt->execute();
$row = $stmt->fetch();

if (!$row) {
    echo "<p class='error'>Page not found.</p>";
} else {
    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
    echo "<p><strong>Owner:</strong> " . htmlspecialchars($row['fname']) . " " . htmlspecialchars($row['lname']) . "</p>";
    echo "<p><strong>Date:</strong> " . date("F j, Y", strtotime($row['created'])) . "</p>";
    echo "<div>" . $row['details'] . "</div>";
}
?>

<?php
require_once "footer.php";
?>