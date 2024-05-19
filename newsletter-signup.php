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

$email = addslashes(trim($_POST['MAIL']));

//form validation
$errors = array();
$fields = array();
$email_pattern = "/^[a-zA-Z0-9][a-zA-Z0-9\.-_]+\@([a-zA-Z0-9-_]+\.)+[a-zA-Z]+$/";
if(!$email) {
	$errors[] = "Please enter your e-mail address.";
	$fields[] = "news_email";
} else if(!preg_match($email_pattern, $email)) {
	$errors[] = "The e-mail address you provided is invalid.";
	$fields[] = "news_email";	
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

	$content = 'E-mail: '.$email.'<br>'.
	'Time: '.$timestamp.'<br>'.
	'IP: '.$host.'<br>'.
	'User agent: '.$user_agent;	

	//sending mail
	$ok = mail("PUT YOUR E-MAIL HERE","Message MultiPurpose Template", $content, $headers);
	if($ok) {
		$response['msgStatus'] = "ok";
		$response['message'] = "Thank you, you will hear from us soon.";
	} else {
		$response['msgStatus'] = "error";
		$response['message'] = "An error occured while trying to adda you address to the subscription list. Please try again later.";
	}
} else {
	$response['msgStatus'] = "error";
	$response['message'] = $errors[0];
	$response['errorFields'] = $fields;
}

header('Content-type: application/json');
echo json_encode($response);
?>
