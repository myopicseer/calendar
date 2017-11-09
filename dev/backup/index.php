<?php  			if(!isset($_SESSION)){
				require_once('classes/session.php');
				$s = new Session;
			}

				
			if($_SESSION['user']['role'] !== 'admin'){
				//$user = unserialize($_SESSION['user']);
				//print_r($_SESSION['user']);
				
				//$_SESSION["user"] = array('name' => $_SESSION['user']['name'], 'role' => $_SESSION['user']['role']);
				$userURI = '';
				if(!empty($_SESSION['user']['name'])){
					$userURI = '?user='.$_SESSION['user']['name'];
				} 
				header('Location: login.php' . $userURI);
			
				
				//echo $sesID;	TESTED: This new session start id does match the one in the uri from prior page.
				//and matches the one in the database for the active session.
				//if(!empty($query['sid'])) { $sesURI .= $query['sid'];}else{ $sesURI = '';};				
			} else {
			//if($query['user']){
				//$username =  $query['user'];
				$username = $_SESSION['user']['name'];	
				session_id() != NULL ? $sesID = session_id() : $sesID = '' ;
			}

			//'company' is either "ALL" to view all companies (special admin), 
			// or num char (0=csc, etc) to view calendar for 1 company.
			if( isset( $_SESSION['user']['company'] ) ){
				
				switch( $_SESSION['user']['company'] ){
						
						
						
				}
				
				
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
/******* REQUIRES HTACCESS RULES ************************************************/
	/*
RewriteEngine on
RewriteRule ^(.*)\.[\d]{10}\.(css|js)$ $1.$2 [L]
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
   <link rel="stylesheet" href="<?php echo auto_version('/calendar/dev/backup/styles/calendar.css'); ?>" type="text/css" media="screen" />   
   <link rel="stylesheet" href="<?php echo auto_version('styles/print.css'); ?>" rel="stylesheet" media="print">
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
   <link rel="stylesheet" href="assets/pickadate.js-3.5.6/default.css">
   <link rel="stylesheet" href="assets/pickadate.js-3.5.6/default.date.css">
   <link href='https://fonts.googleapis.com/css?family=Kaushan+Script&effect=3d-float' rel='stylesheet' type='text/css'> 
   <link rel="stylesheet" href="assets/icomoon/style.css">
    <link rel="stylesheet" href="styles/nav.css">
   
    
</head>
<body>
<div class="content-row">
<pre style="text-align:center"><span style="font-size: 12px; background:#FEFFF3;padding:5px 8px;color:#419200; border: 1px dotted #8AC72D">Today: <span id="date"></span> [ <span id="curTime"></span> ]</span> | <span><a href="contact.php" target="_blank" title="Opens Email Form in a New Window or Tab">REPORT BUGS</a> | <a href="help.html" target="_blank" title="WIP Support">HELP</a></span><br><br><span  style="text-align:center; margin: 8px auto 2px auto">Recommended Browsers </span><br>
<img src="assets/compatible_browsers.png" title="Compatible Browsers for This Calendar App" style="text-align:center; margin: 0px auto 5px auto" />
</pre>
<h1 id="pageTitle" class="cursive font-effect-3d-float" style="margin: 6px auto;text-align:center;color:#000"></h1>
	<h3 style="margin: 14px auto;text-align:center;color:#4642A8;background:#DDE95B;padding:18px; text-align:center;font-family: 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, 'sans-serif'">
		DEVELOPMENT PLAYGROUND: This calendar is NOT SHARING nor SAVING Job Data with the Live Calendar.  Therefore, the jobs here may be deleted, iconized, etc without effecting the Live Calendar.  Do what you want to do without concern of affecting the Live App.  --- If you like the features under Development here, the Live Calendar can be Updated with this New Version ( at which Point the Live Data will Feed into this Version at the Live App's Address ).
		
	</h3>
<br />
<?php if($username) { 
	echo "<span style=\"margin: 2px auto 5px auto;color:#000000\" class=\"cursive\">Welcome <span id=\"username\">". $username ."</span>.</span>  
	<form action=\"login.php\" method=\"POST\" >
		<input type=\"hidden\" value=\"".$username."\" name=\"loggedOutUser\" />
		<input class=\"smBtn\" type=\"submit\" value=\"logout\" name=\"logout\" />
	</form>
	<br/>
	<!--
	<form action=\"register.php?sid={$sesID}\" method=\"POST\" >
		<input type=\"hidden\" value=\"".$_SESSION['user']['role']."\" name=\"role\" />
		<input type=\"submit\" value=\"Register a User\" name=\"logout\" />
	</form>
	-->
	";
} else {
	$username = 'user';
} ?>
<form>
	<input type="text" id="search" class="form form-control" placeholder="Search and Click">
	<div id="srchResult" class="hide" style="background-color: #86C73A; color:#0B6F93; padding:10px 25px"></div>
</form>
<br />
<div class="clearfix">
<div class="fifty-pct">
<h3 style="margin: 2px auto 5px auto;color:#8AC72D" class="cursive">Choose a Company Calendar</h3>

<form action="" method="post" name="loadCalendar" id="loadCalendarForm">
	<select name="companyCalendar" id="companyCalendar" >
     	<option class="csc" value="Custom Sign Center" selected>Custom Sign Center</option>
          <option class="jg" value="JG Signs">JG Signs</option>
          <option class="marion" value="Marion Signs">Marion Signs</option>          
          <option class="boyer" value="Boyer Signs">Boyer Signs</option>
          <option class="outdoor" value="Outdoor Images">Outdoor Images</option>
         <!-- <option class="marion outdoor" value="MarionOutdoor">Marion-Outdoor</option> -->
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
       <!-- <button class="morPad center btn undo" name="undo" onClick="editHistory('undo')">UNDO</button>
        <button class="morPad center btn undoall" name="undoall" onClick="editHistory('undoall')">UNDO ALL</button>
        <button class="morPad center btn redo" name="redo" onClick="editHistory('redo')">REDO</button> -->      
        <a style="float:left;margin-left:7px" href="csvimport.php" target="_blank"><button class="morPad center btn import" name="import">IMPORT CSV</button></a>
        
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
        <div class="clearfix">
        <div style="float:right; display:inline-block" id="icons">
       <div name="teamdata" action="" id="teamSelection"> 
       
       <span style="padding-top:12px;float:left">Assignment: &nbsp;</span>
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
		  <br/>
         
         <br />
         <div class="j">
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
            
         
		  </div>
        <br /><br />
        <div class="p">
        
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
          
		  </div>
          
          </div>        
          </div>
          </div> 
          
          <div class="clearfix"><br/>
          <button class="smBtn" onclick="teamsShowAll()">Show All</button> &nbsp;
          <button class="smBtn" onclick="teamsHideAll()">Hide All</button> &nbsp;
          <button class="smBtn" id="btnPrint">Print</button> &nbsp;
          <button class="smBtn" id="btnEmail" title="Emails PDF Attachment.  See 'HELP', above, for best PDF formatting method.">Email</button> &nbsp;
          <button data-clipboard-target="div#prevEmailPopUp" data-clipboard-action="copy" class="copy smBtn" id="previewEmails" > Preview Recipients</button>
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
         <div id="calFooter">App ver. <?php include('/home/custo299/public_html/calendar/backup/ver.php') ?>. 2015 - <?php echo date('Y') ?> &copy; Custom Sign Center, Inc. -- All Rights Reserved.</div>     
	<div class="blocker hide">
     	<div id="modal" class="modal">
          <button class="smBtn" onclick="modalClose(this)">Close</button>
          <span class="addNewLine" onclick="addNewLine(this, modal)"> + </span>
          </div>     
	</div>
    <div id="modalWindow" class="hide">
     	<div id="modal" class="modal">
			<button class="smBtn" onclick="closeModal(this)">Close</button>
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
<!--<script src="assets/copypaste.js" type="text/javascript" charset="utf-8"></script>-->
<script type="text/javascript">
	/* global scope vars */
	var weeksDOM = $("#weeks");
	var headerYr = $("#headerYr");
	var curCompany = 'Custom Sign Center';
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
	var editableLI; //the active/current right-clicked LI obj that contextmenu will style.
	var todayOrdinalCell; //integer of the cell count for today's date.
	var changes=[];
	var responseMonth; //the most recent month html / data sent by php
	var contextMenu; //this is a mini html popup options menu for editing jobs	
	var listElements = []; //all the list elements that hold job entries
	var boxIDs = ['t0','t1','t2','t3','t4','t5','t6','t7','t8','t9','t10'];
	var modalSource;
	var modalContent;
	var monthName = ["OccupyZeroPosition-PlaceHolder","January","February","March","April","May","June","July","August","September","October","November","December"];
	var dateParts;
	var $icon = '<i class="p1"></i><i class="p2"></i><i class="p3"></i><i class="p4"></i>';
	var teamHTML='';
	var $usr;
	var teamAssignment = [];
	var assignLabels = [];
	var iconSet = {
	'ups':'ic-ups',	
	'unas':'ic-flag',
	'trip':'ic-i-ret-trip',	
	'crane':'ic-i-crane',	
	'crew':'ic-users',
	'parts':'ic-cog',
	'comp':'ic-i-comp-alt',
	'inv':'ic-i-comp-inv',
	'info':'ic-p-inf',
	'inspr':'ic-p-insp-req',
	'inspa':'ic-p-insp-appr',
	'pappr':'ic-p-appr'
	};
	var justReloaded = 0;
	
	//2 idle user globals
	var IDLE_TIMEOUT = 900; //15 mins
     var _idleSecondsCounter = 0;
	
	//url parameters
		//user's company access rights.  "developer" has no admin user session token or company restrictions.
		var userCompany;	
	
	
	
	
	//var $spinner = $("#wait");
	var emails = '<div id="copyToClipb">alicia@customsigncenter.com;<br>christina@customsigncenter.com;<br>courtney@customsigncenter.com;<br>dale@customsigncenter.com;<br>dan@customsigncenter.com;<br>debbie@customsigncenter.com;<br>don@customsigncenter.com;<br>doug@customsigncenter.com;<br>emylee@customsigncenter.com;<br>eric@customsigncenter.com;<br>james@customsigncenter.com;<br>jeff@customsigncenter.com;<br>john@customsigncenter.com;<br>jreed@customsigncenter.com;<br>judy@customsigncenter.com;<br>justin@customsigncenter.com;<br>marcus@customsigncenter.com;<br>mary@customsigncenter.com;<br>michael@customsigncenter.com;<br>nathan@customsigncenter.com;<br>sam@customsigncenter.com;<br>scott@customsigncenter.com;<br>tturner@customsigncenter.com;<br>teryl@customsigncenter.com;<br>timh@customsigncenter.com;<br>tim@customsigncenter.com</div>';
	/* for testing only var emailRecipientsCSC = ['info@signcreator.com','cf_is_here@hotmail.com','chris@customsigncenter.com','tim@customsigncenter.com']; */
	var emailRecipientsCSC = ['alicia@customsigncenter.com','christina@customsigncenter.com','courtney@customsigncenter.com','dale@customsigncenter.com','dan@customsigncenter.com','debbie@customsigncenter.com','don@customsigncenter.com','doug@customsigncenter.com','emylee@customsigncenter.com','eric@customsigncenter.com','james@customsigncenter.com','jeff@customsigncenter.com','john@customsigncenter.com','jreed@customsigncenter.com','judy@customsigncenter.com','justin@customsigncenter.com','marcus@customsigncenter.com','mary@customsigncenter.com','michael@customsigncenter.com','nathan@customsigncenter.com','sam@customsigncenter.com','scott@customsigncenter.com','tturner@customsigncenter.com','teryl@customsigncenter.com','timh@customsigncenter.com','tim@customsigncenter.com'];
	//var modalLink = '<a class="modalLink" rel="modal:open"><img src="assets/write-circle-green-128.png" title="edit"</a>'*/
	// var liIndex; //hold the index position of the active list we're referencing in code.
	

function clearAlert() {
  window.clearTimeout(timeoutID);
}

$(document).ready(function (){
	
	//set js vars using query string parameters of the URL
	getUrlParams(window.location.search.slice(1));	
	/*
	
	option             		reserved for
	id = reschedule		calendar
	0					close
	1					team color
	2					team color
	3					team color [reserved]
	4					team color [reserved]
	5					Install (subsidary)
	6					SubInstall (contractor)
	7					CSC Trans				
	8					Shipping
	9					Customer PU
	10					Unassigned  (Yellow backgr)
	******** begin icon fonts ***********************
	11					UPS	ic-ups	
	12					Return Trip				
	13					Completed
	14					Comp + Invoiced
	15					2-man
	16					100ft Crane 
	17					Parts  (red border hold)
	////////removed 18					On Hold (class red border)
	19					Permit Inspection Req. (red border hold)			
	20					Permit Inspection Appr. or Not Required. (red border removed)
	21					Permit Info 
	22					Permit Required (red border hold)
	
	
	*/
	$usr = $("#username").text()
	
	contextMenu = $('<div id="divContextMenu" style="display:none">'+ 
	'<input id="reschedule" type="text" placeholder="reschedule" />'+	
	'<div class="container"><div class="nav"><ul id="ulContextMenu">'+	
	    '<li id="t0" onclick="jobAssignment(0, this)" option="0" style="text-align:right;color:red">x Close</li>'+
	    '<li>ASSIGN<ul><li id="t1" onclick="jobAssignment(1, this)" option="1">Team 1</li>'+
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
		'</ul></li>'+
		'<li id="copy" onclick="jobAssignment(13, this)" option="13">Copy This Job</li>'+
	    '<li id="delete" class="delete" onclick="jobAssignment(11, this)" option="11">Delete Entry</li>'+
	'</ul></div></div></div>');
	
	 $( '#reschedule' ).pickadate(
		{
			format: 'mm/dd/yyyy',
			formatSubmit: 'mm/dd/yyyy',
		}
	);		
	
	/*
     var clipboard = new Clipboard('.copy',{
	  target: function(trigger) {        	
		  if( trigger.hasAttribute('data-clipboard-target')){				  
			  return trigger.nextElementSibling;
		  } else {			  
			  trigger.text('Insert Copied Job');
			  trigger.className = "paste";			  
			  //return the text content of the LI element that is subject to the opened Actions Menu.
			  return editableLI;
		  }
		  
	    }		
	});

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
*/
	
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
	
	


 document.onkeypress = function() 
 {
    _idleSecondsCounter = 0;
 };
 document.onclick = function() 
 {
    _idleSecondsCounter = 0;
 };
 document.onmousemove = function()
 {
    _idleSecondsCounter = 0;
 };

 window.setInterval(CheckIdleTime, 2000); //checks every 2 seconds
	
	
	
	curCompany=$( "#companyCalendar option:selected" ).val();
	loadCalendar(curCompany,-1,-1);
	$("#pageTitle").html(curCompany + " WIP Calendar");
	$('#companyCalendar').on('change', function() {
  		//$("#company" ).val( this.value ); // or $(this).val()
		curCompany=$( "#companyCalendar option:selected" ).val();
		$("#pageTitle").html(curCompany + " WIP Calendar");
		teamNamesHTML();
		loadCalendar( curCompany,-1,-1 );
		addListenersToDom("true");		
		
		/*if(curCompany !== "Custom Sign Center"){
			alert('Planned Update: Calendar Jobs Will Change for Each Company.');
		}*/
	});
	//event trigger submit a company cal request
	/* Hide Submit to Load new Company Calendar.  Select List on Change now fires the submission
	   New Calendar will now load on that trigger for each selected company.
	$("#loadCalendarForm").on("submit", function(e){
		e.preventDefault();
		var str = $( "#loadCalendarForm" ).serialize(); //selected company name
		loadCalendar( str['companyCalendar'],-1,-1 );
		addListenersToDom();
	});*/

	
	
	
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
   justReloaded = 1;
	
   timeoutID = window.setTimeout(addListenersToDom, 20);	
   
   alert('Feature Improvement: To Add User Notes, Dbl-Click a Job.  Turns Green.  Click it once more, an input box and SAVE btn appear.  Type in your note and click save.  1st 3 chars of your username are prepended to your note in red.  Your note is italic and unbolded to make it stand apart from the CASper record.  Change your mind?  Don\'t input any text and click save to exit.');
	
	
}); // doc ready.

//callers set status to 'start' or 'end';
function wait(status){
	if(status=='start') { $( "#wait" ).removeClass( "hide" ); }
	else { $( "#wait" ).addClass( "hide" ); }
}

function addListenersToDom(showTeamsBool = "true")
{
	 var editboxes = document.getElementsByClassName('edit');	 
	// alert("editboxes is: " + editboxes);
	 
	 $.each(editboxes, function(i, elem){		 
		 editboxes[i].addEventListener('dblclick', startEdit, true);
		 //var dateBox = editboxes[i].parentNode; //parent of the edit box.
		//evt.stopImmediatePropagation();stopPropagation()
		
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
	 
	 //remove hide class from any hidden LI elements with a new loading of the page.
	 if(justReloaded === 1)
	 {	//reset the toggling variable to false
		 justReloaded = 0;	 
		 		 
	 }
	 
	   //hide the first and last div in each .row (sundays and saturday columns)
/*    var eachMonth =  $("#weeks").find(".month");
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
    });	*/
    //remove hide class
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
	
	
//idle user function:
function CheckIdleTime() {
    _idleSecondsCounter++;
    var oPanel = document.getElementById("SecondsUntilExpire");
    if (oPanel)
        oPanel.innerHTML = (IDLE_TIMEOUT - _idleSecondsCounter) + "";
    if (_idleSecondsCounter >= IDLE_TIMEOUT) {
        var answer = confirm("You've been idle 15 mins.\nPlease click OK to continue session.\nTo close this session click Cancel.");
        if (answer) 
        {
            //document.location.href = "login.php";   
            _idleSecondsCounter=0;
        }
        else{
            //window.open('', '_self', ''); window.close();
            document.location.href = "login.php";
        }

    }
 }
	
	
	
	

function addNewLine(elem, parentClass){			
					
			var newList = $("<li contenteditable='false' class='lineEntry unassigned newEntry' title='Right Click for Options'><span id='admin-note' contenteditable='false'>&gt;</span></li>");		
			//the ul 
			var ulTarget = $(elem).parents(parentClass).children('.edit');
			$(ulTarget).append(newList);
			var newEntry = $(ulTarget).children(".newEntry")
			
			//addEventListenerToOne(newEntry);
	
			//pass this new LI obj to the func that creates the evt handler for it.		
			bindListeners4EachList(ulTarget);	
			$(newEntry).removeClass("newEntry");
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
			   
			     e.preventDefault();
				
				$(document).find('.context-style').removeClass('context-style');
				
			
				 var wrapper;
				 var parentWrapperOffset;

				 if($("#modal").is(":visible")){
					 //use the ul.edit as the reference for positioning popup menu
					 //console.log("Modal is visible");
					 wrapper = $("#modal");
					 parentWrapperOffset = 100;
				 } else {
					 //console.log("Modal is NOT visible");
					 wrapper = $(newList).closest('.date'); //The date box
					 parentWrapperOffset = $(newList).closest("#weeks").offset(); //offset of wrapper of the parent (i.e., #weeks)
				 }				

				 var parentOffset = wrapper.offset();  //date's parent-div's left/top offset from dom window
				
				 var relX, distX;
				 var relY =  wrapper.scrollTop();
				 // px left distance betw/ weeks wrapper & user's date cell
				 distX = parentOffset.left - parentWrapperOffset.left; 
				 // px top distance betw/ weeks wrapper & user's date cell
				// var distY = parentOffset.top - parentWrapperOffset.top;

				 //console.log("parentWrapperoffset is " + parentWrapperOffset.left+". parentOffset is " + parentOffset.left);



				 //if( distX >= 250 ){
					 //show the popup menu to the left
					 relX = wrapper.scrollLeft() - $("#divContextMenu").outerWidth +30;
				// } else  {
					 //show the popup to the right
				//	 relX = wrapper.scrollLeft() +  wrapper.outerWidth();
				// }		

				
				$(wrapper).append($(contextMenu).css({
					left: relX,
					top: relY,
					display: 'inherit'
				 }));								
                   		
				
				setTimeout(function(){  
					 $( '#reschedule' ).pickadate(
						{
							format: 'mm/dd/yyyy',
							formatSubmit: 'mm/dd/yyyy',
						}
					);
				 }, 3000);		
		  						
		 });	
		 
	
	
}

/* iconSet properties: 'ups','unas','trip','crew','crane,'part','comp,'inv','info','inspr','inspa,'pappr'
/* option is the numeric value of the clicked menu item, obj is the LI DOM object clicked from the MENU */
function jobAssignment(opt, obj){
	
	wait('start');
	
	
	if( $(editableLI).hasClass("unassigned") &&  opt !== 0 && opt !== 13 ){		
		   removeUnassigned(opt);							 
	} 
	//some new option was selected, so clean LI assignment classes if this action is an assignment:
	if( parseInt(opt) >= 1 && parseInt(opt) <= 8){		
		//remove all assignment CSS classes set in LI:
		$(editableLI).attr("class", "lineEntry");
	}	
	
	//0 = close menu window	
	if(opt == '0'){
		
		//1st check to see if a reschedule data change had been made (i.e., move entry to new date cell if applicable)
		//hidden input inside the contextmenu wrapper div #divContextMenu used in the datepicker.
		//clicking a date stores that date to the value attribute of the checkNewEntryDate DOM element.
		//value="mm/dd/yyyy"
		var checkNewEntryDate = $("#divContextMenu").children("input[type='hidden']")[0];
		
		if(typeof checkNewEntryDate !== 'undefined' )
		{			
			//we need to move the entry to its new scheduled day
			//format is a string like mm/dd/yyyy
		     //console.log(checkNewEntryDate);
			var newDate = $(checkNewEntryDate).val();
			if(newDate.length > 5) //if the content is stored there.
			{
				
				// set the value back to empty?				
				$(obj).closest("#divContextMenu").children("input[type='hidden']").prop('value','')[0];
				//get LI's Job Entry HTML that we need to move to newDate's cell		
				
				 		 
				//$(contextMenu).remove();	
				// remove CSS marking the job as active for editing with the context menu:
				$(editableLI).removeClass('context-style');
				//tag it to use outerHTML
				//editableLI is the global LI DOM el that the context menu is trying to work with.
				var clonedLIhtml;
				
				   clonedLIhtml = $(editableLI).clone(true,true); //clone includes the <li> tag just like outerHTML does.
				   				 
				 			 
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
				rescheduleJob(dateParts,editableLI,clonedLIhtml);
			}
		    else
		    {
			    //before closing the menu, gotta set the contenteditable on the parent LI to 'false'.
			    
			    // close the style options menu
			   //console.log("remove contextmenu called");
			    $(contextMenu).remove();
			    $(editableLI).removeClass('context-style');
			    	
		    }
		}
		else
		{
			//console.log("Problem: checkNewEntryDate is undefined");
			// close the pop up menu
			$(contextMenu).remove();
			//$(editableLI).removeClass('context-style');
			//TODO: add a trigger to focus on the error message ctr at top of page
			giveNotice('<span style="color: #FF0000">Failed</span>: A problem was encountered trying to parse your date change request.');
		}
		     $(contextMenu).remove();
		//if($(editableLI).hasClass("context-style")){
			$(editableLI).removeClass("context-style");
		//}
		
		
	}
	
	//var editableLI = $(obj).parents('li.lineEntry');	//the list we want to style or act upon
	
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
	 	if( $(editableLI).hasClass('hold') ) addHoldStatus(false, editableLI);		 

		if( $(editableLI).hasClass('completed') === false )
		{			
			$(editableLI).addClass('completed');			
			return;
		}	
	 }
	 */
	 // II. HOLD OPTION SELECTED?
	 if( $(obj).attr("id") === 'hold' ) //user clicked 'On Hold' opt
	 {		
		// alert('You selected ID hold');
		if( $(editableLI).hasClass('hold') === false )
		{		
			addHoldStatus(true, editableLI);			
		}	
		else 
		{
			addHoldStatus(false, editableLI);							 
		}
	 }
	 // III. DELETE OR JOB COMPLETED OPTION SELECTED?
	 else if( $(obj).attr("id") === 'delete' || $(obj).attr("id") === 'completed')
	 {
		 if($(obj).attr("id") === 'delete'){
		 	var r = confirm("'OK', Permanently Delete this Entry? 'CANCEL' to stop deletion.");
		 	if (r == true) {
    				$(editableLI).remove();
			}
		 } else {
			 
			 $(editableLI).addClass( 'completed' );
		 }
		 		
	 }
	
	else if ( $(obj).attr("id") === 'copy' ){
		
		//copy the job's contents for a paste operation using clipboard plugin.
		
		//get the read target (the clicked obj's job content) and set obj.attr to it "data-clipboard-target". 
		
		//use clone to get event handlers to allow adding unique admin notes or team assigns to each pasted job.
		
		if( $(obj).text() === "Copy This Job"){
			//copy to the clipboard, else paste
			$(obj).text("Insert Copied Job");
		
		     var JobLiClone = $(editableLI).clone(true);//includes LI, dont't want event handlers as they refer to the original job obj.	
			$(JobLiClone).removeClass('context-style');
			var span = $(JobLiClone).children('span').first();			
			$(span).removeAttr('id');//remove the id="job_10000"
			$(span).addClass( 'copyof' + $(span).text() ); //flag it with a class we can use to delete all jobs later.	
			
			// if we copy a copy, then [COPIED] gets duplicated each time. 
			// Check that there is no COPIED text, add if not.
			var ptrn =  /(?:\[CONT\.\])/g;
			if( ptrn.exec( $(span).text() ) === null  ){
				
				$(span).append(' [CONT.] ');
				
			}
			
			
			$("#hiddenClipboard").html('');
			$("#hiddenClipboard").html(JobLiClone);	
		
		//if more than one span, we prepend the 1st one, and append the others.
		} else {
			//paste the job
			$(obj).text("Copy This Job");
		     var LIST = $("#hiddenClipboard").children('li').first();
			var clonedLI = $(LIST).clone(true);		
			$(editableLI).closest('.edit').append(clonedLI);
			bindListeners4EachList( $(editableLI).closest('.edit') );
			return;
		}
		
		
		
		
		
		
	}
	
	
	 
	 // IV. A TEAM OPTION or OTHER OPTION SELECTED?
	 // user selected something other than "completed" or "delete" or "On Hold" 
	 // add and remove classes as needed from the target LI		   
	 else 
	 {
		  //possible options for icons: 'ups','unas','trip','crew','crane,'part','comp,'inv','info','inspr','inspa,'pappr'
		 var ico = '';
		 var removed = '';
		 $.each(iconSet, function(ndex,icoClass){
			 
			 if(opt === ndex){
				 
				 ico = true;
				 //get <i> children; check if any has the icon class already:
				 
				//<LI> = editableLI; .icons = <SPAN>
				 var spanTag = $(editableLI).find('span').first();
				 var iTags = $(spanTag).children('i'); //1st level <i>'s in span, if any.
				 
				 $.each(iTags, function(nd,iTag){			 

					 //toggle 'remove' if selected icon class already assigned this job:
					 if( $(iTag).hasClass(icoClass) ){
						 
						 $(iTag).remove();
						 removed = true;
						 return;

					 } 
				 });
				 
					 if(removed !== true){
						 //ready to add an icon.
						 //some icons need additional classes placed in the parent LI (e.g., unassigned)
						 
						 if(opt === 'unas'){
							 if( $(editableLI).hasClass("unassigned")){
								 
								 $(editableLI).removeClass("unassigned");
								 
							 } else {
								 
							 	$(editableLI).attr("class", "lineEntry unassigned");
								 
							 }
						 }
						 if(opt === 'ups'){
							 
							 if( $(editableLI).hasClass("t10") ){
								 
								 $(editableLI).removeClass("t10");
								 
							 } else {
							 
							 	$(editableLI).attr("class", "lineEntry t10");
								 
							 }
						 }
						 
						 
						 $newTag = '<i class="'+icoClass+'"></i>';
						 $(spanTag).prepend($newTag);					 
						 //console.log('Added the icon class')
						 return;
				 	}
				 
				return;
			 }
			
		 });
		 
			
		if(ico !== true){
		 
		 var CSSclass = $(obj).attr("id");
		 if('t0' !== CSSclass){
		 $(boxIDs).each(function(i,box) {			 
			// if the ignostic array item === the select option & the target LI doesn't contain that class...
			// box is iterations for classes t0, t1, t2, .... CSS team styling Classes
			if( box === CSSclass && $(editableLI).hasClass( CSSclass ) === false ) {	
				// console.log("box is: " +box+ " and the selected id is: "	+ CSSclass); 				 	
				 $(editableLI).addClass( box );
				// $(editableLI).prepend($icon);
				 //if user is setting unassigned as the class
				 //ensure completed cannot remain as a class
				 if(CSSclass == 'unassigned'){
					 $(editableLI).removeClass( 'completed' );
				 }
			}
			else if(box !== CSSclass)
			{	//console.log("Remove a class: box is: " +box+ " and the selected id is: "	+ CSSclass);
				//if($(targetList[0]).hasClass(CSSclass))
				//{
					$(editableLI).removeClass( box );
					//console.log( "Removed class " + box );	
				//}
			}
		 });	
	 }
	 }
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

function startEdit(e) {
	e.stopPropagation();
	
	 //the first two lines create a new li when you click in a cell. REMOVE: Only Create New Job Entries with the "+" btn.
	 /* 
	 	var btn =  $( this ).parents('.date').children('.day').children('.addNewLine');
	   	addNewLine(btn);   
	 */
	   	   
	  //$( this ).attr("contenteditable","true");
	  
	  //this refers to the .edit UL, which holds the LI's of job entries

	 var thisUL = $(this).closest(".edit"); //siblings if you want all the LIs in the UL
	 originalContent = $( this ).html(); //get the content of the cell before editing it (contenteditable = true) 
	 
	  //Make UL editable
	/* $(this).each(function(index,element){	
	 		
	 	
		//alert(originalContent);
		$(element).attr("contenteditable", "true");		  
	  });
	 */
	 
	   $( this ).addClass( "yellow-bg" );
		//make any existing children (lists) editable
		  $(thisUL).children("li").each(function(ct,listEl){
			
		     var inputArea;
			  
			  
			  
			 	  
		   $(this).on('click', function(){
			   	
				   inputArea = $(listEl).append('<div id="x"><button onclick="saveNote(this)">Save</button><br><input type="textarea" id="y" value="" /></div>');
				   //inputArea = $('#x');
				   $(inputArea).focus(); //set cursor	
			        $(listEl).unbind('click');
			    
		    });			  
		
		

		    //$(listEl).children("span").attr("contenteditable", "false");		
		    // add mouseout set editing false handler here:
		    //	
	    });
	   
}

// called by func start edit.  Param is a LI element set as editable.
// set a handler mouseout to stop editing.
function closeEditing( LIst ){
	
	var parentUL = $(LIst).closest(".edit");
	
	 $( parentUL ).on('mouseout', function(evt) {
		// $domObj refers to the UL of class .edit.
		//foreach edit node <li> that is empty, remove them...
		//addListenersToDom();
		//evt.stopImmediatePropagation();
		
		//$('li.lineEntry:empty').remove();
		//var listTags = $(domObj).children('li');
		//remove any hidden contextMenus, default New Job Lists, and br tags		
		//$(listTags).each(function() {
			/*
			if($(li).text() === '! New Job'){
				$(li).remove();
			}
			*/
			//remove editable attrib				  
		  	 //$(LIst).prop("contenteditable", "false"); 
		      $(LIst).removeAttr("contenteditable");
			//remove all break tags.
			$(LIst).find('br').remove();
	
			 
	//	});
		
		//$( domObj ).attr("contenteditable","false");
		 if( $(parentUL).hasClass("yellow-bg")){
    			$(parentUL).removeClass( "yellow-bg" );  
		 }
		//any li tags not empty, add them to the newContent var.
		
		newContent = $(parentUL).html(); //get the newly added cell contents; ContentEditable = false	
		if( originalContent !== newContent ){
			lastEditedCell.push( $(parentUL) );
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
		$(parentUL).off( 'mouseout' );
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

//form buttons' functions
function saveMonth(){
	
	
	var allContextCSS = $("#weeks").find('.context-style').removeClass('context-style');
	
	if(allContextCSS.length){
		$.each(allContextCSS, function(){
			
			$(this).removeClass('context-style');
			
		});
	}
	
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
		  url : "classes/calendar.php",
		  type: "POST",
		  data : data,
		  dataType:"json",
	   success: function(respData, textStatus, jqXHR)
	   {	
	   	  wait('end');
		  giveNotice('<span style="color: #009000">Success</span>: Your Updates have been Save.');
		  //giveNotice('<span style="color: #009000">Success</span>: Your Updates have been Save.');
		  //console.log(respData);
		  
	   },
	   error: function (jqXHR, textStatus, errorThrown)
	   {
		   wait('end');
		   giveNotice('<span style="color: #FF0000">Failed</span>: Server Response: "'+errorThrown+'"');
	   }	
  	});
}
	
function backUpCal(e){
	
	
		
	e.preventDefault;
	
	var today = new Date();
	var date = (today.getMonth()+1)+'-'+today.getDate() +'-'+today.getFullYear();
	/*
	if
	
	var filename = ;
	
	if($('input name[filenameSuffix]').val() !== '' && typeof $('input name[filenameSuffix]').val() !== 'undefined'){
		filename = date + '_' + $usr + '_' + curCompany.replace(' ', '-') + '_' $('input name[backupfilename]').val();
	} else {
		filename = date + '_' + $usr + '_' + curCompany.replace(' ', '-');
	} 
	*/
	
	$("#weeks").find('.context-style').removeClass('context-style');
	//if there is an open #divContextMenu, got to close it so it isn't saved into the xml.
	var $openMenus = $('.month').find('#divContextMenu');
	$($openMenus).each(function(i,menu){
		$(menu).remove();
		
	});
		
	wait('start');
	var htmlDataToSave = $("#weeks").html();
	var data={"content":htmlDataToSave,"company":curCompany};
	
	//update the appropriate cell node in the xml
	$.ajax({	
		  url : "classes/backup.php",
		  type: "POST",
		  data : data,
		  dataType:"json",
	   success: function(respData, textStatus, jqXHR)
	   {	
	   	  wait('end');
		  giveNotice('<span style="color: #009000">Success</span>: Your Updates have been Save.');
		  //giveNotice('<span style="color: #009000">Success</span>: Your Updates have been Save.');
		  //console.log(respData);
		  
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
//rescheduleJob(dateParts,editableLI,clonedLIhtml)
function rescheduleJob(moveToDate,$srcLI,jobHTML)
{
	//console.log("rescheduleJob called.");
	//must remove leading zero from the moveTo month and day (e.g., 07 to 7)
	if(moveToDate === null || moveToDate === '')
	{
		//console.log("moveToDate is null or empty...cannot complete rescheduling");
		return; //skip this whole plan... no date to move to	
	}//end if moveToDate is null		
	else
	{	
			/*moveToDate is array like:
				 [0] "07/30/2016"    
				 [1] "07"	
				 [2] "30"
				 [3] "2016"*/
	
	    $.each(moveToDate, function(i,v){
		    
		     /*moveToDate is array like:
				 [0] "7/30/2016"    
				 [1] "07"	
				 [2] "30"
				 [3] "2016"*/
		  if(i !== 0){ //skip the first string  
		    removeZeros=Number(v);
		  // console.log(v + ' converted by Number is now: ' + removeZeros);
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
		   //console.log('removeZeros, saved to moveToDate['+i+'] after conversion to str looks like: ' + moveToDate[i]);
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
		     }
	    });
	  
	    //1: Is current cal the yr and month we're moving the job to?
		    //if not, we need to load that year and month
	/*    if(year === moveToDate[3]) //current global year == the selected moved to date's year?
	    {	*/
		   
			    //the cur cal year is the moveTo location.
			    //get the moveTo cell as a DOM obj to append to:
			    //our identifier for the day can be the ID of the "+" image
			    //that is in the form of "d4" for 4th day of the month in the HTML DOM
			    saveToCell(moveToDate,jobHTML);
			    
			    //now remove the date that was in the datepicker input
			    $(contextMenu).children("#reschedule.picker__input").prop('value','');
			    $srcLI.remove();
			    //saveMonth();
			   addListenersToDom("false"); //ensure event handlers are added to the item's new location.	
	    
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
		if( $(m).attr("ordinal") === String(moveToDate[1]) && $(m).attr("yr") === String(moveToDate[3]))
		{
			//we found the month to move to.
			//console.log("found moveToDate month as month nmbr: " + moveToDate[1]);
			//this is the month we need to reschedule on
			//var $dates = $(m).find(".date"); //all the date cells
			//var $moveToCell = $(m).find("ul[modalid=d"+moveToDate[2]+"]");
			var $moveToCell;
			if($moveToCell = $(m).find("ul[modalid=d"+moveToDate[2]+"]")){
			 // console.log("movetocell is: " + $($moveToCell).text());
			  //console.log("jobHTML is " + jobHTML);
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
/*
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
*/
// end form buttons' functions

function  bindListeners4EachList(ULtags)
{
	//htmlReceivedFromXML = the html inside of div#weeks.  div.row are the top level elements
	//drill down to each list to bind handler
	//event handler for selecting installer team for new lists (right click)
	//console.log(htmlReceivedFromXML);
	var LItags = $(ULtags).children('.lineEntry');
	//var eachUL = $(eachWeekRow).children('ul.edit'); //each ul holding the list tags we need handlers on.
	var wrapper;
	var parentOffset;
	var parentWrapperOffset;
	$.each(LItags, function(i,posting){	
		//each list requiring a handler			
			$(posting).on("contextmenu", function(e) {
				
				 e.preventDefault();		 	
			      $(document).find('.context-style').removeClass('context-style');
				
				 $(posting).addClass("context-style");
				
				 editableLI = posting;
				 $( '#reschedule' ).pickadate(
					{
						format: 'mm/dd/yyyy',
						formatSubmit: 'mm/dd/yyyy',
					}
				);	
				var relX, distX;
				
				 if( $("#modal").is(":visible") ){
					 //use the ul.edit as the reference for positioning popup menu					
					 wrapper = $("#modal");
					 var off = $(wrapper).offset(); //offset of wrapper
					 //parentWrapperOffset = off.right + 300;
					 relX = parseInt(wrapper.scrollLeft() + $(wrapper).outerWidth());
				 } else {					
					 wrapper = $(this).closest(".date"); //The date box					 
					 var off = $("#weeks").offset(); //offset of wrapper of the parent (i.e., #weeks)
					 //parentWrapperOffset = off.left;
					 relX = parseInt(wrapper.scrollLeft() + wrapper.outerWidth());
				 }				
				
				 //parentOffset = $(wrapper).offset();  //date's parent-div's left/top offset from dom window				

				 // need relative position of the current day div
				 // compared to the top-left of the #weeks container.
				 // the resulting value of datePosition - #weeks position 
				 // will help to place the contextmenu left of right
				 // so as to avoid being directly over top of the active date cell.
				
				 
				 var relY = wrapper.scrollTop();
				 // px left distance betw/ weeks wrapper & user's date cell
				
				// distX = parentOffset.right - parentWrapperOffset; 

				 // px top distance betw/ weeks wrapper & user's date cell
				 // var distY = parentOffset.top - parentWrapperOffset.top;			

				// if( distX >= 330 ){
					 //show the popup menu to the right
					 
				// } else  {
					 //show the popup to the right
				//	relX = parseInt(wrapper.scrollLeft() -  wrapper.outerWidth());
				//	console.log(relX +" and relY " + relY);
				// }		

				 $(wrapper).append($(contextMenu).css({
					left: relX,
					top: relY,
					display: 'inherit'
				 }));										
		 });	
	});

}
function teamsShowAll(){
	
	//only show all if the modal popup is not visible.
	
	if( $('#modal').is(":visible") === false ){	
	
		$(listElements).each(function(i,entry)

		{   //console.log('showall teams fired for ' + $(entry).clone().html());
		//outputs <span id="job_98592">98592</span> WEN #05802 WO 98592 Parsippany, NJ
			$(entry).removeClass('hide');
		});

		var TeamChkBoxes = $("#teamSelection").find("input");
		$(TeamChkBoxes).each(function(x,box)
		{

				$(box).prop("checked", true);

		});
	} else {
		return false;
	}
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
	
	
function teamsHideAll(){
	//this.preventDefault();
	//alert('hide called');
	$(listElements).each(function(x,listItem)
	{  //console.log('Hide all fired');
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
				
				if($(mo).attr("ordinal") == nextMonth){
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
			var validMonth = monthHTMLexists( allMonths, "1", String( parseInt(year) +1 ) ); //does next mo exist in the DOM for on the year requested?
					
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
				    
				    if($(mo).attr("ordinal") == nextMonth){
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

function teamNamesHTML()
{	
	
	
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
			' Return Trip',
			' 2-Man',
			' 100ft Crane',
			' Part Needed',
			' Completed',
			' Completed &amp; Invoiced',
			' Info Needed',
			' Inspection Required',
			' Inspection Approved',						
			' Prmt Compl/Not Required'
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
				li0 + '21' + li1 + '\'pappr\'' + li2 + 'pappr' + li3 + '<i class="ic-p-appr"></i>' + assignLabels[20] + li4				
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
			' Return Trip',
			' 2-Man',
			' 100ft Crane',
			' Part Needed',
			' Completed',
			' Completed &amp; Invoiced',
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
			' Return Trip',
			' 2-Man',
			' 100ft Crane',
			' Part Needed',
			' Completed',
			' Completed &amp; Invoiced',
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
			' Return Trip',
			' 2-Man',
			' 100ft Crane',
			' Part Needed',
			' Completed',
			' Completed &amp; Invoiced',
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
			' Return Trip',
			' 2-Man',
			' 100ft Crane',
			' Part Needed',
			' Completed',
			' Completed &amp; Invoiced',
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
			' Return Trip',
			' 2-Man',
			' 100ft Crane',
			' Part Needed',
			' Completed',
			' Completed &amp; Invoiced',
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
		
		if( team !== '' ){
			//console.log(i + " will be a LIST")
			$(l).html(team);
			$(lpar).removeClass('hide');
			//$("#t"+(i-1)).html(team);
			//popup opt menu on r-clk of job entry	
		} else { 
			
			$(lpar).addClass('hide');
			
		}
		
	});
	
	
	
			
			
	
	
	var menuOptAssign = '';
	var menuOptJob = '';
	var menuOptPermt = '';
	
	//assignment menu with upto 9 options
	for($g=0; 10 >= $g; $g++){
		
		if(teamAssignment[$g] !== ''){
			menuOptAssign += teamAssignment[$g];
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
	    teamAssignment[16]+ //completed invoiced
	    '</ul></li>'+
	    '<li style="padding:12px 5px"><span style="color:#007F16">PERMIT</span><ul style="color:#007F16">'+    
	    teamAssignment[17]+
	    teamAssignment[18]+
	    teamAssignment[19]+
	    teamAssignment[20]+
	    '</ul></li>'+
	    '<li id="copy" data-clipboard-target="" data-clipboard-action="copy" onclick="jobAssignment(13, this)" class="copy" option="copy">Copy This Job</li>'+
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

<div id="copyemails" style="height:0px;width:0px;margin:0px;overflow:hidden">'alicia@customsigncenter.com','christina@customsigncenter.com','courtney@customsigncenter.com','dale@customsigncenter.com','dan@customsigncenter.com','debbie@customsigncenter.com','don@customsigncenter.com','doug@customsigncenter.com','emylee@customsigncenter.com','eric@customsigncenter.com','james@customsigncenter.com','jeff@customsigncenter.com','john@customsigncenter.com','jreed@customsigncenter.com','judy@customsigncenter.com','justin@customsigncenter.com','marcus@customsigncenter.com','mary@customsigncenter.com','michael@customsigncenter.com','nathan@customsigncenter.com','sam@customsigncenter.com','scott@customsigncenter.com','tturner@customsigncenter.com','teryl@customsigncenter.com','timh@customsigncenter.com','tim@customsigncenter.com'</div>
<div id="print" class="hide"></div>
<!-- copy text of an element to clipboard... REQUIRES : No Libraries -->
	<!--<script src="assets/clipboard.min.js" type="text/javascript" ></script>-->
	
	<script>
		
		
		
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
		
		
		//create backup of cur cal
		function backup(){
			
			var $calHtml = $("#weeks").html();
			//var $usr = $("#username").text();			
			$json = {"html":$calHtml,"company":curCompany,"username":$usr};

			$.ajax({
				url: "classes/backup.php",
				type: "post",
				data: $json,
				dataType: "json",			
			     success: function(respData, textStatus, jqXHR){
					
				
				},
				error: function(respData, textStatus, er){
				
				
				
				}		    
			    
			 });
		
			
		} //backup()
		
		//getUrlParams
		function getUrlParams(queryString){
			

		  // get query string from url (optional) or window
		 // var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

		  // we'll store the parameters here
		  var urlParams = {};

		  // if query string exists
		  if (queryString) {

		    // stuff after # is not part of query string, so get rid of it
		    queryString = queryString.split('#')[0];

		    // split our query string into its component parts
		    var arr = queryString.split('&');

		    for (var i=0; i<arr.length; i++) {
			 // separate the keys and the values
			 var a = arr[i].split('=');

			 // in case params look like: list[]=thing1&list[]=thing2
			 var paramNum = undefined;
			 var paramName = a[0].replace(/\[\d*\]/, function(v) {
			   paramNum = v.slice(1,-1);
			   return '';
			 });

			 // set parameter value (use 'true' if empty)
			 var paramValue = typeof(a[1])==='undefined' ? true : a[1];

			 // (optional) keep case consistent
			 paramName = paramName.toLowerCase();
			 paramValue = paramValue.toLowerCase();

			 // if parameter name already exists
			 if (urlParams[paramName]) {
			   // convert value to array (if still string)
			   if (typeof urlParams[paramName] === 'string') {
				urlParams[paramName] = [urlParams[paramName]];
			   }
			   // if no array index number specified...
			   if (typeof paramNum === 'undefined') {
				// put the value on the end of the array
				urlParams[paramName].push(paramValue);
			   }
			   // if array index number specified...
			   else {
				// put the value at that index number
				urlParams[paramName][paramNum] = paramValue;
			   }
			 }
			 // if param name doesn't exist yet, set it
			 else {
			   urlParams[paramName] = paramValue;
			 }
		    }
		  }

		  return urlParams;
			
	}//getUrlParams
		
	function removeUnassigned(opt){
			
		   $(editableLI).removeClass("unassigned");
		
		   if(opt !== 'unas'){
			   //the span likely contains <i class="ic-flag"></i>
			   var flag = $(editableLI).children('span').first().children('.ic-flag');
			   if(flag){
			   	$(flag[0]).remove();
			   }
		   }
		
	}
		
		
	//'<div id="x"><button onclick="saveNote(this,'+listEl+')">Save</button><br><input type="textarea" id="y" value="" /></div>	
	// obj param references dom 'save button', from the above html.
	function saveNote(obj){
		var r = $(obj).siblings('input#y').val();
		var LI = $(obj).closest('.lineEntry');
	 // $(listEl).attr("contenteditable", "false");
	//	$(this).on('mouseleave', function (){  

		    //$(inputArea).unbind('dblclick');		 
		    var user = $usr.slice(0,3);
		    
		    var notes = ' [<i style="color:#f00">' + user + '</i>]: ' + r;			
		    if( notes.length > 35 ){		
				
				$(obj).parent('#x').replaceWith('<br><span class="admin-note">'+notes+'</span>');
				
			} else {
				
				$(obj).parent('#x').remove();
				
			}			

		  /*  if(notes.length > 9){				    
			    $(listEl).append('<span class="admin-note">'+notes+'</span>');
		    }
		    
			$(listEl).unbind('mouseleave');
			
	   }); 		  
*/



		closeEditing(LI);


	}
		
		//general toggle show hide; param is the target DOM element
		
		function toggleVisibility(target){
			var $t = $(target)
			if( $t.hasClass( 'hide' ) ){
				$t.removeClass( "hide" );
			} else {
				$t.addClass( "hide" );
			}
			
		}
		
		
		
	</script>
	<div style="visibility:hidden;padding:0;margin:0;height:0" id="hiddenClipboard"></div>
</body>


</html>
