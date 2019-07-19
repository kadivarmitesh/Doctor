<?php
session_start();
require '../config.php';
if(!isset($_SESSION['id']))
{
    header("Location:../index.php?msg=Please login first");
}

$sql = "SELECT * FROM `tbl_disease` ORDER BY orderby";
$res = mysqli_query($con,$sql);

$qry = "SELECT * FROM `tbl_appointmenttime` WHERE `status`= 1";
$data = mysqli_query($con,$qry);

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Online Consult Doctor</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="colorlib.com">

        <link rel="icon" type="image/png" href="../images/icons/favicon.ico"/>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css">

		<!-- MATERIAL DESIGN ICONIC FONT -->
		<link rel="stylesheet" href="fonts/material-design-iconic-font/css/material-design-iconic-font.css">

		<!-- DATE-PICKER -->
		<!-- <link rel="stylesheet" href="vendor/date-picker/css/datepicker.min.css"> -->

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

		<!-- STYLE CSS -->
        <link rel="stylesheet" href="css/style.css">
<style>
.aDisabled
{
    cursor: default;
    pointer-events: none;
}

@media only screen and (max-width: 375px) and (min-width: 320px)  {
    img#logoimg {
        height: 52px;
        width: 100%;
    }
}
@media only screen and (max-width: 425px) and (min-width: 376px)  {
    img#logoimg {
        height: 39px;
        width: 100%;
    }
}
/* mobile horizontal  */
@media only screen and (max-width: 736px) and (min-width: 476px){
    img#logoimg {
    margin-top: 61px;
    height: 48px;
    width: 95%;
	
}

