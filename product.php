<?php 
session_start();
include 'db_connect.php';

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderData'])) {
    $user_id = $_SESSION['user_id'] ?? 1; // Replace 1 with default or redirect to login
    $orderData = json_decode($_POST['orderData'], true);

    if (!$orderData || !isset($orderData['items'])) {
        die("Invalid order data.");
    }

    $stmt = $conn->prepare("INSERT INTO orders (user_id, product_name, quantity, total_price) VALUES (?, ?, ?, ?)");

    foreach ($orderData['items'] as $item) {
        $product_name = $item['name'];
        $quantity = $item['quantity'];
        $price = $item['price'] * $quantity;

        $stmt->execute([$user_id, $product_name, $quantity, $price]);
    }

    echo "<script>alert('Order placed successfully!'); window.location.href='".$_SERVER['PHP_SELF']."';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Scoop on Main</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .cart-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quantity {
            font-size: 1.2rem;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>
<body>

    <!-- Topbar Start -->
    <div class="container-fluid bg-primary py-3 d-none d-md-block">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-lg-left mb-2 mb-lg-0">
                    <div class="d-inline-flex align-items-center">
                        <a class="text-white pr-3" href="">FAQs</a>
                        <a class="text-white px-3" href="">Help</a>
                        <a class="text-white pl-3" href="">Support</a>
                        
                        <a class="text-white pl-3" href="scss/feedback.html">Feedback</a>
                    </div>
                </div>
                <div class="col-md-6 text-center text-lg-right">
                    <div class="d-inline-flex align-items-center">
                        <a class="text-white px-3" href="">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="text-white px-3" href="">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a class="text-white px-3" href="">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a class="text-white px-3" href="">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a class="text-white pl-3" href="">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a class="text-white pl-3" href="<?php echo isset($_SESSION['username']) ? 'logout.php' : 'scss/login.php'; ?>">
                            <?php echo isset($_SESSION['username']) ? 'Logout' : 'Login'; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid position-relative nav-bar p-0">
        <div class="container-lg position-relative p-0 px-lg-3" style="z-index: 9;">
            <nav class="navbar navbar-expand-lg bg-white navbar-light shadow p-lg-0">
                <a href="index.php" class="navbar-brand d-block d-lg-none">
                    <h1 class="m-0 display-4 text-primary"><span class="text-secondary">ICE</span>CREAM</h1>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav ml-auto py-0">
                        <a href="index.php" class="nav-item nav-link">Home</a>
                        <a href="about.html" class="nav-item nav-link">About</a>
                        <a href="product.html" class="nav-item nav-link active">Product</a>

                    </div>
                    <a href="index.php" class="navbar-brand mx-5 d-none d-lg-block">
                    <h1 class="m-0 display-4 text-primary"><span class="text-secondary">ICE</span>CREAM</h1>
                    </a>
                    <div class="navbar-nav mr-auto py-0">
                        <a href="service.html" class="nav-item nav-link">Service</a>
                        <a href="gallery.html" class="nav-item nav-link">Gallery</a>
                        <a href="contact.html" class="nav-item nav-link">Contact</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->


    <!-- Header Start -->
    <div class="jumbotron jumbotron-fluid page-header" style="margin-bottom: 90px;">
        <div class="container text-center py-5">
            <h1 class="text-white display-3 mt-lg-5">Product</h1>
            <div class="d-inline-flex align-items-center text-white">
                <p class="m-0"><a class="text-white" href="">Home</a></p>
                <i class="fa fa-circle px-3"></i>
                <p class="m-0">About</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Products Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <h1 class="section-title position-relative text-center mb-5">Best Prices We Offer For Ice Cream Lovers</h1>
                </div>
            </div>
            <div class="row">
                <!-- Product 8 -->
                <?php
            $stmt = $conn->query("SELECT * FROM products");
            while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="col-lg-3 col-md-6 mb-4 pb-2">
                    <div class="product-item d-flex flex-column align-items-center text-center bg-light rounded py-5 px-3">
                        <div class="bg-primary mt-n5 py-3" style="width: 80px;">
                            <h4 class="font-weight-bold text-white mb-0">₹<?= htmlspecialchars($product['price']) ?></h4>
                        </div>
                        <div class="position-relative bg-primary rounded-circle mt-n3 mb-4 p-3" style="width: 150px; height: 150px;">
                            <img class="rounded-circle w-100 h-100" src="img/<?= htmlspecialchars($product['image_url']) ?>" style="object-fit: cover;">
                        </div>
                        <h5 class="font-weight-bold mb-4"><?= htmlspecialchars($product['name']) ?></h5>
                        <div class="cart-controls">
                            <button class="btn btn-sm btn-secondary" onclick="changeQuantity(this, -1)">-</button>
                            <span class="quantity">0</span>
                            <button class="btn btn-sm btn-secondary" onclick="changeQuantity(this, 1)">+</button>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
                <div class="col-12 text-center">
                    <button class="btn btn-primary py-3 px-5" onclick="buyNow()">Buy Now</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Products End -->

