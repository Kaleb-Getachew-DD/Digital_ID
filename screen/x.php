<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee ID Card Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .id-card-container {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 2rem;
        }
        .preview-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            max-width: 400px;
            margin: 0 auto;
        }
        .card-header {
            background: #007bff;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1rem;
            text-align: center;
        }
        .employee-photo {
            width: 120px;
            height: 120px;
            border: 3px solid #007bff;
            border-radius: 50%;
            margin: -60px auto 1rem;
            background: white;
        }
        .print-only {
            display: none;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            .preview-card, .preview-card * {
                visibility: visible;
            }
            .preview-card {
                position: fixed;
                left: 0;
                top: 0;
                margin: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body class="id-card-container">
    <div class="container">
        <div class="row">
            <!-- Input Form -->
            <div class="col-md-6">
                <h2 class="mb-4">Employee Information</h2>
                <form method="POST" class="bg-light p-4 rounded shadow">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" 
                               value="<?= $_POST['name'] ?? '' ?>" required>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Employee ID</label>
                            <input type="text" name="empid" class="form-control" 
                                   value="<?= $_POST['empid'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <input type="text" name="department" class="form-control" 
                                   value="<?= $_POST['department'] ?? '' ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Photo URL</label>
                        <input type="url" name="photo" class="form-control" 
                               value="<?= $_POST['photo'] ?? 'https://via.placeholder.com/150' ?>" 
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Generate ID Card</button>
                </form>
            </div>

            <!-- ID Card Preview -->
            <div class="col-md-6 mt-md-0 mt-4">
                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): 
                    $name = htmlspecialchars($_POST['name']);
                    $empid = htmlspecialchars($_POST['empid']);
                    $department = htmlspecialchars($_POST['department']);
                    $photo = filter_var($_POST['photo'], FILTER_VALIDATE_URL) ? $_POST['photo'] : 'https://via.placeholder.com/150';
                ?>
                <div class="preview-card">
                    <div class="card-header">
                        <h3 class="mb-0">Company Name</h3>
                        <p class="mb-0">Employee Identity Card</p>
                    </div>
                    
                    <div class="card-body text-center pt-5">
                        <img src="<?= $photo ?>" class="employee-photo" alt="Employee Photo">
                        
                        <h4 class="mb-2"><?= $name ?></h4>
                        <div class="text-muted mb-3"><?= $department ?></div>
                        
                        <table class="table table-bordered">
                            <tr>
                                <th>Employee ID</th>
                                <td><?= $empid ?></td>
                            </tr>
                            <tr>
                                <th>Valid Until</th>
                                <td>31-Dec-2025</td>
                            </tr>
                        </table>
                        
                        <div class="row mt-3">
                            <div class="col-6">
                                <small class="text-muted">Employee Signature</small>
                                <div class="border-bottom"></div>
                            </div>
                            <div class="col-6">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?= $empid ?>" 
                                     alt="QR Code" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>

                <button onclick="window.print()" class="btn btn-success w-100 mt-3">
                    Print ID Card
                </button>
                <?php else: ?>
                    <div class="alert alert-info">Submit form to preview ID Card</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>