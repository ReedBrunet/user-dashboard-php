<?php
session_start();
require_once "header.php";
checkLogin();

$pageName = "Add Page";
$showForm = true;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = trim($_POST['title']);
    $details = trim($_POST['details']);
    $categoryID = trim($_POST['categoryID']);
    $uploadedFilename = null;

    if (empty($title)) {
        $errors['title'] = "Title is required.";
    } else {
        $sql = "SELECT title FROM pages_rabrunet WHERE title = :field";
        $dup = check_duplicates($pdo, $sql, $title);
        if ($dup) {
            $errors['title'] = "This title already exists.";
        }
    }

    if (empty($details)) {
        $errors['details'] = "Details are required.";
    }

    if (empty($categoryID)) {
        $errors['categoryID'] = "Category selection is required.";
    }

        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
        if (in_array($mimeType, $allowedTypes)) {
            $pinfo = pathinfo($_FILES['myfile']['name']);
            $extension = strtolower($pinfo['extension']);
            $uploadedFilename = strtolower("rabrunet" . time() . "." . $extension);
            $uploadPath = "uploads/" . $uploadedFilename;
            move_uploaded_file($_FILES['myfile']['tmp_name'], $uploadPath);
        }
    }

    if (empty($errors)) {
        $sql = "INSERT INTO pages_rabrunet (userID, title, details, created, updated, filename, categoryID)
                VALUES (:userID, :title, :details, :created, :updated, :filename, :categoryID)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':userID', $_SESSION['ID']);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':details', $details);
        $now = date("Y-m-d H:i:s");
        $stmt->bindValue(':created', $now);
        $stmt->bindValue(':updated', $now);
        $stmt->bindValue(':filename', $uploadedFilename);
        $stmt->bindValue(':categoryID', $categoryID);
        $stmt->execute();

        echo "<p class='success'>Page content added successfully!</p>";
        $showForm = false;
    } else {
        echo "<p class='error'>Please fix the following errors:</p>";
    }
}

$sql = "SELECT * FROM rabrunet_categories ORDER BY categoryName ASC";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll();
?>

<?php if ($showForm): ?>
<form name="pageadd" id="pageadd" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" maxlength="255" value="<?php if (isset($title)) echo htmlspecialchars($title); ?>">
    <?php if (isset($errors['title'])) echo "<span class='error'>{$errors['title']}</span>"; ?>
    <br>

    <label for="details">Details:</label><br>
    <?php if (isset($errors['details'])) echo "<span class='error'>{$errors['details']}</span>"; ?>
    <textarea name="details" id="details"><?php if (isset($details)) echo htmlspecialchars($details); ?></textarea>
    <br>

    <label for="categoryID">Category:</label>
    <select name="categoryID" id="categoryID">
        <option value="">Choose a category</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['categoryID']; ?>" <?php if (isset($categoryID) && $categoryID == $cat['categoryID']) echo "selected"; ?>>
                <?php echo htmlspecialchars($cat['categoryName']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (isset($errors['categoryID'])) echo "<span class='error'>{$errors['categoryID']}</span>"; ?>
    <br><br>

    <label for="myfile">Optional File Upload (PDF, JPG, PNG, GIF):</label><br>
    <input type="file" name="myfile" id="myfile">
    <br><br>

    <input type="submit" name="submit" value="Submit">
</form>
<?php endif; ?>

<?php
require_once "footer.php";
?>
