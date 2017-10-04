<?php //model = models/calendarXML.php
libxml_use_internal_errors(true);

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

class calendar {
	//submitted data
	public $cMo;
	public $cYr;
	public $content;
	public $year;
	public $month;	
	public $theDate;	
	public $company;
	public $method;
	public $xmlPath = '/home/custo299/public_html/calendar/models/calendar.xml';
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
		
		$context = stream_context_create($opts);
		libxml_set_streams_context($context);		
		
		 if (file_exists(realpath ( '/home/custo299/public_html/calendar/models/calendar.xml' ))) 
		 {
			 $this->doc = new DOMDocument();		
			
			//http://de.php.net/manual/en/domdocument.load.php		
			if( $this->doc->load(filter_var(realpath('/home/custo299/public_html/calendar/models/calendar.xml'))))
			{	
				
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
						}						
					}
				
				
			     $searchNode = $this->activeYearNode->getElementsByTagName( "month" ); 
				foreach( $searchNode as $sNode ) {
				
			    
								
			    		if( (integer) $sNode->getAttribute("ordinal") == (integer) $this->month )
					{
						
						$this->activeMonthName = $sNode->getAttribute("name");
						$this->activeMonthOrdinal = $this->month;
						
						$this->activeMonthNode = $this->activeYearNode->getElementsByTagName("month")->item($this->month-1);
					}
				}
						
					
				//get the array index value for the current month (ordinal - 1).
				$moOrdin = (integer) $this->activeMonthOrdinal -1; //e.g. may will be 4
				$curYr = $this->doc->getElementsByTagName('year')->item(0);
				
				
				
				if(is_object($this->activeMonthNode)){
					$Mo = $curYr->getElementsByTagName('month')->item($this->activeMonthOrdinal -1);
					//echo 'Mo is obj';
					$curWks = $this->activeMonthNode->getElementsByTagName('week');
				}			
				
