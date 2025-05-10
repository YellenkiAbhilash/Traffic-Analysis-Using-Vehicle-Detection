<?php
@include 'config.php';

session_start();

if(isset($_POST['submit'])){
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = $_POST['password']; 

   $select = " SELECT * FROM user_form WHERE email = '$email' && password = '$pass' ";

   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_array($result);

      if($row['user_type'] == 'allregionshead'){
         $_SESSION['admin_name'] = $row['name'];
         header('location:allregionshead.php');

      } elseif($row['user_type'] == 'regionhead') {
         $region = $row['region']; 
         if($region == 'a') {
            $_SESSION['user_name'] = $row['name'];
            header('location:region_a_page.php');
         } elseif($region == 'b') {
            $_SESSION['user_name'] = $row['name'];
            header('location:region_b_page.php');
         }
      } elseif($row['user_type'] == 'regionpolice') {
         $region = $row['region']; 
         if($region == 'a') {
            $_SESSION['user_name'] = $row['name'];
            header('location:region_a_police_page.php');
         } elseif($region == 'b') {
            $_SESSION['user_name'] = $row['name'];
            header('location:region_b_police_page.php');
         }
      }
     
   } else {
      $error[] = 'incorrect email or password!';
   }

};
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login form</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post">
      <h3>login now</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="email" name="email" required placeholder="enter your email">
      <input type="password" name="password" required placeholder="enter your password">
      <input type="submit" name="submit" value="login now" class="form-btn">
   </form>

</div>

</body>
</html>