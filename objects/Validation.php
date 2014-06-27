<?php
class Validation
{
	private $errorCount=0;
	private $errorMsg =array();
	public function validateInput($input,$fieldname)
	{

		if(empty($input))
		{
			$this->errorMsg[] ="<p class=\"error\">Error:The \"$fieldname\" field is required.</p>";
			$this->errorCount++;
			$retval="";
		}
		else
		{
				$retval=$input;
		}
		return $retval;
	
	}

	public function valid24Time($startHours,$startMinutes,$endHours,$endMinutes)
	{

		$result= array();
		if(($startHours==$endHours)&&($startMinutes==$endMinutes))
		{
			$this->errorMsg[] ="<p class='error'>Error: The start time and end times cannot be the same.</p>";
			$this->errorCount++;
			//$result="";
		}
		else if(($startHours>$endHours)||(($startHours==$endHours)&&($startMinutes>$endMinutes)))
		{
			$this->errorMsg[] ="<p class='error'>Error: The start time cannot come after the end time.</p>";
			$this->errorCount++;
			//$result="";	
		}
		//else
		//{

			$result[0] = $startHours;
			$result[1] = $startMinutes;
			$result[2] = $endHours;
			$result[3] = $endMinutes;
		//}
		return $result;
	}



	public function validNum($input,$fieldname)
	{
		$pattern = '/^(([0-9]+)|([0-9]+).([0-9]+))$/';
		$result= preg_match($pattern,$input);
		if ($result ==0||!isset($input))
		{
			$this->errorMsg[] ="<p class='error'>Error:'$fieldname' is a required field that must contain numbers only";
			$this->errorCount++;
			$result="";
			
		}
		else
		{
			$result = $input;
		}
		return $result;
	}


	public function validDate($input,$fieldname)
	{
		$pattern1 = '/^(20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/';
		$pattern2 = '/^(0[1-9]|1[012])[\/](0[1-9]|[12][0-9]|3[01])[\/](20)\d\d$/';
		$pattern3 = '/^(20)\d\d[\/](0[1-9]|1[012])[\/](0[1-9]|[12][0-9]|3[01])$/';
		$pattern4 = '/^(0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])[-](20)\d\d$/';

		$result1= preg_match($pattern1,$input);
		$result2= preg_match($pattern2,$input);
		$result3= preg_match($pattern3,$input);
		$result4= preg_match($pattern4,$input);

		if (($result1 ==0&&$result2==0&&$result3==0&&$result4==0)||!isset($input))
		{
			$this->errorMsg[] ="<p class='error'>Error:'$fieldname' is a required field that must contain mm-dd-yyyy or yyyy-mm-dd format only";
			$this->errorCount++;
			$result="";
			
		}
		else
		{
			$input = str_replace("/","-",$input);
			if($result2!=0||$result4!=0)
			{
				
				$newinput = explode("-", $input);
			
				$arrayInput=array();
				$arrayInput[0] = $newinput[2];
				$arrayInput[1] = $newinput[0];
				$arrayInput[2] = $newinput[1];
			
				$input = implode("-", $arrayInput);
			}
			$result = $input;
		}
		return $result;
	}

	public function dateCompare($startDate,$endDate)
	{
		$startDate=strtotime($startDate);
		$endDate=strtotime($endDate);
		if($endDate<$startDate)
		{
			$this->errorMsg[] ="<p class='error'><strong>Error:</strong> The end date cannot come before the start date!</p>";
			$this->errorCount++;
		}
	}


	public function validAlpha($input,$fieldname)
	{
		$pattern = "/^[A-Za-z'-\s]+$/";
		$result= preg_match($pattern,$input);
		if ($result ==0||!isset($input))
		{
			$this->errorMsg[] ="<p class='error'>Error:'$fieldname' is a required field that must contain letters only";
			$this->errorCount++;
			$result="";	
		}
		else
		{
			$result = $input;
		}
		return $result;
	}

	public function validEmptyAlpha($input,$fieldname)
	{
		$pattern = "/^[A-Za-z'-\s]{1,}$/";
		$result= preg_match($pattern,$input);
		if ($result ==0||!isset($input))
		{
			$this->errorMsg[] ="<p class='error'>Error:'$fieldname' is a required field that must contain letters";
			$this->errorCount++;
			$result="";	
		}
		else
		{
			$result = $input;
		}
		return $result;
	}
	
