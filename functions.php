<?php
// Check for duplicates in the database
function check_duplicates($pdo, $sql, $field) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':field', $field);
        $stmt->execute();
        return $stmt->fetch(); // returns row if duplicate found
    } catch (PDOException $e) {
        echo "<p class='error'>This email has been taken." . $e->getMessage() . "</p>";
        return false;
    }
}
?>

<?php
function checkLogin()
{
    if (!isset($_SESSION['ID'])) {
        echo "<p class='error'>This page requires authentication. Please log in to view details.</p>";
        require_once "footer.php";
        exit();
    }
}

function checkAdmin()
{
    if (!isset($_SESSION['status']) || $_SESSION['status'] != 1) {
        echo "<p class='error'>This page requires administrative privileges. Please log in as an admin to continue.</p>";
        require_once "footer.php";
        exit();
    }
}
?>