<?php //model = models/calendarXML.php
//libxml_use_internal_errors(true);
include($_SERVER['DOCUMENT_ROOT'].'/../classes/dbConnPDO.php');
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
			$this->userCompany = $this->company;
		}
		// echo $this->xmlPath.$this->xmlfile;
		
		$context = stream_context_create($opts);
		libxml_set_streams_context($context);		
		//echo $this->xmlPath.$this->xmlfile;
		 if (file_exists(realpath ( $this->xmlPath.$this->xmlfile ))) 
		 {
			 /*
			 echo "The file exists and it is at ".$this->xmlPath.$this->xmlfile; 
			 exit;
			 OUTPUTS: /home/custo299/public_html/calendar/dev/models/csc.xml
				 */
			 $this->doc = new DOMDocument();		
			//echo '    YES';
			//http://de.php.net/manual/en/domdocument.load.php		
			if( $this->doc->load(filter_var(realpath($this->xmlPath.$this->xmlfile )), LIBXML_NOBLANKS))
			{	
				//exit('THE FILE LOADED');
				//start calendar node AS $c (there is always just one of these)
				$this->companyCalendar = $this->doc->getElementsByTagName("calendar")->item(0);
				
				
				//get each of the year nodes as $yrNode, and save to our year node array;
				for( $i = 0; $yrNode = $this->companyCalendar->getElementsByTagName("year")->item($i); $i++ ) {		    		
					//echo "Found a year node and it is ".$yrNode->getAttribute("ordinal");
					if( (integer)$yrNode->getAttribute("ordinal") === (integer)$this->year ){

						$this->activeYearNode = $yrNode;
						//echo '$this->activeYearNode equates to'. $this->activeYearNode;
					}
					//store each year node (if more than one in the xml) to an array to iterate thru
					array_push($this->arXmlYearNodes, $yrNode);		
				}
				
				foreach($this->arXmlYearNodes as $yrNODE){
					$this->iterateYEAR($yrNODE);//param is the yr node 
				}
			} else {
				//exit('THE FILE DID NOT LOAD');
			}
			// var_dump($this->arXmlYearNodes); exit;
		}//file_exists
		else {
			echo '    NO.  The xml document does not exist.';
		}
		 
	}

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
	function saveMonth() {		
		/* Check that user possess the access_token to perform this action */		
		$this->pdoDB = parent::dbOpen('custo299_wipcalendar');		
		$query = 'SELECT COUNT(id) FROM `active_token` WHERE `id_user` = :userID AND `coid_token` = :coID';
		$statement = $this->pdoDB->prepare($query);
		$statement->bindParam(':userID', $_POST['userID']);
		$statement->bindParam(':coID', $_POST['coID']);
		if( $statement->execute() ) {			
			$row = $statement->fetch(PDO::FETCH_ASSOC);
			if( $row["COUNT(id)"] < 1 ) {
				//user does NOT have the access_token permission to save at this time.
				$msg = array('msg'=>"Not Saved.  Another user with save rights is currently logged in.");
				$msg = json_encode($msg);
				exit($msg);
			} else {		
				// THIS USER HAS THE ACCESS_TOKEN -> AUTHORIZED TO SAVE THE CALENDAR.
				// Convert HTML to be saved ( POST[content] ) into DomDocument.
			    $this->content = stripslashes($this->content); // remove escaping backslashes, if any
			    $htmlDoc = new DOMDocument();		
			    $htmlDoc->loadHTML($this->content);
			    //free some memory:
			    unset($this->content);
			    /* set the domdoc node contents into an array such that:
			    // array[12][4][100100] is the html contents for job 100100, which should be saved to the 
			    // xml doc at node December 4th for the current iteration year we are in.
			    // it will be saved in a <job> node with attrib 'number' equal to 100100.
			    */
			    $xmlToSaveArray = array();				
			    $tempArray = array();
			    //search html object tree ....
			    //$htmlSearchDiv = $htmlDoc->getElementsByTagName("div");
				for($z=0; $div = $htmlDoc->getElementsByTagName("div")->item($z); $z++) {				    

					/*// DEBUG: Examine the Contents of the Current Div.
					 $v = html_entity_decode(json_encode($div ->nodeValue));
					//*/

					/* TODO: 	Getting the Month Ordinal Value below MUST be done within 
							a FOREACH YR Node Ordinal Value.  Otherwise, there will 
							be, e.g., the possibility of more than 1 month with an 
							ordinal of, say, 2 (feb) & identical content would probably 
							save to both Feb 2017 and Feb 2018.

							UNTIL ADDRESSED, best to have not more than 12 months total 
							within an xml structure.
					*/

					/* TODO:  look for substring 'month' in class attrib, to achieve 
							greater stability against future class attrib modifications.
					*/

					if( $div->getAttribute( "class" ) == "month" || $div->getAttribute( "class" ) == "month hide" ) {
					   $monthOrd = ( integer )$div->getAttribute( "ordinal" ); //this number will be utilized 
					   // as the index value in the html content array, to map htmldoc month node 
					   //contents to a matching xml mo. node.	
					   $yearOrd = ( integer )$div->getAttribute( "yr" );
					   //Here, $div is a month div to iterate into / extract LI HTML to store in an array, 
					   //then write into the xml;
					   for( $d=0; $dateDiv = $div->getElementsByTagName( "div" )->item( $d ); $d++ ) { 
						    //for each date div within the current month $div
						    if( $dateDiv->getAttribute( "class" ) == "date" || $dateDiv->getAttribute( "class" ) == "date today" ) {
							    $ord = ( integer )$dateDiv->getAttribute( "ordinal" );
							    //we have a date cell with potentially new content
							    //$debugMsg .= $z . " - we have a date cell with potentially new content - ";
							    //drill down to this div's child ul of class="edit" for its content.
							    // echo json_encode(addslashes($div ->nodeValue));
							    for( $i=0; $childUL = $dateDiv->getElementsByTagName( "ul" )->item( $i ); $i++ ) {
									  // $debugMsg .= "\r\n"; 
									  // $debugMsg .= " -we have the child UL for the date cell \r\n"; 
									  if( $childUL->getAttribute( "class" ) == "edit" ) {
										$xmlToSaveArray[ $yearOrd ][ $monthOrd ][ $ord ] = array();
										// what if we want to save an empty value (no <li> tags)?
										if( $childUL->getElementsByTagName( "li" )->length > 0 ) {
											foreach( $childUL->getElementsByTagName( "li" ) as $li ) {
												// we have the edit div, let's get the contents	  
												// echo 'an edit class node was found';
												// get each list and save to the ordinal index for our array
												$val = '';
												// remove the .hide class if present to 
												// prevent entries from hiding when xml loads next time.
												$class = $li->getAttribute( 'class' );
												if( preg_match( '~^[ hide]$~', $class ) ) {
													$val = str_replace( $class, '7#77****88#8', $val );
													$liClassTxt = str_replace( ' hide', '', $class );
													$val = str_replace( '7#77****88#8', $class, $val );
												} 

											// ad hoc not LI's added by admins have NO SPAN.
											// have to account for this when saving.

											// DEFINE THE jobNmbr VARIABLE AS THE 1st SPAN CHILD
												$span = $li->getElementsByTagName( "span" );
												if( $span->length ) {
													// this LI contains at least 1 node of type "span".

													// the 1st span node [aka item(0)] will usually be the
													// jobnumber-identity-holder (within its 'id' attribute).

													// copied / pasted jobs don't have an id attrib,
													// but do use a class beginning with "copyof".
													if( $span->item( 0 )->getAttribute( "id" ) ) {
														$jobNmbr = $span->item( 0 )->getAttribute( "id" );
														if( strlen($jobNmbr) > 2 ) {
															if( strpos( $jobNmbr, 'job_' ) !== false ) {
																// the span's id attrib holds our job number:
																// store the job number.
																$jobNmbr = str_replace( "job_", "", $jobNmbr );
															} elseif( strpos( $jobNmbr, 'note' ) !== false ) {
																// this is a [+] added LI by an admin.
																$jobNmbr = 'admin-note';
															}
														} else {
															$jobNmbr = null;
														}// if/else id attrib len > 2
													}// if get 1st span's "id" attrib
													elseif( strlen( $span->item( 0 )->getAttribute( "class" ) ) > 2 ) {
														//span has no id attrib.  Try for "class" attrib:
														//e.g., 'copyof' spans.
														$clasVal = $span->item( 0 )->getAttribute( "class" );			
														if( strpos( $clasVal, 'copyof' ) !== false ) {					$jobNmbr = $clasVal;						
														} elseif( strpos( $clasVal, 'note' ) !== false ) {
															//there can be classes instead of id's
															//of value "admin-note"
															$jobNmbr = 'admin-note';								
														}
													}//end elseif try to get a class attrib from the span node.
													else {
														$jobNmbr = null;
													}
												}
											    //echo 'there are '.$childUL->getElementsByTagName("li")->length.' list elements for this edit UL found';										    

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
														$xmlToSaveArray[ $yearOrd ][ $monthOrd ][ $ord ][ $jobNmbr ] = $val;
												 }												
											}//end foreach li
									      }//end if li length
									  }
								}//end for each date div
						    }
					} //end if date div

				}//end if month div
			}
			// var_dump($xmlToSaveArray); exit;
		   /* ***************NOW BEGIN ITERATING OVER THE XML DOC NODES, SAVING DATA FROM xmlToSaveArray******************** */
		   for( $xyr = 0; $XMLyr = $this->companyCalendar->getElementsByTagName( 'year' )->item( $xyr ); $xyr++ ) {
				// iterate the month nodes in the (each) year node(s) of the xml doc.
				$currentXMLyr = ( integer )$XMLyr->getAttribute( 'ordinal' );		   	
			   
			    // companyCalendar XML's month nodes, each as $m.
			    for( $i = 0; $m = $XMLyr->getElementsByTagName( 'month' )->item( $i ); $i++ ) {
				    // the xml month's ordinal will be used to map it to the index value of our html 
				    // within our xmlToSaveArray
				    $currentXMLmo = ( integer )$m->getAttribute( 'ordinal' );
				    for( $s = 0; $d = $m->getElementsByTagName( "day" )->item( $s ); $s++ ) {
					    $currentXMLday = ( integer )$d->getAttribute( 'ordinal' );
					    // prevent moved jobs in cal from duplicating across original and new date.
					    // Best just to completely remove all job nodes from the XML doc,
					    // then append the new jobs using the updated html array:
					    for( $iJ = 0; $j = $d->getElementsByTagName( "job" )->item( $iJ ); $iJ++ ){
						   $d->removeChild( $j );
					    }
					    $d->nodeValue = '';
						  
						// Structure for June 4 2017, contents for job # 01234 is like this: array[2017][6][4][01234]=>li contents

						//go directly to the html array contents for the current XML day node that we have accessed:				    
						$HTMLdayContentsArray = $xmlToSaveArray[$currentXMLyr][$currentXMLmo][$currentXMLday];	
					   	
						if( count( $HTMLdayContentsArray ) > 0 ) { //array of LIs with content with index value of job number.
							foreach( $HTMLdayContentsArray as $jNbr => $job ) {
							   $jobNode = $this->doc->createElement( 'job', $job );
							   $attrib = $this->doc->createAttribute( 'number' );
							   // assign job# to the new attribute
							   $attrib->value = $jNbr;
							   // attach attrib to the new element
							   $jobNode->appendChild( $attrib );
							   // append this as a node to this day node:
							   $d->appendChild( $jobNode );
							   $this->doc->formatOutput = true;									
							   // Save the XML with the appended node.
							   $this->doc->save( $this->xmlPath.$this->xmlfile );
							}
						 } else {
							// if update has nothing to add, the xml's date cell needs to be emptied
							$d->nodeValue = '';
						 }




				    }//end for $d				    
				}// end each month in xml
			    }// end each year in xml
				// Nicely format the structure.
				// save change
				$this->doc->formatOutput = true;

				// Save the XML with the appended node.
				if( $this->doc->save( $this->xmlPath.$this->xmlfile ) ) {
					$msg = array('msg'=>"Successfully saved.");
					echo json_encode($msg);
				}
				else {
					$msg = array('msg'=>"Save status uncertain; try again, then reload the page to see if updates held.  If this message continues, notify Web Team.");
					echo json_encode($msg);
				}
				// $debugMsg .= "Successfully saved \r\n" . $debugMsg
				// echo $response;
				exit;
			
			}// END else user does have access token
		}// END if we connect to the database
		else {
				// user does NOT have the access_token permission to save at this time.
				$msg = array('msg'=>"Database Connection Failed: Could not Confirm Your User Privileges. Action not completed.");
				echo json_encode($msg);				
			}
		 
	    }// end saveMonth method.
	
// display the company calendar
 function display() 
 {	
	 $row = '';// rows are the weeks in html
	 $cells = '';// date cells as html output
	 //var_dump($this->arXmlYearNodes);exit;
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
					    $row .= '<div class="calRow" id="row'.($i).'">'; //row1, row2, etc.
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

$calendar = new calendar;
$calendar->init();

if( $calendar->method==NULL || empty($calendar->method) ){
	$calendar->display;
} else {
	//echo 'Else was called!  ';
	$action = $calendar->method; 
	$calendar->$action();//update, display...etc. [set by init()]
}


?>