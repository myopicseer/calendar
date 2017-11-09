<?php 
header("Expires: Sat, 1 Jan 2005 00:00:00 GMT");
header("Last-Modified: ".gmdate( "D, d M Y H:i:s")."GMT");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
if(!isset($_SESSION)){
	require_once('classes/session.php');
	$s = new Session;
}
			


	if($_SESSION['user']['name']){
			//if($query['user']){
				//$username =  $query['user'];
				$username = $_SESSION['user']['name'];	
				session_id() != NULL ? $sesID = session_id() : $sesID = '' ;
		} else {
			header("location: login.php");
		}

				
			
if( $_SESSION['user']['role'] === 'user' || !empty($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'guest' ){
		$role = 'user';	
	}

	
	//session_start();
  	//$_SESSION['counter']++;
  	//echo "You have visited this page $_SESSION[counter] times.";
	
/*  LOAD NEW CSS FILE IF MODIFIED DATE CHANGED  */

/**
 *  Given a file, i.e. /css/base.css, replaces it with a string containing the
 *  file's mtime, i.e. /css/base.1221534296.css.
 *  
 *  @param $file  The file to be loaded.  Must be an absolute path (i.e.
 *                starting with slash).
 */
//clear the remote cached file info from prior filemtime operations.
clearstatcache();
function auto_version($file)
{
  
  if(strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file))
    return $file;

  $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
  return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}
/* USE: <link rel="stylesheet" href="<?php echo auto_version('/css/base.css'); ?>" type="text/css" />*/




?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title></title> 
   <!--styles-->  
  <!--styles-->   
   <link rel="stylesheet" href="<?php echo auto_version('styles/bootstrap.min.css'); ?>" type="text/css" media="screen" />
   
   <link rel="stylesheet" href="<?php echo auto_version('styles/calendarview.css'); ?>" type="text/css" media="screen" />
   
   <link rel="stylesheet" href="<?php echo auto_version('styles/print.css'); ?>" type="text/css" media="print" />   
	
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">   
   <link href='https://fonts.googleapis.com/css?family=Kaushan+Script&effect=3d-float' rel='stylesheet' type='text/css'> 
 
   <link rel="stylesheet" href="assets/icomoon/style.css" type="text/css" media="all">
    <link rel="stylesheet" href="styles/nav.css">	
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">  
   <link href='https://fonts.googleapis.com/css?family=Kaushan+Script&effect=3d-float' rel='stylesheet' type='text/css'> 
 
   <link rel="stylesheet" href="assets/icomoon/style.css" type="text/css" media="all">
    <link rel="stylesheet" href="styles/nav.css">
 <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw=" crossorigin="anonymous"></script>
   <style>
   .addNewLine{display:none;}
   </style>
    
</head>
<body>
<div class="container-fluid" style="padding:15px;">
<pre style="text-align:center"><span style="font-size: 12px; background:#FEFFF3;padding:5px 8px;color:#419200; border: 1px dotted #8AC72D">Today: <span id="date"></span> [ <span id="curTime"></span> ]</span> <br>
<span><a href="contact.php" target="_blank" title="Opens Email Form in a New Window or Tab">REPORT BUGS</a> | <a href="help.html" target="_blank" title="WIP Support">HELP</a> | <a href="directory.php" target="_parent" title="Employee Phone Directory">EMPLOYEE DIRECTORY</a></span><br><br><span  style="text-align:center; margin: 8px auto 2px auto">Recommended Browsers (Avoid IE; MS Edge is OK)</span><br><img src="assets/compatible_browsers.png" title="Compatible Browsers for This Calendar App" style="text-align:center; margin: 0px auto 5px auto" />
</pre>

<h1 id="pageTitle" class="cursive font-effect-3d-float" style="margin: 6px auto;text-align:center;color:#000"></h1>
	<!--<h3 style="margin: 14px auto;text-align:center;color:#4642A8;background:#DDE95B;padding:18px; text-align:center;font-family: 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, 'sans-serif'">
		APP DEVELOPMENT PLAYGROUND: This calendar is NOT SHARING nor SAVING Job Data with the Live Calendar.
		
	</h3>-->
	
<br />


<br />

<div class="row">
	<div class="col-lg-3 col-md-offset-1">		
		<div style="margin: 2px auto 8px auto;font-size:18px;color:#8AC72D" class="cursive">Choose a Company Calendar</div>
	
	<form action="" method="post" name="loadCalendar" id="loadCalendarForm">
		<select name="companyCalendar" id="companyCalendar" >
			<option value="Custom Sign Center" selected>Custom Sign Center</option>
			<option value="JG Signs">JG Signs</option>
			<option value="Marion Signs">Marion Signs</option>          
			<option value="Boyer Signs">Boyer Signs</option>
			<option value="Outdoor Images">Outdoor Images</option>			
		  </select>
		  <!--<input type="hidden" name="company" id="company" value="Custom Sign Center" />-->
		  <button class="smBtn" name="submitBtn" style="margin-left: 15px">Submit</button>
	 </form>
	 </div><!--/col-lg-4, select-company-form wrapper-->
	 
	 <div class="col-lg-3 col-md-offset-1">
		 <form class="form-group"><label style="color:#8AC72D; font-weight: normal;font-size:18px" class="cursive">Search (Job Number or ANY text)</label>
			<input type="text" id="search" class="form-control form-control-lg" placeholder="Search and Click" style="background-color: lightyellow; margin-top:6px">
			<div id="srchResult" class="hide" style="background-color: #86C73A; color:#0B6F93; padding:10px 25px"></div>
		</form>	 
	</div><!--/col-lg-4, search jobs form-->	 
	 
	 <div class="col-lg-3 col-md-offset-1">
		<?php if($username) { 
			echo "<div style=\"margin: 2px auto 8px auto;;font-size:18px;color:#8AC72D\" class=\"cursive\">Welcome <span id=\"username\">". $username ." <span style='font-family:san-serif'>&nbsp;(".$role.").</span></span></div>
			<form action=\"login.php\" method=\"POST\" >
				<input type=\"hidden\" value=\"".$username."\" name=\"loggedOutUser\" />
				<input class=\"smBtn\" type=\"submit\" value=\"logout\" name=\"logout\" />
			</form>
			<br/>
			<!-- Show this for admins?
			<form action=\"register.php?sid={$sesID}\" method=\"POST\" >
				<input type=\"hidden\" value=\"".$_SESSION['user']['role']."\" name=\"role\" />
				<input type=\"submit\" value=\"Register a User\" name=\"logout\" />
			</form>
			-->
			";
		} else {
			$username = 'user';
		} ?>
	 </div><!--/col-lg-3, Welcome user, logout form-->
 </div> <!--end row-->
 <div id="icons">
 <div class="row">
      
        <!-- BACKUP
        
        <button class="morPad center btn backup" name="backup" onClick="toggleVisibility('#backup')">CREATE RESTORE POINT</button>
        <form id="backup" name="backup" class="hide">
		   <p>Backup File Name (leave as defined below, or enhance the name.)</p>
		   <label>File Name: 
			   <span>

			   <?php //$filename = date('Y-m-d', strtotime('now')) + '_' + $username;  echo $filename; ?>
			   
			   </span>
		   
		   </label> <input type="text" name="filenameSuffix" value="" />
		            <input type="hidden" name="filename" value="<?php // echo  $filename ?>" />
			       <input type="submit" value="Backup Now" onclick='backUpCal()'>
	  </form>
       -->
        <!-- lengend -->
        
        
		
			   <!-- column #1: listing of overdue jobs -->        
			   <!-- show here, if any, jobs marked as OVERDUE -->
		
		<div class="col-lg-3 col-md-offset-1">
			<div style="color:#8AC72D;font-size:18px" class="cursive" >Overdue Jobs</div>	  
			<div id="OverDueJobsList">
			</div>		
		</div>   
			   
		
		<!-- column #2: The Job Assignment Checkbox Groups --> 
		
		<div class="col-lg-8"> 
	   
	    		<div class="container-fluid">
	    		  <div name="teamdata" action="" id="teamSelection"> <!--js id to show/hide ticked checkbox assignments-->
				<div class="row">		   
				    <div class="iconrow alert alert-success">      
					    <!--chkbx attrib is used to show / hide jobs having matched css class (i.e. t21) -->    	
						<input style="float:left" type="checkbox" id="select-21" name="t21" value="t21" checked="checked">               
						<div class="box-label due"> Overdue</div>
					</div>
				</div><!--/row-->
				<div class="row">					  
					  <span style="padding-top:12px;float:left; clear:left">Assignment: &nbsp;</span>
						  <div class="iconrow">             
							<input style="float:left" type="checkbox" id="select-1" name="t1" value="t1" checked="checked">

						    <div class="box-label t1" id="l1"></div>
						</div>
						<div class="iconrow">              
						    <input style="float:left" type="checkbox" id="select-2" name="t2" value="t2"  checked="checked">

						    <div class="box-label t2" id="l2"></div>
						</div>
						  <div class="iconrow">              
						    <input style="float:left" type="checkbox" id="select-3" name="t3" value="t3"  checked="checked">

						    <div class="box-label t3" id="l3"></div>
						</div>          

						  <div class="iconrow">              
						    <input style="float:left" type="checkbox" id="select-4" name="t4" value="t4"  checked="checked">

						    <div class="box-label t4" id="l4"></div>
						</div>      

						<div class="iconrow">              
						    <input style="float:left" type="checkbox" id="select-5" name="t5" value="t5"  checked="checked">

						    <div class="box-label t5" id="l5"></div>
						</div>          
						 <div class="iconrow">               
							<input style="float:left" type="checkbox" id="select-6" name="t6" value="t6"  checked="checked">

							<div class="box-label t6" id="l6"></div>
						 </div>      
						 <div class="iconrow">              
							<input style="float:left" type="checkbox" id="select-7" name="t7" value="t7" checked="checked">
							<div class="box-label" id="l7"></div>
						 </div>                                
						<div class="iconrow">
							<input style="float:left" type="checkbox" id="select-8" name="t8" value="t8" checked="checked">               

							<div class="box-label t8" id="l8"></div>
						</div>                                        
					    <div class="iconrow">              
						    <input style="float:left" type="checkbox" id="select-9" name="t9" value="t9" checked="checked">
						   <!--<div class="icon-box b7"></div>-->
						    <div class="box-label t9" id="l9"></div>
					    </div>                                                  
					    <div class="iconrow">         		
							<input style="float:left" type="checkbox" id="select-10" name="t10" value="t10"  checked="checked">

							<div class="box-label t10" id="l10"></div>
					    </div> 

						<div class="iconrow">          	
							<input style="float:left" type="checkbox" id="select-11" name="unassigned" value="unassigned" checked="checked">               
							<div class="box-label" id="l11"> Unassigned</div>
						</div>         
						 
				</div><!--/row-->
		         </div><!--/#teamselection -- used by js to collect array of checkboxes for show/hide click events-->
			    <div class="j row">
				    <span style="padding-top:12px;float:left; color:#0d58a1;">Job Status: &nbsp;</span>
				   <!-- <div class="iconrow">
						<div class="icon-box b9"></div>
						<input type="checkbox" id="select-9" name="completed" value="t9" checked="checked">
						<div class="box-label completed" id="l9">Completed</div>
					</div>        
					-->         

					<!-- Adding New Status Icons for Install Status and Permitting -->
					<!-- Partially Completed Job, Return Trip Required - half-filled circle -->
					<div class="iconrow">
					  <!--  <input style="float:left" type="checkbox" id="select-12" name="inst-return" value="t12" checked="checked">-->
						<div class="box-label" id="l12"></div>
					</div>

					<!-- Completed Job - Fully-filled Circle -->
					<div class="iconrow">
						 <!--<input style="float:left" type="checkbox" id="select-13" name="inst-compl" value="t13" checked="checked">-->
						<div class="box-label" id="l13"></div>
					</div>

					<!-- Completed and Invoiced Job - Fully-filled Circle + 'chmark' -->
					<div class="iconrow">
					   <!--  <input style="float:left" type="checkbox" id="select-14" name="inst-invoice" value="t14" checked="checked">-->
						<div class="box-label ic-i-comp" id="l14"> Completed+Invoiced</div>
					</div>

					<!-- Job Requirement "2-man team" - dumbbell-like icon -->
					<div class="iconrow">
					    <!--  <input style="float:left" type="checkbox" id="select-15" name="inst-team" value="t15" checked="checked">-->
						<div class="box-label" id="l15"> 2-Man Crew</div>
					</div>

					<!-- Job Requirement "100-ft Crane" - Triangle Crane icon -->
					<div class="iconrow">
						<!-- <input style="float:left" type="checkbox" id="select-16" name="inst-crane" value="t16" checked="checked">-->
						<div class="box-label" id="l16"> 100ft Crane</div>
					</div>

					<!-- Job Requirement "Parts Needed" - Barcode icon -->
					<div class="iconrow">
						<!-- <input style="float:left" type="checkbox" id="select-17" name="inst-parts" value="t17" checked="checked">  -->             
						<div class="box-label" id="l17"> Awaiting Parts</div>      
					</div>
			  </div><!--/row-->

			  <div class="p row">
				<span style="padding-top:12px;float:left;color:#0f8040">Permit Status: </span>
				 <div class="iconrow">
					<!--<input style="float:left" type="checkbox" id="select-18" name="perm-info" value="t18" checked="checked">-->
					<div class="box-label" id="l18"> Permit Info</div>
				</div>

				<div class="iconrow">
					<!--<input style="float:left" type="checkbox" id="select-18" name="perm-info" value="t19" checked="checked">-->
					<div class="box-label" id="l19"> Permit Info</div>
				</div>

				<!-- Permit Inspection Approved - Green Eye icon -->
				<div class="iconrow">
					<!-- <input style="float:left" type="checkbox" id="select-19" name="perm-insp-req" value="t20" checked="checked">-->
					<div class="box-label" id="l20"> Inspection Required </div>
				</div>

				<!-- Permit Info Needed - Question-mark icon -->
				<div class="iconrow">          	
					<!-- <input style="float:left" type="checkbox" id="select-20" name="perm-insp-appr" value="t21" checked="checked">-->
					<div class="box-label" id="l21"> Inspection Approved</div>
				</div>    


				<!-- Permit Approved or Not Required - Solid Green Square icon -->
			    <!--  <div class="iconrow">
				   <!--   <input style="float:left" type="checkbox" id="select-21" name="ic-p-appr" value="t22" checked="checked">
					<div class="box-label" id="l20"> </div>
				</div>-->
				<!-- Permit Inspection Required - Red Eye icon -->
			    <!--  <div class="iconrow">          	
					<!-- <input style="float:left" type="checkbox" id="select-11" name="hold" value="t11" checked="checked">
					<div class="box-label" id="l16">On Hold</div>
				</div>-->

			    <!-- Permit Required - Half Green Square icon 
				<div class="iconrow">
				    <!--  <input type="checkbox" id="select-21" name="perm-req" value="t21" checked="checked">
					<div class="box-label hold" id="l21"> Permit Required</div> 
				</div>-->

			  </div><!--/row, permitting checkboxes-->

			
			 </div><!--container-fluid for checkboxes-->
		 </div><!--/col-8 for job-assignment checkboxes-->
		
		
	   </div><!--/row, overdue jobs and job assignment selector columns-->
	</div><!--/#icons styling-->
          <br/>
          <div class="row">
           	<div class="col-md-10">
           	<div class="pull-right"> 
			
			
          		<button class="smBtn" onclick="teamsShowAll()">Show All</button> &nbsp;
			
          		<button class="smBtn" onclick="teamsHideAll()">Hide All</button> &nbsp;	
         		
          		<button class="smBtn" id="btnPrint">Print</button> &nbsp;
          	
				</div><!--/bootstrap's pull-right-->
			</div><!--/col-md-9, button group-->
			<div class="col-md-2">&nbsp; <!-- right gutter space--></div>
			
  </div><!--/row, buttons-->



