<?php  


	
			header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past



		if(!isset($_SESSION)){
			require_once('classes/session.php');
			$s = new Session;
			$_SESSION['user']['role']='user';
		}

		if( !isset($_SESSION['user']['role']) ){
			//$user = unserialize($_SESSION['user']);
			//print_r($_SESSION['user']);
			
			header('Location: login.php');

			//echo $sesID;	TESTED: This new session start id does match the one in the uri from prior page.
			//and matches the one in the database for the active session.
			//if(!empty($query['sid'])) { $sesURI .= $query['sid'];}else{ $sesURI = '';};				
		} else {
		//if($query['user']){
			//$username =  $query['user'];
			$username = $_SESSION['user']['name'];	
			$sesID =( session_id() != NULL ? session_id() : '' );
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
<a href="index.php?<?php echo 'user='.$username.'&sid='.$sesID ?>"><img src="assets/ico-calendarcell.png" title="Open WIP Calendar" style="width: 60px" /></a>
<pre style="text-align:center"><span style="font-size: 12px; background:#FEFFF3;padding:5px 8px;color:#419200; border: 1px dotted #8AC72D">Today: <span id="date"></span> [ <span id="curTime"></span> ]</span><br><br>
</pre>

<br />
<?php if($username) { 
	echo "<span style=\"margin: 2px auto 5px auto;color:#000000\" class=\"cursive\">Welcome <span id=\"username\">". $username ."</span>.</span>";  
	
} 
	?>
<div class="clearfix"><!--
<div class="fifty-pct">
<h3 class="cursive" style="color:#E14447">The Editing Features are not Implemented Yet</h3>

</div>
 <div class="fifty-pct" style="padding-left: 50px">
 </div>
 </div> --><!--end clearfix-->
 
 <br/>
<h1 id="pageTitle" class="cursive font-effect-3d-float" style="margin: 6px auto;text-align:center;">Custom Sign Center - Directory</h1>


	
 
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
         <div id="calFooter"><?php echo date('Y') ?> &copy; Custom Sign Center, Inc. -- All Rights Reserved.</div>         
	
   
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
	
	
	
	
function clearAlert() {
  window.clearTimeout(timeoutID);
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


$(document).ready(function (){
	
	
    
	
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


	

  
	AJAXsynchronous("read");
	
	
   
}); // doc ready.


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
	
	
		var row = '<div class="row" id="topHeaders">'+
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
		var n = (employees[key].name === null ? '' : employees[key].name);
		var p = (employees[key].ph === null ? '' : employees[key].ph);
		var x = (employees[key].ext === null ? '' : employees[key].ext);
		var c = (employees[key].cell === null ? '' : employees[key].cell);
		var e = (employees[key].email === null ? '' : employees[key].email);
		var d = (employees[key].dept === null ? '' : employees[key].dept);
		
		var nextRow = '<div class="row">'+
				'<div class="inline sixt n">'+n+'</div>'+
				'<div class="inline sixt p">'+p+'</div>'+
				'<div class="inline sixt x">'+x+'</div>'+
				'<div class="inline sixt c">'+c+'</div>'+
				'<div class="inline sixt e">'+e+'</div>'+
		    		'<div class="inline sixt d">'+d+'</div>'+
				//'<div class="inline sixt n">'+employees[key].dept+'</div>'+
			'<button id="editEmployee" class="smBtn inline three" empid="'+ employees[key].id +'" onclick="editEmployee(this)">Edit</button></div>';
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
		
		//is another editable record already open?  Close it first!
		
		
		if( $('#directory').find('.editable') ){
			
			var b = $('.editable').children('#btnEditGroup');
			cancel(b);	
			
		}
		
		
		
		
		var editableRow = $(btn).parent('.row').first();
		if($(btn).text() === 'Save'){
			//ready to save.  Hide Edit Btn.
			$(btn).removeClass('hide');
			updateRow(btn, editableRow);
		}
		else {
			//ready to edit....	
			$(btn).addClass('hide');
			
			var columnValues = $(editableRow).children('div.inline');
			
			var valsAr = [];
			
			//get the content value of each column to copy 
			//it to the editable inputs form.
			for( $b=0; columnValues.length > $b; $b++ ){
				
				valsAr[$b] = columnValues[$b].textContent;				 
				
			}
			
			var empid = $(btn).attr('empid');
			var editForm = '<form method="post" id="saveUpdates">'+
							'<input type="text" class="sixt" name="name" value="'+valsAr[0]+'" />'+
							'<input type="text" class="sixt" name="ph" value="'+valsAr[1]+'" />'+
							'<input type="text" class="sixt" name="ext" value="'+valsAr[2]+'" />'+
							'<input type="text" class="sixt" name="cell" value="'+valsAr[3]+'" />'+
							'<input type="text" class="sixt" name="email" value="'+valsAr[4]+'" />'+
			  				'<input type="text" class="sixt" name="dept" value="'+valsAr[5]+'" />'+	
						    '<input type="hidden" class="sixt" name="method" value="update" />'+
						    '<input type="hidden" class="sixt" name="id" value="'+empid+'" />'+
						'</form><div id="btnEditGroup">&nbsp; <button id="updateRow" class="smBtn inline four" empid="'+ empid + 
			    			'" onclick="updateEdit(this)">Save</button>'+ 
			    			'&nbsp; <button id="updateCancel" class="smBtn inline six"'+
			    			' empid="'+ empid +'" onclick="cancel(this)">Cancel</button>' + 
			    			'&nbsp; <button id="delete" class="smBtn inline six alertStyle"'+
			    			' empid="'+ empid +'" onclick="Delete(this)">Delete</button></div>';
			
			
			$(editableRow).append(editForm);
			
		}
		
		$(editableRow).addClass('editable ghosted');
		
		//$(btn).text('Save');
		//set a new onclick attrb function to SAVE the row.
		//btn.onclick = function(){ saveRow(); };
		//$(btn).attr('onclick', 'saveRow()');
		
		
		
	}
	
	//the form fields to update.  b button r row.
	function updateEdit(btn){		
		
		
		//console.log("updateRow called.");
		//employee db id.
		//var empid = $(btn).attr('empid');
		//alert('emp id is '+ empid );
		//id is not the id_user FK to table user.
		//it is the phonedirectory record's id.
		
		
		var reqData = $("#saveUpdates").serializeArray();
		
	/*	//form to save = #saveUpdates;
		$.each($inputs, function(k,v){
			
			var keyName = $(v).attr('name');
			reqData.keyName = $(v).text();	
			console.log("keyName is "+ keyName);
			
		});
		
	*/
		
		
	
		
		$.ajax({		
			url: "classes/PhoneDirectory.php",
			type: "POST",
			data: reqData,
			dataType: "json",		
		  	success: function(result, textStatus, jqXHR){
			
				
				$('#directory').html('');
			     //reload data from the database to show changes
				AJAXsynchronous("read");
				giveNotice(result);
				
			},
			failure: function(jqXHR, textStatus, errorThrown){

					console.log("Error: " +errorThrown);

			}	  	 
		
		});
		
		
		
		//close the editing form fields.
		cancel(btn);
		
		
		
	}
	
	function cancel(btn){
		
		//console.log('cancel called.');
		var $origRow = $(btn).closest('.editable');
		$($origRow).removeClass('editable ghosted');
		$($origRow).children('#editEmployee').removeClass('hide');	
		
		$($origRow).find('form#saveUpdates').remove();
		//remove all buttons except the 'Edit' Btn
		$($origRow).find('#btnEditGroup').remove();		
		
	}//cancel
	
	function Delete(btn){
		
		//record id for deletion
		var id = parseInt($(btn).attr('empid'));
		
		//get the empl's name	
		var empName = $("#saveUpdates").children("input [name='name']").text();
		
		if ( confirm('Permanently Delete ' + empName + '?' ) ) {		
			
			var reqData = {"id":id,"method":"delete","name":empName};
			
			$.ajax({		
				url: "classes/PhoneDirectory.php",
				type: "POST",
				data: reqData,
				dataType: "json",		
				success: function(result, textStatus, jqXHR){			

					$('#directory').html('');
					//reload data from the database to show changes
					AJAXsynchronous("read");
					cancel(btn);
					giveNotice(result.msg);

				},
				failure: function(jqXHR, textStatus, errorThrown){

					giveNotice(result.msg + " Error: " + errorThrown);

				}	  	 

			});
			
		} else {
			
			giveNotice('Action Aborted.');
			
		}
		
	}//delete
	
	
	
	
	</script>
	
</body>


</html>
