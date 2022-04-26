<?php
    include "connection.php";
    session_start();
    $email = $_SESSION['email'];
    $password = $_POST['pass'];
    $cpasword = $_POST['cpass'];
    $rpass="";
    $sql="SELECT password FROM useracc WHERE email='$email'";
    $result = mysqli_query($conn,$sql);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            $rpass= $row['password'];
        }
    }
    
    echo "$rpass<br>";
    if($rpass==null){
        //echo "null<br>";
        if ($password==$cpasword){
            //echo "cpass<br>";
            $sql="INSERT INTO useracc VALUES ('$email','$password')";
            $result = mysqli_query($conn,$sql);
            //echo mysqli_error($conn);
            if ($result){
                echo "<script>alert('Account Created Successfully');window.location.replace('index.php')</script>";
            }
        }
        else{
            //echo "cpass else<br>";
            echo "<script>alert('Oops! Password not matched!');window.location.replace('set_pass.php')</script>";
        }
    }
    else{
        echo "2ns else<br>";
        if ($password==$cpasword){
            $sql="UPDATE useracc set password='$password' WHERE email='$email'";
            $result = mysqli_query($conn,$sql);
            if ($result){
                echo "<script>alert('Password Changed Successfully');window.location.replace('index.php')</script>";
            }
        }
        else{
            echo "<script>alert('Oops! Password not matched!');window.location.replace('set_pass.php')</script>";
        }

    }


?>