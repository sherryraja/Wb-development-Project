<?php
session_start(); // Start the session to manage cart items
require('dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Food</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="style.css">

</head>
<body>
    
<!-- header section starts  -->

<header class="header">

    <section class="flex">

        <div id="menu" class="fas fa-bars"></div>

        <a href="#" class="logo"><i class="fas fa-bone"></i>pedie</a>

        <nav class="navbar">
            <a href="#home">home</a>
            <a href="#category">category</a>
            <a href="#products">products</a>
            <a href="#contact">contact</a>
        </nav>

        <div class="icons">
            <i class="fas fa-search" id="search-icon"></i>
            <a href="#" class="fas fa-heart"></a>
            <!-- Updated cart icon with session-based cart count -->
            <a href="cart.php" class="fas fa-shopping-cart">
                <span id="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>
            </a>
            <a href="login.php" class="fas fa-user"></a>
        </div>

        <form action="" id="search-box">
            <input type="search" id="search" placeholder="search here">
            <label for="search" class="fas fa-search"></label>
        </form>

    </section>

</header>

<!-- header section ends -->

<!-- home section starts  -->

<div class="home" id="home">

    <section class="flex">
        <div class="content">
            <span>upto 50% off</span>
            <h3>we care about <br> your pet!</h3>
            <a href="#" class="btn">get started</a>
        </div>
    </section>

</div>

<!-- home section ends -->

<!-- category section starts  -->

<section class="category" id="category">

    <h1 class="heading"> <i class="fas fa-paw"></i> shop by category <i class="fas fa-paw"></i> </h1>

    <div class="box-container">
        
        <div class="box">
            <img src="images/c1.webp" alt="">
            <h3>food for dogs</h3>
            <a href="#" class="btn">shop now</a>
        </div>

        <div class="box">
            <img src="images/c2.webp" alt="">
            <h3>food for cats</h3>
            <a href="#" class="btn">shop now</a>
        </div>

        <div class="box">
            <img src="images/c3.webp" alt="">
            <h3>food for rabbits</h3>
            <a href="#" class="btn">shop now</a>
        </div>

        <div class="box">
            <img src="images/c4.webp" alt="">
            <h3>food for birds</h3>
            <a href="#" class="btn">shop now</a>
        </div>

    </div>

</section>

<!-- category section ends -->

<!-- products section starts -->

<section class="products" id="products">

<h1 class="heading"> <i class="fas fa-paw"></i> Our Cat Food Products <i class="fas fa-paw"></i> </h1>

<div class="box-container">
    
    <div class="box">
        <img src="cat1.jpg" alt="Dry Cat Food">
        <h3>Dry Cat Food</h3>
        <span>$19.99</span>
        <a href="#" class="btn add-to-cart" data-product-id="1">Add to Cart</a>
    </div>

    <div class="box">
        <img src="p6webp..jpg" alt="Wet Cat Food - Tuna Flavor">
        <h3>Wet Cat Food - Tuna Flavor</h3>
        <span>$15.99</span>
        <a href="#" class="btn add-to-cart" data-product-id="2">Add to Cart</a>
    </div>

    <div class="box">
        <img src="images/catfood3.jpg" alt="Grain-Free Cat Food">
        <h3>Grain-Free Cat Food</h3>
        <span>$22.99</span>
        <a href="#" class="btn add-to-cart" data-product-id="3">Add to Cart</a>
    </div>

    <div class="box">
        <img src="images/catfood4.jpg" alt="Organic Cat Food">
        <h3>Organic Cat Food</h3>
        <span>$24.99</span>
        <a href="#" class="btn add-to-cart" data-product-id="4">Add to Cart</a>
    </div>

    <div class="box">
        <img src="images/catfood5.jpg" alt="Cat Treats - Chicken Bites">
        <h3>Cat Treats - Chicken Bites</h3>
        <span>$9.99</span>
        <a href="#" class="btn add-to-cart" data-product-id="5">Add to Cart</a>
    </div>

</div>

</section>

<!-- products section ends -->

<!-- contact section starts  -->

<section class="contact" id="contact">

    <h1 class="heading"> <i class="fas fa-paw"></i> contact us <i class="fas fa-paw"></i> </h1>

    <div class="row">
        <!-- Replaced form with new form -->
        <form action="submit_contact.php" method="POST">
            <h3>get in touch</h3>
    
            <!-- Name field -->
            <input type="text" name="name" placeholder="name" class="box" required>
    
            <!-- Email field -->
            <input type="email" name="email" placeholder="email" class="box" required>
    
            <!-- Phone field -->
            <input type="tel" name="phone" placeholder="phone (optional)" class="box">
    
            <!-- Subject field -->
            <input type="text" name="subject" placeholder="subject" class="box">
    
            <!-- Message textarea -->
            <textarea name="message" placeholder="message" class="box" cols="30" rows="10" required></textarea>
    
            <!-- Submit button -->
            <input type="submit" value="send message" class="btn">
        </form>

    </div>

</section>

<!-- contact section ends -->

<!-- footer section starts  -->

<footer class="footer">

    <section class="flex">

        <div class="box">
            <h3>quick links</h3>
            <a href="#">home</a>
            <a href="#">category</a>
            <a href="#">products</a>
            <a href="#">contact</a>
        </div>

        <div class="box">
            <h3>contact info</h3>
            <p> <i class="fas fa-map-marker-alt"></i> 123 street, city, country </p>
            <p> <i class="fas fa-phone"></i> +123-456-7890 </p>
            <p> <i class="fas fa-envelope"></i> example@gmail.com </p>
            <p> <i class="fas fa-clock"></i> 9:00am to 9:00pm </p>
        </div>

    </section>

</footer>

<!-- footer section ends -->

<!-- custom js file link  -->
<script src="script.js"></script>

<!-- jQuery for AJAX -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('.add-to-cart').click(function(e) {
            e.preventDefault();
            let productId = $(this).data('product-id');
            $.ajax({
                url: 'add_to_cart.php',
                type: 'POST',
                data: { product_id: productId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        let cartCount = parseInt($('#cart-count').text());
                        $('#cart-count').text(cartCount + 1);
                    } else {
                        alert(response.message);
                    }
                }
            });
        });
    });
</script>

</body>
</html>
