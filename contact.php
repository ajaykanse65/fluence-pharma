<?PHP
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

define('kNumberRangeFrom', 1);
define('kNumberRangeTo', 2);
define('kNumberRangeBetween', 3);




error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('track_errors', true);

function DoStripSlashes($fieldValue)  { 
// temporary fix for PHP6 compatibility - magic quotes deprecated in PHP6
 if ( function_exists( 'get_magic_quotes_gpc' ) && get_magic_quotes_gpc() ) { 
  if (is_array($fieldValue) ) { 
   return array_map('DoStripSlashes', $fieldValue); 
  } else { 
   return trim(stripslashes($fieldValue)); 
  } 
 } else { 
  return $fieldValue; 
 } 
}

function FilterCChars($theString) {
 return preg_replace('/[\x00-\x1F]/', '', $theString);
}

function ProcessPHPFile($PHPFile) {
 
    ob_start();
    
    if (file_exists($PHPFile)) {
     require $PHPFile;
    } else {
     echo '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><title>Error</title></head><body>Forms To Go - Error: Unable to load HTML form: ' . $PHPFile . '</body></html>';
     exit;
    }
    
    return ob_get_clean();
   }
   
function CheckString($value, $low, $high, $mode, $limitAlpha, $limitNumbers, $limitEmptySpaces, $limitExtraChars, $optional) {

 $regEx = '';

 if ($limitAlpha == kYes) {
  $regExp = 'A-Za-z';
 }
 
 if ($limitNumbers == kYes) {
  $regExp .= '0-9'; 
 }
 
 if ($limitEmptySpaces == kYes) {
  $regExp .= ' '; 
 }

 if (strlen($limitExtraChars) > 0) {
 
  $search = array('\\', '[', ']', '-', '$', '.', '*', '(', ')', '?', '+', '^', '{', '}', '|', '/');
  $replace = array('\\\\', '\[', '\]', '\-', '\$', '\.', '\*', '\(', '\)', '\?', '\+', '\^', '\{', '\}', '\|', '\/');

  $regExp .= str_replace($search, $replace, $limitExtraChars);

 }

 if ( (strlen($regExp) > 0) && (strlen($value) > 0) ){
  if (preg_match('/[^' . $regExp . ']/', $value)) {
   return false;
  }
 }

 if ( (strlen($value) == 0) && ($optional === kOptional) ) {
  return true;
 } elseif ( (strlen($value) >= $low) && ($mode == kStringRangeFrom) ) {
  return true;
 } elseif ( (strlen($value) <= $high) && ($mode == kStringRangeTo) ) {
  return true;
 } elseif ( (strlen($value) >= $low) && (strlen($value) <= $high) && ($mode == kStringRangeBetween) ) {
  return true;
 } else {
  return false;
 }

}


function CheckNumeric($value, $low, $high, $mode, $optional) {
 if ( (strlen($value) == 0) && ($optional === kOptional) ) {
  return true;
 } elseif (!is_numeric($value)) {
  return false;
 } elseif ( ($value >= $low) && ($mode == kNumberRangeFrom) ) {
  return true;
 } elseif ( ($value <= $high) && ($mode == kNumberRangeTo) ) {
  return true;
 } elseif ( ($value >= $low) && ($value <= $high) && ($mode == kNumberRangeBetween) ) {
  return true;
 } else {
  return false;
 }
}


function CheckEmail($email, $optional) {
 if ( (strlen($email) == 0) && ($optional === kOptional) ) {
  return true;
  } elseif ( preg_match("/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i", $email) == 1 ) {
  return true;
 } else {
  return false;
 }
}




if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
 $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
 $clientIP = $_SERVER['REMOTE_ADDR'];
}

