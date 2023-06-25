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
    $sql = "SELECT component.Component_Name, ordered_component.Ordered_Component_Quantity, ordered_component.Ordered_Component_Price
            FROM component INNER JOIN ordered_component ON component.Component_ID = ordered_component.Component_ID
            WHERE ordered_component.Invoice_ID = $id
            ORDER BY ordered_component.Component_ID DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Component_Name"] . "</td>";
            echo "<td>" . $row["Ordered_Component_Quantity"] . "</td>";
            echo "<td>" . $row["Ordered_Component_Price"] . "</td>";
            echo "<td>" . number_format((float)$row["Ordered_Component_Price"]*$row["Ordered_Component_Quantity"], 2, '.', '') . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No Components found.</td></tr>";
    }
    $conn->close();
}
?>
