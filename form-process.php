<?php
if (isset($_POST['email'])) {
    header('Location: payment.html');
    // REPLACE THIS 2 LINES AS YOU DESIRE
    $email_to = "eugene.vlassov@sparkytech.org";
    $email_subject = "You've got a new submission";

    function problem($error)
    {
        echo "Oh looks like there is some problem with your form data: <br><br>";
        echo $error . "<br><br>";
        echo "Please fix those to proceed.<br><br>";
        die();
    }

    // validation expected data exists
    if (
        !isset($_POST['company']) ||
        !isset($_POST['name']) ||
        !isset($_POST['email']) ||
        !isset($_POST['phone'])
        !isset($_POST['domain'])
        !isset($_POST['address'])
        !isset($_POST['restaurantphone'])
        !isset($_POST['remailaddress'])
        !isset($_POST['file'])
        !isset($_POST['feature'])
       
     

    ) {
        problem('Oh looks like there is some problem with your form data.');
    }
    $company = $_POST['company'];
    $name = $_POST['name']; // required
    $email = $_POST['email']; // required
    $message = $_POST['message']; // required
    $message = $_POST['phone']; // required
    $message = $_POST['domain']; // required
    $message = $_POST['address']; // required
    $message = $_POST['restaurantphone']; // required
    $message = $_POST['remailaddress']; // required
    $message = $_POST['file']; // required
    $message = $_POST['feature']; // required
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

    $email_message .= "Name: " . clean_string($name) . "\n";
    $email_message .= "Phone Number: " . clean_string($phone) . "\n";
    $email_message .= "Email: " . clean_string($email) . "\n";
    $email_message .= "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -" . "\n";
    $email_message .= "Company: " . clean_string($company) . "\n";
    $email_message .= "Domain: " . clean_string($domain) . "\n";
    $email_message .= "Restaurant Address: " . clean_string($address) . "\n";
    $email_message .= "Restaurant Phone Number: " . clean_string($restaurantphone) . "\n";
    $email_message .= "Restaurant Email Address: " . clean_string($remailaddress) . "\n";
    $email_message .= "Features: " . clean_string($feature) . "\n";
    $email_message .= "Message: " . clean_string($message) . "\n";
    $email_message .= "Menu Files: " . clean_string($file) . "\n";

    // create email headers
    $headers = 'From: ' . $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    @mail($email_to, $email_subject, $email_message, $headers);
?>

    Thank you! Your order has been placed. Check your email for confirmation.
    
    We will have your website ready within 2 weeks.

<?php
}
?>