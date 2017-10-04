<?php
/**
  * Perform a backup of the html contents for the current 
  * user's calendar and company
  * POST Array "content":htmlDataToSave,"year":year,"month":month,"theDate":theDate,"company":curCompany
  *
  **/

// procedure: (1) copy the company xml file to the backup drive, rename the file; 
// (2) remove all data (resulting in a bare nodes-only xml file);
// (3) perform save operation to the new file from the live html data.
// (4) report result to user.

class backup{
	
	private $content;
	private $company;	
	private $filename;	
	private $simpleXml; //simpleXML instance
	private $simpleXmlDoc; //xml resource obj	
	private $domDoc; //domDocument instance
	private $docHTML; //domDocument html resource	
	private $xpdXML; //xPath for XML resource
	private $xpdHTML; //xPath for XML resource
	private $savePath = __DIR__."/models/userbackups/";	
	private $sourcePath = __DIR__."/models/";
	private $status;
	
	
	function __construct() {
		
		//save POST to class properties of same name:
		foreach( $_POST[] as $k=>$x ){
			
			if( empty($x) ){
				
				exit("Could Not Complete Your Request.  Missing Data for ".$k);
				
			} else {
				
				$this->$k = $x;
			}
		}			
			
		$this->status = '[OK] User Data Validated.  [TRY] Creating the File...<br>';		

		$result = $this->copyXMLfile();	
		
		echo $this->status;
		
	}// __construct
	
	/*
	// return an xml filename
	private function fileName(){
		
		$fnCompany = strtolower( str_replace( ' ', '_', trim( $this->company ) ) );		
		$fnUser = strtolower( $this->username );
		$fnDate = date( 'Y-m-d_hh-mm', strtotime( 'now' ) );
		
		return $fnDate.'--'.$fnUser.'--'.$fnCompany.".xml";
		
	}// fileName
	*/
	/*	
	private function htmlToXml(){
		
		// start the xml file as resource:
		$this->simpleXml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?>
				<!DOCTYPE calendars [
				<!ENTITY nbsp "&#160;">
				]><calendars><calendar id="'.$this->company.'"></calendar></calendars>');	
	
		
		$this->dom = new DOMDocument;
		libxml_use_internal_errors(true);
		$this->dom->loadHTML($this->html);
		$htmlXpath = new DOMXPath($doc);
		$xmlXpath = new DOMxPath( $this->simpleXml );
		// returns a list of all divs with class=month
		//$hMonths = $xpath->query("//div[@class='month']");
		$hMonths = $htmlXpath->query("//div[@yr]"); //all divs with attribute 'yr' (month divs)
		
		for( $i=0; count( $hMonths )>$i; $i++ ){
			//from the HTML doc
			
			$moYr = $hMonths[$i]->getAttribute( 'yr' );
			$ord = $hMonths[$i]->getAttribute( 'ordinal' );
			$moName = $this->ordinalMonthToString( $ord );
			
			// if there's one or more year nodes:
			if( $xmlYearNodes = $xmlXpath->query("//year") ){
				
				if(count( $xmlYearNodes )>1){
					
					//get the correct year node for the 
					//html month node to write to:					
					foreach( $xmlYearNodes as $xYrNode ){
						
						if( (string)$moYr === (string)$xYrNode->getAttribute('ordinal') ){
							
							$xYrNodeTarget = $xYrNode;
							
						}						
						
					}					
					
				} else {
					//only 1 year node to write to:
					
					$xYrNodeTarget = $xmlYearNodes[0];
					
				}
				
			} else {
				
				// No yr nodes match for this month's yr... add node to calendar
				$yrNode = $this->simpleXml->createNode('year');
				$yrNode->addAttribute('ordinal',$moYr);
				$this->simpleXml->getElementByTagName('calendar')->appendNode($yrNode);				
				$xYrNodeTarget = $xmlXpath->query("//year")->item[0];
			}
			
			// xPath year node to append the html month node to is set as $xYrNodeTarget			
			$mNode = $this->simpleXml->createNode('month');
				$yrNode->addAttribute('ordinal',$moYr);
			
			//$xmlStructure = ;
			
		}
		
		
		
		
		
		
		foreach ($images as $image) {
			   $image->setAttribute('src', 'http://example.com/' . $image->getAttribute('src'));
		}
		$html = $this->dom->saveHTML();
		
	}// htmlToXml
	*/
	
	
	private function copyXMLfile() {
		
		$fileSuffix;
		
		switch($this->company){			
			case 'Custom Sign Center':
			$fileSuffix = 'csc.xml';			     
			break;
			case 'JG Signs':
			$fileSuffix = 'jg.xml';
			break;
			case 'Marion Signs':
			$fileSuffix = 'mar.xml';
			break;
			case 'Boyer Signs':
			$fileSuffix = 'boy.xml';
			break;
			case 'Outdoor Images':
			$fileSuffix = 'out.xml';
			break;
			case 'Marion-Outdoor':
			$fileSuffix = 'marout.xml';
			break;						
		}
		
		
		//(1) copy the xml calendar file to the backup folder 
		//    bool copy ( string $source , string $dest [, resource $context ] )
		
		$src = $this->sourcePath.$fileSuffix;
		$dest = $this->savePath.$this->filename.$fileSuffix;
		
		if( copy( $src, $dest ) ){ 
			
			$this->status .= "[OK] File Created.  [TRY] Update the File with Restoration Data...<br>";
			
		} else {
			
			$this->status .= "[FAIL] Could Not Create the File.  Exiting.<br>";
			exit($this->status);
		}
		
		$this->saveHTMLtoXMLbackupFile($dest);
		
	}// copyXMLfile
	
	
	function saveHTMLtoXMLbackupFile($destFile) 
	
