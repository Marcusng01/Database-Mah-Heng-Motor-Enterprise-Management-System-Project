<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
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

        // Retrieve form data
        $invoiceTotalPrice = $_POST["totalPrice"];
        $invoiceDate = $_POST["invoiceDate"]; 
        $invoiceComponents = $_POST["invoiceComponents"];
        // Get Supplier ID based on if the supplier is existing or new
        $supplierType = $_POST["supplierType"];
        if ($supplierType == "existing"){
            //Get Supplier ID from Dropdownbox
            $supplierID = $_POST["supplierID"];
        }
        else if ($supplierType == "new"){
            $supplierName = $_POST["supplierName"];
            $supplierPhone = $_POST["supplierPhone"];
            // Insert new supplier details
            $supplierQuery = "INSERT INTO supplier_details (Supplier_Name, Supplier_Contact_Number) VALUES ('$supplierName', '$supplierPhone');";
            $conn->query($supplierQuery);
            //Get Supplier ID from insert query
            $supplierID = $conn->insert_id;
        }
        else {
            echo "Invalid supplier option selected.";
            exit();
        }

        // Insert invoice details
        $invoiceQuery = "INSERT INTO invoice_details (Invoice_Total_Price, Invoice_Date, Supplier_ID) VALUES ('$invoiceTotalPrice', '$invoiceDate','$supplierID');";
        if ($conn->query($invoiceQuery) === TRUE) {
            //Based on new invoice id, insert ordered_components
            $invoiceID = $conn->insert_id;
            foreach($invoiceComponents as $component){
                $componentID = $component["existingComponentName"];
                $componentPricePerPiece = $component["pricePerPiece"];
                $componentQuantity = $component["quantity"];
                if ($componentID == "new"){
                    $componentName = $component["newComponentName"];
                    //Insert new component details
                    $newComponentQuery = "INSERT INTO component (Component_Name, Component_Quantity) VALUES ('$componentName',0);";
                    print($newComponentQuery);
                    $conn->query($newComponentQuery);
                    $componentID = $conn->insert_id;
                }
                $invoiceComponentQuery = "INSERT INTO ordered_component (Invoice_ID, Component_ID, Ordered_Component_Price, Ordered_Component_Quantity) VALUES ($invoiceID,$componentID,$componentPricePerPiece,$componentQuantity);";
                if ($conn->query($invoiceComponentQuery) === TRUE){
                    $stockQuantityQuery = "SELECT Component_Quantity FROM component WHERE Component_ID = $componentID";
                    $result = $conn->query($stockQuantityQuery);
                    $stockQuantity = $result->fetch_array()[0];
                    $deductStockQuery = "UPDATE component SET Component_Quantity = $stockQuantity+$componentQuantity WHERE Component_ID = $componentID";
                    $result = $conn->query($deductStockQuery);
                }
                else{
                    echo "Error: " . $invoiceInsertSql . "<br>" . $conn->error;
                    exit();
                }
            }
        }
        else {
            echo "Error: " . $invoiceInsertSql . "<br>" . $conn->error;
            exit();
        }
        $conn->close();
        header("Location: ../invoices.php");
        exit();
    }
?>
