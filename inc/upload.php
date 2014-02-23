<?php
/* don't allow this page to be requested directly from browser */	

require_once '../../../qa-include/qa-base.php';
require_once QA_INCLUDE_DIR.'qa-app-users.php';
// Security Check
if(qa_get_logged_in_level()<QA_USER_LEVEL_ADMIN){
		header('Location: /');
		exit;
}

$output_dir = "../uploads/";
if(isset($_FILES["myfile"]))
{
	$ret = array();

	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
 	 	$fileName = $_FILES["myfile"]["name"];
 		move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
    	echo $fileName;
	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["myfile"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["myfile"]["name"][$i];
		move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
	  	$ret[]= $fileName;
	  }
	  echo json_encode($ret);
	}
   
 }

/*
	Omit PHP closing tag to help avoid accidental output
*/