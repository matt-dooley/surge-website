<?php
$name = '';	 
$subject = '';	
$email = '';
$message = '';
    
function getIp()
{if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
	$ip_address=$_SERVER['HTTP_X_FORWARDED_FOR'];
}

if (!isset($ip_address)){
		if (isset($_SERVER['REMOTE_ADDR']))	
		$ip_address=$_SERVER['REMOTE_ADDR'];
}
return $ip_address;
}


//taking the data from form	

$name = addslashes(trim($_POST['name']));	
$subject = addslashes(trim($_POST['subject']));	
$email = addslashes(trim($_POST['email']));
$message = addslashes(trim($_POST['message']));

//form validation
$errors = array();
$fields = array();
if(!$name) {
	$errors[] = "Please enter your name.";
	$fields[] = "name";
}
$email_pattern = "/^[a-zA-Z0-9][a-zA-Z0-9\.-_]+\@([a-zA-Z0-9_-]+\.)+[a-zA-Z]+$/";
if(!$email) {
	$errors[] = "Please enter your e-mail address.";
	$fields[] = "email";
} else if(!preg_match($email_pattern, $email)) {
	$errors[] = "The e-mail address you provided is invalid.";
	$fields[] = "email";	
}
if(!$subject) {
	$errors[] = "Please choose the subject of your message.";
	$fields[] = "subject";
}
if(!$message) {
	$errors[] = "Please enter your message.";
	$fields[] = "message";
}

//preparing mail
if(!$errors) {
	//taking info about date, IP and user agent
	$timestamp = date("Y-m-d H:i:s");
	$ip   = getIp();
	$host = gethostbyaddr($ip); 
	$user_agent = $_SERVER["HTTP_USER_AGENT"];

	$headers = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=utf-8\n";
	$headers .= "Content-Transfer-Encoding: quoted-printable\n";
	$headers .= "From: $email\n";

	$content = 'Subject: '.$subject.'<br>'.
	'Name: '.$name.'<br>'.
	'E-mail: '.$email.'<br>'.
	'Message: '.$message.'<br>'.
	'Time: '.$timestamp.'<br>'.
	'IP: '.$host.'<br>'.
	'User agent: '.$user_agent;	

	//sending mail
	$ok = mail("mdooley86@yahoo.com","Message MultiPurpose Template", $content, $headers);
	if($ok) {
		$response['msgStatus'] = "ok";
		$response['message'] = "Thank you for contacting the team at example.com.\nWe will respond to your inquiry as soon as possible.";
	} else {
		$response['msgStatus'] = "error";
		$response['message'] = "An error occured while trying to send your message. Please try again later.";
	}
} else {
	$response['msgStatus'] = "error";
	$response['errors'] = $errors;
	$response['errorFields'] = $fields;
}

header('Content-type: application/json');
echo json_encode($response);
?>
