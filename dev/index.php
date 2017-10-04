<?php  			
error_reporting(E_ALL);		
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Or Date in the past

//error_reporting(E_ALL);

			if(!isset($_SESSION)){
				require_once('classes/session.php');
				$s = new Session;
				//echo 'Developer Notification: A new Session created and session.php loaded.';
			}

				
			$ses = $_SESSION['user'];
			// js console.log(json_encode($ses)) = { name: "chris", role: "admin", userId: 1, company: "All" }
			// possible values of 'company': (str) All,0,1,2,3,4 (equating to: *,csc,boy,mar,out,jg)
			// special company when logging into dev directory app is "Developer" which nevers claims the
			// admin token from db.
	
			if( $_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'mgr' && $_SESSION['user']['role'] !== 'Developer' ){
				//$user = unserialize($_SESSION['user']);
				//print_r($_SESSION['user']);
				
				//$_SESSION["user"] = array('name' => $_SESSION['user']['name'], 'role' => $_SESSION['user']['role']);
				$userURI = '';
				if(!empty($_SESSION['user']['name'])){
					$userURI = '?user='.$_SESSION['user']['name'];
					//$userURI .= '&role='.$_SESSION['user']['role'];
				} 
				
				header('Location: login.php' . $userURI);
			
				
				//echo $sesID;	TESTED: This new session start id does match the one in the uri from prior page.
				//and matches the one in the database for the active session.
				//if(!empty($query['sid'])) { $sesURI .= $query['sid'];}else{ $sesURI = '';};				
			} else {
					
				$role = ( $_SESSION['user']['role'] === 'admin' ? 'admin' : 'mgr');
				$roleBasedJsFile = ( $_SESSION['user']['role'] === 'admin' ? 'admin.js' : 'mgr.js');
				//if($query['user']){
				//$username =  $query['user'];
				$username = $_SESSION['user']['name'];	
				session_id() != NULL ? $sesID = session_id() : $sesID = '' ;
				
			} 


			//'company' is either "ALL" to view all companies (special admin), 
			// or num char (0=csc, etc) to view calendar for 1 company.
			if( isset( $_SESSION['user']['company'] ) ){
				
				switch( $_SESSION['user']['company'] ){
						
					case '10':
						$coHide = 'co_all';
						$curCo = 'All';
						break;
					case 'Developer':
						$coHide = 'co_all';
						$curCo = 'All';
						break;	
					case 'ALL':
						$coHide = 'co_all';
						$curCo = 'All';
						break;
					case '0':
						$coHide = 'co_csc';
						$curCo = 'Custom Sign Center';
						break;
					case '1':
						$coHide = 'co_boy';
						$curCo = 'Boyer Signs';
						break;
					case '2':
						$coHide = 'co_mar';
						$curCo = 'Marion Signs';
						break;
					case '3':
						$coHide = 'co_out';
						$curCo = 'Outdoor Images';
						break;
					case '4':
						$coHide = 'co_jg';
						$curCo = 'JG Signs';
						break;
						
				}
				
				
			}

	/*	
		
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
*/

	
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
   <link rel="stylesheet" href="<?php echo auto_version('/calendar/dev/styles/calendar.css'); ?>" type="text/css" media="screen" />
   
  <!-- <link href="styles/print.css" rel="stylesheet" media="print"> -->
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
   <link rel="stylesheet" href="assets/pickadate.js-3.5.6/default.css">
   <link rel="stylesheet" href="assets/pickadate.js-3.5.6/default.date.css">
   <link href='https://fonts.googleapis.com/css?family=Kaushan+Script&effect=3d-float' rel='stylesheet' type='text/css'> 
   <link rel="stylesheet" href="assets/icomoon/style.css">
    <link rel="stylesheet" href="styles/nav.css">
 <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw=" crossorigin="anonymous"></script>
    <!-- load appropriate js based on authenticated user's role. -->
