<?php
if (isset($_POST['id'])) {
    $id = $_POST['id'];

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
    $sql = "SELECT component.Component_Name, service_component.Service_Component_Quantity, service_component.Service_Component_Price_Per_Unit
            FROM component INNER JOIN service_component ON component.Component_ID = service_component.Component_ID
            WHERE service_component.Service_ID = $id
            ORDER BY service_component.Component_ID DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Component_Name"] . "</td>";
            echo "<td>" . $row["Service_Component_Quantity"] . "</td>";
            echo "<td>" . $row["Service_Component_Price_Per_Unit"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No Components found.</td></tr>";
    }
    $conn->close();
}
?>
