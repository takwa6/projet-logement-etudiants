<?php

include 'connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = create_unique_id();
   setcookie('user_id', $user_id, time() + 60*60*24*30, '/');
   header('location:index.php');
}

if(isset($_POST['cancel'])){

   $booking_id = $_POST['booking_id'];
   $booking_id = filter_var($booking_id, FILTER_SANITIZE_STRING);

   $verify_booking = $conn->prepare("SELECT * FROM `bookings` WHERE booking_id = ?");
   $verify_booking->execute([$booking_id]);

   if($verify_booking->rowCount() > 0){
      $delete_booking = $conn->prepare("DELETE FROM `bookings` WHERE booking_id = ?");
      $delete_booking->execute([$booking_id]);
      $success_msg[] = 'booking cancelled successfully!';
   }else{
      $warning_msg[] = 'booking cancelled already!';
   }
   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>bookings</title>
   <style>
/*ANIMATION*/
.animate {
-webkit-animation: animatezoom 0.6s;
animation: animatezoom 0.6s
}

@-webkit-keyframes animatezoom {
from {-webkit-transform: scale(0)} 
to {-webkit-transform: scale(1)}
}

@keyframes animatezoom {
from {transform: scale(0)} 
to {transform: scale(1)}
}

body{
margin:0;
background-image:url("https://source.unsplash.com/featured/?house");
background-repeat:no-repeat;
background-size:cover;
width:100%;
height:100%;
}
.box-container{
  background-color: rgba(255,255,255,0.5);
}


</style>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet
" href="admin_style.css">

</head>
<body>
<?php include 'admin_header.php'; ?>
<!-- booking section starts  -->


<section class="grid">
   <h1 class="heading">my bookings</h1>

   <div class="box-container">
   <?php
      $select_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ?");
      $select_bookings->execute([$user_id]);
      if($select_bookings->rowCount() > 0){
         while($fetch_booking = $select_bookings->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>name : <span><?= $fetch_booking['name']; ?></span></p>
      <p>email : <span><?= $fetch_booking['email']; ?></span></p>
      <p>number : <span><?= $fetch_booking['number']; ?></span></p>
      <p>check in : <span><?= $fetch_booking['check_in']; ?></span></p>
      <p>check out : <span><?= $fetch_booking['check_out']; ?></span></p>
      <p>rooms : <span><?= $fetch_booking['rooms']; ?></span></p>
      <p>nbr_persons : <span><?= $fetch_booking['nbr_persons']; ?></span></p>
      <p>type_housing : <span><?= $fetch_booking['type_housing']; ?></span></p>
      <p>booking id : <span><?= $fetch_booking['booking_id']; ?></span></p>
      <form action="" method="POST">
         <input type="hidden" name="booking_id" value="<?= $fetch_booking['booking_id']; ?>">
         <input type="submit" value="cancel booking" name="cancel" class="btn" onclick="return confirm('cancel this booking?');">
      </form>
   </div>
   <?php
    }
   }else{
   ?>   
   <div class="box" style="text-align: center;">
      <p style="padding-bottom: .5rem; text-transform:capitalize;">no bookings found!</p>
      <a href="index.php#reservation" class="btn">book new</a>
   </div>
   <?php
   }
   ?>
   </div>

</section>

<!-- booking section ends -->


























<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="script.js"></script>
<?php include 'connect.php'; ?>
<?php include 'message.php'; ?>
</body>
</html>