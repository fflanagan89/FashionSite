<?php
include'top.php';
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%5
//
//SECTION: 1 Initialize variables
//
// SECTION:1a
// we print out the post array so that we can see our form is working.
// if($debug){//later you can uncomment the if statement 
print'<p>Post Array:</p><pre>';
print_r($_POST);
print '</pre>';
//}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%5
//
//SECTION:1B Security
//
//define security variable to be used in SECTION 2a.

$thisURL = $domain . $phpSelf;


//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%5
//
//SECTION:1c form variables
//
// Initialzie variables one for each form element
// in the order they appear on the form

$firstName = "";
$lastName="";

$email = "shuang10@uvm.edu";
$comments = "";

$gender = "choose";
$dog = false; // not checked
$cat = false; //not checked
$bird=false;
$city = "choose an option"; // pick the option
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//
//SECTION:1d form error flags
//
//Initialize error flags one for each form elemtn we validate in the order they appear in section 1c.
$commentsERROR = false;
$firstNameERROR = false;
$lastNameERROR=false;
$emailERROR = false;
$genderERROR = false;
$activityERROR = false;
$totalChecked = 0;
$cityERROR = false;
////%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//
//SECTION:1e misc variables
//
//create array to hold error messages filled(if any) in 2d displayed in 3c.
$errorMsg = array();

//array used to hold form values that will wrtten to a CSV file
$dataRecord = array();

