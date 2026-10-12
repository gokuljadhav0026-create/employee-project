<?php
session_start();
$error = [];
include "connection.php";

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $salary = $_POST['salary'];
    $designation = $_POST['designation'];

    if (empty($name)) {
        $error['name'] = "name is required";
    }
    if (empty($email)) {
        $error['email'] = "Email is required";
    }
    if (empty($salary)) {
        $error['salary'] = "filed  is required";
    } elseif (!is_numeric($salary)) {
        $error['salary'] = "please add number field  is required";
    } elseif ($salary <= 0) {

        $error['salary'] = "salary should greter than zero";
    }
    if (empty($designation)) {
        $error['designation'] = "field is required ";
    }
     if (empty($error)) {

    $_SESSION['success'] = "Employee Added Sucessfully";
   
    $stmt = $conn->prepare("INSERT INTO employees (name, email, salary , designation	) VALUES (?, ?, ? , ?)");

    $stmt->bind_param(
        "ssis",
        $name,
        $email,
        $salary,
        $designation
    );


    // Execute query
    if ($stmt->execute()) {

        header("Location: index.php"); 
          exit;
    } else {

        echo "Something went wrong";
    }


    // Close statement
    $stmt->close();
} else{
    $_SESSION['faield'] = "Please Fill All Field";
    
     header("Location: addemployee.php"); 
     exit;
}


}




//edit
if (isset($_POST['edit'])) {
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $salary = $_POST['salary'];
    $designation = $_POST['designation'];
    $id = $_POST['id'];
     

    
if (empty($name)) {
        $error['name'] = "name is required";
    }
    if (empty($email)) {
        $error['email'] = "Email is required";
    }
    if (empty($salary)) {
        $error['salary'] = "filed  is required";
    } elseif (!is_numeric($salary)) {
        $error['salary'] = "please add number field  is required";
    } elseif ($salary <= 0) {

        $error['salary'] = "salary should greter than zero";
    }
    if (empty($designation)) {
        $error['designation'] = "field is required ";
    }
     if (empty($error)) {
       
   $id = $_POST['id'];

$sql = "UPDATE employees
        SET name = ?, email = ?, salary = ?, designation = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisi", $name, $email, $salary, $designation, $id);
$stmt->execute();
if($stmt->execute()){
    $_SESSION['update'] = "Record Updated Sucess Fully" ;
    header("Location: index.php"); 

}
    
} else{
    
}


}