</div><!--/container-fluid, bootstrap4-->
<div class="content-row" id="message">
</div>

<div id="calWrap" class="clearfix">
	<div id="topHeaders">
        <!-- <div class="row">
               <div class="btnPrev"><a href="#" onClick="prev('yr')"><img  id="prevYear" src="assets/prev-yr.png"></a></div>       
             
               <div class="btnNext" ><a href="#" onClick="next('yr')"><img id="prevYear" src="assets/nex-yr.png"></a></div>     
          </div> -->
          <div class="calRow">
             <div id="btnPrev"><img  id="prevMonth" src="assets/prev-mo.png"></div>              
              <div style="width:49.5%; display:inline-block; text-align:center; margin: 0px; box-sizing:border-box;"><span class="cursive" id="mo" oridnal=""><!-- e.g., ordinal="12" for december --></span> <span class="year cursive" id="yr" ordinal=""><!-- e.g., 2016, etc --></span></div>
              <div id="btnNext" ><img id="nextMonth" src="assets/nex-mo.png"></div>             
          </div>
     </div><!--end topHeaders-->
         <div id="headerDays">
              <div class="calCol morPad bold weekend">SUN</div>          
              <div class="calCol morPad bold">MON</div>
              <div class="calCol morPad bold">TUE</div>
              <div class="calCol morPad bold">WED</div>          
              <div class="calCol morPad bold">THU</div>
              <div class="calCol morPad bold">FRI</div>
              <div class="calCol morPad bold weekend">SAT</div>
         </div>
         <div id="weeks">
           <!-- js builds the rows as month requires 
             <div class"row" id="row1">
             	<div class="date" ordinal=""></div>
               <br/>content is entered here
             </div>
             <div id="row1"></div>
             <div id="row1"></div>
             <div id="row1"></div>
             <div id="row1"></div> 
           -->
         </div>
         <div id="calFooter"> 2015 - <?php echo date('Y') ?> &copy; Custom Sign Center, Inc. -- All Rights Reserved.</div>     
	<div class="blocker hide">
     	<div id="modal" class="modal">
          <button class="smBtn" onclick="modalClose(this)">Close</button>
          <span class="addNewLine" onclick="addNewLine(this, modal)"> + </span>
          </div>     
	</div>
     <img class="hide" id="wait" src="assets/preloader_blue.png" />
</div>

<script src="assets/clipboard.min.js" type="text/javascript" charset="utf-8"></script>
<!--<script src="assets/pickadate.js-3.5.6/legacy.js" type="text/javascript" charset="utf-8"></script>-->
<script type="text/javascript">
	/* global scope vars */
	var weeksDOM = $("#weeks");
	var headerYr = $("#headerYr");
	var curCompany = '';
	var timeoutID;
	var originalContent='';
	var content;
	var job;
	var curMonthCounter; //prev/next calendar month counting : increment/decrement
	
	var year;
	var month; //integer
	var monthName;
	var theDate;
	var todayOrdinalCell; //integer of the cell count for today's date.
	var changes=[];
	var responseMonth; //the most recent month html / data sent by php
	//relative pos html construct for claiming job entries.	
	var 	claimJobMenu;
	 
	var listElements = []; //all the list elements that hold job entries for showall/hideall. listsIntoObjects(){ listElements.push(liItem)} 
	var boxIDs = ['t0','t1','t2','t3','t4','t5','t6','t7','t8','t9','t10','unassigned'];
	
	var monthName = ["OccupyZeroPosition-PlaceHolder","January","February","March","April","May","June","July","August","September","October","November","December"];
	/*var jobClaimHTML = 
	'<div id="jobClaimMenu" class="hide">'+
	    '<p id="close" onclick="claimJob(0, this)" style="text-align:right;color:red">x Close</p>'+
	    '<p id="start" onclick="claimJob(1, this)">Start </p>'+
	    '<p id="continue" onclick="claimJob(2, this)" >Continue </p>'+
	    '<p id="complete" onclick="claimJob(3, this)">Completed</p>'+
	    '</div>';
	    */

	var dateParts;
	var teamHTML='';
	var teamAssignment = [
			"Team 1",
			"Team 2",
			"Team 3",
			"Team 4",
			"Team 5",
			"Team 6",
			"Team 7",
			"Team 8"			
			];
	var justReloaded = 1;
	
	var overdueJobs = {};		

function clearAlert() {
  window.clearTimeout(timeoutID);
}

