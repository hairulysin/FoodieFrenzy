<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

// Fetch data from products table
$select_products = $conn->prepare("SELECT * FROM `products`");
$select_products->execute();
$numbers_of_products = $select_products->rowCount();

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <!-- Bootstrap data table -->
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">

   <h1 class="marquee"><span> Welcome <?= $fetch_profile['name']; ?>   Great to have you back in the foodie dashboard !</span></h1>

   <div class="box-container">
      <div class="box">
         <?php
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_pendings->execute(['pending']);
         while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
            $total_pendings += $fetch_pendings['total_price'];
         }
         ?>
         <h3><span>Rp. </span><?= $total_pendings; ?><span></span></h3>
         <p>total pendings</p>
         <a href="placed_orders.php" class="btn">see orders</a>
      </div>

      <div class="box">
         <?php
         $total_completes = 0;
         $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_completes->execute(['completed']);
         while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
            $total_completes += $fetch_completes['total_price'];
         }
         ?>
         <h3><span>Rp. </span><?= $total_completes; ?><span></span></h3>
         <p>total completes</p>
         <a href="placed_orders.php" class="btn">see orders</a>
      </div>

      <div class="box">
         <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         $numbers_of_orders = $select_orders->rowCount();
         ?>
         <h3><?= $numbers_of_orders; ?></h3>
         <p>total orders</p>
         <a href="placed_orders.php" class="btn">see orders</a>
      </div>

      <div class="box">
         <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         $numbers_of_products = $select_products->rowCount();
         ?>
         <h3><?= $numbers_of_products; ?></h3>
         <p>products added</p>
         <a href="products.php" class="btn">see products</a>
      </div>

      <div class="box">
         <?php
         $select_users = $conn->prepare("SELECT * FROM `users`");
         $select_users->execute();
         $numbers_of_users = $select_users->rowCount();
         ?>
         <h3><?= $numbers_of_users; ?></h3>
         <p>users accounts</p>
         <a href="users_accounts.php" class="btn">see users</a>
      </div>

      <div class="box">
         <?php
         $select_messages = $conn->prepare("SELECT * FROM `messages`");
         $select_messages->execute();
         $numbers_of_messages = $select_messages->rowCount();
         ?>
         <h3><?= $numbers_of_messages; ?></h3>
         <p>new messages</p>
         <a href="messages.php" class="btn">see messages</a>
      </div>

   </div>

   <div class="container">
      <hr>
      <div class="row">
         <div class="col-md-12">
            <h2>Product List</h2>

            <!-- <button class="btn print-all-btn">Print All</button>  -->
            <table id="product_table" class="table table-bordered table-striped w-100">
               <thead>
                  <tr>
                     <th>ID</th>
                     <th>Name</th>
                     <th>Category</th>
                     <th>Price</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  // $select_products = $conn->prepare("SELECT * FROM `products`");
                  // $select_products->execute();
                  // $products = $select_products->fetchAll();
                  // foreach ($products as $product) {
                  //    echo "<tr>";
                  //    echo "<td>" . $product['id'] . "</td>";
                  //    echo "<td>" . $product['name'] . "</td>";
                  //    echo "<td>" . $product['category'] . "</td>";
                  //    echo "<td>" . $product['price'] . "</td>";
                  //    echo "</tr>";
                  // }
                  // Fetch products based on search query or print all products
                  $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
                  $select_products = $conn->prepare("SELECT * FROM `products` WHERE id LIKE '%$searchQuery%' OR name LIKE '%$searchQuery%' OR category LIKE '%$searchQuery%'");
                  $select_products->execute();
                  $products = $select_products->fetchAll();
                  if ($searchQuery && count($products) == 0) {
                     echo "<tr><td colspan='4'>No results found</td></tr>";
                  } else {
                     foreach ($products as $product) {
                        echo "<tr>";
                        echo "<td>" . $product['id'] . "</td>";
                        echo "<td>" . $product['name'] . "</td>";
                        echo "<td>" . $product['category'] . "</td>";
                        echo "<td>" . $product['price'] . "</td>";
                        echo "</tr>";
                     }
                  }
                  ?>
               </tbody>
            </table>
            <button class="btn print-all-btn">Print All</button>
         </div>
      </div>
   </div>
</section>

<!-- admin dashboard section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>
<!-- Load jQuery and DataTables JS -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<!-- Init DataTables -->
<script type="text/javascript">
   $(document).ready(function () {
      $('#product_table').DataTable({
         lengthMenu: [5, 10, 15, 20] // Menentukan pilihan jumlah entri per halaman
      });

   //    // Handle print button click
   //    $('.print-btn').click(function () {
   //       var productId = $(this).data('id');
   //       window.location.href = '../print.php?id=' + productId;
   //    });

   //    // Handle print all button click
   //    $('.print-all-btn').click(function () {
   //       window.location.href = '../print.php?all=true';

         
   //    });
   // });
   $('.print-all-btn').click(function () {
         var searchQuery = '<?php echo $searchQuery; ?>';
         var printUrl = '../print.php?all=true';
         if (searchQuery) {
            printUrl += '&search=' + searchQuery;
         }
         window.location.href = printUrl;
      });
   });
</script>
</body>
</html>
