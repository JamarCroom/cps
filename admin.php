<?php
session_start();
include 'cpsInclude/httpsRedirect.inc';
if(isset($_SESSION['isAdmin']))
{
	$style="
		#wrapper
		{
			height:500px;
		}";
	include_once 'cpsInclude/head.inc';
?>

	<h2 class="center" style="padding-bottom:30px;">Admin Menu</h2>

<div style="float:left; height: 200px; margin-left:30%;">	
	<form action='addCPSUser.php' method='POST'><button>Add A User</button></form>
		<br/>
	<form action='updateCPSUser.php' method='POST'><button>Update A User</button></form><br/>
	
</div>


	<div style="float:right; height: 200px; margin-right:30%;">
	
	<form action='viewEntries.php' method='POST'><button>View and Update Entries</button></form>
	<br/>
		<form action='distributionUpdate.php' method='POST'><button>Modify Distribution List</button></form>


</div>
</body>
</html>

<?php


}
else
{
		
	include_once 'cpsInclude/privilegeError.inc';


}
?>