nav#mynav {
    margin-top: 15px;
}
}
</style>
	</head>
	<body>
		<div class="wrapper">

            <div class="row" style="height : 150px"> 
            <div class="col-md-6">
                <img src="images/Dr-Renuka-Siddhapura.png" alt="Trulli">
            </div>
            <div class="col-md-6">
                <img src="images/logo.png" alt="Trulli" id="logoimg">    
            </div>
            </div>
            <br>
        
        <!-- .navbar -->
        <nav class="navbar navbar-full navbar-dark bg-primary" id="mynav">
        <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">
        &#9776;
        </button>
        <a class="navbar-brand" href="#">
        <img class="img-rounded" src="https://placehold.it/32/ffffff?text=B">
        </a>
        <div class="collapse navbar-toggleable-md" id="mainNavbarCollapse">
            <ul class="nav navbar-nav pull-lg-right">
                <li class="nav-item">
                    <a class="nav-link" href="#wizard" id="bookapp">Book Appointment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#history" id="pateintdetails">History <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Sign out</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /.navbar -->
        <div id="history">
        <div class="card text-center">
            <div class="card-header">
                <h3>Appointment Details</h3>
            </div>
            <div class="card-body">
            <div style="margin:15px; margin-bottom: 30px;">
            <div class="table-responsive">
            <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Patient name</th>
                        <th scope="col">Appointment Date</th>
                        <th scope="col">Appointment Time</th>
                        <th scope="col">Status</th>
                        <th scope="col">Delete</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                        $userid = $_SESSION['id'];
                        $query = "CALL sp_FetchUserAppointment($userid)";                               
                        $result = mysqli_query($con,$query);
                        $number = 1;
                        while ($patients=mysqli_fetch_assoc($result)):      
                    ?>
                       <tr id="<?php echo $patients['appointment_id'] ?>">
                        <th scope="row"><?php echo $number; ?></th>
                        <td><?php echo $patients['firstname']." ".$patients['lastname']; ?></td>
                        <?php 
                            $adate = strtotime($patients['appointment_date']);
                            $appointmentdate = date("d-m-Y", $adate);
                        ?>
                        <td><?php echo $appointmentdate; ?></td>
                        <?php 
                            // 24-hour time to 12-hour time 
                            $starttime  = date("g:i A", strtotime($patients['appointment_starttime']));
                            $endtime  = date("g:i A", strtotime($patients['appointment_endtime']));
                        ?>
                        <td><?php echo $starttime." to ".$endtime; ?></td>
                        <td><?php if($patients['status'] == "Pending"){ echo '<span class="label label-warning">Pending</span>'; } 
                             if($patients['status'] == "Followup") { echo '<span class="label label-success">Followup</span>'; }
                            if($patients['status'] == "Cancelled") { echo '<span class="label label-danger">Cancelled</span>'; } ?></td>
                        <td>
                            <button class="tip-bottom remove" data-toggle="tooltip" title="Delete Appointment"> <i class="zmdi zmdi-close-circle-o"></i></button>
                        </td>
                      </tr>
                      <?php $number++; endwhile; ?>                            
                    </tbody>
            </table>
            </div>
            </div>
            </div>
        </div>
        </div>

            <form method="post" action="" id="wizard">
        		<!-- SECTION 1 -->
                <h4></h4>
                <section>
                <div class="card text-center">
                    <div class="card-header">
                        <div class="row">
                                <div class="col-md-12 col-sm-12">
                                  <h3 class="text-center">Book Appointment</h3>
                                  <b><p class="text-center" id="weektime"></p></b>
                                </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <div class="container-fluid">

                        <ul id="clothing-nav" class="nav nav-tabs" role="tablist">


                        </ul>

                    <!-- Content Panel -->
                    <div id="clothing-nav-content" class="tab-content">

                    <div role="tabpanel" class="tab-pane fade" id="tabDateSlots" aria-labelledby="tabDateSlots">
                        <br>
                        <div class="row" style="margin-bottom:10px;">
                        <div class="btn-group-toggle" data-toggle="buttons">
                        <?php while ($row1 = mysqli_fetch_assoc($data)): ?>
                            <div class="col-md-4 col-sm-4">
                            <label class="btn btn-secondary">
                                <input type="radio" name="options" id="<?php echo $row1['id'] ?>" autocomplete="off" value="<?php echo $row1['start-time']." to ".$row1['end-time']; ?>"> <?php echo $row1['start-time']." to ".$row1['end-time']; ?>
                            </label>
                            </div>
                        <?php endwhile; ?>
                        </div>
                        </div>
                    </div>

                     </div>
                </section>

				<!-- SECTION 2 -->
                <h4></h4>
                <section>
                <div class="card text-center">
                    <div class="card-header">
                    <fieldset data-role = "controlgroup" data-type = "horizontal" data-mini = "true">
                        <div class="col-md-6 float-left">                              
                        <input type = "radio" name = "radio1" id = "radio1" value = "val1" 
                        checked = "checked" onclick="show1();"/>
                        <label for = "radio20">New Appointment</label>
                        </div>
                        <div class="col-md-6 float-right">
                        <input type = "radio" name = "radio1" id = "radio1" value = "val2" onclick="show2();" />
                        <label for = "radio21">Follow Up Appointment</label>
                        </div>
                    </fieldset>
                    </div>
                    <div class="card-body">
                    <div class="alert alert-success alert-dismissible text-center" id="success" style="display:none;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    </div>
                    <div style="margin: 15px;margin-bottom: 30px;">
                    <div id="div1">       
                        <div class="alert alert-success">
                            You are selected new appointment, Please press continue button to book your appointment.
                        </div>
                    </div>
                    <div id="div2" class="hide">       
                    <div class="form-row">
                    <div class="table-responsive">
                        <table class="table table-hover" id="tableSelect">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient name</th>
                                <th>Last Appointment date</th>
                                <th>Disease</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                               mysqli_close($con);
                               require '../config.php';
                                $loginid = $_SESSION['id'];
                                $query1 = "CALL sp_FetchUserAppointment($loginid)";
                                $res123 = mysqli_query($con,$query1);
                                $no = 1;
                                //while ($record=mysqli_fetch_assoc($res123)):
                                while ($record =mysqli_fetch_array($res123,MYSQLI_ASSOC)):
                            ?>
                            <tr id="<?php echo $record['appointment_id']; ?>">
                                <td>
                                    <div>
                                        <label><input type="radio" id='pateintid-<?php echo $record['appointment_id']; ?>' name="optradio"  style="opacity:1" value="<?php echo $record['appointment_id']; ?>"></label>
                                    </div>
                                </td>
                                <td><?php echo $record['firstname']." ".$record['lastname'] ?></td>
                                <?php 
                                    $adate = strtotime($record['appointment_date']);
                                    $appointmentdate = date("d-m-Y", $adate);
                                ?>
                                <td><?php echo $appointmentdate; ?></td>
                                <td><?php echo $record['disease']; ?></td>
                            </tr>
                                <?php $no++; endwhile; ?>    
                            </tbody>
                        </table> 
                        </div> 
                    </div>
                    </div>
                </div>


                </section>

                <!-- SECTION 3 -->
                <h4></h4>
                <section>
                    <!-- <h3>Thank You For Book Appointment</h3> -->
                    <div class="card text-center">
                    <div class="card-header">
                        <h3>Patient details</h3>
                    </div>
                    <div class="card-body">
                    <div class="alert alert-success alert-dismissible text-center" id="success" style="display:none;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    </div>
                    <div style="margin: 15px;margin-bottom: 30px;">
                    <input type="hidden" id="followupid" name="followupid">
                    <div class="form-row">
                            <div class="form-holder">
                                <i class="zmdi zmdi-calendar"></i>
                                <input type="text" class="form-control" id="Appointmentdate" placeholder="Appointment date" disabled>
                            </div>
                            <div class="form-holder">
                                <i class="zmdi zmdi-time"></i>
                                <input type="text" class="form-control" id="Appointmenttime" placeholder="Appointment time" disabled>
                            </div>
                    </div>
                    <div class="form-row">
                            <div class="form-holder">
                                <i class="zmdi zmdi-account"></i>
                                <input type="text" class="form-control" id="firstname" autocomplete="off" maxlength="20" onKeyPress="return ValidateAlpha(event);" placeholder="First Name" ondrop="return false;" onpaste="return false;">
                            </div>
                            <div class="form-holder">
                                <i class="zmdi zmdi-account"></i>
                                <input type="text" class="form-control" id="lastname" autocomplete="off" maxlength="20" onKeyPress="return ValidateAlpha(event);" placeholder="Last Name" ondrop="return false;" onpaste="return false;">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-holder">
                                <i class="zmdi zmdi-email"></i>
                                <input type="email" class="form-control" id="email" autocomplete="off" placeholder="Email ID">
                            </div>
                            <div class="form-holder">
                                <i class="zmdi zmdi-calendar"></i>
                                <input type="text" class="form-control" id="appdate" autocomplete="off"  onkeypress="return isbirthdatekey(event)" placeholder="Birthdate" ondrop="return false;" onpaste="return false;">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-holder">
                                <i class="zmdi zmdi-caret-down-circle"></i>
                                <select class="form-control" id="disease">
                                     <option value="">Select Disease</option>
                                      <?php while ($row = mysqli_fetch_assoc($res)): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['disease']; ?></option>
                                      <?php endwhile; ?>
                                      </select>
                            </div>
                            <div class="form-holder password">
                                <i class="zmdi zmdi-smartphone-android"></i>
                                <input type="text" class="form-control" id="mobileno" autocomplete="off" pattern="^\d{10}$" maxlength="10" minlength="10" placeholder="Mobile No" onkeypress="return isNumberKey(event)" ondrop="return false;" onpaste="return false;">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-holder" style="width:100%; height: 71px; ">
                            <i class="zmdi zmdi-edit"></i>
                            <textarea class="form-control" rows="5" id="discription" autocomplete="off" placeholder="Discription" style="height: 75px;"></textarea>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                </section>

                <!-- SECTION 4 -->
                <h4></h4>
                <section>
                    <!-- <h3>Thank You For Book Appointment</h3> -->
                    <div class="jumbotron text-xs-center">
                        <h1 class="display-3">Thank You!</h1>
                        <p class="lead"><strong>Please check your email</strong> for further instructions on how to complete your Appointment booked.</p>
                        <hr>
                        <p class="lead">
                            <a class="btn btn-success btn-sm" href="index.php" role="button">Back to New Appointment</a>
                        </p>
                    </div>

                </section>
            </form>
		</div>

        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"> </script>
        <!--  -->
        <!-- <script src="vendor/date-picker/js/datepicker.js"></script> -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

		<!-- JQUERY STEP -->
		<script src="js/jquery.steps.js"></script>

        <script src="js/main.js"></script>

        <script type="text/javascript">
        function show1(){
            document.getElementById('div1').style.display = 'block';
            $('#div2').hide();
        }

        function show2(){
            document.getElementById('div2').style.display = 'block';
            $('#div1').hide();
        }

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

        $(".remove").click(function(){
            var id = $(this).parents("tr").attr("id");


            if(confirm('Are you sure to remove this Patient ?'))
            {
            $.ajax({
               url: 'deleteappointment.php',
               type: 'GET',
               data: {id: id},
               error: function() {
                  alert('Something is wrong');
               },
               success: function(data) {
                    $("#"+id).remove();
                    alert("Patient removed successfully");
                    //For wait 1 seconds
                    setTimeout(function() 
                    {
                        location.reload();  //Refresh page
                    }, 1000);
               }
            });
            }
        });


        </script>                            

        <script type="text/javascript">
            $(document).ready(function(){
                $('#appdate').datepicker({
                    //format: 'yyyy-mm-dd',
                    format: 'dd-mm-yyyy',
                    endDate: "today",
                    autoclose:true,
                });

                $('#wizard').hide();
                $('#bookapp').click(function(){
                    $('#wizard').show();
                    $('#history').hide();
                    $("#bookapp").addClass("active");
                    $('#pateintdetails').removeClass("active");
                });
                $('#pateintdetails').click(function(){
                    $('#history').show();
                    $('#wizard').hide();
                    $("#bookapp").removeClass("active");
                    $('#pateintdetails').addClass("active");
                });

                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();

                //todayd = yyyy + '-' + mm + '-' + dd;
                todayd = dd + '-' + mm + '-' + yyyy;
                var newdate = new Date();
                newdate.setDate(newdate.getDate()+6);
                var nedate = newdate.getFullYear() + "-" + (newdate.getMonth() + 1) + "-" + newdate.getDate();
                var f = new Date(nedate);
                var dd1 = String(f.getDate()).padStart(2, '0');
                var mm1 = String(f.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy1 = f.getFullYear();
                //todayd1 = yyyy1 + '-' + mm1 + '-' + dd1;
                todayd1 = dd1 + '-' + mm1 + '-' + yyyy1;
                $('#weektime').text("( "+todayd +" to "+todayd1+" )");

                var weekday = new Array();

                weekday[1] = "Monday";
                weekday[2] = "Tuesday";
                weekday[3] = "Wednesday";
                weekday[4] = "Thursday";
                weekday[5] = "Friday";
                weekday[6] = "Saturday";
                var d = today.getUTCDay();
                var todaydigit = d;
                var text="";
                var tomorrow = new Date();
                if(d==1)
                {
                    for(var i=1;i<=6;i++)
                {
                    if(todaydigit==d)
                    {
                        text=text+'<li class="nav-item" data-value="'+todayd+'"><a class="nav-link active" onClick=getAppoDate("'+todayd+'") href="#tabDateSlots" id="'+weekday[d]+'-tab" role="tab" data-toggle="tab" aria-controls="'+weekday[d]+'" aria-expanded="true" data-id ='+todayd+'>'+weekday[d]+'<br/>'+todayd+'</a></li>';
                        getAppoDate(todayd);
                        $("#tabDateSlots").addClass("show active in");

                    }
                    else
                    {
                        tomorrow.setDate(tomorrow.getDate() + 1);
                        var newdate = tomorrow.getFullYear() + "-" + (tomorrow.getMonth() + 1) + "-" + tomorrow.getDate();
                        var f1 = new Date(newdate);
                        if(f1.getDay() == 0)
                        {
                            continue;
                        }
                        else
                        {
                        var dd2 = String(f1.getDate()).padStart(2, '0');
                        var mm2 = String(f1.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy2 = f1.getFullYear();
                        //currentday = yyyy2 + '-' + mm2 + '-' + dd2;
                        currentday = dd2 + '-' + mm2 + '-' + yyyy2;
                        text=text+'<li class="nav-item" data-value="'+currentday+'"><a class="nav-link" href="#tabDateSlots" onClick=getAppoDate("'+currentday+'") id="'+weekday[d]+'-tab" role="tab" data-toggle="tab" aria-controls="tabDateSlots" aria-expanded="true" data-id ='+currentday+'>'+weekday[d]+'<br/>'+currentday+'</a></li>';
                        }
                    }

                    $("#clothing-nav").html(text);
                    d=d+1;
                    if(d==7)
                    {
                        d=1;
                    }

                }
                }
                else
                {
                    for(var i=0;i<=6;i++)
                {
                    if(todaydigit==d)
                    {
                        text=text+'<li class="nav-item" data-value="'+todayd+'"><a class="nav-link active" onClick=getAppoDate("'+todayd+'") href="#tabDateSlots" id="'+weekday[d]+'-tab" role="tab" data-toggle="tab" aria-controls="'+weekday[d]+'" aria-expanded="true" data-id ='+todayd+'>'+weekday[d]+'<br/>'+todayd+'</a></li>';
                        getAppoDate(todayd);
                        $("#tabDateSlots").addClass("show active in");

                    }
                    else
                    {
                        tomorrow.setDate(tomorrow.getDate() + 1);
                        var newdate = tomorrow.getFullYear() + "-" + (tomorrow.getMonth() + 1) + "-" + tomorrow.getDate();
                        var f1 = new Date(newdate);
                        if(f1.getDay() == 0)
                        {
                            continue;
                        }
                        else
                        {
                        var dd2 = String(f1.getDate()).padStart(2, '0');
                        var mm2 = String(f1.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy2 = f1.getFullYear();
                        //currentday = yyyy2 + '-' + mm2 + '-' + dd2;
                        currentday = dd2 + '-' + mm2 + '-' + yyyy2;
                        text=text+'<li class="nav-item" data-value="'+currentday+'"><a class="nav-link" href="#tabDateSlots" onClick=getAppoDate("'+currentday+'") id="'+weekday[d]+'-tab" role="tab" data-toggle="tab" aria-controls="tabDateSlots" aria-expanded="true" data-id ='+currentday+'>'+weekday[d]+'<br/>'+currentday+'</a></li>';
                        }
                    }

                    $("#clothing-nav").html(text);
                    d=d+1;
                    if(d==7)
                    {
                        d=1;
                    }

                }
                }
               
    
                var now = new Date();
                var hh = now.getHours();
                var min = now.getMinutes();
                                
                var ampm = (hh>=12)?'PM':'AM';
                hh = hh%12;
                hh = hh?hh:12;
                hh = hh<10?'0'+hh:hh;
                min = min<10?'0'+min:min;
                                
                var time = hh+" : "+min+" "+ampm;
               


                $("ul#clothing-nav > li").click(function() {
                    var tabclicked = $(this).find("a").attr("data-id");
                    // alert(tabclicked);
                    $("#Appointmentdate").val(tabclicked);
                   
                });
                $("ul#clothing-nav > li:first").trigger('click');
            });
            $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
            var tab = $(e.target);
             var appointmentDate = tab.attr("data-id");
             
                //This check if the tab is active
                if (tab.hasClass('active')) {
                    //console.log('the tab with the content id ' + appointmentDate + ' is visible');
                    $("#Appointmentdate").val(appointmentDate);
                } 

            });
  
        </script>

        <script type='text/javascript'>

        function getAppoDate(Appodate)
        {
            $(".btn-group-toggle input[type='radio']").each(function() {
                     var idVal = $(this).attr("id");
                        $("#" + idVal). attr('disabled', false);
                        $("#" + idVal).parents("label").addClass("btn-secondary").removeClass("disabled");
                    });
                
            $.ajax({
            url: 'getAppoTime.php?appointmentdt='+ Appodate,
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

                $(".btn-group-toggle input[type='radio']").each(function() {
                     var idVal = $(this).attr("id");

                    if($("#" + idVal).val() == existAppTime)
                    { 
                        $("#" + idVal). attr('disabled', true);
                        $("#" + idVal).parents("label").removeClass("btn-secondary").addClass("disabled");

                    }
                    });
                }); 
            }
            
          });

        }

        </script>

<!-- Template created and distributed by Colorlib -->
</body>
</html>