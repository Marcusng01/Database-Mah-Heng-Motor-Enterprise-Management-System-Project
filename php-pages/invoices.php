<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices | Mah Heng Motor Enterprise</title>
    <link rel="stylesheet" href="../stylesheets/global-styles.css">
    <link rel="stylesheet" href="../stylesheets/invoice-styles.css">
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
        <h1>Invoices Management</h1>
        <!-- Add Table For all Services -->
        <table id="invoiceTable">
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th id="dateHeader" onclick="toggleSort()">Invoice Date</th>
                    <th>Supplier Name</th>
                    <th>Supplier Contact Number</th>
                    <th>Invoice Total Price(RM)</th>
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

                // Fetch invoiec data from the database
                $sql = "SELECT invoice_details.Invoice_ID, invoice_details.Invoice_Date, invoice_details.Invoice_Total_Price, supplier_details.Supplier_Name,supplier_details.Supplier_Contact_Number FROM invoice_details
                        INNER JOIN supplier_details ON  invoice_details.Supplier_ID = supplier_details.Supplier_ID
                        ORDER BY invoice_details.Invoice_Date DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["Invoice_ID"] . "</td>";
                        echo "<td>" . $row["Invoice_Date"] . "</td>";
                        echo "<td>" . $row["Supplier_Name"] . "</td>";
                        echo "<td>" . $row["Supplier_Contact_Number"] . "</td>";
                        echo "<td id=total".$row['Invoice_ID'].">" . $row["Invoice_Total_Price"] . "</td>";
                        echo "<td><p><a href='#' data-id=".$row['Invoice_ID']." class='more'>More Details</a></p></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No invoices found.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
        <div class="popup">
            <span class="close">&#10006;</span>
            <h2>INVOICES COMPONENTS</h2>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Component Name</th>
                        <th>Quantity</th>
                        <th>Price Per Unit (RM)</th>
                        <th>Subtotal (RM)</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
