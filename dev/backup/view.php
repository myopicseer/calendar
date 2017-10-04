<?php 
	//require_once('../classes/security.php');
	//libxml_use_internal_errors(true);

	// initialize uri parameter variables.
/*	$userURI = $sesURI = $roleURI = '&';

	$url = "{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}";
	$parts = parse_url($url);
	if(isset($parts['query'])){
		
			parse_str($parts['query'], $query);
		//start session:
			if(!isset($_SESSION)){
				require_once('classes/session.php');
				$s = new Session;
			}
			*/
		//print_r($_SESSION);
			//if( $sesID = session_id() ){


			if(!isset($_SESSION)){
				require_once('classes/session.php');
				$s = new Session;
			}

				
		
			//if($query['user']){
				//$username =  $query['user'];
				$username = $_SESSION['user']['name'];	
				session_id() != NULL ? $sesID = session_id() : $sesID = '' ;
			

	
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
   <!-- eventually have a calendarcommon.css, for shared styles between index and view calendars -->
   <link href="styles/calendarview.css" rel="stylesheet" media="screen">
  <!-- <link href="styles/print.css" rel="stylesheet" media="print"> -->
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
   <link rel="stylesheet" href="assets/pickadate.js-3.5.6/default.css">
   <link rel="stylesheet" href="assets/pickadate.js-3.5.6/default.date.css">
   <link href='https://fonts.googleapis.com/css?family=Kaushan+Script&effect=3d-float' rel='stylesheet' type='text/css'> 
   <style>
   .addNewLine{display:none;}
   </style>
    
</head>
<body>
<div class="content-row">
<pre style="text-align:center"><span style="font-size: 12px; background:#FEFFF3;padding:5px 8px;color:#419200; border: 1px dotted #8AC72D">Today: <span id="date"></span> [ <span id="curTime"></span> ]</span><br><br><span  style="text-align:center; margin: 8px auto 2px auto">Compatible Browsers (avoid Internet Explorer)</span><span> <a href="contact.php" target="_blank" title="Opens Email Form in a New Window or Tab">REPORT BUGS</a> | <a href="help.html" target="_blank" title="WIP Support">HELP</a></span><br>
<img src="assets/compatible_browsers.png" title="Compatible Browsers for This Calendar App" style="text-align:center; margin: 0px auto 5px auto" />
</pre>
<h1 id="pageTitle" class="cursive font-effect-3d-float" style="margin: 6px auto;text-align:center;color:#000"></h1>
<?php if($username) { 
	echo "<span style=\"margin: 2px auto 5px auto;color:#000000\" class=\"cursive\">Welcome <span id=\"username\">". $username ."</span>.</span>  
	<form action=\"login.php\" method=\"POST\" >
		<input type=\"hidden\" value=\"".$username."\" name=\"loggedOutUser\" />
		<input type=\"submit\" value=\"logout\" name=\"logout\" />
	</form>
	<!--
	<form action=\"register.php?sid={$sesID}\" method=\"POST\" >
		<input type=\"hidden\" value=\"".$_SESSION['user']['role']."\" name=\"role\" />
		<input type=\"submit\" value=\"Register a User\" name=\"logout\" />
	</form>
	-->
	";
} ?>
<form>
	<input type="text" id="search" class="form form-control" placeholder="Search For Jobs" style="background-color: lightyellow;height:22px;margin:10px 0;padding:5px">
	<div id="srchResult" class="hide" style="background-color: #86C73A; color:#0B6F93; padding:10px 25px"></div>
</form>
<br />
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
          <option value="MarionOutdoor">Marion-Outdoor</option>
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
       <!-- <button class="morPad center btn save" name="update" onClick="saveMonth()">SAVE UPDATES</button>
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
          <!--<button class="smBtn" id="btnEmail" title="Emails PDF Attachment to Distribution List">Email</button> &nbsp;-->
         <!-- <button data-clipboard-target="div#copyemails" data-clipboard-action="copy" class="copy smBtn" id="previewEmails" > Preview Recipients</button>
          <div id="prevEmailPopUp" class="hide" style="background:#3C8CB8; color:#ECC585; padding:12px; position:absolute; z-index:9999;">          
          </div>-->
          
         </div>
       
  </div><!--end clearfix-->



</div>

<div class="content-row" id="message">
</div>

