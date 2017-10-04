<?php

/**
* Build an XML doc calendar as a template 
* Used to dynamically generate the calendar nodes
* Template Loaded by the Calendar Backup Script
**/

// build calendar array using php time functions

//fire construct func
$c=new CalendarMaker;

// expects post data 'startdate', 'enddate', 'company' formatted as 01/31/2017
class CalendarMaker {
	
	//POST variables:
	private $startdate; //mm/dd/Y
	private $enddate;
	private $company;
	
	//other class properties
	private $XML;
	private $fname;
	private $status; 
	private $savePath = __DIR__."/models/backup/";
	
	
	private $nodeCalId;
	private $nodeYearOrd;
	
	//Date Properties
	private $monthList; //array => year (['2017] => array ('months'([0] => (['name'] = 'January', ['ordinal'] = '1', [2] => ),
					//	   ['2018'] 
	
	
	
	
	function __construct(){
		

		foreach( $_POST[] as $k=>$x ){
			
			if( empty($x) ){
				
				exit("fail - missing data for " . $k);
				
			} else {
				
				$this->$k = $x;
			}
		}		

		$status = 'Application started...<br>';

		//set the filename to use.
		$this->setNames();					

		$this->XMLtemplate = $this->xmlSkeleton();		
		
		echo $status;		
		
	}
	
	
	/**
	 * Set useful variables like 'csc' for file-naming, from 'Custom Sign Center'
	 *
	 **/
	private function setNames(){
		
		$timeName = date('mm-dd-Y_hh-mm', strtotime('now'));
		
		switch ( str_replace(' ','',strtolower($this-company)) ){
				
			case 'customsigncenter' || 'csc':
				$this->fname = $timeName.'$csc.xml';
				$this->nodeCalId = 'Custom Sign Center';
				break;
			case 'outdoorimages' || 'outdoor':
				$this->fname = $timeName.'$out.xml';
				$this->nodeCalId = 'Outdoor Images';
				break;
			case 'boyer' || 'boyersigns':
				$this->fname = $timeName.'$boy.xml';
				$this->nodeCalId = 'Boyer Signs';
				break;
			case 'marionsigns' || 'marion':
				$this->fname = $timeName.'$mar.xml';
				$this->nodeCalId = 'Marion Signs';
				break;
			case 'jgsigns' || 'jg' || 'jgsignservices':
				$this->fname = $timeName.'jg.xml';
				$this->nodeCalId = 'JG Signs';
			default: $this->status .= 'Fatal Error: Company name must be spelled like one of the following:<br>
								  custom sign center, outdoor images, boyer signs, marion signs, jg signs.<br>
								  Exiting.';
				exit($this->status);
		}		
		
	}
	
	
	/**
	 * Create the XML scaffolding 
	 * Load it as the resource 'XML' to build upon.
	 **/
	
	private function xmlSkeleton(){
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		// start the xml file as resource:
		$this->XML = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?>
				<!DOCTYPE calendars [
				<!ENTITY nbsp "&#160;">
				]><calendars><calendar id="'.$this->nodeCalId.'">
				
				
				
				
				
				
				</calendar></calendars>');
		
		
		
		
		
		
		
		
		
	}
	
	
	
	
}