$(document).ready(function (){
	
    
	/*disable back/forward page navigation of the browser*/
	history.pushState(null, null, document.title);
	 window.addEventListener('popstate', function () {
		history.pushState(null, null, document.title);
	 });	
	
	var today = new Date(); //date obj
	var cDate = today.getDate(); //current date.
    	var m = today.getMonth()+1; //month is zero-based (+1)	
    	var y = today.getFullYear();
	// 01_31_2016	for hiddne POST field.
	var cD = m+"_"+cDate+"_"+y;
	//display the date 01/31/2016
	var formatedDate = m+"/"+cDate+"/"+y;
	$("#curDate").html(cD);
	$("#date").html(formatedDate);	
	
	//get the time:
      var hours = today.getHours();
      var minutes = today.getMinutes();
	 if (minutes.length < 2){
		 minutes = "0"+minutes;
	 }
	 //no leading zeros for minutes less than 10.
	 //fix:
	  if(minutes < 10){
		 minutes = "0"+minutes;
	 }	 
	 
      var period = "AM";
      if (hours > 12) {
         period = "PM"
      }
      else {
         period = "AM";
     }
	var cTime = hours+":"+minutes+" "+period;
	$("#curTime").html(cTime);


	var weekRows = $( "#weeks .row" ); //each row
	//dispay the cal from xml when page first loads
	
	
$('#companyCalendar').on('change', function() {
  		//$("#company" ).val( this.value ); // or $(this).val()		
		justReloaded = 1;
		curCompany=$( "#companyCalendar option:selected" ).val();
		$("#pageTitle").html(curCompany + " WIP Calendar");
		teamNamesHTML();		
		loadCalendar( curCompany,-1,-1 ); //load calendar calls addListenersToDom func.
		//addListenersToDom("true");		
		
		/*if(curCompany !== "Custom Sign Center"){
			alert('Planned Update: Calendar Jobs Will Change for Each Company.');
		}*/
	});
	
	
	
	curCompany=$( "#companyCalendar option:selected" ).val();
	loadCalendar(curCompany,-1,-1);
	
	$("#pageTitle").html(curCompany + " WIP Calendar");
	
	
	
	
	$("#btnPrev").on('click', function(){
		
		displayNewMonth('prev');
		
	});
	
	
	$("#btnNext").on('click', function(){		
	
			displayNewMonth('next');
		
	});
	
	//printing calendar
  $("#btnPrint").on("click", function () { 
 		cleanCalendarLayout();
		printWindow();
          
   });
	
	
	

	/*capture some keyboard keys and set to desired behaviors inside the .edit container*/

      
	 //add clearfix class to wrappers to hold floats on a single line.		
	 var floatWraps = ['#headerDays','.row'];
	 
	 $(floatWraps).each(function(i,el){
		 $('#print ' + el ).addClass("clearfix");
	 });
		
	
	
  
   //assign current company hmtl for the team names
   teamNamesHTML();  
   timeoutID = window.setTimeout(addListenersToDom, 500);	
   
}); // doc ready.

//callers set status to 'start' or 'end';
function wait(status){
	if(status=='start') { $( "#wait" ).removeClass( "hide" ); }
	else { $( "#wait" ).addClass( "hide" ); }
}

function addListenersToDom(showTeamsBool = "true")
{
	 var editboxes = document.getElementsByClassName('edit');	
	  $.each(editboxes, function(i, elem){		 
		 
		listsIntoObjects(elem,i);
	 });	 
	 
	 
	 // add listeners to the checkboxes to select the team entries to show or hide
	 // array of inputs - "toggle show / hide" input checkboxes array
	 var checkboxes = $('#teamSelection').find('input');	 
	 
	 $(checkboxes).each(function(i,box){
		 //change listener for each checkbox
		 $(box).on("change", function() {
			 //alert('Checkbox status changed!');
			 //which box changed? Answ = this
			 var chkBoxName = $(this).attr('name');
			 var chkBoxId = $(this).attr('id');
			// alert('the checkbox id is ' + chkBoxId);
			 
			 if( $(this).is(':checked') ) {	
			 	// we need to show these if they are hidden				
				// find each li in the DOM with a class matching the checkbox's chkBoxName.
			
				for( var i = 0; listElements.length > i; i++ ) 
		 		{	
				    if($(listElements[i]).hasClass(chkBoxName))				  		
				    {
					    $(listElements[i]).removeClass("hide");
				    } 							
				 }//end for				
			 }//end is:checked
			 else //change is an uncheck (so hide it)
			 {
				for( var i = 0; listElements.length > i; i++ ) 
		 		{	
				    if($(listElements[i]).hasClass(chkBoxName))				  		
				    {
					    $(listElements[i]).addClass("hide");
				    }		
			 	}
			 }
		 });		 
	 });
	 
	// dbl click any LI entry to claim the job; redirect user to the timeclock app
	// this construct is faster than conventional for loop and $.each
	/*var len = editboxes.length, x=0;
	for(x;len>x;x++){	
		editboxes[x].addEventListener("click", childClicked, false);
		//listElements[x].addEventListener("dblclick", getClickPosition, false);		
	}*/
	 //remove hide class from any hidden LI elements with a new loading of the page.
	 if(justReloaded === 1)
	 {	//reset the toggling variable to false
		 justReloaded = 0;
		 onloadSetOverdueDisplay();		 		 
	 }
	
	/*   //hide the first and last div in each .row (sundays and saturday columns)
    var eachMonth =  $("#weeks").find(".month");
    $(eachMonth).each(function(i,el)
    {	 
	   var $monthRow = $(el).find(".row");	   
	   $($monthRow).each(function(i2, row){
		   var div1 = $(row).children("div:eq( 0 )");
		   var div2 = $(row).children("div:eq( 6 )");
		  //DON'T HIDE WEEKENDS ANYMORE
		   //$(div1).addClass("hide");
		  // $(div2).addClass("hide");
	   });	  
    });	
    */
	if(showTeamsBool === "true"){
    		teamsShowAll();  	
	}
}

// assign each lineEntry LI to the global listElements Obj
function listsIntoObjects(eachUL,i){ 
	//convenience global obj to hold all job entry LI's on the page; used for show specific jobs by team
    var liObj = $(eachUL).find("li");	
    $(liObj).each(function(ct, liItem) {
	    listElements.push(liItem);
	    //console.log('list number' +ct+ ' found for UL number '+ i);
    });
    
   
	
}
//handlers for each child LI or P inside the UL or DIV
//e is the clickevent for the parent (aka e.target).  The child clicked is e.currentTarget.
/*function childClicked(e){
	//insert the html opt menu in the p clicked.	
  var menus = $(document).find("#jobClaimMenu");
  if(menus.length){
	  $(menus).each(function(i,m){
		  $(m).remove();
	  }); 
  }
  
	$(e.target).append(jobClaimHTML);
	$("#jobClaimMenu").removeClass('hide');
	job = $(e.target).children('span').text();	
	 console.log(job);	  	
	 e.stopPropagation();
}
*/


/*
function getClickPosition(e) {
    var parentPosition = getPosition(e.currentTarget); 
    var xPosition = e.clientX - parentPosition.x - (claimJobMenu.clientWidth / 2);
    var yPosition = e.clientY - parentPosition.y - (claimJobMenu.clientHeight / 2);
    $(claimJobMenu).removeClass('hide');
    claimJobMenu.style.left = xPosition + "px";
    claimJobMenu.style.top = yPosition + "px";
}

//pop up options menu needs a position next to the click event (the LI obj)
// helper function to get an element's exact position
function getPosition(el) {
  var xPosition = 0;
  var yPosition = 0;
 
  while (el) {
    if (el.tagName == "BODY") {
      // deal with browser quirks with body/window/document and page scroll
      var xScrollPos = el.scrollLeft || document.documentElement.scrollLeft;
      var yScrollPos = el.scrollTop || document.documentElement.scrollTop;
 
      xPosition += (el.offsetLeft - xScrollPos + el.clientLeft);
      yPosition += (el.offsetTop - yScrollPos + el.clientTop);
    } else {
      xPosition += (el.offsetLeft - el.scrollLeft + el.clientLeft);
      yPosition += (el.offsetTop - el.scrollTop + el.clientTop);
    }
 
    el = el.offsetParent;
  }
  return {
    x: xPosition,
    y: yPosition
  };
}

*/
	

//company calendar to load, month to show, year to show (for next month, next year navigation)
/*php returns assoc array  (ex)
    [year] => 2016
    [activeMonthName] => June
    [activeMonthNumber] => 6
    [html] => (all the html of the calendar cells 
 */
function loadCalendar(co, m, y){
	//console.log('load calendar func called. CurCompany is: '+ co['companyCalendar']);
	//reset the overdue jobs obj to empty.
	overdueJobs = {};
	$("#OverDueJobsList").html('');
	if(typeof co === 'undefined') { co = curCompany;}
	wait('start');
	var today = new Date(); //date obj
	var cDate = today.getDate(); //current date.
	
	//if month and yr not passed in...
	if(m<0){//set month to cur month		
		m = today.getMonth()+1; //month is zero-based (+1)
	}
	if(y<0){//set year to cur year .. by default, load cur yr on first load.			
		y = today.getFullYear();		
	}
	
	
	//date = current date to highlight, month=req mo, year=req yr.
	var data = {"content":'',"year":y,"month":m,"theDate":cDate,"company":co,"method":"display"}; 
	
	$.ajax({	
		  url : "classes/calendar.php",
		  type: "POST",
		  data : data,
		  dataType:"json",
	   success: function(respData, textStatus, jqXHR)
	   {
		   wait('end');
		 //debug:
		 /* console.log('respData was: ' + respData);*/
		  theDate = respData.theDate;
		  year = respData.year;
		  month = respData.activeMonthNumber;
		  curMonthCounter = month;
		  //monthName = respData.activeMonthName;	
		 
		  todayOrdinalCell = respData.activeOrdinalCell;
		 $("#weeks").html(respData.html);
		 //for "undo all" operations, preserve copy of html
		 var respDataHtml = toString(respData.html);
		 $("#yr").html(year);
		 $("#yr").attr('ordinal', year);
		 $("#mo").html(monthName[month]);		 
		 $("#mo").attr('ordinal', month);
		 //the company the user has access rights for:
		 // "developer" = no user session; user is within the /dev directory. No 'admin' login blocking applies.
		 userCompany = respData.userCompany;
		 //$(".month").attr('yr', year);	 
		 
	   },
	   error: function (jqXHR, textStatus, errorThrown)
	   {
		  wait('end');
	   }	
  	});
	timeoutID = window.setTimeout(addListenersToDom, 300);	
	justReloaded = 1;
}


function giveNotice(message){
	
	$( "div#message" ).fadeIn( 'slow', function(){
	    $( "div#message" ).css("display", "inherit");
	    $( "div#message" ).css("padding", "12px");
	    $( "div#message" ).css("height", "auto");
	    $( "div#message" ).html("<p>"+message+"</p>");
	}).delay( 1600 ).fadeOut('slow' );
	$( "div#message" ).css("display", "none");
	$( "div#message" ).css("padding", "0px");
	$( "div#message" ).css("height", "0px");
}


		
function openModal(modalContent){		
		$('#modalWindow').html(modalContent);
		$('#modalWindow').removeClass('.hide');		
		$('#modalWindow').children('#modal')
		
}
function closeModal(){
	$('#modalWindow').find('.context-style').removeClass('context-style');
	$('#modalWindow').html('');
	$('#modalWindow').html('<div id="modal" class="modal"><button class="smBtn" onclick="closeModal(this)">Close</button></div>');   
	$('#modalWindow').addClass('.hide');
}
	

