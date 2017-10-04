<?php  


	
			header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past



		if(!isset($_SESSION)){
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
	

function auto_version($file)
{
  if(strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file))
    return $file;

  $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
  return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}

?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title></title> 
   <!--styles-->   
   <link rel="stylesheet" href="<?php echo auto_version('styles/directory.css'); ?>" type="text/css" media="screen" />
  <!-- <link href="styles/print.css" rel="stylesheet" media="print"> -->
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
   <link rel="stylesheet" href="assets/pickadate.js-3.5.6/default.css">
   <link rel="stylesheet" href="assets/pickadate.js-3.5.6/default.date.css">
   <link href='https://fonts.googleapis.com/css?family=Kaushan+Script&effect=3d-float' rel='stylesheet' type='text/css'> 
   
    
</head>
<body>

<div class="content-row">
<pre style="text-align:center"><span style="font-size: 12px; background:#FEFFF3;padding:5px 8px;color:#419200; border: 1px dotted #8AC72D">Today: <span id="date"></span> [ <span id="curTime"></span> ]</span><br><br>
</pre>
<h1 id="pageTitle" class="cursive font-effect-3d-float" style="margin: 6px auto;text-align:center;color:#000"></h1>
	<h4 style="color: #CC220A; text-align:center; padding: 12px 0; background-color: #DDE95B">This is an <u>Outdated Version</u> (1.02).  An Update to the Calendar (at <a href="dev/index.php">calendar/dev</a>) is Awaiting Approval.</h4>
<br />
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
	<input type="text" id="search" class="form form-control" placeholder="Search For Employee" style="background-color: lightyellow;height:22px;margin:10px 0;padding:5px">
	<div id="srchResult" class="hide" style="background-color: #86C73A; color:#0B6F93; padding:10px 25px"></div>
</form>
<br />
<div class="clearfix">
<div class="fifty-pct">
<h3 style="margin: 2px auto 5px auto;color:#8AC72D" class="cursive">Choose a Company Calendar</h3>

<form action="" method="post" name="loadDirectory" id="loadDirectoryForm">
	<select name="companyDirectory" id="companyDirectory" >
     	<option value="Custom Sign Center" selected>Custom Sign Center</option>
          <option value="JG Signs">JG Signs</option>
          <option value="Marion Signs">Marion Signs</option>          
          <option value="Boyer Signs">Boyer Signs</option>
          <option value="Outdoor Images">Outdoor Images</option>
          <option value="MarionOutdoor">Marion-Outdoor</option>
       </select>
       <!--<input type="hidden" name="company" id="company" value="Custom Sign Center" />-->
      <button class="smBtn" onclick="loadDirectory()" name="submitBtn" style="margin-left: 15px">Get Employee List</button>
 </form>
 </div>
 <div class="fifty-pct" style="padding-left: 50px">
 </div>
 </div> <!--end clearfix-->
 <br/>
 <div class="clearfix">
      
       
  </div><!--end clearfix-->



</div>

<div class="content-row" id="message">
</div>

