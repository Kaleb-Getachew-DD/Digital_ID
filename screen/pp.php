<?php

// Set default language to English
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';


// Check if the user switches the language
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang; // Store the selected language in the session
}

// Load the language file
$translations = include "lang/$lang.php";

// Generate the ID card when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieving form data
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $id = $_POST['id'];
    $department = $_POST['department'];

    // Generate the ID card HTML
    $idCard = "
    <div id='id-card' class='card' style='width: 18rem; border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
        <div class='card-body'>
            <h5 class='card-title text-center' style='color: #28a745;'>ID Card</h5>
            <p class='card-text'><strong>Name:</strong> $name</p>
            <p class='card-text'><strong>Device:</strong> $dob</p>
            <p class='card-text'><strong>ID:</strong> $id</p>
            <p class='card-text'><strong>Serial No.:</strong> $department</p>
        </div>
    </div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .form-container {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            padding: 30px;
        }
        .card-body {
            background-color: #f1fdf3;
        }
        .card-title {
            color: #28a745;
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
            border: none;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
        .row {
            display: flex;
            justify-content: space-between;
        }
        .form-card {
            width: 45%;
        }
        .id-card {
            width: 45%;
        }
        .btn-action {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center mb-4"> <?= $translations["id_present_process"]?></h2>
        <div class="row">
            <!-- Form Section -->
            <div class="form-card card col-md-5 p-4">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label"> <?= $translations["full_name"]?></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label"> <?= $translations["device_type"]?></label>
                        <input type="text" class="form-control" id="dob" name="dob" required>
                    </div>
                    <div class="mb-3">
                        <label for="id" class="form-label"> <?= $translations["id_number"]?></label>
                        <input type="text" class="form-control" id="id" name="id" required>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label"> <?= $translations["serial_number"]?></label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>
                    <button type="submit" class="btn btn-custom"> <?= $translations["generate_id_card"]?></button>
                </form>
            </div>

            <!-- ID Card Section -->
            <div class="id-card card col-md-5 p-4">
                <?php
                // If form is submitted, display the generated ID card
                if (isset($idCard)) {
                    echo "<div class='mt-4'>$idCard</div>";
                }
                ?>
                <!-- Buttons for Print and Download -->
                <?php if (isset($idCard)) { ?>
                    <div class="btn-action text-center">
                        <!-- Print Button -->
                        <button onclick="printCard()" class="btn btn-custom"> <?= $translations["print_card"]?></button>
                        
                        
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Print Card Function
        function printCard() {
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>ID Card</title>');
            printWindow.document.write('<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">');
            printWindow.document.write('</head><body>');
            printWindow.document.write(document.getElementById('id-card').innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        // Download Card Function (Converts to PNG using html2canvas)
        function downloadCard() {
            var cardElement = document.getElementById('id-card');
            html2canvas(cardElement, { scale: 2 }).then(function(canvas) {
                var link = document.createElement('a');
                link.download = 'id_card.png';  // Specify the download filename
                link.href = canvas.toDataURL();  // Convert canvas to base64 string
                link.click();
            });
        }
    </script>

    <!-- Include html2canvas library for download functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
</body>
</html>