function teamsShowAll(){
	
	$(listElements).each(function(i,entry)
	{
		$(entry).removeClass('hide');
	});
	
	var TeamChkBoxes = $("#teamSelection").find("input");
	$(TeamChkBoxes).each(function(x,box)
	{
		
			$(box).prop("checked", true);
		
	});
}
function teamsHideAll(){
	console.log("teamsHIDEall loaded!");
	//this.preventDefault();
	//alert('hide called');
	$(listElements).each(function(x,listItem)
	{
		if( $(listItem).parent().hasClass('edit') ){			
			$(listItem).addClass('hide');
		}
	});	
	
	//alert ("teams hide all has been called");
	var TeamChkBoxes = $("#teamSelection").find("input");
	//var lengthOf = TeamChkBoxes; 
	//alert("length is " +lengthOf);
	$(TeamChkBoxes).each(function(d,cBox)
	{
		//if($(cBox).is("checked")){
			//alert("trying to uncheck a box");
			$(cBox).prop('checked', false);
		//}
	});
}

//back and forward through the month navigation
function displayNewMonth(action)
{
	wait('start');
	var allMonths = $(".month");
	
	if(action==='next') //display next month
	{
		// parseInt(month); //global cur mo as string (e.g. "7" for July). Convert to int. for math.		
		// Check 1st if there is HTML available for job listings for the next month being requested
		// if ! class="month" ordinal="12" (if the DOM does not find that attr == to month+1 (12), 
		// then giveNotice that there is no job scheduled in that month yet.
		// +1 for next, -1 for previous month being requested.	 We need to track YEAR as well for multiyear navigation.	
				
		if( parseInt(month) < 12 ){ //stay on the same year.
			var nextMonth =  String(parseInt(month) + 1);
			//console.log('the month is not dec.');
			var validMonth = monthHTMLexists( allMonths, nextMonth, year ); //does next mo exist in the DOM?
					//console.log(validMonth,allMonths);
		
		   if( validMonth !== 'ok'){
			   giveNotice('<span style="color: #FF0000">There is No Job Data Stored for that Month.</span>');

			   wait('end'); //hide the animated processing graphic
			   return; //get outta town
		   }
			
			$(allMonths).each(function(i,mo){
				/*
				if($(mo).attr("ordinal") == month){ //this is the current month we want to hide.
					$(mo).addClass("hide");
				}*/
				 /* hide all instead.. except the one we are nav to */
				$(mo).addClass("hide");   
				
				if($(mo).attr("ordinal") == nextMonth && $(mo).attr("yr") == String(year) ){
					$(mo).removeClass("hide");
					month = String(nextMonth); //set the month var to the new month displayed.
					curMonthCounter = month;
					 //$("#mo").html(monthName);
					$("#mo").html(monthName[month]);
					$("#mo").attr("ordinal",month);
					$("#mo").removeClass("hide");					
				} 
					
			});
			wait('end'); //hide the animated processing graphic
			   return; //get outta town
			//loadCalendar(curCompany, month, year);
		} 
		else //the month is dec (12)
		{ //need to roll over to next yr
				
			
			//console.log('the month is dec and the year is ' + year);	
			//all .month, ordinal month to search DOM, yr attrib val to seach DOM		
			var validMonth = monthHTMLexists( allMonths, "1", String( parseInt(year) +1 ) ); //does next mo exist in the DOM for the year requested?
					
		   	if( validMonth !== 'ok'){
				//there is not a DOM element for that year/mo combination.			   	
			   	giveNotice('<span style="color: #FF0000">There is No Job Data Stored for that Month.</span>');
			  	wait('end'); //hide the animated processing graphic
			   	return; //get outta town
		   	} else {
				nextMonth = "1";
				year = String( parseInt(year) +1 );				
				$(allMonths).each(function(i,mo){				
				    /*if($(mo).attr("ordinal") == "12"){ //this is the current month we want to hide.
					    $(mo).addClass("hide");
				    }*/
				     /* hide all instead.. except the one we are nav to */
				    $(mo).addClass("hide");   
				    
				    if($(mo).attr("ordinal") == nextMonth && $(mo).attr("yr") == String(year)){
					    $(mo).removeClass("hide");
					    month = String(nextMonth); //set the month var to the new month displayed.
					    curMonthCounter = month;
						//$("#mo").html(monthName);
					    $("#mo").html(monthName[month]);
					    $("#mo").attr("ordinal",month);
					    $("#mo").removeClass("hide");	
					   $("#yr").html(year);
					   $("#yr").attr('ordinal', year);		 			 
					   //$(mo).attr('yr', year);						
				    } 					
			}); 			   						
			    wait('end');
			    return;
			}// else is a valid month/yr combo in the DOM
		}		
	} //if nav is "next" month
	else //action is to display the 'prev' month
	{		
		if( parseInt(month) > 1 ){
			//no need to roll back the year
			var nextMonth = parseInt(month) - 1;
			var validMonth = monthHTMLexists( allMonths, nextMonth, year ); 
			//does next mo exist in the DOM for on the year requested?					
		   	if( validMonth !== 'ok'){
				//there is not a DOM element for that year/mo combination.
				 
			     giveNotice('<span style="color: #FF0000">There is No Job Data Stored for that Month.</span>');
			     wait('end'); //hide the animated processing graphic
			     return; //get outta town
		   	} else {
			    
			    $(allMonths).each(function(i,mo){				
				    /*if($(mo).attr("ordinal") == month){ //this is the current month we want to hide.
					    $(mo).addClass("hide");
				    }*/
				     /* hide all instead.. except the one we are nav to */
				    $(mo).addClass("hide");   				
				    if($(mo).attr("ordinal") == String(nextMonth) && $(mo).attr("yr") == String(year)){
					    $(mo).removeClass("hide");					
					    curMonthCounter = month;
						//$("#mo").html(monthName);
					    $("#mo").html(monthName[nextMonth]);
					    $("#mo").attr("ordinal",nextMonth);
					    $("#mo").removeClass("hide");
				    }
			    });
			    month = String(nextMonth); //set the month var to the new month displayed.
			}//end else is validMonth
		} else if(parseInt(month) == 1) { //need to roll back to prev yr				
			
			//console.log('the month is dec and the year is ' + year);	
			//all .month, ordinal month to search DOM, yr attrib val to seach DOM	
			var prevYr = parseInt(year) -1;	
			//console.log("prevYr is " + prevYr);
			var validMonth = monthHTMLexists( allMonths, "12", prevYr ); 
			//does next mo exist in the DOM for the prior year?					
		   	if( validMonth !== 'ok'){
				//there is not a DOM element for that year/mo combination.				 
			     giveNotice('<span style="color: #FF0000">There is No Job Data Stored for that Month.</span>');
			     wait('end'); //hide the animated processing graphic
			     return; //get outta town
		   	} else {			
			
			var nextMonth = "12";
				year = String( parseInt(year) -1 );				
				$(allMonths).each(function(i,mo){				
				    /*if($(mo).attr("ordinal") == "12"){ //this is the current month we want to hide.
					    $(mo).addClass("hide");
				    }*/
				     /* hide all instead.. except the one we are nav to */
				    $(mo).addClass("hide");  
				});
				 $(allMonths).each(function(i,mo){	   
				    if($(mo).attr("yr") == prevYr && $(mo).attr("ordinal") == nextMonth ){
					    $(mo).removeClass("hide");
					    month = String(nextMonth); //set the month var to the new month displayed.
					    curMonthCounter = month;
						//$("#mo").html(monthName);
					    $("#mo").html(monthName[month]);
					   // $("#mo").attr("ordinal",month);
					   // $("#mo").removeClass("hide");	
					    $("#yr").html(year);
					    //$("#yr").attr('ordinal', year);		 			 
					   //$(mo).attr('yr', year);						
				    } 					
			}); 				
			
		}//else validmonth ok
		
	}//else try roll back one year from month 1 to 12
	wait('end');
	var func = addListenersToDom("false");
	window.setTimeout(func, 100);
	
}//else prev
}

// verify DOM contains HTML of job listings for a user navigated month
// allMonths is all DOM els of class "month"
// action is "next" or "prev" nav request 
//the month to check
function monthHTMLexists(allMonths,checkMonth,yr){
	
	 var result = false;	 
	// TODO: Check allMonths is not null, empty
	
	checkMonth = String(checkMonth);	
	 
	//console.log('checkMonth is: ' + checkMonth + ' and yr is: ' + yr );
	$(allMonths).each(function(i,el){
		//get the value from the element's "ordinal" attribute that matches the
		//requested month to navigate to.  If it doesn't exist in DOM, then return false;	
		//console.log("EACH has fired!");
		if( $(el).attr("yr") === String(yr) ){
			if(  $(el).attr("ordinal")  === String(checkMonth) ){			
			  //e.g. el looks like: <div class="month" ordinal="10" yr="2016">
			  //we found a DOM el with the requested month and having the same (current) year.
			  //console.log($(el).attr("ordinal"));
			  result = 'ok';	
			}
		} 
	});
	
	return result; //if undefined, there is not a DOM for that month+yr
		
}


	
	
