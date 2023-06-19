<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
   $select_user->execute([$email, $number]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'email or number already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $profile_photo = $_FILES['profile_photo']['name'];
         $profile_photo_tmp_name = $_FILES['profile_photo']['tmp_name'];
         $profile_photo_folder = 'uploaded_img/'.$profile_photo;

         if(!empty($profile_photo_tmp_name)){
            move_uploaded_file($profile_photo_tmp_name, $profile_photo_folder);
         }
         
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password, profile_photo) VALUES(?,?,?,?,?)");
         $insert_user->execute([$name, $email, $number, $cpass, $profile_photo]);
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if($select_user->rowCount() > 0){
            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<section class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>register now</h3>
      <input type="text" name="name" required placeholder="Name" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="Email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="Number Phone" class="box" min="0" max="9999999999" maxlength="13">
      <input type="password" name="pass" required placeholder="Password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirm Password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="file" name="profile_photo" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <input type="submit" value="register now" name="submit" class="btn">
      <p>Already have an account? <a href="login.php">Login</a></p>
   </form>

</section>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>


<?php include 'components/footer.php'; ?>







<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
