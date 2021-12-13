<?php
function Check_admin_access($email,$pass){
	
	if($email=="admin@gmail.com" && $pass=="admin123"){
		return true;
	}
	return false;
}

?>