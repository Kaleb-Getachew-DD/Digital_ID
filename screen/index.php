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

// Connect to the Database 
include('config.php');

$insert = false;
$update = false;
$empty = false;
$delete = false;
$already_card = false;

if(isset($_GET['delete'])){
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `cards` WHERE `sno` = $sno";
  $result = mysqli_query($conn, $sql);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset( $_POST['snoEdit'])){
      // Update the record
        $sno = $_POST["snoEdit"];
        $full_name = $_POST["nameEdit"];
        $id_no = $_POST["id_noEdit"];
        $phone = $_POST["phoneEdit"];
        $address = $_POST["addressEdit"];
        $device_name = $_POST["device_nameEdit"];
        $serial_number = $_POST["serial_numberEdit"];
        $exp_date = $_POST["exp_dateEdit"];

      // Sql query to be executed
      $sql = "UPDATE `cards` SET `full_name` = '$full_name' , `id_no` = '$id_no', `phone` = '$phone', `address` = '$address', `device_name` = '$device_name', `serial_number` = '$serial_number', `exp_date` = '$exp_date' WHERE `cards`.`sno` = $sno";
      $result = mysqli_query($conn, $sql);
      if($result){
        $update = true;
    }
    else{
        echo "We could not update the record successfully";
    }
}
else{
    $full_name = $_POST["full_name"];
    $id_no = $_POST["id_no"];
    $phone = $_POST["phone"]; 
    $address = $_POST["address"];
    $device_name = $_POST["device_name"];
    $serial_number = $_POST["serial_number"];
    $exp_date = $_POST["exp_date"];

    if($full_name == '' || $id_no == ''){
        $empty = true;
    }
    else{
        //Check that Card no. is Already Registerd or not.
        $querry = mysqli_query($conn, "SELECT * FROM cards WHERE id_no= '$id_no'");
        if(mysqli_num_rows($querry)>0)
        {
              $already_card = true;
        }
        else{


          // image upload 
          $uploaddir = '../assets/uploads/';
          $uploadfile = $uploaddir . basename($_FILES['image']['name']);

      
          if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
              
          } else {
              echo "Possible file upload attack!\n";
          }
  // Sql query to be executed
  $sql = "INSERT INTO `cards`(`full_name`, `id_no`, `phone`, `address`,`device_name`,`serial_number`, `exp_date`, `image`) VALUES ('$full_name','$id_no','$phone','$address', '$device_name', '$serial_number', '$exp_date','$uploadfile')"; 

  // $sql = "INSERT INTO `cards` (`name`, `id_no`) VALUES ('$name', '$id_no')";
  $result = mysqli_query($conn, $sql);

  if($result){ 
      $insert = true;
  }
  else{
      echo "The record was not inserted successfully because of this error ---> ". mysqli_error($conn);
  } 
}
}
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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" type="image/png" href="images/favicon.png"/>
  <title></title>
</head>

