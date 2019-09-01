<?php
  session_start();

  if (!isset($_SESSION['userEmail'])) {
      header("Location: logout.php?error=mysql");
  }
 ?>

<!doctype HTML>
<html>

<title>Physics Lab App</title>

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

  <style>
        /* In order to place the tracking correctly */
        canvas.drawing, canvas.drawingBuffer {
            position: absolute;
            left: 0;
            top: 0;
        }

        .alert , .hiddenModal {
          display: none;
        }

        #barcode-scanner video, canvas {
          width: 100%;
          height: auto;
        }

        #barcode-scanner video.drawingBuffer, canvas.drawingBuffer {
          display: none;
        }

        .code-overlay {
          position: absolute;
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
          text-align: center;
          display: none;
        }
    </style>

</head>

<body>
  <header class="header bg-primary p-4 text-light">
    <div class="float-right">
      <a href="logout.php"><button class="btn bg-danger text-light text-center">Log out</button></a>
    </div>
    <div class="container" id="titleBar">
      <img src="img\logo.png" class="img-fluid mr-2" alt="Atom.png">CavLab
    </div>
  </header>
  <div class="container-fluid text-center">
    <div class="row">
      <div class="col"></div>
      <div class="col-lg-4 col-lg-offset-1 col-xs-12">
        <div class="container-fluid mb-3">
          <div class="container-fluid text-center">
              <h3 class="mt-5">Borrow by Typing below...</h3>
          </div>
          <!-- <div class="container-fluid text-center">
            <button class="btn btn-primary p-2" id="btn">Scan</button>
          </div> -->
        </div>
        <div id="manualInput">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Type code here..." aria-label="Barcode Alt" aria-describedby="button-addon2" id="codeInput">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick="openConfirmItemDialog( -1 )">Add</button>
            </div>
          </div>
          <div class="alert alert-danger" id="emptyCodeAlert">
            <strong>Error!</strong> Input Code is Empty!
          </div>
          <div class="alert alert-danger" id="NoSuchCodeAlert">
            <strong>Error!</strong> The code you entered does not show up in our database. Check your code!
          </div>
          <div class="alert alert-danger" id="WrongInputAlert">
            <strong>Error!</strong> Please check your input!
          </div>
        </div>

        <div class="container text-center">
          <button type="button" class="bg-warning text-light btn" name="photo-take-button" data-toggle="modal" data-target="#cameraModal" onclick="openCameraModal()"><i class="material-icons mr-2" style="float:left">camera_alt</i>Take Photo (Beta)</button><br />
          <button id="updateYourItemsModalButton" class="bg-info text-light btn mt-3" data-toggle="modal" data-target="#yourItemsModal" onclick="getUserItems()"><i class="material-icons mr-2" style="float:left;">all_inbox</i>Your Items</button>
        </div>
      </div>
      <div class="col"></div>
    </div>
    </div>
    <!-- <div class="container" id="errorLogBox"></div> -->
    </div>
  </div>
</body>

<!-- Camera Modal -->
<div id="cameraModal" class="modal hiddenModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title mr-1">Barcode Scanner</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="container text-center">
          <h4>Aim at the target barcode only...</h4>
        </div>
        <div class="container">
          <div id="barcode-scanner">
            <div class="code-overlay bg-dark">
              <h2 class="text-light">Code Detection Error</h2>
            </div>
            <video src=""></video>
            <canvas class="drawingBuffer"></canvas>
          </div>
				<div class="error"></div>
        </div>
      </div>
      <div class="modal-footer">
        <div id="after-scan">
          <button type="button" name="confirm-button" class="btn btn-warning" onclick="startScanner()" style="display:none;">Try Again</button>
        </div>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
    </div>

  </div>
</div>

<!-- Confirm Modal -->
<div id="confirmModal" class="modal hiddenModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title mr-1">Borrowing Item</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>
          The following item will be borrowed:
        </p>
        <span id="pendingItem" class="font-weight-bold"></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="addItem()">Confirm</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
    </div>

  </div>
</div>

<!-- Sucess Alert Modal -->
<div id="borrowSuccessModal" class="modal hiddenModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title mr-1">Enjoy!</h4>
      </div>
      <div class="modal-body">
        <p>
          You have successfully borrowed the item
        </p>
        <span id="pendingItem" class="font-weight-bold"></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Your Items Modal -->
<div id="yourItemsModal" class="modal slide hiddenModal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title mr-1">Your Items</h4>
      </div>
      <div class="alert alert-success" id="returnSuccessAlert">
        <strong>Success!</strong> Thanks for returning the item!
      </div>
      <div class="alert alert-danger" id="returnFailureAlert">
        <strong>Error!</strong> Wrong code input!
      </div>
      <div class="modal-body">
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th scope="col" style="width: 50%;">Item Name</th>
              <th scope="col">Borrowed</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody id="yourItemsTable">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- External scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdn.rawgit.com/serratus/quaggaJS/0420d5e0/dist/quagga.min.js"></script>

<!-- Page script -->
<script src="index.js"></script>

</html>
