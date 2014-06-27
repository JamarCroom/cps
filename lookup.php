<?php
	session_start();
include 'cpsInclude/httpsRedirect.inc';
	if(!isset($_SESSION['userId'])&&(!isset($_GET['parentFirstName'])&&!isset($_GET['parentLastName'])))
	{
		include_once 'cpsInclude/privilegeError.inc';
	}

	else
	{
		$parentFirstName=$_GET['parentFirstName'];
		$parentLastName=$_GET['parentLastName'];
		include 'cpsInclude/head.inc';
		try
		{
			$query = 'SELECT * FROM parentInfoTable JOIN childInfoTable ON parentInfoTable.parentId=childInfoTable.parentId 
			JOIN cpsUserTable on cpsUserTable.userId = parentInfoTable.userId WHERE parentInfoTable.parentFirstName LIKE :parentFirstName AND parentInfoTable.parentLastName LIKE :parentLastName ORDER BY childLastName, childFirstName';
			require_once 'cpsInclude/dbConnect.inc';
			$statement = $db->prepare($query);
			$statement->bindValue(':parentFirstName','%'.$parentFirstName.'%');
			$statement->bindValue(':parentLastName','%'.$parentLastName.'%');
			$statement->execute();
			$result = $statement->fetchAll();
			$statement->closeCursor();
			$showTable=true;
		}
		catch(PDOException $e)
		{
			$showTable=false;
?>
			<fieldset class="adjust">
	<?php
			include 'cpsInclude/dbError.inc';
	?>
			</fieldset>
<?php
		}

		if($showTable)
		{
?>
			<h2 style='text-align:center'>Child Lookup</h2>
<?php
			if(!empty($result))
			{
?>
				<table>
					<thead><tr><th>Child<br/>Last Name</th><th>Child<br/>First Name</th><th>Parent<br/>First Name</th><th>Parent<br/>Last Name</th><th>Agency</th><th>Name of technician<br/> who submitted</th><th>Email of <br/> key contact</th><th>Date<br/>Distributed</th></tr></thead>
	<?php
				foreach($result as $results)
				{
					echo"<tr><td>".ucfirst($results['childLastName'])."</td><td>".ucfirst($results['childFirstName'])."</td><td>".ucfirst($results['parentFirstName'])."</td><td>".ucfirst($results['parentLastName'])."</td><td>".ucwords($results['agency'])."</td><td>".ucwords($results['technicianName'])."</td><td>".$results['email']."</td><td>".$results['dateSubmission']."</td></tr>";
				}
	?>

				</table>
<?php
			}
			else
			{
				echo"<p class='error center'>Attention: There are no results to display for the search criteria provided.</p>";
			}

		}

		echo"</div></body></html>";
	}
?>