<?php
	ob_start();
	session_start();
	include 'cpsInclude/httpsRedirect.inc';
	$style="
	#signin
	{
		background: #F1F1F1;
		border-radius: 5px;
		width: 60%;
		margin: 100px auto;
		-moz-box-shadow:    3px 1px 3px 4px rgb(95,95,95);
  		-webkit-box-shadow: 3px 1px 3px 4px rgb(95,95,95);
 	 	box-shadow:         2px 1px 2px 3px rgb(95,95,95);
 	 	padding: 20px 0;
	}

	.inputs
	{
		text-align:center;
		font-weight: bold;
		font-size: 1.3em;
		border-radius: 5px;
	}

	#button
	{
		text-align:center;

	}

	#submit
	{
		width: 20%;
	}

	#heading
	{
		text-align:center;
		font-size: 1.5em;
	}
	";


?>
<!DOCTYPE html>
<!--CPS-->
<html>


<head>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="../jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.js"></script>
<link href='style/cpsStyle.css' rel='stylesheet' type='text/css'/>
<link href='https://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'/>
<link href='https://fonts.googleapis.com/css?family=Paytone+One' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="../jquery-ui-1.10.3.custom/css/overcast/jquery-ui-1.10.3.custom.css" />
<style type="text/css">
<?php
if(isset($style))
{
	echo $style;
}
?>
</style>


<script type="text/javascript">
$(function()
{
<?php
	if(isset($script))
	{
		echo $script;
	}

?>
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
              // alert(compatible);
		if(compatible)
		{
			$( '#startDate' ).datepicker({ dateFormat: 'yy-mm-dd' });
			
			$( '#endDate' ).datepicker({ dateFormat: 'yy-mm-dd' });
		}


		if(compatible)
		{
			$(".datepicker").each(function()
			{
				$(this).datepicker({ dateFormat: 'yy-mm-dd' });
			});
		}

		$('#addChildSt').click(function(){
			$('.childAndSeatInfo:last').after('<div class="childAndSeatInfo"><div class="childInformation" style="margin-top: 35px;"><h4>Recipient&#39;s Information</h4><p>Child&#39;s First Name <input type="text" name="childFirstName[]"/></p><p>Child&#39;s Last Name <input type="text" name="childLastName[]"/></p><p>Child&#39;s Age (Note: if child has not yet been born enter 0) <input type="text" name="childsAge[]"/></p><p>Child&#39;s Date of Birth/Due Date <input type="text" class="datepicker" name="childDOB[]"/></p><p>Child&#39;s Weight (Note: if child has not yet been born enter 0) <input type="text" name="childWeight[]" /></p><p>Child&#39;s Height (Note: if child has not yet been born enter 0)<input type="text" name="childHeight[]" /></p></div><div class="safetySeatInfo"><h4>Safety Seat Information</h4><p>Car Seat Manufacturer:<select name="manufacturerList[]" class="safetySeatInfo"><option value=""></option><option value="Evenflo Embrace">Evenflo Embrace-Infant Seat</option><option value="Evenflo Titan">Evenflo Titan-Convertible Seat</option><option value="Evenflo Secure Kid">Evenflo Secure Kid-Combination Seat</option><option value="Evenflo">Evenflo-Highback to No Back Booster Seat</option></select></p><p>Car Seat Manufacturer:(if different from the list above) <input type="text" name="manufacturerText[]" /></p><p>Car Seat Model Name:(if different from the list above)<input type="text"  name="carSeatNme[]"/></p><p>Car Seat Manufacture Date: <input type="text" class="datepicker" name="carSeatDte[]" /></p><p>Car Seat Model/Serial Number: <input type="text" class="safetySeatInfo" name="carSeatSerialNum[]" /></p></div></div>');
			if(compatible)
			{
				$(".datepicker").each(function()
				{
				$(this).datepicker({ dateFormat: 'yy-mm-dd' });
				});
			}

			return false;
		});
});
</script>

</head>
<body>

<div id="wrapper">
	<div id="header"><img src="pics/bhs_logo.jpg" id="logo" style="vertical-align: middle; border-radius: 5px;"/><span id="logoWording">Bureau of Highway Safety Web Applications Portal</span></div>
		<form id="signin" action="index.php" method="POST">
<?php
		if(isset($_POST['submit']))
		{
			$username = $_POST['username'];
			$password = $_POST['password'];
			require_once './objects/Validation.php';
			$validation = new Validation();
			$username =$validation->validateInput($username,'Username');
			$password =$validation->validateInput($password,'Password');
			
			
			$errorNum=$validation->getErrorCount();
			if($errorNum==0)
			{
				try
				{

					$password1 = crypt($password,'%20This%20is%20the%20salt%20I%20use%20');
					require_once 'cpsInclude/dbConnect.inc';
					$sql = 'SELECT * FROM cpsUserTable WHERE userName =:userName AND password = :password';
					$statement=$db->prepare($sql);
					$statement->bindValue(':userName', $username);
					$statement->bindValue(':password',$password1);
					$statement->execute();
					$results = $statement->fetchAll();
					$statement->closeCursor();
					if(empty($results) )
					{
						echo'<p class="error">Error:Username and password combination are invalid.</p>';
					}
					else
					{
						foreach($results as $result)
						{
							$_SESSION['userId'] = $result['userId'];
							$_SESSION['agency'] = trim(strtolower($result['department']));
							if($result['isAdmin']=='y')
								$_SESSION['isAdmin'] = $result['isAdmin'];

						}	


					
						session_regenerate_id();
						header('Location:CPS.php');
					}

				}

				catch(PDOException $e)
				{
					echo "<p class='error'>Error:Unable to connect to the database at this time: ".$e->getMessage()."</p>";
				}

			}
			else
			{
				$validation->printErrorMsgs();
			}


		}

	
?>

		
			<h3 id="heading">User Authentication</h3>
			
			<p class="inputs">Username:&nbsp;&nbsp;<input name="username" id="username" type="text"
				<?php
				if (isset($username))
				{
					echo"value='".$username."'";
				}
				?>
				/></p>

			<p class="inputs">Password:&nbsp;&nbsp;<input name="password" id="password" type="password"
				<?php
				if (isset($password))
				{
					echo"value='".$password."'";
				}
				?>
			/></p>

			<p id="button"><input id="submit" name="submit" type="submit" value="Submit"/></p>
			



		</form>
</div>
</body>
</html>
<?php

ob_flush();
?>