<!-- Footer Start -->
    <div class="container-fluid footer bg-light py-5" style="margin-top: 90px;">
        <div class="container text-center py-5">
            <div class="row">
                <div class="col-12 mb-4">
                    <a href="index.php" class="navbar-brand m-0">
                    <h1 class="m-0 display-4 text-primary"><span class="text-secondary">ICE</span>CREAM</h1>
                    </a>
                </div>
                <div class="col-12 mb-4">
                    <a class="btn btn-outline-secondary btn-social mr-2" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-outline-secondary btn-social mr-2" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-secondary btn-social mr-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-outline-secondary btn-social" href="#"><i class="fab fa-instagram"></i></a>
                </div>
                <div class="col-12 mt-2 mb-4">
                    <div class="row">
                        <div class="col-sm-6 text-center text-sm-right border-right mb-3 mb-sm-0">
                            <h5 class="font-weight-bold mb-2">Get In Touch</h5>
                            <p class="mb-1">P123 Dessert Lane</p>
                            <p class="mb-1">MG Road Pune-411001</p>
                            <p class="mb-1">Maharashtra,India</p>
                            <p class="mb-0">+012 345 67890</p>
                        </div>
                        <div class="col-sm-6 text-center text-sm-left">
                            <h5 class="font-weight-bold mb-2">Opening Hours</h5>
                            <p class="mb-2">Mon – Sat, 9AM – 10PM</p>
                            <p class="mb-0">Sunday: Closed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script>
        const productPrices = [299, 299, 299, 299, 299, 299, 299, 299];
        const productNames = [
            "Vanilla Ice Cream",
            "Chocolate Ice Cream",
            "Strawberry Ice Cream",
            "Mango Ice Cream",
            "Vanilla Ice Cream",
            "Vanilla Ice Cream",
            "Chocolate Ice Cream",
            "Strawberry Ice Cream"
        ];

        function changeQuantity(button, change) {
            const quantitySpan = button.parentElement.querySelector('.quantity');
            let currentQuantity = parseInt(quantitySpan.textContent);
            currentQuantity += change;
            if (currentQuantity < 0) currentQuantity = 0;
            quantitySpan.textContent = currentQuantity;
        }

        function buyNow() {
    let totalCost = 0;
    let orderItems = [];
    const quantities = document.querySelectorAll('.quantity');

    quantities.forEach((quantitySpan, index) => {
        const quantity = parseInt(quantitySpan.textContent);
        if (quantity > 0) {
            const item = {
                name: productNames[index],
                price: productPrices[index],
                quantity: quantity
            };
            orderItems.push(item);
            totalCost += item.price * item.quantity;
        }
    });

    if (orderItems.length === 0) {
        alert("Please select at least one item.");
        return;
    }

    const cartDetails = orderItems.map(item => {
        const subtotal = item.price * item.quantity;
        return `${item.name}: ${item.quantity} x ₹${item.price} = ₹${subtotal}`;
    }).join('\n') + `\n\nTotal Cost: ₹${totalCost}`;

    alert(cartDetails);

    // Submit order to PHP
    document.getElementById("orderData").value = JSON.stringify({
        items: orderItems,
        total: totalCost
    });

    document.getElementById("orderForm").submit();
}

    </script>
    <form id="orderForm" method="POST" style="display:none;">
    <input type="hidden" name="orderData" id="orderData">
</form>

</body>
</html>