function claimJob(opt,obj){
	//options 0=close, 1 = start claim, 2 = continue claim, 3=complete (clockout)
	
	
	if(String(opt) == "0"){
		$(obj).parent("#jobClaimMenu").remove;
	} else {
		var uriParam = "&job="+job;	
		var answer = confirm("Want to clock in now for job "+job+"?");
		if (answer==true){
		window.location.href = "https://customsigncenter.com/secure/timeclock.php?"+uriParam;
		}
		
	}
}
function teamNamesHTML(){	
	
	
	var li0 = '<li id="';
	var liCl = '" Class="';
	var li1 = '" onclick="jobAssignment(';
	var li2 = ', this)" option="';
	var li3 = '">';
	var li4 = '</li>';
	
	
	if(curCompany == "Custom Sign Center")
	{	
		
		assignLabels = [
			'RobertC',
			'DennisH',
			'',
			'',
			'Install',
			'SubInstall',
			'CSC Transp',
			'Shipping',
			'Cust PU',
			' UPS', 
			' Unassigned',
			' Service',
			' 2-Man',
			' 100ft Crane',
			' Part Needed',
			' Ready to Invoice',
			' Collect',
			' Info Needed',
			' Inspection Required',
			' Inspection Approved',						
			' Prmt Compl/Not Required',
			'Overdue'
		];	
		
		teamAssignment = [
		
				li0 + 't1' + liCl +'1' + li1 + '1' +li2 + '1' + li3 + assignLabels[0] + li4,
		
				/*'<div class="tooltip">Bob-Michael<span class="tooltiptext">Contact Info Can Be Displayed Here!</span></div></li>',*/
		
				li0 + 't2' + liCl +'2' + li1 + '2' +li2 + '2' + li3 + assignLabels[1] + li4,
		
				'',
				'',
				
				
				li0 + 't5' + liCl +'5' + li1 + '5' +li2 + '5' + li3 + assignLabels[4] + li4,
		
			
				li0 + 't6' + liCl +'6' + li1 + '6' +li2 + '6' + li3 + assignLabels[5] + li4,
				li0 + 't7' + liCl +'7' + li1 + '7' +li2 + '7' + li3 + assignLabels[6] + li4,
				li0 + 't8' + liCl +'8' + li1 + '8' +li2 + '8' + li3 + assignLabels[7] + li4,
				li0 + 't9' + liCl +'9' + li1 + '9' +li2 + '9' + li3 + assignLabels[8] + li4,

				// iconmoon elements
				li0 + 'ic-ups' + liCl +'na' + li1 + '\'ups\'' +li2 + 'ups' + li3 + '<i class="ic-ups"></i>' + assignLabels[9] + li4,			
				li0 + 'unassigned' + liCl + 'unassigned' +  li1 + '\'unas\'' +li2 + 'unas' + li3 + '<i class="ic-flag"></i>' + assignLabels[10] + li4,
				li0 + 'ic-i-ret-trip' + li1 + '\'trip\'' + li2 + 'trip' + li3 + '<i class="ic-i-ret-trip"></i>' + assignLabels[11] + li4,
				li0 + '13' + liCl + 'ic-users' + li1 + '\'crew\'' + li2 + 'crew' + li3 + assignLabels[12] + li4,
				li0 + '14' + li1 + '\'crane\'' + li2 + 'crane' + li3 + '<i class="ic-i-crane"></i>' + assignLabels[13] + li4,
				li0 + '15' + liCl + 'ic-cog' + li1 + '\'parts\'' + li2 + 'parts' + li3 + assignLabels[14] + li4,
				li0 + '16' + li1 + '\'comp\'' + li2 + 'comp' + li3 + '<i class="ic-i-comp-alt"></i>' + assignLabels[15] + li4,
				li0 + '17' + li1 + '\'inv\'' + li2 + 'inv' + li3 + '<i class="ic-i-comp-inv"></i>' + assignLabels[16] + li4,
				li0 + '18' + li1 + '\'info\'' + li2 + 'info' + li3 + '<i class="ic-p-inf"></i>' + assignLabels[17] + li4,			
				li0 + '19' + li1 + '\'inspr\'' + li2 + 'inspr' + li3 + '<i class="ic-p-insp-req"></i>' + assignLabels[18] + li4,
				li0 + '20' + li1 + '\'inspa\'' + li2 + 'inspa' + li3 + '<i class="ic-p-insp-appr"></i>' + assignLabels[19] + li4,
				li0 + '21' + li1 + '\'pappr\'' + li2 + 'pappr' + li3 + '<i class="ic-p-appr"></i>' + assignLabels[20] + li4,
				//css style only, no icon for Overdue (class .due)
				li0 + 'due' + liCl +'due' + li1 + '21' +li2 + '21' + li3 + assignLabels[21] + li4
			];	
	
		
	} 
	else if(curCompany == "MarionOutdoor")
	{		
		
		
		assignLabels = [
			'ChadL',
			'CurtisS',			
			'DavidS',
			'',
			'Install', 
				'', 
			'Rec CSC Transp',
			'Rec Shipping',
			'Cust PU',
			' Rec UPS',
			' Unassigned',
			' Service',
			' 2-Man',
			' 100ft Crane',
			' Part Needed',
			' Ready to Invoice',
			' Collect',
			' Info Needed',
			' Inspection Required',
			' Inspection Approved',						
			' Prmt Compl/Not Required'				
		];	
		
		teamAssignment = [
				li0 + 't1' + liCl +'1' + li1 + '1' +li2 + '1' + li3 + assignLabels[0] + li4,
				li0 + 't2' + liCl +'2' + li1 + '2' +li2 + '2' + li3 + assignLabels[1] + li4,	
				
				li0 + 't3' + liCl +'3' + li1 + '3' +li2 + '3' + li3 + assignLabels[2] + li4,		
				//li0 + 't4' + liCl +'4' + li1 + '4' +li2 + '4' + li3 + assignLabels[3] + li4,	
				'',
				li0 + 't5' + liCl +'5' + li1 + '5' +li2 + '5' + li3 + assignLabels[4] + li4,				
			
				//li0 + 't6' + liCl +'6' + li1 + '6' +li2 + '6' + li3 + assignLabels[5] + li4,
				'',
				li0 + 't7' + liCl +'7' + li1 + '7' +li2 + '7' + li3 + assignLabels[6] + li4,
				li0 + 't8' + liCl +'8' + li1 + '8' +li2 + '8' + li3 + assignLabels[7] + li4,
				li0 + 't9' + liCl +'9' + li1 + '9' +li2 + '9' + li3 + assignLabels[8] + li4,
				// iconmoon elements
				li0 + 'ic-ups' + liCl +'t5' + li1 + '\'ups\'' +li2 + 'ups' + li3 + '<i class="ic-ups"></i>' + assignLabels[9] + li4,			
				li0 + 'unassigned' + liCl + 'unassigned' +  li1 + '\'unas\'' +li2 + 'unas' + li3 + '<i class="ic-flag"></i>' + assignLabels[10] + li4,
				li0 + 'ic-i-ret-trip' + li1 + '\'trip\'' + li2 + 'trip' + li3 + '<i class="ic-i-ret-trip"></i>' + assignLabels[11] + li4,
				li0 + '13' + liCl + 'ic-users' + li1 + '\'crew\'' + li2 + 'crew' + li3 + assignLabels[12] + li4,
				li0 + '14' + li1 + '\'crane\'' + li2 + 'crane' + li3 + '<i class="ic-i-crane"></i>' + assignLabels[13] + li4,
				li0 + '15' + liCl + 'ic-cog' + li1 + '\'parts\'' + li2 + 'parts' + li3 + assignLabels[14] + li4,
				li0 + '16' + li1 + '\'comp\'' + li2 + 'comp' + li3 + '<i class="ic-i-comp-alt"></i>' + assignLabels[15] + li4,
				li0 + '17' + li1 + '\'inv\'' + li2 + 'inv' + li3 + '<i class="ic-i-comp-inv"></i>' + assignLabels[16] + li4,
				li0 + '18' + li1 + '\'info\'' + li2 + 'info' + li3 + '<i class="ic-p-inf"></i>' + assignLabels[17] + li4,			
				li0 + '19' + li1 + '\'inspr\'' + li2 + 'inspr' + li3 + '<i class="ic-p-insp-req"></i>' + assignLabels[18] + li4,
				li0 + '20' + li1 + '\'inspa\'' + li2 + 'inspa' + li3 + '<i class="ic-p-insp-appr"></i>' + assignLabels[19] + li4,
				li0 + '21' + li1 + '\'pappr\'' + li2 + 'pappr' + li3 + '<i class="ic-p-appr"></i>' + assignLabels[20] + li4				
			];	
		
	
	}
	else if(curCompany == "Marion Signs")
	{		
		
			assignLabels = [
				
			'ChadL',
			'CurtisS',			
			'DavidS',
			'',//team reserved
			'Install', //unassigned
				'', //subinstall
			'Rec CSC Transp',
			'Rec Shipping',
			'Cust PU',
			' Rec UPS',
			' Unassigned',
			' Service',
			' 2-Man',
			' 100ft Crane',
			' Part Needed',
			' Ready to Invoice',
			' Collect',
			' Info Needed',
			' Inspection Required',
			' Inspection Approved',						
			' Prmt Compl/Not Required'
				
		
		];
		
		teamAssignment = [
				li0 + 't1' + liCl +'1' + li1 + '1' +li2 + '1' + li3 + assignLabels[0] + li4,
				li0 + 't2' + liCl +'2' + li1 + '2' +li2 + '2' + li3 + assignLabels[1] + li4,	
				
				li0 + 't3' + liCl +'3' + li1 + '3' +li2 + '3' + li3 + assignLabels[2] + li4,		
				//li0 + 't4' + liCl +'4' + li1 + '4' +li2 + '4' + li3 + assignLabels[3] + li4,	
				'',
				li0 + 't5' + liCl +'5' + li1 + '5' +li2 + '5' + li3 + assignLabels[4] + li4,				
			
				//li0 + 't6' + liCl +'6' + li1 + '6' +li2 + '6' + li3 + assignLabels[5] + li4,
				'',
				li0 + 't7' + liCl +'7' + li1 + '7' +li2 + '7' + li3 + assignLabels[6] + li4,
				li0 + 't8' + liCl +'8' + li1 + '8' +li2 + '8' + li3 + assignLabels[7] + li4,
				li0 + 't9' + liCl +'9' + li1 + '9' +li2 + '9' + li3 + assignLabels[8] + li4,
				// iconmoon elements
				li0 + 'ic-ups' + liCl +'t5' + li1 + '\'ups\'' +li2 + 'ups' + li3 + '<i class="ic-ups"></i>' + assignLabels[9] + li4,			
				li0 + 'unassigned' + liCl + 'unassigned' +  li1 + '\'unas\'' +li2 + 'unas' + li3 + '<i class="ic-flag"></i>' + assignLabels[10] + li4,
				li0 + 'ic-i-ret-trip' + li1 + '\'trip\'' + li2 + 'trip' + li3 + '<i class="ic-i-ret-trip"></i>' + assignLabels[11] + li4,
				li0 + '13' + liCl + 'ic-users' + li1 + '\'crew\'' + li2 + 'crew' + li3 + assignLabels[12] + li4,
				li0 + '14' + li1 + '\'crane\'' + li2 + 'crane' + li3 + '<i class="ic-i-crane"></i>' + assignLabels[13] + li4,
				li0 + '15' + liCl + 'ic-cog' + li1 + '\'parts\'' + li2 + 'parts' + li3 + assignLabels[14] + li4,
				li0 + '16' + li1 + '\'comp\'' + li2 + 'comp' + li3 + '<i class="ic-i-comp-alt"></i>' + assignLabels[15] + li4,
				li0 + '17' + li1 + '\'inv\'' + li2 + 'inv' + li3 + '<i class="ic-i-comp-inv"></i>' + assignLabels[16] + li4,
				li0 + '18' + li1 + '\'info\'' + li2 + 'info' + li3 + '<i class="ic-p-inf"></i>' + assignLabels[17] + li4,			
				li0 + '19' + li1 + '\'inspr\'' + li2 + 'inspr' + li3 + '<i class="ic-p-insp-req"></i>' + assignLabels[18] + li4,
				li0 + '20' + li1 + '\'inspa\'' + li2 + 'inspa' + li3 + '<i class="ic-p-insp-appr"></i>' + assignLabels[19] + li4,
				li0 + '21' + li1 + '\'pappr\'' + li2 + 'pappr' + li3 + '<i class="ic-p-appr"></i>' + assignLabels[20] + li4				
			];	
			
		
		
	}
	else if(curCompany == "Outdoor Images")
	{	
		assignLabels = [
			'ChadL',
			'',			
			'DavidS',
			'',
			'Install', 
				'', 
			'Rec CSC Transp',
			'Rec Shipping',
			'Cust PU',
			' Rec UPS',
			' Unassigned',
			' Service',
			' 2-Man',
			' 100ft Crane',
			' Part Needed',
			' Ready to Invoice',
			' Collect',
			' Info Needed',
			' Inspection Required',
			' Inspection Approved',						
			' Prmt Compl/Not Required'
			];
		
			teamAssignment = [
		
				li0 + 't1' + liCl +'1' + li1 + '1' +li2 + '1' + li3 + assignLabels[0] + li4,
				//li0 + 't2' + liCl +'2' + li1 + '2' +li2 + '2' + li3 + assignLabels[1] + li4,	
				'',
				li0 + 't3' + liCl +'3' + li1 + '3' +li2 + '3' + li3 + assignLabels[2] + li4,		
				//li0 + 't4' + liCl +'4' + li1 + '4' +li2 + '4' + li3 + assignLabels[3] + li4,	
				'',
				li0 + 't5' + liCl +'5' + li1 + '5' +li2 + '5' + li3 + assignLabels[4] + li4,				
			
				//li0 + 't6' + liCl +'6' + li1 + '6' +li2 + '6' + li3 + assignLabels[5] + li4,
				'',
				li0 + 't7' + liCl +'7' + li1 + '7' +li2 + '7' + li3 + assignLabels[6] + li4,
				li0 + 't8' + liCl +'8' + li1 + '8' +li2 + '8' + li3 + assignLabels[7] + li4,
				li0 + 't9' + liCl +'9' + li1 + '9' +li2 + '9' + li3 + assignLabels[8] + li4,
// iconmoon elements
				li0 + 'ic-ups' +  li1 + '\'ups\'' +li2 + 'ups' + li3 + '<i class="ic-ups"></i>' + assignLabels[9] + li4,			
				li0 + 'unassigned' + liCl + 'unassigned' +  li1 + '\'unas\'' +li2 + 'unas' + li3 + '<i class="ic-flag"></i>' + assignLabels[10] + li4,
				li0 + 'ic-i-ret-trip' + li1 + '\'trip\'' + li2 + 'trip' + li3 + '<i class="ic-i-ret-trip"></i>' + assignLabels[11] + li4,
				li0 + '13' + liCl + 'ic-users' + li1 + '\'crew\'' + li2 + 'crew' + li3 + assignLabels[12] + li4,
				li0 + '14' + li1 + '\'crane\'' + li2 + 'crane' + li3 + '<i class="ic-i-crane"></i>' + assignLabels[13] + li4,
				li0 + '15' + liCl + 'ic-cog' + li1 + '\'parts\'' + li2 + 'parts' + li3 + assignLabels[14] + li4,
				li0 + '16' + li1 + '\'comp\'' + li2 + 'comp' + li3 + '<i class="ic-i-comp-alt"></i>' + assignLabels[15] + li4,
				li0 + '17' + li1 + '\'inv\'' + li2 + 'inv' + li3 + '<i class="ic-i-comp-inv"></i>' + assignLabels[16] + li4,
				li0 + '18' + li1 + '\'info\'' + li2 + 'info' + li3 + '<i class="ic-p-inf"></i>' + assignLabels[17] + li4,			
				li0 + '19' + li1 + '\'inspr\'' + li2 + 'inspr' + li3 + '<i class="ic-p-insp-req"></i>' + assignLabels[18] + li4,
				li0 + '20' + li1 + '\'inspa\'' + li2 + 'inspa' + li3 + '<i class="ic-p-insp-appr"></i>' + assignLabels[19] + li4,
				li0 + '21' + li1 + '\'pappr\'' + li2 + 'pappr' + li3 + '<i class="ic-p-appr"></i>' + assignLabels[20] + li4				
			];	
	
	} else if(curCompany == "JG Signs")
	{	
		assignLabels = [
			'',
			'',			
			'',
			'',
			'Install', 
				'', 
			'Rec CSC Transp',
			'Rec Shipping',
			'Cust PU',
			' Rec UPS',
			' Unassigned',
			' Service',
			' 2-Man',
			' 100ft Crane',
			' Part Needed',
			' Ready to Invoice',
			' Collect',
			' Info Needed',
			' Inspection Required',
			' Inspection Approved',						
			' Prmt Compl/Not Required'
			];
		
			teamAssignment = [
		
				//li0 + 't1' + liCl +'1' + li1 + '1' +li2 + '1' + li3 + assignLabels[0] + li4,
				'',
				//li0 + 't2' + liCl +'2' + li1 + '2' +li2 + '2' + li3 + assignLabels[1] + li4,	
				'',
				//li0 + 't3' + liCl +'3' + li1 + '3' +li2 + '3' + li3 + assignLabels[2] + li4,	
				'',
				//li0 + 't4' + liCl +'4' + li1 + '4' +li2 + '4' + li3 + assignLabels[3] + li4,	
				'',
				li0 + 't5' + liCl +'5' + li1 + '5' +li2 + '5' + li3 + assignLabels[4] + li4,				
			
				//li0 + 't6' + liCl +'6' + li1 + '6' +li2 + '6' + li3 + assignLabels[5] + li4,
				'',
				li0 + 't7' + liCl +'7' + li1 + '7' +li2 + '7' + li3 + assignLabels[6] + li4,
				li0 + 't8' + liCl +'8' + li1 + '8' +li2 + '8' + li3 + assignLabels[7] + li4,
				li0 + 't9' + liCl +'9' + li1 + '9' +li2 + '9' + li3 + assignLabels[8] + li4,
				// iconmoon elements
				li0 + 'ic-ups' +  li1 + '\'ups\'' +li2 + 'ups' + li3 + '<i class="ic-ups"></i>' + assignLabels[9] + li4,			
				li0 + 'unassigned' + liCl + 'unassigned' +  li1 + '\'unas\'' +li2 + 'unas' + li3 + '<i class="ic-flag"></i>' + assignLabels[10] + li4,
				li0 + 'ic-i-ret-trip' + li1 + '\'trip\'' + li2 + 'trip' + li3 + '<i class="ic-i-ret-trip"></i>' + assignLabels[11] + li4,
				li0 + '13' + liCl + 'ic-users' + li1 + '\'crew\'' + li2 + 'crew' + li3 + assignLabels[12] + li4,
				li0 + '14' + li1 + '\'crane\'' + li2 + 'crane' + li3 + '<i class="ic-i-crane"></i>' + assignLabels[13] + li4,
				li0 + '15' + liCl + 'ic-cog' + li1 + '\'parts\'' + li2 + 'parts' + li3 + assignLabels[14] + li4,
				li0 + '16' + li1 + '\'comp\'' + li2 + 'comp' + li3 + '<i class="ic-i-comp-alt"></i>' + assignLabels[15] + li4,
				li0 + '17' + li1 + '\'inv\'' + li2 + 'inv' + li3 + '<i class="ic-i-comp-inv"></i>' + assignLabels[16] + li4,
				li0 + '18' + li1 + '\'info\'' + li2 + 'info' + li3 + '<i class="ic-p-inf"></i>' + assignLabels[17] + li4,			
				li0 + '19' + li1 + '\'inspr\'' + li2 + 'inspr' + li3 + '<i class="ic-p-insp-req"></i>' + assignLabels[18] + li4,
				li0 + '20' + li1 + '\'inspa\'' + li2 + 'inspa' + li3 + '<i class="ic-p-insp-appr"></i>' + assignLabels[19] + li4,
				li0 + '21' + li1 + '\'pappr\'' + li2 + 'pappr' + li3 + '<i class="ic-p-appr"></i>' + assignLabels[20] + li4				
			];	
	
	}
	else if(curCompany == "Boyer Signs")
	{	
		assignLabels = [
			'',
			'',			
			'',
			'',
			'Install', 
				'', 
			'Rec CSC Transp',
			'Rec Shipping',
			'Cust PU',
			' Rec UPS',
			' Unassigned',
			' Service',
			' 2-Man',
			' 100ft Crane',
			' Part Needed',
			' Ready to Invoice',
			' Collect',
			' Info Needed',
			' Inspection Required',
			' Inspection Approved',						
			' Prmt Compl/Not Required'
			];
		
			teamAssignment = [
		
				
				//li0 + 't1' + liCl +'1' + li1 + '1' +li2 + '1' + li3 + assignLabels[0] + li4,
				'',
				//li0 + 't2' + liCl +'2' + li1 + '2' +li2 + '2' + li3 + assignLabels[1] + li4,	
				'',
				//li0 + 't3' + liCl +'3' + li1 + '3' +li2 + '3' + li3 + assignLabels[2] + li4,	
				'',
				//li0 + 't4' + liCl +'4' + li1 + '4' +li2 + '4' + li3 + assignLabels[3] + li4,	
				'',
				li0 + 't5' + liCl +'5' + li1 + '5' +li2 + '5' + li3 + assignLabels[4] + li4,				
			
				//li0 + 't6' + liCl +'6' + li1 + '6' +li2 + '6' + li3 + assignLabels[5] + li4,
				'',
				li0 + 't7' + liCl +'7' + li1 + '7' +li2 + '7' + li3 + assignLabels[6] + li4,
				li0 + 't8' + liCl +'8' + li1 + '8' +li2 + '8' + li3 + assignLabels[7] + li4,
				li0 + 't9' + liCl +'9' + li1 + '9' +li2 + '9' + li3 + assignLabels[8] + li4,
				// iconmoon elements
				li0 + 'ic-ups' +  li1 + '\'ups\'' +li2 + 'ups' + li3 + '<i class="ic-ups"></i>' + assignLabels[9] + li4,			
				li0 + 'unassigned' + liCl + 'unassigned' +  li1 + '\'unas\'' +li2 + 'unas' + li3 + '<i class="ic-flag"></i>' + assignLabels[10] + li4,
				li0 + 'ic-i-ret-trip' + li1 + '\'trip\'' + li2 + 'trip' + li3 + '<i class="ic-i-ret-trip"></i>' + assignLabels[11] + li4,
				li0 + '13' + liCl + 'ic-users' + li1 + '\'crew\'' + li2 + 'crew' + li3 + assignLabels[12] + li4,
				li0 + '14' + li1 + '\'crane\'' + li2 + 'crane' + li3 + '<i class="ic-i-crane"></i>' + assignLabels[13] + li4,
				li0 + '15' + liCl + 'ic-cog' + li1 + '\'parts\'' + li2 + 'parts' + li3 + assignLabels[14] + li4,
				li0 + '16' + li1 + '\'comp\'' + li2 + 'comp' + li3 + '<i class="ic-i-comp-alt"></i>' + assignLabels[15] + li4,
				li0 + '17' + li1 + '\'inv\'' + li2 + 'inv' + li3 + '<i class="ic-i-comp-inv"></i>' + assignLabels[16] + li4,
				li0 + '18' + li1 + '\'info\'' + li2 + 'info' + li3 + '<i class="ic-p-inf"></i>' + assignLabels[17] + li4,			
				li0 + '19' + li1 + '\'inspr\'' + li2 + 'inspr' + li3 + '<i class="ic-p-insp-req"></i>' + assignLabels[18] + li4,
				li0 + '20' + li1 + '\'inspa\'' + li2 + 'inspa' + li3 + '<i class="ic-p-insp-appr"></i>' + assignLabels[19] + li4,
				li0 + '21' + li1 + '\'pappr\'' + li2 + 'pappr' + li3 + '<i class="ic-p-appr"></i>' + assignLabels[20] + li4				
			];	
	
	}
	
	
	
	
	
	$(teamAssignment).each(function(i,team){
			
		i++;
		var l = $("#l"+i);
		var lpar = $(l).parent('div.iconrow');
		
		if( team !== '' && team !== 'Overdue' ){
			//console.log(i + " will be a LIST")
			$(l).html(team);
			$(lpar).removeClass('hide');
			//$("#t"+(i-1)).html(team);
			//popup opt menu on r-clk of job entry	
		} else if( team == 'Overdue' ) { 
			$("#l21").parent('div.iconrow').removeClass('hide');
			$("#l21").html(team);
			i--;
			//alert("menu item is overdue and the iterator is " +i);
		
			
		} else {
			
			$(lpar).addClass('hide');
			
		}
		
	});
	
	
			
			
	
	
	var menuOptAssign = '';
	var menuOptJob = '';
	var menuOptPermt = '';
	
	//assignment menu with up to 12 options
	for($g=0; 11 >= $g; $g++){
		
		if(teamAssignment[$g] !== '' && $g !== 11){
			menuOptAssign += teamAssignment[$g];
		}
		else if($g == 11){
			//this would be the 1st item in the STATUS submenu, but needs to be Overdue item.
			menuOptAssign += teamAssignment[21];
		}
	}
	
	
	
	contextMenu = $('<div id="divContextMenu" style="display:none">'+ 
	'<input id="reschedule" type="text" placeholder="reschedule" />'+	
	'<div class="container"><nav class="navbar"><ul id="ulContextMenu">'+	
	    '<li id="t0" onclick="jobAssignment(0, this)" option="0" style="text-align:right;color:red">x Close</li>'+
	    '<li style="padding:12px 5px;color:#000000">ASSIGN<ul>'+	    
		menuOptAssign +
	    '</ul></li>'+
	    '<li style="padding:12px 5px"><span style="color:#236FBF">STATUS</span><ul>'+    
	    teamAssignment[11]+	
	    teamAssignment[12]+
	    teamAssignment[13]+	     
	    teamAssignment[14]+
         teamAssignment[15]+
	    teamAssignment[16]+ //Ready to Invoice
	    '</ul></li>'+
	    '<li style="padding:12px 5px"><span style="color:#007F16">PERMIT</span><ul style="color:#007F16">'+    
	    teamAssignment[17]+
	    teamAssignment[18]+
	    teamAssignment[19]+
	    teamAssignment[20]+
	    '</ul></li>'+
	    '<li style="padding:12px 5px" id="delete" class="delete" onclick="jobAssignment(11, this)" option="11">Delete Entry</li>'+
	'</ul></nav></div>');
	
}
	
	
	
	
function cleanCalendarLayout(){
	 $( "#pageTitle" ).clone().appendTo('#print');	
	 $( "span#date" ).clone().appendTo('#print');			
	 $( "span#curTime" ).clone().appendTo('#print');
	 $( "#icons" ).clone().appendTo('#print');
	 $( "span#mo" ).clone().appendTo('#print');
	 $( "span#yr" ).clone().appendTo('#print');
	 $( "#headerDays" ).clone().appendTo('#print');
	 $( ".month" ).clone().appendTo('#print');
}
	
	function printWindow(){
	$( "#print" ).removeClass( "hide" );
	 var printWindow = window.open('', '_blank', 'scrollbars=yes,resizable=yes,top=20,left=5,height=900,width=1200');
	 printWindow.document.write('<html><head><title>Print Calendar</title><link href="styles/print_1.css" media="all" rel="stylesheet" /> <link rel="stylesheet" href="assets/icomoon/style.css"><link rel="stylesheet" href="styles/bootstrap.min.css">');
	 
	 var $editULs = $("#print").find(".edit");
		
	//hide empty ul.edit -- this works, but the calendar does not really save any space
	//with the current layout used for printing.
	/*	$( $editULs ).each(function(i,ulEl){
			
			if( $(ulEl).find('li').length < 1){
				$(ulEl).parent('.date').addClass('hide');				
			}
			
		});
	*/
	
	$( $editULs ).each(function(i,ulEl){
		 
		 if( $(ulEl).find('li').length < 1){
			 $(ulEl).parent('.date').attr('style', 'border:none');				
		 }
		 
	 });
 
	 //add clearfix class to wrappers to hold floats on a single line.		
	 var floatWraps = ['#headerDays','.row'];
	 
	 $(floatWraps).each(function(i,el){
		 $('#print ' + el ).addClass("clearfix");
	 });
	 	 
	 printWindow.document.write('</head><body id="printBody">');
	 printWindow.document.write( $( "#print" ).html() );
	 printWindow.document.write('</body></html>');
	 printWindow.document.close();
	 printWindow.print(); 
	 $( "#print" ).html('');
	 $( "#print" ).addClass( "hide" );
	
}
	
	

	//the img icon button click = clickedObj
