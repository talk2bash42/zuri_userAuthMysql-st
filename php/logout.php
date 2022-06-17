<?php
session_start();

function logout()
{
    /*
Check if the existing user has a session
if it does
destroy the session and redirect to login page
*/

    // check if user is logged in
    if(isset($_SESSION['username'])) {
        // delete user datails
        unset($_SESSION['username']);
        // destroy session
        session_destroy();
        // redirect user to login page
        header('location: ../forms/login.html');
    } 

    else {
        // destroy session
        session_destroy();
        // redirect user to login page
        header('location: ../forms/login.html');
    }
}

// call logout function
logout();
?>