<?php
session_start();
include 'cpsInclude/httpsRedirect.inc';
if (!isset($_SESSION['isAdmin']))
{
	include_once 'cpsInclude/privilegeError.inc';

}
else
{
		
	$showForm=true;
	
	include 'cpsInclude/head.inc';
	if(isset($_POST['submit']))
	{
		require_once 'objects/Validation.php';
		$validate= new Validation();
		$firstName = $validate->validAlpha($_POST['firstName'],'First Name');
		$lastName= $validate->validAlpha($_POST['lastName'],'Last Name');
		$agency= $validate->validateInput($_POST['agency'],'Agency');
		$userName = $validate->validateInput($_POST['userName'],'Username');
		$email= $validate->validEmail($_POST['email']);
		$password = $validate->confirmPass($_POST['password'],$_POST['confirmPassword']);
		$errorCount =$validate->getErrorCount();
		if($errorCount!=0)
		{
			$validate->printErrorMsgs();
		} 
		else
		{
			try
			{
				include 'cpsInclude/dbConnect.inc';
			
				$queryString='SELECT userName, department FROM cpsUserTable WHERE userName =:userName OR department =:agency';
				$statement=$db->prepare($queryString);
				$statement->bindValue(':userName',$userName);
				$statement->bindValue(':agency',$agency);
				
				$statement->execute();
				$result = $statement->fetchAll();
				foreach($result as $results)
				{
					$confirmUserName = $results['userName'];
					$confirmAgency = $results['department'];
				}
				$statement->closeCursor();
				if(!isset($confirmUserName))
					$confirmUserName=0;
				if(!isset($confirmAgency))
					$confirmAgency=0;
				if(($confirmUserName!==$userName)&&($agency!==$confirmAgency))
				{

					$query ='INSERT INTO cpsUserTable (firstName,lastName,userName, email,password,department, isAdmin)
					VALUES
					(:firstName,:lastName,:userName,:email,:password,:department, :isAdmin)';
					$statement=$db->prepare($query);
					$statement->bindValue(':firstName',$firstName);
					$statement->bindValue(':lastName',$lastName);
					$statement->bindValue(':email',$email);
					$statement->bindValue(':department',strtolower($agency));
					$statement->bindValue(':password',crypt($password[0],'%20This%20is%20the%20salt%20I%20use'));
					$statement->bindValue(':isAdmin','n');
					$statement->bindValue(':userName',$userName);
					$statement->execute();
					$statement->closeCursor();
					/*
					$file = file_get_contents('distributionSites.txt');
					$agencies = explode(',', $file);
					$agencies[] = trim($agency);
					$file = implode(',', $agencies);
					file_put_contents('distributionSites.txt', $file);
					*/
					?>

					<h2 class="center">Success!</h2>
					<p class="center">You have successfully entered a new user into the database.</p>
					<?php
					$showForm = false;
				}
				else
				{
					echo '<p class="adjust error">Error: This is department or username has already been registered. Please try again.</p>';
				}
				
			}
			catch(PDOException $e)
			{
				$showForm =false;
				include 'cpsInclude/dbError.inc';
			}

		}
	}
	if($showForm)
	{
	?>
		<h2 class="center">Add a New User</h2>
		<form action="addCPSUser.php" method="POST">
		
		<p>First Name of Key Contact: <input type="text" name="firstName" <?php if (isset($firstName)) echo "value='$firstName'"; ?>/></p>
		<p>Last Name of Key Contact: <input type="text" name="lastName" <?php if (isset($lastName)) echo "value='$lastName'"; ?>/></p>
		<?php
			$distributionSites=array();
			$file = file_get_contents('distributionSites.txt');
			$distributionSites = explode(',',$file);
			natcasesort($distributionSites);
?>
		<p>Agency:<select name="agency">
		<option value=""></option>
<?php
		foreach ($distributionSites as $distributionSite)
		{
			echo "<option value='".trim($distributionSite)."' ";
			if(isset($agency))
			{
				if(trim($distributionSite) == $agency)
					echo 'selected';
			}
			echo">$distributionSite</option>";
		}
?>
		</select>
		</p>
		<p>Username:<input type="text" name="userName" <?php if (isset($userName)) echo "value='$userName'"; ?>/> </p>

		<p>Key Contact Email:<input type="text" name="email" <?php if (isset($email)) echo "value='$email'"; ?>/> </p>
		<p>Password:<input type="password" name="password" <?php if (isset($password[0])) echo "value='".$password[0]."'"; ?>/></p>
		<p>Confirm Password: <input type="password" name="confirmPassword" <?php if (isset($password[1])) echo "value='".$password[1]."'"; ?>/></p>
		<p><input type="submit" name="submit" /></p>
		
		</form>
	<?php
	}
?>
	</div>
	</body>
	</html>
<?php
}
?>
