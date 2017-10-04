<?php

class Maintenance {
	
	private $posted;
	private $xmlfile;
	
	
	private function __construct()
	{		
		$this->posted = $_POST[];
		
		$opts = array(
		    'http' => array(
			   'user_agent' => 'PHP libxml agent',
		    )
		);
		$this->company = trim($this->posted['company']);
		$fileTime = date('Y-m-d__hh-mm', strtotime('now'));		
		switch($this->company){			
			case 'Custom Sign Center':
			$this->xmlfile = 'csc_'.$fileTime.'.xml';
			break;
			case 'JG Signs':
			$this->xmlfile = 'jg_'.$fileTime.'.xml';
			break;
			case 'Marion Signs':
			$this->xmlfile = 'mar_'.$fileTime.'.xml';
			break;
			case 'Boyer Signs':
			$this->xmlfile = 'boy_'.$fileTime.'.xml';
			break;
			case 'Outdoor Images':
			$this->xmlfile = 'out_'.$fileTime.'.xml';
			break;
		     case 'MarionOutdoor':
			$this->xmlfile = 'marion-outdoor_'.$fileTime.'.xml';
			break;
			default:
			$this->xmlfile = 'notfound_'.$fileTime.'.xml';			
		}
		
		//if development server, use a different path for the xml 
		//define the xml path:
		if( preg_match('~wwwroot~', __DIR__) ) {
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
			if( $this->doc->load(filter_var(realpath($this->xmlPath.$this->xmlfile ))))
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
		
		
		
		
		
	}//construct()
	
	private function backup(){
		
	
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
		   
		   
		   if( $div->getAttribute("class") == "month" || $div->getAttribute("class") == "month hide"  )
		   {
			   $monthOrd = $div->getAttribute("ordinal"); //used as the index value for our html array of content to save to our xml file.			   
		   	   //$div is a month div to iterate thru & extract LI HTML to save to our array, then to xml;
		   
		   	   for($d=0; $dateDiv = $div->getElementsByTagName("div")->item($d); $d++)
			   { //for each date div within the current month $div
		   
		   
		    
		    if( /*$div->getAttribute("ordinal") && */ $dateDiv->getAttribute("class") == "date" || $dateDiv->getAttribute("class") == "date today"  ) 
		    {
			    //we have a date cell with potentially new content
			   //$debugMsg .= $z . " - we have a date cell with potentially new content - ";
			   $ord = $dateDiv->getAttribute("ordinal");
			   //drill down to this div's child ul of class="edit" for its content.
			   // echo json_encode(addslashes($div ->nodeValue));
			   for($i=0; $childUL = $dateDiv->getElementsByTagName("ul")->item($i); $i++)
			   {
				  //$debugMsg .= "\r\n"; 
				  //$debugMsg .= " -we have the child UL for the date cell \r\n"; 
				  if( $childUL->getAttribute("class") == "edit" ) 
				  {
					  //we have the edit div, let's get the contents	  
					//echo 'an edit class node was found';
					//get each list and save to the ordinal index for our array
					$val = '';
					
					 //what if we want to save an empty value (no <li> tags)?
					if($childUL->getElementsByTagName("li")->length > 0) 
					{
						foreach($childUL->getElementsByTagName("li") as $li) 
						{
						    
						    
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
							 
							 //remove the .hide class if present to 
							 //prevent entries from hiding when xml loads next time.
							 $pos = strpos($val,'class="');
							 $liClassTxt = substr($val,$pos,40);
							 if(preg_match('~^[ hide]$~',$liClassTxt))
							 {
								$val = str_replace($liClassTxt,'7#77****88#8',$val);
								$liClassTxt = str_replace( ' hide','',$liClassTxt);
								$val = str_replace('7#77****88#8',$liClassTxt,$val);
							 } 
						    
							    //echo 'val of '. $val . ' saved to xmlarray index '.$monthOrd.' and subindex '.$ord;		  
							 
						}//end foreach li
						$xmlToSaveArray[$monthOrd][$ord] = $val; //add new assoc element to array (li tags and contents of each).
					 } 
					 else 
					 {
						 						 
						// there are no LI tags for this UL.  
						// clear them out of the xml node if it has prior content.
						//echo ' nothing to save for this ul';
						$xmlToSaveArray[$monthOrd][$ord] = $val; 
					 }
				 
				  }
		      }//end for each date div
		    }
		     } //end if date div
			
		   }//end if month div
	    }
	   //print_r($xmlToSaveArray);
		
	    //company calendar xml node for the current requested save operation.
	    
	    for( $i = 0; $m = $this->companyCalendar->getElementsByTagName('month')->item($i); $i++ ) {
			//echo '  we found a month in the xml  ';
			//iterate over array of htm	l LIs.  Structure for June 1 is like this: array(6)(1)=>li contents
			foreach($xmlToSaveArray as $moOrdinal=>$dayOrdinal)
			{
				foreach($dayOrdinal as $ordinal => $content)
				{
				//echo '  We have an xmltosavearray item  ';
				    if((integer)$m->getAttribute('ordinal') == (integer)$moOrdinal)
				    {
					    //echo '  $m->getAttribute(ordinal) == $month for ' . $m->getAttribute('ordinal') .'   ';
					    //the xml month's ordinal is a match for the index of our array
					    for( $s = 0; $d = $m->getElementsByTagName("day")->item($s); $s++ )  //get xml day nodes
					    {		
							    if( $d->getAttribute('ordinal') == $ordinal ) {
									   // echo 'attempting to save possible content: '. $content . '  ';				 
									   $d->nodeValue = $content; //add the content to PHP resource's day node
									   $this->doc->formatOutput = true;
									   $this->doc->save($this->xmlPath.$this->xmlfile); // write new daynode contents to the flatfile
								  
							    }    
											   
					    }
					    
				    }
			}//end nested for each
			
			
		/*	
			   
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
		    }*/
			}//end foreach $xmlToSaveArray
	    } //end for $m
		   
		     // Nicely format the structure.
			
			// Save the XML with the appended node.
			if($this->doc->save($this->xmlPath.$this->xmlfile))
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
		 
	    
		
		
	}//backup()
	
	private function restore(){
		
		
		
	}//restore()
	
	private function verfiyStore(){
		
		
	}//verifyStore()	
	
	/** 
	  * Record each user Maintenance Action.
	  * Include the date, time, attempted action, username and results 
	  */
	
	private function registerAction(){
		
		
	}//registerAction()
	
	private function getActions(){
		
		
	}//getActions()
	
} //Maintenance