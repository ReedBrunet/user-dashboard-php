<?php 
session_start();
require_once "header.php";
checkLogin();

$pageName = "Manage Page";

$sorting = "title ASC";

if (isset($_GET['sort'])) {
    if ($_GET['sort'] == "idasc") {
        $sorting = "ID ASC";
    } else if ($_GET['sort'] == "iddesc") {
        $sorting = "ID DESC";
    } else if ($_GET['sort'] == "titleasc") {
        $sorting = "title ASC";
    } else if ($_GET['sort'] == "titledesc") {
        $sorting = "title DESC";
    }
}

$sql = "SELECT ID, title, userID FROM pages_rabrunet ORDER BY $sorting";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll();

echo '<p><a href="pageadd.php" class="btn">Add Page</a></p>';

if (count($results) > 0): ?>
    <table class="pagemanage">
        <thead>
            <tr>
                <th>Options</th>
                <th>
                    ID
                    <a href="pagemanage.php?sort=idasc">&#9650;</a>
                    <a href="pagemanage.php?sort=iddesc">&#9660;</a>
                </th>
                <th>
                    Title
                    <a href="pagemanage.php?sort=titleasc">&#9650;</a>
                    <a href="pagemanage.php?sort=titledesc">&#9660;</a>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $row): ?>
            <tr>
                <td>
                    <a href="pageview.php?id=<?= $row['ID'] ?>">View</a>
                    <?php if ($row['userID'] == $_SESSION['ID']): ?>
                        | <a href="pageupdate.php?id=<?= $row['ID'] ?>">Update</a>
                        | <a href="pagedelete.php?id=<?= $row['ID'] ?>">Delete</a>
                    <?php endif; ?>
                </td>
                <td><?= $row['ID'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No pages have been added yet.</p>
<?php endif;

require_once "footer.php";
?>
