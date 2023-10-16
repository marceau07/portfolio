<?php 
header('Access-Control-Allow-Origin: *');
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

if(!empty($_POST["login_marod"])) {
	$sql_tmp_user = "SELECT user_password FROM marod_users WHERE user_nickname=:user_nickname;";
	$req_tmp_user = $db->prepare($sql_tmp_user);
	$req_tmp_user->bindParam(":user_nickname", $_POST["login_username"]);
	$req_tmp_user->execute();
	$tmp_user = $req_tmp_user->fetch(PDO::FETCH_ASSOC);
	
	if(password_verify($_POST["login_password"], $tmp_user["user_password"])) {
		die(json_encode(array("success" => true, "message" => "Connecté")));		
	}
	
	die(json_encode(array("success" => false, "message" => "Une erreur s'est produite")));
} elseif(!empty($_POST["signup_marod"])) {
	$sql_signup = "INSERT INTO marod_users(user_nickname, user_email, user_password) VALUES(:user_nickname, :user_email, :user_password);";
	$req_signup = $db->prepare($sql_signup);
	$req_signup->bindParam(":user_nickname", $_POST["signup_username"]);
	$req_signup->bindParam(":user_email", $_POST["signup_email"]);
	$hashed_password = password_hash($_POST["signup_password"], PASSWORD_DEFAULT);
	$req_signup->bindParam(":user_password", $hashed_password);
	if($req_signup->execute()) {
		die(json_encode(array("success" => true, "message" => "Compte créé")));
	}
	
	die(json_encode(array("success" => false, "message" => "Une erreur s'est produite")));
}

?>