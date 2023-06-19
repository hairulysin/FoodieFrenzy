<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['update_payment'])) {
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'Payment status updated!';
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <!-- Load jQuery and DataTables CSS -->
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

   <style>
      .table-responsive {
         margin-top: 30px;
      }
   </style>
</head>

<body>

   <?php include '../components/admin_header.php' ?>

   <!-- placed orders section starts  -->
   <section class="dashboard">
      <h1 class="heading">Placed Orders</h1>
      <br><br>
      <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if ($select_orders->rowCount() > 0) {
      ?>
         <div class="container1">
         <hr>
         <div class="row">
            <div class="col-md-12">
            <table id="placed_orders_table" class="table">
               <thead>
                  <tr>
                     <th>User ID</th>
                     <th>Placed on</th>
                     <th>Name</th>
                     <th>Number</th>
                     <th>Address</th>
                     <th>Total products</th>
                     <th>Total price</th>
                     <th>Payment method</th>
                     <th>Payment status</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                     <tr>
                        <td><?= $fetch_orders['user_id']; ?></td>
                        <td><?= $fetch_orders['placed_on']; ?></td>
                        <td><?= $fetch_orders['name']; ?></td>
                        <td><?= $fetch_orders['number']; ?></td>
                        <td><?= $fetch_orders['address']; ?></td>
                        <td><?= $fetch_orders['total_products']; ?></td>
                        <td>Rp.<?= $fetch_orders['total_price']; ?>/-</td>
                        <td><?= $fetch_orders['method']; ?></td>
                        <td>
                           <form action="" method="POST">
                              <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                              <select name="payment_status" class="drop-down">
                                 <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
                                 <option value="pending">pending</option>
                                 <option value="completed">completed</option>
                              </select>
                              <!-- <div class="flex-btn">
                                 <input type="submit" value="update" class="btn" name="update_payment">
                                 <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
                              </div> -->
                           </form>
                        </td>
                        <td>
                        <div class="flex-btn1">
                                 <input type="submit" value="update" class="btn" name="update_payment">
                                 <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
                              </div>
                        </td>
                     </tr>
                  <?php
                  }
                  ?>
               </tbody>
            </table>
         </div>
            </div>
         </div>
         </div>
      <?php
      } else {
         echo '<p class="empty">No orders placed yet!</p>';
      }
      ?>
   </section>
   <!-- placed orders section ends -->

   <!-- Load jQuery and DataTables JS -->
   <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

   <!-- Init DataTables -->
   <script type="text/javascript">
      $(document).ready(function() {
         $('#placed_orders_table').DataTable({
            lengthMenu: [5, 10, 15, 20] // Menentukan pilihan jumlah entri per halaman
         });
      });
   </script>

   <!-- custom js file link  -->
   <script src="../js/admin_script.js"></script>
</body>

</html>
