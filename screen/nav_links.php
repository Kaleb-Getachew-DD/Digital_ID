<?php
// Define navigation links as an associative array
$navLinks = [
    ["label" => "Home", "url" => "home.php", "icon" => "bi bi-house"],
    ["label" => "Profile", "url" => "profile.php", "icon" => "bi bi-person"],
    ["label" => "Settings", "url" => "settings.php", "icon" => "bi bi-gear"],
    ["label" => "Logout", "url" => "logout.php", "icon" => "bi bi-box-arrow-right"]
];

// Render the navigation links
foreach ($navLinks as $link) {
    echo '<li class="nav-item">
            <a class="nav-link" href="' . htmlspecialchars($link['url']) . '">
                <i class="' . htmlspecialchars($link['icon']) . '"></i>
                ' . htmlspecialchars($link['label']) . '
            </a>
          </li>';
}
?>
