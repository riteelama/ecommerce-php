<?php require "../includes/header.php"?> 
<?php require "../includes/dbconfig.php"?>  

<?php 
if(!isset($_SESSION['username']))
{ 
    header("location: ".APPURL."");
}

?>

<?php

//for extracting the user_id
$username = $_SESSION['username'];
$userSql = "SELECT * FROM users Where username = '$username'";
$userQuery = mysqli_query($conn,$userSql);
$users = mysqli_fetch_assoc($userQuery);
$user_id = $users['id'];

$products = "SELECT * FROM cart WHERE user_id = '$user_id'";
// var_dump($products);

$productsQuery = mysqli_query($conn,$products);

// $productValue = mysqli_fetch_assoc($productsQuery);
// $prod_id = $productValue['prod_id'];
// var_dump($prod_id);

$allProducts = mysqli_fetch_all($productsQuery,MYSQLI_ASSOC);
// var_dump($allProducts['prod_id']);

if(isset($_POST['submit'])){
  
  $price = $_POST['price'];

  $_SESSION['price'] = $price;

  header("location:checkout.php");
}
?>


    <div class="row d-flex justify-content-center align-items-center h-100 mt-5 mt-5">
      <div class="col-12">
        <div class="card card-registration card-registration-2" style="border-radius: 15px;">
          <div class="card-body p-0">
            <div class="row g-0">
              <div class="col-lg-8">
                <div class="p-5">
                  <div class="d-flex justify-content-between align-items-center mb-5">
                    <h1 class="fw-bold mb-0 text-black">Shopping Cart</h1>
                  </div>


                  <table class="table" height="190" >
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Update</th>
                        <th scope="col"><button class="btn btn-danger text-white delete-all">Clear</button></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(count($allProducts) > 0) : ?>
                         <input class="prod_id" value="<?php 
                        //  var_dump($allProducts['prod_id']);?>" type="hidden">
                         <?php 
                         foreach($allProducts as $product) :
                        ?>

                      <tr class="mb-4">
                        <th scope="row"><?php echo $product['id'];?></th>
                        <td><img width="100" height="100"
                        src="http://localhost/ecommerce/admin-panel/categories-admins/images/<?php echo $product['prod_image'];?>"
                        class="img-fluid rounded-3" alt="Cotton T-shirt">
                        </td>
                        <td><?php echo $product['prod_name'];?></td>
                        <td class="pro_price">$<span><?php echo $product['prod_price'];?></span></td>
                        <td>
                          <input id="form1" min="1" name="quantity" value="<?php echo $product['prod_quantity'];?>" type="number"
                          class="form-control form-control-sm pro_amount" />
                        </td>
                        <td class="total_price"><?php echo "$ " ;?><span><?php echo $product['prod_price'] * $product['prod_quantity'];?></span></td>
                       <input type="text" class="prod_id" value="<?php  echo $product['prod_id'];?>">
                        <td><button value="<?php echo $product['id'];?>" class="btn btn-warning text-white btn-update"><i class="fas fa-pen"></i> </button></td>
                        <td><button value="<?php echo $product['id'];?>" class="btn btn-danger text-white btn-delete"><i class="fas fa-trash-alt"></i> </button></td>
                      </tr>
                      <?php endforeach; ?>
                      <?php else : ?>

                        <div class="alert alert-danger bg-danger text-white">
                          There is no product in cart
                        </div>

                      <?php endif; ?>
                    </tbody>
                  </table>
                  <a href="<?php echo APPURL;?>" class="btn btn-success text-white"><i class="fas fa-arrow-left"></i>  Continue Shopping</a>
                </div>
              </div>
              <div class="col-lg-4 bg-grey">
                <div class="p-5">
                  <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                  <hr class="my-4">

                  
                  <form method="post" action="cart.php">
                  <div class="d-flex justify-content-between mb-5">
                    <h5 class="text-uppercase">Total price</h5>
                    <input class="inp_price" name="price" type="hidden"/>
                    <h5 class="full_price"></h5>
                  </div>

                  <button type="submit" name="submit"class="btn btn-dark btn-block btn-lg checkout"
                    data-mdb-ripple-color="dark">Checkout</button>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
       
      </div>

    </div>

<?php require "../includes/footer.php"?> 

<script>
    $(document).ready(function(){
      
      $(".pro_amount").mouseup(function () {
                  
        var $el = $(this).closest('tr');

        var pro_amount = $el.find(".pro_amount").val();
        var pro_price = $el.find(".pro_price").children("span").html();

        var total = pro_amount * pro_price;
        $el.find(".total_price").html();        

        $el.find(".total_price").html('$'+ parseFloat(total));

        $(".btn-update").on('click', function(e) {

            var id = $(this).val();
          

            $.ajax({
              type: "POST",
              url: "update-item.php",
              data: {
                update: "update",
                id: id,
                pro_amount: pro_amount
              },

              success: function() {
                alert("Cart updated successfully");
                reload();
              }
            })
        }); 


           fetch();        
      });

      $(".checkout").on('click', function(e) {
        var total_price = $('.total_price').find('span').html();
        localStorage.setItem("totalPrice",total_price);
        var prod_id =  $('.prod_id').val();
        localStorage.setItem("prodId",prod_id);
        // alert(total_price);

      });

      $(".btn-delete").on('click', function(e) {

          var id = $(this).val();


            $.ajax({
              type: "POST",
              url: "delete-item.php",
              data: {
                delete: "delete",
                id: id
              },

              success: function() {
                alert("Product deleted successfully");
                reload();
              }
            })
      }); 

      $(".delete-all").on('click', function(e) {

          $.ajax({
            type: "POST",
            url: "delete-all-item.php",
            data: {
              delete: "delete",
            },

            success: function() {
              alert("All Product deleted successfully");
              reload();
            }
          })
      });

      fetch();

      function fetch() {

          var sum = 0.0;
          $('.total_price').each(function()
          {
              var str = $(this).text();
              sum += parseFloat(str.substring(1));
              console.log($(this).text());
          });
          $(".full_price").html("$" + sum);
          $(".inp_price").val(sum);

          if($(".inp_price").val() > 0) {
            $(".checkout").show();
          }
          else {
            $(".checkout").hide();
          }
      } 
      
      function reload() {       
            $("body").load("cart.php")       
      }

    });

  
  
</script>