$FTGcategory = DoStripSlashes( $_POST['category'] );
$FTGproducts = DoStripSlashes( $_POST['products'] );
$FTGMale_Kits = DoStripSlashes( $_POST['Male_Kits'] );
$FTGFemale_Kits = DoStripSlashes( $_POST['Female_Kits'] );
$FTGVeg_Kits = DoStripSlashes( $_POST['Veg_Kits'] );
$FTGPro_Immune_Kits = DoStripSlashes( $_POST['Pro_Immune_Kits'] );
$FTGWomens_Functional_Kits = DoStripSlashes( $_POST['Womens_Functional_Kits'] );
$FTGFunctional = DoStripSlashes( $_POST['Functional'] );
$FTGKids = DoStripSlashes( $_POST['Kids'] );
$FTGMaintenance = DoStripSlashes( $_POST['Maintenance'] );
$FTGSkin_Fact = DoStripSlashes( $_POST['Skin_Fact'] );
$FTGScalp_Application = DoStripSlashes( $_POST['Scalp_Application'] );
$FTGname = DoStripSlashes( $_POST['name'] );
$FTGlastName = DoStripSlashes( $_POST['lastName'] );
$FTGemail = DoStripSlashes( $_POST['email'] );
$FTGcontactNumber = DoStripSlashes( $_POST['contactNumber'] );
$FTGmessage = DoStripSlashes( $_POST['message'] );

// foreach ($FTGMale_Kits as $FTGMale_Kit){
//   if($FTGMale_Kit == 'none'){
//     continue;
//   }
//   $FTGMale_Kits = $FTGMale_Kit.", ";
//   echo ($FTGMale_Kits);
// }

$validationFailed = false;

# Fields Validations


// if (!CheckString($FTGname, 1, 60, kStringRangeBetween, kYes, kNo, kYes, '', kMandatory)) {
//  $FTGErrorMessage['name'] = 'Please enter your name';
//  $validationFailed = true;
// }

// if (!CheckString($FTGlastName, 1, 100, kStringRangeBetween, kYes, kNo, kYes, '', kMandatory)) {
//  $FTGErrorMessage['lastName'] = 'Please enter your company name';
//  $validationFailed = true;
// }

$FTGErrorMessage['email'] ="";
$FTGErrorMessage['contactNumber'] = "";
if (!CheckEmail($FTGemail, kMandatory)) {
 $FTGErrorMessage['email'] = 'Please enter your valid email address';
 $validationFailed = true;
}

// if (strlen($FTGcontactNumber) !== 10) {
//  $FTGErrorMessage['contactNumber'] = 'Please enter 10 digit phone number';
//  $validationFailed = true;
// }



# Include message in error page and dump it to the browser

if ($validationFailed === true) {

    $fileErrorPage = 'error.html';

    if (file_exists($fileErrorPage) === false) {
     echo '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><title>Error</title></head><body>The error page: <b>' . $fileErrorPage. '</b> cannot be found on the server.</body></html>';
     exit;
    }
   
    $errorPage = ProcessPHPFile($fileErrorPage);


 $errorPage = str_replace('<!--FIELDVALUE:category-->', $FTGcategory, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:products-->', $FTGproducts, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:Male_Kits-->', $FTGMale_Kits, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:Female_Kits-->', $FTGFemale_Kits, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:Veg_Kits-->', $FTGVeg_Kits, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:Pro_Immune_Kits-->', $FTGPro_Immune_Kits, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:Womens_Functional_Kits-->', $FTGWomens_Functional_Kits, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:Functional-->', $FTGFunctional, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:Kids-->', $FTGKids, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:Maintenance-->', $FTGMaintenance, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:Skin_Fact-->', $FTGSkin_Fact, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:Scalp_Application-->', $FTGScalp_Application, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:name-->', $FTGname, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:lastName-->', $FTGlastName, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:email-->', $FTGemail, $errorPage);
//  $errorPage = str_replace('<!--FIELDVALUE:contactNumber-->', $FTGcontactNumber, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:message-->', $FTGmessage, $errorPage);
//  $errorPage = str_replace('<!--ERRORMSG:name-->', $FTGErrorMessage['name'], $errorPage);
//  $errorPage = str_replace('<!--ERRORMSG:lastName-->', $FTGErrorMessage['lastName'], $errorPage);
 $errorPage = str_replace('<!--ERRORMSG:email-->', $FTGErrorMessage['email'], $errorPage);
 $errorPage = str_replace('<!--ERRORMSG:contactNumber-->', $FTGErrorMessage['contactNumber'], $errorPage);


 $errorList = @implode("<br />\n", $FTGErrorMessage);
 $errorPage = str_replace('<!--VALIDATIONERROR-->', $errorList, $errorPage);
echo (nl2br($subject));


 echo $errorPage;

}
$FTGMale_Kitsf='';
$FTGFemale_Kitsf ='';
$FTGVeg_Kitsf = '';
$FTGPro_Immune_Kitsf='';
$FTGWomens_Functional_Kitsf='';
$FTGFunctionalf='';
$FTGKidsf='';
$FTGMaintenancef='';
$FTGSkin_Factf='';
$FTGScalp_Applicationf='';

