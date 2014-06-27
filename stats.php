<?php
session_start();
include 'cpsInclude/httpsRedirect.inc';
if(!isset($_SESSION['userId']))
{
	include 'cpsInclude/privilegeError.inc';

}
else
{
	$script ="

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
// Returns the version of Internet Explorer or a -1
		// (indicating the use of another browser).
		function pickerCompatible()

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
";


		include 'cpsInclude/head.inc';

?>
	<h2 class="center">Review Statistics</h2>


	<form action="statisticsprocess.php" method="POST" style="margin: 15px 0 15px 40px;">

	<p>
		<span class="state"><strong>View Stats By State:</strong></span>&nbsp;&nbsp;&nbsp;<select class="state" disabled><option value="">Statewide Stats</option><option="">Bunchof Dummy Data Here</option></select>
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
		<span><strong>Select a filter criteria:</strong></span>&nbsp;&nbsp;&nbsp;County<input type="radio" id="countyChoice" name="choice" value="county"/>&nbsp;&nbsp;Agency<input type="radio" id="departmentChoice" name="choice" value="department"/>&nbsp;&nbsp;State<input type="radio" id="stateChoice" name="choice" value="state" checked/><?php
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

	include 'cpsInclude/foot.inc';

}

?>