<div id="container" class="clearfix">
<div id="directory">

	</div>
	
         </div>
         <div id="calFooter">App ver. <?php include('/home/custo299/public_html/calendar/backup/ver.php') ?>. 2015 - <?php echo date('Y') ?> &copy; Custom Sign Center, Inc. -- All Rights Reserved.</div>         
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
	//placeholders for values in the employee output HTML.
	var rowID ='';
	var empName ='';
	
	//default company on 1st page load.
	var curCompany = {"coid":"0","name":"Custom Sign Center","abbrev":"csc"};
	var employees={}; //list of employees to display
	
		
	//var $spinner = $("#wait");
	var emails = '<div id="copyToClipb">alicia@customsigncenter.com;<br>christina@customsigncenter.com;<br>courtney@customsigncenter.com;<br>dale@customsigncenter.com;<br>dan@customsigncenter.com;<br>debbie@customsigncenter.com;<br>don@customsigncenter.com;<br>doug@customsigncenter.com;<br>emylee@customsigncenter.com;<br>eric@customsigncenter.com;<br>james@customsigncenter.com;<br>jeff@customsigncenter.com;<br>john@customsigncenter.com;<br>jreed@customsigncenter.com;<br>judy@customsigncenter.com;<br>justin@customsigncenter.com;<br>marcus@customsigncenter.com;<br>mary@customsigncenter.com;<br>michael@customsigncenter.com;<br>nathan@customsigncenter.com;<br>sam@customsigncenter.com;<br>scott@customsigncenter.com;<br>tturner@customsigncenter.com;<br>teryl@customsigncenter.com;<br>timh@customsigncenter.com;<br>tim@customsigncenter.com</div>';
	/* for testing only var emailRecipientsCSC = ['info@signcreator.com','cf_is_here@hotmail.com','chris@customsigncenter.com','tim@customsigncenter.com']; */
	var emailRecipientsCSC = ['alicia@customsigncenter.com','christina@customsigncenter.com','courtney@customsigncenter.com','dale@customsigncenter.com','dan@customsigncenter.com','debbie@customsigncenter.com','don@customsigncenter.com','doug@customsigncenter.com','emylee@customsigncenter.com','eric@customsigncenter.com','james@customsigncenter.com','jeff@customsigncenter.com','john@customsigncenter.com','jreed@customsigncenter.com','judy@customsigncenter.com','justin@customsigncenter.com','marcus@customsigncenter.com','mary@customsigncenter.com','michael@customsigncenter.com','nathan@customsigncenter.com','sam@customsigncenter.com','scott@customsigncenter.com','tturner@customsigncenter.com','teryl@customsigncenter.com','timh@customsigncenter.com','tim@customsigncenter.com'];
	//var modalLink = '<a class="modalLink" rel="modal:open"><img src="assets/write-circle-green-128.png" title="edit"</a>'*/
	// var liIndex; //hold the index position of the active list we're referencing in code.
	

	var row = `<div class="row headers">
				<div class="inline sixt">Name</div>
				<div class="inline sixt">Phone</div>
				<div class="inline sixt">Ext</div>
				<div class="inline sixt">Cell</div>
				<div class="inline sixt">E-Mail</div>
				<div class="inline sixt">Dept</div>
			</div>`;
	
	
	
function clearAlert() {
  window.clearTimeout(timeoutID);
}

