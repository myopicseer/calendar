<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Import CASper CSV to WIP Calendar</title>
<link href="styles/calendar.css" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Kaushan+Script&effect=3d-float' rel='stylesheet' type='text/css'>

<?php 
/*
login 
*/
//if($_POST['username']=='csv' && $_POST['password']=='csv' || $u == 'csv' && $p == 'csv'): ?>

<?php

// echo '<div style="visibility:hidden" id="u">csv</div><div style="visibility:hidden" id="p">csv</div>'; ?>

<style>

body{margin:0px;}
.menu{
float:left; width:100%; border-bottom: #288D9A 2px solid; margin-bottom: 30px;
background: rgb(230,240,163); /* Old browsers */
background: -moz-linear-gradient(top, rgba(230,240,163,1) 0%, rgba(210,230,56,1) 38%, rgba(195,216,37,1) 51%, rgba(219,240,67,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, rgba(230,240,163,1) 0%,rgba(210,230,56,1) 38%,rgba(195,216,37,1) 51%,rgba(219,240,67,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, rgba(230,240,163,1) 0%,rgba(210,230,56,1) 38%,rgba(195,216,37,1) 51%,rgba(219,240,67,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e6f0a3', endColorstr='#dbf043',GradientType=0 ); /* IE6-9 */
}
.menu img {cursor:pointer; float:left; margin: 20px 30px; margin-left: 34%};

</style>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

<script>
var file;
//on document load.
$(function() {	
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
	
});
//delete an existing csv file from the server.

function deletecsv(file){
	
/*
	readyState 	Holds the status of the XMLHttpRequest. Changes from 0 to 4:
	   0: request not initialized
	   1: server connection established
	   2: request received
	   3: processing request
	   4: request finished and response is ready
	   
	 status 	200: "OK"
	   404: Page not found
*/
/*
    var data={"file":file};
	
    var xhttp = new XMLHttpRequest();
    
    xhttp.onreadystatechange = function() {
	    
        if (xhttp.readyState == 4 && xhttp.status == 200) {
		   giveNotice('<span style="color: #009000">Success</span>: '+ xhttp.responseText +'.');
        }
    };
    xhttp.open("GET", "ajax_info.txt", true);
    xhttp.send();
*/	
	
	
	
	//ajax call to delete file.
	var data={"file":file};
	
	//update the appropriate cell node in the xml
	$.ajax({	
		  url : "classes/filedelete.php",
		  method: "POST",
		  data : data
		  //dataType:"json",		  
	 })		  
	 .done (function(response, textStatus, jqXHR) { 
		giveNotice('<span style="color: #009000">Success</span>: '+response+'.'); 
	 })
	 .fail (function(jqXHR, textStatus, errorThrown) { 
		giveNotice('<span style="color: #FF0000">Failed</span>: Server Responsed: "'+errorThrown+'"');
	 })
	 .always (function(jqXHROrData, textStatus, jqXHROrErrorThrown) { 
		giveNotice('<span style="color: #009000">Completed</span>'); 
		$("#phpMsg").html(''); //hide the delete file message.
	 });
}
function giveNotice(message){
	
	$( "div#message" ).fadeIn( 'slow', function(){
	    $( "div#message" ).css("display", "inherit");
	    $( "div#message" ).css("padding", "12px");
	    $( "div#message" ).css("height", "auto");
	    $( "div#message" ).html("<p>"+message+"</p>");
	}).delay( 1900 ).fadeOut('slow' );
	$( "div#message" ).css("display", "none");
	$( "div#message" ).css("padding", "0px");
	$( "div#message" ).css("height", "0px");
}

</script>
</head>
<body>
<div class="menu">

<a href="http://customsigncenter.com/calendar/"><img src="assets/ico-calendarcell.png" title="Open WIP Calendar" style="width: 60px" /></a>
<div style="text-align:center; float:left;margin-top: 40px"><span style="padding:5px 8px;color:#4911D0">Today: <span id="date"></span> [ <span id="curTime"></span> ]</span></div>
</div>
<div class="content-row">

	<h1 style="margin: 9px auto 0px auto;text-align:center;color:#8AC72D">Remove Old Data</h1>
     <h3 style="margin: 9px auto 30px auto;text-align:center;color:#7EAB26">Maintain a Lightweight WIP Calendar</h3> 
     <div style="text-align:center">  
    
     <div style="height:0;display:none" id="message"></div>
      <!-- The data encoding type MUST be specified as below -->
     
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="loadCalendar" enctype="multipart/form-data" id="loadCalendarForm" style="width:480px; margin: 30px auto; text-align:left; padding:20px; border-radius:9px; border: #E3ECD3 solid 1px">
    <div style="text-align:center">
    <p class="formLabel">Choose a Company Calendar to Cleanup</p>
         <select name="companyCalendar" id="companyCalendar" >
             <!-- Values match the XML node 'calendar' 'id' attribute value -->
              <option value="Custom Sign Center" selected>Custom Sign Center</option>
              <option value="JG Signs">JG Signs</option>
              <option value="Marion Signs">Marion Signs</option>          
              <option value="Boyer Signs">Boyer Signs</option>
              <option value="Outdoor Images">Outdoor Images</option>
        </select> 
           
          <br/><br/>     
          <!-- Select Date Range (inclusive) -->
          <span class="formLabel">Start Month (data erasure): &nbsp;</span> <input class="smBtn" name="eraseStartMo" type="text" /><br/><br/>
          <span class="formLabel">End Month (data erasure): &nbsp;&nbsp;</span> <input class="smBtn" name="eraseEndMo" type="text" />
      </div>
<br/><br/><br/>

          <div style="text-align:center"><input class="smBtn" type="submit" value="Clean Up" style="width:190px;" /> 
          </div>  
  </form></div>
	<br/>
	<div style="text-align:center"><a href="cvsimport.php" title="Update Calendars with New Data.">Import CVS Data Into Calendars</a></div>
	<br/>
  <span style="color: #8C8C8C;font-size:15px;">Footnotes:</span>
  <hr/>
  <p style="text-align:right;color: #8C8C8C; margin-top: 60px;font-size:13px;font-style:italic">2015 - <?php echo date('Y') ?> &copy; Custom Sign Center, Inc. -- All Rights Reserved.</p>  
</div>
 

</body>
</html>

<?php //import csv file and create or save to the calendar.xml

/* 	1. 	(up)Load a csv file of WIPs as a resource using html form controls.
	2.	Build csv data into well-formed assoc array
	3.	Load XML file as resource
	4.	Iterate through array:
			a. Update where mm/dd/yyyy node exists
			b. Create node where required, expanding the calendar.
	5.	Save XML with success message.
/*

/*
CSV may look similar to this:
job, custName, deadline
job0001,Sunoco,5/1/2016
job0002,Marathon,5/1/2016
job0003,Shell,5/2/2016
*/

//First, build into arrays by deadline values

//test data 
/*
$string_csv = 
'job0001,Sunoco,5/1/2016
job0002,Marathon,5/1/2016
job0004,Shell,5/2/2016
job0005,Shell on High,4/3/2016
job0006,Donatos,5/1/2016
job007,Maxwell\'s,3/04/2014';
*/


  
  
  // IF A FILE HAS BEEN UPLOADED
  if (!empty($_FILES))
  { 
  	// error_reporting(E_ALL);
 	libxml_use_internal_errors(true);
	// DEBUG: print_r($_FILES);
	 
	// save to the remote server
  	$save = new saveToCalendar;
	
	// upload csv.  Returns path to file.
	$file = $save->a();
	
	// parse csv file into array
	$save->b($file);
	/*if($arCSV === false){		
		$save->endMsg('Faild to open the uploaded csv file');
	}*/
	
	// Set class properties to POST value(s)
	$save->c();
	
	// Open XML resource.  Get node for correct day.  Write csv value to the day.
	$save->d();
	  	 
  }
  
class buildNewMonthNode{
	
    //build array of dates for requested company->year->month for calendar.xml
    //public $datesArray = array();
   
	
}//end buildnewmonth class




class saveToCalendar  {
	
	const DS = DIRECTORY_SEPARATOR;	
	const APP_URL_PATH = "customsigncenter.com/calendar/";
	private $CSVrows = array(); // final csv array.
	private $month;
	private $year;
	private $date;
	private $msg='';
	private $csvPath; // full path to the uploaded csv file.
	private $xmlCompanyNode;
	private $xmlYearNode;
	private $xmlMonthNode;
	private $xmlDayNode;
	
	// POSTED NAME VALUES
	// May not need any of the date info posted below:
	private $cDate; // current date.
    	private $m; // current month	
    	// private $y; //current year
	private $mm_dd_yyyy; //mm_dd_year
	// MORE NAME VALUES POSTED BY THE FORM
	private $companyCalendar; // company name of calendar to update.
	private $MAX_FILE_SIZE;	
	public $doc;
	public $xmlfile; // xml file name to update
    	public $targetYear;
    	public $targetMonth;
	public $xpd; // xpath 
	public $allMonthNodes; // all the month nodes to search for duplicate entries in dates
	
		
	function a(){
	   $origFileName = $_FILES["userfile"]["name"];
	   $temp = $_FILES["userfile"]["tmp_name"];
	   $approot = "/home/custo299/public_html/calendar/";
	   $destFileName = $approot."csv/".$origFileName;
	   $csvFileName = $destFileName;
	   //won't be accessible if chmod is not set.
	  
	    chmod($approot."csv", 0777);
	   if ( is_file($destFileName) ) { //if the file is already uploaded... presume this has been imported.
	   		chmod($approot."csv", 0755);
	   		echo '<div id="phpMsg">'.$origFileName.' has already been imported once before.  <span style="cursor:pointer; color:#040073; text-decoration:underline;" onClick="deletecsv(\'csv/'.$origFileName.'\')">Delete</span> it or upload a different csv for importing.</div>';
			
	   }
	   else {
	   if (!$contents = file_put_contents($destFileName, file_get_contents($temp)))
		//if (!move_uploaded_file($_FILES["userfile"]["tmp_name"], $destFileName)) 
		{
			echo "CANNOT GET ". $origFileName . PHP_EOL;
			chmod($approot."csv", 0755);
		} else
		{
			/*
			$contents = file_get_contents($destFileName);
			//echo "Here are the contents".$contents;			
			$this->msg= "<br><p>" . $origFileName. 
			" is ready for Importing to the Calendar.  Location: <em>/calendar/csv/" .
			$origFileName . "</em><p>";			
			$this->csvPath = $approot.'csv/'. $origFileName;
			*/
			
			//new way....
			//return $this->csvPath = $approot.'csv/'. $origFileName;
			
			
			
		  
			
			//$contents = file_get_contents($destFileName);
			//$file = new SplFileObject($destFileName, "r");
			//$fields = $file->fgetcsv($contents);
			//print_r($contents);
			
			//echo "Here are the contents".$contents;			
			$this->msg= "<br><p>" . $origFileName. 
			" is ready for Importing to the Calendar.  Location: <em>/calendar/csv/" .
			$origFileName . "</em><p>";			
			$this->csvPath = $approot.'csv/'. $origFileName;
		
			
			
			
			//end new way.
			
			
			
			
			
		}
		echo "Success: The File Uploaded To: <strong>" . $this->csvPath . "</strong><br>";
		return $this->csvPath;
	   }
	  // exit;
		
	}
	
	function b($file){		
	    //echo "csv path is: " . $this->csvPath;
		if (($handle = fopen($file, "r")) !== FALSE) {
			   while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				  $num = count($data);
				  //try to replace illegal Ampersands for XML with &amp;
				  $data = preg_replace('/&(?!;{6})/', '&amp;', $data);
				  array_push($this->CSVrows,$data);
				  //echo "<p> $num fields in this line: <br /></p>\n";
				 
				  /*for ($c=0; $c < $num; $c++) {
					 //echo $data[$c] . "<br />\n";					 
				   }*/
			   }
			   fclose($handle);
			   //print_r($this->CSVrows);
		    }
			
			
			array_shift($this->CSVrows); //remove headers row.
			
			//print_r($this->CSVrows); //echos correct dates here, but below the foreach, the dates are not correct.  They repeat .
			
			
			$i=0;
			foreach($this->CSVrows as $row) {	
			    // 1st: create a new index called 'date' in the array from the chars between forw slashes: 5/1/2016 // which is 1
			    $patrn = '~\/(.*)\/~';//any character betw. forward slashes (the date).
			    if( preg_match($patrn,$row[2],$matches) ){ //find the date	
			    	    // echo 'Pattern found and it is ' .	$matches[0] . ' for '. $row[0].'<br>';
				    $date = $matches[0]; //e.g., /21/		
				    $yr = substr(str_replace(' ', '', $row[2]),-4,4); //pull out the year
				    $patrn2 = '~[^\/]*~';//match all up to first '/' (not including /) to get the month
				    if( preg_match($patrn2,$row[2],$matches2) ){
					   $mo = $matches2[0];
				    }
				    // $row[2] = str_replace($date,'_',$row[2]); //replace '/21/' from 5/21/2016 with '_'
				    $date = str_replace('/','',$date);	// this /21/ to this 21					
			    }					
			    $this->CSVrows[$i]['date'] = $date;// 21
			    $this->CSVrows[$i]['month'] = $mo;
			    $this->CSVrows[$i]['year'] = $yr;
			    $this->CSVrows[$i]['job'] = $row[1];// 0345
			    // xml does not allow '&' or '<' or '>'.  Replace ampersands with char encoded equivalent.
			    $this->CSVrows[$i]['custName'] = str_replace('&', '&amp;', $row[0]);// Marathon Gas
			    
			    //$this->CSVrows[$i]['deadline'] = $row[2];// 5_2016
			    unset($this->CSVrows[$i][0]);
			    unset($this->CSVrows[$i][1]);
			    unset($this->CSVrows[$i][2]);
			   // echo 'record #' .($i+1). ' is job ' .$row['job']. ' date is: ' . $mo . '/'. $date. '/' .$yr.' for cx: ' .$this->CSVrows[$i]['custName'].'<br>';
			    $i++;
			    
			}
		
			//print_r($this->CSVrows);
	}
	
	function c(){
		//$k represents the class properties identically-named with the $_post assoc key names		
		foreach($_POST as $k=>$v){
			$this->$k=$v;
		}	
			
	}//c()
	
	//open XML
	//create our xml node objects for writing to:
	function d(){
		
		switch($this->companyCalendar){			
			case 'Custom Sign Center':
			$this->xmlfile = 'csc.xml';
			break;
			case 'JG Signs':
			$this->xmlfile = 'jg.xml';
			break;
			case 'Marion Signs':
			$this->xmlfile = 'mar.xml';
			break;
			case 'Boyer Signs':
			$this->xmlfile = 'boy.xml';
			break;
			case 'Outdoor Images':
			$this->xmlfile = 'out.xml';
			break;			
			default:
			$this->xmlfile = 'csc.xml';			
		}		
		
		//echo 'xmlfile variable is now: ' . $this->xmlfile;
		
		 $approot = "/home/custo299/public_html/calendar/";
		 
		 $opts = array('http' => array(
			 'user_agent' => 'PHP libxml agent',
		 ));
	 
		$context = stream_context_create($opts);
		libxml_set_streams_context($context);		
		
		 if (file_exists(realpath ( $approot . 'models/'.$this->xmlfile ))) 
		 {
			
			  $this->doc = new DOMDocument();	
			  $this->doc->formatOutput = TRUE;	
			  // echo 'Path is now ' . $approot . 'models/'.$this->xmlfile . '<br/>';
			  
			  //http://de.php.net/manual/en/domdocument.load.php		
			  if( $this->doc->load(filter_var(realpath( $approot . 'models/'.$this->xmlfile ))))
			  {	
				  //Locate the correct company calendar XML node for this request. 
				/*  for( $i = 0; $c = $this->doc->getElementsByTagName('calendar')->item($i); $i++ ) 
				  {					
					  if( $c->getAttribute("id") == $this->companyCalendar ) 
					  {
						  echo 'Company calendar node found :  ' . $c->getAttribute("id");
						  //exit;
						  if(is_object($c))
						  {
							  $this->xmlCompanyNode = $c; // obj node.  We have the Company Node for All Rows.
							  //echo "\r\n The Company Node is an object! <br/>";	
						  } else {
							  echo "\r\n The Company Node cannot be set as an object! <br/>";	
							  exit;
						  }
					  }						
				  }	*/			
				  
				  $this->xpd = new DOMXPath($this->doc); //used to do iterative queries over the children nodes of XML doc.
				  $this->allMonthNodes = $this->xpd->query("//month");
				  //company node in XML file.  No longer required with company-specific XML files.
				  $this->xmlCompanyNode = $this->doc->getElementsByTagName('calendar')->item(0);			  
				  
				  
				 
				  foreach($this->CSVrows as $rowNmbr=>$csvRow)
				  { //get the year for this row, then the month, then date.
  
						  //***YR NODE******** get the XML year for this CSV row.
						  for( $iYr = 0; $y = $this->xmlCompanyNode->getElementsByTagName('year')->item($iYr); $iYr++ ){
							  
							  //if exists, set as the node to access, else create the node
							  if((integer)$y->getAttribute('ordinal') == (integer)$csvRow['year']){								
								  $this->xmlYearNode = $y;	//We have the Year Node for this Row.
								  //echo "The year node has been set!";					
							  } //end for to get year.
						  }//end for
							  //if no matching year node found. create it in xml.
							  if(!isset($this->xmlYearNode))
							  {
								  // no matching year.  Create xml Calendar with ordianl year matching the import's yr.
								  //send parent: node parent obj, newNodeName, new node attrib name, new node attrib value.
								  $this->xmlYearNode = $this->createNode($this->xmlCompanyNode, 'year', 'ordinal',  $csvRow['year']);
							  }// end if
							  // what if we still could not get or set a year node for this row...
							  if(!isset($this->xmlYearNode) || $this->xmlYearNode == NULL) {								
								  $this->msg .= "Some Records from the CSV file could not be written to the Calendar: \r\n";
								  $this->msg .= "Could not Access or Create a year node for csv year: " . $csvRow["year"] ."\r\n";
								  echo $msg;								
							  } //end if
							  else{ //we have our year node; get month node for the csv row:
								  
								  //***MO NODE******** Get the XML month node for this CSV row.
								  for( $iMo = 0; $m = $this->xmlYearNode->getElementsByTagName('month')->item($iMo); $iMo++ ){
									  
									  if( (integer)$m->getAttribute('ordinal') == (integer)$csvRow['month'] ){										
										  $this->xmlMonthNode = $m;	echo "We have the Month Node for mo \r\n";																		
									  }
								  }//end for
									  //if no matching month node found. create it in xml.
									  if(!isset($this->xmlMonthNode))
									  {
										  echo " no matching mo.  Create xml Calendar with ordinal year matching the import's yr \r\n";
										  //send parent: node parent obj, newNodeName, new node attrib name, new node attrib value.
										  $this->xmlMonthNode = $this->createNode($this->xmlYearNode, 'month', 'ordinal',  $csvRow['month']);
									  }
									  // what if we still could not get or set a month node for this row...
									  if(!isset($this->xmlMonthNode) || $this->xmlMonthNode == NULL) {											
										  $this->msg .= "Could not Access or Create a month node for csv month: " . $csvRow["month"] ."\r\n";	
										  //echo $msg;							
									  } else {
									       
										  //***DATE NODE******** Get the XML date node for this CSV row.  Create it if it doesn't exist.
										  for($iDay=0;$d=$this->xmlMonthNode->getElementsByTagName('day')->item($iDay); $iDay++) {
											  if( (integer)$d->getAttribute('date') == (integer)$csvRow['date'] ){										
												  $this->xmlDayNode = $d;	//We have the Day Node for this Row.
												  
												  
												   /********* FIRST ******************/
												  /*  LET'S TRY TO REMOVE ALL CSV UPDATES THAT ALREADY HAVE A RECORD IN THE CURRENT XML DOCUMENT
												  
															  
												 //B4 appending new content to the old contents in the cal,
												 //we need to ensure that all new jobs are unique, and not already 
												 //saved someplace in the old calendar content.
												 //Use the span id (i.e., <span id="job_uniqueJobNumber">uniqueJobNumber</span> ) from 
												 //the old content, iterate over all of them, and then SKIP the new content update
												 //for any match betw/ new content's job number and any job number from the old content.
												 
												 */

													
			  
											  }
										  }//end for
										  //if no matching Day node found. create it in xml.
										   if(!isset($this->xmlDayNode))
										   {
											   // let's get the date node of this csv row, or create it if !exist:
											   // send parent: node parent obj, newNodeName, new node attrib name, new node attrib value.
											   $this->xmlDayNode = $this->createNode($this->xmlMonthNode, 'day', 'date',  $csvRow['date']);
										   }//end if
										   // what if we still could not get or set a day node for this row...
										   if(!isset($this->xmlDayNode) || $this->xmlDayNode == NULL) {											
											  $this->msg .= "Skipping CSV ROW: Could not Create Missing day node for csv date: " . $csvRow["date"] ."\r\n";
											  echo "Skipping CSV ROW: Could not Create Missing day node for csv date: " . $csvRow["date"] ."\r\n";
											  //echo $msg;	
											  //cannot proceed with this row.
											  $this->errorMessage();
											  									
										   }	//end if									
									  } //end else for xmlmonthnode
										  
								   } //end else for xmlYearNode
								   
								  // got our final node to write to (day), let's write the data!
								  // try to remove csv items that already have an entry in the xml calendar file.
												
													 
								  // so, for example, if job is '', or is 'none',
								  // it will not be added to the calendar.  Job#
								  // must be unique in the calendar XML.
								 $duplicateEntry = false;
								 $p = "job_".$csvRow['job'];  
								 $ptrn = "/($p)/";
								 //echo '$d->nodeValue is: '.$d->nodeValue;
								 //echo "\r\n ptrn is: $ptrn \r\n";
								 if( preg_match($ptrn, $d->nodeValue) )
								 {	
									echo "Skipping: Missing or Duplicate Job No ".$csvRow['job']."\r\n";						 
									unset($this->CSVrows[$rowNmbr]);
									$duplicateEntry = true;	
								 }
								 else //do exhaustive srch of entire xml day nodes for all months
								 { 
									foreach($this->allMonthNodes as $mNODE)
									{
										foreach($this->xpd->query("week",$mNODE) as $wkNODE)
										{
										    foreach($this->xpd->query("day",$wkNODE) as $dayNODE)
										    {
											   if( preg_match($ptrn, $dayNODE->nodeValue) )
											   {
												   echo "Skipping: Missing or Duplicate Job No ".$csvRow['job']."\r\n";
												   unset($this->CSVrows[$rowNmbr]);
												   $duplicateEntry = true;											
											   }
										    }
										}
										
									}
									 
								 }
								 
								 if($duplicateEntry == false)
								 {	
									//update this record.
									//echo "Attempting to Add: Job No ".$csvRow['job']."\r\n";					   
									$this->updateDayNode($rowNmbr); //index of row to update
								 }
								 
								   
							   }//end foreach csvrow
						   
			}//end if
			else { //could not load the xml file
				echo "\r\n COULD NOT load the calendar.xml file!";
			}
						 
		 }//end if file exists
						
	}//end d()
	
	
	/* key for character encoding for my html angle brackets in this app:
		str_replace '</', '*^*'   
		str_replace '<', '~~'
		str_replace '/>', '$^$'
		str_replace '>', '#$#'
		
		Examples for xml imports:
		<br/> encodes to:  ~~br$^$	
		<li>  encodes to:  ~~li~~	
		</li> encodes to:  *^*li~~	
	*/
	
	
	
	//example data to add to day node: [job] => job0001 [custName] => Sunoco
	function updateDayNode($rowNmbr){
		
		$oldNodeVal = $this->xmlDayNode->nodeValue;
		
		if(isset($this->CSVrows[$rowNmbr])){
			//echo "the csvrow is set";
			$appendToNode = ' ~~li class="lineEntry unassigned" title="Right-Click for Options" #$#~~span id="job_'.
			$this->CSVrows[$rowNmbr]['job'].'" #$#' . $this->CSVrows[$rowNmbr]['job'] . '*^*span#$# ' . $this->CSVrows[$rowNmbr]['custName'] .'*^*li#$# ';

			
			$this->xmlDayNode->nodeValue = $oldNodeVal . $appendToNode;
			
			//save the changes to xml			
			$this->doc->save('/home/custo299/public_html/calendar/models/'.$this->xmlfile);
		} else {
			$this->errorMsg();
			echo 'Done';
			exit; //done with all csv rows.		
		}
		return;
		
		
	}//end updateDayNode()
	
						
						
					
	// node parent obj, newNodeName, new node attrib name, new node attrib value.
	function createNode($parentNode, $newNodeName, $newNodeAttribName, $newNodeAttribValue) {		
		
		//IF 'new month' Node...
			// THEN Let's just Create the whole darn Month in one Shot
			// Call the buildCalendar->createDates (for the month)
	    if( $newNodeName == 'month' ){ //build in all dates for the missing month node
		    $node = $this->createDates($parentNode, $newNodeName, $newNodeAttribName, $newNodeAttribValue);
		    return $node;
	    } else
	    {		
		  //newNodeName is the name of the node to create.  parent is the actual obj node to place new node.
		  $element = $this->doc->createElement($newNodeName); //always create elements using the domdocument obj, not a node obj therein.
		  $element->setAttribute($newNodeAttribName,$newNodeAttribValue);
		  $node = $parentNode->appendChild($element); //append it to the correct node.
		 // $elemAttrib = $element->setAttribute($newNodeAttribName,$newNodeAttribValue);
		  // Value for the created attribute
		  //$elemAttrib->value = $newNodeAttribValue;
		  //try save first, since you cannot find the node until it is saved:
		  $this->doc->saveXML($node);
		  
		  //how to set this as an obj node?  Try:
		  //should only be one, the new one created.
		  
		  foreach($node as $childNode)
		  {
		  //for($i=0;$childNode = $parentNode->getElementsByTagName($newNodeName)->items($i); $i++){
			  
			  if(  $childNode.getAttribute($newNodeAttribName) == $newNodeAttribValue ) {
				  //we found our new node.  Save and return it as an obj.	
				  			
				  return $childNode;		
			  }		
		  }	
	    }
	}
	
	
	
	//Create the days for the requested month (array of dates).
	// node parent obj, newNodeName, new node attrib name (will be 'month'), new node attrib value (e.g., 5 for may).
	public  function createDates($parentNode, $newNodeName, $newNodeAttribName='ordinal', $newNodeAttribValue){ 
	    $this->targetMonth = (integer)$newNodeAttribValue; //the month to create node for with all dates
	   // $targetYear = (int)$parentNode->getAttribute('ordinal'); //the parent year to create month node in.
	   $this->targetYear = (integer)date('Y');
	    //echo "month is " .$targetMonth . " and year is " . $targetYear;
	    //number of days in a month:
	    //params: type of calendar, month, year)
	    cal_days_in_month(CAL_GREGORIAN,$targetMonth,$targetYear);
	    
	    $dates = array();
		    // for each day in the month, date('t') is number of days in the given month.
		   // for($i = 1; $i <=  cal_days_in_month(CAL_GREGORIAN,10,2005); $i++)
		   for($i = 1; $i <= (integer)date('t'); $i++)
		    {	
			 $index = $i-1;	
			 $theDate = $i;
			 //need to know ordinal day of the week for the very first date
			 if($i===1){			  
				 $FirstOridinalDayofMonth = new DateTime((string)"$targetMonth/1/$targetYear", new DateTimeZone("America/New_York"));
				 $dayOfWeek = $FirstOridinalDayofMonth->format('N'); //0 is sunday, 1 mon, etc.
				 // echo "Day of the week for the first day of the month is: ".$day;
				 $dates['ordinalFirstDay'] = (integer)$dayOfWeek;
			 }
				    
			 //$theDates = $targetMonth.'-'.$i.'-'.$targetYear;
					 
			 $dates[$index]['month'] = (int)$targetMonth;
			 $dates[$index]['date'] = (int)$theDate;//creates a new index [0], [1] etc for each date.		  
			 $dates[$index]['year'] = $targetYear;
			 $nmbrOfDays = $theDate;//how many days in the month.		  	  
		    }	
		    
		    $dates['numberDaysinMonth'] = (int)$nmbrOfDays;
		    $datesArray = array();
		    $datesArray = $dates;		
		    //var_dump($dates);
		    //echo "first day of the month is: " .  date("$targetYear-$targetMonth-01"); // first day of this month
		    
		    $this->buildMonth($parentNode, $newNodeAttribName, $newNodeAttribValue); //works with the dates array property and xml objects;
		    
    /*returns this structure:	
		    
		    array(33) { ["ordinalFirstDay"]=> int(1) [0]=> array(3) { ["month"]=> int(8) ["date"]=> int(1) ["year"]=> string(4) "2016" } [1]=> array(3) { ["month"]=> int(8) ["date"]=> int(2) ["year"]=> string(4) "2016" } [2]=> array(3) { ["month"]=> int(8) ["date"]=> int(3) ["year"]=> string(4) "2016" } [3]=> array(3) { ["month"]=> int(8) ["date"]=> int(4) ["year"]=> string(4) "2016" } [4]=> array(3) { ["month"]=> int(8) ["date"]=> int(5) ["year"]=> string(4) "2016" } [5]=> array(3) { ["month"]=> int(8) ["date"]=> int(6) ["year"]=> string(4) "2016" } [6]=> array(3) { ["month"]=> int(8) ["date"]=> int(7) ["year"]=> string(4) "2016" } [7]=> array(3) { ["month"]=> int(8) ["date"]=> int(8) ["year"]=> string(4) "2016" } [8]=> array(3) { ["month"]=> int(8) ["date"]=> int(9) ["year"]=> string(4) "2016" } [9]=> array(3) { ["month"]=> int(8) ["date"]=> int(10) ["year"]=> string(4) "2016" } [10]=> array(3) { ["month"]=> int(8) ["date"]=> int(11) ["year"]=> string(4) "2016" } [11]=> array(3) { ["month"]=> int(8) ["date"]=> int(12) ["year"]=> string(4) "2016" } [12]=> array(3) { ["month"]=> int(8) ["date"]=> int(13) ["year"]=> string(4) "2016" } [13]=> array(3) { ["month"]=> int(8) ["date"]=> int(14) ["year"]=> string(4) "2016" } [14]=> array(3) { ["month"]=> int(8) ["date"]=> int(15) ["year"]=> string(4) "2016" } [15]=> array(3) { ["month"]=> int(8) ["date"]=> int(16) ["year"]=> string(4) "2016" } [16]=> array(3) { ["month"]=> int(8) ["date"]=> int(17) ["year"]=> string(4) "2016" } [17]=> array(3) { ["month"]=> int(8) ["date"]=> int(18) ["year"]=> string(4) "2016" } [18]=> array(3) { ["month"]=> int(8) ["date"]=> int(19) ["year"]=> string(4) "2016" } [19]=> array(3) { ["month"]=> int(8) ["date"]=> int(20) ["year"]=> string(4) "2016" } [20]=> array(3) { ["month"]=> int(8) ["date"]=> int(21) ["year"]=> string(4) "2016" } [21]=> array(3) { ["month"]=> int(8) ["date"]=> int(22) ["year"]=> string(4) "2016" } [22]=> array(3) { ["month"]=> int(8) ["date"]=> int(23) ["year"]=> string(4) "2016" } [23]=> array(3) { ["month"]=> int(8) ["date"]=> int(24) ["year"]=> string(4) "2016" } [24]=> array(3) { ["month"]=> int(8) ["date"]=> int(25) ["year"]=> string(4) "2016" } [25]=> array(3) { ["month"]=> int(8) ["date"]=> int(26) ["year"]=> string(4) "2016" } [26]=> array(3) { ["month"]=> int(8) ["date"]=> int(27) ["year"]=> string(4) "2016" } [27]=> array(3) { ["month"]=> int(8) ["date"]=> int(28) ["year"]=> string(4) "2016" } [28]=> array(3) { ["month"]=> int(8) ["date"]=> int(29) ["year"]=> string(4) "2016" } [29]=> array(3) { ["month"]=> int(8) ["date"]=> int(30) ["year"]=> string(4) "2016" } [30]=> array(3) { ["month"]=> int(8) ["date"]=> int(31) ["year"]=> string(4) "2016" } ["numberDaysinMonth"]=> int(31) }
		    
		    */
		    
		    
		    
		    
		
	
	
			
			
	}
	
	function buildMonth($parentNode, $newNodeAttribName, $newNodeAttribValue){
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		
	
	function errorMsg(){	
		$this->msg .= "\r\n \r\n -- WRITING TO FILE -------------------------------------- \r\n";	
		$h = fopen('logs'.DS.'calendar_log.txt', 'a'); //append to file
		fwrite($h, $this->msg);			
	}//endMsg()
	
}//class
?>
<?php // else: ?>
<!--
</head>
<body>
<div style="color:#575F6C">
<h1 style="text-align:center; color:#B42528; margin-top: 100px">Login Required</h1>
	<form action="<?php // $_SERVER['PHP_SELF']; ?>" method="post" name="login" style="width:480px; margin: 30px auto; text-align:left; padding:20px; border-radius:9px; border: #E3ECD3 solid 1px">
     <label for="username">Username &nbsp;</label>
     <div style="text-align:center"><input name="username" type="text" value="" /></div><br/>
     <label for="password">Password &nbsp;&nbsp;</label>
     <div style="text-align:center"><input name="password" type="password" value="" /></div><br/><br/>
     <div style="text-align:center"><input type="submit" name="submit" Value="Submit" /></div>     
     </form>
</div>
</body</html>-->

<?php // endif;?>