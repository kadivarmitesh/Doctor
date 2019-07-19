<?php
session_start();
require '../../../config.php';
if(!isset($_SESSION['id']))
{
    header("Location: login.php?msg=Please login first");
}

$msg="";
// Delete Patient
if(isset($_POST['deletepatient']))
{
  $did = $_POST['patientid'];
  $delete = "DELETE FROM `patinet_tbl` WHERE `id`= $did";
  if(mysqli_query($con, $delete))
  {
    header("Location: user.php?msg=Patient Deleted Successfull");
    exit();
  }
  else{
    $eroormsg = "Something wrong Please try again";
  }

}


// Delete User
if(isset($_POST['deleteUser']))
{
  $userid = $_POST['UserID'];
  $deleteuser = "DELETE FROM `tbl_user` WHERE `id`= $userid";
  if(mysqli_query($con, $deleteuser))
  {
    header("Location: user.php?msg=User Deleted Successfull");
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
            <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12">
                      <h2 class="text-center">User Details</h2>
                    </div>
                  </div>
                  <hr>
                  <?php
                  if(isset($_GET['msg']))
                  {
                    $msg= $_GET['msg'];
                    echo "<div class='alert alert-success text-center' role='alert'>$msg</div>";
                  }
                  ?>
                  
                    <div class="table-responsive">
                        <table id="example" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile No</th>
                                    <th>Status</th>
                                    <th>Change Status</th>
                                    <th>Action</th>
                                    <th>View Patients</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                                $sql = "CALL `sp_FetchUser`()";
                                $res = mysqli_query($con,$sql);
                                $no = 1;
                                while ($row=mysqli_fetch_assoc($res)): 
                              ?>
                              <tr id="<?php echo $row['id']; ?>">
                                  <td><?php echo $no; ?></td>
                                  <td> <span class="editSpan username"><?php echo $row['username']; ?></span>
                                      <input class="editInput username form-control input-sm" type="text" name="username" value="<?php echo $row['username']; ?>" style="display: none;">
                                  </td>
                                  <td><span class="editSpan email"><?php echo $row['email']; ?></span>
                                      <input class="editInput email form-control input-sm" type="text" name="email" value="<?php echo $row['email']; ?>" style="display: none;">
                                  </td>
                                  <td><span class="editSpan mobileno"><?php echo $row['mobileno']; ?></span>
                                      <input class="editInput mobileno form-control input-sm" type="text" name="mobileno" value="<?php echo $row['mobileno']; ?>" style="display: none;">
                                  </td>
                                  <td><?php if($row['status']==1) { echo "<span class='badge badge-gradient-success'>Active</span>"; }else { echo "<span class='badge badge-gradient-danger'>Deactive</span>"; } ?></td>
                                  <td>
                                  <?php 
                                    $user_id = $row['id'];
                                    if ($row['status'] == 1) {
                                            echo "<a href='user_status_change.php?id=$user_id'>Deactive</a>";
                                        }
                                        else{
                                            echo "<a href='user_status_change.php?id=$user_id'>Active</a>";
                                        }
                                  ?>
                                </td>
                                <td><button type="button" class="btn btn-sm btn-default editBtn" style="float: none;"><span class="mdi mdi-lead-pencil"></span></button> <button type="button" class="btn btn-sm btn-success saveBtn" style="float: none; display: none;">Save</button><a href="#myModaldelete" onclick="DeleteUserRow(<?php echo $row['id']; ?>)"  class="trigger-btn" data-toggle="modal" id="delete-User" data-toggle="tooltip" class="tip-bottom" title="Delete User" style="font-size: 20px;"><i class="mdi mdi-delete "></td></td>
                                <td><a href='patient.php?User_ID=<?php echo $user_id; ?>' class='btn btn-gradient-info btn-sm' data-toggle='tooltip' class='tip-bottom' title='View Patinets' >View Patinets</a></td>
                              </tr>
                              <?php $no++; endwhile; ?>
                            </tbody>
                        </table>
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
                 <input type="hidden" name="UserID" id="UserID"> 
                <p>Do you really want to delete these User ? </p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                <input class="btn btn-danger" type="submit" name="deleteUser" value="Delete">
                </form>
              </div>
            </div>
          </div>
      </div> 

      <!-- Modal Delete Patient  -->
      <div id="myModaldeletepatient" class="modal fade">
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
                 <input type="hidden" name="patientid" id="patientid"> 
                <p>Do you really want to delete these records ? </p>
              </div>
              <div class="modal-footer">
              <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                <input class="btn btn-danger" type="submit" name="deletepatient" value="Delete">
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
  <script>
     
    $('#example').DataTable();
      	

function DeleteRow(Id)
{
  $('#patientid').val(Id);
} 
function DeleteUserRow(id)
{
  $('#UserID').val(id);
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

$('.saveBtn').on('click',function(){
    var trObj = $(this).closest("tr");
    var ID = $(this).closest("tr").attr('id');
    var inputData = $(this).closest("tr").find(".editInput").serialize();
    $.ajax({
        type:'POST',
        url:'userUpdateAction.php',
        dataType: "json",
        data:'action=edit&id='+ID+'&'+inputData,
        success:function(response){
            if(response.status == 'ok'){
                trObj.find(".editSpan.username").text(response.result["username"]);
                trObj.find(".editSpan.email").text(response.result["email"]);
                trObj.find(".editSpan.mobileno").text(response.result["mobileno"]);
                
                trObj.find(".editInput.username").text(response.result["username"]);
                trObj.find(".editInput.email").text(response.result["email"]);
                trObj.find(".editInput.mobileno").text(response.result["mobileno"]);
                
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

</script>

</body>

</html>