foreach ($FTGMale_Kits as $FTGMale_Kit){
  if($FTGMale_Kit == 'none'){
    continue;
  }
  $FTGMale_Kitsf = $FTGMale_Kitsf."".$FTGMale_Kit.", ";
}


foreach ($FTGFemale_Kits as $FTGFemale_Kit){
  if($FTGFemale_Kit == 'none'){
    continue;
  }
  $FTGFemale_Kitsf = $FTGFemale_Kitsf."".$FTGFemale_Kit.", ";
}
foreach ($FTGVeg_Kits as $FTGVeg_Kit){
  if($FTGVeg_Kit == 'none'){
    continue;
  }
  $FTGVeg_Kitsf = $FTGVeg_Kitsf."".$FTGVeg_Kit.", ";
}


foreach ($FTGPro_Immune_Kits as $FTGPro_Immune_Kit){
  if($FTGPro_Immune_Kit == 'none'){
    continue;
  }
  $FTGPro_Immune_Kitsf = $FTGPro_Immune_Kitsf."".$FTGPro_Immune_Kit.", ";
}


foreach ($FTGWomens_Functional_Kits as $FTGWomens_Functional_Kit){
  if($FTGWomens_Functional_Kit == 'none'){
    continue;
  }
  $FTGWomens_Functional_Kitsf = $FTGWomens_Functional_Kitsf."".$FTGWomens_Functional_Kit.", ";
}


foreach ($FTGFunctional as $FTGFunctiona){
  if($FTGFunctiona == 'none'){
    continue;
  }
  $FTGFunctionalf = $FTGFunctionalf."".$FTGFunctiona.", ";
}


foreach ($FTGKids as $FTGKid){
  if($FTGKid == 'none'){
    continue;
  }
  $FTGKidsf = $FTGKidsf."".$FTGKid.", ";
}


foreach ($FTGMaintenance as $FTGMaintenanc){
  if($FTGMaintenanc == 'none'){
    continue;
  }
  $FTGMaintenancef = $FTGMaintenancef."".$FTGMaintenanc.", ";
}


foreach ($FTGSkin_Fact as $FTGSkin_Fac){
  if($FTGSkin_Fac == 'none'){
    continue;
  }
  $FTGSkin_Factf = $FTGSkin_Factf."".$FTGSkin_Fac.", ";
}


foreach ($FTGScalp_Application as $FTGScalp_Applicatio){
  if($FTGScalp_Applicatio == 'none'){
    continue;
  }
  $FTGScalp_Applicationf = $FTGScalp_Applicationf."".$FTGScalp_Applicatio.", ";
}

if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
  $captcha=$_POST['g-recaptcha-response'];
$secret_key='6LfYPt0cAAAAAGJXy8PCUbe_KSjF5oRZm4rbqnVF';
  $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret_key."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
  $obj = json_decode($response);
}