<script src="assets/<?php echo $roleBasedJsFile ?>" type="text/javascript" charset="utf-8"></script>
</head>
<body>
<div class="content-row">
<pre style="text-align:center"><span style="font-size: 12px; background:#FEFFF3;padding:5px 8px;color:#419200; border: 1px dotted #8AC72D">Today: <span id="date"></span> [ <span id="curTime"></span> ]</span> <br>
<span><a href="contact.php" target="_blank" title="Opens Email Form in a New Window or Tab">REPORT BUGS</a><!--| <a href="help.html" target="_blank" title="WIP Support">HELP</a>--> | <a href="directory.php" target="_parent" title="Employee Phone Directory">EMPLOYEE DIRECTORY</a></span><br><br><span  style="text-align:center; margin: 8px auto 2px auto">Recommended Browsers (Avoid IE; MS Edge is OK)</span><br><img src="assets/compatible_browsers.png" title="Compatible Browsers for This Calendar App" style="text-align:center; margin: 0px auto 5px auto" />
</pre>

<h1 id="pageTitle" class="cursive font-effect-3d-float" style="margin: 6px auto;text-align:center;color:#000"></h1>
	<h3 style="margin: 14px auto;text-align:center;color:#4642A8;background:#DDE95B;padding:18px; text-align:center;font-family: 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, 'sans-serif'">
		APP DEVELOPMENT PLAYGROUND: This calendar is NOT SHARING nor SAVING Job Data with the Live Calendar.
		
	</h3>
	
