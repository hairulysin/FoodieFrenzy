<?php

include '../components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   
   if($select_admin->rowCount() > 0){
      $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
      $_SESSION['admin_id'] = $fetch_admin_id['id'];
      header('location:dashboard.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
      body {
         display: flex;
         justify-content: center;
         align-items: center;
         background-color: #263238;
      }
   </style>

</head>
<body id="login">

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- admin login form section starts  -->

<div class="form-container">
   <div class="login">
               <form action="" method="POST">
                  <h1>Login</h1>
                  <hr>
                  <p>Please enter your login details to continue.</p><br><br>
                  <!-- <label for="">Username</label> -->
                  <input type="text" name="name" maxlength="20" required placeholder="Username...">
                  <!-- <label for="">Password</label> -->
                  <input type="password" name="pass" maxlength="20" required placeholder="Password...">
                  <!-- <button>Login</button> -->
                  <input type="submit" value="login now" name="submit" class="btn">
               </form>  
   </div>
   <div class="right">
            <img src="..\images\Authentication-pana.svg" alt="">
   </div>
</div>

<!-- admin login form section ends -->
</body>
</html>