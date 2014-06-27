<?php
session_start();
include 'cpsInclude/httpsRedirect.inc';
if (!isset($_SESSION['isAdmin']))
{
	include_once 'cpsInclude/privilegeError.inc';
}
else
{
	include_once 'cpsInclude/head.inc';
	
	echo '<h2 class="center">Update CPS User Information</h2>';
	$personForm= false;
	$lookup = true;
	$successForm = false;
	$showForm=false;
	if(isset($_POST['lookup']))
	{
		
		
		$agency = $_POST['agency'];
		try
		{
			require_once 'cpsInclude/dbConnect.inc';
			$sql="SELECT * FROM cpsUserTable WHERE department = :agency";
			$statement=$db->prepare($sql);
			$statement->bindValue(':agency',$agency);
			$statement->execute();
			$result=$statement->fetchAll();
			$statement->closeCursor();
			$lookup=true;
			$personForm=true;
		}
		catch(PDOException $e)
		{
				include 'cpsInclude/dbError.inc';
		}
	}

	if(isset($_POST['personForm']))
	{
		$userId = $_POST['userId'];
		try
		{
			require_once 'cpsInclude/dbConnect.inc';
			$sql="SELECT * FROM cpsUserTable WHERE userId= :userId";
			$statement=$db->prepare($sql);
			$statement->bindValue(':userId',$userId);
			$statement->execute();
			$result=$statement->fetchAll();
			$statement->closeCursor();
			foreach ($result as $results) 
			{
				$firstName=$results['firstName'];
				$lastName=$results['lastName'];
				$email=$results['email'];
				$userId=$results['userId'];
				$password[]=$results['password'];
				$password[] = $results['password'];
				$userName = $results['userName'];
				//$agency = $results['department'];
				$originalPass = $results['password'];
			}
			$lookup=false;
			$showForm=true;
			


		}


		catch(PDOException $e)
		{
			include 'cpsInclude/dbError.inc';
		}

	}


		
	if(isset($_POST['submit']))
	{
		require_once 'objects/Validation.php';
		$validate = new Validation();
		$password = $validate->confirmPass($_POST['password'],$_POST['confirmPassword']);	
		$firstName = $validate->validAlpha($_POST['firstName'],'First Name');
		$lastName = $validate->validAlpha($_POST['lastName'],'Last Name');
		//$agency = $validate->validAlpha($_POST['agency'],'Agency');
		$email= $validate->validEmail($_POST['email']);
		$userName = $validate->validateInput($_POST['userName'],'Username');
		$originalPass = $_POST['hiddenPass'];
		$userId = $_POST['userId'];
		$errors = $validate->getErrorCount();
		if($errors==0)
		{
			if($originalPass != $password[0])
				$password[0]=crypt($password[0],'%20This%20is%20the%20salt%20I%20use%20');		
			try
			{
	
				

				$query = 'UPDATE cpsUserTable SET firstName=:firstName, lastName =:lastName, email=:email,userName=:userName,
				password = :password WHERE userId=:userId';
				require_once '../includes/dbConnect.inc';
				$statement=$db->prepare($query);
				$statement->bindValue(':password', $password[0]);
				$statement->bindValue(':firstName',$firstName);
				$statement->bindValue(':lastName',$lastName);
				$statement->bindValue(':userName',$userName);
				$statement->bindValue(':email',$email);
				$statement->bindValue(':userId',$userId);
				$statement->execute();
				$statement->closeCursor();
				$lookup = false;
				$successForm = true;

			}

			catch(PDOException $e)
			{

				include_once 'cpsInclude/dbError.inc';

				$showForm=false;


			}

		}
		else
		{
			$validate->printErrorMsgs();
			$showForm=true;
			$lookup=false;
		}

	}
	if($lookup)
	{
		$distributionSites=array();
		$file = file_get_contents('distributionSites.txt');
		$distributionSites = explode(',',$file);
		natcasesort($distributionSites);
?>
		
		<form action="updateCPSUser.php" method="POST">
		<p>Select an Agency: <select name= "agency">
<?php
		foreach ($distributionSites as $distributionSite)
		{
			echo "<option value='".trim($distributionSite)."'>".ucwords(trim($distributionSite))."</option>";
		}
?>
	</select></p>
		<input type="submit" name="lookup" value="Submit" />
	</form>
<?php
	}



	if($showForm)
	{
?>
		<form action="updateCPSUser.php" method="POST">

		<p>First Name of Key Contact: <input type="text" name="firstName" <?php if (isset($firstName)) echo "value='$firstName'"; ?>/></p>
		<p>Last Name of Key Contact: <input type="text" name="lastName" <?php if (isset($lastName)) echo "value='$lastName'"; ?>/></p>

		<p>Username:<input type="text" name="userName" <?php if (isset($userName)) echo "value='$userName'"; ?>/> </p>

		<p>Key Contact Email:<input type="text" name="email" <?php if (isset($email)) echo "value='$email'"; ?>/> </p>
		
		<p>Password:<input type="password" name="password" <?php if (isset($password[0])) echo "value='".$password[0]."'"; ?>/></p>
		<p>Confirm Password: <input type="password" name="confirmPassword" <?php if (isset($password[1])) echo "value='".$password[1]."'"; ?>/></p>
		<input type="hidden" name="userId" value="<?php echo $userId ?>" />
		<input type="hidden" name="hiddenPass" value ="<?php echo $originalPass ?>" />
		<p><input type='submit' name='submit' value="Submit"/></p>	
		</form>
<?php
	}

	if ($personForm) 
	{
?>
		
			<table>
				<thead><tr><th>First Name</th><th>Last Name</th><th>Department</th><th>Update<br/>User</th><thead></thead></tr></thead>
<?php
	foreach ($result as $results) 
	{
		echo '<tr><td>'.$results['firstName'].'</td><td>'.$results['lastName'].'</td><td>'.$results['department'].'</td><td><form action="updateCPSUser.php" method="POST"><input type="hidden" name="userId" value="'.$results['userId'].'"><input type="submit" name="personForm" value="Update"/></form></td></tr>';
	}

?>
		</table>
		
<?php
	}



	if($successForm)
	{
?>
		
		<h2 class="center success">Success</h2>
		<p class="success">User information has been successfully updated.</p>
		
<?php
	}
?>





</div>
</body>
</html>

<?php
}
?>