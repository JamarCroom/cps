<!DOCTYPE html>
<!--CPS-->
<html>


<head>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="../jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.js"></script>
<link href='style/cpsStyle.css' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Paytone+One' rel='stylesheet' type='text/css'>
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
		
		var childNumber=2;
		var index = $('.childsUnitAge').length;
		$('#addChildSt').click(function(){
			$('.childAndSeatInfo:last').after('<div class="childAndSeatInfo"><div class="childInformation" style="margin-top: 35px;"><h4>Child #'+childNumber+' Information</h4><p>Child&#39;s First Name <input type="text" name="childFirstName[]"/></p><p>Child&#39;s Last Name <input type="text" name="childLastName[]"/></p><p>Child&#39;s Age (Note: if child has not yet been born enter 0) <input type="text" name="childsAge[]"/> <strong>Select one:</strong><select name="childAgeUnit['+index+']"><option value=""></option><option value="Days">Days</option><option value="Months">Months</option><option value="Years">Years</option><select></p></p><p>Child&#39;s Date of Birth/Due Date <input type="text" class="datepicker" name="childDOB[]"/></p><p>Child&#39;s Weight (Note: if child has not yet been born enter 0) <input type="text" name="childWeight[]" /></p><p>Child&#39;s Height (Note: if child has not yet been born enter 0)<input type="text" name="childHeight[]" /></p></div><div class="safetySeatInfo"><p>Was the child present at the appointment? Select one:<select name="present['+index+']"><option value=""></option><option value="Yes">Yes</option><option value="No">No</option><option value="Unknown">Unknown</option><select></p><h4>Child #'+childNumber+' Safety Seat Information</h4><p>Car Seat Manufacturer:<select name="manufacturerList['+index+']" class="safetySeatInfo"><option value=""></option><option value="Evenflo Embrace">Evenflo Embrace-Infant Seat</option><option value="Evenflo Titan">Evenflo Titan-Convertible Seat</option><option value="Evenflo Secure Kid">Evenflo Secure Kid-Combination Seat</option><option value="Evenflo">Evenflo-Highback to No Back Booster Seat</option></select></p><p>Car Seat Manufacturer:(if different from the list above) <input type="text" name="manufacturerText[]" /></p><p>Car Seat Model Name:(if different from the list above)<input type="text"  name="carSeatNme[]"/></p><p>Car Seat Manufacture Date: <input type="text" class="datepicker" name="carSeatDte[]" /></p><p>Car Seat Model/Serial Number: <input type="text" class="safetySeatInfo" name="carSeatSerialNum[]" /></p></div></div>');
			if(compatible)
			{
				$(".datepicker").each(function()
				{
				$(this).datepicker({ dateFormat: 'yy-mm-dd' });
				});
			}
			childNumber ++;
			index++;
			return false;
		});
});
</script>

</head>
<body>

<div id="wrapper">
	<div id="header"><img src="../pics/bhs_logo.jpg" id="logo" style="vertical-align: middle; border-radius: 5px;"/><span id="logoWording">Bureau of Highway Safety Web Applications Portal</span></div>
<?php
	include 'cpsInclude/navbar.inc';
?>
