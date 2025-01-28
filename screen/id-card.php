<?php
// Include the phpqrcode library
include('../assets/phpqrcode/qrlib.php');

// Set default language to English
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

// Check if the user switches the language
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang; // Store the selected language in the session
}

// Load the language file
$translations = include "lang/$lang.php";

$notfound = false;
include 'config.php';
$html = '';
$qrHtml = ''; // HTML for the QR code card

if (isset($_POST['search'])) {
    $id_no = $_POST['id_no'];
    $sql = "SELECT * FROM cards WHERE id_no='$id_no'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $html = "<div class='card' style='width:350px; padding:0;'>";
        $qrHtml = "<div class='card' style='width:350px; padding:0;'>"; // Initialize QR code card

        while ($row = mysqli_fetch_assoc($result)) {
            $full_name = $row["full_name"];
            $id_no = $row["id_no"];
            $phone = $row['phone'];
            $address = $row['address'];
            $device_name = $row['device_name'];
            $serial_number = $row['serial_number'];
            $exp_date = $row['exp_date'];
            $image = $row['image'];
            $date = date('M d, Y', strtotime($row['date']));

            // Generate QR code
            $qrCodePath = 'qrcodes/' . $id_no . '.png';
            QRcode::png($id_no, $qrCodePath);

            // ID card HTML
            $html .= "
                <!-- second id card  -->
                <div class='container' style='text-align:left; border:2px dotted black;'>
                    <div class='header' style='text-align: center; padding: 10px;'>
                        <img src='../assets/images/logo.png' alt='University Logo' style='max-width: 70px;'>
                        <h5 style='margin-top: 7px; font-size: 12px;'>Dire Dawa University</h5>
                        <h6 style='font-size: 10px;'>Laptop ID</h6>
                    </div>
                    <div class='container-2'>
                        <div class='box-1'>
                            <img src='$image'/>
                        </div>
                        <div class='box-2'>
                            <h2>$full_name</h2>
                        </div>
                    </div>
                    <div class='container-3'>
                        <div class='info-1'>
                            <div class='id'>
                                <h4>ID No</h4>
                                <p>$id_no</p>
                            </div>
                            <div class='dob'>
                                <h4>Serial Number</h4>
                                <p>$serial_number</p>
                            </div>
                        </div>
                        <div class='info-2'>
                            <div class='join-date'>
                                <h4>Issued Date</h4>
                                <p>$date</p>
                            </div>
                            <div class='expire-date'>
                                <h4>Expire Date</h4>
                                <p>$exp_date</p>
                            </div>
                        </div>
                        <div class='info-3'>
                            <div class='email'>
                                <h4>Device Type</h4>
                                <p>$device_name</p>
                            </div>
                        </div>
                        <div class='info-4'>
                            <div class='sign'>
                                <br>
                                <p style='font-size:12px; margin-bottom: 8px;'>Auth Signature</p>
                                <p style='font-family: Dancing Script'>Gate Security</p>
                            </div>
                        </div>
                    </div>
                </div>
            ";

            // QR code card HTML
            $qrHtml .= "
                <div class='container' style='text-align:left; border:2px dotted black;'>
                    <div class='header' style='text-align: center; padding: 10px;'>
                        
                        <h6 style='font-size: 10px;'>QR Code</h6>
                    </div>
                    <div class='container-2' style='text-align: center;'>
                        <img src='$qrCodePath' alt='QR Code' style='width: 150px; height: 150px;'>
                    </div>
                    
                </div>
            ";
        }
        $html .= "</div>";
        $qrHtml .= "</div>";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
    <link rel="stylesheet" href="css/dashboard.css">
    <title><?= $translations["card_generation"] ?> <?php echo date("Y") ?></title>
    <style>
    
body{
   font-family:'arial';
   }

.lavkush img {
  border-radius: 8px;
  border: 2px solid blue;
}
span{

    font-family: 'Orbitron', sans-serif;
    font-size:16px;
}
hr.new2 {
  border-top: 1px dashed black;
  width:350px;
  text-align:left;
  align-items:left;
}
 /* second id card  */
 p{
     font-size: 13px;
     margin-top: -5px;
 }

 
 .container {
    width: 80vh;
    height: 45vh;
    margin: auto;
    background-color: white;
    box-shadow: 0 1px 10px rgb(146 161 176 / 50%);
    overflow: hidden;
    border-radius: 10px;
}

/* .header {
    border: 2px solid black;
    width: 73vh;
    height: 15vh;
    margin: 20px auto;
    background-color: white;
    box-shadow: 0 1px 10px rgb(146 161 176 / 50%);
    border-radius: 10px;
    background-image: url(assets/images/Code_Camp_BD.png);
    overflow: hidden;
    font-family: 'Poppins', sans-serif;
} */

.header h1 {
    color: rgb(90 139 249);
    text-align: right;
    margin-right: 20px;
    margin-top: 15px;
}

.header p {
    color: rgb(157, 51, 0);
    text-align: right;
    margin-right: 22px;
    margin-top: -10px;
}

.container-2 {
    /* border: 2px solid red; */
    width: 73vh;
    height: 10vh;
    margin: 0px auto;
    margin-top: -10px;
    display: flex;
}

.box-1 {
    border: 4px solid #fff;
    width: 90px;
    height: 95px;
    margin: -20px 25px;
    border-radius: 3px;
}

.box-1 img {
    width: 82px;
    height: 87px;
}

.box-2 {
    /* border: 2px solid purple; */
    width: 33vh;
    height: 8vh;
    margin: 7px 0px;
    padding: 5px 7px 0px 0px;
    text-align: left;
    font-family: 'Poppins', sans-serif;
}

.box-2 h2 {
    font-size: 1.3rem;
    margin-top: -5px;
    color: rgb(90 139 249);
    ;
}

.box-2 p {
    font-size: 0.7rem;
    margin-top: -5px;
    color: rgb(179, 116, 0);
}

.box-3 {
    /* border: 2px solid rgb(21, 255, 0); */
    width: 8vh;
    height: 8vh;
    margin: 8px 0px 8px 30px;
}

.box-3 img {
    width: 8vh;
}

.container-3 {
    /* border: 2px solid rgb(111, 2, 161); */
    width: 73vh;
    height: 12vh;
    margin: 0px auto;
    margin-top: 10px;
    display: flex;
    font-family: 'Shippori Antique B1', sans-serif;
    font-size: 0.7rem;
}

.info-1 {
    /* border: 1px solid rgb(255, 38, 0); */
    width: 17vh;
    height: 12vh;
}

.id {
    /* border: 1px solid rgb(2, 92, 17); */
    width: 17vh;
    height: 5vh;
}

.id h4 {
    color: rgb(90 139 249);
    font-size:15px;
}

.dob {
    /* border: 1px solid rgb(0, 46, 105); */
    width: 17vh;
    height: 5vh;
    margin: 8px 0px 0px 0px;
}

.dob h4 {
    color: rgb(90 139 249);
    font-size:15px;
}

.info-2 {
    /* border: 1px solid rgb(4, 0, 59); */
    width: 17vh;
    height: 12vh;
}

.join-date {
    /* border: 1px solid rgb(2, 92, 17); */
    width: 17vh;
    height: 5vh;
}

.join-date h4 {
    color: rgb(90 139 249);
    font-size:15px;
}

.expire-date {
    /* border: 1px solid rgb(0, 46, 105); */
    width: 17vh;
    height: 5vh;
    margin: 8px 0px 0px 0px;
}

.expire-date h4 {
    color: rgb(90 139 249);
    font-size:15px;
}

.info-3 {
    /* border: 1px solid rgb(255, 38, 0); */
    width: 17vh;
    height: 12vh;
}

.email {
    /* border: 1px solid rgb(2, 92, 17); */
    width: 22vh;
    height: 5vh;
}

.email h4 {
    color: rgb(90 139 249);
    font-size:15px;
}

.phone {
    /* border: 1px solid rgb(0, 46, 105); */
    width: 17vh;
    height: 5vh;
    margin: 8px 0px 0px 0px;
}

.info-4 {
    /* border: 2px solid rgb(255, 38, 0); */
    width: 22vh;
    height: 12vh;
    margin: 0px 0px 0px 0px;
    font-size:15px;
}

.phone h4 {
    color: rgb(90 139 249);
    font-size:15px;
}

.sign {
    /* border: 1px solid rgb(0, 46, 105); */
    width: 17vh;
    height: 5vh;
    margin: 41px 0px 0px 20px;
    text-align: center;
}
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.js"></script>
</head>
<body>
    <div class="row" style="margin: 0px 20px 5px 20px">
        <div class="col-sm-6">
            <div class="card jumbotron">
                <div class="card-body">
                    <form class="form" method="POST" action="">
                        <label for="exampleInputEmail1"><?= $translations["student_id_card"] ?></label>
                        <input class="form-control mr-sm-2" type="search" placeholder="<?= $translations["search_id"] ?>" name="id_no">
                        <small id="emailHelp" class="form-text text-muted">Every User's should have unique Id no.</small>
                        <br>
                        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit" name="search"><?= $translations["generate"] ?></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <?= $translations["here_is_id"] ?>
                </div>
                <div class="card-body" id="mycard">
                    <?php echo $html ?>
                    <?php echo $qrHtml ?>
                </div>
                <br>
            </div>
        </div>
    </div>
    <hr>
    <button id="demo" class="downloadtable btn btn-primary" onclick="downloadtable()"><?= $translations["download_id"] ?></button>
    <!-- Optional JavaScript -->
    <script>
        function downloadtable() {
            var node = document.getElementById('mycard');
            domtoimage.toPng(node)
                .then(function (dataUrl) {
                    var img = new Image();
                    img.src = dataUrl;
                    downloadURI(dataUrl, "card.png")
                })
                .catch(function (error) {
                    console.error('oops, something went wrong', error);
                });
        }

        function downloadURI(uri, name) {
            var link = document.createElement("a");
            link.download = name;
            link.href = uri;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            delete link;
        }
    </script>
</body>
</html>