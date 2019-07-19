<?php
session_start();
require '../config.php';
if(!isset($_SESSION['id']))
{
    header("Location:pages/samples/login.php?msg=Please login first");
}
// count total patints
$total = "SELECT COUNT(*) AS totalpatient FROM `tbl_patient`";
$totalpatient = mysqli_query($con,$total);
$totaluser=mysqli_fetch_assoc($totalpatient);
// total users 
$users = "SELECT COUNT(*) AS users FROM `tbl_user`";
$siteuser = mysqli_query($con,$users);
$fetchsiteuser=mysqli_fetch_assoc($siteuser);
$countuser = $fetchsiteuser['users'] + $totaluser['totalpatient'];
// total pending appointment 
$pending = "SELECT COUNT(*) AS pendingapp FROM `tbl_appointment` WHERE status='Pending'";
$pendingappointment = mysqli_query($con,$pending);
$pappointment=mysqli_fetch_assoc($pendingappointment);

$msg="";
$followupmsg="";
$deletemsg="";
// cancel Appointment for Patient
if(isset($_POST['cancelappointment']))
{
  $appoointment_id = $_POST['cancelid'];
  $reason = $_POST['reason'];
  $status = "Cancelled";
  $update = "UPDATE `tbl_appointment` SET `reason`='".$reason."', `isDoc_cancelled`=1, `status`='".$status."' WHERE `appointment_id`=$appoointment_id";
  if(mysqli_query($con, $update))
  {
    header("Location: index.php?msg=Appointment cencelled ");
    $successmsg = "Appointment cencelled";
    exit();
  }
  else{
    $eroormsg = "Something is wrong Please try again";
  }
}

// follow up patient appointment 
if(isset($_POST['followupappointment']))
{
  $followupID = $_POST['followup'];
  $status = "Followup";
  $followuped = "UPDATE `tbl_appointment` SET `isDoc_folloup`=1, `status`='".$status."' WHERE `appointment_id`=$followupID";
  if(mysqli_query($con, $followuped))
  {
    header("Location: index.php?followupmsg=Appointment Followuped successfull");
    exit();
  }
  else{
    $eroormsg = "Something is wrong Please try again";
  }
} 

