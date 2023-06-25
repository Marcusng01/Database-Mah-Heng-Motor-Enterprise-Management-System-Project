<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers | Mah Heng Motor Enterprise</title>
    <link rel="stylesheet" href="../stylesheets/global-styles.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
        
        th {
            cursor: pointer;
        }
        
        input[type="text"] {
            width: 200px;
            padding: 6px;
            margin-bottom: 10px;
        }
        .content{
            height: 1000px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
    </style>
    <script>
        function toggleSort(column) {
            var table = document.getElementById("customerTable");
            var rows = Array.from(table.getElementsByTagName("tr"));
            var headerRow = rows.shift();
            var index = Array.from(headerRow.getElementsByTagName("th")).indexOf(column);

            rows.sort(function(a, b) {
                var textA = a.cells[index].innerHTML.toLowerCase();
                var textB = b.cells[index].innerHTML.toLowerCase();
                return textA.localeCompare(textB);
            });

            if (column.classList.contains("desc")) {
                rows.reverse();
                column.classList.remove("desc");
            } else {
                column.classList.add("desc");
            }

            table.tBodies[0].append(...rows);
        }

        function filterCustomers() {
            var input = document.getElementById("customerSearch");
            var filter = input.value.toLowerCase();
            var table = document.getElementById("customerTable");
            var rows = table.getElementsByTagName("tr");

            for (var i = 1; i < rows.length+1; i++) {
                var name = rows[i].cells[1].innerHTML.toLowerCase();
                if (name.includes(filter)) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    </script>
</head>
<body>
    <div class="header">
        <div class="header-logo-excluder">
            <div class="header-logo"><img src="../image/logo.png"></div>
            <div class="header-button" onclick="window.location.href = 'customers.php'">Customers</div>
            <div class="header-button" onclick="window.location.href = 'invoices.php'">Invoices</div>
            <div class="header-button" onclick="window.location.href = 'services.php'">Services</div>
            <div class="header-button" onclick="window.location.href = 'stock.php'">Stock</div>
            <div class="header-button" onclick="window.location.href = '../index.html'">Log Out</div>
        </div>
    </div>
    <div class="content">
        <h1>Customer List</h1>
        <input type="text" id="customerSearch" oninput="filterCustomers()" placeholder="Search by Name &#128270;">
        <table id="customerTable">
            <thead>
                <tr>
                    <th onclick="toggleSort(this)">Customer ID</th>
                    <th onclick="toggleSort(this)">Customer Name</th>
                    <th>Customer Contact Number</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Replace with your database connection details
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "mah heng motor database";

                // Create a database connection
                $conn = new mysqli($servername, $username, $password, $database);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch customer data from the database
                $sql = "SELECT * FROM customer_details";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["Customer_ID"] . "</td>";
                        echo "<td>" . $row["Customer_Name"] . "</td>";
                        echo "<td>" . $row["Customer_Contact_Number"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No customers found.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <div class="footer">
            <div class="footer-logo"><img src="../image/logo.png" alt="Logo"></div>
    </div>
    <script type="text/javascript" src="../scripts/global-scripts.js"></script>
</body>
</html>
