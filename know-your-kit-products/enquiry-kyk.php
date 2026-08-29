<?php
 ######################################################
 #                                                    #
 #                Forms To Go 4.5.4                   #
 #             http://www.bebosoft.com/               #
 #                                                    #
 ######################################################
 
 define('kOptional', true);
 define('kMandatory', false);

 define('kStringRangeFrom', 1);
 define('kStringRangeTo', 2);
 define('kStringRangeBetween', 3);

 define('kYes', 'yes');
 define('kNo', 'no');

 error_reporting(E_ERROR | E_WARNING | E_PARSE);
 ini_set('track_errors', true);

 function DoStripSlashes($fieldValue)
 {
     // temporary fix for PHP6 compatibility - magic quotes deprecated in PHP6
     if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
         if (is_array($fieldValue)) {
             return array_map('DoStripSlashes', $fieldValue);
         } else {
             return trim(stripslashes($fieldValue));
         }
     } else {
         return $fieldValue;
     }
 }

 function FilterCChars($theString)
 {
     return preg_replace('/[\x00-\x1F]/', '', $theString);
 }

 function ProcessPHPFile($PHPFile)
 {
     ob_start();

     if (file_exists($PHPFile)) {
         require $PHPFile;
     } else {
         echo '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><title>Error</title></head><body>Forms To Go - Error: Unable to load HTML form: ' . $PHPFile . '</body></html>';
         exit();
     }

     return ob_get_clean();
 }

 function CheckString(
     $value,
     $low,
     $high,
     $mode,
     $limitAlpha,
     $limitNumbers,
     $limitEmptySpaces,
     $limitExtraChars,
     $optional
 ) {
     $regExp = '';

     if ($limitAlpha == kYes) {
         $regExp = 'A-Za-z';
     }

     if ($limitNumbers == kYes) {
         
     }

     if ($limitEmptySpaces == kYes) {
         $regExp .= ' ';
     }

     if (strlen($limitExtraChars) > 0) {
         $search = [
             '\\',
             '[',
             ']',
             '-',
             '$',
             '.',
             '*',
             '(',
             ')',
             '?',
             '+',
             '^',
             '{',
             '}',
             '|',
             '/',
         ];
         $replace = [
             '\\\\',
             '\[',
             '\]',
             '\-',
             '\$',
             '\.',
             '\*',
             '\(',
             '\)',
             '\?',
             '\+',
             '\^',
             '\{',
             '\}',
             '\|',
             '\/',
         ];

         $regExp .= str_replace($search, $replace, $limitExtraChars);
     }
 
     if ((strlen($regExp) > 0) && (strlen($value) > 0)) {
         if (preg_match('/[^' . $regExp . ']/', $value)) {
             return false;
         }
     }

     if ((strlen($value) == 0) && ($optional === kOptional)) {
         return true;
     } elseif ((strlen($value) >= $low) && ($mode == kStringRangeFrom)) {
         return true;
     } elseif ((strlen($value) <= $high) && ($mode == kStringRangeTo)) {
         return true;
     } elseif (
         (strlen($value) >= $low) &&
         (strlen($value) <= $high) &&
         ($mode == kStringRangeBetween)
     ) {
         return true;
     } else {
         return false;
     }
 }

 function CheckEmail($email, $optional)
 {
     if ((strlen($email) == 0) && ($optional === kOptional)) {
         return true;
     } elseif (
         preg_match(
             '/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i',
             $email
         ) == 1
     ) {
         return true;
     } else {
         return false;
     }
 }

// code inserted through enquiry.php from ptruniversal to test

