<?php
session_start();
require_once "header.php";
checkLogin();
checkAdmin();

$pageName = "Manage User Status";
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['userID']) && isset($_POST['newStatus'])) {
    $userID = $_POST['userID'];
    $newStatus = $_POST['newStatus'];

    $sql = "UPDATE users_rabrunet SET status = :status WHERE ID = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':status', $newStatus);
    $stmt->bindValue(':id', $userID);
    $stmt->execute();

    $success = "User status successfully updated.";
}

$sql = "SELECT ID, fname, lname, email, status FROM users_rabrunet WHERE ID != :id ORDER BY lname, fname";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $_SESSION['ID']);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Manage User Status</h2>

<?php
if (!empty($success)) {
    echo "<p class='success'>$success</p>";
}

if (!empty($errors)) {
    echo "<p class='error'>" . implode("<br>", $errors) . "</p>";
}
?>

<?php if (count($users) > 0): ?>
    <table>
        <tr>
            <th>User</th>
            <th>Current Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['fname'] . " " . $user['lname'] . " (" . $user['email'] . ")"); ?></td>
                <td><?php echo $user['status'] == 1 ? "Admin" : "User"; ?></td>
                <td>
                    <form action="userstatus.php" method="post" style="display:inline;">
                        <input type="hidden" name="userID" value="<?php echo $user['ID']; ?>">
                        <?php if ($user['status'] == 0): ?>
                            <input type="hidden" name="newStatus" value="1">
                            <input type="submit" value="Promote to Admin">
                        <?php else: ?>
                            <input type="hidden" name="newStatus" value="0">
                            <input type="submit" value="Demote to User">
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No other users available to manage.</p>
<?php endif; ?>

<?php
require_once "footer.php";
?>

