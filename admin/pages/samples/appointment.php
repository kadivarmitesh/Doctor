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

$msg="";
$deletemsg="";
$followupmsg="";
// Delete Appointment
if(isset($_POST['delete']))
{
  $did = $_POST['deleteid'];
  $delete = "DELETE FROM `tbl_appointment` WHERE `appointment_id`= $did";
  if(mysqli_query($con, $delete))
  {
    header("Location: appointment.php?deletemsg=Appointment Deleted Successfull");
    exit();
  }
  else{
    $eroormsg = "Something wrong Please try again";
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
    header("Location: appointment.php?followupmsg=Appointment Followuped successfull");
    exit();
  }
  else{
    $eroormsg = "Something is wrong Please try again";
  }
}

// cancel Appointment for Patient
if(isset($_POST['cancelappointment']))
{
  $appoointment_id = $_POST['cancelid'];
  $reason = $_POST['reason'];
  $status = "Cancelled";
  $update = "UPDATE `tbl_appointment` SET `reason`='".$reason."', `isDoc_cancelled`=1, `status`='".$status."' WHERE `appointment_id`=$appoointment_id";
  if(mysqli_query($con, $update))
  {
    header("Location: appointment.php?msg=Appointment cancelled");
    $successmsg = "Appointment cancelled";
    exit();
  }
  else{
    $eroormsg = "Something is wrong Please try again";
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
                  <div class="row">
                    <div class="col-md-3 col-sm-3"></div>
                    <div class="col-md-6 col-sm-6">
                      <h2 class="text-center">Appointments</h2>
                    </div>
                    <div class="col-md-3 col-sm-3">
                      <button type="button" data-toggle="modal" data-target="#AppointmentBookModel" class="btn btn-success btn-sm"><i class="mdi mdi-bookmark-plus"></i>Book an Appointment</button>
                    </div>
                  </div>
                  <hr><br>
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
                  if(isset($_GET['followupmsg']))
                  {
                    $followupmsg= $_GET['followupmsg'];
                    echo "<div class='alert alert-success text-center' role='alert'>$followupmsg</div>";
                  }
                  ?>
                  <div class="alert alert-success alert-dismissible text-center" id="success" style="display:none;">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                  </div>
                  <div class="card text-center" id="searchpatinetcard">
                    <div class="card-header">
                    <div class="row">
                            <div class="col-md-2">
                              <input type="text" class="form-control" id="datefrom" autocomplete="off" onkeypress="return isbirthdatekey(event)" placeholder="Select Date From"  ondrop="return false;" onpaste="return false;"  value="<?php echo date("d-m-Y");?>">
                            </div>
                            <div class="col-md-2 col-half-offset">
                                <input type="text" class="form-control" id="dateto" autocomplete="off" onkeypress="return isbirthdatekey(event)" placeholder="Select Date To"  ondrop="return false;" onpaste="return false;"  value="<?php echo date("d-m-Y");?>">
                              </div>
                              <div class="col-md-2 col-half-offset">
                                <input type="text" class="form-control" id="PatientName" onKeyPress="return ValidateAlpha(event);"  placeholder="PatientName" ondrop="return false;" onpaste="return false;"/>                                                                      
                              </div>                                                                      
                              <div class="col-md-2 col-half-offset">
                                    <select class="form-control" id="SearchingDisease">
                                      <option value="">Select Disease</option>
                                        <?php 
                                          $diaseassearchqry = "SELECT * FROM `tbl_disease` ORDER BY orderby";
                                                            $diaseassearchresult = mysqli_query($con,$diaseassearchqry);
                                                            while ($searchDisease = mysqli_fetch_assoc($diaseassearchresult)): ?>
                                                            <option value="<?php echo $searchDisease['id']; ?>" ><?php echo $searchDisease['disease']; ?></option>
                                                          <?php endwhile; ?>
                                                      </select>
                                </div>
                                <div class="col-md-2 col-half-offset">
                                  <button type="button" class="btn btn-outline-success btn btn-sm" onclick="FetchigPatinetData()">
                                    <i class="mdi mdi-magnify"></i>
                                    Search Appointments
                                  </button>
                                </div>

                            </div>  
                          </div>
                  </div>
                  <br><br>
                  <!-- Table Data -->
                  <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient Name</th>
                                <th>Mobile no</th>
                                <th>Disease</th>
                                <th>Appointment Date</th>
                                <th>Age</th>
                                <th>Status</th>
                                <th>More</th>
                                <th>Action</th>
                                <th>Follow up</th>
                            </tr>
                        </thead>
                        <tbody id="GetAppointmentData">
                         
                        </tbody>
                    </table>
                </div>
                </div>
              </div>
            </div>


            <div class="modal fade" id="AppointmentBookModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">Appointment book for patient</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                    <!-- Registration part patient -->
                    <div class="col-12 grid-margin" id="bookanappointment">
                        <div class="card">
                          <div class="card-body">
                            <h2 class="text-center"> Appointment book for patient</h2><hr><br>
                                <div class="card text-center" id="searchpatinetcard">
                                        <div class="card-header">
                                        <div class="row">
                                                
                                              
                                                <div class="col-md-6">
                                                  <input type="text" class="form-control" id="PatientNameInPopup" onKeyPress="return ValidateAlpha(event);"  placeholder="Enter PatientName" ondrop="return false;" onpaste="return false;"/>         
                                                </div>                                                                      
                                                <div class="col-md-6">
                                                  <button type="button" class="btn btn-outline-success btn btn-sm" onclick="FetchigPatinetDataInPopup()">
                                                    <i class="mdi mdi-magnify"></i>
                                                    Search Appointments
                                                  </button>
                                                </div>

                                          </div>  
                                        </div>
                                        <div class="card-body">
                                            <div id="DisplaysearchDataInPopup">
                                            
                                            </div>
                                        </div>
                                </div>
                                
                                <br><br>                                                                

                            <form class="form-sample" id="first_form" method="post" action="#">
                              <div class="alert alert-danger alert-dismissible text-center" id="displayerror" style="display:none;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                              </div>
                              <div class="row">
                              <input type="hidden" name="patientid" id="patientid" value="<?php if(isset($_GET['id'])){ echo $patientdata['id']; }  ?>"> 
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">First Name</label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" id="firstname" autocomplete="off" onKeyPress="return ValidateAlpha(event);"  placeholder="Enter Firstname" value="<?php if(isset($_GET['id'])){ echo $patientdata['firstname']; }  ?>" ondrop="return false;" onpaste="return false;"/>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Last Name</label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" id="lastname" autocomplete="off" onKeyPress="return ValidateAlpha(event);" placeholder="Enter Lastname" value="<?php if(isset($_GET['id'])){ echo $patientdata['lastname']; }  ?>" ondrop="return false;" onpaste="return false;"/>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Email</label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" id="email" autocomplete="off" placeholder="Enter Email" value="<?php if(isset($_GET['id'])){ echo $patientdata['email']; }  ?>" />
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Date of Birth</label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" id="birthdate" autocomplete="off" onkeypress="return isbirthdatekey(event)" placeholder="Patient Birthdate" value="<?php if(isset($_GET['id'])){ echo $patientdata['dob']; }  ?>" ondrop="return false;" onpaste="return false;">
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Disease</label>
                                      <div class="col-sm-9">
                                      <select class="form-control" id="disease">
                                        <option value="">Select Disease</option>
                                          <?php while ($dd = mysqli_fetch_assoc($diseas)): ?>
                                            <option value="<?php echo $dd['id']; ?>" <?php if(isset($_GET['id'])){ if ($diseaseid == $dd['id']) { echo 'selected'; } } ?>><?php echo $dd['disease']; ?></option>
                                          <?php endwhile; ?>
                                      </select>
                                      </div>
                                  </div> 

                                </div>
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Mobile Number</label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" id="mobileno" autocomplete="off" maxlength="10" onkeypress="return isNumberKey(event)" placeholder="Enter Phone no" value="<?php if(isset($_GET['id'])){ echo $patientdata['mobileno']; }  ?>" ondrop="return false;" onpaste="return false;"/>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Appointment Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="appdate" autocomplete="off" onkeypress="return isbirthdatekey(event)" placeholder="Appointement Date" value="<?php if(isset($_GET['id'])){ echo $patientdata['appointmentdate']; }  ?>" ondrop="return false;" onpaste="return false;">
                                        </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group row">
                                      <label class="col-sm-3 col-form-label">Appointment Time</label>
                                      <div class="col-sm-9">
                                        <select class="form-control" id="apptime">
                                        <option value="">Select appointment time</option>
                                          <?php while ($row1 = mysqli_fetch_assoc($data)): ?>
                                            <option value="<?php echo $row1['start-time']." to ".$row1['end-time']; ?>"  <?php if(isset($_GET['id'])){ if ($apptime == $row1['id']) { echo 'selected'; } } ?>><?php echo $row1['start-time']." to ".$row1['end-time']; ?></option>
                                          <?php endwhile; ?>
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Description</label>
                                    <div class="col-sm-9">
                                      <textarea class="form-control" rows="5" id="description" placeholder="Enter Description"><?php if(isset($_GET['id'])){ echo $patientdata['description']; }  ?></textarea>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  
                                </div>
                              </div>
                             
                              <?php
                                  if(isset($_GET['id']))
                                  {
                                    echo '<input class="btn btn-gradient-success mb-2 float-right" type="button" name="update" id="update" value="Update">';
                                  }
                                  else
                                  {
                                    echo '<input type="submit" class="btn btn-gradient-success mb-2 float-right" id="submit" value="Submit">';
                                  }
                                ?>
                             
                            </form>
                          </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
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


      <!-- Modal Prescription -->
      <div class="modal fade" id="Prescription" role="dialog">
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
                                 <td id="tdname"></td>
                              </tr>
                              <tr>
                                 <th scope="row">Age</th>
                                 <th>:-</th>
                                 <td id="tdage"></td>
                              </tr>
                              <tr>
                                 <th scope="row">Mobile No</th>
                                 <th>:-</th>
                                 <td id="tdmobileno"></td>
                              </tr>
                              <tr>
                                 <th scope="row">Email</th>
                                 <th>:-</th>
                                 <td id="tdemail"></td>
                              </tr>
                              <tr>
                                 <th scope="row">Disease</th>
                                 <th>:-</th>
                                 <td id="tddisease"></td>
                              </tr>
                              <tr>
                                 <th scope="row">Appointment Date</th>
                                 <th>:-</th>
                                 <td id="tdappointmentdate"></td>
                              <tr>
                                 <th scope="row">Status</th>
                                 <th>:-</th>
                                 <td id="tdstatus"></td>
                              </tr>
                              <tr>
                                 <th scope="row">Appointment Time</th>
                                 <th>:-</th>
                                 <td id="tdappointmenttime"></td>
                              </tr>
                              <tr>
                                 <th scope="row">Description</th>
                                 <th>:-</th>
                                 <td id="tddescription"></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <br>
                  <div class="card" id="symptomscard">
                     <div class="alert alert-success alert-dismissible text-center" id="successsymtoms" style="display:none;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                     </div>
                     <div class="prescription">
                        <div class="row">
                           <div class="col-md-3 col-sm-3"> 
                              <label for="symptoms">Symptoms:-</label>
                           </div>
                           <div class="col-md-9 col-sm-9"> 
                              <textarea class="form-control" rows="5" id="symptoms" placeholder="Type Here..."></textarea>
                              <br>
                              <button type="button" class="btn btn-success btn-sm" id="addsymptoms"> <i class="mdi mdi-note-plus btn-icon-prepend"></i>                                                    
                              Add</button>
                           </div>
                        </div>
                        <br>
                     </div>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="card" id="prescriotioncard">
                     <div class="alert alert-success alert-dismissible text-center" id="successPrescription" style="display:none;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                     </div>
                     <div class="prescription">
                        <div class="row">
                           <div class="col-md-3 col-sm-3"> 
                              <label for="Prescription">Prescription:-</label>
                           </div>
                           <div class="col-md-9 col-sm-9"> 
                              <textarea class="form-control" rows="5" id="prescription" placeholder="Type Prescription Here..."></textarea>
                              <br>
                              <button type="button" class="btn btn-success btn-sm" id="addpresction"> <i class="mdi mdi-note-plus btn-icon-prepend"></i>                                                    
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
                              <tbody id="preRecord">
                                
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
    $(document).ready(function() {
      $('#zero_config').DataTable();
    });
      
      function DeleteRow(Id)
      {
        $('#deleteid').val(Id);
      }    

      function FollowupAppointment(ID)
      {
        $('#followup').val(ID);
      }

      function CancelRow(Id)
      {
        $('#cancelid').val(Id);
      } 
                                
      function DeleteRowPrescription(Id)
      {
        $('#deleteprescriptionid').val(Id);
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
     // set default dates
      var start = new Date();
      var end = new Date(new Date().setYear(start.getFullYear()+1));
    $('#datefrom').datepicker({
        todayBtn:  1,
        format: 'dd-mm-yyyy',
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#dateto').datepicker('setStartDate', minDate);
    }); 

    $("#dateto").datepicker({
      format: 'dd-mm-yyyy',
    }).on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('#datefrom').datepicker('setEndDate', maxDate);
    });
    // insert symptoms 
    function Savesymptoms(id)
    {
    
      var symptoms = $('#symptoms').val();
      $(".error").remove();
      $error = 0;
      if (symptoms == '') {
            $('#symptoms').after('<span class="error">This field is required</span>');
            $error = 1;    
      }
     
      if($error == 0)
      {
            $.ajax({
            url: "insertsymptoms.php",
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
                $('#symptoms').val("");
                $('#successsymtoms').show();
                $('#successsymtoms').html('symptoms add successfully !'); 						
              }
              else if(dataResult.statusCode==201){
                alert("Error occured !");
              }						
            }
            
          });
        }
    }  
     
