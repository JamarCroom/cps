<?php
session_start();
include 'cpsInclude/httpsRedirect.inc';
$showForm=false;

	if(!isset($_SESSION['userId']))
	{
		include 'cpsInclude/privilegeError.inc';
	}
	else
	{
		$script="
/*
			function pickerCompatible()
			// Returns the version of Internet Explorer or a -1
			// (indicating the use of another browser).
			{
		  	  var rv = true; // Return value assumes failure.
			  if (navigator.appName == 'Microsoft Internet Explorer')
			  {
			    var ua = navigator.userAgent;
			    var re  = new RegExp('MSIE ([0-9]{1,}[\.0-9]{0,})');
			    if (re.exec(ua) != null)
			      var version = parseFloat( RegExp.$1 );
				  if(version<=8)
				  {
				  	rv=false;
				  }  
			  }

			  return rv;
			}
*/
		function pickerCompatible()
		// Returns the version of Internet Explorer or a -1
		// (indicating the use of another browser).
		{
	  	  var rv = true; // Return value assumes failure.
		  if (navigator.appName == 'Microsoft Internet Explorer')
		  {
			  	rv=false;
			 
		  }

		  return rv;
		}

					var compatible=pickerCompatible();
					if(compatible)
					{

						$( '#startDate' ).datepicker({ dateFormat: 'yy-mm-dd' });
						
						$( '#endDate' ).datepicker({ dateFormat: 'yy-mm-dd' });
					}
						$('.county').each(function()
						{
							$(this).hide();

						});

						$('.final').each(function()
						{
							$(this).hide();
						});

						$('.department').each(function()
						{
							$(this).hide();
						});
						$('#countyChoice').bind('click',function()
						{
							$('.final').each(function()
							{
								$(this).hide();
							});
							$('.final').eq(1).prop('disabled',true);
							



							$('.county').each(function()
							{
								$(this).show();

							});
							$('.county').eq(1).prop('disabled', false);
							$('.department').each(function()
							{
								$(this).hide();

							});



							$('.department').eq(1).prop('disabled', true);
							$('.state').each(function()
							{
								$(this).hide();

							});
							$('.state').eq(1).prop('disabled', true);
						});

						$('#stateChoice').bind('click',function()
						{
							$('.state').each(function()
							{
								$(this).show();

							});
							$('.state').eq(1).prop('disabled', false);
							
							$('.final').each(function()
							{
								$(this).hide();
							});
							$('.final').eq(1).prop('disabled',true);


							$('.county').each(function()
							{
								$(this).hide();

							});
							$('.county').eq(1).prop('disabled', true);
							$('.department').each(function()
							{
								$(this).hide();

							});
							$('.department').eq(1).prop('disabled', true);
						});


						$('#finalChoice').bind('click',function()
						{
							$('.final').each(function()
							{
								$(this).show();
							});
							$('.final').eq(1).prop('disabled',false);
							
							$('.county').each(function()
							{
								$(this).hide();
							});
							$('.county').eq(1).prop('disabled', true);

							$('.department').each(function()
							{
								$(this).hide();
							});
							$('.department').eq(1).prop('disabled', true);

							$('.state').each(function()
							{
								$(this).hide();
							});
							$('.state').eq(1).prop('disabled', true);
						});


						$('#departmentChoice').bind('click',function()
						{
							$('.final').each(function()
							{
								$(this).hide();
							});
							$('.final').eq(1).prop('disabled',true);

							$('.department').each(function()
							{
								$(this).show();

							});
							$('.department').eq(1).prop('disabled', false);

							$('.county').each(function()
							{
								$(this).hide();

							});
							$('.county').eq(1).prop('disabled', true);

							$('.state').each(function()
							{
								$(this).hide();

							});
							$('.state').eq(1).prop('disabled', true);
						});
				});";
			include 'cpsInclude/head.inc';


		if(isset($_POST['submit']))
		{
			require_once 'objects/Validation.php';
			$validate= new Validation();
			$startDate=$validate->validDate($_POST['startDate'],'Start Date');
			$endDate=$validate->validDate($_POST['endDate'],'End Date');
			$choice = $_POST['choice'];
			//echo "$choice";
			if(!empty($endDate)&&!empty($startDate))
				$validate->dateCompare($startDate,$endDate);
			$errors=$validate->getErrorCount();
			if($errors!=0)
			{
				$validate->printErrorMsgs();
?>


				
					<h2 class="center">Review Statistics</h2>


					<form action="statisticsprocess.php" method="POST" style="margin: 15px 0 15px 40px;">

					<p>
						<span class="state"><strong>View Stats By State:</strong></span>&nbsp;&nbsp;&nbsp;<select class="state" disabled><option value="">Statewide Stats</option></select>
						<span class="final"><strong>View Stats Final Report:</strong></span>&nbsp;&nbsp;&nbsp;<select class="final" disabled><option value="">Final Stats</option></select>
						<span class="department"><strong>View Stats By Agency:</strong></span>&nbsp;&nbsp;&nbsp;
				<select name="department" class="department">
			<option value=""></option>
	<?php
				$distributionSites=array();
				$file = file_get_contents('distributionSites.txt');
				$distributionSites = explode(',',$file);
				natcasesort($distributionSites);

			foreach ($distributionSites as $distributionSite)
			{
				echo "<option value='$distributionSite'>".ucwords($distributionSite)."</option>";
			}
	?>
			</select>
			</p>



						<span class="county"><strong>View Stats By County:</strong></span>&nbsp;&nbsp;&nbsp;
										<select class="county"name="county" disabled>
									
									<option value="Androscoggin">Androscoggin</option>
									<option value="Aroostook">Aroostook</option>
									<option value="Cumberland">Cumberland</option>
									<option value="Franklin">Franklin</option>
									<option value="Hancock">Hancock</option>
									<option value="Kennebec">Kennebec</option>
									<option value="Knox">Knox</option>
									<option value="Lincoln">Lincoln</option>
									<option value="Oxford">Oxford</option>
									<option value="Penobscot">Penobscot</option>
									<option value="Piscataquis">Piscataquis</option>
									<option value="Sagadahoc">Sagadahoc</option>
									<option value="Somerset">Somerset</option>
									<option value="Waldo">Waldo</option>
									<option value="Washington">Washington</option>
									<option value="York">York</option>
								</select>
								
								<br/>
								<br/>
						<span><strong>Select a timeframe:</strong></span>&nbsp;&nbsp;&nbsp;Start Date:&nbsp;&nbsp;<input type="text" id="startDate" name="startDate" />&nbsp;&nbsp;&nbsp;End Date:&nbsp;&nbsp;<input type="text" id="endDate" name="endDate"/>
						<br/>
						<br/>
						<span><strong>Select a filter criteria:</strong></span>&nbsp;&nbsp;&nbsp;County<input type="radio" id="countyChoice" name="choice" value="county"/>&nbsp;&nbsp;Department<input type="radio" id="departmentChoice" name="choice" value="department"/>&nbsp;&nbsp;State<input type="radio" id="stateChoice" name="choice" value="state" checked/><?php
						if(isset($_SESSION['isAdmin']))
						{
						?>
							&nbsp;&nbsp;Final Report<input type="radio" id="finalChoice" name="choice" value="final"/>
						<?php
						}
						?>
						<br/>
						<br/>
						<input type="submit"  name="submit" value="Review Report" id="submit"/>
					</p>
					</form>
<?php
			}
			else
			{
				try
				{
					require_once 'cpsInclude/dbConnect.inc';
					$query="SELECT COUNT(parentId) as parentCount FROM parentInfoTable WHERE (dateSubmission >= :startDate AND dateSubmission <= :endDate )";
					$query2="SELECT COUNT(childId) as childCount  FROM childInfoTable JOIN parentInfoTable ON parentInfoTable.parentId=childInfoTable.parentId WHERE (dateSubmission >= :startDate AND dateSubmission <= :endDate)";
					$finalQuery="SELECT COUNT(parentId) as parentCount, agency FROM parentInfoTable WHERE (dateSubmission >= :startDate AND dateSubmission <= :endDate) GROUP BY agency ORDER BY agency";
					$finalQuery2="SELECT COUNT(childId) as childCount,parentInfoTable.agency FROM childInfoTable JOIN parentInfoTable ON parentInfoTable.parentId=childInfoTable.parentId WHERE dateSubmission >= :startDate AND dateSubmission <= :endDate GROUP BY agency ORDER BY agency";

					switch($choice)
					{

						case 'department':
							$query.=" AND agency=:department";
							$statement=$db->prepare($query);
							$statement->bindValue(':department',strtolower(trim($_POST['department'])));
							$statement->bindValue(':startDate',$startDate);
							$statement->bindValue(':endDate',$endDate);
							$statement->execute();
							$parentResult=$statement->fetchAll();
							$statement->closeCursor();
							$query2.=" AND agency=:department";
							$statement2=$db->prepare($query2);
							$statement2->bindValue(':department',strtolower(trim($_POST['department'])));
							$statement2->bindValue(':startDate',$startDate);
							$statement2->bindValue(':endDate',$endDate);
							$statement2->execute();
							$childResult=$statement2->fetchAll();
							$statement2->closeCursor();
							$selection =ucwords($_POST['department']);
							/****/

						break;

						case 'county':
							$query.=" AND county=:county";
							$statement=$db->prepare($query);
							$statement->bindValue(':county',$_POST['county']);
							$statement->bindValue(':startDate',$startDate);
							$statement->bindValue(':endDate',$endDate);
							$statement->execute();
							$parentResult=$statement->fetchAll();
							$query2.=" AND county=:county";
							$statement2=$db->prepare($query2);
							$statement2->bindValue(':county',$_POST['county']);
							$statement2->bindValue(':startDate',$startDate);
							$statement2->bindValue(':endDate',$endDate);
							$statement2->execute();
							$childResult=$statement2->fetchAll();
							$statement2->closeCursor();
							$selection=$_POST['county']." County";
						break;
						case 'state':
							$statement=$db->prepare($query);
							$statement->bindValue(':startDate',$startDate);
							$statement->bindValue(':endDate',$endDate);
							$statement->execute();
							$parentResult=$statement->fetchAll();
							$statement2=$db->prepare($query2);
							$statement2->bindValue(':startDate',$startDate);
							$statement2->bindValue(':endDate',$endDate);
							$statement2->execute();
							$childResult=$statement2->fetchAll();
							$statement2->closeCursor();
							$selection ='State-wide';
						break; 
						case 'final':
							$statement=$db->prepare($finalQuery);
							$statement->bindValue(':startDate',$startDate);
							$statement->bindValue(':endDate',$endDate);
							$statement->execute();
							$finalResultParent=$statement->fetchAll();
							$statement2=$db->prepare($finalQuery2);
							$statement2->bindValue(':startDate',$startDate);
							$statement2->bindValue(':endDate',$endDate);
							$statement2->execute();
							$finalResultChild=$statement2->fetchAll();							
							$selection ='Final';
						break; 

					}
					$showForm=true;
				}
				catch(PDOException $e)
				{
					$showForm=false;
					include 'cpsInclude/dbError.inc';

				}

			}
		}
		else
		{
			echo'<p style="center errors"><strong>Error:</strong><br/>You have not submitted data for processing. Please go <a href="stats.php">back</a> and try again.</p>';
			$showForm=false;
		}

		if($showForm)
		{	echo "<div style='margin: 10px 0px 20px 30px;'>";
			echo "<h2 class='center'>Displaying $selection Report Statistics from $startDate to $endDate</h2>";
			if($choice=='department'||$choice=='county'||$choice=='state')
			{
				foreach($parentResult as $parentResults)
				{
					echo"<p>Number of parent/guardian applicants: ".$parentResults['parentCount']."</p>";

				}

				foreach($childResult as $childResults)
				{
					echo"<p>Number of seats distributed: ".$childResults['childCount']."</p>";
					echo"<p>Number of children who received seats: ".$childResults['childCount']."</p>";
				}
			}

				
				if($choice=='final')
				{
					$agency =file_get_contents('distributionSites.txt');
					$agency = explode(',', $agency);
					
					foreach($agency as $agencies)
					{
						
						foreach($finalResultParent as $finalResultParents)
						{


							if(trim(strtolower($finalResultParents['agency']))==trim(strtolower($agencies)))
							{
								echo "<h3>".ucwords($agencies)."</h3>";
								echo"<p>Number of parent/guardian applicants: ".$finalResultParents['parentCount']."</p>";
								break;	
							}
						}

						foreach($finalResultChild as $finalResultChildren)
						{
							if(trim(strtolower($finalResultChildren['agency']))==trim(strtolower($agencies)))
							{
								echo"<p>Number of seats distributed: ".$finalResultChildren['childCount']."</p>";
								echo"<p>Number of children who received seats: ".$finalResultChildren['childCount']."</p><br/><br/>";

								break;
							}	
						}
					




					}
					
				}

				echo"</div>";


			}
			else
			{
				echo"<p class='errors'>There are no results to display for the criteria selected</p>";
			}


include 'cpsInclude/foot.inc';
		}


		