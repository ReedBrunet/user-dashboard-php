<?php
// rabrunet
session_start();
require_once "header.php";
checkLogin();

// Validate ID
if (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) {
    echo "<p class='error'>Invalid page ID.</p>";
    require_once "footer.php";
    exit();
}

$pageName = "Delete Page";

$id = $_GET['id'];
$userID = $_SESSION['ID'];

// Fetch content and verify ownership
$sql = "SELECT * FROM pages_rabrunet WHERE ID = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id);
$stmt->execute();
$row = $stmt->fetch();

if (!$row) {
    echo "<p class='error'>Content not found.</p>";
    require_once "footer.php";
    exit();
}

if ($row['userID'] != $userID) {
    echo "<p class='error'>You are not authorized to delete this content.</p>";
    require_once "footer.php";
    exit();
}

// Form submit = confirmed deletion
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $sql = "DELETE FROM pages_rabrunet WHERE ID = :id AND userID = :userID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':userID', $userID);
    $stmt->execute();

    echo "<p class='success'>Page titled <strong>" . htmlspecialchars($row['title']) . "</strong> has been deleted.</p>";
    require_once "footer.php";
    exit();
}

// Confirmation form display
?>

<h3>Are you sure you want to delete this page?</h3>
<p><strong>Title:</strong> <?= htmlspecialchars($row['title']) ?></p>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id; ?>">
    <input type="submit" value="Yes, Delete Page">
    <a href="pagemanage.php">Cancel</a>
</form>

<?php
require_once "footer.php";
?>
