<?php
// nav_links.php: PHP file containing navigation links as an array
session_start();

if(!isset($_SESSION['user_name'])){
    header('Location:login.php');
}

// Set default language to English
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';


// Check if the user switches the language
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang; // Store the selected language in the session
}

// Load the language file
$translations = include "lang/$lang.php";

$navLinks = [
    ["label" => $translations["id_card_register"], "url" => "index.php", "icon" => "fa fa-id-card"],
    ["label" => $translations["generate_id_card"], "url" => "id-card.php", "icon" => "fa fa-address-card"],
    ["label" => $translations["present_process"], "url" => "pp.php", "icon" => "fa fa-address-card"],
    ["label" => $translations["change_password"], "url" => "resetpassword.php", "icon" => "fa fa-address-card"],
    
];

if ($_SESSION['position'] == 'admin') {
    $navLinks = [
        ["label" => $translations["dashboard"], "url" => "dashboard.php", "icon" => "fa fa-dashboard"],
        ["label" => $translations["create_account"], "url" => "register.php", "icon" => "fa fa-user"],
        ["label" => $translations["change_password"], "url" => "resetpassword.php", "icon" => "fa fa-address-card"],
        ["label" => $translations["account_management"], "url" => "users.php", "icon" => "fa fa-address-card"],
    ];
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    

    <title>Digital ID Management System</title>
    
    <style>
        body {
            overflow-x: hidden;
        }

.custom-dropdown {
    position: relative;
    display: inline-block;
}
.dd-toggle {
    background: white;
    border: 1px solid #28a745;
    color: #28a745;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
}
.dd-menu {
    display: none;
    position: absolute;
    right: 0;
    background: white;
    border: 1px solid #28a745;
    border-radius: 4px;
    min-width: 160px;
    margin-top: 5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.dd-item {
    display: block;
    padding: 8px 16px;
    color: #333;
    text-decoration: none;
}
.dd-item:hover {
    background: #e9f5e9;
    color: #28a745;
}
.custom-dropdown:hover .dd-menu,
.custom-dropdown:focus-within .dd-menu {
    display: block;
}

        #sideMenu {
            height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            transform: translateX(-250px);
            transition: transform 0.3s ease-in-out;
        }

        #sideMenu.open {
            transform: translateX(0);
        }

        #sideMenu a {
            color: white;
            padding: 15px;
            display: block;
            text-decoration: none;
        }

        #sideMenu a:hover {
            background-color: #495057;
        }

        #content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
        }

        #content.active {
            margin-left: 250px;
        }

        #header {
            background-color: green;
            color: white;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .menu-btn {
            font-size: 1.5rem;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <!-- Side Menu -->
    <div id="sideMenu">
        <h3 class="text-center py-3"> <?PHP echo $translations["digital_id"]?> </h3>
        
        <?php
        // Include the navigation links from another PHP file
        // include 'nav_links.php';

        // Render navigation links dynamically
        foreach ($navLinks as $link) {
            echo "<a href='?page=" . urlencode($link['url']) . "'><i class='" . $link['icon'] . "'></i> " . $link['label'] . "</a>";
        }
        ?>
    </div>

    <!-- Main Content -->
    <div id="content">
        <!-- Header -->
        <div id="header">
            <div>
                <span class="menu-btn" onclick="toggleMenu()"><i class="fa fa-bars"></i></span>
                <span>DIMS</span>
            </div>
            <div class="text-right p-2">
    <div class="custom-dropdown">
        <button class="dd-toggle">🌐 Select Language</button>
        <div class="dd-menu">
            <a href="?lang=en" class="dd-item">🇬🇧 English</a>
            <a href="?lang=am" class="dd-item">🇪🇹 አማርኛ</a>
        </div>
    </div>
</div>
            <div class="justify-content-end">
                <a href="logout.php" class="btn text-white"><?PHP echo $translations["logout"]?></a>
            </div>
        </div>

        <div class="">
            <?php
            // Dynamically include the requested page, or show a default page
            if ($_SESSION['position'] == 'admin'){
                $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard.php';
            }
            else{
                $page = isset($_GET['page']) ? $_GET['page'] : 'index.php';
            }
            

            // Secure the input and check if the file exists before including it
            $pagePath = basename($page); // Prevent directory traversal
            if (file_exists($pagePath)) {
                include $pagePath;
            } else {
                echo "<h1>" . $translations["page_not_found"] . "</h1>";
            }
            ?>
        </div>
    </div>

    <script>
        function toggleMenu() {
            document.getElementById("sideMenu").classList.toggle("open");
            document.getElementById("content").classList.toggle("active");
        }
    </script>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>
