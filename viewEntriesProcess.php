<?php
session_start();
include 'cpsInclude/httpsRedirect.inc';
if(!isset($_SESSION['isAdmin'])||!isset($_GET['parentId']))
{
	include_once 'cpsInclude/privilegeError.inc';
}
else
{

	$showForm=true;
	include 'cpsInclude/head.inc';
	if(!isset($_POST['submit']))
	{
	
		try
		{
			$parentId = $_GET['parentId'];
			require_once 'cpsInclude/dbConnect.inc';
			$db->beginTransaction();
			$sql="SELECT * FROM parentInfoTable WHERE parentId=:parentId";
			$statement=$db->prepare($sql);
			$statement->bindValue(':parentId',$_GET['parentId']);
			$statement->execute();
			$report=$statement->fetchAll();

			$query='SELECT * FROM childInfoTable WHERE parentId = :parentId';
			$statement=$db->prepare($query);
			$statement->bindValue(':parentId',$_GET['parentId']);
			$statement->execute();	
			$childReport=$statement->fetchAll();
			$db->commit();
			foreach($report as $reports)
			{
				$dateDistributed = $reports['dateSubmission'];
				$technicianName = $reports['technicianName'];
				$parentFname=$reports['parentFirstName'];
				$parentLname=$reports['parentLastName'];
				$otherParentFname=$reports['otherParentFirstName'];
				$otherParentLname=$reports['otherParentLastName'];
				$agency=$reports['agency'];
				$address=$reports['address'];
				$city=$reports['city'];
				$zipcode=$reports['zipcode'];
				$county=$reports['county'];
				$incomeEligible=$reports['incomeEligible'];
				$maineResidency=$reports['maineResidency'];
			}
			$i=0;
			foreach($childReport as $childReports)
			{
				$childFirstName[$i]=$childReports['childFirstName'];
				$childLastName[$i]=$childReports['childLastName'];
				$childsAge[$i]=$childReports['childAge'];
				$childDOB[$i]=$childReports['childDOB'];
				$childAgeUnit[$i]=$childReports['childAgeUnit'];
				$present[$i]=$childReports['childPresent'];
				$childWeight[$i]=$childReports['childWeight'];
				$childHeight[$i]=$childReports['childHeight'];
				$manufacturerList[$i]=$childReports['carSeatManufacturerList'];
				$manufacturerText[$i]=$childReports['carSeatManufacturer'];
				$carSeatNme[$i]=$childReports['carSeatModelName'];
				$carSeatDte[$i]=$childReports['carSeatManufactureDate'];
				$carSeatSerialNum[$i]=$childReports['carSeatSerialModel'];
				$childId[$i] = $childReports['childId'];
				$i++;
			}
			

		 }

		 catch(PDOException $e)
		 {
		 	$showForm=false;
		?>
		 					<fieldset class="adjust">
	<?php
				include 'cpsInclude/dbError.inc';
	?>
			</fieldset>
<?php
		 }
	}
	 else
	 {
	 	require_once 'objects/Validation.php';
		$validate= new Validation();
		$parentId = $_POST['parentId'];
		$parentFname = strtolower($validate->validAlpha($_POST['prnt1firstName'],'First Name'));
		$parentLname = strtolower($validate->validAlpha($_POST['prnt1LastName'],'Last Name'));
		$otherParentFname=strtolower($validate->validAlpha($_POST['prntFnme'], 'First name of other parent/guardian'));
		$otherParentLname=strtolower($validate->validAlpha($_POST['prntLstme'], 'Last name of other parent/guardian'));
		$dateDistributed = $validate->validDate($_POST['dateDistributed'],'Date Distributed');
		$technicianName = $validate->validAlpha($_POST['technicianName'],'Technician Name');
		$address = $validate->validateInput($_POST['address'],'Address');
		$city =strtolower($validate->validAlpha($_POST['city'], 'City'));
		$zipcode = $validate->validNum($_POST['zipcode'],'Zipcode');
		$county = $validate->validateInput($_POST['county'],'County');
		$incomeEligible = strtolower($validate->validateInput($_POST['incmeEligType'],'Income Eligibility Verification'));
		$maineResidency = strtolower($validate->validateInput($_POST['maineRes'],'Maine Residency Verification'));
		$agency = $validate->validateInput(strtolower(trim($_POST['agency'])),'Agency');
 
		$childFirstName = $_POST['childFirstName'];
		$childLastName= $_POST['childLastName'];
		$childsAge = $_POST['childsAge'];
		$childDOB = $_POST['childDOB'];
		$childAgeUnit=$_POST['childAgeUnit'];
		$present = $_POST['present'];
		$childWeight = $_POST['childWeight'];
		$childHeight = $_POST['childHeight'];
		$manufacturerList = $_POST['manufacturerList'];		
		$manufacturerText = $_POST['manufacturerText'];
		$carSeatNme = $_POST['carSeatNme'];
		$carSeatDte = $_POST['carSeatDte'];
		$carSeatSerialNum = $_POST['carSeatSerialNum'];
		$childId = $_POST['childId'];
		foreach($childFirstName as $key=>$value)
		{	
			$childFirstName[$key] = strtolower($validate->validAlpha($childFirstName[$key],"Child's First Name"));
			$childLastName[$key] = strtolower($validate->validAlpha($childLastName[$key],"Child's Last Name"));
			$childsAge[$key] = $validate->validNum($childsAge[$key],"Child's Age");
			$childAgeUnit[$key] = $validate->validateInput($childAgeUnit[$key],"Child's unit of age");
			$childDOB[$key]=$validate->validDate($childDOB[$key],"Child's Date of Birth");
			$present[$key] = $validate->validateInput($present[$key],"Child present");
			$childWeight[$key] = $validate->validNum($childWeight[$key],"Child's Weight");
			$childHeight[$key] = $validate->validNum($childHeight[$key],"Child's Height");
			if(empty($manufacturerList[$key])&&empty($manufacturerText[$key])&& empty($carSeatNme[$key]))
			{
				$manufacturerList[$key] = strtolower($validate->validateInput($manufacturerList[$key],"Select from list: Car Seat Manufacturer"));
				$manufacturerText[$key]=strtolower($validate->validAlphaNum($manufacturerText[$key],"Car Seat Manufacturer: (if different from the list above)"));
				$carSeatNme[$key]=strtolower($validate->validAlphaNum($carSeatNme[$key],"Car Seat Model Name: (if different from the list above)"));
			}
			else if(empty($manufacturerList[$key])&& (!empty($manufacturerText[$key])||!empty($carSeatNme[$key])))
			{
				$manufacturerText[$key]= strtolower($validate->validAlphaNum($manufacturerText[$key],"Car Seat Manufacturer: (if different from the list above)"));
				$carSeatNme[$key]= strtolower($validate->validAlphaNum($carSeatNme[$key],"Car Seat Model Name: (if different from the list above)"));
			}
			else if(!empty($manufacturerList[$key])&& (!empty($manufacturerText[$key])||!empty($carSeatNme[$key])))
			{
				$validate->incErrorCount();
				$validate->addErrorMsgs("<p class='error'>Error:Either the 'Manufacturer's list' field OR the 'Car Seat Manufacturer' and 'Car Seat Model Name' fields must be completed.</p>");
			}
			$carSeatDte[$key] = $validate->validDate($carSeatDte[$key],"Car Seat Manufacturer Date");
			$carSeatSerialNum[$key]= $validate->validAlphaNum($carSeatSerialNum[$key],"Car Seat Model/Serial Number");
		}
		$errors=$validate->getErrorCount();

		//validate
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
			try
			{
				require_once 'cpsInclude/dbConnect.inc';
				$db->beginTransaction();
				$updateQuery = "UPDATE parentInfoTable SET parentFirstName =:parentFname,technicianName=:technicianName,dateSubmission=:dateDistributed,
				parentLastName =:parentLname, otherParentFirstName=:otherParentFname, otherParentLastName=:otherParentLname,
				agency =:agency, address=:address, city=:city, zipcode=:zipcode, county=:county, incomeEligible=:incomeEligible,
				maineResidency=:maineResidency
				WHERE parentId =:parentId";
				$statement = $db->prepare($updateQuery);
				$statement->bindValue(':parentFname',$parentFname);
				$statement->bindValue(':parentLname',$parentLname);
				$statement->bindValue(':otherParentFname',$otherParentFname);
				$statement->bindValue(':otherParentLname',$otherParentLname);
				$statement->bindValue(':agency',$agency);
				$statement->bindValue(':address',$address);
				$statement->bindValue(':city',$city);
				$statement->bindValue(':zipcode',$zipcode);
				$statement->bindValue(':county',$county);
				$statement->bindValue(':incomeEligible',$incomeEligible);
				$statement->bindValue(':maineResidency',$maineResidency);
				$statement->bindValue(':technicianName',$technicianName);
				$statement->bindValue(':dateDistributed',$dateDistributed);
				$statement->bindValue('parentId',$parentId);
				$statement->execute();

				$updateQuery2="UPDATE childInfoTable SET childAgeUnit=:childAgeUnit, childFirstName=:childFirstName, childLastName=:childLastName,
				 childAge=:childsAge, childDOB=:childDOB, childWeight=:childWeight,childPresent=:childPresent,
				 childHeight=:childHeight, carSeatManufacturerList=:manufacturerList, carSeatManufacturer=:manufacturerText,
				 carSeatModelName=:carSeatNme,carSeatManufactureDate=:carSeatDte, carSeatSerialModel=:carSeatSerialNum WHERE childId=:childId";

				 $statement2=$db->prepare($updateQuery2);
				 foreach($childFirstName as $key=>$value)
				 {
					 $statement2->bindValue(':childFirstName',$childFirstName[$key] );
					 $statement2->bindValue(':childLastName',$childLastName[$key]);
					 $statement2->bindValue(':childsAge',$childsAge[$key]);
					 $statement2->bindValue(':childDOB',$childDOB[$key]);
					 $statement2->bindValue(':childAgeUnit',$childAgeUnit[$key]);
					 $statement2->bindValue(':childWeight',$childWeight[$key]);
					  $statement2->bindValue(':childPresent',$present[$key]);
					 $statement2->bindValue(':childHeight',$childHeight[$key]);
					 $statement2->bindValue(':manufacturerList',$manufacturerList[$key]);
					 $statement2->bindValue(':manufacturerText',$manufacturerText[$key]);
					 $statement2->bindValue(':carSeatNme',$carSeatNme[$key]);
					 $statement2->bindValue(':carSeatDte',$carSeatDte[$key]);
					 $statement2->bindValue(':childId',$childId[$key]);
					 $statement2->bindValue(':carSeatSerialNum',$carSeatSerialNum[$key]);
					 $statement2->execute();
				}	
			 
				$db->commit();	
				$showForm = false;
				echo '<fieldset class="adjust"><h2 style="text-align:center" class="success">Success</h2><p class="success">The report was sucessfully updated.</p></fieldset> ';


			}

			catch(PDOException $e)
			{
						?>
		 		<fieldset class="adjust">
	<?php
					include 'cpsInclude/dbError.inc';
	?>
				</fieldset>
<?php
			}

		}

	 }

	

		if($showForm)
		{
			echo '<h2 style ="text-align:center; margin-top: 3px;">Child Passenger Safety Reporting Form</h2>';
	?>
			<form  action="viewEntriesProcess.php" method="POST">
		<fieldset id="technicianInformation">
		<h4>Technician and Site Information</h4>
		<p>Technician Name: <input type="text" name="technicianName" value="<?php echo $technicianName?>"></p>
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
			
				if(trim($distributionSite) == $agency)
					echo 'selected';
				echo">$distributionSite</option>";
			}
