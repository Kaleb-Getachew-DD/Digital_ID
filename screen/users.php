<?php

// Include database connection
include 'db_connection.php';

// Set default language to English
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';


// Check if the user switches the language
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang; // Store the selected language in the session
}

// Load the language file
$translations = include "lang/$lang.php";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'update') {
            // Update user
            $stmt = $conn->prepare("UPDATE user SET 
                full_name = ?, 
                user_name = ?, 
                position = ?, 
                status = ? 
                WHERE id = ?");
            
            $stmt->bind_param("ssssi",
                $_POST['full_name'],
                $_POST['user_name'],
                $_POST['position'],
                $_POST['status'],
                $_POST['id']
            );
            $stmt->execute();
            $stmt->close();
            
        } elseif ($_POST['action'] === 'delete') {
            // Delete user
            $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
            $stmt->bind_param("i", $_POST['id']);
            $stmt->execute();
            $stmt->close();
        }
        
        header("Location: sideMenu.php?page=users.php");
        exit();
    }
}

// Fetch all user
$result = $conn->query("SELECT * FROM user");
$users = $result->fetch_all(MYSQLI_ASSOC);
$result->close();
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #28a745;
            --hover-color: #218838;
        }
        .primary-bg { background-color: var(--primary-color); }
        .btn-primary { 
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
        }
        .table-hover tbody tr:hover {
            background-color: rgba(40, 167, 69, 0.05);
        }
        .status-badge {
            padding: 0.35rem 0.65rem;
            font-size: 0.875em;
            border-radius: 10rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">

        <h2 class="mb-4 text-success"><?= $translations['title'] ?></h2>
        
        <div class="card shadow">
            <div class="card-header primary-bg text-white">
                <h5 class="mb-0"><?= $translations['users_list'] ?></h5>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th><?= $translations['full_name'] ?></th>
                                <th><?= $translations['username'] ?></th>
                                <th><?= $translations['position'] ?></th>
                                <th><?= $translations['status'] ?></th>
                                <th><?= $translations['created_date'] ?></th>
                                <th><?= $translations['actions'] ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['full_name']) ?></td>
                                <td><?= htmlspecialchars($user['user_name']) ?></td>
                                <td><?= htmlspecialchars($user['position']) ?></td>
                                <td>
                                    <span class="status-badge <?= $user['status'] == 1 ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= $user['status'] == 1 ? $translations['active'] : $translations['inactive'] ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($user['created_date'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-btn" 
                                            data-user='<?= htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8') ?>'>
                                        <?= $translations['edit'] ?>
                                    </button>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('<?= $translations['confirm_delete'] ?>')">
                                            <?= $translations['delete'] ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header primary-bg text-white">
                    <h5 class="modal-title"><?= $translations['edit_user'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="editId">
                        
                        <div class="mb-3">
                            <label><?= $translations['full_name'] ?></label>
                            <input type="text" class="form-control" name="full_name" id="editFullName" required>
                        </div>
                        
                        <div class="mb-3">
                            <label><?= $translations['username'] ?></label>
                            <input type="text" class="form-control" name="user_name" id="editUserName" required>
                        </div>
                        
                        <div class="mb-3">
                            <label><?= $translations['position'] ?></label>
                            <select class="form-select" name="position" id="editPosition" required>
                                <option value="admin">Admin</option>
                                <option value="user">Staff</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label><?= $translations['status'] ?></label>
                            <select class="form-select" name="status" id="editStatus" >
                                <option value="1"><?= $translations['active'] ?></option>
                                <option value="0"><?= $translations['inactive'] ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $translations['close'] ?></button>
                        <button type="submit" class="btn btn-primary"><?= $translations['save_changes'] ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const user = JSON.parse(btn.dataset.user);
                document.getElementById('editId').value = user.id;
                document.getElementById('editFullName').value = user.full_name;
                document.getElementById('editUserName').value = user.user_name;
                document.getElementById('editPosition').value = user.position;
                document.getElementById('editStatus').value = user.status;
                
                new bootstrap.Modal(document.getElementById('editModal')).show();
            });
        });
    </script>
</body>
</html>