// insert Prescription
function Saveprescription(id)
{
    
    var prescription = $('#prescription').val();
    $(".error").remove();
    $error = 0;
    if (prescription == '') {
        $('#prescription').after('<span class="error">This field is required</span>');
        $error = 1;    
    }
     
    if($error == 0)
    {
        $.ajax({
        url: "insertprescription.php",
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
                
              string += "<tr id='rowid-"+dataResult.result['id']+"'><td>#</td>"+ 
              "<td>"+today+"</td>"+
              "<td><span class='editSpan prescription' id='editSpandata-"+dataResult.result['id']+"'>"+ dataResult.result["prescription"]+"</span><input class='editInput prescriptionchange form-control input-sm' type='text' id='prescriptioneditchange-"+dataResult.result['id']+"' name='prescriptioneditchange-"+dataResult.result['id']+"' value='"+dataResult.result["prescription"]+"' style='display: none;'></td> "+
              "<td><button type='button' class='btn btn-sm btn-default editBtn' id='editBtn-"+dataResult.result['id']+"' onclick='FunEditPrescription("+dataResult.result['id']+")' style='float: none;'><span class='mdi mdi-pencil'></span></button>"+
              "<button type='button' class='btn btn-sm btn-success saveBtn' id='PresaveBtn-"+dataResult.result['id']+"' onclick='UpdatePresaveBtn("+dataResult.result['id']+")' style='float: none; display: none;'>Save</button>"+
              "<button type='button' class='btn btn-sm btn-default deleteBtn' id='deleteButton-"+dataResult.result['id']+"' onclick='FunDeletePrescription("+dataResult.result['id']+")' style='float: none;'><span class='mdi mdi-delete'></span></button>"+
              "<button type='button' class='btn btn-sm btn-danger confirmBtn' id='confirmdeleteButton-"+dataResult.result['id']+"' onclick='confirmDelPrescription("+dataResult.result['id']+")' style='float: none; display: none;'>Confirm</button>"+
              "</td></tr>";
            $('#prescription').val("");
            $('#successPrescription').show();
            $('#successPrescription').html('Prescription add successfully !'); 	
            }
            $('#preRecord').append(string);
                            
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
   
    function UpdatePresaveBtn(id)
    {
      var inputData = $('#prescriptioneditchange-'+id).val();
      var ID = id;

      $.ajax({
            type:'POST',
            url:'UpdatePrescription.php',
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
    function confirmDelPrescription(id)
    {
      var ID = id;
      $.ajax({
            type:'POST',
            url:'PrescriptiondeleteAction.php',
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
       
        if($error == 0)
        {
            $.ajax({
            url: "insertpatient.php",
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
              description : description		
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
                $('#success').html('Appointment Book successfully !'); 
                $('#AppointmentBookModel').modal('toggle');
              }
              else if(dataResult.statusCode==201){
                alert("Error occured !");
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

<!-- Fetchin data -->
<script type="text/javascript">

$(document).ready(function(){ 
  var urlParams = new URLSearchParams(window.location.search);
  var patient_name = urlParams.get('Patient_name');
  if(patient_name==null)
  {
    FetchigPatinetData();
  }
  else
  {
    $('#datefrom').val('');
    $('#dateto').val('');
    $('#PatientName').val(patient_name);
    FetchigPatinetData();
  }
  
});

function FetchigPatinetData()
{
  var fromdate = $("#datefrom").val();
  var dateto = $("#dateto").val();
  var PatientName = $("#PatientName").val();
  var SearchingDisease = $("#SearchingDisease").val();

  $.ajax({
      url: "SelectCustomPatients.php",
      type: "POST",
      data: {
        fromdate : fromdate,
        dateto: dateto,
        PatientName: PatientName,
        SearchingDisease: SearchingDisease				
      },
     
      cache: false,
      success: function(dataResult){
        var jsonData = JSON.parse(dataResult);
        table = $('#zero_config').DataTable();
        table.destroy();
        $("#GetAppointmentData").html(jsonData.result);

        table =$('#zero_config').dataTable({
            paging: true,
            searching: true
        });
      			
      }
            
  });


}


// Fetch appointment Details

function AppointmentDetails(Id,firstname,lastname,age,mobile,email,disease,appointmentdate,status,starttimt,endtime,description)
{
  // alert(Id);
  $('#Prescription').modal();
  $('#tdname').html(firstname+' '+lastname);
  $('#tdage').html(age+' years');
  $('#tdmobileno').html(mobile);
  $('#tdemail').html(email);
  
  $('#tddisease').html(disease.split('=').join(' '));
  $('#tdappointmentdate').html(appointmentdate);
  $('#tdstatus').html(status);
  $('#tdappointmenttime').html(starttimt.split('=').join(' ')+" to "+endtime.split('=').join(' '));
  $('#tddescription').html(description.split('=').join(' '));

  document.getElementById("addsymptoms").addEventListener("click",function() {
    Savesymptoms(Id);
  });  
  document.getElementById("addpresction").addEventListener("click",function() {
    Saveprescription(Id);  

  });

  GetPrescription(Id);
}


function GetPrescription(Id)
{
  
  $.ajax({
      url: "FetchPreviousPrescription.php",
      type: "POST",
      data: {
        appointmentID: Id				
      },
      cache: false,
      success: function(dataResult){
        var jsonData = JSON.parse(dataResult); 
        $("#preRecord").html("");
        $("#preRecord").html(jsonData.result);
        					
      }
            
  });
}

// Searching Exist appointment in patient BY search patient name in popup
function FetchigPatinetDataInPopup()
{
  var patinetname = $('#PatientNameInPopup').val();
 
  $.ajax({
    url: "SelectExistFollowupPatients.php",
    type: "POST",
    data: {
      PatientName: patinetname				
    },
    cache: false,
    success: function(dataResult){
      var jsonData = JSON.parse(dataResult); 
      $("#DisplaysearchDataInPopup").html(jsonData.result);
      
    }
            
  });
}

// Followup check radio then get appointment and bind input text 

function FollowupFetchAppointmentDetails(id,firstname,lastname,email,dob,did,mobileno)
{
    //alert(id);
    $('#patientid').val(id);
    $('#firstname').val(firstname);
    $('#lastname').val(lastname);
    $('#email').val(email);
    $('#birthdate').val(dob);
    $('#mobileno').val(mobileno);
    $('#disease').val(did).attr("selected");
    
}

$('input[id="appdate"]').change(function(){
 
    $('#appdate').val(this.value);
    var inputDate = this.value;
    
    $.ajax({
        url: 'getAppoTime.php?appointmentdt='+ inputDate,
        type: 'GET',
        
        success: function(data){ 
          
        var obj = jQuery.parseJSON(data);
        
        $.each(obj, function(key,value) {
        
        var stattime = value.appointment_starttime;
        var endtime = value.appointment_endtime;

        var H = +stattime.substr(0, 2);
        var h = (H % 12) || 12;
        var ampm = H < 12 ? "AM" : "PM";
        timeString = h + stattime.substr(2, 3) + " "+ ampm;
        

        var H = +endtime.substr(0, 2);
        var h = (H % 12) || 12;
        var ampm = H < 12 ? "AM" : "PM";
        endtime = h + endtime.substr(2, 3) + " "+ ampm;
        
        var existAppTime =  timeString + " to " + endtime;
        
        $("#apptime option").each(function()
        {
            if ($(this).text() == existAppTime) {
                    this.disabled = true;
            }
        });

    }); 
  }
  
});


});

</script>


</body>

</html>