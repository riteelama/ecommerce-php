<?php require "../layouts/header.php";?>
<?php require "../../includes/dbconfig.php";?>

<?php 

    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        $select = "SELECT * FROM categories WHERE id = '$id'";
        $selectQuery = mysqli_query($conn,$select);

        $images = mysqli_fetch_assoc($selectQuery);

        unlink("images/".$images['image']."");

        $delete = "DELETE FROM categories WHERE id = '$id'";
        // var_dump($delete);
        $deleteQuery = mysqli_query($conn,$delete);

        header("location: ".ADMINURL."/categories-admins/show-categories.php");

       }
       else {
        header("location: http://localhost/ecommerce/404.php");
       }

?>



<?php require "../layouts/footer.php";?>