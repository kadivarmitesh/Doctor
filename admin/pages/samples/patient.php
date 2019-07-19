<?php
session_start();
require '../../../config.php';
if(!isset($_SESSION['id']))
{
    header("Location: login.php?msg=Please login first");
}

$sql = "SELECT * FROM `tbl_disease` ORDER BY orderby";
$diseas = mysqli_query($con,$sql);

$qry = "SELECT * FROM `tbl_appointmenttime` WHERE `status`= 1";
$data = mysqli_query($con,$qry);

if(isset($_GET['User_ID']))
{
  $userid= $_GET['User_ID'];
}

// edit Patient
if(isset($_GET['id']))
{

  $id =$_GET['id'];
  $qry = "SELECT * FROM `patinet_tbl` WHERE `id`=$id";

  $res = mysqli_query($con,$qry); 
  $patientdata = mysqli_fetch_assoc($res);
  $diseaseid =  $patientdata['diseaseid'];
  $apptime = $patientdata['appointmenttime'];

}

// Delete Patient
if(isset($_POST['delete']))
{
  $did = $_POST['deleteid'];
  $delete = "DELETE FROM `patinet_tbl` WHERE `id`= $did";
  if(mysqli_query($con, $delete))
  {
    header("Location: addpatient.php?msg=Patient Deleted Successfull");
    $successmsg = "Patient Deleted Successfull";
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
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h2 class="text-center">Patient Details</h2><hr>
                  <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient Name</th>
                                <th>email</th>
                                <th>Birth date</th>
                                <th>Mobile no</th>
                                <th>View Appointment</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php
                            if(isset($_GET['User_ID']))
                            {
                              $userid= $_GET['User_ID'];
                              $sql = "SELECT * FROM `tbl_patient` WHERE `userid`=$userid";
                            }
                            else
                            {
                              $sql = "CALL sp_FetchPatient()";                         
                            }
                            $res = mysqli_query($con,$sql);
                            $number = 1;
                            while ($row=mysqli_fetch_assoc($res)):      
                          ?>
                            <tr>
                                <td><?php echo $number; ?></td>
                                <td><?php echo $row['firstname']." ".$row['lastname']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <?php 
                                  $bdate = strtotime($row['dob']);
                                  $birthdate = date("d-m-Y", $bdate);
                                ?>
                                <td><?php echo $birthdate; ?></td>
                                <td><?php echo $row['mobileno']; ?></td>
                                <td><a href='appointment.php?Patient_name=<?php echo $row['firstname']; ?>' class='btn btn-gradient-info btn-sm' data-toggle='tooltip' class='tip-bottom' title='View Patinets' >View Appointment</a></td>
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
                <p>Do you really want to delete these Patient ?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                <input class="btn btn-danger" type="submit" name="delete" value="Delete">
                </form>
              </div>
            </div>
          </div>
        </div>    

    <?php include_once('footer.php');  ?>
        
  <!-- datatable -->
  <script src="../../vendors/js/datatables.min.js"></script>

  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/misc.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <!-- End custom js for this page-->

  <!-- datepicker -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script>
      $('#zero_config').DataTable();
      function DeleteRow(Id)
      {
          $('#deleteid').val(Id);
      }                               

      $('#birthdate').datepicker({ 
        format: 'dd-mm-yyyy',
        endDate: "today",
        autoclose:true,
      });
      
      var date = new Date();
        date.setDate(date.getDate());
      $('#appdate').datepicker({ 
        format: 'dd-mm-yyyy',
        startDate: date,
        autoclose:true,
        daysOfWeekDisabled: [0]
      });
  
  </script>


<script type="text/javascript"> 

    function ValidateAlpha(evt)
    {
        var keyCode = (evt.which) ? evt.which : evt.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
         
          return false;
          return true;
    }
    function isNumberKey(evt){  
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode < 48 || charCode > 57)
            return false;
            return true;
    }
    function isbirthdatekey(evt){
      var charCode = (evt.which) ? evt.which : evt.keyCode
      if (charCode != 45 && charCode > 31  && (charCode < 48 || charCode > 57))
          return false;
          return true;
    }

    $('#first_form').submit(function(e) {
      e.preventDefault();
      var firstname = $('#firstname').val();
      var lastname = $('#lastname').val();
      var email = $('#email').val();
      var birthdate = $('#birthdate').val();
      var disease = $('#disease').val();
      var mobileno = $('#mobileno').val();
      var appdate = $('#appdate').val();
      var apptime = $('#apptime').val();
      var description = $('#description').val();
      var status = $('#status').val();
      $(".error").remove();

        $error = 0;
        if (firstname.length < 1) {
            $('#firstname').after('<span class="error">This field is required</span>');
            $error = 1;
        }
        else{
          var alpha = /^[a-zA-Z\s-, ]+$/;
          if (!firstname.match(alpha)) {
          $('#firstname').after('<span class="error">Only Charaters  allowed in firstname</span>');     
          $error = 1;
        }

        } 
        if (lastname.length < 1) {
            $('#lastname').after('<span class="error">This field is required</span>');
            $error = 1;
        }
        else{
          var alpha = /^[a-zA-Z\s-, ]+$/;
          if (!lastname.match(alpha)) {
          $('#lastname').after('<span class="error">Only Charaters  allowed in lastname</span>');     
          $error = 1;
        }

        } 
        if (email.length < 1) {
            $('#email').after('<span class="error">This field is required</span>');
            $error = 1;
        } else {
            var regEx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var validEmail = regEx.test(email);
            if (!validEmail) {
              $('#email').after('<span class="error">Enter a valid email</span>');
              $error = 1;
        }
        }
        if (birthdate.length < 1) {
            $('#birthdate').after('<span class="error">Please select birthdate</span>');
            $error = 1;
        }

        if (disease == '') {
            $('#disease').after('<span class="error">This field is required</span>');
            $error = 1;
        }
        
        if (mobileno.length < 1) {
            $('#mobileno').after('<span class="error">This field is required</span>');
            $error = 1;
        }
        else{
          // var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;
          // if(!numericReg.test(mobileno)) {
          //     $('#mobileno').after('<span class="error">Numeric characters only.</span>');
          // }
          var ten = /^[0][1-9]\d{9}$|^[1-9]\d{9}$/;
          if(!ten.test(mobileno)) {
              $('#mobileno').after('<span class="error">Mobile no must be 10 digit</span>');
          }
        }
        if (appdate.length < 1) {
            $('#appdate').after('<span class="error">Please select birthdate</span>');
            $error = 1;
        }
        if (apptime == '') {
            $('#apptime').after('<span class="error">This field is required</span>');
            $error = 1;
        }
        
        if (description.length < 1) {
            $('#description').after('<span class="error">This field is required</span>');
            $error = 1;
        }
        if (status == '') {
            $('#status').after('<span class="error">This field is required</span>');
            $error = 1;
        }

        if($error == 0)
        {
            $.ajax({
            url: "insertpatient.php",
            type: "POST",
            data: {
              firstname: firstname,
              lastname: lastname,
              email: email,
              birthdate: birthdate,
              disease: disease,
              mobileno: mobileno,
              appdate: appdate,
              apptime: apptime,
              description : description,
              status : status				
            },
            cache: false,
            success: function(dataResult){
              console.log(dataResult);
              var dataResult = JSON.parse(dataResult);
              if(dataResult.statusCode==200){
                $('#first_form').find('input:text').val('');
                $("#description").val("");
                $('#apptime').val("");
                $('#status').val("");
                $("#success").show();
                $("#displayerror").hide();
                $('#success').html('Patient added successfully !'); 						
              }
              else if(dataResult.statusCode==301)
              {
                $("#displayerror").show();
                $('#displayerror').html('Email id already exists!'); 						
              }
              else if(dataResult.statusCode==302)
              {
                $("#displayerror").show();
                $('#displayerror').html('Mobile number is already exists!'); 	 
              }
              else if(dataResult.statusCode==201){
                $("#displayerror").show();
                $('#displayerror').html('Something are wrong please try again !'); 
              }						
            }
            
          });

        }

   
    });


    // edit Patient
    $("#update").click(function(){
      var patientid = $('#patientid').val();
      var firstname = $('#firstname').val();
      var lastname = $('#lastname').val();
      var email = $('#email').val();
      var birthdate = $('#birthdate').val();
      var disease = $('#disease').val();
      var mobileno = $('#mobileno').val();
      var appdate = $('#appdate').val();
      var apptime = $('#apptime').val();
      var description = $('#description').val();
      var status = $('#status').val();
      $(".error").remove();

        $error = 0;
        if (firstname.length < 1) {
            $('#firstname').after('<span class="error">This field is required</span>');
            $error = 1;
        }
        else{
          var alpha = /^[a-zA-Z\s-, ]+$/;
          if (!firstname.match(alpha)) {
          $('#firstname').after('<span class="error">Only Charaters  allowed in firstname</span>');     
          $error = 1;
        }

        } 
        if (lastname.length < 1) {
            $('#lastname').after('<span class="error">This field is required</span>');
            $error = 1;
        }
        else{
          var alpha = /^[a-zA-Z\s-, ]+$/;
          if (!lastname.match(alpha)) {
          $('#lastname').after('<span class="error">Only Charaters  allowed in lastname</span>');     
          $error = 1;
        }

        } 
        if (email.length < 1) {
            $('#email').after('<span class="error">This field is required</span>');
            $error = 1;
        } else {
            var regEx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var validEmail = regEx.test(email);
            if (!validEmail) {
              $('#email').after('<span class="error">Enter a valid email</span>');
              $error = 1;
        }
        }
        if (birthdate.length < 1) {
            $('#birthdate').after('<span class="error">Please select birthdate</span>');
            $error = 1;
        }

        if (disease == '') {
            $('#disease').after('<span class="error">This field is required</span>');
            $error = 1;
        }
        
        if (mobileno.length < 1) {
            $('#mobileno').after('<span class="error">This field is required</span>');
            $error = 1;
        }
        else{
          var ten = /^[0][1-9]\d{9}$|^[1-9]\d{9}$/;
          if(!ten.test(mobileno)) {
              $('#mobileno').after('<span class="error">Mobile no must be 10 digit</span>');
          }
        }
        if (appdate.length < 1) {
            $('#appdate').after('<span class="error">Please select birthdate</span>');
            $error = 1;
        }
        if (apptime == '') {
            $('#apptime').after('<span class="error">This field is required</span>');
            $error = 1;
        }
        
        if (description.length < 1) {
            $('#description').after('<span class="error">This field is required</span>');
            $error = 1;
        }
        if (status == '') {
            $('#status').after('<span class="error">This field is required</span>');
            $error = 1;
        }  

        if($error == 0)
        {
            $.ajax({
            url: "updatepatient.php",
            type: "POST",
            data: {
              patientid : patientid,
              firstname: firstname,
              lastname: lastname,
              email: email,
              birthdate: birthdate,
              disease: disease,
              mobileno: mobileno,
              appdate: appdate,
              apptime: apptime,
              description : description,
              status : status					
            },
            cache: false,
            success: function(dataResult){
              console.log(dataResult);
              var dataResult = JSON.parse(dataResult);
              if(dataResult.statusCode==200){
                $('#first_form').find('input:text').val('');
                $("#description").val("");
                $('#apptime').val("");
                $('#status').val("");
                $("#success").show();
                $('#success').html('Patient Updated successfully !'); 						
              }
              else if(dataResult.statusCode==201){
                alert("Error occured !");
              }						
            }
            
          });

        }

   
    });
     

</script>

</body>

</html>
