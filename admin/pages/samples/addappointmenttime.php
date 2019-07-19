<?php
session_start();
require '../../../config.php';
if(!isset($_SESSION['id']))
{
    header("Location: login.php?msg=Please login first");
}
$successmsg = "";
$msg="";
$deletemsg="";
$updatemsg="";
$eroormsg = "";
if(isset($_POST['submit']))
{
  $starttime = $_POST['timepicker1'];
  $endtime = $_POST['timepicker2'];
  $createddate = date('y-m-d h:i:s');
  $status = 1 ;
  $sql= "INSERT INTO `tbl_appointmenttime`( `start-time`, `end-time`, `status`, `createddate`) VALUES ('".$starttime."','".$endtime."',$status,'".$createddate."')";

  if(mysqli_query($con, $sql))
  {
    header("Location: addappointmenttime.php?msg=Appointment Time Add Successfull");
    $successmsg = "Appointment Time Add Successfull";
  }
  else{
    $eroormsg = "Something wrong Please try again";
  }

}


// edit Time
if(isset($_GET['id']))
{

  $id =$_GET['id'];
  $qry = "SELECT * FROM `tbl_appointmenttime` WHERE `id`=$id";

  $res = mysqli_query($con,$qry); 
  $row = mysqli_fetch_assoc($res);
  
}
//update Disease
if(isset($_POST['update']))
{
  if(isset($_GET['id']))
  {
    $timeid = $_GET['id'];
  }
  $starttime = $_POST['timepicker1'];
  $endtime = $_POST['timepicker2'];
  $updatedate = date('y-m-d h:i:s');

  $update = "UPDATE `tbl_appointmenttime` SET `start-time`='".$starttime."', `end-time`='".$endtime."', `updatedate`='".$updatedate."' WHERE `id`=$timeid";
  if(mysqli_query($con, $update))
  {
    header("Location: addappointmenttime.php?updatemsg=Appointment time Updated Successfull");
    $successmsg = "Appointment time Updated Successfull";
    exit();
  }
  else{
    $eroormsg = "Something wrong Please try again";
  }

}


// Delete Time
if(isset($_POST['delete']))
{
  $did = $_POST['deleteid'];
  $delete = "DELETE FROM `tbl_appointmenttime` WHERE `id`= $did";
  if(mysqli_query($con, $delete))
  {
    header("Location: addappointmenttime.php?deletemsg=Appointment Time Deleted Successfull");
    $successmsg = "Appointment Time Deleted Successfull";
    exit();
  }
  else{
    $eroormsg = "Something wrong Please try again";
  }

}