	{
		
	    $this->content = stripslashes($this->content); // remove escaping backslashes, if any
		$this->domDoc = new DOMDocument();
	    
	
	

	    if( file_exists( $destFile ) ){
		    
		     $this->domXML = $this->domDoc->load( $destFile ); //load xml from file;
		     $this->xpdXML =  new DOMXPath( $this->domXML );
		    

	    } else {

			exit( $this->status .= "[FAIL] Could Not Load the XML Structure.  Exiting.<br>" );
	    }	
	    		
	    $this->docHTML = $this->domDoc->loadHTML($this->content);
	    $this->xpdHTML = new DOMXPath( $this->domHTML );
		
	    // Next: Clean out all data from the xml nodes before writing in the new data from HTML.
	    $cleanCount = $this->emptyXmlNodes();
		
	    $this->status .= '[OK] Wiped clean '. $cleanCount . ' jobs from the cloned file...';
	    
	    $xmlToSaveArray = array(); 
	    $tempArray = array();
	    //search html object tree ....
	    //$htmlSearchDiv = $htmlDoc->getElementsByTagName("div");
	    
	   
		 for($z=0; $div = $htmlDoc->getElementsByTagName("div")->item($z); $z++)
		 {
		    // $debugMsg .= " - a div tag was found - ";
		    // if the ordinal cell does not have a class="empty" (meaning it is a cell with a date for the save month)		    
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
											   $this->doc->save($this->xmlPath.$this->companyAbbrev);
											  
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
			if($this->doc->save($this->xmlPath.$this->companyAbbrev))
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
		
	
	
	private function emptyXmlNodes(){
		
		$jNodes = $this->xpdXML->query('//calendars/calendar/year/month/week/day/job');
		$jobCount = count($jNodes);
		
		for( $i=0; $i<$jobCount; $i++ ){
			
			$jNodes[$i]->nodeValue = '';
		}
		return $jobCount;
	}// emptyXmlNodes
	
	
	
	
	
	
	
	
	
	
}// backup

?>