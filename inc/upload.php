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
$inc_dir = "../inc/class_images.php";
if(isset($_FILES["myfile"]))
{
	$ret = array();

	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
		require_once $inc_dir;
			$uploaddir 	= $output_dir;
			$ext = pathinfo( $_FILES['myfile']['name'], PATHINFO_EXTENSION);
			$file_name = md5(time().uniqid());
			$temp_name = $file_name.'_temp';
			$temp_name_with_ext =$file_name.'_temp'.$ext;
			$file_name_with_ext = $file_name .'.'.$ext;
			move_uploaded_file($_FILES['myfile']['tmp_name'], $uploaddir.$temp_name_with_ext);
			
			$image = new Image($uploaddir.$temp_name_with_ext);
			
			$image->resize(621, 300, 'crop', 'c', 'c', 99);
			$image->save($file_name, $uploaddir);
			
			$thumb = new Image($uploaddir.$temp_name_with_ext);
			$thumb->resize(278, 120, 'crop', 'c', 'c', 99);
			$thumb->save($file_name.'_s', $uploaddir);
			unlink ($uploaddir.$temp_name_with_ext); 
 	 	
    	echo $file_name_with_ext;
	}
	/* else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["myfile"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["myfile"]["name"][$i];
		move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
	  	$ret[]= $fileName;
	  }
	  echo json_encode($ret);
	} */
   
 }

/*
	Omit PHP closing tag to help avoid accidental output
*/