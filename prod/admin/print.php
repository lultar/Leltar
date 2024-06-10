<?php
 require "db.php";


// Fetch item data
$sql = "SELECT i.ItemName, i.Description, i.Quantity, i.RealQuantity, m.MeasurementType, s.ShelfName, a.AisleName, b.BuildingName 
        FROM Items i 
        LEFT JOIN MeasurementTypes m ON i.MeasurementTypeID = m.MeasurementTypeID 
        LEFT JOIN Shelves s ON i.ShelfID = s.ShelfID 
        LEFT JOIN Aisles a ON s.AisleID = a.AisleID 
        LEFT JOIN Buildings b ON a.BuildingID = b.BuildingID";

$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/Leltar/prod/imgs/colored/icons8-quantum-64.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quantum Storage & Logistics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .more {
            background-color: #d4edda; /* Light green */
            color: #155724; /* Dark green */
        }
        .less {
            background-color: #f8d7da; /* Light red */
            color: #721c24; /* Dark red */
        }
        .arrow-up::after {
            content: ' \2191'; /* Up arrow */
        }
        .arrow-down::after {
            content: ' \2193'; /* Down arrow */
        }

        @media print {
            .more {
                background-color: #d4edda !important; /* Light green */
                color: #155724 !important; /* Dark green */
            }
            .less {
                background-color: #f8d7da !important; /* Light red */
                color: #721c24 !important; /* Dark red */
            }

            body {
                -webkit-print-color-adjust: exact; /* Ensures colors are printed accurately */
            }
        }
    </style>
</head>
<body>
    <h1>Items Report</h1>
    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Real Quantity</th>
                <th>Difference</th>
                <th>Measurement Type</th>
                <th>Shelf</th>
                <th>Aisle</th>
                <th>Building</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $difference = $row["Quantity"] - $row["RealQuantity"];
                    $class = '';
                    $arrow = '';
                    if ($difference > 0) {
                        $class = 'more';
                        $arrow = 'arrow-up';
                    } elseif ($difference < 0) {
                        $class = 'less';
                        $arrow = 'arrow-down';
                    }
                    echo "<tr class='{$class}'>
                            <td>{$row['ItemName']}</td>
                            <td>{$row['Description']}</td>
                            <td>{$row['Quantity']}</td>
                            <td>{$row['RealQuantity']}</td>
                            <td class='{$arrow}'>{$difference}</td>
                            <td>{$row['MeasurementType']}</td>
                            <td>{$row['ShelfName']}</td>
                            <td>{$row['AisleName']}</td>
                            <td>{$row['BuildingName']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No items found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <script type="text/javascript">
        window.onload = function() {
            // Trigger print dialog
            window.print();
            
            // Add an event listener to detect when the print dialog is closed
            window.addEventListener('afterprint', function() {
                // Redirect to the home page
                window.location.href = "admin.php";
            });
        };
    </script>
</body>
</html>

<?php
$connection->close();
?>
