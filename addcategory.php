<?php
// rabrunet

session_start();
require_once "header.php";

checkLogin();
checkAdmin();

$pageName = "Add Category";
$errors = [];
$category = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $category = trim($_POST['category'] ?? '');

    if (empty($category)) {
        $errors['category'] = "Category name is required.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO rabrunet_categories (category) VALUES (:category)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':category', $category);
        $stmt->execute();

        header("Location: index.php");
        exit();
    }
}
?>

<h2>Add New Category</h2>

<form method="post" action="addcategory.php">
    <label for="category">Category Name:</label>
    <input type="text" name="category" id="category" value="<?php echo htmlspecialchars($category); ?>">
    <?php if (isset($errors['category'])) echo "<span class='error'>&#9888; {$errors['category']}</span>"; ?>
    <br><br>
    <input type="submit" value="Add Category">
</form>

<?php require_once "footer.php"; ?>
