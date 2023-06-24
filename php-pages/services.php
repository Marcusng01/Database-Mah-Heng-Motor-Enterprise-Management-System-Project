<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services | Mah Heng Motor Enterprise</title>
    <link rel="stylesheet" href="../stylesheets/global-styles.css">
    <link rel="stylesheet" href="../stylesheets/services-styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
            center {
            font-size: 30px;
            color: green;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
            border: solid 3px red;
            background-color: white;
            padding: 20px;
            text-align: center;
            z-index: 9999;
        }

        .popup .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 18px;
            cursor: pointer;
        }
        .popup .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .popup .label {
            text-decoration: underline;
            margin-right: 20px;
        }

        .popup .value {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-logo"><img src="" alt="Logo"></div>
        <div class="header-logo-excluder">
            <div class="header-button" onclick="window.location.href = 'customers.php'">Customers</div>
            <div class="header-button" onclick="window.location.href = 'invoices.php'">Invoices</div>
            <div class="header-button" onclick="window.location.href = 'services.php'">Services</div>
            <div class="header-button" onclick="window.location.href = 'stock.php'">Stock</div>
            <div class="header-button" onclick="window.location.href = '../index.html'">Log Out</div>
        </div>
    </div>
    <div class="content">
        <h1>Service Management</h1>
        <!-- Add Table For all Services -->
        <table id="serviceTable">
            <thead>
                <tr>
                    <th>Service ID</th>
                    <th id="dateHeader" onclick="toggleSort()">Service Date</th>
                    <th>Motor ID</th>
                    <th>Customer Name</th>
                    <th>Service Description</th>
                    <th>Service Total Price</th>
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

                // Fetch service data from the database
                $sql = "SELECT service.Service_ID, service.Service_Date, customer_details.Customer_Name, service.Description, service.Service_Total_Price, service.Motor_ID FROM service
                        INNER JOIN customer_details ON service.Customer_ID = customer_details.Customer_ID
                        ORDER BY service.Service_Date DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["Service_ID"] . "</td>";
                        echo "<td>" . $row["Service_Date"] . "</td>";
                        echo "<td>" . $row["Motor_ID"] . "</td>";
                        echo "<td>" . $row["Customer_Name"] . "</td>";
                        echo "<td>" . $row["Description"] . "</td>";
                        echo "<td>" . $row["Service_Total_Price"] . "</td>";
                        echo "<td><p><a href='#' data-id=".$row['Service_ID']." class='more'>More Details</a></p></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No services found.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
        <div class="popup">
            <span class="close">&#10006;</span>
            <h2>SERVICES COMPONENTS</h2>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Component Name</th>
                        <th>Quantity</th>
                        <th>Price Per Unit (RM)</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <br>
        <button onclick="document.getElementById('addServiceForm').classList.add('display')">Add Service</button>
        <!-- Add Service Form Popup -->
        <div id="addServiceForm">
            <h2>Add Service</h2>
            <form action="./form-handlers/services-post.php" method="post">

                <div class="form-field">
                    <label>
                        <input type="radio" name="customerType" value="existing" onclick="toggleCustomerFields()" checked>Existing Customer
                    </label>
                    <label>
                        <input type="radio" name="customerType" id="newCustomerRadio" value="new" onclick="toggleCustomerFields()">New Customer
                    </label>
                </div>

                <div class="form-field" id="customer-name-field">
                    <label for="customerName">Customer Name:</label>
                    <input type="text" id="customerNameText" name="customerName" >
                    <select id="customerNameDropdown" name="customerID" >
                        <?php
                        // Fetch customer data from the database
                        $conn = new mysqli($servername, $username, $password, $database);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT Customer_ID, Customer_Name, Customer_Contact_Number FROM customer_details";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='".$row["Customer_ID"]."'>" . $row["Customer_Name"] . " (" . $row["Customer_Contact_Number"] . ")</option>";
                            }
                        }

                        $conn->close();
                        ?>
                    </select>
                </div>

                <div class="form-field" id="customer-phone-field">               
                    <label for="customerPhone">Customer Phone:</label>
                    <input type="text" id="customerPhone" name="customerPhone" ><br>
                </div>


                <div class="form-field">
                    <label for="motorcycleId">Motorcycle ID:</label>
                    <input type="text" id="motorcycleId" name="motorcycleId" required><br>
                </div>

                <div class="form-field">
                    <label for="serviceDate">Service Date:</label>
                    <input type="date" id="serviceDate" name="serviceDate" required><br>
                </div>

                <div id="componentFieldsContainer">
                </div>
                
                <div class="form-field">
                    <button type="button" onclick="addServiceComponent()" required>Add Component</button><br>
                </div>

                <div class="form-field">
                    <label for="totalPrice">Total Price (RM):</label>
                    <input type="number" id="totalPrice" name="totalPrice" step="0.1" readonly required><br>
                </div>

                <div class="form-field">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea><br>
                </div>

                <div class="form-field">
                    <input type="submit" value="Submit">
                    <button type="button" onclick="document.getElementById('addServiceForm').classList.remove('display')">Cancel</button>
                </div>

            </form>
        </div>
    </div>
    <div class="footer">
        <div class="footer-logo"><img src="All Pages/logo.png" alt="Logo"></div>
    </div>
    <script type="text/javascript" src="../scripts/global-scripts.js"></script>
    <script>
        // Function to toggle the sort order of the service date column
        function toggleSort() {
            var table = document.getElementById("serviceTable");
            var rows = Array.from(table.getElementsByTagName("tr"));
            var header = rows.shift();
            var index = Array.from(header.getElementsByTagName("th")).indexOf(document.getElementById("dateHeader"));

            rows.sort(function(a, b) {
                var dateA = new Date(a.cells[index].innerHTML);
                var dateB = new Date(b.cells[index].innerHTML);
                return dateA - dateB;
            });

            if (table.classList.contains("desc")) {
                rows.reverse();
                table.classList.remove("desc");
            } else {
                table.classList.add("desc");
            }

            table.tBodies[0].append(...rows);
        }

        function updateGrandTotal(){
            var totalPrice = document.getElementById("totalPrice");
            totalPrice.value = 0;
            subTotals = document.querySelectorAll(".subtotal");
            subTotals.forEach(subTotal=> {
                totalPrice.value=parseFloat(totalPrice.value) + parseFloat(subTotal.value);
            });
            totalPrice.value = (Math.round(totalPrice.value * 100) / 100).toFixed(2);
        }

        // Function to add a service component
        function addServiceComponent() {
            var componentAndQuantity ={}
            var componentFieldsContainer = document.getElementById("componentFieldsContainer");
            var numComponents = componentFieldsContainer.childElementCount;
            var totalPrice = document.getElementById("totalPrice");
            totalPrice.value = 0;
            var div = document.createElement("div");
            var label = document.createElement("label");
            label.classList.add("service-component-label")
            var select = document.createElement("select");
            select.name = "serviceComponents[" + numComponents + "][componentName]";
            select.required = true;
            <?php
                // Replace with your database connection details
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "mah heng motor database";
                // Fetch component names from the database and generate options dynamically
                $conn = new mysqli($servername, $username, $password, $database);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $selectComponentsSql = "SELECT Component_ID, Component_Name, Component_Quantity FROM component";
                $result = $conn->query($selectComponentsSql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "var option = document.createElement('option');";
                        echo "option.value = '" . $row["Component_ID"] . "';";
                        echo "option.textContent = '" . $row["Component_Name"] . "';";
                        echo "select.appendChild(option);";
                        echo "componentAndQuantity['".$row["Component_Name"]."'] = ".$row["Component_Quantity"].";";
                    }
                }
                $conn->close();
            ?>
            select.oninput =function changeMaxQuantity(){quantityInput.max= componentAndQuantity[option.textContent]};
            var quantityLabel = document.createElement("label");
            quantityLabel.textContent = "Quantity:";

            var quantityInput = document.createElement("input");
            quantityInput.type = "number";
            quantityInput.name = "serviceComponents[" + numComponents + "][quantity]";
            quantityInput.min = "1";
            quantityInput.step = "1";
            quantityInput.required = true;
            quantityInput.oninput = function calculateTotal(){
                subTotalInput.value=(Math.round(quantityInput.value * priceInput.value * 100) / 100).toFixed(2);
                updateGrandTotal()
            };
            quantityInput.max= componentAndQuantity[option.textContent];


            var priceLabel = document.createElement("label");
            priceLabel.textContent = "Price per piece (RM):";

            var priceInput = document.createElement("input");
            priceInput.type = "number";
            priceInput.name = "serviceComponents[" + numComponents + "][pricePerPiece]";
            priceInput.step = "0.01";
            priceInput.required = true;
            priceInput.oninput = function calculateTotal(){
                subTotalInput.value=(Math.round(quantityInput.value * priceInput.value * 100) / 100).toFixed(2);
                updateGrandTotal()
            };

            var subTotalLabel = document.createElement("label");
            subTotalLabel.textContent = "Sub Total (RM):";

            var subTotalInput = document.createElement("input");
            subTotalInput.classList.add("subtotal");
            subTotalInput.type = "number";
            subTotalInput.step = "0.01";
            subTotalInput.value = "0";
            subTotalInput.name = "serviceComponents[" + numComponents + "][subTotal]";
            subTotalInput.readOnly = true;
            var removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.textContent = "Remove Component";
            removeButton.onclick = function() {
                componentFieldsContainer.removeChild(div);
                // Re-label the remaining service components
                var labels = componentFieldsContainer.getElementsByClassName("service-component-label");
                for (var i = 0; i < labels.length; i++) {
                    labels[i].innerHTML = "Service Component " + (i+1) + ":";
                }
                updateGrandTotal()
            };
            div.appendChild(label);
            div.appendChild(select);
            div.appendChild(quantityLabel);
            div.appendChild(quantityInput);
            div.appendChild(priceLabel);
            div.appendChild(priceInput);
            div.appendChild(subTotalLabel);
            div.appendChild(subTotalInput);
            div.appendChild(removeButton);
            componentFieldsContainer.appendChild(div);
            // Re-label the remaining service components
            var labels = componentFieldsContainer.getElementsByClassName("service-component-label");
            for (var i = 0; i < labels.length; i++) {
                labels[i].innerHTML = "Service Component " + (i+1) + ":";
            }
            updateGrandTotal()
        }

        function toggleCustomerFields() {
            var newCustomerRadio = document.getElementById("newCustomerRadio");
            var customerNameDropdown = document.getElementById("customerNameDropdown");
            var customerNameText = document.getElementById("customerNameText");
            var customerPhoneField= document.getElementById("customer-phone-field");

            if (!newCustomerRadio.checked) {
                customerNameDropdown.style.display = "block";
                customerNameDropdown.required = true;
                customerNameText.style.display = "none";
                customerNameText.required = false;
                customerPhoneField.style.display = "none";
                customerPhoneField.required = false;
                
            } else {
                customerNameDropdown.style.display = "none";
                customerNameDropdown.required = false;
                customerNameText.style.display = "block";
                customerNameText.required = true;
                customerPhoneField.style.display = "block";
                customerPhoneField.required = true;

            }
        }
        addServiceComponent();
        toggleCustomerFields();
        //When document is ready
        $(document).ready(function () {
            var isOpen = false;
            //If More Details button is clicked, toggle popup
            $(".more").click(function (e) {
                if(isOpen){
                    closePopup();
                }else{
                    e.preventDefault();
                    invoiceId = $(this).data("id")
                    openPopup(invoiceId);
                }
            });

            //If popup is open popup focus is lost, toggle popup (close it)
            $(document).click(function (e) {
                if (isOpen && !$(e.target).closest('.popup').length && !$(e.target).closest('.more').length) {
                    closePopup();
                }
            });

            //If popup's close button is clicked, toggle popup (close it)
            $(".popup .close").click(function () {
                closePopup();
            });

            function openPopup(invoiceId) {
                isOpen = true;
                $.ajax({
                    type: "POST",
                    url: "./form-handlers/ordered-component-ajax.php",
                    data: { 'id': invoiceId },
                    success: function(response) {
                        // Handle the response from PHP here
                        $("div.popup tbody").html(response);
                        $("div.popup").fadeIn();
                    }
                });
            }

            function closePopup(){
                $("div.popup").fadeOut();
                isOpen = false;
            }
        });
    </script>
</body>
</html>