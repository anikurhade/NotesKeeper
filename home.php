<?php
include "connection.php";

if(session_status() == PHP_SESSION_NONE)
  session_start();

if(!isset($_SESSION["user"]))
  header("Location: index.php");

// if(isset( $_SESSION['counter'])) {
//   $_SESSION['counter'] += 1;
//   $welcomeback = "Welcome back ".$_SESSION['user'];
// }
// else {
//   $_SESSION['counter'] = 1;
//   $welcome = "Welcome ".$_SESSION['user'];
// }

if(isset($_POST['save-note'])) {
  $title = $_POST['title'];
  $note = $_POST['note'];
  $label = $_POST['label'];
  $user = $_SESSION['user'];

  $stmt = $conn->prepare("INSERT INTO note (title, note_body, label, user) VALUES(?, ?, ?, ?)");
  $stmt->bind_param("ssss", $title, $note, $label, $user);

  $stmt->execute();
  //echo mysqli_error($conn);
  $stmt->close();
}

if(isset($_POST['save-note-archive'])) {
  $title = $_POST['title'];
  $note = $_POST['note'];
  $archive = intval(true);
  $label = $_POST['label'];
  $user = $_SESSION['user'];

  $stmt = $conn->prepare("INSERT INTO note (title, note_body, label, archive, user) VALUES(?, ?, ?, ?, ?)");
  $stmt->bind_param("sssis", $title, $note, $label, $archive, $user);

  if(!$stmt->execute()) {
    //echo mysqli_error($conn);
    echo "<script>alert('Error: ".mysqli_error($conn)."');</script>";
  }
  $stmt->close();
}

if(isset($_POST['save-label'])) {
  $label = $_POST['addlabel'];
  $user = $_SESSION['user'];

  $sql = "INSERT INTO label (text, user) VALUES ('$label', '$user')";
  if(!mysqli_query($conn, $sql)) {
    echo "<script>alert('Error: ".mysqli_error($conn)."');</script>";
  }
}

if(isset($_POST['update-note'])) {
  $id = $_POST['uid'];
  $title = $_POST['utitle'];
  $note_body = $_POST['unote'];
  $label = $_POST['ulabel'];

  $sql = "UPDATE note SET title='$title', note_body='$note_body', label='$label', archive=false WHERE id='$id'";
  if(!mysqli_query($conn, $sql)) {
    echo "<script>alert('Error: ".mysqli_error($conn)."');</script>";
  }
  //echo mysqli_error($conn);
}

if(isset($_POST['update-note-archive'])) {
  $id = $_POST['uid'];
  $title = $_POST['utitle'];
  $note_body = $_POST['unote'];
  $label = $_POST['ulabel'];

  $sql = "UPDATE note SET title='$title', note_body='$note_body', label='$label', archive=true WHERE id='$id'";
  if(!mysqli_query($conn, $sql)) {
    echo "<script>alert('Error: ".mysqli_error($conn)."');</script>";
  }
  //echo mysqli_error($conn);
}

// if(isset($_GET['edit'])) {
//   $id = $_GET['edit'];
//   $title = $_GET['title'];
//   $sql = "UPDATE note SET title=''";
// }

if(isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $sql = "UPDATE note SET bin=true WHERE id=$id";
  if(!mysqli_query($conn, $sql)) {
    echo "<script>alert('Error: ".mysqli_error($conn)."');</script>";
  }
}

if(isset($_GET['bin-delete'])) {
  $id = $_GET['bin-delete'];
  $sql = "DELETE FROM note WHERE id=$id";
  if(!mysqli_query($conn, $sql)) {
    echo "<script>alert('Error: ".mysqli_error($conn)."');</script>";
  }
}

