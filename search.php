<?php
//rabrunet

session_start();
$pageName = "Search Page";

require_once "header.php";
?>

<p>Please enter the title you want to find!</p>
<form name="mysearch" id="mysearch" method="get" action="<?php echo $currentfile; ?>">
	<label for="term">Search Title:</label>
	<input type="search" id="term" name="term" placeholder="Search for title">
	<input type="submit" id="search" name="search" value="Search">
</form>

<?php
if (isset($_GET['search'])) {
	if (empty($_GET['term'])) {
		echo "<p class='error'>No title found. Please try again!</p>";
	}else {
		$term = trim($_GET['term']) . "%";
		//SELECT FROM THE DATABASE
		$sql = "SELECT title, ID FROM pages_rabrunet WHERE title LIKE :term ORDER BY ID";
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':term', $term);
		$stmt->execute();
		$result = $stmt->fetchALL(PDO::FETCH_ASSOC);
		if (empty($result)) {
			// IF THERE ARE NO RESULTS, LET THE USER KNOW
			echo "<p class='error'>We couldn't find any results for you!" . htmlspecialchars($_GET['term']) . ". Please try again.</p>";
		}else {
			echo "<p class='success'>We found results for " . htmlspecialchars($_GET['term']) . ". Enjoy!</p>";
			
			echo "<table class='search-results'>";
			echo "<tr><th>Title</th><th>ID</th></tr>";
			foreach ($result as $row) {
				echo "<tr>";
				echo "<td>" . htmlspecialchars($row['title']) . "</td>";
				echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
				echo "</tr>";
			}
			echo "</table>";
		} // else if empty results
	} // if empty
}// if isset
require_once "footer.php";
?>
