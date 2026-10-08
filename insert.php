<?php
$error = [];

if(isset($_POST['submit'])){

$name = $_POST['name'];
$email = $_POST['email'];
$salary = $_POST['salary'];
$designation = $_POST['designation'];

if(empty($name)){
    $error['name'] = "name is required";
    
}
if(empty($email)){
  $error['email'] = "Email is required";

}
if(empty($salary)){
 $error['salary'] = "filed  is required";

}elseif(!is_numeric($salary)){
    $error['salary'] = "please add number field  is required"; 


}elseif($salary <= 0){

 $error['salary'] = "salary should greter than zero"; 

}
 if(empty($designation )){
     $error['designation'] = "field is required "; 
 }

}

if(empty($error)){
   
}else{
    echo "plz fill all field";
}


?>