	public function validAlphaNum($input,$fieldname)
	{
		$pattern = "/^[A-Za-z0-9_'-\.$&!@;,()*\s]+$/";
		$result= preg_match($pattern,$input);
		if ($result ==0||!isset($input))
		{
			$this->errorMsg[] = "<p class='error'>Error:'$fieldname' is a required field that must contain valid characters(i.e.numbers, letters, and standard punctuation)";
			$this->errorCount++;
			$result="";	
		}
		else
		{
			$result = $input;
		}
		return $result;
	}
	public function confirmPass($input,$input2)
	{
	
		if (!(strlen($input)>=6)||!(strlen($input2)>=6))
		{
			$this->errorMsg[] ="<p class='error'>Error:The password or confirmation password is not greater than 6 characters.</p>";
			$this->errorCount++;
			$result="";	
		}
		else if($input!=$input2)
		{
			$this->errorMsg[] ="<p class='error'>Error:The password and confirmation password must match!</p>";
			$this->errorCount++;
			$result="";	

		}	
		else
		{
			$result = array($input,$input2);
		}
		return $result;
	}
	public function validKeyword($input,$fieldname)
	{
		$pattern = '/^[A-za-z0-9,\s]+$/';
		$result= preg_match($pattern,$input);
		if ($result ==0||!isset($input))
		{
			$this->errorMsg[] ="<p class='error'>Error:'$fieldname' is a required field and each keyword must be separated by a space";
			$this->errorCount++;
			$result="";	
		}
		else
		{
			
			$result = $input;
			$result=trim($result);
			$result=str_replace(' ','',$result);
			$result=strtolower($result);
			$result=explode(',',$result);
		}
		return $result;
	}
	
	public function validEmail($email)
	{
		if(!isset($email))
			$email=0;
	   $isValid = true;
	   $atIndex = strrpos($email, "@");
	   if (is_bool($atIndex) && !$atIndex)
	   {
		  $isValid = false;
	   }
	   else
	   {
		  $domain = substr($email, $atIndex+1);
		  $local = substr($email, 0, $atIndex);
		  $localLen = strlen($local);
		  $domainLen = strlen($domain);
		  if ($localLen < 1 || $localLen > 64)
		  {
			 // local part length exceeded
			 $isValid = false;
		  }
		  else if ($domainLen < 1 || $domainLen > 255)
		  {
			 // domain part length exceeded
			 $isValid = false;
		  }
		  else if ($local[0] == '.' || $local[$localLen-1] == '.')
		  {
			 // local part starts or ends with '.'
			 $isValid = false;
		  }
		  else if (preg_match('/\\.\\./', $local))
		  {
			 // local part has two consecutive dots
			 $isValid = false;
		  }
		  else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
		  {
			 // character not valid in domain part
			 $isValid = false;
		  }
		  else if (preg_match('/\\.\\./', $domain))
		  {
			 // domain part has two consecutive dots
			 $isValid = false;
		  }
		  else if
		  (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
					 str_replace("\\\\","",$local)))
		  {
			 // character not valid in local part unless 
			 // local part is quoted
			 if (!preg_match('/^"(\\\\"|[^"])+"$/',
				 str_replace("\\\\","",$local)))
			 {
				$isValid = false;
			 }
		  }
		  if ($isValid && !(checkdnsrr($domain,"MX") || 
	 	  checkdnsrr($domain,"A")))
		  {
			 // domain not found in DNS
			 $isValid = false;
		  }
	   }
	  if($isValid)
	  {
		$isValid = $email;
	  }
	  
	  else
	  {
		$this->errorCount++;
		$this->errorMsg[]="<p class='error'>Error: The email field is either empty or contains an invalid email.</p>";
		$isValid ="";		
	  }
	  return $isValid;
	}
	
	//return the current error count
	public function getErrorCount()
	{
		return $this->errorCount;		
	}

	/*increments the errorCount by one*/
	public function incErrorCount()
	{
	  $this->errorCount++;
	}

	public function addErrorMsgs($msg)
	{
	  $this->errorMsg[] = $msg;
	}

	public function printErrorMsgs()
	{
		$msg=$this->errorMsg;
		foreach($msg as $msgs)
		{
			echo $msgs;
		}
	}

}
?>