</script>
        <br>
        <button onclick="document.getElementById('addInvoiceForm').classList.add('display')">Add Invoice</button>
        <!-- Add Service Form Popup -->
        <div id="addInvoiceForm">
            <h2>Add Invoice</h2>
            <form action="./form-handlers/invoice-post.php" method="post">

                <div class="form-field">
                    <label>
                        <input type="radio" name="supplierType" value="existing" onclick="toggleSupplierFields()" checked>Existing Supplier
                    </label>
                    <label>
                        <input type="radio" name="supplierType" id="newSupplierRadio" value="new" onclick="toggleSupplierFields()">New Supplier
                    </label>
                </div>

                <div class="form-field" id="supplier-name-field">
                    <label for="supplierName">Supplier Name:</label>
                    <input type="text" id="supplierNameText" name="supplierName" >
                    <select id="supplierNameDropdown" name="supplierID" >
                        <?php
                        // Fetch customer data from the database
                        $conn = new mysqli($servername, $username, $password, $database);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT Supplier_ID, Supplier_Name, Supplier_Contact_Number FROM supplier_details";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='".$row["Supplier_ID"]."'>" . $row["Supplier_Name"] . " (" . $row["Supplier_Contact_Number"] . ")</option>";
                            }
                        }

                        $conn->close();
                        ?>
                    </select>
                </div>

                <div class="form-field" id="supplier-phone-field">               
                    <label for="supplierPhone">Supplier Phone:</label>
                    <input type="text" id="supplierPhone" name="supplierPhone" ><br>
                </div>

                <div class="form-field">
                    <label for="invoiceDate">Invoice Date:</label>
                    <input type="date" id="invoiceDate" name="invoiceDate" required><br>
                </div>

                <div id="componentFieldsContainer">
                </div>
                
                <div class="form-field">
                    <button type="button" onclick="addInvoiceComponent()" required>Add Component</button><br>
                </div>

                <div class="form-field">
                    <label for="totalPrice">Total Price (RM):</label>
                    <input type="number" id="totalPrice" name="totalPrice" step="0.1" readonly required><br>
                </div>

                <div class="form-field">
                    <input type="submit" value="Submit">
                    <button type="button" onclick="document.getElementById('addInvoiceForm').classList.remove('display')">Cancel</button>
                </div>

            </form>
        </div>
    </div>
    <div class="footer">
        <div class="footer-logo"><img src="../image/logo.png" alt="Logo"></div>
    </div>
    <script type="text/javascript" src="../scripts/global-scripts.js"></script>
    <script>
        // Function to toggle the sort order of the invoice date column
        function toggleSort() {
            var table = document.getElementById("invoiceTable");
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

        // Function to add a invoice component
        function addInvoiceComponent() {
            var componentAndQuantity ={}
            var componentFieldsContainer = document.getElementById("componentFieldsContainer");
            var numComponents = componentFieldsContainer.childElementCount;
            var totalPrice = document.getElementById("totalPrice");
            totalPrice.value = 0;
            var div = document.createElement("div");
            var label = document.createElement("label");
            label.classList.add("invoice-component-label")
            var select = document.createElement("select");
            select.name = "invoiceComponents[" + numComponents + "][existingComponentName]";
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
            //Extra option to represent new component
            var newComponentOption = document.createElement("option");
            newComponentOption.textContent = "(New Component)";
            newComponentOption.value = "new";
            //Clicking on this new component option will hide dropdown and show textbox
            select.oninput= function changeComponentFields(){
                if (select.value=="new"){
                    select.style.display = "none";
                    select.required =false;
                    newComponentText.style.display = "inline";
                    newComponentText.required = true;
                }
            };
            select.appendChild(newComponentOption);
            var newComponentText = document.createElement("input");
            newComponentText.type = "text";
            newComponentText.name = "invoiceComponents[" + numComponents + "][newComponentName]";
            newComponentText.style.display = "none";
            var quantityLabel = document.createElement("label");
            quantityLabel.textContent = "Quantity:";
            var quantityInput = document.createElement("input");
            quantityInput.type = "number";
            quantityInput.name = "invoiceComponents[" + numComponents + "][quantity]";
            quantityInput.min = "1";
            quantityInput.step = "1";
            quantityInput.required = true;
            quantityInput.oninput = function calculateTotal(){
                subTotalInput.value=(Math.round(quantityInput.value * priceInput.value * 100) / 100).toFixed(2);
                updateGrandTotal()
            };
            var priceLabel = document.createElement("label");
            priceLabel.textContent = "Price per piece (RM):";

            var priceInput = document.createElement("input");
            priceInput.type = "number";
            priceInput.name = "invoiceComponents[" + numComponents + "][pricePerPiece]";
            priceInput.step = "0.01";
            priceInput.min = "0";
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
            subTotalInput.name = "invoiceComponents[" + numComponents + "][subTotal]";
            subTotalInput.readOnly = true;
            var removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.textContent = "Remove Component";
            removeButton.onclick = function() {
                componentFieldsContainer.removeChild(div);
                // Re-label the remaining invoice components
                var labels = componentFieldsContainer.getElementsByClassName("invoice-component-label");
                for (var i = 0; i < labels.length; i++) {
                    labels[i].innerHTML = "Invoice Component " + (i+1) + ":";
                }
                updateGrandTotal()
            };
            div.appendChild(label);
            div.appendChild(select);
            div.appendChild(newComponentText);
            div.appendChild(quantityLabel);
            div.appendChild(quantityInput);
            div.appendChild(priceLabel);
            div.appendChild(priceInput);
            div.appendChild(subTotalLabel);
            div.appendChild(subTotalInput);
            div.appendChild(removeButton);
            componentFieldsContainer.appendChild(div);
            // Re-label the remaining invoice components
            var labels = componentFieldsContainer.getElementsByClassName("invoice-component-label");
            for (var i = 0; i < labels.length; i++) {
                labels[i].innerHTML = "Invoice Component " + (i+1) + ":";
            }
            updateGrandTotal()
        }

        function toggleSupplierFields() {
            var newSupplierRadio = document.getElementById("newSupplierRadio");
            var supplierNameDropdown = document.getElementById("supplierNameDropdown");
            var supplierNameText = document.getElementById("supplierNameText");
            var supplierPhoneField= document.getElementById("supplier-phone-field");

            if (!newSupplierRadio.checked) {
                supplierNameDropdown.style.display = "block";
                supplierNameDropdown.required = true;
                supplierNameText.style.display = "none";
                supplierNameText.required = false;
                supplierPhoneField.style.display = "none";
                supplierPhoneField.required = false;
                
            } else {
                supplierNameDropdown.style.display = "none";
                supplierNameDropdown.required = false;
                supplierNameText.style.display = "block";
                supplierNameText.required = true;
                supplierPhoneField.style.display = "block";
                supplierPhoneField.required = true;

            }
        }
        addInvoiceComponent();
        toggleSupplierFields();

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
                        $(".popup").find("h2:last").remove();
                        $(".popup").append("<h2>TOTAL PRICE: RM" + $("#total"+invoiceId).text() +"</h2>")
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