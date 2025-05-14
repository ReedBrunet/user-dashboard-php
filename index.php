<?php
//rabrunet
$pageName = "Home";
include "header.php";
?>

<?php
// fetch content titles from pages_rabrunet
$sql = "SELECT ID, title FROM pages_rabrunet ORDER BY title ASC";
$stmt = $pdo->query($sql);
?>

<h1>Final Project For CSCI303</h1>
<p>Welcome to my page! please register so you can add a new page!
 After adding a new page you are able to view, update or delete it.</p>

<ul>
<?php while ($row = $stmt->fetch()): ?>
	<li>
		<a href="pageview.php?id=<?= htmlspecialchars($row['ID']) ?>">
			<?= htmlspecialchars($row['title']) ?>
		</a>
	</li>
<?php endwhile; ?>
</ul>

<?php 
require_once "footer.php";
?>