<div id="calWrap" class="clearfix">
	<div id="topHeaders">
        <!-- <div class="row">
               <div class="btnPrev"><a href="#" onClick="prev('yr')"><img  id="prevYear" src="assets/prev-yr.png"></a></div>       
             
               <div class="btnNext" ><a href="#" onClick="next('yr')"><img id="prevYear" src="assets/nex-yr.png"></a></div>     
          </div> -->
          <div class="row">
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
<!--scripts-->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw=" crossorigin="anonymous"></script>
<script  src="assets/pickadate.js-3.5.6/picker.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/pickadate.js-3.5.6/picker.date.js" type="text/javascript" charset="utf-8"></script>
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
	var justReloaded = 0;
		
	

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
	});

	loadCalendar(curCompany,-1,-1);
	
	
	$("#btnPrev").on('click', function(){
		
		displayNewMonth('prev');
		
	});
	
	
	$("#btnNext").on('click', function(){		
	
			displayNewMonth('next');
		
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

function addListenersToDom()
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
	}
	 //remove hide class from any hidden LI elements with a new loading of the page.
	 if(justReloaded === 1)
	 {	//reset the toggling variable to false
		 justReloaded = 0;
		 //remove hide class		 		 
	 }
	 */
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
    teamsShowAll();  	
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
	console.log("teamsshowall loaded!");
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
function teamNamesHTML()
{	
	if(curCompany == "Custom Sign Center"){
		teamAssignment = [];
		teamAssignment = [
			"Bob-Michael",//red
			"Joe-Dave",//violet
			"Install",//black
			"SubInstall",
			"n/a",
			"CSC Trans",
			"Shipping",
			"Cust PU"			
			];
			
			
	} else if(curCompany == "Marion Signs"){
		teamAssignment = [];
		teamAssignment = [
			"Curtis-Stock",
			"Shawn-King",
			"no-team",
			"no-team",
			"no-team",
			"CSC Trans",
			"Shipping",
			"Cust PU"	
			];
	}
	else 
	{		
		teamAssignment = [];		
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
		   	   
		   
		   		// add listeners afresh to the calendar target UL, modalContent:
		   		$(modalUL).siblings('.cursive').remove();
		   		$(modalUL).remove();
		  
				modalSource = '';
				$('.blocker').addClass('hide');
		   		
		   
		   /*
		   var overlay = '<div class="blocker" style="display:none; width:100%; position:absolute; z-index:99999"> U P D A T I N G . . .</div>';
		   	
*/
		   		
		   
				/*
		   		setTimeout(
						function(){
							addListenersToDom("false");							
							//$("body").append(overlay);
							giveNotice("Please Wait for Calendar UPDATE...");							
						}, 3500);
						*/
		   		//$("body").find(".blocker").remove();
				//modalSave(saveTarget, modalSource);
				//saveTarget is a .month div, where it contains attr values 'yr' & 'ordinal'
				//that match the exact DOM element with cell date (attr->modalid) value == to 
				//the original date that was the html source for the popup modal.
				
				//used to see if the popup model html (modalSource) is identical to the
				//original source, to determine if the modalSource needs to be written to the original source
				//upon closing the modal.
				
		
			//}
		    
		   // });
	//var objUl = $('div.month').attr('ordinal')$('.edit[modalid="'+modalId+'"]');
	
	
	 
	//console.log("Modal ID is " + modalId);
	//$('.edit[modalid="'+modalId+'"]');
	//console.log("modalId is "+modalId);
	
	//modalSource is html dom contents in the calendar
	//modalContent is the html dom contents in the modal popup
	//compare to see if they are different; save modal content to the source if true.
	
		   
	// Forego comparison.  The actions in the modal or non-action can be updated to the calendar cell.
	//if( modalContent !== modalSource )
	//{
		// compared contents are different; let's save new content to the calendar
				
		//addListenersToDom();
		// alert('Updates Saved');
	//}

	
	   }// else
}

function modalSave(destination, source)
{
	//clone it back to the source with event listeners intact.
	//need to add in your contextmenu event handler.  Clone could not 
	//copy those for some reason:
	
	//if the modal source contains contextMenu html, remove it first.
	/*if( $(source).find('#divContextMenu') ){
		$(source).find('#divContextMenu').remove();
	}*/
	
	//now save the modal content to the original date cell of the calendar
	//console.log("Source: " + source);
	destination.html(source);
	//console.log("The HTML to copy to the cal is: "+ $(source).html() );
	//addListenersToDom();
	
	
		 
	// alert("editboxes is: " + editboxes);
	 
	 $.each(destination, function(i, elem){		 
		 destination[i].addEventListener('click', startEdit, true);
		 //var dateBox = editboxes[i].parentNode; //parent of the edit box.
		//evt.stopImmediatePropagation();stopPropagation()
		
		bindListeners4EachList(destination);
		//listsIntoObjects(destination,i);
	 });	 
	 
	
	
	
	
	
	
			
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
						
						$('#srchResult').append( "Date: " + thedate + ", " + $(res).html() ).append("<br/>");	
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
		
		
		
		
		$('#search').on('focusout',function(){
			$('#srchResult').addClass('hide');
			$( '#srchResult').empty();
		});


</script>


</body>


</html>