<body>

  <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel"><?= $translations["edit_card"]?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="POST">
          <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="form-group">
              <label for="name"><?= $translations["full_name"]?></label>
              <input type="text" class="form-control" id="nameEdit" name="nameEdit">
            </div>

            <div class="form-group">
              <label for="desc" > <?= $translations["id_card_number"]?></label>
              <input class="form-control" id="id_noEdit" name="id_noEdit" rows="3"></input>
            </div>
            <div class="form-group">
              <label for="desc"><?= $translations["phone"]?></label>
              <input type="text" class="form-control" id="phoneEdit" name="phoneEdit" rows="3"></input>
            </div>
            <div class="form-group">
              <label for="desc"><?= $translations["address"]?></label>
              <input class="form-control" id="addressEdit" name="addressEdit" rows="3"></input>
            </div>
            <div class="form-group">
              <label for="desc"><?= $translations["device_type"]?></label>
              <input class="form-control" id="device_nameEdit" name="device_nameEdit" rows="3"></input>
            </div>
            <div class="form-group">
              <label for="desc"><?= $translations["serial_number"]?></label>
              <input class="form-control" id="serial_numberEdit" name="serial_numberEdit" rows="3"></input>
            </div>
            <div class="form-group">
              <label for="desc"><?= $translations["expire_date"]?></label>
              <input class="form-control" id="exp_dateEdit" name="exp_dateEdit" rows="3"></input>
            </div>
            <div class="form-group">
              <label for="image"><?= $translations["current_image"]?></label>
              <img src="" id="imageEdit" class="img-fluid" alt="Card Image">
            </div>
            
          </div>
          <div class="modal-footer d-block mr-auto">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translations["close"]?></button>
            <button type="submit" class="btn btn-primary"><?= $translations["save_changes"]?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php
  if($insert){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your Card has been inserted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if($delete){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your Card has been deleted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if($update){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your Card has been updated successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
   <?php
  if($empty){
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
    <strong>Error!</strong> The Fields Cannot Be Empty! Please Give Some Values.
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
    <?php
  if($already_card){
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
    <strong>Error!</strong> This Card is Already Added.
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  
  <div class="container my-4">
  <button class="btn " type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
  <i class="fa fa-plus"></i> <?= $translations["add_new_card"]?>
  </button>
  <!-- <a href="id-card.php" class="btn btn-primary">
  <i class="fa fa-address-card"></i> Generate ID Card
</a> -->
</p>
<div class="collapse" id="collapseExample">
  <div class="card card-body">

    <form method="POST" enctype="multipart/form-data">
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputCity"><?= $translations["full_name"]?></label>
        <input type="text" name="full_name" class="form-control" id="inputCity">
      </div>
      <div class="form-group col-md-5">
        <label for="inputCity"><?= $translations["device_type"]?></label>
        <input type="text" name="device_name" class="form-control">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputCity"><?= $translations["phone"]?></label>
        <input type="text" name="phone" class="form-control">
      </div>
      <div class="form-group col-md-4">
        <label for="inputState"><?= $translations["address"]?></label>
        <input type="text" name="address" class="form-control">
      </div>
      <div class="form-group col-md-2">
        <label for="inputZip"><?= $translations["expire_date"]?></label>
        <input type="date" name="exp_date" class="form-control">
      </div>
    </div>

      <div class="form-row">
        <div class="form-group col-md-3">
          <label for="id_no"><?= $translations["identification_number"]?></label>
          <input class="form-control" id="id_no" name="id_no" ></input>
        </div>
        <div class="form-group col-md-3">
          <label for="phone"><?= $translations["serial_number"]?></label>
          <input class="form-control" id="phone" name="serial_number" ></input>
        </div>
        <div class="form-group col-md-4">
          <label for="photo"><?= $translations["photo"]?></label>
          <input type="file" name="image" />
        </div>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> <?= $translations["add_card"]?></button>
    </form>
  </div>
</div>

  <div class="container my-4">


    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">No.</th>
          <th scope="col"><?= $translations["full_name"]?></th>
          <th scope="col"><?= $translations["identification_number"]?></th>
          <th scope="col"><?= $translations["action"]?></th>
        </tr>
      </thead>
      
      <tbody>
        <?php 
          $sql = "SELECT * FROM `cards` order by 1 DESC";
          $result = mysqli_query($conn, $sql);
          $sno = 0;
          while($row = mysqli_fetch_assoc($result)){
            $sno = $sno + 1;
            echo "<tr>
            <th scope='row'>". $sno . "</th>
            <td>". $row['full_name'] . "</td>
            <td>". $row['id_no'] . "</td>
            <td> <button class='edit btn btn-sm btn-primary' id=".$row['sno'].">View</button> <button class='delete btn btn-sm btn-primary' id=d".$row['sno'].">Delete</button>  </td>
          </tr>";
        } 
          ?>


      </tbody>
    </table>
  </div>
  

  
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();

    });
  </script>
  <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        tr = e.target.parentNode.parentNode;
        name = tr.getElementsByTagName("td")[0].innerText;
        id_no = tr.getElementsByTagName("td")[1].innerText;
        console.log(name, id_no);
        nameEdit.value = name;
        id_noEdit.value = id_no;
        snoEdit.value = e.target.id;
        console.log(e.target.id)
        $('#editModal').modal('toggle');
      })
    })

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        sno = e.target.id.substr(1);

        if (confirm("Are you sure you want to delete this note!")) {
          console.log("yes");
          window.location = `sideMenu.php?delete=${sno}`;
          // TODO: Create a form and use post request to submit a form
        }
        else {
          console.log("no");
        }
      })
    })
  </script>
</body>

</html>
