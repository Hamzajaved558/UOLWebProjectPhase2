<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$productProfile = $productName = $productPrice = $productCode = $productInStock = $productDiscount = $productSizes = $productDetails = "";
$productProfile_err = $productName_err = $productPrice_err = $productCode_err = $productInStock_err = $productDiscount_err = $productSizes_err = $productDetails_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_product_name = trim($_POST["productName"]);
    if(empty($input_product_name)){
        $productName_err = "Please enter Product Name.";
    } elseif(!filter_var($input_product_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $productName = $input_product_name;
    }
    
    // Validate Product Price
    $input_product_price = trim($_POST["productPrice"]);
    if(empty($input_product_price)){
        $productPrice_err = "Please enter the price.";     
    } elseif(!ctype_digit($input_product_price)){
        $productPrice_err = "Please enter a positive integer value.";
    } else{
        $productPrice = $input_product_price;
    }

    // Validate Product Code
    $input_product_code = trim($_POST["productCode"]);
    if(empty($input_product_code)){
        $productCode_err = "Please enter code.";     
    } else{
        $productCode = $input_product_code;
    }

    // Validate Product InStock
    $input_product_instock = trim($_POST["productInStock"]);
    if(empty($input_product_instock)){
        $productInStock_err = "Please enter stock.";     
    }elseif(!ctype_digit($input_product_instock)){
        $productInStock_err = "Please enter a positive integer value.";
    } else{
        $productInStock = $input_product_instock;
    }
    
    // Validate Product Discount
    $input_product_discount = trim($_POST["productDiscount"]);
    if(empty($input_product_discount)){
        $productDiscount_err = "Please enter discount.";     
    } else{
        $productDiscount = $input_product_discount;
    }

    // Validate Product Sizes
    $input_product_sizes = trim($_POST["productSizes"]);
    if(empty($input_product_sizes)){
        $productSizes_err = "Please enter discount.";     
    } else{
        $productSizes = $input_product_sizes;
    }

    // Validate Product Details
    $input_product_details = trim($_POST["productDetails"]);
    if(empty($input_product_details)){
        $productDetails_err = "Please enter details.";     
    } else{
        $productDetails = $input_product_details;
    }
    
    // Check input errors before inserting in database
    if(empty($productName_err) && empty($productPrice_err) && empty($productCode_err) && empty($productInStock_err) && empty($productDiscount_err) && empty($productSize_err) && empty($productDetails_err)){
        // Prepare an update statement
        $sql = "UPDATE employees SET productName=?, productPrice=?, productCode=?, productInStock=?, productDiscount=?, productSizes=?, productDetails=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssi", $param_productName, $param_productPrice, $param_productCode, $param_productInStock, $param_productDiscount, $param_productSizes, $param_productDetails, $param_id);
            
            // Set parameters
            //$param_productProfile = $productProfile;
            $param_productName = $productName;
            $param_productPrice = $productPrice;
            $param_productCode = $productCode;
            $param_productInStock = $productInStock;
            $param_productDiscount = $productDiscount;
            $param_productSizes = $productSizes;
            $param_productDetails = $productDetails;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM tbl_products WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $productName = $row['productName'];
                    $productPrice = $row['productPrice'];
                    $productCode = $row['productCode'];
                    $productInStock = $row['productInStock'];
                    $productDiscount = $row['productDiscount'];
                    $productSizes = $row['productSizes'];
                    $productDetails = $row['productDetails'];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        
                        <div class="form-group <?php echo (!empty($productName_err)) ? 'has-error' : ''; ?>">
                            <label>Product Name</label>
                            <input type="text" name="productName" class="form-control" value="<?php echo $productName; ?>">
                            <span class="help-block"><?php echo $productName_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($productPrice_err)) ? 'has-error' : ''; ?>">
                            <label>Product Price</label>
                            <input type="text" name="productPrice" class="form-control" value="<?php echo $productPrice; ?>">
                            <span class="help-block"><?php echo $productPrice_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($productCode_err)) ? 'has-error' : ''; ?>">
                            <label>Product Code</label>
                            <input type="text" name="productCode" class="form-control" value="<?php echo $productCode; ?>">
                            <span class="help-block"><?php echo $productCode_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($productInStock_err)) ? 'has-error' : ''; ?>">
                            <label>Product Instock</label>
                            <input type="text" name="productInStock" class="form-control" value="<?php echo $productInStock; ?>">
                            <span class="help-block"><?php echo $productInStock_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($productDiscount_err)) ? 'has-error' : ''; ?>">
                            <label>Product Discount</label>
                            <input type="text" name="productDiscount" class="form-control" value="<?php echo $productDiscount; ?>">
                            <span class="help-block"><?php echo $productDiscount_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($productSizes_err)) ? 'has-error' : ''; ?>">
                            <label>Product Sizes</label>
                            <input type="text" name="productSizes" class="form-control" value="<?php echo $productSizes; ?>">
                            <span class="help-block"><?php echo $productSizes_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($productDetails_err)) ? 'has-error' : ''; ?>">
                            <label>Product Details</label>
                            <textarea name="productDetails" class="form-control" value="<?php echo $productDetails; ?>"></textarea>
                            <span class="help-block"><?php echo $productDetails_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>