if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
   } else {
    $clientIP = $_SERVER['REMOTE_ADDR'];
   }
   
   $FTGname = DoStripSlashes( $_POST['name'] );
   $FTGmobile = DoStripSlashes( $_POST['mobile'] );
   $FTGcity = DoStripSlashes( $_POST['city'] );
   $FTGemail = DoStripSlashes( $_POST['email'] );
   $FTGgender = DoStripSlashes( $_POST['gender'] );
   $FTGproducts = DoStripSlashes( $_POST['products'] );
   $FTGquery = DoStripSlashes( $_POST['query'] );  
   
   $validationFailed = false;
   
   # Fields Validations
   
   

   if (!CheckString($FTGname, 3, 100, kStringRangeBetween, kNo, kNo, kNo, '', kMandatory)) {
    $FTGErrorMessage['Name'] = 'Please enter your name correctly (minimum&Prime; characters)';
    $validationFailed = true;
   }
   
   if (!CheckEmail($FTGemail, kMandatory)) {
    $FTGErrorMessage['Email'] = 'Please enter a valid email address';
    $validationFailed = true;
   }
   
   if (!CheckString($FTGmobile, 8, 14, kStringRangeBetween, kNo, kYes, kNo, '', kMandatory)) {
    $FTGErrorMessage['Mobile'] = 'Please enter a valid phone number';
    $validationFailed = true;
   }
   
  
   
   # Embed error page and dump it to the browser
   
   if ($validationFailed === true) {
   
    $fileErrorPage = 'error.html';
   
    if (file_exists($fileErrorPage) === false) {
        echo '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><title>Error</title></head><body>The error page: <b>' . $fileErrorPage. '</b> cannot be found on the server.</body></html>';
        exit;
    }
   
    $errorPage = ProcessPHPFile($fileErrorPage);
   
    $errorList = @implode('<br />\n', $FTGErrorMessage);
    $errorPage = str_replace('<!--VALIDATIONERROR-->', $errorList, $errorPage); 
    $errorPage = str_replace('<!--FIELDVALUE:name-->', $FTGname, $errorPage);
    $errorPage = str_replace('<!--FIELDVALUE:email-->', $FTGemail, $errorPage);
    $errorPage = str_replace('<!--FIELDVALUE:mobile-->', $FTGmobile, $errorPage);
    $errorPage = str_replace('<!--FIELDVALUE:submitbtn-->', $FTGsubmit, $errorPage);
    // $errorPage = str_replace('<!--ERRORMSG:name-->', $FTGErrorMessage['Visitor-type'], $errorPage);
    $errorPage = str_replace('<!--ERRORMSG:name-->', $FTGErrorMessage['name'], $errorPage);
    $errorPage = str_replace('<!--ERRORMSG:email-->', $FTGErrorMessage['email'], $errorPage);
    $errorPage = str_replace('<!--ERRORMSG:mobile-->', $FTGErrorMessage['mobile'], $errorPage);
   
    echo $errorPage;
   
   }
   
   if ( $validationFailed === false ) {
   
    # Email to Form Owner
     
    ini_set("sendmail_from" , 'donotreply@fluencepharma.in');  
    $to = 'customercare@fluencepharma.com';
    $to = 'amitbhusari@fluencepharma.com';
    $to = 'amit.a.bhusari@gmail.com';
    // $to = 'devteam@ithink.co';
    $subject = "Know Your Kits Enquiry from Fluencepharma"; 
    $message = "<html>\n"
    . "<head>\n"
    . "<title></title>\n"
    . "</head>\n"
    . "<body>\n"
    . "Dear Sir/Madam,<br><br>\n"
    . "Congratulations! An Enquiry has been submitted from your <a href=\"https://www.fluencepharma.in\" target=\"_blank\">fluencepharma.in </a> - Know your Kit, with the following information:<br/><br/>\n" 
    . "<b>Name :</b> $FTGname<br />\n"
    . "<b>Email :</b> $FTGemail<br />\n"
    . "<b>City :</b> $FTGcity<br />\n"
    . "<b>Mobile :</b> $FTGmobile<br />\n"
    . "<b>Gender :</b> $FTGgender<br />\n"
    . "<b>Kit Name :</b> $FTGproducts<br />\n"
    . "<b>Message :</b> " . nl2br( $FTGquery ) . "<br /><br/>\n"
    . "This form is powered by <a href=\"www.ithink.co\" target=\"_blank\">www.iThink.co</a>\n"
    . "</body>\n"
    . "</html>\n"
    . ""
    . "\n";
   
    $headers ="From: donotreply@fluencepharma.in  \r\n"
   # . "Cc: marketing@fluencepharma.com , marketingteam@ithink.co \r\n"
    . "MIME-Version: 1.0 \r\n"
    . "Content-Type: text/html; charset=\"UTF-8\" \r\n"
    . "Content-Transfer-Encoding: base64 \r\n"; 
    if(mail($to,$subject,$message,$headers)){
     
    }
      
    # Confirmation Email to User
   
    $to_auto_responder = FilterCChars($FTGemail) ; 
    $subject_auto_responder = "Thank you for reaching out to us";  
    $message_auto_responder =  
    "<html>\n"
    . "<head>\n"
    . "<title></title>\n"
    . "</head>\n"
    . "<body>\n"
    . "Dear Sir/Madam,<br/><br/>\n"
    . "We are in receipt of your Enquiry submitted on <a href=\"www.fluencepharma.in\" target=\"_blank\">www.fluencepharma.in</a>  and Thank You for the same.<br/><br/>\n"
    . "We appreciate the opportunity to be of assistance during your search for the ideal solution with our $FTGproducts.<br/><br/>\n"
    . "We will reply to you within the next 24 Hours. In case your Query is Urgent you can also call us on <a href=\"tel:+91 22 26300106\">+91 22 26300106</a><br/><br/>\n"
    . "Fluence Pharma has been formed with the philosophy of inclusive happiness for all. Integrating Scientific Innovations to Transform you into Healthy Living.<br/><br/>\n"
    . "Regards,<br/><br/>\n"
    . "Fluencepharma<br/><br/>\n"
    . "<small><a href=\"www.fluencepharma.in\" target=\"_blank\">www.fluencepharma.in</a></small><br/><br/>\n"
    . "<small>Make the Web work for your Company - Web, Cloud, Digital, Social Media & more at <a href=\"https://www.ithink.co/\">Think Technology Services</a>.</small>\n"
    . "</body>\n"
    . "</html>\n"
    . "" ;

   
    $header_auto_responder = "From: donotreply@fluencepharma.in \r\n"
    . "MIME-Version: 1.0 \r\n"
    . "Content-Type: text/html; charset=\"UTF-8\" \r\n"
    . "Content-Transfer-Encoding: base64 \r\n"; 

    $result = mail($to_auto_responder,$subject_auto_responder,$message_auto_responder,$header_auto_responder);
  
# Redirect user to success page

#-------------------------------------------------------------------------------------------------------------------------------------------

# code to snd details to google sheets

$url = 'https://script.google.com/macros/s/AKfycbzYogcvJBEt5vcskczxw6EJVGgiYOPuK-omGL0XVxBhNFS9MCv7cMZmQcRtoCVbBCHu/exec';

$ch = curl_init();

$post_data = array('name'=>$_POST['name'],
               'email'=>$_POST['email'],
               'mobile'=>$_POST['mobile'],
               'city'=>$_POST['city'],
               'other'=>$_POST['city'],
               'gender' =>$_POST['gender'],
               'products' =>$_POST['products'],
              'query'=>$_POST['query'], 
            );

curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$info = curl_getinfo($ch);

// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close ($ch);
//print_r($result);
// header("Location: success.html");
echo("Thankyou, someone will reach out to you soon");
   }

?>
 
