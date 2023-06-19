<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <!-- Load jQuery and DataTables CSS -->
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
</head>
<body>

   <?php include '../components/admin_header.php' ?>

   <!-- messages section starts  -->

   <section class="messages">

      <h1 class="heading">messages</h1>

      <div class="container2">
         <?php
         $select_messages = $conn->prepare("SELECT * FROM `messages`");
         $select_messages->execute();
         if ($select_messages->rowCount() > 0) {
            echo '<table id="messages_table" class="custom-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Name</th>';
            echo '<th>Number</th>';
            echo '<th>Email</th>';
            echo '<th>Message</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)) {
               echo '<tr>';
               echo '<td>' . $fetch_messages['name'] . '</td>';
               echo '<td>' . $fetch_messages['number'] . '</td>';
               echo '<td>' . $fetch_messages['email'] . '</td>';
               echo '<td>' . $fetch_messages['message'] . '</td>';
               echo '<td>';
               echo '<a href="messages.php?delete=' . $fetch_messages['id'] . '" class="delete-btn" onclick="return confirm(\'delete this message?\');">delete</a>';
               echo '</td>';
               echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
         } else {
            echo '<p class="empty">you have no messages</p>';
         }
         ?>

      </div>

   </section>

   <!-- messages section ends -->

   <!-- custom js file link  -->
   <script src="../js/admin_script.js"></script>

   <!-- Load jQuery and DataTables JS -->
   <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

   <!-- Init DataTables -->
   <script type="text/javascript">
      $(document).ready(function() {
         $('#messages_table').DataTable();
      });
   </script>

</body>
</html>