<br />
<?php if($username) { 
	echo "<span style=\"margin: 2px auto 5px auto;color:#000000\" class=\"cursive\">Welcome <span id=\"username\">". $username ."</span>.</span>  
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
<form>
	<input type="text" id="search" class="form form-control" placeholder="Search and Click" style="background-color: lightyellow;height:22px;margin:10px 0;padding:5px">
	<div id="srchResult" class="hide" style="background-color: #86C73A; color:#0B6F93; padding:10px 25px"></div>
</form>
<br />

<div class="clearfix">
<div class="fifty-pct">
<h3 style="margin: 2px auto 5px auto;color:#8AC72D" class="cursive">Choose a Company Calendar</h3>

<form action="" method="post" name="loadCalendar" id="loadCalendarForm">
<select name="companyCalendar" id="companyCalendar" >
<?php if($curCo==='All') : ?>
	
     	<option class="csc" value="Custom Sign Center" selected>Custom Sign Center</option>
          <option class="jg" value="JG Signs">JG Signs</option>
          <option class="marion" value="Marion Signs">Marion Signs</option>          
          <option class="boyer" value="Boyer Signs">Boyer Signs</option>
          <option class="outdoor" value="Outdoor Images">Outdoor Images</option>
         <!-- <option class="marion outdoor" value="MarionOutdoor">Marion-Outdoor</option> -->
    
<?php elseif($curCo==='Custom Sign Center') : ?>    
	<option class="csc" value="Custom Sign Center" selected>Custom Sign Center</option>    
<?php elseif($curCo==='Boyer Signs') : ?>
      <option class="boyer" value="Boyer Signs">Boyer Signs</option>
<?php elseif($curCo==='Marion Signs') : ?>    
     <option class="marion" value="Marion Signs">Marion Signs</option>          
<?php elseif($curCo==='Outdoor Images') : ?>        
	<option class="outdoor" value="Outdoor Images">Outdoor Images</option>   
<?php elseif($curCo==='JG Signs') : ?>
  	<option class="jg" value="JG Signs">JG Signs</option>
<?php endif; ?>
        
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
      
      	
        	   <!-- column #1: listing of overdue jobs -->        
        	   <!-- show here, if any, jobs marked as OVERDUE -->
		   <div id="OverDueJobsList"></div>
		   
		   <!-- column #2: The Job Assignment Checkbox Groups -->     
      
        <div style="float:right; display:inline-block;max-width:990px; box-sizing:border-box" id="icons">
        
       <div name="teamdata" action="" id="teamSelection"> 
       	  <div class="iconrow">      
              <!--chkbx attrib is used to show / hide jobs having matched css class (i.e. t21) -->    	
               <input style="float:left" type="checkbox" id="select-21" name="t21" value="t21" checked="checked">               
               <div class="box-label due"> Overdue</div>
          </div>
          <br/><br/>
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
            <button class="morPad center btn save" name="update" onClick="saveMonth()">SAVE UPDATES</button> &nbsp;
		  <!-- <button class="morPad center btn undo" name="undo" onClick="editHistory('undo')">UNDO</button>
		   <button class="morPad center btn undoall" name="undoall" onClick="editHistory('undoall')">UNDO ALL</button>
		   <button class="morPad center btn redo" name="redo" onClick="editHistory('redo')">REDO</button> -->      
		  <?php if($role === 'admin') : ?>
			  <a style="float:left;margin-left:7px" href="csvimport.php" target="_blank">
			  <button class="morPad center btn import" name="import">IMPORT CSV</button></a> &nbsp;
		  <?php endif; ?>
        
          <button class="smBtn" onclick="teamsShowAll()">Show All</button> &nbsp;
          <button class="smBtn" onclick="teamsHideAll()">Hide All</button> &nbsp;
          <button class="smBtn" id="btnPrint">Print</button> &nbsp;
          <?php if($role==='admin') : ?>
			<button class="smBtn" id="btnEmail" title="Emails PDF Attachment.  
			See 'HELP', above, for best PDF formatting method.">Email</button> &nbsp;
          
          <button data-clipboard-target="div#prevEmailPopUp" data-clipboard-action="copy" class="copy smBtn" id="previewEmails" > Preview Recipients</button>
          <div id="prevEmailPopUp" class="hide" style="background:#3C8CB8; color:#ECC585; padding:12px; position:absolute; z-index:9999;">          
          </div>
          <?php endif; ?>
          
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

<script  src="assets/pickadate.js-3.5.6/picker.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/pickadate.js-3.5.6/picker.date.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/clipboard.min.js" type="text/javascript" charset="utf-8"></script>
<!--<script src="assets/pickadate.js-3.5.6/legacy.js" type="text/javascript" charset="utf-8"></script>-->
  <!-- shared js functions -->
<script src="assets/common.js" type="text/javascript" charset="utf-8"></script>	

<script>
	var role; //  e.g., admin, mgr
	var curCompany; 
	(function(){
		/* global scope vars */
		var ses = '<?php   echo json_encode($ses); ?>';
		console.log(ses);
		role = '<?= $role; ?>'; //  e.g., admin, mgr
		console.log(role);
		curCompany = <?php  $c = ( $curCo == 'All' ?  'Custom Sign Center' :  $curCo ); echo "'".$c."'" ?>;
	})();	
	
</script>



<div id="copyemails" style="height:0px;width:0px;margin:0px;overflow:hidden">'alicia@customsigncenter.com','christina@customsigncenter.com','courtney@customsigncenter.com','dale@customsigncenter.com','dan@customsigncenter.com','debbie@customsigncenter.com','don@customsigncenter.com','doug@customsigncenter.com','emylee@customsigncenter.com','eric@customsigncenter.com','james@customsigncenter.com','jeff@customsigncenter.com','john@customsigncenter.com','jreed@customsigncenter.com','judy@customsigncenter.com','justin@customsigncenter.com','marcus@customsigncenter.com','mary@customsigncenter.com','michael@customsigncenter.com','nathan@customsigncenter.com','sam@customsigncenter.com','scott@customsigncenter.com','tturner@customsigncenter.com','teryl@customsigncenter.com','timh@customsigncenter.com','tim@customsigncenter.com'</div>
<div id="print" class="hide"></div>
<!-- copy text of an element to clipboard... REQUIRES : No Libraries -->
<!--<script src="assets/clipboard.min.js" type="text/javascript" ></script>-->

</body>


</html>