if(isset($_GET['bin-restore'])) {
  $id = $_GET['bin-restore'];
  $sql = "UPDATE note SET bin=false WHERE id=$id";
  if(!mysqli_query($conn, $sql)) {
    echo "<script>alert('Error: ".mysqli_error($conn)."');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Note Keeper</title>

  <!-- fontawesome icons library -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
  <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/simple-sidebar.css" rel="stylesheet">

</head>

<body>
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">New Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" autocomplete="off" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group">
              <!-- <label for="title" class="col-form-label">Title:</label> -->
              <input class="form-control" name="title" id="title" placeholder="Enter title"/>
            </div>
            <div class="form-group">
              <!-- <label for="note" class="col-form-label">Note:</label> -->
              <textarea class="form-control" name="note" id="note" placeholder="Enter note"></textarea>
            </div>
            <div class="form-group">
              <!-- <label for="label" class="col-form-label">Label:</label> -->
              <select name="label" id="label" class="form-control">
                <?php
                $user = $_SESSION['user'];

                $sql = "SELECT * FROM label WHERE user='$user'";
                $result = $conn->query($sql);

                if($result->num_rows > 0) {
                  echo "<option value='select' selected disabled>Select label</option>";
                  while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['text']."'>".$row['text']."</option>";
                  }
                } else {
                  echo "<option value='empty' disabled>Empty</option>";
                  echo "<option value='' selected style='display: none;'></option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!--<button type="submit" class="btn btn-primary">Send</button>-->
            <div class="btn-group">
              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Save
            </button>
            <div class="dropdown-menu">
              <button type="submit" name="save-note" class="dropdown-item">Save</button>
              <div class="dropdown-divider"></div>
                <button type="submit" name="save-note-archive" class="dropdown-item">Save to Archive</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
 
  <!-- edit note modal -->
  <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">New Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" autocomplete="off" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group" style='display: none'>
              <!-- <label for="title" class="col-form-label">Title:</label> -->
              <input class="form-control" name="uid" id="uid" placeholder="Id"/>
            </div>
            <div class="form-group">
              <!-- <label for="title" class="col-form-label">Title:</label> -->
              <input class="form-control" name="utitle" id="utitle" placeholder="Enter title"/>
            </div>
            <div class="form-group">
              <!-- <label for="note" class="col-form-label">Note:</label> -->
              <textarea class="form-control" name="unote" id="unote" placeholder="Enter note"></textarea>
            </div>
            <div class="form-group">
              <!-- <label for="label" class="col-form-label">Label:</label> -->
              <select name="ulabel" id="ulabel" class="form-control">
              <?php
                $user = $_SESSION['user'];

                $sql = "SELECT * FROM label WHERE user='$user'";
                $result = $conn->query($sql);

                if($result->num_rows > 0) {
                  echo "<option value='select' disabled>Select label</option>";
                  while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['text']."'>".$row['text']."</option>";
                  }
                } else {
                  echo "<option value='empty' selected disabled>Empty</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!--<button type="submit" class="btn btn-primary">Send</button>-->
            <div class="btn-group">
              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Save
            </button>
            <div class="dropdown-menu">
              <button type="submit" name="update-note" class="dropdown-item">Save</button>
              <div class="dropdown-divider"></div>
                <button type="submit" name="update-note-archive" class="dropdown-item">Save to Archive</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- add label form -->
  <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">New Label</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" autocomplete="off" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group">
              <input class="form-control" name="addlabel" id="addlabel" placeholder="Enter new label"/>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="save-label">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading"><img src="images/idea.png" alt="logo"> Note Keeper</div>
      <div class="list-group list-group-flush">
        <a href="update_navbar.php?destroy=true" class="list-group-item list-group-item-action"><i class="fa fa-sticky-note" aria-hidden="true"></i> Notes</a>
        <a href="#" class="list-group-item list-group-item-action bg-light" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus" aria-hidden="true"></i> Add Note</a>
        <a href="#" class="list-group-item list-group-item-action bg-light" data-toggle="modal" data-target="#exampleModal2"><i class="fa fa-tags" aria-hidden="true"></i> Add Label</a>
        <a href="update_navbar.php?archive=Archive" class="list-group-item list-group-item-action bg-light"><i class="fa fa-archive" aria-hidden="true"></i> Archive</a>
        <a href="update_navbar.php?bin=Bin" class="list-group-item list-group-item-action bg-light"><i class="fa fa-trash" aria-hidden="true"></i> Bin</a>
      </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-primary" id="menu-toggle">Menu</button>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item active">
              <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Profile 
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item disabled" href="#">Account</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php">Logout</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>

      <div class="container-fluid">
        <h1 class="mt-4">
          <?php
          if(isset($_SESSION['archive'])) {
            echo $_SESSION['archive'];
          } 
          if(isset($_SESSION['bin'])) {
            echo $_SESSION['bin'];
          }
          if(!isset($_SESSION['archive']) and !isset($_SESSION['bin'])) {
            echo "Notes";
          }
          ?>
        </h1>
        <div class="card-columns">
        <?php
          $user = $_SESSION['user'];

          if(isset($_SESSION['archive'])) {
            $sql = "SELECT * FROM note WHERE archive IS TRUE AND bin IS FALSE AND user='$user'";
            $result = $conn->query($sql);
            if($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                $str = "
                <div class='card shadow'>
                  <div class='card-body'>
                    <h5 class='card-title'>".$row['title']."</h5>
                    <p class='card-text'>".$row['note_body']."</p>
                    <p class='card-text'><small class='text-muted'>Created on ".$row['update_date']."</small></p>
                    <a href='home.php?delete=".$row['id']."'><i class='fa fa-trash text-danger' aria-hidden='true'></i></a>
                    <a href='#' class='edittrigger' data-toggle='modal' data-target='#exampleModal3' data-id='".$row['id']."' data-title='".$row['title']."' data-note='".$row['note_body']."' data-label='".$row['label']."' style='margin-left: 10px'><i class='fa fa-pencil-square-o text-primary' aria-hidden='true'></i></a>
                    <br><br><span class='badge badge-pill badge-primary'>".$row['label']."</span>
                  </div>
                </div>";
                echo $str;
              }
            } else {
                echo "<h5 class='text-warning'>Empty</h5>";
            }
          }
          if(isset($_SESSION['bin'])) {
            $sql = "SELECT * FROM note WHERE bin IS TRUE AND user='$user'";
            $result = $conn->query($sql);
            if($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                $str = "
                <div class='card shadow'>
                  <div class='card-body'>
                    <h5 class='card-title'>".$row['title']."</h5>
                    <p class='card-text'>".$row['note_body']."</p>
                    <p class='card-text'><small class='text-muted'>Created on ".$row['update_date']."</small></p>
                    <a href='home.php?bin-delete=".$row['id']."'><i class='fa fa-trash text-danger' aria-hidden='true'></i></a>
                    <a href='home.php?bin-restore=".$row['id']."' style='margin-left: 10px'><i class='fa fa-share text-info' aria-hidden='true'></i></a>
                    <br><br><span class='badge badge-pill badge-primary'>".$row['label']."</span>
                  </div>
                </div>";
                echo $str;
                }
            } else {
              echo "<h5 class='text-warning'>Empty</h5>";
              }
            }
          if(!isset($_SESSION['archive']) and !isset($_SESSION['bin'])) {
            $sql = "SELECT * FROM note WHERE archive IS FALSE AND bin IS FALSE AND user='$user'";
            $result = $conn->query($sql);
            if($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                $str = "
                <div class='card shadow'>
                  <div class='card-body'>
                    <h5 class='card-title'>".$row['title']."</h5>
                    <p class='card-text'>".$row['note_body']."</p>
                    <p class='card-text'><small class='text-muted'>Created on ".$row['update_date']."</small></p>
                    <a href='home.php?delete=".$row['id']."'><i class='fa fa-trash text-danger' aria-hidden='true'></i></a>
                    <a href='#' class='edittrigger' data-toggle='modal' data-target='#exampleModal3' data-id='".$row['id']."' data-title='".$row['title']."' data-note='".$row['note_body']."' data-label='".$row['label']."' style='margin-left: 10px'><i class='fa fa-pencil-square-o text-primary' aria-hidden='true'></i></a>
                    <br><br><span class='badge badge-pill badge-primary'>".$row['label']."</span>
                    </a>
                  </div>
                </div>";
                echo $str;
                }
            } else {
                echo "<h5 class='text-warning'>Empty</h5>";
              }
          }
          ?>
        </div>
      </div>
    </div>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Menu Toggle Script -->
  <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });

    $(document).on("click", ".edittrigger", function () {
     var noteid = $(this).data('id');
     var notetitle = $(this).data('title')
     var notebody = $(this).data('note')
     var notelabel = $(this).data('label')

     $(".modal-body #uid").val( noteid );
     $(".modal-body #utitle").val( notetitle );
     $(".modal-body #unote").val( notebody );
     $(".modal-body #ulabel").val( notelabel );
    });

  </script>

</body>

</html>