function modalOpen(clickedObj) {
	/*
	alert("Popup Editor is Being Improved.  Currently Disabled Pending Updates.");
	return;
	*/
	//console.log("modal Open Called");
	
	if( $('#srchResult').is(':visible') ) {
			
				$('#srchResult').addClass('hide');
				$( '#srchResult').empty();
	}
	
		
	//if there is an opened contextMenu popup, remove it first.
	if( $('#divContextMenu').is(":visible") ){
		$('#divContextMenu').remove();
		$(document).find('.context-style').removeClass('context-style');
	} 
	
	
	//obj clicked is the "edit" button within the date cell user wants to edit
	//if there is any modal content, remove it
	//$("#modal").html('');
	//get user-requested content for the new modal

	
	//to prevent duplication after closing the modal, 
	//must know NOT ONLY the modalId of the date cell
	//but also the month and year of the cell.
	var $m = $(clickedObj).closest(".month");
	modalContent = $(clickedObj).parents('.date').children('ul');	
	var numerMonth = $($m).attr("ordinal");
	//console.log("Ordinal as a month is "+ numerMonth);
	var numerYr = $($m).attr("yr");
	$(modalContent).attr('ordinal',numerMonth);
	$(modalContent).attr('yr',numerYr);
	var numerDate = $(modalContent).attr('modalid').replace('d','');
	//will result in, ex: <ul modalid="d3" ordinal="5" yr="2017">
	//in the modal div, for a date of May 3rd, 2017 from the calendar.
		
	//display the date in the modal popup.
	var cellDate = '<span class="cursive" style="float:right;font-size:17px;color:#8AC72D">' + numerMonth +'/'+ numerDate + '/' + numerYr + '</span>';
	    
	    

	
     $( modalContent ).clone(true, true).appendTo( "#modal" );	
	$("#modal").prepend(cellDate);
	
	$('.blocker').removeClass('hide');
	$('.blocker').css('opacity',0).animate({opacity: 1}, 10);	
	//required to run add listeners again to preserve the onclick editable events for lists
	addListenersToDom("false");
	
	
	
	
	
}

