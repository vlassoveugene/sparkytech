<?php
if (isset($_POST['email'])) {

    // REPLACE THIS 2 LINES AS YOU DESIRE
    $email_to = "eugene.vlassov@sparkytech.org";
    $email_subject = "Get Started Submission";

    function problem($error)
    {
        echo "Oh looks like there is some problem with your form data: <br><br>";
        echo $error . "<br><br>";
        echo "Please fix those to proceed.<br><br>";
        die();
    }

    // validation expected data exists
    if (
        !isset($_POST['fullName']) ||
        !isset($_POST['phone']) ||
        !isset($_POST['email']) ||
        !isset($_POST['rname']) ||
        !isset($_POST['domain']) ||
        !isset($_POST['raddress']) ||
        !isset($_POST['rphone']) ||
        !isset($_POST['remail']) ||
      
        !isset($_POST['features']) ||
        !isset($_POST['message'])
    ) {
        problem('Oh looks like there is some problem with your form data.');
    }

    $name = $_POST['fullName']; // required
    $phone = $_POST['phone']; // required
    $email = $_POST['email']; // required
    $rname = $_POST['rname']; // required
    $domain = $_POST['domain']; // required
    $raddress = $_POST['raddress']; // required
    $rphone = $_POST['rphone']; // required
    $remail = $_POST['remail']; // required
 
    $features = $_POST['features']; // required
    $message = $_POST['message']; // required

    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

    if (!preg_match($email_exp, $email)) {
        $error_message .= 'Email address does not seem valid.<br>';
    }

    $string_exp = "/^[A-Za-z .'-]+$/";

    if (!preg_match($string_exp, $name)) {
        $error_message .= 'Name does not seem valid.<br>';
    }

    if (strlen($message) < 2) {
        $error_message .= 'Message should not be less than 2 characters<br>';
    }

    if (strlen($error_message) > 0) {
        problem($error_message);
    }

    $email_message = "Form details following:\n\n";

    function clean_string($string)
    {
        $bad = array("content-type", "bcc:", "to:", "cc:", "href");
        return str_replace($bad, "", $string);
    }

    $boundary = uniqid();


    $file = $_FILES["attachment"]["tmp_name"];
    $filename = $_FILES["attachment"]["name"];
    $attachment = chunk_split(base64_encode(file_get_contents($file)));


    $email_message .= "Name: " . clean_string($name) . "\n";
    $email_message .= "Phone Number: " . clean_string($phone) . "\n";
    $email_message .= "Email: " . clean_string($email) . "\n";
    $email_message .= "- - - - - - - - - - - - - - - - - - - - - - - - - - -" . "\n";
    $email_message .= "Restaurant Name: " . clean_string($rname) . "\n";
    $email_message .= "Domain: " . clean_string($domain) . "\n";
    $email_message .= "Restaurant Address: " . clean_string($raddress) . "\n";
    $email_message .= "Restaurant Phone Number: " . clean_string($rphone) . "\n";
    $email_message .= "Restaurant Email: " . clean_string($remail) . "\n";
    $email_message .= "Features: " . clean_string($features) . "\n";
    $email_message .= "Message: " . clean_string($message) . "\n";

    $email_message = "--".$boundary."\r\n";
    $email_message .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $email_message .= "Content-Transfer-Encoding: base64\r\n\r\n";
  
    $email_message .= "--".$boundary."\r\n";
    $email_message .= "Content-Type: application/octet-stream; name=\"file.pdf\"\r\n";
    $email_message .= "Content-Transfer-Encoding: base64\r\n";
    $email_message .= "Content-Disposition: attachment; filename=\"file.pdf\"\r\n\r\n";
    $email_message .= $attachment."\r\n";
    $email_message .= "--".$boundary."--";

    // create email headers
    $headers = "From: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\".$boundary.\"\r\n";
    
    @mail($email_to, $email_subject, $email_message, $headers);
?>

    <!-- Replace this as your success message -->

    Thanks for contacting us, we will get back to you as soon as possible.

<?php
}
?>