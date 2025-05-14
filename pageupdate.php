<?php
session_start();
$pageName = "Update Page Content";
require_once "header.php";
checkLogin();

$showForm = true;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
    $ID = $_GET['id'];
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['ID'])) {
    $ID = $_POST['ID'];
} else {
    echo "<p class='error'>No such entry!</p>";
    $showForm = false;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = trim($_POST['title']);
    $details = trim($_POST['details']);
    $categoryID = trim($_POST['categoryID']);
    $origtitle = $_POST['origtitle'];

    if ($title != $origtitle) {
        $sql = "SELECT title FROM pages_rabrunet WHERE title = :field";
        $dupTitle = check_duplicates($pdo, $sql, $title);
        if ($dupTitle) {
            $errors['title'] = "Title is taken.";
        }
    }

    if (empty($title)) {
        $errors['title'] = "Title is required.";
    }

    if (empty($details)) {
        $errors['details'] = "Details are required.";
    }

    if (empty($categoryID)) {
        $errors['categoryID'] = "Category selection is required.";
    }

    if (empty($errors)) {
        $sql = "UPDATE pages_rabrunet 
                SET title = :title, details = :details, updated = :updated, categoryID = :categoryID" .
                ($uploadedFilename ? ", filename = :filename" : "") . "
                WHERE ID = :ID AND userID = :userID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':details', $details);
        $stmt->bindValue(':updated', date("Y-m-d H:i:s"));
        $stmt->bindValue(':categoryID', $categoryID);
        $stmt->bindValue(':ID', $ID);
        $stmt->bindValue(':userID', $_SESSION['ID']);
        $stmt->execute();

        echo "<p class='success'>Page updated successfully.</p>";
        $showForm = false;
    } else {
        echo "<p class='error'>There are errors. Please fix them.</p>";
    }
}

if ($showForm) {
    $sql = "SELECT * FROM pages_rabrunet WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $ID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo "<p class='error'>There is no entry.</p>";
        $showForm = false;
    }

    if ($row && $row['userID'] != $_SESSION['ID']) {
        echo "<p class='error'>You are not the owner of this content.</p>";
        $showForm = false;
    }
}

$sql = "SELECT * FROM rabrunet_categories ORDER BY categoryName ASC";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll();
?>

<?php if ($showForm): ?>
<p>All fields required.</p>

<form name="myupdate" id="myupdate" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $ID; ?>" method="post" enctype="multipart/form-data">

    <input type="hidden" name="ID" value="<?php echo htmlspecialchars($row['ID']); ?>">
    <input type="hidden" name="origtitle" value="<?php echo htmlspecialchars($row['title']); ?>">

    <label for="title">Title:</label>
    <input type="text" name="title" id="title" maxlength="255"
           value="<?php echo isset($title) ? htmlspecialchars($title) : htmlspecialchars($row['title']); ?>">
    <?php
    if (!empty($errors['title'])) {
        echo "<span class='error'> " . $errors['title'] . "</span>";
    }
    ?>
    <br><br>

    <label for="details">Paragraph:</label><br>
    <textarea name="details" id="details" rows="6" cols="50"><?php
        echo isset($details) ? htmlspecialchars($details) : htmlspecialchars($row['details']);
    ?></textarea>
    <?php
    if (!empty($errors['details'])) {
        echo "<br><span class='error'>" . $errors['details'] . "</span>";
    }
    ?>
    <br><br>

    <label for="categoryID">Category:</label>
    <select name="categoryID" id="categoryID">
        <option value="">Choose a category</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['categoryID']; ?>"
                <?php 
                if (isset($categoryID) && $categoryID == $cat['categoryID']) {
                    echo "selected";
                } elseif (!isset($categoryID) && $row['categoryID'] == $cat['categoryID']) {
                    echo "selected";
                }
                ?>>
                <?php echo htmlspecialchars($cat['categoryName']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
    if (!empty($errors['categoryID'])) {
        echo "<br><span class='error'>" . $errors['categoryID'] . "</span>";
    }
    ?>
    <br><br>
    <input type="submit" name="submit" value="Update Page">
</form>
<?php endif; ?>

<?php
require_once "footer.php";
?>

