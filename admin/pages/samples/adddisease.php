<?php
session_start();
require '../../../config.php';
if(!isset($_SESSION['id']))
{
    header("Location: login.php?msg=Please login first");
}
$successmsg = "";
$msg="";
$updatemsg="";
$deletemsg="";
$eroormsg = "";
if(isset($_POST['submit']))
{
  $disease = $_POST['disease'];
  $createddate = date('y-m-d h:i:s');
 
  $sql= "INSERT INTO `tbl_disease`( `disease`, `createddate`) VALUES ('".$disease."','".$createddate."')";

  if(mysqli_query($con, $sql))
  {
    header("Location: adddisease.php?msg=Disease Add Successfull");
    exit();
  }
  else{
    $eroormsg = "Something wrong Please try again";
  }
  
}

// edit Disease
if(isset($_GET['id']))
{

  $id =$_GET['id'];
  $qry = "SELECT * FROM `tbl_disease` WHERE `id`=$id";

  $res = mysqli_query($con,$qry); 
  $row = mysqli_fetch_assoc($res);
  
}
//update Disease
if(isset($_POST['update']))
{
  if(isset($_GET['id']))
  {
    $diseaseid = $_GET['id'];
  }
  $disease = $_POST['disease'];
  $updatedate = date('y-m-d h:i:s');

  $update = "UPDATE `tbl_disease` SET `disease`='".$disease."',`updatedate`='".$updatedate."' WHERE `id`=$diseaseid";
  if(mysqli_query($con, $update))
  {
    header("Location: adddisease.php?updatemsg=Disease Updated Successfull");
    $successmsg = "Disease Updated Successfull";
    exit();
  }
  else{
    $eroormsg = "Something wrong Please try again";
  }

}

// Delete Disease
if(isset($_POST['delete']))
{
  $did = $_POST['deleteid'];
  $delete = "DELETE FROM `tbl_disease` WHERE `id`= $did";
  if(mysqli_query($con, $delete))
  {
    header("Location: adddisease.php?deletemsg=Disease Deleted Successfull");
    $successmsg = "Disease Deleted Successfull";
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
                            <h2 class="text-center">Add Disease</h2><hr>
                            <form method="post" action="#" class="form-sample">
                              <div class="row">	
                                <div class="col-md-12">
                                <?php
                                if(isset($_GET['msg']))
                                {
                                  $msg= $_GET['msg'];
                                  echo "<div class='alert alert-success text-center' role='alert'>$msg</div>";
                                }
                                if(isset($_GET['updatemsg']))
                                {
                                  $updatemsg= $_GET['updatemsg'];
                                  echo "<div class='alert alert-success text-center' role='alert'>$updatemsg</div>";
                                }
                                if(isset($_GET['deletemsg']))
                                {
                                  $deletemsg= $_GET['deletemsg'];
                                  echo "<div class='alert alert-success text-center' role='alert'>$deletemsg</div>";
                                }
                                ?>
                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Disease :-</label>
                                    <div class="col-sm-9">
                                      <input type="text" name="disease" autocomplete="off" onKeyPress="return ValidateAlpha(event);" class="form-control"  placeholder="Enter Disease name" required="required" value="<?php if(isset($_GET['id'])){ echo $row['disease']; }  ?>" ondrop="return false;">
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
                  <h2 class="text-center">Disease Details</h2><hr>
                  <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                 <th>Disease name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="row_position">
                          <?php
                            $sql = "SELECT * FROM `tbl_disease` ORDER BY orderby";
                            $res = mysqli_query($con,$sql);
                            $number = 1;
                            while ($row=mysqli_fetch_assoc($res)):
                          ?>
                            <tr  id="<?php echo $row['id'] ?>">
                              <td><?php echo $number; ?></td>
                              <td><?php echo $row['disease']; ?></td>
                              <td><a href="adddisease.php?id=<?php echo $row["id"]; ?>" class="trigger-btn" id="edit"
                                                    data-toggle="tooltip" class="tip-bottom" title="Edit Disease" style="font-size: 20px;">
                                                    <i class="mdi mdi-lead-pencil "></i>
                                                  </a>
                                <a href="#myModaldelete" onclick="DeleteRow(<?php echo $row['id']; ?>)"  class="trigger-btn" data-toggle="modal" id="delete-Disease" data-toggle="tooltip" class="tip-bottom" title="Delete Disease" style="font-size: 20px;"><i class="mdi mdi-delete "></td>
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
                <p>Do you really want to delete these disease ?</p>
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

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>                              

 
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/misc.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <!-- End custom js for this page-->

  <script>
  function ValidateAlpha(evt)
  {
      var keyCode = (evt.which) ? evt.which : evt.keyCode
      if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
        
        return false;
        return true;
  }

  function DeleteRow(Id)
  {
    $('#deleteid').val(Id);
  }

  </script>
  
  <script type="text/javascript">
    $( ".row_position" ).sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $('.row_position>tr').each(function() {
                selectedData.push($(this).attr("id"));
            });
            updateOrder(selectedData);
        }
    });

    function updateOrder(data) {
        $.ajax({
            url:"ajaxPro.php",
            type:'post',
            data:{position:data},
            success:function(){
                alert('your change successfully saved');
            }
        })
    }
</script>  
 

</body>

</html>
