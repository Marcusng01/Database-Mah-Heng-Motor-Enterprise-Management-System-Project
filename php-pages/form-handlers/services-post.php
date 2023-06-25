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
        $serviceDescription = $_POST["description"];
        $serviceTotalPrice = $_POST["totalPrice"];
        $serviceDate = $_POST["serviceDate"];
        $serviceMotorID = $_POST["motorcycleId"];
        $serviceComponents = $_POST["serviceComponents"];

        // Get Customer ID based on if the customer is existing or new
        $customerType = $_POST["customerType"];
        if ($customerType == "existing"){
            $customerID = $_POST["customerID"];
        }
        else if ($customerType == "new"){
            $customerName = $_POST["customerName"];
            $customerPhone = $_POST["customerPhone"];
            // Insert new customer details
            $customerQuery = "INSERT INTO customer_details (Customer_Name, Customer_Contact_Number) VALUES ('$customerName', '$customerPhone');";
            if ($conn->query($customerQuery) === TRUE) {
                $customerID = $conn->insert_id;
            }
        }
        else {
            // Invalid customer option selected
            echo "Invalid customer option selected.";
            exit();
        }

        // Insert service details
        $serviceQuery = "INSERT INTO service (Description, Service_Total_Price, Service_Date, Motor_ID, Customer_ID) VALUES ('$serviceDescription','$serviceTotalPrice', '$serviceDate','$serviceMotorID','$customerID');";
        if ($conn->query($serviceQuery) === TRUE) {
            $serviceID = $conn->insert_id;
            foreach($serviceComponents as $component){
                $componentID = $component["componentName"];
                $componentPricePerPiece = $component["pricePerPiece"];
                $componentQuantity = $component["quantity"];
                $serviceComponentQuery = "INSERT INTO service_component (Service_ID, Component_ID, Service_Component_Price_Per_Unit, Service_Component_Quantity) VALUES ($serviceID,$componentID,$componentPricePerPiece,$componentQuantity);";
                if ($conn->query($serviceComponentQuery) === TRUE){
                    $stockQuantityQuery = "SELECT Component_Quantity FROM component WHERE Component_ID = $componentID";
                    $result = $conn->query($stockQuantityQuery);
                    $stockQuantity = $result->fetch_array()[0];
                    $deductStockQuery = "UPDATE component SET Component_Quantity = $stockQuantity-$componentQuantity WHERE Component_ID = $componentID";
                    $result = $conn->query($deductStockQuery);
                }
                else{
                    echo "Error: " . $serviceInsertSql . "<br>" . $conn->error;
                    exit();
                }
            }
        }
        else {
            echo "Error: " . $serviceInsertSql . "<br>" . $conn->error;
            exit();
        }
        $conn->close();
        header("Location: ../services.php");
        exit();
    }
?>
