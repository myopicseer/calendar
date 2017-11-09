<?php //model = models/calendarXML.php
libxml_use_internal_errors(true);
include($_SERVER['DOCUMENT_ROOT'].'/../classes/dbConnPDO.php');

$calendar = new calendar;
$calendar->init();

if( $calendar->method==NULL || empty($calendar->method) ){
	$calendar->display;
} else {
	//echo 'Else was called!  ';
	$action = $calendar->method; 
	$calendar->$action();//update, display...etc. [set by init()]
}
/*xml nodes and attribs :
	<root aka "calendars">
		<calendar id="Custom Sign Center">
		  <year ordinal="2016">
			   <month ordinal="5" name="May">
				   <week ordinal="1">
					   <day ordinal="1" name="Sunday"  date="1">
*/

class calendar extends dbConnPDO {
	//submitted data
	public $cMo; //current mo.  Set in init func: $this->cMo = date('m');
	public $cYr; //current yr.  set in init func: $this->cYr = date('Y');
	// Post Data includes content,year,month,theDate,company,method (to call)
	// All $_POST elements saved to class Properties [with the same var name] by the init() method.
	public $content;
	public $year; // sent as json of y = today.getFullYear();
	public $month;	// sent as json today.getMonth()+1; //month is zero-based (+1)
	public $theDate; // sent as json today.getDate(); //current date.
	public $company; // javascript global curCompany (name of company)
	public $method; // calendar class' method to call (e.g., "display()")
	// End POST data stored in properties.
	//dirname(__FILE__) on the local environment this equates to: C:\inetpub\wwwroot\customsigncenter.com\calendar
	public $path = __DIR__;
	//protected $app_root = dirname(__FILE__);
	public $xmlPath;
	
	// private $domDocument; //not using this property
	// end submitted data
	public $ds = DIRECTORY_SEPARATOR;
	// public $calNode;
	public $companyCalendar;
	public $msg = '';
	public $doc; // document obj to traverse over the xml tags
	public $activeMonthNode; // obj to refer to this month node in xml for finding correct days in the xml tree.
	public $activeYearNode;
	public $activeDateNode; // obj to refer to this date node in the xml.  For Update operations.
	public $activeOrdinalCell; //the current day's cell number in the calendar layout.  (div #x) //for hightlighting the current day's html.
	public $day1Cell; // the html div location of the first date of the cur mo.  (Sunday = 1, Tuesday = 3)
	public $activeMonthName; // e.g. "June"
	public $activeMonthOrdinal;// e.g. "6" for June
	public $responseArray = array();
	public $xmlfile;
	public $arXmlYearNodes = array(); //hold the year node objs if more than one yr is in the xml doc	
	public $userCompany;  // defines privileges based upon user's company.  Also used to define if this is a developer in the dev folder.
	public $pdoDB; //db connection
	
