<?php 

libxml_use_internal_errors(true);
/*
if (!isset($_SESSION)) { 
	session_start(); 
	//set the session variables id and username
}
elseif( (integer)$_SESSION['id'] !== (integer)$id && strtolower($_SESSION['username']) !== strtolower($username) ) {

	//redirect to login page or show form here

} else {
	//authenticated user -- load the requested URL

}
*/
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title></title> 
   <!--styles-->   
   <link href="../styles/calendar.css" rel="stylesheet" media="screen">
  <!-- <link href="styles/print.css" rel="stylesheet" media="print"> -->
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
   <link rel="stylesheet" href="../assets/pickadate.js-3.5.6/default.css">
   <link rel="stylesheet" href="../assets/pickadate.js-3.5.6/default.date.css">
   <link href='https://fonts.googleapis.com/css?family=Kaushan+Script&effect=3d-float' rel='stylesheet' type='text/css'> 
</head>
<body>
<div class="content-row">
<pre style="text-align:center"><span style="font-size: 12px; background:#FEFFF3;padding:5px 8px;color:#419200; border: 1px dotted #8AC72D">Today: <span id="date"></span> [ <span id="curTime"></span> ]</span><br><br><span  style="text-align:center; margin: 8px auto 2px auto">Compatible Browsers (avoid Internet Explorer)</span><span> <a href="contact.php" target="_blank" title="Opens Email Form in a New Window or Tab">REPORT BUGS</a> | <a href="help.html" target="_blank" title="WIP Support">HELP</a></span><br>
<img src="../assets/compatible_browsers.png" title="Compatible Browsers for This Calendar App" style="text-align:center; margin: 0px auto 5px auto" />
</pre>
<h1 id="pageTitle" class="cursive font-effect-3d-float" style="margin: 6px auto;text-align:center;color:#000"></h1>

<div class="clearfix">
<div class="fifty-pct">
<h3 style="margin: 2px auto 5px auto;color:#8AC72D" class="cursive">Choose a Company Calendar</h3>

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
 </div>
 <div class="fifty-pct" style="padding-left: 50px">
 </div>
 </div> <!--end clearfix-->
 <br/>
 <div class="clearfix">
        <button class="morPad center btn save" name="update" onClick="saveMonth()">SAVE UPDATES</button>
        <button class="morPad center btn undo" name="undo" onClick="editHistory('undo')">UNDO</button>
        <button class="morPad center btn undoall" name="undoall" onClick="editHistory('undoall')">UNDO ALL</button>
        <button class="morPad center btn redo" name="redo" onClick="editHistory('redo')">REDO</button>        
        <a style="float:left;margin-left:7px" href="csvimport.php" target="_blank"><button class="morPad center btn import" name="import">IMPORT CSV</button></a>
       
        <!--<button class="morPad center btn" name="export" onClick="exportCSV()">EXPORT</button>-->
        <!-- lengend -->
        <div class="clearfix">
        <div style="float:right; display:inline-block" id="icons">
       <div name="teamdata" action="" id="teamSelection"> 
       <span style="padding-top:12px;float:left">Assignment: &nbsp;</span>
       <div class="iconrow">
              <div class="icon-box b1"></div>
              <input type="checkbox" id="select-1" name="t1" value="t1" checked="checked">
              <div class="box-label t1" id="l1">Team 1</div>
          </div>
          <div class="iconrow">
              <div class="icon-box b2"></div>
              <input type="checkbox" id="select-2" name="t2" value="t2" checked="checked">
              <div class="box-label t2" id="l2">Team 2</div>
          </div>
          <div class="iconrow">
              <div class="icon-box b3"></div>
              <input type="checkbox" id="select-3" name="t3" value="t3" checked="checked">
              <div class="box-label t3" id="l3">Team 3</div>
          </div>          
           <div class="iconrow">
               <div class="icon-box b4"></div>
               <input type="checkbox" id="select-4" name="t4" value="t4" checked="checked">
               <div class="box-label t4" id="l4">Team 4</div>
           </div>                    
           <div class="iconrow">
               <div class="icon-box b5"></div>
               <input type="checkbox" id="select-5" name="t5" value="t5" checked="checked">
               <div class="box-label t5" id="l5">Team 5</div>
           </div>                              
          <div class="iconrow">
          	<div class="icon-box b6"></div>
               <input type="checkbox" id="select-6" name="t6" value="t6" checked="checked">
               <div class="box-label t6" id="l6">Team 6</div>
          </div>                                        
         <div class="iconrow">
              <div class="icon-box b7"></div>
              <input type="checkbox" id="select-7" name="t7" value="t7" checked="checked">
              <div class="box-label t7" id="l7">Team 7</div>
         </div>                                                  
         <div class="iconrow">
         		<div class="icon-box b8"></div>
               <input type="checkbox" id="select-8" name="t8" value="t8" checked="checked">
               <div class="box-label t8" id="l8">Team 8</div>
         </div> <br />
         <span style="padding-top:12px;float:left">Status: &nbsp;</span>
        <!-- <div class="iconrow">
          	<div class="icon-box b9"></div>
               <input type="checkbox" id="select-9" name="completed" value="t9" checked="checked">
               <div class="box-label completed" id="l9">Completed</div>
          </div>        
          -->
          <div class="iconrow">
          	<div class="icon-box b10"></div>
               <input type="checkbox" id="select-10" name="unassigned" value="t10" checked="checked">
               <div class="box-label unassigned" id="l10">Unassigned</div>
          </div>
          <div class="iconrow">
          	<div class="icon-box b11"></div>
               <input type="checkbox" id="select-11" name="hold" value="t11" checked="checked">
               <div class="box-label hold" id="l11">On Hold</div>
          </div>
          </div>        
          </div>
          </div> 
          
          <div class="clearfix"><br/>
          <button class="smBtn" onclick="teamsShowAll()">Show All</button> &nbsp;
          <button class="smBtn" onclick="teamsHideAll()">Hide All</button> &nbsp;
          <button class="smBtn" id="btnPrint">Print</button> &nbsp;
          <button class="smBtn" id="btnEmail" title="Emails PDF Attachment to Distribution List">Email</button> &nbsp;
          <button data-clipboard-target="div#copyemails" data-clipboard-action="copy" class="copy smBtn" id="previewEmails" > Preview Recipients</button>
          <div id="prevEmailPopUp" class="hide" style="background:#3C8CB8; color:#ECC585; padding:12px; position:absolute; z-index:9999;">          
          </div>
          
         </div>
       
  </div><!--end clearfix-->



</div>

<div class="content-row" id="message">
</div>

<div id="calWrap" class="clearfix">
	<div id="topHeaders">
        <!-- <div class="row">
               <div class="btnPrev"><a href="#" onClick="prev('yr')"><img  id="prevYear" src="../assets/prev-yr.png"></a></div>       
             
               <div class="btnNext" ><a href="#" onClick="next('yr')"><img id="prevYear" src="../assets/nex-yr.png"></a></div>     
          </div> -->
          <div class="row">
             <div id="btnPrev"><img  id="prevMonth" src="../assets/prev-mo.png"></div>              
              <div style="width:49.5%; display:inline-block; text-align:center; margin: 0px; box-sizing:border-box;"><span class="cursive" id="mo" oridnal=""><!-- e.g., ordinal="12" for december --></span> <span class="year cursive" id="yr" ordinal=""><!-- e.g., 2016, etc --></span></div>
              <div id="btnNext" ><img id="nextMonth" src="../assets/nex-mo.png"></div>             
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
     <img class="hide" id="wait" src="../assets/preloader_blue.png" />
