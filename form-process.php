<?php
if($_POST && isset($_FILES['file']))
{
    header("Location: payment.html");
	$recipient_email 	= "eugene.vlassov@sparkytech.org"; //recepient
	$from_email 		= "info@sparkytech.org"; //from email using site domain.
	$subject			= "Contact Form Submission"; //email subject line
	
	$sender_name = filter_var($_POST["s_name"], FILTER_SANITIZE_STRING); //capture sender name
    $sender_phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING); //capture sender email
	$sender_email = filter_var($_POST["s_email"], FILTER_SANITIZE_STRING); //capture sender email
    $rname = filter_var($_POST["rname"], FILTER_SANITIZE_STRING); //capture sender email
    $domain = filter_var($_POST["domain"], FILTER_SANITIZE_STRING); //capture sender email
    $raddress = filter_var($_POST["raddress"], FILTER_SANITIZE_STRING); //capture sender email
    $rphone = filter_var($_POST["rphone"], FILTER_SANITIZE_STRING); //capture sender email
    $remail = filter_var($_POST["remail"], FILTER_SANITIZE_STRING); //capture sender email
	$sender_message = filter_var($_POST["s_message"], FILTER_SANITIZE_STRING); //capture message
	$attachments = $_FILES['file'];
	
	//php validation
    if(strlen($sender_name)<4){
        die('Name is too short or empty');
    }
	if (!filter_var($sender_email, FILTER_VALIDATE_EMAIL)) {
	  die('Invalid email');
	}
    if(strlen($sender_message)<4){
        die('Too short message! Please enter something');
    }
	
	$file_count = count($attachments['name']); //count total files attached
	$boundary = md5("sanwebe.com"); 
			
	if($file_count > 0){ //if attachment exists
		//header
        $headers = "MIME-Version: 1.0\r\n"; 
        $headers .= "From:".$from_email."\r\n"; 
        $headers .= "Reply-To: ".$sender_email."" . "\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n"; 
        
        //message text
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n"; 
        $body .= chunk_split(base64_encode("Name: " . $sender_name . "\n")); 
        $body .= chunk_split(base64_encode("Phone: " . $sender_phone . "\n"));
        $body .= chunk_split(base64_encode("Email Address: " . $sender_email . "\n"));
        $body .= chunk_split(base64_encode("Restaurant Name: " . $rname . "\n"));
        $body .= chunk_split(base64_encode("Domain Name: " . $domain . "\n"));
        $body .= chunk_split(base64_encode("Restaurant Address: " . $raddress . "\n"));
        $body .= chunk_split(base64_encode("Restaurant Phone Number: " . $rphone . "\n"));
        $body .= chunk_split(base64_encode("Restaurant Email Address: " . $remail . "\n"));
        $body .= chunk_split(base64_encode("Message: " . $sender_message . "\n"));

      

		//attachments
		for ($x = 0; $x < $file_count; $x++){		
			if(!empty($attachments['name'][$x])){
				
				if($attachments['error'][$x]>0) //exit script and output error if we encounter any
				{
					$mymsg = array( 
					1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini", 
					2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form", 
					3=>"The uploaded file was only partially uploaded", 
					4=>"No file was uploaded", 
					6=>"Missing a temporary folder" ); 
					die($mymsg[$attachments['error'][$x]]); 
				}
				
				//get file info
				$file_name = $attachments['name'][$x];
				$file_size = $attachments['size'][$x];
				$file_type = $attachments['type'][$x];
				
				//read file 
				$handle = fopen($attachments['tmp_name'][$x], "r");
				$content = fread($handle, $file_size);
				fclose($handle);
				$encoded_content = chunk_split(base64_encode($content)); //split into smaller chunks (RFC 2045)
				
				$body .= "--$boundary\r\n";
				$body .="Content-Type: $file_type; name=\"$file_name\"\r\n";
				$body .="Content-Disposition: attachment; filename=\"$file_name\"\r\n";
				$body .="Content-Transfer-Encoding: base64\r\n";
				$body .="X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n"; 
				$body .= $encoded_content; 
			}
		}

	}else{ //send plain email otherwise
       $headers = "From:".$from_email."\r\n".
        "Reply-To: ".$sender_email. "\n" .
        "X-Mailer: PHP/" . phpversion();
        $body .= $sender_message;
        $body .= $sender_phone;
        $body .= $sender_name;
        $body .= $sender_email;
        $body .= $rname;
        $body .= $domain;
        $body .= $raddress;
        $body .= $rphone;
        $body .= $remail;
	}
		
	 $sentMail = @mail($recipient_email, $subject, $body, $headers);
	if($sentMail) //output success or failure messages
	{       
		die('Thank you for your email');
	}else{
		die('Could not send mail! Please check your PHP mail configuration.');  
	}
}
?>