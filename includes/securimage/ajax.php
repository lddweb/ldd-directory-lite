<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' )
{


    foreach($_POST as $key => $value) {
        if (!is_array($key)) {
            // sanitize the input data
            if ($key != 'ct_message') $value = strip_tags($value);
            $_POST[$key] = htmlspecialchars(stripslashes(trim($value)));
        }
    }

    $captcha = @$_POST['ct_captcha']; // the user's entry for the captcha code

    $errors = array();  // initialize empty error array


    // Only try to validate the captcha if the form has no errors
    // This is especially important for ajax calls
    if (sizeof($errors) == 0) {
        require_once dirname(__FILE__) . '/securimage.php';
        $securimage = new Securimage();

        if ($securimage->check($captcha) == false) {
            $errors['captcha_error'] = 'Incorrect security code entered';
        }
    }

    if (sizeof($errors) == 0) {
        $return = array('error' => 0, 'message' => '');
        die(json_encode($return));
    } else {
        $errmsg = '';
        foreach($errors as $key => $error) {
            // set up error messages to display with each field
            $errmsg .= " - {$error}\n";
        }

        $return = array('error' => 1, 'message' => $errmsg);
        die(json_encode($return));
    }
    die;
} // POST

die;