function modalClose(clickedObj)
{
	//obj clicked is a button withino the open #modal element
	//model el's content child has attr of 'modalid'.
	//that unique modalid is the same as the class name of the
	//original content wrapper.  so val = attr('modalid'); 
	//so original content wrapper in the DOM can be located as: '$(getElementsByClassName(val)).html()';
	
	//if contents of ul with attr modalid = (ex: 'd8') 
	//does not equal contents of the modal (i.e., modal contents edited by user),
	//then modalSave is called before close the modal window.
	
	
	$('#modal').find('li.context-style').first().removeClass('context-style');
	
	
	//don't allow a close of the modal if a context menu is open.
	if($("#divContextMenu").is(':visible')){
	  	 alert("Please FIRST close the Options Menu Popup, THEN close this Window.");
		return;
	
	   } else {
	
	
	
	
	var modalUL = $("#modal").children("ul.edit");
		//var modalContent = $("#modal").children('ul').html();	//ul with li contents edited by user.
	//modalContent = $(modalUL).children('li').clone( true, true );
	//modalContent = $(modalUL).children('li').clone( true, true );
	
		  /* if(modalContent){
			   console.log("modalContent is defined as "+modalContent);
		   }*/
	
	// BUG!!!!!  The modalid is the same value for all matching dates of the calendar:
	// EX: April 2 and Dec 2 BOTH HAVE modalid=2.
	// This causes the modal window when closed, to duplicate changes from the UL of the modal window
	// Across all dates of the calendar that match modalid's.  12 x.
	
	//TODO: Fix this bug by grabbing also the month as id=mo, attribute ordinal= [number of the month], and (when there are
	// multiple years in a calendar, to avoid duplication on the date and month), id=yr, ordinal [number of the year]
	
	var objMonth = $(modalUL).attr('ordinal');
	var objYear = $(modalUL).attr('yr');
	
	
	// the dom object to save changes to in the calendar.
	var saveTarget;
	var modalId = $(modalUL).attr('modalid'); //used to locate the original UL in the DOM
	//$(".month").each( function(){
		
		    //find each div.month.  Compare attribute values for ordinal and yr, compare to same values in the 
		 //  	if($(this).attr('yr') == objYear && $(this).attr('ordinal') == objMonth){
				//console.log('This Month\'s Ordinal: '+ $(this).attr('ordinal'));
				//console.log('This Month\'s yr: '+ $(this).attr('yr'));
				//console.log("objYear is " + objYear);
		    		//console.log("objMonth is " + objMonth);
				// correct month to update contents.  Find the correct date of this month:				
			//	saveTarget = $(this).find("ul[modalid='" + modalId + "']");
				//$("#modal").find('li').clone( true, true ).appendTo(saveTarget);
		   		//$(modalContent).html() = ""; //clear out the old html in the calendar cell.
		   
		   // modalUL is: var modalUL = $("#modal").children("ul.edit");;
		   // modalContent is the UL in the calendar of the clicked list that opened popup editor.
		   // that is: modalContent = $(clickedObj).parents('.date').children('ul');
		   
		   		//modalContent is the Calendar's UL DOM object, that was defined when modalOpen was last called.
		   
		   
		   
	
		   
		   
		   
		   
		   		$( modalContent ).replaceWith( $( modalUL ).clone(true, true) );
		   	     addListenersToDom("false");
		   
		   		// add listeners afresh to the calendar target UL, modalContent:
		   		$(modalUL).siblings('.cursive').remove();
		   		$(modalUL).remove();
		  
				modalSource = '';
				$('.blocker').addClass('hide');
		   		
		   
		  

	
	   }// else
}


		
		// you could use set() which builds on the set only if it does not already exist.
		//$('#search').on('keyup',function(){
		$('#search').on('input',function(){
		   
			var searchTerm = $(this).val().toLowerCase();
			var results = [];
			var domObjs = [];
			//console.log('on input fired');
		   //require search terms of 3 chars or more
		   if(searchTerm.length > 2){ 
			//console.log('searchTerm len gtr than 2 fired');
		   $('li.lineEntry').each(function(i,list){
			  if(typeof list !== 'undefined'){
				  //console.log('LI is defined in .each');
			   	  var lineStr = $(this).text().toLowerCase().trim();
			  }
			   // -1 returned if searchTerm not found in LI string
			  if(lineStr.indexOf(searchTerm) === -1){
				  //$('#srchResult').empty(lineStr);
				// console.log('no le.');
				  
				 //$(this).hide();
				 // $('#srchResult').addClass('hide');
			  }else{	
				  //var nth = i+1;
				// console.log('Found a matche');
				  
				 // results.push(lineStr);
				  domObjs.push(list); //lineEntry Ojb with matched content
				  
				
				//  if(results.length>0){
					 // console.log('len is '.results.length);
					/*  results = results.map(function(el){
						  if( el.indexOf(searchTerm) > -1 ){
							//remove element from the array
							return el;
						  }
					  });	*/				  
				 // }			  
				  // add the matched str to the results array.
				 // results.push(lineStr);		
				  results.push(lineStr);
	
			  }	//else	   
		   }); //each
			
			// output to the view
			
			if(domObjs.length>0){	
				
				$('#srchResult').removeClass('hide');
				
				
				
				
				$( '#srchResult').empty(); //clear out displayed results with each on.input
				
				//Date: undefined: [object HTMLLIElement]<br/>
				
				var br =  document.createElement("br");

				
				$(domObjs).each(function(i,res){
					
					
					
					
					
					
					var thedate = $(res).parent('.edit').attr('modalid'); //e.g., d21 for the 21st date of a month.	
				  	
					if(typeof thedate !== 'undefined' && thedate.length > 1){
						thedate = thedate.replace('d', '');
				  		var month = $(res).closest('div.month').attr('ordinal');
						var yr = $(res).closest('div.month').attr('yr');
					  	thedate = month + "/" + thedate + "/" + yr;
					  	//console.log("found "+ searchTerm + " on " + thedate);
						//results[i] = thedate + ': ' + results[i];						
					} // if defined
					
					if( typeof res !== 'undefined'){ //  && $(res).html().indexOf(searchTerm) !== -1 					
						
						//$('#srchResult').append( "<div class='result"+i+"' >Date: " + thedate + ", " + $(res).html() ).append("</div><br/>");
						$("#srchResult").append( "<div style='cursor:pointer' class='result"+i+"'  >Date: " + thedate + ", " + $(res).html() + "</div>");
						
						
						
						$(document.body).find('.result'+i).on('click', function() {
								$(res).addClass('context-style');
   								//modalOpen($(res).closest('.modalImg'));
							modalOpen(res);
						});
						
						
						
						
						/*$('.result'+i).on("click", function(){	
							$(this).addclass('context-style');
							modalOpen($(res));
						});*/
					}
					
					
				});  // each results
				
			/*	// display the current results to the user
				$(domObjs).each(function(i,res){		
					if(typeof res !== 'undefined'){
						$('#srchResult').append( $(res).html() );	
					}
				});
				*/
				
				
				
			}//if domObjs has members
			else {
				$('#srchResult').addClass('hide');
			}	
			   
		   }//end if search term > 2
			else {
				$('#srchResult').removeClass('hide');
				$('#srchResult').html( '[ Search Requires 3+ Characters. ]' )
			}
				  			
		}); //on.input
		
		
		
		
		$('#srchResult').parent('form').on('focusout',function(){
			
			if( $('.blocker').hasClass('.hide') ){
				
				$('#srchResult').addClass('hide');
				$( '#srchResult').empty();
				
			} 
			
			
		});
	