?>
			</select>
			</p>
<?php
		
?>

		<p>Date of distribution: <input type="text" class="datepicker" name="dateDistributed" value="<?php echo $dateDistributed?>" /></p>
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
			</fieldset>

			<fieldset id="childAndSeatInformation">
	<?php
		$y=1;
		$i=0;
			foreach ($childFirstName as $key => $value) 
			{
	?>

				<div class="childAndSeatInfo">
					<div class="childInformation">
						<h4>Child #<?php echo $y; ?> Information</strong></h4>
							<p>Child's First Name <input type="text" name="childFirstName[]" value=<?php echo"'".$childFirstName[$key]."'";?>/></p>
							<p>Child's Last Name <input type="text" name="childLastName[]" value=<?php echo"'".$childLastName[$key]."'";?>/></p>
							<p>Child's Age (Note: if child has not yet been born enter 0) <input type="text" name="childsAge[]" value=<?php echo"'".$childsAge[$key]."'"; ?>/>
								<strong>Select One:</strong><select class="childsUnitAge" name ="childAgeUnit[<?php echo $i;?>]">
								<option value="Days"  <?php if ($childAgeUnit[$key]=="Days") echo"selected";?>>Days</option>
								<option value="Months" <?php if ($childAgeUnit[$key]=="Months") echo"selected";?>>Months</option>
								<option value="Years" <?php if ($childAgeUnit[$key]=="Years") echo"selected";?>>Years</option>
							<select>
							</p>
							<p>Child's Date of Birth/Due Date <input type="text" class="datepicker" name="childDOB[]" value=<?php echo"'".$childDOB[$key]."'"; ?>/></p>
							<p>Child's Weight (in pounds) (Note: if child has not yet been born enter 0) <input type="text" name="childWeight[]" value=<?php echo"'".$childWeight[$key]."'"; ?>/></p>
							<p>Child's Height (in inches) (Note: if child has not yet been born enter 0) <input type="text" name="childHeight[]" value=<?php echo"'".$childHeight[$key]."'"; ?>/></p>
					
							<p>Was the child present at the appointment? Select one:
								<select name="present[<?php echo $i?>]">
								<option value=""></option>
								<option value="Yes" <?php if ($present[$key]=="Yes") echo"selected";?>>Yes</option>
								<option value="No" <?php if ($present[$key]=="No") echo"selected";?>>No</option>
								<option value="Unknown" <?php if ($present[$key]=="Unknown") echo"selected";?>>Unknown</option>
							<select>
						</p>

					<input type="hidden" name="childId[]" value= <?php echo'"'.$childId[$key].'"';?> />
					</div>

					<div class="safetySeatInfo">
						<h4>Child #<?php echo $y;?> Safety Seat Information</h4>
							<p>Car Seat Manufacturer:
								<select name="manufacturerList[<?php echo $i;?>]" class="safetySeatInfo">
									<option value=""></option>
									<option value="evenflo embrace" <?php if ($manufacturerList[$key]=="evenflo embrace") echo"selected";?>>Evenflo Embrace-Infant Seat</option>
									<option value="evenflo titan" <?php if ($manufacturerList[$key]=="evenflo titan") echo"selected";?>>Evenflo Titan-Convertible Seat</option>
									<option value="evenflo secure kid" <?php if ($manufacturerList[$key]=="evenflo secure kid") echo"selected";?>>Evenflo Secure Kid-Combination Seat</option>
									<option value="evenflo" <?php if ($manufacturerList[$key]=="evenflo") echo"selected";?>>Evenflo-Highback to No Back Booster Seat</option>
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
				$i++;
			}
	?>
			
		</fieldset>
			<input type="hidden" name="parentId" value="<?php echo $parentId?>" />
			<input type="submit" name="submit" value="Update" />
		</form>
<?php
	}

}
?>

</div>
</body>
</html>