</div>
<!--scripts-->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw=" crossorigin="anonymous"></script>
<script  src="../assets/pickadate.js-3.5.6/picker.js" type="text/javascript" charset="utf-8"></script>
<script src="../assets/pickadate.js-3.5.6/picker.date.js" type="text/javascript" charset="utf-8"></script>
<script src="../assets/clipboard.min.js" type="text/javascript" charset="utf-8"></script>
<!--<script src="../assets/pickadate.js-3.5.6/legacy.js" type="text/javascript" charset="utf-8"></script>-->
<script type="text/javascript">
	/* global scope vars */
	var weeksDOM = $("#weeks");
	var headerYr = $("#headerYr");
	var curCompany = '';
	var timeoutID;
	var originalContent='';
	var content;
	var newContent = '';
	var curMonthCounter; //prev/next calendar month counting : increment/decrement
	var lastEditedCell=[];
	var redo=[];	
	var redoObj;
	var year;
	var month; //integer
	var monthName;
	var theDate;
	var todayOrdinalCell; //integer of the cell count for today's date.
	var changes=[];
	var responseMonth; //the most recent month html / data sent by php
	var contextMenu; //this is a mini html popup options menu for editing jobs	
	var listElements = []; //all the list elements that hold job entries
	var boxIDs = ['t0','t1','t2','t3','t4','t5','t6','t7','t8','t9','t10','unassigned'];
	var modalSource;
	var modalContent;
	var monthName = ["OccupyZeroPosition-PlaceHolder","January","February","March","April","May","June","July","August","September","October","November","December"];
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
	var justReloaded = 0;
		
	//var $spinner = $("#wait");
	var emails = '<h2 style="color:#FFFFFF;">Recipients</h2><div id="copyToClipb">alicia@customsigncenter.com<br>chad@customsigncenter.com<br>christina@customsigncenter.com<br>courtney@customsigncenter.com<br>debbie@customsigncenter.com<br>don@customsigncenter.com<br>doug@customsigncenter.com<br>dale@customsigncenter.com<br>emylee@customsigncenter.com<br>eric@customsigncenter.com<br>james@customsigncenter.com<br>jeff@customsigncenter.com<br>johna@customsigncenter.com<br>judy@customsigncenter.com<br>john@customsigncenter.com<br>justin@customsigncenter.com<br>mary@customsigncenter.com<br>michael@customsigncenter.com<br>nathan@customsigncenter.com<br>sam@customsigncenter.com<br>scott@customsigncenter.com<br>tturner@customsigncenter.com<br>teryl@customsigncenter.com<br>timh@customsigncenter.com<br>tim@customsigncenter.com</div>';
	/* for testing only var emailRecipientsCSC = ['info@signcreator.com','cf_is_here@hotmail.com','chris@customsigncenter.com','tim@customsigncenter.com']; */
	var emailRecipientsCSC = ['alicia@customsigncenter.com','chad@customsigncenter.com','christina@customsigncenter.com','courtney@customsigncenter.com','don@customsigncenter.com','doug@customsigncenter.com','dale@customsigncenter.com','debbie@customsigncenter.com','emylee@customsigncenter.com','eric@customsigncenter.com','james@customsigncenter.com','jeff@customsigncenter.com','johna@customsigncenter.com','judy@customsigncenter.com','john@customsigncenter.com','justin@customsigncenter.com','mary@customsigncenter.com','michael@customsigncenter.com','nathan@customsigncenter.com','sam@customsigncenter.com','scott@customsigncenter.com','tturner@customsigncenter.com','teryl@customsigncenter.com','timh@customsigncenter.com','tim@customsigncenter.com'];
	//var modalLink = '<a class="modalLink" rel="modal:open"><img src="../assets/write-circle-green-128.png" title="edit"</a>'*/
	// var liIndex; //hold the index position of the active list we're referencing in code.
	

function clearAlert() {
  window.clearTimeout(timeoutID);
}