//2 functions: set and unset overdue jobs.
//over due jobs are contained in the glob obj "overdueJobs"
//and accessed by keys named after each overdue job number
//overdueJobs.jobnumber
function setOverDueJob(){
	//alert("Set this as overdue");
	$(editableLI).addClass("due");
	$(editableLI).removeClass('context-style');
	$(editableLI).addClass("t21");
	//add this job to the global obj 'overdueJobs':
	//1. get the job # of current edited job in dom
		var jobNmbr = $(editableLI).children('span').first().text(); //the job number parent span is always the first one in the LI.
	//alert("Job# is " + jobNmbr);
	//2. get the date of the edited job's UL wrapper's modalid.
		var date = $(editableLI).parent('ul').attr('modalid').substr(1);
	//3. trim off the first char ('d') from the modalid value, leaving just the numeral.
		//date = date.substr(1);
		date = '<span style="color: red">'+month + '/' + date + '/' + year+'</span>';
	//get the job desc text and truncate it to the first 30 chars.
		var desc = $(editableLI).text();
		desc = desc.slice(0,30);
	//concat into a line of text to save as a property of overdueJobs obj.
		var info = '<div class="ovrDue" id="'+jobNmbr+'">' + date +': '+ desc +'...</div>';
	if( overdueJobs.hasOwnProperty(jobNmbr) === false ){
		overdueJobs[jobNmbr] = info;
	}

	if( isEmpty(overdueJobs) === false ){

		//first erase all the content of the overdue job list in the view:
		$('#OverDueJobsList').html('');

		//write all the updated overdue jobs to the view:
		//by iterating through the overdueJobs properties:
		Object.keys(overdueJobs).forEach(function(key) {
			$('#OverDueJobsList').append(overdueJobs[key]);
		});		
		$('#OverDueJobsList').prepend('<p><span class="due" style="padding: 3px 8px !important; font-size: 18px">Overdue Jobs</span></p>');
	}//if			
				
}
		
function unsetOverDueJob(){
	//alert("UnSet this as overdue");
	//called when user assigns a job "overdue";
	$(editableLI).removeClass("due");
	$(editableLI).removeClass("t21"); //this class is used in checkbox controls to hide/show entry
	$(editableLI).addClass('context-style');
	//remove this job from the global obj 'overdueJobs'
	//1. get the job # of current edited job in dom
		var jobNmbr = $(editableLI).children('span').first().text(); //the job number parent span is always the first one in the LI.
	//2. delete that property
		delete overdueJobs[jobNmbr]; 
	//3. Delete the job from the overdue display in the DOM.
		$("#"+jobNmbr).remove();	
	
}

//display overdue to the user interface.
//generally called only when page loads
function displayOverDueJobs(){
	
	let wks = document.body.querySelector('div#weeks');
	let $due = wks.querySelectorAll('li.due');
	$.each($due, function(i,v){
		//alert("Found a due LIST: "+ v);
		editableLI = v;
		setOverDueJob();
	});
}	
	
function onloadSetOverdueDisplay(){
	
	var $overdues = $("#weeks").find("li.due");
	
	$.each($overdues, function(i, d){
		//  alert("overdue found");
		  //list this in the view of overdue jobs.
		 var jobNmbr = $(this).children('span').first().text(); //the job number parent span is always the first one in the LI.
		//alert("Job# is " + jobNmbr);
		//2. get the date of the edited job's UL wrapper's modalid.
			var date = $(this).parent('ul').attr('modalid').substr(1);
		//3. trim off the first char ('d') from the modalid value, leaving just the numeral.
			//date = date.substr(1);
			var mo = $(d).closest( ".month" ).attr('ordinal');
			var yr = $(d).closest( ".month" ).attr('yr');
			date = '<span style="color: red">'+ mo + '/' + date + '/' + yr+'</span>';
		//get the job desc text and truncate it to the first 30 chars.
			var desc = $(this).text();
			desc = desc.slice(0,30);
		//concat into a line of text to save as a property of overdueJobs obj.
			var info = '<div class="ovrDue" id="'+jobNmbr+'">' + date +': '+ desc +'...</div>';
		if( overdueJobs.hasOwnProperty(jobNmbr) === false ){
			overdueJobs[jobNmbr] = info;
		}

		});
	


	if( isEmpty(overdueJobs) === false ){

		//first erase all the content of the overdue job list in the view:
		$('#OverDueJobsList').html('');

		//write all the updated overdue jobs to the view:
		//by iterating through the overdueJobs properties:
		Object.keys(overdueJobs).forEach(function(key) {
			$('#OverDueJobsList').append(overdueJobs[key]);
		});		
		$('#OverDueJobsList').prepend('<p><span class="due" style="padding: 3px 8px !important; font-size: 18px">Overdue Jobs</span></p>');
	}//if			
				
	
}


function isEmpty(obj) {
   
   //check if it's an Obj first
   var isObj = obj !== null 
   && typeof obj === 'object' 
   && Object.prototype.toString.call(obj) === '[object Object]';

   if (isObj) {
       //"var o", simply represents any property at all, no matter its name.
       for (var o in obj) {
           if (obj.hasOwnProperty(o)) {
			// this is not an empty object.
               return false;
               break;
           }
       }
       return true;
   } else {
       console.error("isEmpty function only accepts an Object");
   }
}


	
	
	

	
</script>

<div id="print" class="hide"></div>
</body>


</html>