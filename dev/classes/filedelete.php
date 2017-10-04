<?php
//delete a file from the server::param is a path


          $approot = "/home/custo299/public_html/calendar/";		
		$file = $approot.(string)$_POST['file'];
		//print_r($_POST);
		//echo $file;

		chmod($approot."csv", 0777);
		
		//echo 'the del function fired';
		if(is_file($file)) {
		    chmod($file, 0777);
		    if(unlink($file)){
			    $msg = json_encode("File has been deleted.");
			    echo $msg;
			     chmod($approot."csv", 0755);
		    }else{
			   $msg = json_encode("File was not deleted.");
			   echo $msg;
			   chmod($approot."csv", 0755);
		    }
		    exit;
		} else {
			$msg = json_encode("File not found.  Perhaps it has already been deleted.");		
			echo $msg;	
		}
		if(is_file($file)) {
			chmod($file, 0755);
		}
		if(is_dir($approot."csv")){
			chmod($approot."csv", 0755);
		}

?>