// If the Google Recaptcha box was not clicked
else {
http_response_code(400);
echo "Please click the reCAPTCHA box.";
}
if ( $validationFailed === false && $obj->success == true) {

  # Email to Form Owner
  
 $emailSubject = FilterCChars("Enquiry Submitted on fluencepharma.in");
  
 $emailBody = chunk_split( base64_encode( "<html>\n"
  . "<head>\n"
  . "<title></title>\n"
  . "</head>\n"
  . "<body>\n"
  . "Dear Sir/Madam,<br /><br />\n"
  . "Congratulations! An Enquiry has been submitted from your <a href=\"www.fluencepharma.in\" target=\"_blank\">www.fluencepharma.in</a> with the Following information:<br /><br />\n"
  . "<b>Name :</b> $FTGname<br />\n"
  . "<b>Contact :</b> $FTGcontactNumber<br />\n"
  . "<b>Email :</b> $FTGemail<br />\n"
  . "<b>Company Name :</b> $FTGlastName<br />\n"
  ."<b>Category :</b> $FTGcategory<br />\n"
  . "<b>Products :</b> $FTGproducts<br />\n"
  . "<b>Male Kits :</b> $FTGMale_Kitsf<br />\n"
  . "<b>Female Kits :</b> $FTGFemale_Kitsf<br />\n"
  . "<b>Veg Kits : </b>$FTGVeg_Kitsf<br />\n"
  . "<b>Pro Immune Kits :</b> $FTGPro_Immune_Kitsf<br />\n"
  . "<b>Womens Functional Kits :</b> $FTGWomens_Functional_Kitsf<br />\n"
  . "<b>Functional :</b> $FTGFunctionalf<br />\n"
  . "<b>Kids :</b> $FTGKidsf<br />\n"
  . "<b>Maintenance :</b> $FTGMaintenancef<br />\n"
  . "<b>Skin Fact : </b>$FTGSkin_Factf<br />\n"
  . "<b>Scalp Application :</b> $FTGScalp_Applicationf<br />\n"
  . "<b>Message :</b> " . nl2br( $FTGmessage ) . "<br /><br /><br />\n"
  . "This form is powered by www.iThink.co\n"
  . "</body>\n"
  . "</html>\n"
  . "" ) )
  . "\n";
  $emailTo = 'Fluencepharma <customercare@fluencepharma.com>';
  $emailTo = 'Fluencepharma <amitbhusari@fluencepharma.com >';
  $emailTo = 'Fluencepharma <amit.a.bhusari@gmail.com>';
//   $emailTo = 'Fluencepharma <devteam@ithink.co>';

  $emailFrom = FilterCChars("donotreply@fluencepharma.in");
   
  $emailHeader = "From: $emailFrom\n"
   #. 'Cc: Think Technology Services - Marketing <siddhi@ithink.co>,Adhir Varma - Think Technology Services <adhir@ithink.co>' . "\n"
   #. 'Bcc: Think Technology Services - Marketing <marketing@ithink.co>,Think Technology Services - Digital Marketing <dm@ithink.co>,<thinktech1@gmail.com>' . "\n"
   . 'Bcc: Think Technology Services - <thinktech1@gmail.com>' . "\n"
   . "MIME-Version: 1.0\n"
   . "Content-Type: text/html; charset=\"UTF-8\"\n"
   . "Content-Transfer-Encoding: base64\n"
   . "\n";
   
  mail($emailTo, $emailSubject, $emailBody, $emailHeader);
  
  
 # Confirmation Email to User
  
 $confEmailTo = FilterCChars($FTGemail);
  
 $confEmailSubject = FilterCChars("Thank You for reaching out to us.");
  
 $confEmailBody = chunk_split( base64_encode( "<html>\n"
  . "<head>\n"
  . "<title></title>\n"
  . "</head>\n"
  . "<body>\n"
  . "Dear Sir/Madam,<br/><br/>\n"
  . "We are in receipt of your Enquiry submitted on <a href=\"www.fluencepharma.in\" target=\"_blank\">www.fluencepharma.in</a> and Thank You for the same.<br/><br/>\n"
  . "We will reply to you within the next 24 Hours. In case your Query is Urgent you can also call us on <a href=\"tel:+91 22 26300106\">+91 22 26300106</a><br/><br/>\n"
  . "Fluence Pharma has been formed with the philosophy of inclusive happiness for all. Integrating Scientific Innovations to Transform you into Healthy Living.<br/><br/>\n"
  . "Regards,<br/><br/>\n"
  . "Fluencepharma<br/><br/>\n"
  . "<small><a href=\"www.fluencepharma.in\" target=\"_blank\">www.fluencepharma.in</a></small><br/><br/>\n"
  . "<small>Make the Web work for your Company - Web, Cloud, Digital, Social Media & more at <a href=\"https://www.ithink.co/\">Think Technology Services</a>.</small>\n"
  . "</body>\n"
  . "</html>\n"
  . "" ) )
  . "\n";
  
 $confEmailHeader = "From: donotreply@fluencepharma.in\n"
  . "MIME-Version: 1.0\n"
  . "Content-type: text/html; charset=\"UTF-8\"\n"
  . "Content-Transfer-Encoding: base64\n"
  . "\n";
  
 mail($confEmailTo, $confEmailSubject, $confEmailBody, $confEmailHeader);
  


# Redirect user to success page

header("Location: success.html");

}
// If the Google Recaptcha check was not successful
else {
  http_response_code(400);
  echo "Robot verification failed. Please try again.";
}
?>