<?php

require_once "../config.php";

session_start();

//register users
function registerUser($fullnames, $email, $password, $gender, $country)
{
    //create a connection variable using the db function in config.php
    $conn = db();
    //check if user with this email already exist in the database
    $check_sql = "SELECT * FROM `students` WHERE email = '$email' ";
    $query = mysqli_query($conn, $check_sql);
    $check = mysqli_num_rows($query);

    if ($check == 0) {
        // insert new user 
        // User Id is auto generated from the SQL Auto increment function.
        $insert_sql = "INSERT INTO `Students` (`full_names`, `country`, `email`, `gender`, `password`) 
    VALUES ('$fullnames', '$country', '$email', '$gender', '$password')";
        $query = mysqli_query($conn, $insert_sql);

        if ($query) {
            echo "<script> alert('User Successfully registered') 
            window.location.href = '../forms/login.html'
            </script>";
            
        } else {
            echo "<script> alert('Unable to register user, Try Again Later') 
            window.location.href = '../forms/register.html'
            </script>";
        }
    } else {
        echo "<script> alert('User Email Already taken') 
        window.location.href = '../forms/register.html'
        </script>";
    }

}


//login users
function loginUser($email, $password)
{
    //create a connection variable using the db function in config.php
    $conn = db();
    //open connection to the database and check if username exist in the database
    $check_sql = "SELECT * FROM `students` WHERE email = '$email' ";
    $query = mysqli_query($conn, $check_sql);
    $check = mysqli_num_rows($query);
    //if it does, check if the password is the same with what is given
    if ($check > 0) {
        // fetch user password
        while ($data = mysqli_fetch_assoc($query)) {
            $db_password = $data['password'];
            $fullnames = $data['full_names'];
        }
        // password check
        if($db_password == $password){
            $_SESSION['username'] = $fullnames;
            header('location: ../dashboard.php');

        }
        else{
            echo "<script> alert('Invalid User Password') 
        window.location.href = '../forms/login.html'
        </script>";
        }
    }
    else{
        echo "<script> alert('User Not Found') 
        window.location.href = '../forms/login.html'
        </script>";
    }
    
    //if true then set user session for the user and redirect to the dasbboard
}


function resetPassword($email, $password)
{
    //create a connection variable using the db function in config.php
    $conn = db();
    
    //open connection to the database and check if username exist in the database
    $check_sql = "SELECT * FROM `students` WHERE email = '$email' ";
    $query = mysqli_query($conn, $check_sql);
    $check = mysqli_num_rows($query);
    //if it does, replace the password with $password given
    if ($check > 0) {
        $update_sql = "UPDATE `students` SET `password` = '$password' WHERE `email` = '$email' ";
        $query = mysqli_query($conn, $update_sql);
        if($query){
            echo "<script> alert('User Password Successfully Changed') 
        window.location.href = '../forms/login.html'
        </script>";
        }
        else{
            echo "<script> alert('Unable to reset user password') 
        window.location.href = '../forms/resetpassword.html'
        </script>";
        }
    }
    else {
        echo "<script> alert('User Email Not Found') 
        window.location.href = '../forms/resetpassword.html'
        </script>";
    }

}

function getusers()
{
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo "<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if (mysqli_num_rows($result) > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            //show data
            echo "<tr style='height: 30px'>" .
                "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_names'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] .
                "</td> <td style='width: 150px'>" . $data['country'] .
                "</td>
                <form action='action.php' method='post'>
                <input type='hidden' name='id'" .
                "value=" . $data['id'] . ">" .
                "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>" .
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

function deleteaccount($id)
{
    $conn = db();
    //delete user with the given id from the database
    $delete_sql = "DELETE FROM `students` WHERE id = '$id' ";
    $query = mysqli_query($conn,$delete_sql);
    if($query){
        echo "<script> alert('User Deleted Successfully !!')</script>";
        // show all users after deletion
        getusers();
    }
    else {
        echo "<script> alert('Unable To Delete User')</script>";
        // Go to previous page with javascript
        echo "<script> window.history.back() </script>";
    }
}

