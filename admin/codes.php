<?php
include('../config/dbconnect.php');
include('../functions/myAlerts.php');
 
if(isset($_POST['addCateg_button'])){ // IF FORM SUBMIT IS FROM addCateg_button
    $name = $_POST['name'];
    $slug = $_POST['slug'];  
    $description = $_POST['description'];
    $image = $_POST['image'];
    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keywords = $_POST['meta_keywords'];
    $additional_price = $_POST['additional_price'];
    $status = isset($_POST['status']) ? '1':'0'; // IF THE STATUS IS SET AND NOT NULL
    $popular = isset($_POST['popular']) ? '1':'0'; // IF THE POPULAR IS SET AND NOT NULL
    
    $image = $_FILES['image']['name']; // GET THE ORIGINAL NAME OF THE UPLOADED FILE 

    $path = "../uploads"; // DEFINE THE DIRECTORY WHERE UPLOADED IMAGES IN WILL BE STORED 
    
    $image_ext = pathinfo($image, PATHINFO_EXTENSION); // GET THE FILE EXTENSION OF THE UPLOADED IMAGE 
    $filename = time().'.'.$image_ext; // GENERATE A UNIQUE FILENAME FOR THE UPLOADED IMAGE BY APPEDING THE CURRENT TIMESTAMP AND THE ORIGINAL FILE EXT
    
    $categ_query = "INSERT INTO categories
        (name,slug,description, meta_title,meta_description,meta_keywords, additional_price, status,popular,image)
        VALUES ('$name','$slug','$description','$meta_title','$meta_description','$meta_keywords', '$additional_price', '$status','$popular','$filename')"; 
    
    $categ_query_run = mysqli_query($con, $categ_query); // EXECURE THE SQL QUERY TO INSERT CATEGORY INFORMATION INTO THE DATABASE 
    
    if($categ_query_run){
        move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$filename); // MOVE THE UPLOADED IMAGE FILE FROM THE TEMPORARY DIRECTORY TO THE SPECIFIED UPLOAD DIRECTORY WITH GENERATED FILE NAME 
        redirect("addCategory.php", "Category added successfully"); 
    } else{
        redirect("addCategory.php", "Something went wrong"); 
    }
} else if(isset($_POST['editCateg_button'])){
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $slug = $_POST['slug'];  
    $description = $_POST['description'];
    $image = $_POST['image'];
    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keywords = $_POST['meta_keywords'];
    $additional_price = $_POST['additional_price'];
    $status = isset($_POST['status']) ? '1':'0'; // IF THE STATUS IS SET AND NOT NULL
    $popular = isset($_POST['popular']) ? '1':'0'; // IF THE POPULAR IS SET AND NOT NULL

    $new_image = $_FILES['image']['name']; // GET THE ORIGINAL NAME OF THE UPLOADED FILE 
    $old_image = $_POST['old_image'];

    if($new_image != ""){
        $image_ext = pathinfo($new_image, PATHINFO_EXTENSION); // GET THE FILE EXTENSION OF THE UPLOADED IMAGE 
        $update_filename = time().'.'.$image_ext; // GENERATE A UNIQUE FILENAME FOR THE UPLOADED IMAGE BY APPEDING THE CURRENT TIMESTAMP AND THE ORIGINAL FILE EXT
    } else{
        $update_filename = $old_image;
    }

    $path = "../uploads";

    $update_query = "UPDATE categories SET name='$name', slug='$slug', description='$description', 
    meta_title='$meta_title', meta_description='$meta_description', meta_keywords='$meta_keywords', 
    additional_price='$additional_price', status='$status', popular='$popular', image='$update_filename' WHERE id='$category_id' ";

    $update_query_run = mysqli_query($con, $update_query);

    if($update_query_run){
        if($_FILES['image']['name'] != ""){
            move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$update_filename);
            if(file_exists("../uploads/".$old_image)){
                unlink("../uploads/".$old_image);
            }
        }
        redirect("editCategory.php?id=$category_id","Category Updated Successfully");
    } else{
        redirect("editCategory.php?id=$category_id","Something went wrong");
    }
} else if(isset($_POST['deleteCategory_button'])){
    $category_id = mysqli_real_escape_string($con, $_POST['category_id']);

    $category_query = "SELECT * FROM categories WHERE id='$category_id'";
    $category_query_run = mysqli_query($con, $category_query);
    $category_data = mysqli_fetch_array($category_query_run);
    $image = $category_data['image'];

    // Delete the category
    $delete_query = "DELETE FROM categories WHERE id='$category_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if($delete_query_run){
        if(file_exists("../uploads/".$image)){
            unlink("../uploads/".$image);
        }
        
        // Get the last auto-increment value
        $last_id_query = "SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'aquaflowdb' AND TABLE_NAME = 'categories'";
        $last_id_result = mysqli_query($con, $last_id_query);
        $last_id_row = mysqli_fetch_assoc($last_id_result);
        $last_auto_increment_value = $last_id_row['AUTO_INCREMENT'];

        // Set the auto-increment value to the last deleted ID
        $alter_query = "ALTER TABLE categories AUTO_INCREMENT = $category_id";
        mysqli_query($con, $alter_query);

        redirect("category.php","Category Deleted Successfully");
    } else{
        redirect("category.php","Something went wrong");
    }
} else if(isset($_POST['addProduct_button'])){
    $name = $_POST['name'];
    $slug = $_POST['slug'];  
    $size = $_POST['size'];
    $original_price = $_POST['original_price'];
    $selling_price = $_POST['selling_price'];
    $image = $_POST['image'];
    $quantity = $_POST['quantity'];
    $meta_title = $_POST['meta_title'];
    $meta_keywords = $_POST['meta_keywords'];
    $status = isset($_POST['status']) ? '1':'0'; // IF THE STATUS IS SET AND NOT NULL
    $trending = isset($_POST['trending']) ? '1':'0'; // IF THE TRENDING IS SET AND NOT NULL
    
    $image = $_FILES['image']['name']; // GET THE ORIGINAL NAME OF THE UPLOADED FILE 

    $path = "../uploads"; // DEFINE THE DIRECTORY WHERE UPLOADED IMAGES IN WILL BE STORED 
    
    $image_ext = pathinfo($image, PATHINFO_EXTENSION); // GET THE FILE EXTENSION OF THE UPLOADED IMAGE 
    $filename = time().'.'.$image_ext; // GENERATE A UNIQUE FILENAME FOR THE UPLOADED IMAGE BY APPEDING THE CURRENT TIMESTAMP AND THE ORIGINAL FILE EXT

    $product_query = "INSERT INTO product(name, slug, size, original_price, selling_price, quantity, meta_title, meta_keywords, status, trending, image) 
    VALUES ('$name', '$slug', '$size', '$original_price', '$selling_price', '$quantity', '$meta_title', 
    '$meta_keywords', '$status', '$trending', '$filename')";

    $product_query_run = mysqli_query($con, $product_query);

    if($product_query_run){
        move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$filename); // MOVE THE UPLOADED IMAGE FILE FROM THE TEMPORARY DIRECTORY TO THE SPECIFIED UPLOAD DIRECTORY WITH GENERATED FILE NAME 
        redirect("addProduct.php", "Product added successfully"); 
    } else{
        redirect("addProduct.php", "Something went wrong"); 
    }
} else if(isset($_POST['editProduct_button'])){
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $slug = $_POST['slug'];  
    $size = $_POST['size'];
    $original_price = $_POST['original_price'];
    $selling_price = $_POST['selling_price'];
    $image = $_POST['image'];
    $quantity = $_POST['quantity'];
    $meta_title = $_POST['meta_title'];
    $meta_keywords = $_POST['meta_keywords'];
    $status = isset($_POST['status']) ? '1':'0'; // IF THE STATUS IS SET AND NOT NULL
    $trending = isset($_POST['trending']) ? '1':'0'; // IF THE TRENDING IS SET AND NOT NULL

    $new_image = $_FILES['image']['name']; // GET THE ORIGINAL NAME OF THE UPLOADED FILE 
    $old_image = $_POST['old_image'];

    if($new_image != ""){
        $image_ext = pathinfo($new_image, PATHINFO_EXTENSION); // GET THE FILE EXTENSION OF THE UPLOADED IMAGE 
        $update_filename = time().'.'.$image_ext; // GENERATE A UNIQUE FILENAME FOR THE UPLOADED IMAGE BY APPEDING THE CURRENT TIMESTAMP AND THE ORIGINAL FILE EXT
    } else{
        $update_filename = $old_image;
    }

    $path = "../uploads";

    $update_query = "UPDATE product SET name='$name', slug='$slug', size='$size', 
    original_price='$original_price', selling_price='$selling_price', quantity='$quantity',
    meta_title='$meta_title', meta_keywords='$meta_keywords', 
    status='$status', trending='$trending', image='$update_filename' WHERE id='$product_id' ";

    $update_query_run = mysqli_query($con, $update_query);

    if($update_query_run){
        if($_FILES['image']['name'] != ""){
            move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$update_filename);
            if(file_exists("../uploads/".$old_image)){
                unlink("../uploads/".$old_image);
            }
        }
        redirect("editProduct.php?id=$product_id","Product Updated Successfully");
    } else{
        redirect("editProduct.php?id=$product_id","Something went wrong");
    }
} else if(isset($_POST['cartBtn'])){
    $productId = isset($_POST['selectedProduct']) ? $_POST['selectedProduct'] : null;
    $categoryId = isset($_POST['selectedCategory']) ? $_POST['selectedCategory'] : null;
    $quantity = isset($_POST['quantityInput']) ? $_POST['quantityInput'] : 1; // Default quantity is 1

    if(empty($productId) || empty($categoryId)){
        echo '<script>alert("Please select a product/category");</script>';
        echo '<script>window.location.href = "../order.php";</script>';
    } else {
        // Call the addToCart function
        addToCart($productId, $categoryId, $quantity);
        // Redirect to the cart page
        header("Location: ../payment.php");
        exit(); // Make sure to exit after redirecting
    }
}

// Check if session variables exist and pre-fill the form fields if they do
if(isset($_SESSION['selectedProduct'])) {
    $selectedProduct = $_SESSION['selectedProduct'];
}
if(isset($_SESSION['selectedCategory'])) {
    $selectedCategory = $_SESSION['selectedCategory'];
}



?>