<?php
if(isset($_SESSION['userId']))
{
?>
		<nav><ul><li><a href="index.php">Log-in</a></li><li><a href="logout.php">Log-out</a></li>
	<li><a href="CPS.php">Add A Report</a></li>


<?php
	if(isset($_SESSION['isAdmin']))
		echo'<li><a href="admin.php">Admin Menu</a></li>';
?>

	<li><a href="stats.php">View Statistics</a></li></ul></nav>



<?php
}


?>