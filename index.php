<?php require "includes/header.php"?> 
<?php require "includes/dbconfig.php"?> 

<?php
    $sql = "SELECT * FROM products WHERE status = 1";
    $query = mysqli_query($conn,$sql);
     // var_dump($allRows);
    // $allRows = $rows->mysqli_fetch_all;

?>
        <div class="row mt-5">
            <?php 
            while($row = mysqli_fetch_assoc($query)):
            ?>
            <div class="col-lg-4 col-md-6 col-sm-10 offset-md-0 offset-sm-1">
                <div class="card" >
                    <img height="213px" class="card-img-top" src="images/<?php echo $row['image'];?>">
                    <div class="card-body" >
                        <h5 class="d-inline"><b><?php echo $row['product_name'];?></b> </h5>
                        <h5 class="d-inline"><div class="text-muted d-inline">($<?php echo $row['price'];?>/item)</div></h5>
                        <p><?php echo $row['excerpt'];?></p>
                         <a href="<?php echo APPURL;?>/shopping/single.php?id=<?php echo $row['id']?>"  class="btn btn-primary w-100 rounded my-2"> View Details<i class="fas fa-arrow-right"></i> </a>      
     
                    </div>
                </div>
            </div>
            <br>
            <?php endwhile; ?>
         </div>
         <br>

<?php require"includes/footer.php"?>