$(document).ready(function (){
	
		contextMenu = $('<div id="divContextMenu" style="display:none">'+ 
	'<input id="reschedule" type="text" placeholder="reschedule" />'+	
	'<ul id="ulContextMenu">'+	
	    '<li id="t0" onclick="jobAssignment(0, this)" option="0" style="text-align:right;color:red">x Close</li>'+
	    '<li id="t1" onclick="jobAssignment(1, this)" option="1">Team 1</li>'+
	    '<li id="t2" onclick="jobAssignment(2, this)" option="2">Team 2</li>'+
	    '<li id="t3" onclick="jobAssignment(3, this)" option="3">Team 3</li>'+
	    '<li id="t4" onclick="jobAssignment(4, this)" option="4">Team 4</li>'+
	    '<li id="t5" onclick="jobAssignment(5, this)" option="5">Team 5</li>'+
	    '<li id="t6" onclick="jobAssignment(6, this)" option="6">Team 6</li>'+
	    '<li id="t7" onclick="jobAssignment(7, this)" option="7">Team 7</li>'+
	    '<li id="t8" onclick="jobAssignment(8, this)" option="8">Team 8</li>'+
	    '<li id="hold" class="hold_small" onclick="jobAssignment(12, this)" option="12">On Hold</li>'+
	    '<li id="unassigned" class="unassigned" onclick="jobAssignment(9, this)" option="9">Unassigned</li>'+
	    '<li id="completed" class="completed" style="font-weight:normal" onclick="jobAssignment(10, this)" option="10">In/Complete</li>'+	 
	    '<li id="delete" class="delete" onclick="jobAssignment(11, this)" option="11">Delete Entry</li>'+
	'</ul></div>');
	
     var clipboard = new Clipboard('.copy');

    clipboard.on('success', function(e) {
	   console.info('Action:', e.action);
	   console.info('Text:', e.text);
	   console.info('Trigger:', e.trigger);
    
	   e.clearSelection();
    });
    
    clipboard.on('error', function(e) {
	   console.error('Action:', e.action);
	   console.error('Trigger:', e.trigger);
    });

	
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
	
	
	curCompany=$( "#companyCalendar option:selected" ).val();
	$("#pageTitle").html(curCompany + " WIP Calendar");
	$('#companyCalendar').on('change', function() {
  		//$("#company" ).val( this.value ); // or $(this).val()
		curCompany=$( "#companyCalendar option:selected" ).val();
		$("#pageTitle").html(curCompany + " WIP Calendar");
		teamNamesHTML();
		
		
		/*if(curCompany !== "Custom Sign Center"){
			alert('Planned Update: Calendar Jobs Will Change for Each Company.');
		}*/
	});
	//event trigger submit a company cal request
	$("#loadCalendarForm").on("submit", function(e){
		e.preventDefault();
		var str = $("#loadCalendarForm").serialize(); //selected company name
		loadCalendar(str['companyCalendar'],-1,-1);
		addListenersToDom();
		teamsShowAll();
	});

	loadCalendar(curCompany,-1,-1);
	
	
	$("#btnPrev").on('click', function(){
		
		displayNewMonth('prev');
		
	});
	
	
	$("#btnNext").on('click', function(){		
	
			displayNewMonth('next');
		
	});
	
	
	
	
	

	/*capture some keyboard keys and set to desired behaviors inside the .edit container*/
	
  var inputArea = $('.edit > *');
  inputArea.on('keydown', function() {
    var key = event.keyCode || event.charCode;
    //allow backspace (key8) to NOT go back to previous web page, but instead delete backward
    //key46 is the delete key.
    if( key == 8 || key == 46 )
        return false; //prevents default browser/DOM behaviors for the specified keys.
  });	
  
 
  
  //printing calendar
  $("#btnPrint").on("click", function () { 
 		cleanCalendarLayout();
		printWindow();
          
   });
  
  $("#btnEmail").on("click", function(e) { 
  
		e.preventDefault();	
		cleanCalendarLayout();
		wait('start'); //spinner gif indicating busy
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
		
	var calendarHTML = $("#print").clone().html();
		
	var data = {"recipients":emailRecipientsCSC,"company":curCompany,"calendar":calendarHTML}; 
	
	    $.ajax({	
			 url : "classes/sendemail.php",
			 type: "POST",
			 data : data,
			 dataType:"json",
		  success: function(respData, textStatus, jqXHR)
		  {			
		  	 wait('end');
			 //var msg = respData.msg;			
			 giveNotice('<span style="color: #009000">Success</span>: Calendar has been Emailed to Your Recipients.');// with subject line: '+respData.subject);
			 //reset the print div's html to empty for reuse.
			 
		  },
		  error: function (jqXHR, textStatus, errorThrown)
		  {
			 wait('end');
			 giveNotice('<span style="color: #009000">Success</span>: Calendar has been Emailed to Your Recipients.');
		  }	
	    });	
	    $( "#print" ).html('');    
   });
   $("#prevEmailPopUp").html(emails);
   $("#previewEmails").on("mouseover", function(e){
	    $("#prevEmailPopUp").removeClass("hide");
	    $(this).html(' Click To Copy Emails to Clipboard');	    
   });
   $("#previewEmails").on("mouseout", function(e){
	    $("#prevEmailPopUp").addClass("hide");
	    $(this).html('Preview Recipients');
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

function addListenersToDom()
{
	 var editboxes = document.getElementsByClassName('edit');	 
	// alert("editboxes is: " + editboxes);
	 
	 $.each(editboxes, function(i, elem){		 
		 editboxes[i].addEventListener('click',startEdit,false);
		 //var dateBox = editboxes[i].parentNode; //parent of the edit box.
		
		
		bindListeners4EachList(elem);
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
	 
	 /*
	 $("#select-1").on("change", function(){
		 if($(listElements[i]).hasClass("t1")){
			 
			 
		 }
		 else
		 {
			 
			 
			 
		 }
		*/ 
		 
		 
		 
	/*	 
		 
	  if( $(this).is(':checked') ) {		
		 for( var i = 0; listElements.length > i; i++ ) 
		 {	
			 //for select-[1], [2], and [3] CSS classes 
			 //for( var x = 1; 4 > x; x++)
			  //{ 
				 if($(listElements[i]).hasClass("t1") &&  $(listElements[i]).hasClass("hide"))
				  //if($(listElements[i]).hasClass("select-"+x) == true ) 
				  {
					  $(listElements[i]).removeClass("hide");
				  } 
				  else 
				  {
					  $(listElements[i]).addClass("hide");
				  }
			  //}	//end  nested for	
		 }//end for
	   } 
	   else //select-1 is unchecked
	   {
		 for( var i = 0; listElements.length > i; i++ ) 
		 {	
			 
				 if($(listElements[i]).hasClass("t1") &&  $(listElements[i]).hasClass("hide") == true)
				  //if($(listElements[i]).hasClass("select-"+x) == true ) 
				  {
					  $(listElements[i]).addClass("hide");
				  }
			 
		 }//end for 
	   }	
	 */	
	 //remove hide class from any hidden LI elements with a new loading of the page.
	 if(justReloaded === 1)
	 {	//reset the toggling variable to false
		 justReloaded = 0;
		 //remove hide class
		 teamsShowAll();		 
	 }
	 
	   //hide the first and last div in each .row (sundays and saturday columns)
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

function addNewLine(elem, parentClass){			
					
			var newList = $("<li contenteditable='false' class='lineEntry unassigned' title='Right Click for Options'>New Job</li>");		
			//the ul 
			var ulTarget = $(elem).parents(parentClass).children('.edit');
			$(ulTarget).append(newList);		
			
			//pass this new LI obj to the func that creates the evt handler for it.		
			contextMenuHandler(newList);	
}

function contextMenuHandler(newList){
	
	    //newList is newly-added lineEntry LI ... needs event handler for context menu
	/*
		 	$(newList).on("focus", function(evt) {
				$(this).css("background", "#DDFBFF"); 
			});
			
	*/	
	
			//var $jobEntriesUL = $(newList).closest('.edit');	// the UL wrapping the job entry li just clicked.
			
			//event handler for selecting installer team for new lists (right click)
			$(newList).on("contextmenu", function(e) {	
			   
			
			 //if contextMenu is already open elsewhere, close it
		/*	 if($(contextMenu).length){
				 alert('Removing ContextMenu');
				$(contextMenu).remove();
			 }		*/				
			 //index location of the new list
			 //liIndex = $(newList).parent().children().index(this);				
			// e.preventDefault();
			 var wrapper = $(newList).parents('.date');//The date box
			 var parentOffset = wrapper.offset(); 
			 var relX = wrapper.scrollLeft();
			 var relY =  wrapper.scrollTop();
		      //append the popup contextmenu html where you right clicked.
			 $(newList).append($('#contextMenu').html());
			 // addlistener to the close button to stop lineEntry's contenteditable=true from remaining in effect.
			 // or better: can I prevent bubbling up of the contenteditable attr to the parent LI of the contextmenu in the first place?
			
			 
			 $(newList).css({
				left: relX,
				top: relY,
				display: 'inherit'
			 });	
			 
			 $( '#reschedule' ).pickadate(
				{
					format: 'mm/dd/yyyy',
					formatSubmit: 'mm/dd/yyyy',
				}
			);			
		  						
		 });	
		 
	
	
}


function jobAssignment(opt, obj){
	//opt 0 = close menu, 1=team 1, 2=team 2, 3=team 3 ... 8=team 8, 
	//opt 9=unassigned, 10=completed, 11=delete
	//the obj is the 'this' DOM obj that was clicked which called this funciton (the job entry wrapped in <LI> to edit.
	wait('start');
	
	//0 = close menu window	
	if(opt =='0'){
		
		//1st check to see if a reschedule data change had been made (i.e., move entry to new date cell)
		//hidden input inside the contextmenu wrapper div #divContextMenu used in the datepicker.
		//clicking a date stores that date to the value attribute of the checkNewEntryDate DOM element.
		//value="mm/dd/yyyy"
		var checkNewEntryDate = $(obj).parents("#divContextMenu").children("input[type='hidden']")[0];
		
		if(typeof checkNewEntryDate !== 'undefined' )
		{			
			//we need to move the entry to its new scheduled day
			//format is a string like mm/dd/yyyy
		     //console.log(checkNewEntryDate);
			var newDate = $(checkNewEntryDate).val();
			if(newDate.length > 5) //if the content is stored there.
			{
				//console.log("newDate is: " + newDate);
				// $(contextMenu).children("#reschedule.picker__input").prop('value','');
				// set the value back to empty?
				$(obj).parents("#divContextMenu").children("input[type='hidden']").prop('value','')[0];
				//get LI's Job Entry HTML that we need to move to newDate's cell
				var $srcLI = $(obj).parents(".lineEntry");	
				 
				
				 		 
				$(contextMenu).remove();	
				//tag it to use outerHTML
				var clonedLIhtml = $srcLI.clone(); //clone includes the <li> tag just like outerHTML does.				 
				 			 
				//parse the date info
				 
				dateParts  = newDate.match(/(\d{2})\/(\d{2})\/(\d{4})/);
				//console.log("dateParts are: " + dateParts);
				// above outputs an array
				/*
				 [0] "07/30/2016"    
				 [1] "07"	
				 [2] "30"
				 [3] "2016"
				*/	
				rescheduleJob(dateParts,$srcLI,clonedLIhtml);
			}
		    else
		    {
			    //before closing the menu, gotta set the contenteditable on the parent LI to 'false'.
			    
			    // close the style options menu
			   
			    $(contextMenu).remove();
			    	
		    }
		}
		else
		{
			console.log("Problem: checkNewEntryDate is undefined");
			// close the pop up menu
			$(contextMenu).remove();	
			//TODO: add a trigger to focus on the error message ctr at top of page
			giveNotice('<span style="color: #FF0000">Failed</span>: A problem was encountered trying to parse your date change request.');
		}
		
		
		
	}
	
	var $targetList = $(obj).parents('li.lineEntry');	//the list we want to style or act upon
	
	 //was the option style an inclusive or preclusive class
	 //i.e., does the li element have a class styles that we would want to add a class with, or one we would want to supplant completely?
	 
	 // I. COMPLETED OPTION SELECTED?
	 // id="completed" already present on the LI, *AND* the user 
	 // selected "completed" option ( then remove the id to 'toggle off', and mark as incomplete ).
	 
/*    No More "Completed" status.  Simply Remove from Calendar.  See: III., Below
	 if( $(obj).attr("id") === 'completed' ) 
	 {	
	 	// 1st, if hold class present, let's remove its, as 'hold' and 'completed' are mutually inclusive
	 	// add or remove class 'hold'.  args(false = remove, obj to remove it from)
	 	if( $($targetList).hasClass('hold') ) addHoldStatus(false, $targetList[0]);		 

		if( $($targetList).hasClass('completed') === false )
		{			
			$($targetList[0]).addClass('completed');			
			return;
		}	
	 }
	 */
	 // II. HOLD OPTION SELECTED?
	 if( $(obj).attr("id") === 'hold' ) //user clicked 'On Hold' opt
	 {		
		if( $($targetList).hasClass('hold') === false )
		{		
			addHoldStatus(true, $targetList[0]);					
			
		}	
		else 
		{
			addHoldStatus(false, $targetList[0]);
							 
		}
	 }
	 // III. DELETE OR JOB COMPLETED OPTION SELECTED?
	 else if( $(obj).attr("id") === 'delete' || $(obj).attr("id") === 'completed')
	 {
		 alert("Permanently Delete this Entry?");
		 $($targetList[0]).remove();
		 		
	 }
	 
	 // IV. A TEAM OPTION or OTHER OPTION SELECTED?
	 // user selected something other than "completed" or "delete" or "On Hold" 
	 // add and remove classes as needed from the target LI		   
	 else 
	 {
		 var CSSclass = $(obj).attr("id");
		 $(boxIDs).each(function(i,box) {			 
			// if the ignostic array item === the select option & the target LI doesn't contain that class...
			if( box === CSSclass && $($targetList[0]).hasClass( CSSclass ) === false ) {	
				 //console.log("box is: " +box+ " and the selected id is: "	+ CSSclass); 				 	
				 $($targetList).addClass( box );	
				 //if user is setting unassigned as the class
				 //ensure completed cannot remain as a class
				 if(CSSclass == 'unassigned'){
					 $($targetList[0]).removeClass( 'completed' );
				 }
			}
			else if(box !== CSSclass)
			{	//console.log("Remove a class: box is: " +box+ " and the selected id is: "	+ CSSclass);
				//if($(targetList[0]).hasClass(CSSclass))
				//{
					$($targetList[0]).removeClass( box );
					//console.log( "Removed class " + box );	
				//}
			}
		 });	
	 }
	
	wait('end');
}

function addHoldStatus( yes, $obj ){
	
	// if yes == false, remove class 'hold' from $obj, else add 'hold' class.	 	
	 if( yes == false ) 
	 {
		 $($obj).removeClass('hold'); //remove 'hold' class bc now completed.
		 wait('end');
		 return;
	 } else {
		 
		 $($obj).addClass('hold');
		 wait('end');
		 return;
	 }
}

function startEdit() {
	
	 //the first two lines create a new li when you click in a cell. REMOVE: Only Create New Job Entries with the "+" btn.
	 /* 
	 	var btn =  $( this ).parents('.date').children('.day').children('.addNewLine');
	   	addNewLine(btn);   
	 */
	   	   
	  //$( this ).attr("contenteditable","true");
	  
	  //this refers to the .edit UL, which holds the LI's of job entries

	 var anyListTag = $(this).children("li");
	
	 
	  //Make UL editable
	/* $(this).each(function(index,element){	
	 		
	 	
		//alert(originalContent);
		$(element).attr("contenteditable", "true");		  
	  });
	 */
	 
	   $( this ).addClass( "yellow-bg" );
		//make any existing children (lists) editable
		  $(anyListTag).each(function(ct,listEl){		  
		    $(listEl).attr("contenteditable", "false");
		     		  
	    });
	    
	  originalContent = $( this ).html(); //get the content of the cell before editing it (contenteditable = true) 
		
	    $(anyListTag).each(function(ct,listEl){		 
	    		
		    $(listEl).attr("contenteditable", "true");		  
	    });
	    
	 /* 
	  
	  if( typeof anyListTag !== 'undefined' )
	  {   // at least one "<li>" in this ul.edit element
		 //add click event listeners so you can edit them
		  
		  //alert("has a list");
		  //is it empty (can we just use it to add content)?
		  $.each( $(this).children("li"), function(ind, liTag){
			  
			    //use this one.
				  if( $(liTag).html() == ''){ 
				  	liTag.addClass( "editNode" );
				  }
				  
			  liTag.on("click", function(){
				  liTag.attr("contenteditable","true");		
				  liLength = liTag.value.length;
				  liTag.selectionStart = liLength;
				  liTag.selectionEnd = liLength;
				  liTag.focus();		  
			  });			  
			  		 
		  });
		  
		  
		  
		  
	  }  
	  else //no list tags found in the ul.edit.  Create one.
	  {		  var liHTML = '<li class="editNode"></li>';
		  	  $(this).append(liHTML);
			  var newLI = $($(this).children("li"));
			  newLI.addClass( "editNode" );
			  //assign enter key evt handler
			  enterToAddNewLI(newLI);			 
			  newLI.focus();			
	  } 
	   */

	  
	 closeEditing(this);
	  
}

function closeEditing( domObj ){
	
	 $( domObj ).on('mouseout', function(evt) {
		// $domObj refers to the UL of class .edit.
		//foreach edit node <li> that is empty, remove them...
		addListenersToDom();
		evt.stopImmediatePropagation();
		
		//$('li.lineEntry:empty').remove();
		var listTags = $(domObj).children('li');
		//remove any hidden contextMenus, default New Job Lists, and br tags		
		$(listTags).each(function(i, li) {
			if($(li).text() === '! New Job'){
				$(li).remove();
			}
			//remove all break tags.
			$(li).find('br').remove();
	
			//remove editable attrib				  
		  	$(li).attr("contenteditable", "false");  
		});
		
		$( domObj ).attr("contenteditable","false");
    		$( domObj ).removeClass( "yellow-bg" );  
		//any li tags not empty, add them to the newContent var.
		
		newContent = $(domObj).html(); //get the newly added cell contents; ContentEditable = false	
		if( originalContent !== newContent ){
			lastEditedCell.push( $(domObj) );
			//console.log("Orig is: "+originalContent);
			//console.log("New is: "+newContent);
			//get the id of element with the year.
			//year = $( '#yr' ).attr('ordinal');
			//get the id of element with the year.
			//month = $( domObj ).parent().attr('ordinal') -1;
			//date = $( domObj ).parent().find('.date').val(); //get date
		} 
		//else for debugging only
		else {
			//No Changes to Save. 
			//$( domObj ).html('');
		}
		$( domObj ).off( 'mouseout' );
	   });   
	
}

//not used.  this adds a new list tag when enter key is pressed while in an edit UL.
function enterToAddNewLI(LIobj) {
	
		  //event handler if enter key pressed (key 13)
	  LIobj.keypress(function(e) {
		  $.each(e, function(){
			 if ( e.keyCode == 13 ) {  // detect the enter key
			 ev.preventDefault();
			 //need to create a new li pair for new entry.			 
			 var li = $(LIobj.add("li"));
			 li.attr("contenteditable","true");
			 li.addClass("lineEntry");			
		  } 
	  	 });
	  });	
	
}
/*
function endEdit(){
    	$( this ).attr("contenteditable","false");
    	$( this ).removeClass( "yellow-bg" );    
	
    	if( originalContent !== newContent ){ //was the cell content changed?
	    //save the originalContent so we can undo our 1 history update to the cell.
	    
	    lastEditedCell.push( $(this) );    
	   
	    //get the id of element with the year.
	    year = $( '#yr' ).attr('ordinal');
	    //get the id of element with the year.
	    month = $( this ).parent().attr('ordinal');
	    date = $( this ).parent().find('.date').val(); //get date
	   // update( content, year, month, date, curCompany, 'update' );
    	}
    	else { return false; }
}
*/

/*
  $(function() {
    $( "#message" ).dialog({
    modal: true,
      buttons: {
        Ok: function() {
          $( this ).dialog( "close" );
	   }
	 }
  });
  
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
	if(typeof co === 'undefined') co = curCompany;
	wait('start');
	var today = new Date(); //date obj
	var cDate = today.getDate(); //current date.
	
	//if month and yr not passed in...
	if(m<0){//set month to cur month		
		var m = today.getMonth()+1; //month is zero-based (+1)
	}
	if(y<0){//set year to cur year .. by default, load cur yr on first load.			
			var y = today.getFullYear();		
	}
	
	
	//date = current date to highlight, month=req mo, year=req yr.
	var data = {"content":'',"year":y,"month":m,"theDate":cDate,"company":co,"method":"display",}; 
	
	$.ajax({	
		  url : "../classes/calendar.php",
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
		 
		  todayOrdinalCell =respData.activeOrdinalCell;
		 $("#weeks").html(respData.html);
		 //for "undo all" operations, preserve copy of html
		 var respDataHtml = toString(respData.html);
		 $("#yr").html(year);
		 $("#mo").html(monthName[month]);		 
		 $("#mo").attr('ordinal', month);	
		 $(".month").attr('yr', year);	 
		 
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

//form buttons' functions
function saveMonth(){
	
	//if there is an open #divContextMenu, got to close it so it isn't saved into the xml.
	var $openMenus = $('.month').find('#divContextMenu');
	$($openMenus).each(function(i,menu){
		$(menu).remove();
	});
		
	wait('start');
	var htmlDataToSave = $("#weeks").html();
	var data={"content":htmlDataToSave,"year":year,"month":month,"theDate":theDate,"company":curCompany,"method":"saveMonth"};
	
	//update the appropriate cell node in the xml
	$.ajax({	
		  url : "../classes/calendar.php",
		  type: "POST",
		  data : data,
		  dataType:"json",
	   success: function(respData, textStatus, jqXHR)
	   {	
	   	  wait('end');
		  giveNotice('<span style="color: #009000">Success</span>: Your Updates have been Save.');
		  //giveNotice('<span style="color: #009000">Success</span>: Your Updates have been Save.');
		  console.log(respData);
		  
	   },
	   error: function (jqXHR, textStatus, errorThrown)
	   {
		   wait('end');
		   giveNotice('<span style="color: #FF0000">Failed</span>: Server Response: "'+errorThrown+'"');
	   }	
  	});
}

// called by the contextMenu option - "reschedule" current LI on click "Close" menu
// Designed to cut/paste the job to a new calendar date.  2 Params are moveToDate, jobHTML
// param1 is array:
/*
			[0] "07/30/2016"
			[1] "07"	...month
			[2] "30"  ...day
			[3] "2016" ...yr
*/
// Param2 is the original jquery DOM obj. This lets us remove it, once confirmed by user.
// Param3, jobHTML, is the LI html string:  '<li ....etc.... /li>' being moved.

function rescheduleJob(moveToDate,$srcLI,jobHTML)
{
	//console.log("rescheduleJob called.");
	//must remove leading zero from the moveTo month and day (e.g., 07 to 7)
	if(moveToDate === null || moveToDate === '')
	{
		console.log("moveToDate is null or empty...cannot complete rescheduling");
		return; //skip this whole plan... no date to move to	
	}//end if moveToDate is null		
	else
	{	
			/*moveToDate is array like:
				 [0] "07/30/2016"    
				 [1] "07"	
				 [2] "30"
				 [3] "2016"*/
	
	    $(moveToDate).each(function(i,v){
		    
		     /*moveToDate is array like:
				 [0] "7/30/2016"    
				 [1] "07"	
				 [2] "30"
				 [3] "2016"*/
		    
		    removeZeros=Number(v);
		    console.log(v + ' converted by Number is now: ' + removeZeros);
		   /* console log outputs removeZeros like this for each v 
		    10/12/2016 converted by Number is now: NaN
		    /calendar/ (line 1112)
		    10 converted by Number is now: 10
		    /calendar/ (line 1112)
		    12 converted by Number is now: 12
		    /calendar/ (line 1112)
		    2016 converted by Number is now: 2016
		    /calendar/ (line 1112)
		    savetocell called
		    /calendar/ (line 1182)
		    found moveToDate month index 1 is: 10
		    /calendar/ (line 1193)
		    movetocell is: [object Object]
		    /calendar/ (line 1197)
		    jobHTML is [object Object]
		    */
		    
		    //back to a string and save		
		    moveToDate[i]=String(removeZeros);
		    console.log('removeZeros, saved to moveToDate['+i+'] after conversion to str looks like: ' + moveToDate[i]);
		    /*
		    removeZeros, saved to moveToDate[3] after conversion to str looks like: 2016
		    /calendar/ (line 1140)
		    savetocell called
		    /calendar/ (line 1204)
		    found moveToDate month index 1 is: 10
		    /calendar/ (line 1215)
		    movetocell is: [object Object]
		    /calendar/ (line 1219)
		    jobHTML is [object Object]
*/
		/* moveToDate[1] is a str of the numeral month ( ie "10" if you are moving the entry to Oct ). */
		   
	    });
	    
	    //1: Is current cal the yr and month we're moving the job to?
		    //if not, we need to load that year and month
	    if(year === moveToDate[3]) //current global year == the selected moved to date's year?
	    {	
		   
			    //the cur cal year is the moveTo location.
			    //get the moveTo cell as a DOM obj to append to:
			    //our identifier for the day can be the ID of the "+" image
			    //that is in the form of "d4" for 4th day of the month in the HTML DOM
			    saveToCell(moveToDate,jobHTML);
			    
			    //now remove the date that was in the datepicker input
			    $(contextMenu).children("#reschedule.picker__input").prop('value','');
			    $srcLI.remove();
			    //saveMonth();
			    addListenersToDom(); //ensure event handlers are added to the item's new location.		
	    }
	/*    else //need show a different month
	    {
		    var msg="We need to load a new calendar month in order to complete your rescheduling request. Ok?\n>"+
		    "If you've made other changes to the current month, you MUST CANCEL NOW and save the current calendar "+
		    "before attempting this reschedule request\n>(else you will lose any changes you have made in the current month).";
		    confirmRequest(msg);	//tru or fals
		    if(confirmRequest(msg) == false)
		    {
			    giveNotice('<span style="color: #009000">Successfully Cancelled.</span>');//action cancelled.
			    return false;
		    }
		    else // user wants to proceed with loading new month
		    {
			    //load the month that is needed.
			    window.setTimeout(loadCalendar(curCompany, moveToDate[1], moveToDate[3]), 400);
			    
			   // rescheduleJob(moveToDate,$srcLI,jobHTML);
			    //just confirm we have the moveTo month and yr loaded:
			    
			    
			    
			    if(year === moveToDate[3] && month === moveToDate[1])
			    {
				    giveNotice('<span style="color: #009000">Success: New Calendar Loaded.</span>');//calendar loaded.
				    saveToCell(moveToDate);
				    giveNotice('<span style="color: #009000">Success: Job has been moved to '+moveToDate[1]+'.  Please SAVE/UPDATE the your </span>');
				    return false;			
			    }
		    }
	    }	*/
	    
	}
}

// micro fn to process save request to a new date cell.
// called by rescheduleJob()
function saveToCell(moveToDate,jobHTML)
{
	/*
	console.log("SaveToCell called");
	console.log("The HTML being moved: "+jobHTML)
	console.log("d"+moveToDate[2]);
	*/
	//console.log("savetocell called");
	/*
	
	var monthToWrite = $("div.month[oridnal="+moveToDate[1]+"]");
	var dateToWrite = $(monthToWrite).find("ul[modalid=d"+moveToDate[2]+"]");
	$(dateToWrite).prepend(jobHTML);	
	
	*/
	$(".month").each(function(i,m){
		if( $(m).attr("ordinal") == moveToDate[1])
		{
			//we found the month to move to.
			console.log("found moveToDate month index 1 is: " + moveToDate[1]);
			//this is the month we need to reschedule on
			//var $dates = $(m).find(".date"); //all the date cells
			//var $moveToCell = $(m).find("ul[modalid=d"+moveToDate[2]+"]");
			var $moveToCell;
			if($moveToCell = $($(m).attr("ordinal", moveToDate[1])).find("ul[modalid=d"+moveToDate[2]+"]")){
			console.log("movetocell is: " +$moveToCell);
			console.log("jobHTML is " + jobHTML);
			/*movetocell is: [object Object]
			jobHTML is [object Object]*/
			$($moveToCell).prepend(jobHTML);	
			return;
			}
		}
	});		
	
	
}

//universal confirm / cancel dialog func
function confirmRequest(msg)
{	    
   var r = confirm(msg);
   return r; //returns false if cancel/close or true if ok.	
}

function editHistory(action){
	//which action button clicked?
	if(typeof lastEditedCell != 'undefined'){
	    //undo last change
	    if(action=='undo'){
		    if(lastEditedCell.length < 1){
			    giveNotice('<span style="color: #FF0000">Failed</span>: No entries available to delete.');
		    } else {
			  // var index = lastEditedCell.length -1;
			   var undoCell=lastEditedCell.splice(-1,1);			   
			   redo.push({"redoText":undoCell[0][0].innerHTML,"redoHandler":undoCell});
		    	   undoCell[0].html(originalContent);//set the cell's content back to the original.
			   undoCell.slice(0,1);
			   giveNotice('<span style="color: #009000">Success</span>: Most Recent Change has been Reversed.');	
			   addListenersToDom();
		    }
		    // lastcellEdited = $(this);		    
	         // undo all changes; restore orig xml for the month
	    } else if(action=='undoall') {
		    //$("#weeks").html('');
		    //1st show all.  We probably cannot edit hidden elements
		    teamsShowAll();	    
		    
		    var undoAmt = lastEditedCell.length;
		    if( undoAmt > 0)
		    {
			 for(var i = 0; undoAmt > i; i++)
			 {
				var undoCell=lastEditedCell.splice(-1,1);	//remove one from the end of the array		   
				redo.push({"redoText":undoCell[0].innerHTML,"redoHandler":undoCell});
				undoCell[0].html(originalContent);//set the cell's content back to the original.
				undoCell.slice(0,1);
			 }
			  giveNotice('<span style="color: #009000">Success</span>: '+undoAmt+' change(s) have (has) been reversed.');
			  addListenersToDom();
		    }
		    else
		    {
			    giveNotice('<span style="color: #009000">OK</span>: There\'s Nothing to Undo.');
			    
		    }
		    	    
		    
		    /* old way: refresh page contents from XML
		    $("#weeks").html(respDataHtml);
		    originalContent='';	
		    newContent = '';
		    addListenersToDom();
		    giveNotice('<span style="color: #009000">Success</span>: All changes this session are removed.');	
		    */
		    
	    //action is 'redo'; redo last undo
	    } else  {
		    //lastEditedCell.html(content); old way when lastEditC was a simple variable, not an array
		    //new way with multiple undo/redo's:
		    if(Object.keys(redo).length<1){
			    giveNotice('<span style="color: #FF0000">Failed</span>: No entries available to undo.');
		    } else {
			    	//lastEditedCell.html(content); old way when lastEditC was a simple variable, not an array
				//new way with multiple undo/redo's:
				var index = Object.keys(redo).length -1; //find the index value of the last item of this obj.
				redoObj=redo.splice(index,1); //remove and assign the last redo item to redoObj before we redo it.
				//var hndlr = redoCell.handler; //the jquery object (edit div).
				redoObj[0].redoHandler[0].html(redoObj[0].redoText); //the old markup being restored to edit UL.
				//add the handler back onto the undo array:
				lastEditedCell.push(redoObj[0].redoHandler[0]);
				//redoCell = ''; //reset it.
				redoObj.slice(0,1);//clean it up
				giveNotice('<span style="color: #009000">Success</span>: Last Undo Request has been Reversed.');
				addListenersToDom();				
		    }
	    }
	 //no history saved
	} else {		
			giveNotice('<span style="color: #FF0000">Failed</span>: There\'s no revisions to update from.');
	}

}

// end form buttons' functions

function  bindListeners4EachList(ULtags)
{
	//htmlReceivedFromXML = the html inside of div#weeks.  div.row are the top level elements
	//drill down to each list to bind handler
	//event handler for selecting installer team for new lists (right click)
	//console.log(htmlReceivedFromXML);
	var LItags = $(ULtags).children('li');
	//var eachUL = $(eachWeekRow).children('ul.edit'); //each ul holding the list tags we need handlers on.
		
	$.each(LItags, function(i,posting){	
		//each list requiring a handler			
			$(posting).on("contextmenu", function(e) {	
			 //if contextMenu is already open elsewhere, close it
			/* if($(contextMenu).length){
				$(contextMenu).remove();
			 }	*/					
			 //index location of the new list
			 
			 $( '#reschedule' ).pickadate(
				{
					format: 'mm/dd/yyyy',
					formatSubmit: 'mm/dd/yyyy',
				}
			);
			
			 
			 
			 
			 
			/* 
			 
			   $('#reschedule').pickadate({
			   format: 'mm/dd/yyyy',
			   formatSubmit: 'mm/dd/yyyy',
			   hiddenName: true,
			   /*
			   onOpen: function() {
				  console.log( 'Opened' )
			   },
			   onClose: function() {
				  console.log( 'Closed' )
			   }, 
			   onSelect: function() {
				  console.log( 'Selected: ' + this.getDate() )
			   }*/
			   /*
			   onStart: function() {
				  console.log( 'Hello there :)' )
			   },
			   onRender: function() {
				console.log( 'New calendar rendered!' )
			   }
			  
		    });	
			 	$('#reschedule').pickadate({
				    format: 'mm/dd/yyyy',
				    formatSubmit: 'mm/dd/yyyy',
				    hiddenName: true
				});
				*/		 
			 
			// liIndex = $(posting).parent().children().index(this);				
			 e.preventDefault();
			 var wrapper = $(posting).parents('.date');//The date box
			 var parentOffset = wrapper.offset(); 
			 var relX = wrapper.scrollLeft();
			 var relY =  wrapper.scrollTop();
		  
			 $(this).append($(contextMenu).css({
				left: relX,
				top: relY,
				display: 'inherit'
			 }));										
		 });	
	});

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

function modalOpen(clickedObj)
{	
	//obj clicked is the "edit" button within the date cell user wants to edit
	//if there is any modal content, remove it
	//$("#modal").html('');
	//get user-requested content for the new modal
	modalContent = $(clickedObj).parents('.date').children('ul');	
	
	//if the ul contains an opened contextMenu popup, remove it first.
	if( $(modalContent).find('#divContextMenu') ){
		$(modalContent).find('#divContextMenu').remove();
	}
	
     $( modalContent ).clone(true, true).appendTo( "#modal" );	
	//$('#modal').removeClass('hide');
	$('.blocker').removeClass('hide');
	$('.blocker').css('opacity',0).animate({opacity: 1}, 10);	
	//reguired to run add listeners again to preserve the onclick editable events for lists
	addListenersToDom();
}
function modalClose(clickedObj)
{
	//obj clicked is a button within the open #modal element
	//model el's content child has attr of 'modalid'.
	//that unique modalid is the same as the class name of the
	//original content wrapper.  so val = attr('modalid'); 
	//so original content wrapper in the DOM can be located as: '$(getElementsByClassName(val)).html()';
	
	//if contents of ul with attr modalid = (ex: 'd8') 
	//does not equal contents of the modal (i.e., modal contents edited by user),
	//then modalSave is called before close the modal window.
	
	//var modalContent = $("#modal").children('ul').html();	//ul with li contents edited by user.
	modalContent = $("#modal").children('ul').children('li').clone( true, true );
	
	var modalId = $(clickedObj).parent('#modal').children('ul').attr('modalid'); //used to locate the original UL in the DOM
	var objUl = $('.edit[modalid="'+modalId+'"]');
	 
	//console.log("Modal ID is " + modalId);
	//$('.edit[modalid="'+modalId+'"]');
	
	modalSource = $(objUl).children('li').clone( true, true );
	//var origContent = document.getElementsByClassName(modalId);
	//get the contents
	//origContent = $(origContent).html();
		
	if( modalContent !== modalSource )
	{
		// compared contents are different; let's save new content to the calendar
		modalSave(objUl, modalContent);
		addListenersToDom();
		//alert('Updates Saved');
	}
	$('#modal').children('ul').remove();
	$('.blocker').addClass('hide');		
}

function modalSave(destination, source)
{
	//clone it back to the source with event listeners intact.
	//need to add in your contextmenu event handler.  Clone could not 
	//copy those for some reason:
	
	//if the modal source contains contextMenu html, remove it first.
	if( $(source).find('#divContextMenu') ){
		$(source).find('#divContextMenu').remove();
	}
	
	//now save the modal content to the original date cell of the calendar
	//console.log("Source: " + source);
	$(destination).html(source);
			
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
		// +1 for next, -1 for previous month being requested.
		
		var validMonth = monthHTMLexists( action); //does next mo exist in the DOM?
		console.log(validMonth);
		
		if( validMonth !== 'ok'){
			giveNotice('<span style="color: #FF0000">There is No Job Data Stored for that Month.</span>');
		
			wait('end'); //hide the animated processing graphic
			return; //get outta town
		}
		
		if( parseInt(month) < 12 ){ //stay on the same year.
			nextMonth =  parseInt(month) + 1;
			$(allMonths).each(function(i,mo){
				
				if($(mo).attr("ordinal") == month){ //this is the current month we want to hide.
					$(mo).addClass("hide");
				}
				
				if($(mo).attr("ordinal") == parseInt(nextMonth)){
					$(mo).removeClass("hide");
					month = String(nextMonth); //set the month var to the new month displayed.
					curMonthCounter = month;
					 //$("#mo").html(monthName);
					$("#mo").html(monthName[month]);
					$("#mo").attr("ordinal",month);
					$("#mo").removeClass("hide");
				}
			});
			
			//loadCalendar(curCompany, month, year);
		} 
		else 
		{ //need to roll over to next yr
			month = "1";		
			year = parseInt(year) +1; 
			$("#yr").html(year);
		 	$("#mo").html(monthName[month]);							
			//load the previous month
			//loadCalendar(curCompany, month, year);
		}
		
	}
	else //action is to display the 'prev' month
	{		
		if( parseInt(month) > 1 ){
			nextMonth =  parseInt(month) - 1;
			$(allMonths).each(function(i,mo){
				
				if($(mo).attr("ordinal") == month){ //this is the current month we want to hide.
					$(mo).addClass("hide");
				}
				
				if($(mo).attr("ordinal") == parseInt(nextMonth)){
					$(mo).removeClass("hide");
					
					curMonthCounter = month;
					 //$("#mo").html(monthName);
					$("#mo").html(monthName[nextMonth]);
					$("#mo").attr("ordinal",nextMonth);
					$("#mo").removeClass("hide");
				}
			});
			month = String(nextMonth); //set the month var to the new month displayed.
		} else { //need to roll back to prev yr
		
			if(year == 2016)
			{
				alert("Rolling Back to Prior Year Currently Disabled on CSC Calendar.  Feature will be added before Jan 2017.");
				wait('end');
			}
			
			month = "12";		
			year = parseInt(year) -1; 
			$("#yr").html(year);
		 	$("#mo").html(monthName[month]);	
			
			/*
			if( parseInt(year) < parseInt(y) ) { //if trying 2 years back, keep to 1 year back.
				//do nothing.  cannot go back more than 2 yrs.
			} else {				
				year = parseInt(year)-1; //go back 1 yr
				month = parseInt("12");
			}			
			//load the previous month
			loadCalendar(curCompany, month, year);
			*/
		}
		
	}
	wait('end');
	window.setTimeout(addListenersToDom, 100);
	
}

// verify DOM contains HTML of job listings for a user navigated month
// allMonths is all DOM els of class "month"
// direction is "next" or "prev" 
function monthHTMLexists(action){
	var allMonths = $(".month");
	var result;
	 var checkMonth;
	// TODO: Check allMonths is not null, empty
	if(action ==='next'){ 
	
	
		checkMonth = parseInt(month) +1; //current month on the calendar + 1
		
		
		
		checkMonth = String(checkMonth);
		
	}	
	else{ //this is navigating calendar back one month
		checkMonth = parseInt(month) -1; //current month on the calendar - 1
		//if checkMonth is 13, need to set it to 1 (jan)
		
		checkMonth = String(checkMonth);	
	 }
	//console.log('checkMonth is: ' + checkMonth);
	$(allMonths).each(function(i,el){
		//get the value from the element's "ordinal" attribute that matches the
		//requested month to navigate to.  If it doesn't exist in DOM, then return false;	
		//console.log("EACH has fired!");
		if(  $(el).attr("ordinal")  === checkMonth ){
			//console.log($(el).attr("ordinal"));
			result = 'ok';	
			
		} 
	});
	
	return result; //if false, there is not DOM for that month
		
}

function teamNamesHTML()
{
	if(curCompany == "Custom Sign Center"){
		
		teamAssignment = [
			"Bob-Walter",
			"John-Larry",
			"Joe-David",
			"Dave-Jim",
			"Mary-Katey",
			"CSC Trans",
			"Shipping",
			"Cust PU"			
			];
			
			
	}
	else
	{		
		teamAssignment = [
			"Team 1",
			"Team 2",
			"Team 3",
			"Team 4",
			"Team 5",
			"Team 6",
			"Team 7",
			"Team 8"			
			];
		
	}
	$(teamAssignment).each(function(i,team){
			
		i++;
		$("#l"+i).html(team);
		//$("#t"+(i-1)).html(team);
		//popup opt menu on r-clk of job entry	
	
			
	});
	contextMenu = $('<div id="divContextMenu" style="display:none">'+ 
	'<input id="reschedule" type="text" placeholder="reschedule" />'+	
	'<ul id="ulContextMenu">'+	
	    '<li id="t0" onclick="jobAssignment(0, this)" option="0" style="text-align:right;color:red">x Close</li>'+
	    '<li id="t1" onclick="jobAssignment(1, this)" option="1">'+
	    teamAssignment[0]+
	    '</li>'+
	    '<li id="t2" onclick="jobAssignment(2, this)" option="2">'+
	    teamAssignment[1]+
	    '</li>'+
	    '<li id="t3" onclick="jobAssignment(3, this)" option="3">'+
	    teamAssignment[2]+
	    '</li>'+
	    '<li id="t4" onclick="jobAssignment(4, this)" option="4">'+
	    teamAssignment[3]+
	    '</li>'+
	    '<li id="t5" onclick="jobAssignment(5, this)" option="5">'+
	    teamAssignment[4]+
	    '</li>'+
	    '<li id="t6" onclick="jobAssignment(6, this)" option="6">'+
	    teamAssignment[5]+
	    '</li>'+
	    '<li id="t7" onclick="jobAssignment(7, this)" option="7">'+
	    teamAssignment[6]+
	    '</li>'+
	    '<li id="t8" onclick="jobAssignment(8, this)" option="8">'+
	    teamAssignment[7]+
	    '</li>'+
	    '<li id="hold" class="hold_small" onclick="jobAssignment(12, this)" option="12">On Hold</li>'+
	    '<li id="unassigned" class="unassigned" onclick="jobAssignment(9, this)" option="9">Unassigned</li>'+
	    '<li id="completed" class="completed" style="font-weight:normal" onclick="jobAssignment(10, this)" option="10">In/Complete</li>'+	 
	    '<li id="delete" class="delete" onclick="jobAssignment(11, this)" option="11">Delete Entry</li>'+
	'</ul></div>');
	
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
	 printWindow.document.write('<html><head><title>Print Calendar</title><link href="styles/print.css" media="all" rel="stylesheet" />');
	 
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
</script>

<!--
<div id="popupWrapper" style="display:none">

<div id="divContextMenu" style="display:none"> 
	<input id="reschedule" type="text" placeholder="reschedule" />	
	<ul id="ulContextMenu">	
	    <li id="t0" onclick="jobAssignment(0, this)" option="0" style="text-align:right;color:red">x Close</li>
	    <li id="t1" onclick="jobAssignment(1, this)" option="1">Team 1</li>
	    <li id="t2" onclick="jobAssignment(2, this)" option="2">Team 2</li>
	    <li id="t3" onclick="jobAssignment(3, this)" option="3">Team 3</li>
	    <li id="t4" onclick="jobAssignment(4, this)" option="4">Team 4</li>
	    <li id="t5" onclick="jobAssignment(5, this)" option="5">Team 5</li>
	    <li id="t6" onclick="jobAssignment(6, this)" option="6">Team 6</li>
	    <li id="t7" onclick="jobAssignment(7, this)" option="7">Team 7</li>
	    <li id="t8" onclick="jobAssignment(8, this)" option="8">Team 8</li>
	    <li id="unassigned" class="unassigned" onclick="jobAssignment(9, this)" option="9">Unassigned</li>
	    <li id="completed" class="completed" style="font-weight:normal" onclick="jobAssignment(10, this)" option="10">In/Complete</li>	 
	    <li id="delete" class="delete" onclick="jobAssignment(11, this)" option="11">Delete Entry</li>
	</ul></div>

</div>-->
<div id="copyemails" style="height:0px;width:0px;margin:0px;overflow:hidden">alicia@customsigncenter.com,chad@customsigncenter.com,christina@customsigncenter.com,courtney@customsigncenter.com,don@customsigncenter.com,doug@customsigncenter.com,dale@customsigncenter.com,debbie@customsigncenter.com,emylee@customsigncenter.com,eric@customsigncenter.com,james@customsigncenter.com,jeff@customsigncenter.com,johna@customsigncenter.com,judy@customsigncenter.com,john@customsigncenter.com,justin@customsigncenter.com,mary@customsigncenter.com,michael@customsigncenter.com,nathan@customsigncenter.com,sam@customsigncenter.com,scott@customsigncenter.com,tturner@customsigncenter.com,teryl@customsigncenter.com,timh@customsigncenter.com,tim@customsigncenter.com</div>
<div id="print" class="hide"></div>
<!-- copy text of an element to clipboard... REQUIRES : No Libraries -->
	<!--<script src="../assets/clipboard.min.js" type="text/javascript" ></script>-->
</body>


</html>