			  //if this request is for a future or past month, don't show the activeOrdinalCell.
			  //if requested year is not this current year, no activeCell.
			  if($this->year != $this->cYr || $this->month != $this->cMo){
				 //no activeCell.  Skip.
			   } else {
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
		
		$this->content = stripslashes($this->content); // remove escaping backslashes, if any
		
		
	    $htmlDoc = new DOMDocument();		
	    $htmlDoc->loadHTML($this->content);
	
	    
	    $xmlToSaveArray = array();
	    //search html object tree ....
	    //$htmlSearchDiv = $htmlDoc->getElementsByTagName("div");
	    
	   
		 for($z=0; $div = $htmlDoc->getElementsByTagName("div")->item($z); $z++)
		 {
		    //$debugMsg .= " - a div tag was found - ";
		    //if the ordinal cell does not have a class="empty" (meaning it is a cell with a date for the save month)
		    
		    
		   // $v = html_entity_decode(json_encode($div ->nodeValue));
		   
		    
		    if( /*$div->getAttribute("ordinal") && */ $div->getAttribute("class") == "date" || $div->getAttribute("class") == "date today"  ) 
		    {
			    //we have a date cell with potentially new content
			   //$debugMsg .= $z . " - we have a date cell with potentially new content - ";
			   $ord = $div->getAttribute("ordinal");
			   //drill down to this div's child ul of class="edit" for its content.
			   // echo json_encode(addslashes($div ->nodeValue));
			   for($i=0; $childUL = $div->getElementsByTagName("ul")->item($i); $i++)
			   {
				  //$debugMsg .= "\r\n"; 
				  //$debugMsg .= " -we have the child UL for the date cell \r\n"; 
				  if( $childUL->getAttribute("class") == "edit" ) 
				  {
					  //we have the edit div, let's get the contents	  
					
					//get each list and save to the ordinal index for our array
					$val = '';
					
					 //what if we want to save an empty value (no <li> tags)?
					if($childUL->getElementsByTagName("li")->length > 0) 
					{
					 foreach($childUL->getElementsByTagName("li") as $li) 
					 {
						 						  
						  $val .= (string)$htmlDoc->saveXML( $li );
						  
						 // echo "json encoded $val is: " . $val;
						  
						  $val = str_replace('<br>', '', $val);
						  $val = str_replace('</', '*^*', $val);
						  $val = str_replace('<', '~~', $val); 
						  $val = str_replace('/>', '$^$', $val); 
						  $val = str_replace('>', '#$#', $val); 
						  //remove the .hide class if present to 
						  //prevent entries from hiding when xml loads next time.
						  $pos = strpos($val,'class="');
						  $liClassTxt = substr($val,$pos,40);
						  if(preg_match('~^[ hide]$~',$listClassTxt))
						  {
							 $val = str_replace($liClassTxt,'7#77****88#8',$val);
							 $liClassTxt = str_replace( ' hide','',$liClassTxt);
							 $val = str_replace('7#77****88#8',$liClassTxt,$val);
						  }
									  
						  $xmlToSaveArray[$ord] = $val; //add new element to array (li tags and contents of each).
					 }
					 } 
					 else 
					 {
						// there are no LI tags for this UL.  
						// clear them out of the xml node if it has prior content.
						
						$xmlToSaveArray[$ord] = $val; 
					 }
				  }
			   }			   
		    }		    
	    }
		
	    //company calendar xml node for the current requested save operation.
	    
	    for( $i = 0; $m = $this->companyCalendar->getElementsByTagName('month')->item($i); $i++ ) {
			   
		    if( $m->getAttribute('ordinal') == $this->month ) 
		    {				   
			    $this->activeMonthNode = $m; //is this an obj node?
			    //$debugMsg .= " -we have the month node in xml \r\n"; 
		    }
		    if($this->activeMonthNode != NULL)
		    {
			   // $debugMsg .= " -the xml month node is not null \r\n";
			    for( $i = 0; $d = $this->activeMonthNode->getElementsByTagName("day")->item($i); $i++ ) 
			    {		    		
				    
				    foreach( $xmlToSaveArray as $key=>$newContent ) {
					    
					    if( $d->getAttribute('ordinal') == $key ) {
						 						
							   $d->nodeValue = $newContent;
							   $this->doc->save($this->xmlPath);	    
						  
					    }    
				    }				    
			    }
		    }
	    } //end for
		   
		     // Nicely format the structure.
			$this->doc->formatOutput = true;
			// Save the XML with the appended node.
			if($this->doc->save($this->xmlPath))
			{
				echo json_encode("Successfully saved.");
			}
			else
			{
				echo json_encode("Not sure it saved this time; try again.  If this message continues, notify the Web Team.");
			}
			//$debugMsg .= "Successfully saved \r\n" . $debugMsg
			
			//echo $response;
			exit;
		 
	    }//end func saveMonth
		

//display the company calendar
	function display() 
	{		
		

		
		$row = '';//rows are the weeks in html
		$cells = '';//date cells as html output
		
		
		$searchMonthNode = $this->activeYearNode->getElementsByTagName('month');
		foreach($searchMonthNode as $searchMonth) //foreach1
		{
			
			if($this->activeMonthOrdinal == (integer)$searchMonth->getAttribute('ordinal'))
			{ 
				$row .= '<div class="month" ordinal="'.$searchMonth->getAttribute('ordinal').'">';
			}
			else 
			{
				$row .= '<div class="month hide" ordinal="'.$searchMonth->getAttribute('ordinal').'">';
			}
				
				//this must be the current active month to display
				//start building the html for calendar cells;
				$searchNode = $searchMonth->getElementsByTagName('week');
				$i=1;
				foreach($searchNode as $searchNode)
				{
				
					//must clear your cells for each new row of weeks.
					unset($cells);$cells='';
					$row .= '<div class="row" id="row'.($i).'">'; //row1, row2, etc.
					$i++;
					for( $t = 0; $dayNode = $searchNode->getElementsByTagName('day')->item($t); $t++ )
					{
						if($dayNode->getAttribute("date") == '') //if1
						{ //empty day cell; not a date.
							$cells .='<div class="empty" ordinal="'.$dayNode->getAttribute("ordinal").'"></div>';
						}  //end if1
						else  //else1
						{
						    //Add the html angle brackets back in:
						    $respConent = str_replace('*^*', '</', $dayNode->nodeValue);
						    $respConent = str_replace('~~', '<', $respConent); 
						    $respConent = str_replace('$^$', '/>', $respConent); 
						    $respConent = str_replace('#$#', '>', $respConent); 					
						
							//is this today's cell?
							if( $this->activeOrdinalCell == $dayNode->getAttribute("ordinal") ) //if2
							{
								$cells .='<div class="date today" ordinal="'.$dayNode->getAttribute("ordinal").'"><div class="day">
								<img onclick="modalOpen(this)" src="assets/write-circle-green-128.png" title="edit" class="modalImg" id="d'.$dayNode->getAttribute("date").'" />
								<span class="addNewLine" onclick="addNewLine(this, modal)">&nbsp;+&nbsp;</span>'.$dayNode->getAttribute("date").
								'</div><ul modalId="d'.$dayNode->getAttribute("date").'" class="edit">'. $respConent .'</ul></div>';
							} //end if2
							else  //else 2
							{
								$cells .='<div class="date" ordinal="'.$dayNode->getAttribute("ordinal").'"><div class="day">
								<img onclick="modalOpen(this)" src="assets/write-circle-green-128.png" title="edit" class="modalImg" id="d'.$dayNode->getAttribute("date").'" />
								<span class="addNewLine" onclick="addNewLine(this, modal)">&nbsp;+&nbsp;</span>'.$dayNode->getAttribute("date").
								'</div><ul modalId="d'.$dayNode->getAttribute("date").'" class="edit">'. $respConent .'</ul></div>';
							}//end else 2
						} //end else1			
					}  //end for           	
				   $row .= $cells ."</div>"; //close the row div with all children of cells inside;		
				   	
				} //end foreach2		
			$row .= "</div>"; //close the month div.	
		}//end foreach1 $searchMonth
		
		//prepare response array
		// $this->responseArray["firstCell"] =  $this->day1Cell;	
		 $this->responseArray["year"] =  $this->year;
		 $this->responseArray["activeMonthName"] =  $this->activeMonthName;
		 $this->responseArray["activeMonthNumber"] = $this->activeMonthOrdinal;
		 $this->responseArray["activeOrdinalCell"] = $this->activeOrdinalCell;
		 $this->responseArray["theDate"] =  $this->theDate;
		 $this->responseArray["html"] =  $row;
		 $response = json_encode($this->responseArray);
		 echo $response;
		 exit;
	
	}//end display()
	
}//end class


?>