	function init()
	{
		//echo "Debug: This is the unprocessed data received by init() " .PHP_EOL;
		//print_r($_POST);				
		foreach($_POST as $k=>$v){
			$this->$k=$v;
		}//$this->content, $this->year, $this->month,$this->theDate, $this->company, $this->method) = $_POST;	
		
		 $this->cMo = date('m');
		 $this->cYr = date('Y');
		
		$opts = array(
		    'http' => array(
			   'user_agent' => 'PHP libxml agent',
		    )
		);
		$this->company = trim($_POST['company']);
				
		switch($this->company){			
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
		     case 'MarionOutdoor':
			$this->xmlfile = 'marion-outdoor.xml';
			break;						
		}
		
		//if development server, use a different path for the xml 
		//define the xml path:
		if( preg_match('~wwwroot~', $this->path) ) {
			// this is development server.
		 	$this->xmlPath = dirname($this->path).$this->ds.'models'.$this->ds;
			// No user session required.  Full access to work in the /dev directory files.
			$this->userCompany = 'developer';
			 //echo 'found wwwroot and xmlpath is: ' . $this->xmlPath;
		} else { 
			$this->xmlPath = dirname($this->path).$this->ds.'models'.$this->ds;
			$this->userCompany = $this->comapny;
		}
		// echo $this->xmlPath.$this->xmlfile;
		
		$context = stream_context_create($opts);
		libxml_set_streams_context($context);		
		//echo $this->xmlPath.$this->xmlfile;
		 if (file_exists(realpath ( $this->xmlPath.$this->xmlfile ))) 
		 {
			 $this->doc = new DOMDocument();		
			//echo '    YES';
			//http://de.php.net/manual/en/domdocument.load.php		
			if( $this->doc->load(filter_var(realpath($this->xmlPath.$this->xmlfile )), LIBXML_NOBLANKS))
			{	
				//echo 'The document loaded.';
				
				//start with the correct company calendar node for this request
				for( $i = 0; $c = $this->doc->getElementsByTagName("calendar")->item($i); $i++ ) {
			
					if( $c->getAttribute("id") == $this->company ) {
						//echo 'Company calendar node found :  ' . $c->getAttribute("id");
						//exit;
						$this->companyCalendar = $c; // obj node
					}
				}
				
				
				
				//get the correct xml year node for this request
				
				//for( $i = 0; $node = $doc->getElementsByTagName('month')->item($i); $i++)
			    		for( $x = 0; $yrNode = $this->companyCalendar->getElementsByTagName( "year" )->item($x); $x++  ) {
						
						if( $yrNode->getAttribute("ordinal") == $this->year ){
							
							$this->activeYearNode = $yrNode;
							//echo '$this->activeYearNode equates to'. $this->activeYearNode;
						}
						//save each year node (if more than one in the xml) to an array to iterate thru
						array_push($this->arXmlYearNodes, $yrNode);		
					}
					
					foreach($this->arXmlYearNodes as $yrNODE){
						$this->iterateYEAR($yrNODE);//param is the yr node 
					}
				
			     	
			}		
		}//file_exists
		else {
			echo '    NO.  The xml document does not exist.';
		}
		 
	}
	
	
	//update xml calendar
	//incoming content for the month looks like:
	/*
	//this is AFTER stripslashes():
	<div class="row" id="row1"><div class="empty" ordinal="1"></div><div class="empty" ordinal
	 ="2"></div><div class="empty" ordinal="3"></div><div class="date" ordinal="4"><div class
	 ="day">1</div><div class="edit"></div></div><div class="date" ordinal="5"><div class="day
	 ">2</div><div class="edit"></div></div><div class="date" ordinal="6"><div class="day">3<
	 /div><div class="edit"></div></div><div class="date" ordinal="7"><div class="day">4</div
	 ><div class="edit"></div></div></div><div class="row" id="row2"><div class="date" ordinal
	 ="8"><div class="day">5</div><div class="edit"></div></div><div class="date" ordinal="9"
	 ><div class="day">6</div><div class="edit"></div></div><div class="date" ordinal="10"><div
	  class="day">7</div><div class="edit"></div></div><div class="date" ordinal="11"><div class
	 ="day">8</div><div class="edit"></div></div><div class="date" ordinal="12"><div class="day
	 ">9</div><div class="edit"></div></div><div class="date" ordinal="13"><div class="day">10
	 </div><div class="edit"></div></div><div class="date" ordinal="14"><div class="day">11</div
	 ><div class="edit"></div></div></div><div class="row" id="row3"><div class="date" ordinal
	 ="15"><div class="day">12</div><div class="edit"></div></div><div class="date" ordinal="16
	 "><div class="day">13</div><div class="edit"></div></div><div class="date" ordinal="17">
	 <div class="day">14</div><div class="edit"></div></div><div class="date" ordinal="18"><div
	  class="day">15</div><div class="edit"></div></div><div class="date" ordinal="19"><div class
	 ="day">16</div><div class="edit"></div></div><div class="date" ordinal="20"><div class="day
	 ">17</div><div class="edit"></div></div><div class="date" ordinal="21"><div class="day">18
	 </div><div class="edit"></div></div></div><div class="row" id="row4"><div class="date" ordinal
	 ="22"><div class="day">19</div><div class="edit"></div></div><div class="date" ordinal="23
	 "><div class="day">20</div><div class="edit"></div></div><div class="date" ordinal="24">
	 <div class="day">21</div><div class="edit"></div></div><div class="date" ordinal="25"><div
	  class="day">22</div><div class="edit"></div></div><div class="date" ordinal="26"><div class
	 ="day">23</div><div class="edit"></div></div><div class="date" ordinal="27"><div class="day
	 ">24</div><div class="edit"></div></div><div class="date" ordinal="28"><div class="day">25
	 </div><div class="edit"></div></div></div><div class="row" id="row5"><div class="date" ordinal
	 ="29"><div class="day">26</div><div class="edit"></div></div><div class="date" ordinal="30
	 "><div class="day">27</div><div class="edit"></div></div><div class="date" ordinal="31">
	 <div class="day">28</div><div class="edit"></div></div><div class="date" ordinal="32"><div
	  class="day">29</div><div class="edit"></div></div><div class="date" ordinal="33"><div class
	 ="day">30</div><div class="edit"></div></div><div class="empty" ordinal="34"></div><div class
	 ="empty" ordinal="35"></div></div>"
	
	*/
	
