<?php
session_start();
include 'cpsInclude/httpsRedirect.inc';
if(!isset($_SESSION['userId']))
{
	include 'cpsInclude/privilegeError.inc';

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
	});";



include 'cpsInclude/head.inc';
?>

	<form id="formCPS" action="CPSprocess.php" method="POST">
		<h2 style ="text-align:center; margin-top: 3px;">Child Passenger Safety Reporting Form</h2>
		<fieldset id="technicianInformation">
		<h4>Technician and Site Information</h4>
		<p>Technician Name: <input type="text" name="technicianName"></p>
<?php
		if(isset($_SESSION['isAdmin']))
		{
?>
			<p>Agency: <select name="agency">
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
<?php
		}
		else
		echo '<input type="hidden" name="agency" value="'.$_SESSION['agency'].'"/>';
?>
		<p>Date of distribution: <input type="text" class="datepicker" name="dateDistributed" /></p>
		</fieldset>
		<fieldset id="applicantInformation">
		<h4>Parent/Guardian Information</h4>
		<p> First Name(parent/guardian/caregiver): <input id="appFnme" class="appInfo" name="prnt1firstName" type="text"/></p>
		<p>Last Name(parent/guardian/caregiver): <input id="appLstme" class="appInfo" name="prnt1LastName" type="text"/></p>
		<p>First name of other parent/guardian(if available): <input id="prntFnme" class="appInfo" name="prntFnme" type="text" /></p>
		<p>Last name of other parent/guardian(if available): <input id="prntLstme" class="appInfo" name="prntLstme" type="text"/></p>

		<p>Applicant's Physical Address: <input id="address" name="address" class="appInfo" type="text" /></p>
		<p>Applicant's Town/City of Residence: <input id="city" name="city" class="appInfo" type="text" /></p>
		<p>Applicant's Zip Code of Residence: <input id="zipcode" class="appInfo" name="zipcode" /></p>
		<p>Applicant's County of Residence:
			<select name="county" class="appInfo">
				<option value =""></option>
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
		</p>
		<p>Income Eligibility Verification:
			<select name="incmeEligType" class="appInfo" >
				<option value=""></option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
				<option value="WIC">WIC folder</option>
				<option value="TANF">TANF letter</option>
				<option value="SNAP">SNAP Letter</option>
				<option value="Maine Care">Maine Care letter or phone call</option>
			</select>
		</p>
		<p>Maine Residency Verification:
			<select name="maineRes" id="maineRes" class="appInfo">
				<option value=""></option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
				<option value="Maine Driver's License">Maine Driver's License</option>
				<option value="Maine Identification Card">Maine Identification Card</option>
				<option value="Tribal Identification">Tribal Identification</option>
				<option value="Refugee I-94">Refugee I-94 letter with photo</option>
                                <option value="High School ID">High School photo ID</option>
			</select>
		</p>
		<button id="lookup_parent">Lookup Parent/Child</button>
		</fieldset>

		<fieldset id="childAndSeatInformation">
			<div class="childAndSeatInfo">
				<div class="childInformation">
					<h4>Child #1 Information</strong></h4>
						<p>Child's First Name <input type="text" name="childFirstName[]"/></p>
						<p>Child's Last Name <input type="text" name="childLastName[]"/></p>
						<p>Child's Age (Note: if child has not yet been born enter 0) <input type="text" name="childsAge[]"/><strong>Select one:</strong>
							<select class="childsUnitAge" name ="childAgeUnit[0]">
								<option value=""></option>
								<option value="Days">Days</option>
								<option value="Months">Months</option>
								<option value="Years">Years</option>
							<select></p>
						<p>Child's Date of Birth/Due Date <input type="text" class="datepicker" name="childDOB[]"/></p>
						<p>Child's Weight (in pounds) (Note: if child has not yet been born enter 0) <input type="text" name="childWeight[]" /></p>
						<p>Child's Height (in inches) (Note: if child has not yet been born enter 0) <input type="text" name="childHeight[]" /></p>
						<p>Was the child present at the appointment? Select one:
								<select name="present[0]">
								<option value=""></option>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
								<option value="Unknown">Unknown</option>
							<select>
						</p>

				</div>

				<div class="safetySeatInfo">
					<h4>Child #1 Safety Seat Information</h4>
						<p>Car Seat Manufacturer:
							<select name="manufacturerList[0]" class="safetySeatInfo">
								<option value=""></option>
								<option value="Evenflo Embrace">Evenflo Embrace-Infant Seat</option>
								<option value="Evenflo Titan">Evenflo Titan-Convertible Seat</option>
								<option value="Evenflo Secure Kid">Evenflo Secure Kid-Combination Seat</option>
								<option value="Evenflo">Evenflo-Highback to No Back Booster Seat</option>
							</select>
						</p>
					<p>Car Seat Manufacturer:(if different from the list above)<input type="text" name="manufacturerText[]" /></p>
					<p>Car Seat Model Name:(if different from the list above)<input type="text"  name="carSeatNme[]" class="safetySeatInfo" /></p>

					<p>Car Seat Manufacture Date: <input type="text" class="datepicker" name="carSeatDte[]" />
					</p>
					<p>Car Seat Model/Serial Number: <input type="text" class="safetySeatInfo" name="carSeatSerialNum[]" /></p>
				</div>

			</div>
		<button id="addChildSt" type="button">Add another child and seat</button>
	</fieldset>
	<p>By checking the following box, you are confirming that the information above is correct. <input type="checkbox" name="confirm" value="confirmed"></p>

		<p style = "text-align:center"><input type="submit" class = "buttons" name="submit" id="submit" value="Submit"/></p>
</form>
</div>
</body>
</html>
<?php
}
?>