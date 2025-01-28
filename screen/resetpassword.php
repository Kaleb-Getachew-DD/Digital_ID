<?php
include 'db_connection.php';

$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';


// Check if the user switches the language
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang; // Store the selected language in the session
}

// Load the language file
$translations = include "lang/$lang.php";
$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password === $confirmPassword) {
        // Prepare and execute
        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE user_name = ?");
        $stmt->bind_param("ss", $password, $user_name);

        if ($stmt->execute()) {
            $message = $translations["password_updated_successfully"];
            $toastClass = "bg-success";
        } else {
            $message = $translations["error_updating_password"];
            $toastClass = "bg-danger";
        }

        $stmt->close();
    } else {
        $message = $translations["passwords_do_not_match"];
        $toastClass = "bg-warning";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" 
          content="width=device-width, 
                  initial-scale=1.0">
    <link href=
"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href=
"https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <script src=
"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src=
"https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Reset Password</title>
</head>

<body>
    <div class="container p-5 d-flex flex-column align-items-center">
        <?php if ($message): ?>
            <div class="toast align-items-center text-white border-0" role="alert"
          aria-live="assertive" aria-atomic="true"
                style="background-color: <?php echo $toastClass === 'bg-success' ? 
                '#28a745' : ($toastClass === 'bg-danger' ? '#dc3545' :
                ($toastClass === 'bg-warning' ? '#ffc107' : '')); ?>">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $message; ?>
                    </div>
                    <button type="button" class="btn-close 
                    btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        <form action="" method="post" class="form-control mt-5 p-4"
            style="height:auto; width:380px; box-shadow: rgba(60, 64, 67, 0.3) 
            0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;">
            <div class="row">
                <i class="fa fa-user-circle-o fa-3x mt-1 mb-2" 
          style="text-align: center; color: green;"></i>
                <h5 class="text-center p-4" style="font-weight: 700;">
          <?php echo $translations["change_your_password"]; ?></h5>
            </div>
            <div class="col-mb-3 position-relative">
                <label for="user_name"><i class="fa fa-envelope"></i> <?php echo $translations["user_name"]; ?></label>
                <input type="text" name="user_name" id="user_name" 
                  class="form-control" required>
                <span id="user_name-check" class="position-absolute"
                    style="right: 10px; top: 50%; transform: translateY(-50%);"></span>
            </div>
            <div class="col mb-3 mt-3">
                <label for="password"><i class="fa fa-lock"></i> 
                  <?php echo $translations["password"]; ?></label>
                <input type="text" name="password"
                  id="password" class="form-control" required>
            </div>
            <div class="col mb-3 mt-3">
                <label for="confirm_password"><i 
                  class="fa fa-lock"></i> <?php echo $translations["confirm_password"]; ?></label>
                <input type="text" name="confirm_password" 
                  id="confirm_password"
                  class="form-control" required>
            </div>
            <div class="col mb-3 mt-3">
                <button type="submit" class="btn bg-dark" 
                  style="font-weight: 600; color:white;">
                  <?php echo $translations["reset_password"]; ?></button>
            </div>
            <div class="col mb-2 mt-4">
                <!-- <p class="text-center" style="font-weight: 600;
                color: navy;"> <a href="./login.php"
                        style="text-decoration: none;"><?php echo $translations["login"]; ?></a></p> -->
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function () {
            $('#user_name').on('blur', function () {
                var user_name = $(this).val();
                if (user_name) {
                    $.ajax({
                        url: 'check_user_name.php',
                        type: 'POST',
                        data: { user_name: user_name },
                        success: function (response) {
                            if (response == 'exists') {
                                $('#user_name-check').html('<i class="fa fa-check 
                                text-success"></i>');
                            } else {
                                $('#user_name-check').html('<i class="fa fa-times
                                text-danger"></i>');
                            }
                        }
                    });
                } else {
                    $('#user_name-check').html('');
                }
            });

            let toastElList = [].slice.call(document.querySelectorAll('.toast'))
            let toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl, { delay: 3000 });
            });
            toastList.forEach(toast => toast.show());
        });
    </script>
</body>

</html>