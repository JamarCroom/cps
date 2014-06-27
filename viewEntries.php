<?php
	session_start();
include 'cpsInclude/httpsRedirect.inc';


if(!isset($_SESSION['isAdmin']))
{
	include 'cpsInclude/privilegeError.inc';
}

else
{
	echo '<h3 class="center">View/Modify Distribution Reports</h3>';

	$showAgencyList=true;
	include 'cpsInclude/head.inc';



	if($showAgencyList)
	{
?>
		<form action="viewEntries.php" method="POST">
		<p>Select An Agency: <select name="agency">
			<option value=""></option>
<?php
				$distributionSites=array();
				$file = file_get_contents('distributionSites.txt');
				$distributionSites = explode(',',$file);
				natcasesort($distributionSites);

				foreach ($distributionSites as $distributionSite)
				{
					echo "<option value='".trim($distributionSite)."'>".ucwords($distributionSite)."</option>";
				}
?>
			</select>
		</p>
		<p>Select A Start Date: <input class="datepicker" name="startDate" /> Select An End Date: <input class="datepicker" name="endDate" /></p>
		<input type="submit" name="submit" value="Submit"/>
		</form>
<?php
	}
	if(isset($_POST['submit']))
	{
		require_once 'objects/Validation.php';
		$validate= new Validation();
		$agency=$validate->validateInput($_POST['agency'],'Agency');
		$startDate=$validate->validDate($_POST['startDate'],'Start Date');
		$endDate=$validate->validDate($_POST['endDate'],'End Date');
		if(!empty($startDate)&&!empty($endDate))
			$validate->dateCompare($startDate,$endDate);
		$errors = $validate->getErrorCount();
		if($errors>0)
		{
			$validate->printErrorMsgs();
		}
		else
		{
			try
			{
				require_once 'cpsInclude/dbConnect.inc';
				/*
				$reports= array();
				$db->beginTransaction();
				$sql1="SELECT userId FROM parentInfoTable WHERE department = :agency";
				$statement=$db->prepare($sql1);
				$statement->bindValue(':agency',trim(strtolower($agency)));
				 $statement->execute();
				$result = $statement->fetchAll();
	*/

				$sql="SELECT * FROM parentInfoTable WHERE agency=:agency AND dateSubmission >= :startDate AND dateSubmission <= :endDate";

				$statement=$db->prepare($sql);
				$statement->bindValue(':agency',trim(strtolower($agency)));
				$statement->bindValue(':startDate',$startDate);
				$statement->bindValue(':endDate',$endDate);
				$statement->execute();
				$reports=$statement->fetchAll();
				//$db->commit();
?>					
				<table>
				<thead><tr><th>Report Number</th><th>Agency</th><th>Date Submitted</th><th>View and Modify<br/> Approve Detail Report</th></tr></thead>
				<?php

					foreach($reports as $report)
					{
						echo "<tr><td>".$report['parentId']."</td><td>".ucwords($agency)."</td><td>".$report['dateSubmission']."</td><td><a href='viewEntriesProcess.php?parentId=".$report['parentId']."&pass=true'>View Report</a></td></tr>";
					}
				?>
			</table>
			<?php
				if(empty($reports))
				{
					echo '<p class="center success"><strong>There are no reports available using the search criteria provided.<strong/></p>';
				}
			}

			catch(PDOException $e)
			{
					include 'cpsInclude/dbError.inc';
			}
		}

		include 'cpsInclude/foot.inc';

}
}
?>
