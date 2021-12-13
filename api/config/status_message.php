<?php
	function status_message($statuscode,$message){
			http_response_code($statuscode);
			// tell the user no products found
			echo json_encode(
				array("message" => $message)
			);
			if($message=="re-pass does not match with password" || $message=="Id is Required" || $message=="UnAuthorised User" || $message=="Invalid Api Key Passed" ||$message="UnAuthorised To Delete this product from wishlist" || $message=="Invalid Admin Credentials" || $message=="id is Required"){
				die();
			}
	}
?>