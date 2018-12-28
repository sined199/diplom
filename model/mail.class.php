<?php

/**
* 
*/
class ModelMail{
	
	function __construct($argument=null){
		
	}

	public function send_registration_mail($user_data,$key){
		$to = $user_data['email'];
		$headers  = 'From: sender@gmail.com' . "\r\n" .
            'Reply-To: sender@gmail.com' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
		$message = $key;
		$subject = "Accept key";
		if(mail($to, $subject, $message, $headers)){
			return "mail send";
		}
		else{
			return "Error send";
		}

	}
}