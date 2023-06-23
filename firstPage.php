<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mah Heng Motor Enterprise</title>
    <link rel="stylesheet" href="./stylesheets/global-styles.css">
</head>
<body>
    <div class="header">
        <div class="header-logo"><img src="./logo.png" alt="Logo"></div>
        <div class="header-logo-excluder">
            <div class="header-button" onclick="window.location.href = './php-pages/customers.php'">Customers</div>
            <div class="header-button" onclick="window.location.href = './php-pages/invoices.php'">Invoices</div>
            <div class="header-button" onclick="window.location.href = './php-pages/services.php'">Services</div>
            <div class="header-button" onclick="window.location.href = './php-pages/stock.php'">Stock</div>
            <div class="header-button" onclick="window.location.href = 'index.html'">Log Out</div>
        </div>
    </div>
    

    <div class="content" style="height: 2000px;"></div>
    <div class="content" style="height: 2000px;">
    <div class="options-container">
        <div class="left-options">
            <div class="option">
                <a href="./php-pages/customers.php" class="option-link"></a>
                <div class="option-label">Customers</div>
            </div>
            <div class="option">
                <a href="./php-pages/invoices.php" class="option-link"></a>
                <div class="option-label">Invoices</div>
            </div>
        </div>
        <div class="right-options">
            <div class="option">
                <a href="./php-pages/services.php" class="option-link"></a>
                <div class="option-label">Services</div>
            </div>
            <div class="option">
                <a href="./php-pages/stock.php" class="option-link"></a>
                <div class="option-label">Stock</div>
            </div>
        </div>
    </div>
</div>

    <div class="footer">
        <div class="footer-logo"><img src="./logo.png" alt="Logo"></div>
    </div>
    <script type="text/javascript" src="./scripts/global-scripts.js"></script>
</body>
</html>