//have we mailed the information to the user?
$mailed = false;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@2
    //
        //SECTION:2a Security 
    //
     if (!securityCheck($thisURL)) {
        $msg = '<p>Sorry you cannot access this page.';
        $msg = 'Security breach detechted and reported.</p>';
        die($msg);
    }


    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
       //SECTION: 2b Sanitize(clean) data
    //remove any potential JavaScript or html code form users input on the 
    //form. Note it is best to follow the same order as declared in section 1c.
    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $firstName;
    
    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $lastName;
    
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;
    
    $comments = htmlentities($_POST["txtComments"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $comments;

    $gender = htmlentities($_POST["radGender"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $gender;
    $city = htmlentities($_POST["1stCity"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $city;
//note if a check box is not checded it is not sent in the Post array.
    if (isset($_POST["chkDog"])) {
        $dog = true;
        $totalChecked++;
    } else {
        $dog = false;
    }
    $dataRecord[] = $dog;
    /* the above saves true(a number 1 in your csv file) or false(empty)
     * you could save the value of the check box
     * if(isset($_POST["chkHiking"])){
     * $hiking=true;
     * $dataRecord[]=htmlentities($_POST["chkHiking"],ENT_QUOTES,"UTF-8");
     * $totalChecked++; // count how many are checked if you need to }else{
     * $hiking=false;
     * $dataRecord[]="";}
     */
    if (isset($_POST["chkCat"])) {
        $cat = true;
        $totalChecked++;
    } else {
        $cat = false;
    }
    $dataRecord[] = $cat;
        if (isset($_POST["chkBird"])) {
        $bird = true;
        $totalChecked++;
    } else {
        $bird = false;
    }
    $dataRecord[] = $bird;
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2c  Validation
//
// Validation section.Check each value for possible errors, empty or not what we expect. You will need an IF block for each element you will check(see above section 1c and 1d). the if blocks should also be in the order that the elements appear on your form so that the error messages will be in the order they appear. errorMsg will be displayed on the form see section 3b.The error flag($emailERROR) will be used in section 3c.

    if ($firstName == "") {
        $errorMsg[] = "Please enter your first name";
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg[] = "Your first name appears to have extra character.";
        $firstNameERROR = true;
    }
      if ($lastName == "") {
        $errorMsg[] = "Please enter your last name";
        $lastNameERROR = true;
    } elseif (!verifyAlphaNum($lastName)) {
        $errorMsg[] = "Your last name appears to have extra character.";
        $lastNameERROR = true;
    }


    if ($email == "") {
        $errorMsg[] = 'Please enter your email address';
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = 'Your email address appears to be incorrect.';
        $emailERROR = true;
    }
    if ($comments != "") {
        if (!verifyAlphaNum($comments)) {
            $errorMsg = "Your comments appear to have extra characters that are not allowed.";
            $commentsERROR = true;
        }
    }
    if ($gender != "Male" AND $gender != "Female" AND $gender!="Transgender") {
        $errorMsg[] = "Please choose a gender";
        $genderERROR = true;
    }
    if ($totalChecked < 1) {
        $errorMsg[] = "Please choose at least one activity";
        $activityERROR = true;
    }
    if ($city == "choose an option") {
        $errorMsg[] = "Please choose a favorite city";
        $cityERROR = true;
    }





    if (!$errorMsg) {
        if ($debug)
            print PHP_EOL . '<p>Form is valid</p>';


        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
 //SECTION:2e Save Data
//
//This block saves the data to a CSV file.
        $myFolder = 'data/';

        $myFileName = 'registration';

        $fileExt = '.csv';

        $filename = $myFolder . $myFileName . $fileExt;
        if ($debug)
            print PHP_EOL . '<p>filename is ' . $filename;

//now we just open the file for append
        $file = fopen($filename, 'a');

        //write the forms informations
        fputcsv($file, $dataRecord);

        //close the file
        fclose($file);


        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
 //SECTION:2f Create message
        //
 // build a message to display on the screen in section 3a and to mail to the person filling out the form(section 2g).

        $message = '<h2>Your information.</h2>';

        foreach ($_POST as $htmlName => $value) {

            $message .= '<p>';
            //break up the form names into words.for example,txtFirstName becomes First Name

            $camelCase = preg_split('/(?=[A-Z])/', substr($htmlName, 3));

            foreach ($camelCase as $oneWord) {
                $message .= $oneWord . ' ';
            }

            $message .= '=' . htmlentities($value, ENT_QUOTES, "UTF-8") . '</p>';
        }

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        // SECTION: 2g Mail to user
        //
   // Process for mailinng a message which contains the forms data
        //the message was built in section 2f.

        $to = $email; //the person who filled out the form
        $cc = '';
        $bcc = '';

        $from = 'WRONG site <customer.service@yoursite.com>';

//subject of mail should make sense to your form
        $subject = 'Changing Earth; ';

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    }  //end form is valid
} // ends if form was submitted
//###################################################################
//
//SECTION 3 Display Form
//
?>
<article id="main">

<?php
//####################################33
//
    //SECTION 3a.
//
    //If its the first time coming to the form or there are errors we are going
// to display the form.

if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) {// closing of if marked with end body submit
    print'<h2>Thank you for providing your information</h2>';

    print'<p>For your records a cope of this data has ';


    if (!$mailed) {
        print "not";
    }
    print 'been sent:</p>';
    print '<p>To:' . $email . '</p>';

    print $message;
} else {

    print'<h2>Register Today</h2>';
    print '<p class="form-heading">You information will greatly help us with our research</p>';

    //##################################
    //
     //SECTION 3b Error Messages
    //
     //display any error messages before we print out the form

    if ($errorMsg) {
        print'<div id="errors">' . PHP_EOL;
        print'<h2>Your form has the following mistakes that need to be fixed.</h2>' . PHP_EOL;
        print '<ol>' . PHP_EOL;

        foreach ($errorMsg as $err) {
            print '<li>' . $err . '</li>' . PHP_EOL;
        }

        print '</ol>' . PHP_EOL;
        print'</div>' . PHP_EOL;
    }


    //###########################
    //
     //SECTION 3c html Form 
    //
     /* Display the HTML form. note that the action is to this same page.$phpSelf is defined in top.php
      NOTE the line:
      value="<?php print $email; ?>"
      this makes the form sticky by displaying either the initial default value(line ??) or the value they typed in (line??)
      NOTE this line:
      <?php if($emailERROR) print 'class="mistake"';?>
      this print out a css class so that we can highlight the background etc. to make it stand out that a mistake happend here.
     */
    ?>

        <form action="<?php print $phpSelf; ?>"
              id="frmRegister"
              method="post">

            <fieldset class="contact">
                <legend>Contact Information</legend>
                <p>       
                    <label class="required text-field" for="txtFirstName">First Name</label>           
                    <input autofocus
    <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                           id="txtFirstName"
                           maxlength="45"
                           name="txtFirstName"
                           onfocus="this.select()"
                           placeholder="Enter your first name"
                           tabindex="100"
                           type="text"
                           value="<?php print $firstName; ?>"
                           >
                </p>
                <p>
                    <label class="required text-field" for="txtLastName">Last Name</label>
                    <input
                             <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                                            id="txtLastName"
                        maxlength="45"
                        name="txtLastName"
                        onfocus="this.select()"
                        placeholder="Enter your last name"
                        tabindex="110"
                        type="text"
                        value="<?php print $lastName; ?>"
                        >
                </p> 


                <p>
                    <label class="required text-field" for="txtEmail">Email</label>
                    <input   
    <?php if ($emailERROR) print 'class="mistake"'; ?>
                        id="txtEmail"
                        maxlength="45"
                        name="txtEmail"
                        onfocus="this.select()"
                        placeholder="Enter a valid email address"
                        tabindex="120"
                        type="text"
                        value="<?php print $email; ?>"
                        >
                </p>
            </fieldset> <!-- ends contact -->
            <fieldset class="textarea">
                <p>
                    <label class="required" for="txtComments">Comments</label>
                    <textarea<?php if ($commentsERROR) print 'class="mistake"'; ?>
                        id="txtComments"
                        name="txtComments"
                        onfocus="this.select()"
                        tabindex="200"><?php print $comments; ?></textarea>
                    <!-- NOTE:no blank spaces inside the text area, be sure to close the text are directly-->
                </p>
            </fieldset><!-- ends -->
            <fieldset class ="radio<?php if ($genderERROR) print ' mistake'; ?>">
                <legend>what is your gender?</legend>
             
                <p>
                    <label class="radio-field">
                        <input
                             
                            type="radio"
                               id="radGenderMale"
                               name="radGender"
                               value="Male"
                               tabindex="572"
    <?php if ($gender == "Male") echo 'checked="checked"'; ?>>
                        Male</label>
                </p>
                <p>
                    <label class="radio-field">
                        <input 
                            type="radio"
                               id="radGenderFemale"
                               name="radGender"
                               value="Female"
                               tabindex="582"
    <?php if ($gender == "Female") echo 'checked="checked"'; ?>>
                        Female</label>

                </p>
                 <p>
                    <label class="radio-field">
                        <input
                             
                            type="radio"
                               id="radGenderTransgender"
                               name="radTransgender"
                               value="Transgender"
                               tabindex="592"
    <?php if ($gender == "Transgender") echo 'checked="checked"'; ?>>
                        Transgender</label>
                </p>
                
            </fieldset><!--end of radio female and male--> 
            <fieldset class="checkbox <?php if ($activityERROR) print ' mistake'; ?>">
                <legend>What is your favirate pet(choose at least one and check all that apply):</legend>
               
                <p>
                    <label class="check-field">
                        <input 
                            <?php if ($dog) print "checked"; ?>
                            id="chkDog"
                            name="chkDog"
                            tabindex="420"
                            type="checkbox"
                            value="Dog">Dog</label>

                </p>
                <p>
                    <label class="check-field">
                        <input <?php if ($cat) print "checked"; ?>
                            id="chkCat"
                            name="chkCat"
                            tabindex="430"
                            type="checkbox"
                            value="Cat">Cat</label>
                </p>
                  <p>
                    <label class="check-field">
                        <input <?php if ($bird) print "checked"; ?>
                            id="chkBird"
                            name="chkBird"
                            tabindex="440"
                            type="checkbox"
                            value="Bird">Bird</label>
                </p>
                
            </fieldset>  <!-- end of check box-->
            <fieldset class="listbox<?php if ($cityERROR) print ' mistake'; ?>">
              
                
                <legend>what is your Favorite city</legend>
              
                    <p>
                <select id="1stCity"
                        name="1stCity"
                        tabindex="520">
                     <option <?php if ($city == "choose an option") print "selected"; ?>
                        value="choose an option"> </option>
                    <option <?php if ($city == "burlington") print "selected"; ?>
                        value="burlington">burlington</option>
                    <option <?php if ($city == "New York") print "selected"; ?>
                        value="New York">New York</option> 
                    <option <?php if ($city == "Montreal") print "selected"; ?>
                        value="Montreal">Montreal</option>
                </select>
                </p>
                
            </fieldset>

            <fieldset class="buttons">
                <legend></legend>
                <input class="button" id="btnSubmit" name="btnSubmit" tabindex="900" type="submit" value="Register" >
            </fieldset> <!-- ends buttons-->
        </form>


    <?php
} // end body submit
?>

</article>

    <?php include 'footer.php'; ?>

</body>
</html>