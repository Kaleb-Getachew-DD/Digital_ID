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

include 'db_connection.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $user_name = $_POST['user_name'];
    $position = $_POST['position'];
    $password = $_POST['password'];

    // Check if user_name already exists
    $checkuser_nameStmt = $conn->prepare("SELECT user_name FROM user WHERE user_name = ?");
    $checkuser_nameStmt->bind_param("s", $user_name);
    $checkuser_nameStmt->execute();
    $checkuser_nameStmt->store_result();

    if ($checkuser_nameStmt->num_rows > 0) {
        $message = "user_name ID already exists";
        $toastClass = "#007bff"; // Primary color
    } else {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO user (full_name, user_name, position, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $full_name, $user_name, $position, $password);

        if ($stmt->execute()) {
            $message = "Account created successfully";
            $toastClass = "#28a745"; // Success color
        } else {
            $message = "Error: " . $stmt->error;
            $toastClass = "#dc3545"; // Danger color
        }

        $stmt->close();
    }

    $checkuser_nameStmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href=
"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href=
"https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <script src=
"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Registration</title>
</head>

<body class="bg-light">
    <div class="container-fluid p-5 d-flex flex-column align-items-center">
        <?php if ($message): ?>
            <div class="toast align-items-center text-white border-0" 
          role="alert" aria-live="assertive" aria-atomic="true"
                style="background-color: <?php echo $toastClass; ?>;">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $message; ?>
                    </div>
                    <button type="button" class="btn-close
                    btn-close-white me-2 m-auto" 
                          data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        <form method="post" class="form-control mt-5 p-4"
            style="height:auto; width:380px;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
            rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;">
            <div class="row text-center">
                <i class="fa fa-user-circle-o fa-3x mt-1 mb-2" style="color: green;"></i>
                <h5 class="p-4" style="font-weight: 700;"><?= $translations["create_account"]?></h5>
            </div>
            <div class="mb-2">
                <label for="full_name"><i 
                  class="fa fa-user"></i> 
                  <?= $translations["full_name"]?> </label>
                <input type="text" name="full_name" id="full_name"
                  class="form-control" required>
            </div>
            <div class="mb-2">
                <label for="position"><i 
                  class="fa fa-user"></i><?= $translations["position"]?></label>
                <select name="position" id="position" class="form-control" required>
                    <option value=""disabled selected><?= $translations["select_position"]?></option>
                    <option value="admin"><?= $translations["admin"]?></option>
                    <option value="user"><?= $translations["staff"]?></option>
                </select>
            </div>
            <div class="mb-2 mt-2">
                <label for="user_name"><i 
                  class="fa fa-envelope"></i> <?= $translations["user_name"]?></label>
                <input type="text" name="user_name" id="user_name"
                  class="form-control" required>
            </div>
            <div class="mb-2 mt-2">
                <label for="password"><i 
                  class="fa fa-lock"></i> <?= $translations["password"]?></label>
                <input type="password" name="password" id="password"
                  class="form-control" required>
            </div>
            <div class="mb-2 mt-3">
                <button type="submit" 
                  class="btn btn-success
                bg-success" style="font-weight: 600;"><?= $translations["create_account"]?></button>
            </div>
            <!-- <div class="mb-2 mt-4">
                <p class="text-center" style="font-weight: 600; 
                color: navy;">I have an Account <a href="./login.php"
                        style="text-decoration: none;">Login</a></p>
            </div> -->
        </form>
    </div>
    <script>
        let toastElList = [].slice.call(document.querySelectorAll('.toast'))
        let toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 });
        });
        toastList.forEach(toast => toast.show());
    </script>
</body> 

</html>
