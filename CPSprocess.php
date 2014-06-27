<?php
session_start();
include 'cpsInclude/httpsRedirect.inc';
	
if(!isset($_SESSION['userId']))
{
	include 'cpsInclude/privilegeError.inc';

}



else if(!isset($_POST['submit']))
{
	include 'cpsInclude/head.inc';
?>
	

		<p class="error ">Errors: You have not submitted any data for processing</p>
	
<?php

}

else
{
$script="
	$('#lookup_parent').bind('click',function()
	{
		var parentFname=$('#appFnme').val();
		var parentLname=$('#appLstme').val();
		if(parentLname==''&& parentFname=='')
			alert('Parent first and last name fields must be complete in order to lookup the parent.');
		else
		{
			window.open('lookup.php?parentFirstName='+parentFname+'&parentLastName='+parentLname+'','','width=900,height=700, scrollbars=yes,resizable=yes');
		}

		return false;

	});




";
	include 'cpsInclude/head.inc';
	?>
	<h2 style ="text-align:center; margin-top: 3px;">Child Passenger Safety Reporting Form</h2>
<?php
	$userId=$_SESSION['userId'];
	$showForm=true;
	require_once 'objects/Validation.php';
	$validate= new Validation();
	$parentFname = strtolower($validate->validAlpha($_POST['prnt1firstName'],'First Name'));
	$parentLname = strtolower($validate->validAlpha($_POST['prnt1LastName'],'Last Name'));
	$dateDistributed=$validate->validDate($_POST['dateDistributed'],'Date of distribution');
	$technicianName=strtolower($validate->validAlpha($_POST['technicianName'],'Technician Name'));
	$otherParentFname=strtolower($validate->validAlpha($_POST['prntFnme'], 'First name of other parent/guardian'));
	$otherParentLname=strtolower($validate->validAlpha($_POST['prntLstme'], 'Last name of other parent/guardian'));
	$address = $validate->validateInput($_POST['address'],'Address');
	$city =strtolower($validate->validAlpha($_POST['city'], 'City'));
	$zipcode = $validate->validNum($_POST['zipcode'],'Zipcode');
	$county = $validate->validateInput($_POST['county'],'County');
	$incomeEligible = strtolower($validate->validateInput($_POST['incmeEligType'],'Income Eligibility Verification'));
	$maineResidency = strtolower($validate->validateInput($_POST['maineRes'],'Maine Residency Verification'));
	$agency = strtolower(trim($validate->validateInput($_POST['agency'],'Agency')));

	$childFirstName = $_POST['childFirstName'];
	foreach($childFirstName as $key=>$value)
	{
		$childFirstName[$key] = strtolower($validate->validAlpha($childFirstName[$key],"Child's First Name"));
	}

	$childLastName= $_POST['childLastName'];
	foreach ($childLastName as $key=>$value) 
	{
		$childLastName[$key] = strtolower($validate->validAlpha($childLastName[$key],"Child's Last Name"));
	}

	$childsAge = $_POST['childsAge'];
	foreach ($childsAge as $key=>$value) 
	{
		$childsAge[$key] = $validate->validNum($childsAge[$key],"Child's Age");
	}

	$childDOB = $_POST['childDOB'];
	foreach ($childDOB as $key=>$value) 
	{
		$childDOB[$key]=$validate->validDate($childDOB[$key],"Child's Date of Birth");
	}
	if(!isset($_POST['present']))
	{
		$present[]='';
	}	
	else
	{
		$present = $_POST['present'];
	}

	
	foreach ($present as $key=>$value)
	{
			$present[$key]=$validate->validateInput($present[$key],"Was child present...");
	}
	if(!isset($_POST['childAgeUnit']))
	{
		$childAgeUnit[]='';
	}	
	else
	{
		$childAgeUnit = $_POST['childAgeUnit'];
	}	

	foreach ($childAgeUnit as $key=>$value)
	{
			$childAgeUnit[$key]=$validate->validateInput($childAgeUnit[$key],"Child's Age -- Age, Months, or Days");
	}




	$childWeight = $_POST['childWeight'];
	foreach ($childWeight as $key=>$value) 
	{
		$childWeight[$key] = $validate->validNum($childWeight[$key],"Child's Weight");
	}

	$childHeight = $_POST['childHeight'];
	foreach ($childHeight as $key=>$value) 
	{
		$childHeight[$key] = $validate->validNum($childHeight[$key],"Child's Height");
	}

	$manufacturerList = $_POST['manufacturerList'];
	$manufacturerCount = count($manufacturerList);
	
	$manufacturerText = $_POST['manufacturerText'];
	$carSeatNme = $_POST['carSeatNme'];

	for($i=0; $i<$manufacturerCount;$i++)
	{
		if(empty($manufacturerList[$i])&&empty($manufacturerText[$i])&& empty($carSeatNme[$i]))
		{
			$manufacturerList[$i] = strtolower($validate->validateInput($manufacturerList[$key],"Select from list: Car Seat Manufacturer"));
			$manufacturerText[$i]=strtolower($validate->validAlphaNum($manufacturerText[$i],"Car Seat Manufacturer: (if different from the list above)"));
			$carSeatNme[$i]=strtolower($validate->validAlphaNum($carSeatNme[$i],"Car Seat Model Name: (if different from the list above)"));
		}
		else if(empty($manufacturerList[$i])&& (!empty($manufacturerText[$i])||!empty($carSeatNme[$i])))
		{
			$manufacturerText[$i]= strtolower($validate->validAlphaNum($manufacturerText[$i],"Car Seat Manufacturer: (if different from the list above)"));
			$carSeatNme[$i]= strtolower($validate->validAlphaNum($carSeatNme[$i],"Car Seat Model Name: (if different from the list above)"));
		}
		else if(!empty($manufacturerList[$i])&& (!empty($manufacturerText[$i])||!empty($carSeatNme[$i])))
		{
			$validate->incErrorCount();
			$validate->addErrorMsgs("<p class='error'>Error:Either the 'Manufacturer's list' field OR the 'Car Seat Manufacturer' and 'Car Seat Model Name' fields must be completed.</p>");
		}

	}

	$carSeatDte = $_POST['carSeatDte'];
	foreach ($carSeatDte as $key=>$value) 
	{
		$carSeatDte[$key] = $validate->validDate($carSeatDte[$key],"Car Seat Manufacturer Date");
	}

	$carSeatSerialNum = $_POST['carSeatSerialNum'];
	foreach ($carSeatSerialNum as $key=>$value) 
	{
		$carSeatSerialNum[$i]= $validate->validAlphaNum($carSeatSerialNum[$key],"Car Seat Model/Serial Number");
	}

	if(isset($_POST['confirm']))
		$confirm =$_POST['confirm'];
	else
		$confirm = 0;
	$confirm = $validate->validateInput($confirm, 'Confirmation');
	
	$errors=$validate->getErrorCount();

	if($errors>0)
	{
?>
		<fieldset class="adjust errors">
		<h2>Errors:</h2>
<?php
			$validate->printErrorMsgs();
?>
		</fieldset>
<?php

	}
	else
	{
		$showForm=false;
		try
		{
			require_once 'cpsInclude/dbConnect.inc';
			$db->beginTransaction();
			//$datedistributed = date('Y-m-d');
			$query="INSERT INTO parentInfoTable (parentId, userId, technicianName, dateSubmission,agency, parentFirstName, parentLastName, otherParentFirstName, otherParentLastName, address, city, zipcode, county, incomeEligible, maineResidency)
				VALUES
				(:parentId, :userId,:technicianName, :dateSubmission ,:agency,:parentFirstName, :parentLastName, :otherParentFirstName, :otherParentLastName, 
					:address, :city, :zipcode, :county, :incomeEligible, :maineResidency)";
			$statement=$db->prepare($query);
			$statement->bindValue(':parentId','');
			$statement->bindValue(':agency',$agency);
			$statement->bindValue(':userId',$userId);
			$statement->bindValue(':technicianName',$technicianName);
			$statement->bindValue(':dateSubmission',$dateDistributed);
			$statement->bindValue(':parentLastName',$parentLname);
			$statement->bindValue(':otherParentFirstName',$otherParentFname);
			$statement->bindValue(':otherParentLastName',$otherParentLname);
			$statement->bindValue(':address',$address);
			$statement->bindValue(':city',$city);
			$statement->bindValue(':zipcode',$zipcode);
			$statement->bindValue(':county',$county);
			$statement->bindValue(':parentFirstName',$parentFname);
			$statement->bindValue(':incomeEligible',$incomeEligible);
			$statement->bindValue(':maineResidency',$maineResidency);
			$statement->execute();
			$parentId=$db->lastInsertId();

			$query2="INSERT INTO childInfoTable (childId, parentId, childPresent, childFirstName, childLastName, childAge, childAgeUnit, childDOB, childWeight, childHeight, carSeatManufacturerList, carSeatManufacturer, carSeatModelName, carSeatManufactureDate, carSeatSerialModel)
			VALUES
			(:childId, :parentId,:childPresent, :childFirstName, :childLastName, :childAge, :childAgeUnit,:childDOB, 
			:childWeight, :childHeight, :carSeatManufacturerList, 
			:carSeatManufacturer, :carSeatModelName, :carSeatManufactureDate, :carSeatSerialModel)";
			
			$statement=$db->prepare($query2);
			foreach($childFirstName as $key=>$value)
			{
				$statement->bindValue(':childId','');
				$statement->bindValue(':parentId',$parentId);
				$statement->bindValue(':childPresent',$present[$key]);
				$statement->bindValue(':childFirstName',$childFirstName[$key]);
				$statement->bindValue(':childLastName',$childLastName[$key]);
				$statement->bindValue(':childAge',$childsAge[$key]);
				$statement->bindValue(':childAgeUnit',$childAgeUnit[$key]);
				$statement->bindValue(':childDOB',$childDOB[$key]);
				$statement->bindValue(':childWeight',$childWeight[$key]);
				$statement->bindValue(':childHeight',$childHeight[$key]);
				$statement->bindValue(':carSeatManufacturerList',$manufacturerList[$key]);
				$statement->bindValue(':carSeatManufacturer',$manufacturerText[$key]);
				$statement->bindValue(':carSeatModelName',$carSeatNme[$key]);
				$statement->bindValue(':carSeatManufactureDate',$carSeatDte[$key]);
				$statement->bindValue(':carSeatSerialModel',$carSeatSerialNum[$key]);
				$statement->execute();
			}
			$db->commit();


			echo '<h2 style="text-align:center success" >Success</h2><p class="success">You have sucessfully entered an entry into the database. To add another entry, click this <a href="CPS.php">link</a>.</p> ';
		}

		catch(PDOException $e)
		{
?>
			
<?php
			include '../includes/dbError.inc';
?>
		
<?php
		}
	}

	if($showForm)
	{
?>
		<form id="formCPS" action="CPSprocess.php" method="POST">

		<fieldset id="technicianInformation">
		<h4>Technician and Site Information</h4>
		<p>Technician Name: <input type="text" name="technicianName" value=<?php echo "'$technicianName'"; ?>></p>

<?php
	if(isset($_SESSION['isAdmin']))
	{
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
			
				if(trim($distributionSite) == $agency)
					echo 'selected';
				echo">$distributionSite</option>";
			}
?>
			</select>
			</p>
<?php
		}
		else
			echo '<input type="hidden" name="agency" value="'.$_SESSION['agency'].'"/>';
?>
		<p>Date of distribution: <input type="text" class="datepicker" name="dateDistributed" value=<?php echo "'$dateDistributed'";?>/></p>
		</fieldset>


		<fieldset id="applicantInformation">
		<h4>Parent/Guardian Information</strong></h4>
		<p> First Name(parent/guardian/caregiver): <input id="appFnme" class="appInfo" name="prnt1firstName" type="text" value=<?php echo"'$parentFname'"; ?>/></p>
		<p>Last Name(parent/guardian/caregiver): <input id="appLstme" class="appInfo" name="prnt1LastName" type="text" value=<?php echo"'$parentLname'"; ?>/></p>
		<p>First name of other parent/guardian(if available): <input id="prntFnme" class="appInfo" name="prntFnme" type="text" value=<?php echo"'$otherParentFname'"; ?>/></p>
		<p>Last name of other parent/guardian(if available): <input id="prntLstme" class="appInfo" name="prntLstme" type="text" value=<?php echo"'$otherParentLname'"; ?>/></p>


		<p>Applicant's Physical Address: <input id="address" name="address" class="appInfo" type="text" value=<?php echo"'$address'"; ?>/></p>
		<p>Applicant's Town/City of Residence: <input id="city" name="city" class="appInfo" type="text" value=<?php echo"'$city'"; ?>/></p>
		<p>Applicant's Zip Code of Residence: <input id="zipcode" class="appInfo" name="zipcode" value=<?php echo"'$zipcode'"; ?>/></p>
		<p>Applicant's County of Residence:
			<select name="county" class="appInfo">
				<option value =""></option>
				<option value="Androscoggin" <?php if($county=="Androscoggin") echo "selected";?>>Androscoggin</option>
				<option value="Aroostook" <?php if($county=="Aroostook") echo "selected";?>>Aroostook</option>
				<option value="Cumberland" <?php if($county=="Cumberland") echo "selected";?>>Cumberland</option>
				<option value="Franklin" <?php if($county=="Franklin") echo "selected";?>>Franklin</option>
				<option value="Hancock" <?php if($county=="Hancock") echo "selected";?>>Hancock</option>
				<option value="Kennebec" <?php if($county=="Kennebec") echo "selected";?>>Kennebec</option>
				<option value="Knox" <?php if($county=="Knox") echo "selected";?>>Knox</option>
				<option value="Lincoln" <?php if($county=="Lincoln") echo "selected";?>>Lincoln</option>
				<option value="Oxford" <?php if($county=="Oxford") echo "selected";?>>Oxford</option>
				<option value="Penobscot" <?php if($county=="Penobscot") echo "selected";?>>Penobscot</option>
				<option value="Piscataquis" <?php if($county=="Piscataquis") echo "selected";?>>Piscataquis</option>
				<option value="Sagadahoc" <?php if($county=="Sagadahoc") echo "selected";?>>Sagadahoc</option>
				<option value="Somerset" <?php if($county=="Somerset") echo "selected";?>>Somerset</option>
				<option value="Waldo" <?php if($county=="Waldo") echo "selected";?>>Waldo</option>
				<option value="Washington" <?php if($county=="Washington") echo "selected";?>>Washington</option>
				<option value="York" <?php if($county=="York") echo "selected";?>>York</option>
			</select>
		</p>
		<p>Income Eligibility Verification:
			<select name="incmeEligType" class="appInfo" >
				<option value="" <?php ?> ></option>
				<option value="Yes" <?php if($incomeEligible=="yes") echo "selected";?>>Yes</option>
				<option value="No" <?php if($incomeEligible=="no") echo "selected";?>>No</option>
				<option value="WIC" <?php if($incomeEligible=="wic") echo "selected";?>>WIC folder</option>
				<option value="TANF" <?php if($incomeEligible=="tanf") echo "selected";?>>TANF letter</option>
				<option value="SNAP" <?php if($incomeEligible=="snap") echo "selected";?>>SNAP Letter</option>
				<option value="Maine Care" <?php if($incomeEligible=="maine care") echo "selected";?>>Maine Care letter or phone call</option>
			</select>
		</p>
		<p>Maine Residency Verification:
			<select name="maineRes" id="maineRes" class="appInfo">
				<option value=""></option>
				<option value="Yes" <?php if($maineResidency=="yes") echo "selected";?>>Yes</option>
				<option value="No" <?php if($maineResidency=="no") echo "selected";?>>No</option>
				<option value="Maine Driver's License" <?php if($maineResidency=="maine driver's license") echo "selected";?>>Maine Driver's License</option>
				<option value="Maine Identification Card" <?php if($maineResidency=="maine identification card") echo "selected";?>>Maine Identification Card</option>
				<option value="Tribal Identification" <?php if($maineResidency=="tribal identification") echo "selected";?>>Tribal Identification</option>
				<option value="Refugee I-94"<?php if($maineResidency=="refugee i-94") echo "selected";?>>Refugee I-94 letter with photo</option>
			              <option value="High School ID" <?php if($maineResidency=="high school id") echo "selected";?>>High School photo ID</option>
</select>
		</p>
				<button id="lookup_parent">Lookup Parent/Child</button>

		</fieldset>

		<fieldset id="childAndSeatInformation">
<?php
	$y=1;
		foreach ($childFirstName as $key => $value) 
		{
?>

			<div class="childAndSeatInfo">
				<div class="childInformation">
					<h4>Child #<?php echo $y; ?> Information</strong></h4>
						<p>Child's First Name <input type="text" name="childFirstName[]" value=<?php echo"'".$childFirstName[$key]."'";?>/></p>
						<p>Child's Last Name <input type="text" name="childLastName[]" value=<?php echo"'".$childLastName[$key]."'";?>/></p>
						<p>Child's Age (Note: if child has not yet been born enter 0) <input type="text" name="childsAge[]" value=<?php echo"'".$childsAge[$key]."'";?>/>
							<strong>Select one:</strong>
							<select class="childsUnitAge" name="childAgeUnit[]">
								<option value=""></option>
								<option value="Days"  <?php if ($childAgeUnit[$key]=="Days") echo"selected";?>>Days</option>
								<option value="Months" <?php if ($childAgeUnit[$key]=="Months") echo"selected";?>>Months</option>
								<option value="Years" <?php if ($childAgeUnit[$key]=="Years") echo"selected";?>>Years</option>
							<select></p>
						</p>
						<p>Child's Date of Birth/Due Date <input type="text" class="datepicker" name="childDOB[]" value=<?php echo"'".$childDOB[$key]."'"; ?>/></p>
						<p>Child's Weight (in pounds) (Note: if child has not yet been born enter 0) <input type="text" name="childWeight[]" value=<?php echo"'".$childWeight[$key]."'"; ?>/></p>
						<p>Child's Height (in inches) (Note: if child has not yet been born enter 0) <input type="text" name="childHeight[]" value=<?php echo"'".$childHeight[$key]."'"; ?>/></p>
						<p>Was the child present at the appointment? Select one:
								<select name="present[]">
								<option value=""></option>
								<option value="Yes" <?php if ($present[$key]=="Yes") echo"selected";?>>Yes</option>
								<option value="No" <?php if ($present[$key]=="No") echo"selected";?>>No</option>
								<option value="Unknown" <?php if ($present[$key]=="Unknown") echo"selected";?>>Unknown</option>
							<select>
						</p>

				<div class="safetySeatInfo">
					<h4>Child #<?php echo $y;?> Safety Seat Information</h4>
						<p>Car Seat Manufacturer:
							<select name="manufacturerList[]" class="safetySeatInfo">
								<option value=""></option>
								<option value="evenflo embrace" <?php if (strtolower($manufacturerList[$key])=="evenflo embrace") echo"selected";?>>Evenflo Embrace-Infant Seat</option>
								<option value="evenflo titan" <?php if (strtolower($manufacturerList[$key])=="evenflo titan") echo"selected";?>>Evenflo Titan-Convertible Seat</option>
								<option value="evenflo secure kid" <?php if (strtolower($manufacturerList[$key])=="evenflo secure kid") echo"selected";?>>Evenflo Secure Kid-Combination Seat</option>
								<option value="evenflo" <?php if (strtolower($manufacturerList[$key])=="evenflo") echo"selected";?>>Evenflo-Highback to No Back Booster Seat</option>
							</select>
						</p>
					<p>Car Seat Manufacturer:(if different from the list above)<input type="text" name="manufacturerText[]" value=<?php echo"'".$manufacturerText[$key]."'";?> /></p>
					<p>Car Seat Model Name:(if different from the list above)<input type="text"  name="carSeatNme[]" class="safetySeatInfo" value=<?php echo"'".$carSeatNme[$key]."'";?> /></p>

					<p>Car Seat Manufacture Date: <input type="text" class="datepicker" name="carSeatDte[]" value=<?php echo"'".$carSeatDte[$key]."'"; ?> />
					</p>
					<p>Car Seat Model/Serial Number: <input type="text" class="safetySeatInfo" name="carSeatSerialNum[]" value=<?php echo"'".$carSeatSerialNum[$key]."'"; ?> /></p>
				</div>

			</div>
<?php
			
			$y++;
		}
?>
		<button id="addChildSt" type="button">Add another child and seat</button>
	</fieldset>
	<p>By checking the following box, you are confirming that the information above is correct. <input type="checkbox" name="confirm" value="confirmed" <?php if($confirm=="confirmed") echo"checked";?> /></p>

		<p style = "text-align:center"><input type="submit" class = "buttons" name="submit" id="submit" value="Submit" /></p>
</form>



<?php
	}

}
?>
</div>
</body>
</html>