// Delete Appointment
if(isset($_POST['delete']))
{
  $did = $_POST['deleteid'];
  $delete = "DELETE FROM `tbl_appointment` WHERE `appointment_id`= $did";
  if(mysqli_query($con, $delete))
  {
    header("Location: index.php?deletemsg=Appointment Deleted Successfull");
    exit();
  }
  else{
    $eroormsg = "Something wrong Please try again";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Online Consult Doctor- Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />

  <!-- Datatable -->
  <link rel="stylesheet" href="vendors/css/dataTables.bootstrap4.css">

</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="index.php"><img src="images/loginlogo.jpg" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="index.php"><img src="images/logo-mini.svg" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <div class="search-field d-none d-md-block">
          <form class="d-flex align-items-center h-100" action="#">
            <div class="input-group">
              <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>                
              </div>
              <input type="text" class="form-control bg-transparent border-0" placeholder="Search">
            </div>
          </form>
        </div>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <div class="nav-profile-img">
                <img src="images/faces/doctor.jpg" alt="image">
                <span class="availability-status online"></span>             
              </div>
              <div class="nav-profile-text">
                <p class="mb-1 text-black">David Greymaax</p>
              </div>
            </a>
          </li>
          <li class="nav-item d-none d-lg-block full-screen-link">
            <a class="nav-link" title="Fullscreen-view">
              <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
            </a>
          </li>
          <li class="nav-item nav-logout d-none d-lg-block">
            <a class="nav-link" href="pages/samples/logout.php" title="Logout">
              <i class="mdi mdi-power"></i>
            </a>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <span class="menu-title">Dashboard</span>
              <i class="mdi mdi-home menu-icon"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/samples/user.php">
              <span class="menu-title">Users</span>
              <i class="mdi mdi-account-multiple menu-icon"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/samples/patient.php">
              <span class="menu-title">Patients</span>
              <i class="mdi mdi-account-multiple menu-icon"></i>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="pages/samples/appointment.php">
                <span class="menu-title">Appointment</span>
                <i class="mdi mdi-account-plus menu-icon"></i>
              </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/samples/adddisease.php">
              <span class="menu-title">Add Disease </span>
              <i class="mdi mdi-plus menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="pages/samples/addappointmenttime.php">
              <span class="menu-title">Add Apoointment-time </span>
              <i class="mdi mdi-plus menu-icon"></i>
            </a>
        </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
          </div>
          <div class="page-header">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-success text-white mr-2">
                <i class="mdi mdi-home"></i>                 
              </span>
              Dashboard
            </h3>

          </div>
          <div class="row">
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                  <img src="images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                  <h4 class="font-weight-normal mb-3">Total Users
                    <i class="mdi mdi-account-multiple mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5"> <?php echo $countuser; ?> </h2>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                  <img src="images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>                  
                  <h4 class="font-weight-normal mb-3">Pending Appointments
                    <i class="mdi mdi-account-multiple-outline mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5"> <?php echo $pappointment['pendingapp']; ?></h2>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>                                    
                  <h4 class="font-weight-normal mb-3">Total Patients
                    <i class="mdi mdi-application mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5"> <?php echo $totaluser['totalpatient']; ?> </h2>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h2 class="text-center">Today Appointment</h2><hr>
                  <?php
                  if(isset($_GET['msg']))
                  {
                    $msg= $_GET['msg'];
                    echo "<div class='alert alert-success text-center' role='alert'>$msg</div>";
                  }
                  if(isset($_GET['followupmsg']))
                  {
                    $followupmsg= $_GET['followupmsg'];
                    echo "<div class='alert alert-success text-center' role='alert'>$followupmsg</div>";
                  }
                  if(isset($_GET['deletemsg']))
                  {
                    $deletemsg= $_GET['deletemsg'];
                    echo "<div class='alert alert-success text-center' role='alert'>$deletemsg</div>";
                  }
                  ?>
                  <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                 <th>Appo. time</th>
                                <th>Patient Name</th>
                                <th>Mobile no</th>
                                <th>Age</th>
                                <th>Disease</th>
                                <th>Prescription</th>
                                <th>Action</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php
                            $adminid = $_SESSION['id'];
                            $today = date("Y-m-d");
                            $sql = "CALL `sp_Admindashboard`('".strval($today)."',
                            '".strval($today)."','','')";
                            $res = mysqli_query($con,$sql);
                            $no = 1;
                            while ($row=mysqli_fetch_assoc($res)):      
                          ?>
                            <tr>
                                <td><?php echo $no; ?>
                                <!-- Modal Prescription -->
                                <div class="modal fade" id="Prescription-<?php echo $row['appointment_id']; ?>" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                    
                                      <!-- Modal content-->
                                      <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Prescription</h4>
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                          <div class="row">
                                              
                                                  <div class="col-lg-6">
                                                      <div class="card" id="prescriotioncard">
                                                        <div class="table-responsive">
                                                        <table class="table" id="patientdetails">
                                                          <tbody>
                                                            <tr>
                                                              <th scope="row">Name</th>
                                                              <th>:-</th>
                                                              <td><?php echo $row['firstname']." ".$row['lastname']; ?></td>
                                                            </tr>
                                                            <tr>
                                                            <?php
                                                              $bday = new DateTime($row['dob']); 
                                                              $today = new Datetime(date('y-m-d'));
                                                              $diff = $today->diff($bday);
                                                            ?>
                                                                <th scope="row">Age</th>
                                                                <th>:-</th>
                                                                <td><?php printf(' %d years', $diff->y);  ?></td>
                                                              </tr>
                                                            <tr>
                                                              <th scope="row">Mobile No</th>
                                                              <th>:-</th>
                                                              <td><?php echo $row['mobileno']; ?></td>
                                                            </tr>
                                                            <tr>
                                                              <th scope="row">Email</th>
                                                              <th>:-</th>
                                                              <td><?php echo $row['email']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Disease</th>
                                                                <th>:-</th>
                                                                <td><?php echo $row['disease']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Appointment Date</th>
                                                                <th>:-</th>
                                                                <?php 
                                                                    $adate = strtotime($row['appointment_date']);
                                                                    $appointmentdate = date("d-m-Y", $adate);
                                                                ?>
                                                                <td><?php echo $appointmentdate; ?></td>
                                                            </tr>
                                                            <?php 
                                                                // 24-hour time to 12-hour time 
                                                                $starttime  = date("g:i A", strtotime($row['appointment_starttime']));
                                                                $endtime  = date("g:i A", strtotime($row['appointment_endtime']));
                                                            ?>
                                                            <tr>
                                                                <th scope="row">Appointment Time</th>
                                                                <th>:-</th>
                                                                <td><?php echo $starttime." to ".$endtime; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Status</th>
                                                                <th>:-</th>
                                                                <td><?php echo $row['status']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Description</th>
                                                                <th>:-</th>
                                                                <td><?php echo $row['description']; ?></td>
                                                            </tr>
                                                          </tbody>
                                                        </table>
                                                      </div>
                                                  </div>
                                                  <br>
                                                  <div class="card" id="symptomscard">
                                                  <div class="alert alert-success alert-dismissible text-center" id="success-<?php echo $row['appointment_id']?>" style="display:none;">
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                                  </div>
                                                    <div class="prescription">
                                                      <div class="row"> 
                                                       
                                                      <div class="col-md-3 col-sm-3"> 
                                                          <label for="symptoms">Symptoms:-</label>
                                                      </div>
                                                      <div class="col-md-9 col-sm-9"> 
                                                        <textarea class="form-control" rows="5" id="symptoms-<?php echo $row['appointment_id']?>" placeholder="Type Here..."></textarea>
                                                      <br>
                                                      <button type="button" class="btn btn-success btn-sm" id="addsymptoms" onclick="Savesymptoms(<?php echo $row['appointment_id']; ?>);"> <i class="mdi mdi-note-plus btn-icon-prepend"></i>                                                    
                                                            Add</button>
                                                      </div>
                                                    
                                                    </div>  
                                                    <br>
                                                                        
                                                  </div>
                            
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="card" id="prescriotioncard">
                                                  <div class="alert alert-success alert-dismissible text-center" id="successPrescription-<?php echo $row['appointment_id']?>" style="display:none;">
                                                      <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                                  </div>
                                                    <div class="prescription">
                                                    <div class="row"> 
                                                        <div class="col-md-3 col-sm-3"> 
                                                            <label for="symptoms">Prescription:-</label>
                                                        </div>
                                                        <div class="col-md-9 col-sm-9"> 
                                                          <textarea class="form-control" rows="5" id="prescription-<?php echo $row['appointment_id']?>" placeholder="Type Prescription Here..."></textarea>
                                                          <br>
                                                          <button type="button" class="btn btn-success btn-sm" id="addpresction" onclick="Saveprescription(<?php echo $row['appointment_id']; ?>);"> <i class="mdi mdi-note-plus btn-icon-prepend"></i>                                                    
                                                            Add</button>
                                                        </div>
                                                    </div>                        
                                                    </div>
                            
                                                </div>
                                                <br>
                                                <div class="card" id="previous-pre">
                                                      <h5 class="card-header">Previous Prescription</h5>
                                                      <div class="card-body" style="padding:0px !important">
                                                          <div class="table-responsive">          
                                                              <table class="table table-bordered">
                                                                <thead>
                                                                  <tr>
                                                                    <th>#</th>
                                                                    <th>Date</th>
                                                                    <th>Prescription</th>
                                                                    <th>Action</th>
                                                                  </tr>
                                                                </thead>
                                                                <tbody id="preRecord-<?php echo $row['appointment_id']?>">
                                                                  <?php
                                                                    mysqli_close($con);
                                                                    require '../config.php';
                                                                    $fetch = "SELECT *, DATE(createdate) FROM `tbl_prescription` WHERE `appointment_id`=".$row['appointment_id'];
                                                                    $mypres = mysqli_query($con,$fetch);
                                                                    $number = 1;
                                                                    while ($predata=mysqli_fetch_assoc($mypres)):
                                                                  ?>
                                                                  
                                                                  <tr id="<?php echo $predata['id']; ?>">
                                                                    <td><?php echo $number; ?></td>
                                                                    <?php 
                                                                    $prescriptiondt = strtotime($predata['DATE(createdate)']);
                                                                      $prescriptionadddate = date("d-m-Y", $prescriptiondt);
                                                                    ?>
                                                                    <td><?php echo $prescriptionadddate; ?></td>
                                                                    <td> <span class="editSpan prescription"><?php echo $predata['prescription']; ?></span>
                            <input class="editInput prescriptionchange form-control input-sm" type="text" id="prescriptionchange" name="prescriptionchange" value="<?php echo $predata['prescription']; ?>" style="display: none;"></td>
                                                                    <td><button type="button" class="btn btn-sm btn-default editBtn" style="float: none;"><span class="mdi mdi-pencil"></span></button>
                                                                      <button type="button" class="btn btn-sm btn-success saveBtn" style="float: none; display: none;">Save</button>
                                                                      
                                                                      <button type="button" class="btn btn-sm btn-default deleteBtn" style="float: none;"><span class="mdi mdi-delete"></span></button>
                                                                      <button type="button" class="btn btn-sm btn-danger confirmBtn" style="float: none; display: none;">Confirm</button>
                                                                      </td>
                              
                                                                  </tr>
                                                                  <?php $number++; endwhile; ?>
                                                                  <input type="hidden" name="srno-<?php echo $row['appointment_id']?>" id="srno-<?php echo $row['appointment_id']?>" value="<?php echo $number; ?>">
                                                                </tbody>
                                                              </table>
                                                              </div>
                                                      </div>
                                                  </div>
                                              </div>

                                          </div>
                                        </div>
                                      </div>
                                      
                                    </div>
                                </div>
                                <?php 
                                    // 24-hour time to 12-hour time 
                                    $starttime  = date("g:i A", strtotime($row['appointment_starttime']));
                                    $endtime  = date("g:i A", strtotime($row['appointment_endtime']));
                                ?>  
                                <td><?php echo $starttime." to ".$endtime; ?></td>
                                <td><?php echo $row['firstname']." ".$row['lastname']; ?></td>
                                <td><?php echo $row['mobileno']; ?></td>
                                <td><?php printf(' %d years', $diff->y);  ?></td>
                                <td><?php echo $row['disease']; ?></td>
                                <td><button type="button" class="btn btn-desc-icon btn-gradient-success btn-rounded" data-toggle="modal" data-target="#Prescription-<?php echo $row['appointment_id']; ?>"
                                  data-toggle="tooltip" class="tip-bottom" title="Add Prescription"><i class="mdi mdi-plus"></i></button></td>
                                <td class="test"> <button type="button" class="btn btn-gradient-info btn-sm" data-toggle="modal" data-target="#FollowupModal" class="tip-bottom" title="follow up" onclick="FollowupAppointment(<?php echo $row['appointment_id']; ?>)">Follow Up</button>
                                  <?php if($row['status'] != "Cancelled") : ?> <a href="#Modalcancelapp" onclick="CancelRow(<?php echo $row['appointment_id']; ?>)"  class="btn btn-gradient-success btn-sm" data-toggle="modal" data-toggle="tooltip" class="tip-bottom" title="Cancel Appointment" >Cancel</a> <?php endif; ?></td>
                                <td><a href="#myModaldelete" onclick="DeleteRow(<?php echo $row['appointment_id']; ?>)"  class="trigger-btn" data-toggle="modal" id="delete-Time" data-toggle="tooltip" class="tip-bottom" title="Delete Appointment" style="font-size: 20px;"><i class="mdi mdi-delete"></i></a></td>  
                            </tr>
                            <?php $no++; endwhile; ?>
                        </tbody> 
                    </table>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    
       <!-- Followup model -->
       <div id="FollowupModal" class="modal fade">
          <div class="modal-dialog modal-confirm">
            <div class="modal-content">
              <div class="modal-header">
                <div class="icon-box">
                  
                </div>				
                <h4 class="modal-title"><i class="mdi mdi-alert"></i>Are you sure?</h4>	
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
              <form method="post" action="#"> 
                 <input type="hidden" name="followup" id="followup"> 
                <p>Do you really want to Follow up patient appointment? </p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                <input class="btn btn-danger" type="submit" name="followupappointment" value="Follow-Up">
                </form>
              </div>
            </div>
          </div>
      </div>  

      <!-- Modal Cancel Appointment HTML -->
      <div id="Modalcancelapp" class="modal fade">
          <div class="modal-dialog modal-confirm">
            <div class="modal-content">
              <div class="modal-header">
                <div class="icon-box">
                  
                </div>				
                <h4 class="modal-title">Cancel Appointment </h4>	
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <form method="post" action="#"> 
              <div class="modal-body">
                  <div class="card" id="cancelappcard">
                      <div class="card-body">
                      
                      <input type="hidden" name="cancelid" id="cancelid">
                        <div class="row"> 
                        <div class="col-md-3 col-sm-3"> 
                            <label for="reason">Reason:-</label>
                        </div>
                        <div class="col-md-9 col-sm-9"> 
                          <textarea class="form-control" rows="5" id="reason" name="reason" placeholder="Enter Reason Cancel Appointment..."></textarea>
                        </div>
                            
                      </div>                          
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Back</button>
                <input class="btn btn-danger btn-sm" type="submit" name="cancelappointment" value="Cancel Appointment">
              </div>
              </form>  
            </div>
          </div>
      </div>   

      <!-- Modal delete appointment HTML -->
      <div id="myModaldelete" class="modal fade">
          <div class="modal-dialog modal-confirm">
            <div class="modal-content">
              <div class="modal-header">
                <div class="icon-box">
                  
                </div>				
                <h4 class="modal-title"><i class="mdi mdi-alert"></i>Are you sure?</h4>	
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
                <form method="post" action="#"> 
                 <input type="hidden" name="deleteid" id="deleteid"> 
                <p>Do you really want to delete these Appointment ?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                <input class="btn btn-danger" type="submit" name="delete" value="Delete">
                </form>
              </div>
            </div>
          </div>
        </div>                                                                       


        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2019 
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/js/vendor.bundle.addons.js"></script>
  <!-- endinject -->

  <!-- datatable -->
  <script src="vendors/js/datatables.min.js"></script>


  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <!-- End custom js for this page-->
  <script>
  
  $('#zero_config').DataTable();
   
  function CancelRow(Id)
  {
    $('#cancelid').val(Id);
  }

  function FollowupAppointment(ID)
  {
    $('#followup').val(ID);
  }

  function DeleteRow(Id)
  {
    $('#deleteid').val(Id);
  } 

 function Savesymptoms(id)
 {
    
      var symptoms = $('#symptoms-'+id).val();
      $(".error").remove();
      $error = 0;
      if (symptoms == '') {
            $('#symptoms-'+id).after('<span class="error">This field is required</span>');
            $error = 1;    
      }
     
      if($error == 0)
      {
            $.ajax({
            url: "pages/samples/insertsymptoms.php",
            type: "POST",
            data: {
              appointment_id : id,
              symptoms: symptoms
            },
            cache: false,
            success: function(dataResult){
              console.log(dataResult);
              var dataResult = JSON.parse(dataResult);
              if(dataResult.statusCode==200){
                $('#symptoms-'+id).val("");
                $('#success-'+id).show();
                $('#success-'+id).html('symptoms add successfully !'); 						
              }
              else if(dataResult.statusCode==201){
                alert("Error occured !");
              }						
            }
            
          });
      }
 }   
  $('.editBtn').on('click',function(){
        //hide edit span
        $(this).closest("tr").find(".editSpan").hide();
       
        //show edit input
        $(this).closest("tr").find(".editInput").show();
        
        //hide edit button
        $(this).closest("tr").find(".editBtn").hide();
        
        //show edit button
        $(this).closest("tr").find(".saveBtn").show();
        
    });

  $('.deleteBtn').on('click',function(){
      //hide delete button
      $(this).closest("tr").find(".deleteBtn").hide();
      
      //show confirm button
      $(this).closest("tr").find(".confirmBtn").show();
      
  }); 

// insert Prescription
 function Saveprescription(id)
 {
    
      var prescription = $('#prescription-'+id).val();
      $(".error").remove();
      $error = 0;
      if (prescription == '') {
            $('#prescription-'+id).after('<span class="error">This field is required</span>');
            $error = 1;    
      }
     
      if($error == 0)
      {
            $.ajax({
            url: "pages/samples/insertprescription.php",
            type: "POST",
            data: {
              appointment_id : id,
              prescription: prescription
            },
            cache: false,
            success: function(data){
              var dataResult = JSON.parse(data);
              var string = "";
              if(dataResult.status == 'ok'){  

                var srno = $('#srno-'+id).val();
                var today = new Date(dataResult.result["predate"]);
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0');
                var yyyy = today.getFullYear();
                
                today = dd+'-'+mm+'-'+yyyy;
                   
                //string += "<tr><td>"+link+"</td> <td>"+today+"</td> <td> " + dataResult.result["prescription"] +'</td><td> '+ '<a href="#myModaldelete" onclick="DeleteRow('+dataResult.result["id"]+')" class="trigger-btn" data-toggle="modal" id="delete-Prescription" data-toggle="tooltip" class="tip-bottom" title="Delete Prescription"><i class="mdi mdi-delete " style="font-size: 25px"></i></a>' +"</td></tr>";
                string += "<tr id='rowid-"+dataResult.result['id']+"'><td>"+srno+"</td>"+ 
                "<td>"+today+"</td>"+
                "<td><span class='editSpan prescription' id='editSpandata-"+dataResult.result['id']+"'>"+ dataResult.result["prescription"]+"</span><input class='editInput prescriptionchange form-control input-sm' type='text' id='prescriptioneditchange-"+dataResult.result['id']+"' name='prescriptioneditchange-"+dataResult.result['id']+"' value='"+dataResult.result["prescription"]+"' style='display: none;'></td> "+
                "<td><button type='button' class='btn btn-sm btn-default editBtn' id='editBtn-"+dataResult.result['id']+"' onclick='FunEditPrescription("+dataResult.result['id']+")' style='float: none;'><span class='mdi mdi-pencil'></span></button>"+
                "<button type='button' class='btn btn-sm btn-success saveBtn' id='PresaveBtn-"+dataResult.result['id']+"' onclick='UpdatePresaveBtn("+dataResult.result['id']+")' style='float: none; display: none;'>Save</button>"+
                "<button type='button' class='btn btn-sm btn-default deleteBtn' id='deleteButton-"+dataResult.result['id']+"' onclick='FunDeletePrescription("+dataResult.result['id']+")' style='float: none;'><span class='mdi mdi-delete'></span></button>"+
                "<button type='button' class='btn btn-sm btn-danger confirmBtn' id='confirmdeleteButton-"+dataResult.result['id']+"' onclick='confirmDelPrescription("+dataResult.result['id']+")' style='float: none; display: none;'>Confirm</button>"+
                "</td></tr>";
                
                $('#prescription-'+id).val("");
                $('#successPrescription-'+id).show();
                $('#successPrescription-'+id).html('Prescription add successfully !'); 	

              }
              $('#preRecord-'+id).append(string);
              
            }
            
          });
      }
 }  

 function FunEditPrescription(Id)
  {
    //hide edit span
    $("#editSpandata-"+Id).hide();  
    var data = $("#editSpandata-"+Id).text();
        
    //show edit input
    $("#prescriptioneditchange-"+Id).show();

    $("#prescriptioneditchange-"+Id).val(data);

    //hide edit button
    $("#editBtn-"+Id).hide();
       
    //show edit button
    $("#PresaveBtn-"+Id).show();
  }

  function FunDeletePrescription(Id)
  {
    //hide delete button
    $("#deleteButton-"+Id).hide();
  
    //show confirm button
    $("#confirmdeleteButton-"+Id).show();
  }

</script>

  
  <script>
    // Edit Prescription & Update

    $('.saveBtn').on('click',function(){
        var trObj = $(this).closest("tr");
        var ID = $(this).closest("tr").attr('id');
        var inputData = $(this).closest("tr").find('#prescriptionchange').val();
        
         $.ajax({
            type:'POST',
            url:'pages/samples/UpdatePrescription.php',
            cache: false,
            data:'id='+ID+'&prescriptionDetails='+inputData,
            success:function(response){
              var dataResult = JSON.parse(response);

                if(dataResult.status == 'ok'){
                    trObj.find(".editSpan.prescription").text(dataResult.result["prescription"]);
                    
                    trObj.find(".editInput.prescriptionchange").text(dataResult.result["prescription"]);
                                      
                    trObj.find(".editInput").hide();
                    trObj.find(".saveBtn").hide();
                    trObj.find(".editSpan").show();
                    trObj.find(".editBtn").show();
                }else{
                    alert(response.msg);
                }
            }
        });

    });

    function UpdatePresaveBtn(id)
    {
      var inputData = $('#prescriptioneditchange-'+id).val();
      var ID = id;

      $.ajax({
            type:'POST',
            url:'pages/samples/UpdatePrescription.php',
            cache: false,
            data:'id='+ID+'&prescriptionDetails='+inputData,
            success:function(response){
              var dataResult = JSON.parse(response);
                if(dataResult.status == 'ok'){
                    $("#editSpandata-"+id).text(dataResult.result["prescription"]);
                    
                    $("#prescriptioneditchange-"+id).text(dataResult.result["prescription"]);
                                      
                    $("#prescriptioneditchange-"+id).hide();
                    $("#PresaveBtn-"+id).hide();
                    $("#editSpandata-"+id).show();
                    $("#editBtn-"+id).show();
                }else{
                    alert(response.msg);
                }
            }
        });

    }

    // Delete prescription in popup box
    $('.confirmBtn').on('click',function(){
        var trObj = $(this).closest("tr");
        var ID = $(this).closest("tr").attr('id');
        
        $.ajax({
            type:'POST',
            url:'pages/samples/PrescriptiondeleteAction.php',
            dataType: "json",
            data:'action=delete&id='+ID,
            success:function(response){
                if(response.status == 'ok'){
                    trObj.remove();
                }else{
                    trObj.find(".confirmBtn").hide();
                    trObj.find(".deleteBtn").show();
                    alert(response.msg);
                }
            }
        });
    });
    

    function confirmDelPrescription(id)
    {
      var ID = id;
      $.ajax({
            type:'POST',
            url:'pages/samples/PrescriptiondeleteAction.php',
            dataType: "json",
            data:'action=delete&id='+ID,
            success:function(response){
              console.log(response);
                if(response.status == 'ok'){
                  $('#rowid-'+id).remove();
                }else{
                    alert(response.msg);
                }
            }
        });
    }

</script>
 
</body>

</html>