$(document).ready(function (){
	
	
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
		$( $editULs ).each(function(i,ulEl){
			
			if( $(ulEl).find('li').length < 1){
				$(ulEl).parent('.date').addClass('hide');				
			}
			
		});
	
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
		
	var data = {"company":emailRecipientsCSC,"company":curCompany,"calendar":calendarHTML}; 
	
	    $.ajax({	
			 url : "classes/PhoneDirectory.php",
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
  
	AJAXsynchronous("read");
	
	
   
}); // doc ready.

//callers set status to 'start' or 'end';
function wait(status){
	if(status=='start') { $( "#wait" ).removeClass( "hide" ); }
	else { $( "#wait" ).addClass( "hide" ); }
}
	
	
function loadDirectory(){	
	curCompany.coid = $( "#companyCalendar option:selected" ).val();
	curCompany.name = $( "#companyCalendar option:selected" ).attr("name");
	curCompany.abbrev = $( "#companyCalendar option:selected" ).attr("abbrev");	
	$("#pageTitle").html(curCompany.name + " Employee Directory");	
	
	
	AJAXsynchronous("read", curCompany); //this function WAITS until the response is completed
									 //before returning to caller.
	
}	

function AJAX(method, data){
	
	$.ajax({		  
		  url : "classes/PhoneDirectory.php",
			 type: "POST",
			 data : data,
			 dataType:"json",
		  success: function(res, textStatus, jqXHR)
		  {			
		  	 wait('end');
			 
			 employees = res;
			 outputListToView();			
			 
		  },
		  error: function (jqXHR, textStatus, errorThrown)
		  {
			 wait('end');
			// giveNotice('<span style="color: #009000">Success</span>: Calendar has been Emailed to Your Recipients.');
		  }	  
		  
	  });

}
	
function outputListToView(){
	//console.log(employees);
	//return;
	
	/* 
		[ {"2":[{"id":7,"idfield":8,"value":"Jeff Dalrymple","coid":0},{"id":8,"idfield":9,"value":"7","coid":0},{"id":9,"idfield":10,"value":"jeff@customsigncenter.com","coid":0},{"id":10,"idfield":11,"value":"240","coid":0},{"id":11,"idfield":12,"value":"614-300-4240","coid":0},{"id":12,"idfield":13,"value":"614-313-6700","coid":0}]},
		
		{"4":[{"id":19,"idfield":8,"value":"Tom Bradford","coid":0}]},
		
		{"5":[{"id":25,"idfield":8,"value":"Chris Nichols","coid":0},{"id":26,"idfield":9,"value":"6","coid":0},{"id":27,"idfield":10,"value":"chris@customsigncenter.com","coid":0},{"id":28,"idfield":11,"value":"269","coid":0},{"id":29,"idfield":12,"value":"614-300-4241","coid":0},{"id":30,"idfield":13,"value":"","coid":0}]},
		
		{"6":[{"id":31,"idfield":8,"value":"Amber Snyder","coid":0},{"id":32,"idfield":9,"value":"11","coid":0},{"id":33,"idfield":10,"value":"amber@jgsignservices.com","coid":0
	*/
	var employeesArray = [];
	var row = '';	
	var empls = Object;
	var ct=0;
	
	
		var row = '<div class="row headers">'+
				'<div class="inline sixt">Name</div>'+
				'<div class="inline sixt">Phone</div>'+
				'<div class="inline sixt">Ext</div>'+
				'<div class="inline sixt">Cell</div>'+
				'<div class="inline sixt">E-Mail</div>'+
				'<div class="inline sixt">Dept</div>'+
			'</div>';
	
	
	
	//id: 1, name: "LL FREE", ph: "800-522-2934", cell: "    ", ext: "", fax: "", email: "TOLL FREE", id_user: null, coid: "0",
	
	Object.keys(employees).forEach(function(key) {
    		//console.log(key, employees[key]);	
		
		//conditional values for null
		var n = (employees[key].name === null ? ' - - -' : employees[key].name);
		var p = (employees[key].ph === null ? ' - - -' : employees[key].ph);
		var x = (employees[key].ext === null ? ' - - -' : employees[key].ext);
		var c = (employees[key].cell === null ? ' - - -' : employees[key].cell);
		var e = (employees[key].email === null ? ' - - -' : employees[key].email);
		var d = (employees[key].dept === null ? ' - - -' : employees[key].dept);
		
		
		
		var nextRow = '<button class="smBtn inline three" empid="'+ employees[key].id +'" onclick="editEmployee(this)">Edit</button><div class="row">'+
				'<div class="inline sixt n">'+n+'</div>'+
				'<div class="inline sixt p">'+p+'</div>'+
				'<div class="inline sixt x">'+x+'</div>'+
				'<div class="inline sixt c">'+c+'</div>'+
				'<div class="inline sixt e">'+e+'</div>'+
		    		'<div class="inline sixt d">'+d+'</div>'+
				//'<div class="inline sixt n">'+employees[key].dept+'</div>'+
			'</div>';
		row = row + nextRow;
		
		
	});
	
	$('#directory').append(row);
	
	
		/*
		$(employees[i]).each( function(ind, val){
			
			
				console.log(val['id']);
			if($ct = employees['idrecord']){			
				employeesArray.push(v);		
			} else {
				if(employeesArray.length > 0){
					empls.$ct = employeesArray;
					employeesArray = [];
				}
				$ct++;
			}
		
			
			
		});
		*/
	
	/*
	$.each(empls, function(j,vv){
		
		console.log(this.value);
		if(this.id === 7){
			//empl name field
			rowID = this.idrecord;
			empName = this.value;			
		}
		if(this.id === 9){
			//email addr
			
		}
		if(this.id === 10){
			//ph ext
			
		}
		if(this.id === 11){
			//ph 
			
		}
		if(this.id === 12){
			//cell
			
		}
		
		$("#container").append(row);
		
	});
	
	*/
	
	
	
}
	
function AJAXsynchronous($method){
	
	var data = {'company': curCompany.name, 'coid':curCompany.coid, 'method': $method};
	console.log("Ajax called.");
	$.ajax({		  
		  url : "classes/PhoneDirectory.php",
			 type: "POST",
			 data : data,
			 dataType:"json",
		  success: function(res, textStatus, jqXHR)
		  {			
		  	 wait('end');
			// var t = JSON.stringify(res);
			 employees = res;
			 outputListToView();
			 /*  res looks like: 
			 
			 
			 [
			 {"id":7,"idfield":8,"idrecord":2,"value":"Jeff Dalrymple","coid":0},
			 {"id":8,"idfield":9,"idrecord":2,"value":"7","coid":0},{"id":9,"idfield":10,"idrecord":2,"value":"jeff@customsigncenter.com","coid":0},{"id":10,"idfield":11,"idrecord":2,"value":"240","coid":0},
			 {"id":11,"idfield":12,"idrecord":2,"value":"614-300-4240","coid":0},
			 {"id":12,"idfield":13,"idrecord":2,"value":"614-313-6700","coid":0},
			 
			 {"id":19,"idfield":8,"idrecord":4,"value":"Tom Bradford","coid":0},
			 {"id":20,"idfield":9,"idrecord":4,"value":"13","coid":0},{"id":21,"idfield":10,"idrecord":4,"value":"tom@outdoorimagesinc.net","coid":0},{"id":22,"idfield":11,"idrecord":4,"value":"281","coid":0},
			 {"id":23,"idfield":12,"idrecord":4,"value":"321-351-3021","coid":0},
			 {"id":24,"idfield":13,"idrecord":4,"value":"407-538-0668","coid":0},
			 
			 {"id":25,"idfield":8,"idrecord":5,"value":"Chris Nichols","coid":0},{"id":26,"idfield":9,"idrecord":5,"value":"6","coid":0},{"id":27,"idfield":10,"idrecord":5,"value":"chris@customsigncenter.com","coid" ETC.... ]
			 
			 */
			
			 
		  },
		  error: function (jqXHR, textStatus, errorThrown)
		  {
			
			 //giveNotice('<span style="color: #009000">Success</span>: Calendar has been Emailed to Your Recipients.');
		  }	  
		  
	  });

	
}
	
		
	function editEmployee(btn){
		
		//if btn text is "edit", change to "save" and import form inputs for the active row
		//else text is "save", pass the editable row to a save function to save updates.
		var editableRow = $(btn).siblings('.row').first();
		if($(btn).text() === 'Save'){
			//ready to save
			$(btn).text('Edit');
			updateRow(btn, editableRow);
		}
		else {
			//ready to edit....
			$(btn).text('Save');
			
			var columnValues = $(editableRow).children('div.inline');
			
			//get the content value of each column to copy 
			//it to the editable inputs form.
			for($b=0; columnValues.length > $b; $b++){
				
				var 'val'+b = columnValues[$b].textContent;				 
				
			}
			
			
			var editForm = '<form method="post" id="saveUpdates">'+
							'<input class="sixt" name="name" value="'+val0+'" />'+
							'<input class="sixt" name="ph" value="'+val1+'" />'+
							'<input class="sixt" name="ext" value="'+val2+'" />'+
							'<input class="sixt" name="cell" value="'+val3+'" />'+
							'<input class="sixt" name="email" value="'+val4+'" />'+
			  				'<input class="sixt" name="name" value="'+val5+'" />'+			    				
						'</form>';
			
			
			
			
			$(editableRow).append(editForm);
			
		}
		
		$(editableRow).addClass('editable');
		
		$(btn).text('Save');
		//set a new onclick attrb function to SAVE the row.
		btn.onclick = function(){ saveRow(); };
		//$(btn).attr('onclick', 'saveRow()');
		
		
		
	}
	
	//the form fields to update.  b button r row.
	function updateRow(b,r){
		
		//employee db id.
		var emplid = $(b).attr('emplid');
		
		//form to save = #saveUpdates;
		
		
		
		
		
	}
	
	</script>
	
</body>


</html>