	/* flow:
	Converting HTML to XML tree to saveMonth()
	// NOTE: Because a browser SESSION can still be active on the calendar,
	         whilst the user's login has become 'stale' and possibly
		    another admin/mgr has logged in to grab the access token,
		    we have to prevent 2 editing users from cross saving data 
		    to the xml.  So, First we will need to check whether the 
		    user attempting the save has the access_token to authorize 
		    this action.
	 HTML comes as "content" in the request array.
	 SEE BELOW.
	 
	 Convert html string into domdocument to access its tags, attributes, etc.
	 Get node matching the :
	 1. ( Company's Calendar: not implemented yet )
	 2. Get XMLnode matching the Year of the request: $this->year == xml year attribute "ordinal"
	 3. Get the $this->year's XMLnode matching the month of the request: $this->month == xml month attribute "ordinal"
	 WE NOW HAVE THE MONTH NODE TO UPDATE FOR THE REQUEST.
	 
	 4. Foreach div with attribute "ordinal" (these are the cells of the month), (the count of each cell for the month) :
	 	foreach 
			set the XML nodeValue to == the HTML nodeValue;
	 */
	 
	// Convert the above into the XML structure needed to update the xml file.
	function saveMonth() 
	
	{
		
	  /* does user possess the access_token to perform this action? */
		
		$this->pdoDB = parent::dbOpen('custo299_wipcalendar');
		
		$query = 'SELECT id FROM active_token WHERE id_user = :userID AND coid_token :coID';

		$statement = $this->pdoDB->prepare($query);

		$statement->bindParam(':userID', $_POST['userID']);
		$statement->bindParam(':coID', $_POST['coID']);

		if( $statement->execute() ) {
			
			if( $statement->columnCount() > 0 ) {				

				// THIS USER HAS THE ACCESS_TOKEN -> AUTHORIZED TO SAVE THE CALENDAR.	

		
		
		
		
	   $this->content = stripslashes($this->content); // remove escaping backslashes, if any
		
		
	    $htmlDoc = new DOMDocument();		
	    $htmlDoc->loadHTML($this->content);
	
	    
	    $xmlToSaveArray = array(); 
	    $tempArray = array();
	    //search html object tree ....
	    //$htmlSearchDiv = $htmlDoc->getElementsByTagName("div");
	    
	   
		 for($z=0; $div = $htmlDoc->getElementsByTagName("div")->item($z); $z++)
		 {
		    //$debugMsg .= " - a div tag was found - ";
		    //if the ordinal cell does not have a class="empty" (meaning it is a cell with a date for the save month)
		    
		    
		   // $v = html_entity_decode(json_encode($div ->nodeValue));
		   
		   
		   if( $div->getAttribute("class") == "month" || $div->getAttribute("class") == "month hide"  )
		   {
			   $monthOrd = $div->getAttribute("ordinal"); //used as the index value for our html array of content to save to our xml file.			   
		   	   //$div is a month div to iterate thru & extract LI HTML to save to our array, then to xml;
		   
		   	   for($d=0; $dateDiv = $div->getElementsByTagName("div")->item($d); $d++)
			   { //for each date div within the current month $div
		   
		   
		    
		    if( /*$div->getAttribute("ordinal") && */ $dateDiv->getAttribute("class") == "date" || $dateDiv->getAttribute("class") == "date today"  ) 
		    {
			    
			    $ord = $dateDiv->getAttribute("ordinal");
			    
			    //we have a date cell with potentially new content
			   //$debugMsg .= $z . " - we have a date cell with potentially new content - ";
			  
			    
			   //drill down to this div's child ul of class="edit" for its content.
			   // echo json_encode(addslashes($div ->nodeValue));
			   for($i=0; $childUL = $dateDiv->getElementsByTagName("ul")->item($i); $i++)
			   {
				    
				   
				  //$debugMsg .= "\r\n"; 
				  //$debugMsg .= " -we have the child UL for the date cell \r\n"; 
				  if( $childUL->getAttribute("class") == "edit" ) 
				  {
					 
					 
			    		$xmlToSaveArray[$monthOrd][$ord] = array();
					  
					 //what if we want to save an empty value (no <li> tags)?
					if($childUL->getElementsByTagName("li")->length > 0) 
					{
						
		
						
						foreach($childUL->getElementsByTagName("li") as $li) 
						{
							 //we have the edit div, let's get the contents	  
							//echo 'an edit class node was found';
							//get each list and save to the ordinal index for our array
							$val = '';
							
							 //remove the .hide class if present to 
							 //prevent entries from hiding when xml loads next time.
							
							$class = $li->getAttribute('class');							
							 
							
							
							 if(preg_match('~^[ hide]$~',$class))
							 {
								$val = str_replace($class,'7#77****88#8',$val);
								$liClassTxt = str_replace( ' hide','',$class);
								$val = str_replace('7#77****88#8',$class,$val);
							 } 
							
							
							
						// ad hoc not LI's added by admins have NO SPAN.
						// have to account for this when saving
							
							
							$span = $li->getElementsByTagName("span");
							if($span->length){
								
								$jobNmbr = $span->item(0)->getAttribute("id");
								
								if(strlen($jobNmbr) > 2){
									
									$jobNmbr = str_replace("job_","",$jobNmbr);
										
								} else {
									
									$jobNmbr = null;
									
								}
								
							} else {
								
								//this is a + added LI by an admin.
								$jobNmbr = 'admin-note';
								
							}
							
						    
						    //echo 'there are '.$childUL->getElementsByTagName("li")->length.' list elements for this edit ul found';
						    
						    //echo 'we have a ul      ';
							    
							    //echo "we have a list element to save ";					  
							// $val .= (string)$htmlDoc->saveXML( $li );
							$val .= (string)$htmlDoc->saveHTML( $li );
							// echo "json encoded $val is: " . $val;
							 
							 $val = str_replace('<br>', '', $val);
							 $val = str_replace('</', '*^*', $val);
							 $val = str_replace('<', '~~', $val); 
							 $val = str_replace('/>', '$^$', $val); 
							 $val = str_replace('>', '#$#', $val);
							/* $val = str_replace('~~li', $openJobTag.'~~li',$val);
							 $val = str_replace('li#$#', 'li#$#'.$closeJobTag,$val);
							 */
							
							//$span = $li->getElementsByTagName("span");
							
							//$jobNumber = $span->item[0]->nodeValue;
							
						    
							    //echo 'val of '. $val . ' saved to xmlarray index '.$monthOrd.' and subindex '.$ord;	
							if($jobNmbr !== null){

									$xmlToSaveArray[$monthOrd][$ord][$jobNmbr] = $val;

							 } 
							 else 
							 {
								$xmlToSaveArray[$monthOrd][$ord][0] = $val;
							 }
					  
							 
						}//end foreach li
						
						
					 
				 
				  }
				  
				  }
		      }//end for each date div
		    }
		     } //end if date div
			
		   }//end if month div
	    }
	  //print_r($xmlToSaveArray);
	 /* array looks like this for june 1-5 ...
	 
	 [6] => Array ( JUNE )
        (
            [1] => Array (FIRST DATE )
                (
                )

            [2] => Array
                (
                )

            [3] => Array
                (
                )

            [4] => Array
                (
                )

            [5] => Array
                (
                    [100710] => ~~li class="lineEntry unassigned" title="Right-Click for Options" contenteditable
="false"#$#
~~span id="job_100710"#$#100710*^*span#$# WEN #00034 Morrow, GA*^*li#$#
                    [100710-1] => ~~li class="lineEntry unassigned" title="Right-Click for Options" contenteditable
="false"#$#
~~span id="job_100710-1"#$#100710-1*^*span#$# WEN #00034 Morrow, GA*^*li#$#
                    [101065] => ~~li class="lineEntry unassigned" title="Right-Click for Options" contenteditable
="false"#$#
~~span id="job_101065"#$#101065*^*span#$# WEN 02910 Urbana, OH*^*li#$#
                    [101377] => ~~li class="lineEntry unassigned" title="Right-Click for Options" contenteditable
="false"#$#
~~span id="job_101377"#$#101377*^*span#$# Ted's Con/CVS #2323 West Warwick, RI*^*li#$#
                    [101441] => ~~li class="lineEntry unassigned" title="Right-Click for Options" contenteditable
="false"#$#
~~span id="job_101441"#$#101441*^*span#$# Wen 01119 Cape Coral FL*^*li#$#
                    [101765] => ~~li class="lineEntry unassigned" title="Right-Click for Options" contenteditable
="false"#$#
~~span id="job_101765"#$#101765*^*span#$# Flyers Pizza - Galloway, OH*^*li#$#
*/
	    //company calendar xml node for the current requested save operation.
	    
		
		
		
	    for( $i = 0; $m = $this->companyCalendar->getElementsByTagName('month')->item($i); $i++ ) {
			
		    
		    
		     //the xml month's ordinal is a match for the index of our array
		    for( $s = 0; $d = $m->getElementsByTagName("day")->item($s); $s++ )  //get xml day nodes
		    {	
			    //prevent moved jobs in cal from duplicating across original and new date.
				  //Best just to completely remove all job nodes from the xml
			    //then append the new jobs using the update html array:
			    for( $iJ = 0; $j = $d->getElementsByTagName("job")->item($iJ); $iJ++ ){

				   $d->removeChild($j);
			    }

			    $d->nodeValue = '';
		    
		    
		    
		    
		    
			
		    //iterate over array of html days, containing html LIs to add / update.  
		    //Structure for June 4, contents for job # 01234 is like this: array[6][4][01234]=>li contents
			foreach($xmlToSaveArray as $moOrdinal=>$mo)
			{
				//for 6 => 4 (June 4th)
				
				//any html LIs inside ?
				
					
					    //now drill down to the correct day node for this LI to update to:
						// We will replaceChild or appendChild (if not there, ie, a new li added by an admin)
					    if((integer)$m->getAttribute('ordinal') == (integer)$moOrdinal)
					    {	
						    
						    
						    foreach($mo as $dayOrdinal => $day)
						    {
							      //if xml day ordinal matches the key of the update array:
							    if( $d->getAttribute('ordinal') == $dayOrdinal ) 
							    {

								if( count($day) > 0 ){ //true = yes, there's content to update / add

									foreach($day as $jNbr => $job){										
											
											   $jobNode = $this->doc->createElement(  'job', $job );
											   
											   $attrib = $this->doc->createAttribute('number');

											   //assign job# to the new attribute
											   $attrib->value = $jNbr;
											   //attach attrib to the new element
											   $jobNode->appendChild($attrib);
											   //append this as a node to this day node:
											   $d->appendChild($jobNode);	
											    
											   $this->doc->formatOutput = true;											
											   // Save the XML with the appended node.
											   $this->doc->save($this->xmlPath.$this->xmlfile);
											  
										    }//xml
										} else {
											
											//if update has nothing to add, the xml's date cell needs to be emptied
											$d->nodeValue = '';
										}
									
								}// when we match xml day node with its html day contents
							}// for days in the html.

						  }   //if this is matched month				  

					    }//each element of the array of html items to save
					}//end nested for each day in xml				
					  
				
			}//end each month in xml
	    
		   
		     // Nicely format the structure.
			 //save change
			$this->doc->formatOutput = true;
											
			// Save the XML with the appended node.
			if($this->doc->save($this->xmlPath.$this->xmlfile))
			{
				$msg = array(['msg']=>"Successfully saved.");
				echo json_encode($msg);
			}
			else
			{
				$msg = array(['msg']=>"Save status uncertain; try again, then reload the page to see if updates held.  If this message continues, notify Web Team.");
				echo json_encode($msg);
			}
			//$debugMsg .= "Successfully saved \r\n" . $debugMsg
			
			//echo $response;
			exit;
			} //end user has db access_token permissions to save calendar
			else {
				//user does NOT have the access_token permission to save at this time.
				$msg = array(['msg']=>"Not Saved.  Another user with save rights is currently logged in.");
				echo json_encode($msg);				
			}
		 
	    }//end func saveMonth
		


//display the company calendar
 function display() 
 {	
	 $row = '';//rows are the weeks in html
	 $cells = '';//date cells as html output
	 
	 foreach($this->arXmlYearNodes as $yrNODE)
	 {
		    $searchMonthNode = $yrNODE->getElementsByTagName('month');
		    foreach($searchMonthNode as $searchMonth) //foreach1
		    {
			    //echo 'activemonthordinal is: ' . $this->activeMonthOrdinal . ' and the xml node ordinal is: '. $searchMonth->getAttribute('ordinal');
			    if((integer)$this->activeMonthOrdinal == (integer)$searchMonth->getAttribute('ordinal'))
			    { 
				    $row .= '<div class="month" ordinal="'.$searchMonth->getAttribute('ordinal').'" yr="'.$yrNODE->getAttribute('ordinal').'">';
			    }
			    else 
			    {
				    $row .= '<div class="month hide" ordinal="'.$searchMonth->getAttribute('ordinal').'" yr="'.$yrNODE->getAttribute('ordinal').'">';
			    }
				    
				    //this must be the current active month to display from the xml
				    //start building the html for calendar cells;
				    $searchNode = $searchMonth->getElementsByTagName('week'); //xml week nodes for the month
				    $i=1;
				    foreach($searchNode as $searchNode) //for each week node, build as a row
				    {
				    
					    //must clear your cells for each new row of weeks.
					    unset($cells); $cells='';
					    $row .= '<div class="row" id="row'.($i).'">'; //row1, row2, etc.
					    $i++;
					    for( $t = 0; $dayNode = $searchNode->getElementsByTagName('day')->item($t); $t++ )
					    {
						    if($dayNode->getAttribute("date") === '' || $dayNode->getAttribute("date") === NULL || empty($dayNode->getAttribute("date"))) //if1
						    {// echo ' day cell; not a date.  ';
							    $cells .='<div class="empty" ordinal="'.$dayNode->getAttribute("ordinal").'"></div>';
						    }  //end if1
						    else  //else1
						    {
							    
							   //echo '  Day node date attrib is: ' .$dayNode->getAttribute("date") .' for month '.$searchMonth->getAttribute('ordinal');
							  
								    //echo 'daynode has a val of: ' . $dayNode->nodeValue;
								    //Add the html angle brackets back in:
								    //~~li class="lineEntry unassigned"
								    //title="Right Click for Options" contenteditable="false"#$#New Job*^*li#$#
							    $dayContents = '';
							    
							    // NEW UDATE TO ALLOW JOB NODES:
							    for( $iJ = 0; $jNode = $dayNode->getElementsByTagName('job')->item($iJ); $iJ++) 
							    {
							    
							    
								    if($jNode->nodeValue !== NULL || $jNode->nodeValue !== '' || !empty($jNode->nodeValue)){
									   $respConent = str_replace('*^*', '</', $jNode->nodeValue);
									   $respConent = str_replace('~~', '<', $respConent); 
									   $respConent = str_replace('$^$', '/>', $respConent); 
									   $respConent = str_replace('#$#', '>', $respConent); 
									   
									  // echo 'respContent is: ' . $respConent . '<br/>';
									   $pos = strpos($respConent,'class=');
									   //echo '$pos is '.$pos;
									   $liClassTxt = substr($respConent,$pos,35); //get str starting with class="
									  // echo '$liClassTxt is'.$liClassTxt;
									   if(preg_match('~^[hide]$~',$liClassTxt)) //is 'hide' in that class= str?
									   {
										  $respConent = str_replace($liClassTxt,'7#77****88#8',$respConent);
										  $liClassTxt = str_replace( 'hide','',$liClassTxt);
										  $respConent = str_replace('7#77****88#8',$liClassTxt,$respConent);
									   }
									    $dayContents .= $respConent;
									    //echo $dayContents;
								    }
								    }//end for each jobnode
								    
							    //is this today's cell?
							    if( (integer)$this->activeOrdinalCell === (integer)$dayNode->getAttribute("ordinal") && (integer)$this->activeMonthOrdinal === (integer)$searchMonth->getAttribute('ordinal') ) //if2
							    {
								    //this is today's cell, with special styling applied:
								    $cells .='<div class="date today" ordinal="'.$dayNode->getAttribute("ordinal").'"><div class="day">
								    <img onclick="modalOpen(this)" src="assets/write-circle-green-128.png" title="edit" class="modalImg" id="d'.$dayNode->getAttribute("date").'" />
								    <span class="addNewLine" onclick="addNewLine(this, modal)">&nbsp;+&nbsp;</span>'.$dayNode->getAttribute("date").
								    '</div><ul modalId="d'.$dayNode->getAttribute("date").'" class="edit">'. $dayContents .'</ul></div>';
							    } //end if2
							    else  //else 2
							    {
								    $cells .='<div class="date" ordinal="'.$dayNode->getAttribute("ordinal").'"><div class="day">
								    <img onclick="modalOpen(this)" src="assets/write-circle-green-128.png" title="edit" class="modalImg" id="d'.$dayNode->getAttribute("date").'" />
								    <span class="addNewLine" onclick="addNewLine(this, modal)">&nbsp;+&nbsp;</span>'.$dayNode->getAttribute("date").
								    '</div><ul modalId="d'.$dayNode->getAttribute("date").'" class="edit">'. $dayContents .'</ul></div>';
							    }//end else 2
							    
						    } //end else1			
					    }  //end for each daynode        	
					  $row .= $cells ."</div>"; //close the row div with all children of cells inside;		
					    
				    } //end foreach2		
			    $row .= "</div>"; //close the month div.	
		    }//end foreach $searchMonth
	   }//end foreach $yrNODE
	   
	   //prepare response array
	   // $this->responseArray["firstCell"] =  $this->day1Cell;	
	    $this->responseArray["year"] =  $this->year;
	    $this->responseArray["activeMonthName"] =  $this->activeMonthName;
	    $this->responseArray["activeMonthNumber"] = $this->activeMonthOrdinal;
	    $this->responseArray["activeOrdinalCell"] = $this->activeOrdinalCell;
	    $this->responseArray["theDate"] =  $this->theDate;
	    $this->responseArray["userCompany"] = $this->userCompany;
	    $this->responseArray["html"] =  $row;
	    $response = json_encode($this->responseArray);
	   // GZIP: compress the string first to enhance speed. $compressedResponse = gzencode($response);
	    echo $response;
	    exit;
   
   }//end display()
	
	
	
	// xml node obj YEAR passed in (for each yr in the xml file, this function runs)
	// func called automatically from init function everytime this script runs
	function iterateYEAR($yrNODE){
		
		$searchNode = $yrNODE->getElementsByTagName( "month" );
					
				foreach( $searchNode as $sNode ) {
			    
								
			    		if( (integer) $sNode->getAttribute("ordinal") == (integer) $this->month  )
					{
						
						$this->activeMonthName = $sNode->getAttribute("name");
						$this->activeMonthOrdinal = $this->month;
						
						$this->activeMonthNode = $yrNODE->getElementsByTagName("month")->item($this->month-1);
					}
				}
						
					
				//get the array index value for the current month (ordinal - 1).
				$moOrdin = (integer) $this->activeMonthOrdinal -1; //e.g. may will be 4
				
				//OLD WAY :: $curYr = $this->doc->getElementsByTagName('year')->item(0);
				//dynamic way to get the the current xml node year value
				$this->cYr = $yrNODE->getAttribute("ordinal");
				
				
				
				if(is_object($this->activeMonthNode)){
					$Mo = $yrNODE->getElementsByTagName('month')->item($this->activeMonthOrdinal -1);
					//echo 'Mo is obj';
					$curWks = $this->activeMonthNode->getElementsByTagName('week');
				}			
				
			  //if this request is for a future or past month, don't show the activeOrdinalCell.
			  //if requested year is not this current year, no activeCell.
			  if($this->year != $this->cYr || $this->month != $this->cMo){
				 //no activeCell.  Skip.
			   } else {
				  //avoid fatal error activeMonthNode is NULL
				  if($this->activeMonthNode)
				  {
						  for($x = 0; $dayNode = $this->activeMonthNode->getElementsByTagName("day")->item($x); $x++)
						  {

						   //store the XML node object; active cell's ordinal position into our class properties.			    		
						   if( (integer) $dayNode->getAttribute("date") == (integer) $this->theDate)
						   {			
							   $this->activeDateNode = $dayNode; //obj to refer to this date node in the xml.  For Update operations.
							   $this->activeOrdinalCell = $dayNode->getAttribute("ordinal"); //for hightlighting the current day's html.	

						   }
						}
				  }
				}
		
	}
	
}//end class


?>