<?php
session_start();
include 'cpsInclude/httpsRedirect.inc';
if (!isset($_SESSION['isAdmin']))
{
	include_once 'cpsInclude/privilegeError.inc';

}
else
{
	include 'cpsInclude/head.inc';
	$updateForm = true;
	if(isset($_POST['submit']))
	{
		$file = file_put_contents('distributionSites.txt', strtolower(trim($_POST['distributionList'])));
		if($file!==false)
		{
			?>
			<h3 class="center success">Success</h3>
			<p class="center success">You have successfully updated the distribution list.</p>
<?php
			$updateForm = false;
		}
		else
		{
?>
			<h3 class="center error">Error</h3>
			<p class="center error">There was an error that occured while updating the file, please try again.</p>		

<?php
		}


	}



	if($updateForm)
	{
		$file = file_get_contents('distributionSites.txt');


?>
		<h2 class="center">Update Distribution List</h2><br/>
		<form action="distributionUpdate.php" method="POST">
		
			
			<p><strong>Instructions:</strong>To add an agency to the distribution list type in the name of the agency at the end of the list. If you'd like to add more than one agency, separate the name of each agency with a comma. To delete an agency from the list,just erase the agency from the list. Once you've finished making changes, press submit.</p>
			
			<br/>
			<p>
			<textarea name="distributionList" rows="15" cols="100">
			<?php echo "".trim(ucwords($file)).""; ?>
			</textarea>
			</p>
			<p><input type="submit" name="submit" value="Submit" /></p>

		</form>
<?php
	}


}
?>
</div>
</body>
</html>