?>

  <?php include_once('header.php');  ?>

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
                <div class="col-12 grid-margin">
                        <div class="card">
                          <div class="card-body">
                            <h2 class="text-center">Add Appointment Time</h2><hr>
                            <form method="post" action="#" class="form-sample">
                            <?php 
                            if(isset($_GET['msg']))
                            {
                              $msg= $_GET['msg'];
                              echo "<div class='alert alert-success text-center' role='alert'>$msg</div>";
                            }
                            if(isset($_GET['deletemsg']))
                            {
                              $deletemsg= $_GET['deletemsg'];
                              echo "<div class='alert alert-success text-center' role='alert'>$deletemsg</div>";
                            }
                            if(isset($_GET['updatemsg']))
                            {
                              $updatemsg= $_GET['updatemsg'];
                              echo "<div class='alert alert-success text-center' role='alert'>$updatemsg</div>";
                            }
					                  ?>
                            <div class="row">	
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Start time</label>
                                    <div class="col-sm-9">
                                        <input id="timepicker1" name="timepicker1" type="text" onKeyPress="return IsAlphaNumeric(event);"  placeholder="h:m AM/PM" class="form-control input-small" required="required" value="<?php if(isset($_GET['id'])){ echo $row['start-time']; }  ?>" ondrop="return false;" onpaste="return false;">
                                    </div>
                                  </div>
                                </div>
                               
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">End time</label>
                                    <div class="col-sm-9">
                                        <input id="timepicker2" name="timepicker2" type="text" onKeyPress="return IsAlphaNumeric(event);"  placeholder="h:m AM/PM" class="form-control input-small" required="required" value="<?php if(isset($_GET['id'])){ echo $row['end-time']; }  ?>" ondrop="return false;" onpaste="return false;">
                                    </div>
                                    </div>
                                </div>
                            </div>
                                <?php
                                  if(isset($_GET['id']))
                                  {
                                    echo '<input class="btn btn-gradient-success mb-2 float-right" type="submit" name="update" value="Update">';
                                  }
                                  else
                                  {
                                    echo '<input class="btn btn-gradient-success mb-2 float-right" type="submit" name="submit" value="Add">';
                                  }
                                ?>
                                
                            </form>
                          </div>
                        </div>
                </div>
                <div class="row">
                <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                    <h2 class="text-center">Display Appointment Time</h2><hr>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Start time</th>
                                <th>End time</th>
                                <th>Status</th>
                                <th>Change Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM `tbl_appointmenttime` WHERE status in(1,2) ORDER BY `createddate` DESC";                                
                            $res = mysqli_query($con,$sql);
                            $number = 1;
                            while ($row=mysqli_fetch_assoc($res)):      
                            ?>
                            <tr>
                                <td><?php echo $number; ?></td>
                                <td><?php echo $row['start-time']; ?></td>
                                <td><?php echo $row['end-time']; ?></td>
                                <td><?php if($row['status']==1){ echo "<span class='badge badge-gradient-success'>Active</span>"; } else { echo "<span class='badge badge-gradient-danger'>Deactive</span>"; } ?></td>
                                <td>
                                <?php 
                                    $time_id = $row['id'];
                                    if ($row['status'] == 1) {
                                            echo "<a href='time_change.php?id=$time_id'>Deactive</a>";
                                        }
                                        else{
                                            echo "<a href='time_change.php?id=$time_id'>Active</a>";
                                        }
                                ?>
                                </td>
                                <td><a href="addappointmenttime.php?id=<?php echo $row["id"]; ?>" class="trigger-btn" id="edit"
                                                    data-toggle="tooltip" class="tip-bottom" title="Edit Time" style="font-size: 20px;">
                                                    <i class="mdi mdi-lead-pencil "></i>
                                                  </a>
                                <a href="#myModaldelete" onclick="DeleteRow(<?php echo $row['id']; ?>)"  class="trigger-btn" data-toggle="modal" id="delete-Time" data-toggle="tooltip" class="tip-bottom" title="Delete Time" style="font-size: 20px;"><i class="mdi mdi-delete "></td>
                            </tr>
                            <?php $number++; endwhile; ?>
                        </tbody>
                    </table>
                </div>
                </div>
              </div>
            </div>
          </div>                                  
        </div>
        
         <!-- Modal HTML -->
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
                <p>Do you really want to delete these appointment time ?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                <input class="btn btn-danger" type="submit" name="delete" value="Delete">
                </form>
              </div>
            </div>
          </div>
        </div>    

  <?php include_once('footer.php'); ?>                              

  <!-- datatable -->
  <script src="../../vendors/js/datatables.min.js"></script>

  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/misc.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <!-- End custom js for this page-->

  <script type="text/javascript" src="../../TimePicker/js/bootstrap-timepicker.min.js"></script>

<script type="text/javascript">
 $('#zero_config').DataTable();

    function IsAlphaNumeric(evt)
    {
        var keyCode = (evt.which) ? evt.which : evt.keyCode
        if ((keyCode > 32 && keyCode < 48) || (keyCode > 57 && keyCode < 65) ||  (keyCode > 90 && keyCode < 97) || (keyCode > 122 && keyCode < 127))
          return false;
          return true;
    }

</script>  
    <script type="text/javascript">
            $('#timepicker1').timepicker();
            $('#timepicker2').timepicker();
                                
        function DeleteRow(Id)
        {
            $('#deleteid').val(Id);
        }                                

    </script>

</body>

</html>
