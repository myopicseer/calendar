
	var weeksDOM = document.querySelectorAll("div#weeks");
	var headerYr = document.getElementById("#headerYr");
	var timeoutID;
	var originalContent='';
	var content;
	var newContent = '';
	var curMonthCounter; //prev/next calendar month counting : increment/decrement
	var lastEditedCell=[];
	var redo=[];	
	var redoObj;
	var year;
	var month; //integer
	var theDate;
	var editableLI; //the active/current right-clicked LI obj that contextmenu will style.
	var todayOrdinalCell; //integer of the cell count for today's date.
	var changes=[];
	var responseMonth; //the most recent month html / data sent by php
	var contextMenu; //this is a mini html popup options menu for editing jobs	
	var listElements = []; //all the list elements that hold job entries
	var boxIDs = ['t0','t1','t2','t3','t4','t5','t6','t7','t8','t9','t10'];
	var modalSource;
	var modalContent;
	var monthName = ["OccupyZeroPosition-PlaceHolder","January","February","March","April","May","June","July","August","September","October","November","December"];
	var dateParts;
	var $icon = '<i class="p1"></i><i class="p2"></i><i class="p3"></i><i class="p4"></i>';
	var teamHTML='';
	var $usr;
	// for rollback purposes; data reporting to user view.
	var historicUpdates = {};
	// holds all user changes in an object; applied to remote xml if a save operation is called.
	var pendingUpdates = {};
	var teamAssignment = [];
	var assignLabels = [];
	var iconSet = {
	'ups':'ic-ups',	
	'unas':'ic-flag',
	'trip':'ic-i-ret-trip',	
	'crane':'ic-i-crane',	
	'crew':'ic-users',
	'parts':'ic-cog',
	'comp':'ic-i-comp-alt',
	'inv':'ic-i-comp-inv',
	'info':'ic-p-inf',
	'inspr':'ic-p-insp-req',
	'inspa':'ic-p-insp-appr',
	'pappr':'ic-p-appr'
	};
	var justReloaded = 0;
	//add/remove jobs that are marked 'overdue' so they can be output to the view in designated areas.
	var overdueJobs = {};
	
	//2 idle user globals
	var IDLE_TIMEOUT = 900; //15 mins
     var _idleSecondsCounter = 0;  
	
	
	//url parameters
		//user's company access rights.  "developer" has no admin user session token or company restrictions.
	var userCompany;	

	var emails = '<div id="copyToClipb">alicia@customsigncenter.com;<br>christina@customsigncenter.com;<br>courtney@customsigncenter.com;<br>dale@customsigncenter.com;<br>dan@customsigncenter.com;<br>debbie@customsigncenter.com;<br>don@customsigncenter.com;<br>doug@customsigncenter.com;<br>emylee@customsigncenter.com;<br>eric@customsigncenter.com;<br>james@customsigncenter.com;<br>jeff@customsigncenter.com;<br>john@customsigncenter.com;<br>jreed@customsigncenter.com;<br>judy@customsigncenter.com;<br>justin@customsigncenter.com;<br>marcus@customsigncenter.com;<br>mary@customsigncenter.com;<br>michael@customsigncenter.com;<br>nathan@customsigncenter.com;<br>sam@customsigncenter.com;<br>scott@customsigncenter.com;<br>tturner@customsigncenter.com;<br>teryl@customsigncenter.com;<br>timh@customsigncenter.com;<br>tim@customsigncenter.com</div>';
	/* for testing only var emailRecipientsCSC = ['info@signcreator.com','cf_is_here@hotmail.com','chris@customsigncenter.com','tim@customsigncenter.com']; */
	var emailRecipientsCSC = ['alicia@customsigncenter.com','christina@customsigncenter.com','courtney@customsigncenter.com','dale@customsigncenter.com','dan@customsigncenter.com','debbie@customsigncenter.com','don@customsigncenter.com','doug@customsigncenter.com','emylee@customsigncenter.com','eric@customsigncenter.com','james@customsigncenter.com','jeff@customsigncenter.com','john@customsigncenter.com','jreed@customsigncenter.com','judy@customsigncenter.com','justin@customsigncenter.com','marcus@customsigncenter.com','mary@customsigncenter.com','michael@customsigncenter.com','nathan@customsigncenter.com','sam@customsigncenter.com','scott@customsigncenter.com','tturner@customsigncenter.com','teryl@customsigncenter.com','timh@customsigncenter.com','tim@customsigncenter.com'];
	//var modalLink = '<a class="modalLink" rel="modal:open"><img src="assets/write-circle-green-128.png" title="edit"</a>'*/
	// var liIndex; //hold the index position of the active list we're referencing in code.
	

function clearAlert() {
  window.clearTimeout(timeoutID);
}

$(document).ready(function (){
	
	$usr = $("#username").text();	
	
     var clipboard = new Clipboard('.copy',{
	  target: function(trigger) {
        	return trigger.nextElementSibling;
	    }		
	});

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

	
	/*disable back/forward page navigation of the browser*/
	history.pushState(null, null, document.title);
	 window.addEventListener('popstate', function () {
		history.pushState(null, null, document.title);
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


	var weekRows = $( "#weeks .row" ); //each row
	//dispay the cal from xml when page first loads
	
	


 document.onkeypress = function() 
 {
    _idleSecondsCounter = 0;
 };
 document.onclick = function() 
 {
    _idleSecondsCounter = 0;
 };
 document.onmousemove = function()
 {
    _idleSecondsCounter = 0;
 };

 window.setInterval(CheckIdleTime, 2000); //checks every 2 seconds
	
	
	
	curCompany=$( "#companyCalendar option:selected" ).val();
	loadCalendar(curCompany,-1,-1);
	$("#pageTitle").html(curCompany + " WIP Calendar");
	$('#companyCalendar').on('change', function() {
  		//$("#company" ).val( this.value ); // or $(this).val()
		curCompany=$( "#companyCalendar option:selected" ).val();
		$("#pageTitle").html(curCompany + " WIP Calendar");
		teamNamesHTML();
		loadCalendar( curCompany,-1,-1 );
		addListenersToDom("true");		
		
		/*if(curCompany !== "Custom Sign Center"){
			alert('Planned Update: Calendar Jobs Will Change for Each Company.');
		}*/
	});
	//event trigger submit a company cal request
	/* Hide Submit to Load new Company Calendar.  Select List on Change now fires the submission
	   New Calendar will now load on that trigger for each selected company.
	$("#loadCalendarForm").on("submit", function(e){
		e.preventDefault();
		var str = $( "#loadCalendarForm" ).serialize(); //selected company name
		loadCalendar( str['companyCalendar'],-1,-1 );
		addListenersToDom();
	});*/

	
	
	
	$("#btnPrev").on('click', function(){
		
		displayNewMonth('prev');
		
	});
	
	
	$("#btnNext").on('click', function(){		
	
			displayNewMonth('next');
		
	});
	
	
	
	
	

	/*capture some keyboard keys and set to desired behaviors inside the .edit container*/
	
  var inputArea = $('.edit > *');
  inputArea.on('keydown', function() {
    var key = event.keyCode || event.charCode;
    //allow backspace (key8) to NOT go back to previous web page, but instead delete backward
    //key46 is the delete key.
    if( key == 8 || key == 46 )
        return false; //prevents default browser/DOM behaviors for the specified keys.
  });	
  
 
  
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
	/*	$( $editULs ).each(function(i,ulEl){
			
			if( $(ulEl).find('li').length < 1){
				$(ulEl).parent('.date').addClass('hide');				
			}
			
		});
	*/
	
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
		
	var data = {"recipients":emailRecipientsCSC,"company":curCompany,"calendar":calendarHTML}; 
	
	    $.ajax({	
			 url : "classes/sendemail.php",
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
   $("#prevEmailPopUp").html(emails);
   $("#previewEmails").on("mouseover", function(e){
	    $("#prevEmailPopUp").removeClass("hide");
	    $(this).html(' Click To Copy Emails to Clipboard');	    
   });
   $("#previewEmails").on("mouseout", function(e){
	    $("#prevEmailPopUp").addClass("hide");
	    $(this).html('Preview Recipients');
   });
  
 
   
	
	
   
   //assign current company hmtl for the team names
   teamNamesHTML();  
   justReloaded = 1;
	
   timeoutID = window.setTimeout(addListenersToDom, 900);

	
}); // doc ready.

//callers set status to 'start' or 'end';
function wait(status){
	if(status=='start') { $( "#wait" ).removeClass( "hide" ); }
	else { $( "#wait" ).addClass( "hide" ); }
}

function addListenersToDom(showTeamsBool = "true")
{
	 var editboxes = document.getElementsByClassName('edit');	 
	// alert("editboxes is: " + editboxes);
	 
	 $.each(editboxes, function(i, elem){		 
		 editboxes[i].addEventListener('dblclick', startEdit, true);
		 //var dateBox = editboxes[i].parentNode; //parent of the edit box.
		//evt.stopImmediatePropagation();stopPropagation()
		
		bindListeners4EachList(elem);
		listsIntoObjects(elem,i);
	 });	 
	 
	 // add listeners to the checkboxes to select the team entries to show or hide
	 // array of inputs - "toggle show / hide" input checkboxes array
	 var checkboxes = $('#teamSelection').find('input');	 
	 
	 $(checkboxes).each(function(i,box){
		 //change listener for each checkbox
		 $(box).on("change", function() {
			 //alert('Checkbox status changed!');
			 //which box changed? Answ = this
			 var chkBoxName = $(this).attr('name');
			 var chkBoxId = $(this).attr('id');
			// alert('the checkbox id is ' + chkBoxId);
			 
			 if( $(this).is(':checked') ) {	
			 	// we need to show these if they are hidden				
				// find each li in the DOM with a class matching the checkbox's chkBoxName.
			
				for( var i = 0; listElements.length > i; i++ ) 
		 		{	
				    if($(listElements[i]).hasClass(chkBoxName))				  		
				    {
					    $(listElements[i]).removeClass("hide");
				    } 							
				 }//end for				
			 }//end is:checked
			 else //change is an uncheck (so hide it)
			 {
				for( var i = 0; listElements.length > i; i++ ) 
		 		{	
				    if($(listElements[i]).hasClass(chkBoxName))				  		
				    {
					    $(listElements[i]).addClass("hide");
				    }		
			 	}
			 }
		 });		 
	 });
	 
	 //remove hide class from any hidden LI elements with a new loading of the page.
	 if(justReloaded === 1)
	 {	//reset the toggling variable to false
		 justReloaded = 0;
		 //this func needs a delay so calling here, not in the doc ready.
		  //this func needs a delay so calling here, not in the doc ready.
		 onloadSetOverdueDisplay();			 		 
	 }
	 
	   //hide the first and last div in each .row (sundays and saturday columns)
/*    var eachMonth =  $("#weeks").find(".month");
    $(eachMonth).each(function(i,el)
    {	 
	   var $monthRow = $(el).find(".row");	   
	   $($monthRow).each(function(i2, row){
		   var div1 = $(row).children("div:eq( 0 )");
		   var div2 = $(row).children("div:eq( 6 )");
		  //DON'T HIDE WEEKENDS ANYMORE
		   //$(div1).addClass("hide");
		  // $(div2).addClass("hide");
	   });	  
    });	*/
    //remove hide class
    if(showTeamsBool === "true"){
    	    teamsShowAll(); 	    
    }
}

// assign each lineEntry LI to the global listElements Obj
function listsIntoObjects(eachUL,i){ 
	//convenience global obj to hold all job entry LI's on the page; used for show specific jobs by team
    var liObj = $(eachUL).find("li");	
    $(liObj).each(function(ct, liItem) {
	    listElements.push(liItem);
	    //console.log('list number' +ct+ ' found for UL number '+ i);
    });
    
   
	
}
	
	
//idle user function:
function CheckIdleTime() {
    _idleSecondsCounter++;
    var oPanel = document.getElementById("SecondsUntilExpire");
    if (oPanel)
        oPanel.innerHTML = (IDLE_TIMEOUT - _idleSecondsCounter) + "";
    if (_idleSecondsCounter >= IDLE_TIMEOUT) {
        var answer = confirm("You've been idle 15 mins.\nPlease click OK to continue session.\nTo close this session click Cancel.");
        if (answer) 
        {
            //document.location.href = "login.php";   
            _idleSecondsCounter=0;
        }
        else{
            //window.open('', '_self', ''); window.close();
            document.location.href = "login.php";
        }

    }
 }

function addNewLine(elem, parentClass){			
					
			var newList = $("<li contenteditable='false' class='lineEntry unassigned newEntry' title='Right Click for Options'><span id='admin-note' contenteditable='false'>&gt;</span></li>");		
			//the ul 
			var ulTarget = $(elem).parents(parentClass).children('.edit');
			$(ulTarget).append(newList);
			var newEntry = $(ulTarget).children(".newEntry")
			
			//addEventListenerToOne(newEntry);
	
			//pass this new LI obj to the func that creates the evt handler for it.		
			bindListeners4EachList(ulTarget);	
			$(newEntry).removeClass("newEntry");
}

function startEdit(e) {
	e.stopPropagation();
	
	 //the first two lines create a new li when you click in a cell. REMOVE: Only Create New Job Entries with the "+" btn.
	 /* 
	 	var btn =  $( this ).parents('.date').children('.day').children('.addNewLine');
	   	addNewLine(btn);   
	 */
	   	   
	  //$( this ).attr("contenteditable","true");
	  
	  //this refers to the .edit UL, which holds the LI's of job entries

	 var thisUL = $(this).closest(".edit"); //siblings if you want all the LIs in the UL
	 originalContent = $( this ).html(); //get the content of the cell before editing it (contenteditable = true) 
	 
	  //Make UL editable
	/* $(this).each(function(index,element){	
	 		
	 	
		//alert(originalContent);
		$(element).attr("contenteditable", "true");		  
	  });
	 */
	 
	   $( this ).addClass( "yellow-bg" );
		//make any existing children (lists) editable
		  $(thisUL).children("li").each(function(ct,listEl){
			
		     var inputArea;
			  
			  
			  
			 	  
		   $(this).on('click', function(){
			   	
				   inputArea = $(listEl).append('<div id="x"><button onclick="saveNote(this)">Save</button><br><input type="textarea" id="y" value="" /></div>');
				   //inputArea = $('#x');
				   $(inputArea).focus(); //set cursor	
			        $(listEl).unbind('click');
			    
		    });			  
		
		

		    //$(listEl).children("span").attr("contenteditable", "false");		
		    // add mouseout set editing false handler here:
		    //	
	    });
	   
}

// called by func start edit.  Param is a LI element set as editable.
// set a handler mouseout to stop editing.
function closeEditing( LIst ){
	
	var parentUL = $(LIst).closest(".edit");
	
	 $( parentUL ).on('mouseout', function(evt) {
		// $domObj refers to the UL of class .edit.
		//foreach edit node <li> that is empty, remove them...
		//addListenersToDom();
		//evt.stopImmediatePropagation();
		
		//$('li.lineEntry:empty').remove();
		//var listTags = $(domObj).children('li');
		//remove any hidden contextMenus, default New Job Lists, and br tags		
		//$(listTags).each(function() {
			/*
			if($(li).text() === '! New Job'){
				$(li).remove();
			}
			*/
			//remove editable attrib				  
		  	 //$(LIst).prop("contenteditable", "false"); 
		      $(LIst).removeAttr("contenteditable");
		       $(parentUL).removeClass( "yellow-bg" ); 
			//remove all break tags.
			$(LIst).find('br').remove();
	
			 
	//	});
		
	
		
		newContent = $(parentUL).html(); //get the newly added cell contents; ContentEditable = false	
		if( originalContent !== newContent ){
			lastEditedCell.push( $(parentUL) );
			//console.log("Orig is: "+originalContent);
			//console.log("New is: "+newContent);
			//get the id of element with the year.
			//year = $( '#yr' ).attr('ordinal');
			//get the id of element with the year.
			//month = $( domObj ).parent().attr('ordinal') -1;
			//date = $( domObj ).parent().find('.date').val(); //get date
		} 
		//else for debugging only
		else {
			//No Changes to Save. 
			//$( domObj ).html('');
		}
		$(parentUL).off( 'mouseout' );
	   });   
	
}

function loadCalendar(co, m, y){
	//console.log('load calendar func called. CurCompany is: '+ co['companyCalendar']);
	if(typeof co === 'undefined') co = curCompany;
	wait('start');
	var today = new Date(); //date obj
	var cDate = today.getDate(); //current date.
	
	//if month and yr not passed in...
	if(m<0){//set month to cur month		
		var m = today.getMonth()+1; //month is zero-based (+1)
	}
	if(y<0){//set year to cur year .. by default, load cur yr on first load.			
			var y = today.getFullYear();		
	}
	
	
	//date = current date to highlight, month=req mo, year=req yr.
	var data = {"content":'',"year":y,"month":m,"theDate":cDate,"company":co,"method":"display",}; 
	
	$.ajax({	
		  url : "classes/calendar.php",
		  type: "POST",
		  data : data,
		  dataType:"json",
	   success: function(respData, textStatus, jqXHR)
	   {
		   wait('end');
		 //debug:
		 /* console.log('respData was: ' + respData);*/
		  theDate = respData.theDate;
		  year = respData.year;
		  month = respData.activeMonthNumber;
		  curMonthCounter = month;
		  //monthName = respData.activeMonthName;	
		 
		  todayOrdinalCell = respData.activeOrdinalCell;
		 $("#weeks").html(respData.html);
		 //for "undo all" operations, preserve copy of html
		 var respDataHtml = toString(respData.html);
		 $("#yr").html(year);
		 $("#yr").attr('ordinal', year);
		 $("#mo").html(monthName[month]);		 
		 $("#mo").attr('ordinal', month);
		 //the company the user has access rights for:
		 // "developer" = no user session; user is within the /dev directory. No 'admin' login blocking applies.
		 userCompany = respData.userCompany;
		 //$(".month").attr('yr', year);	 
		 
	   },
	   error: function (jqXHR, textStatus, errorThrown)
	   {
		  wait('end');
	   }	
  	});
	timeoutID = window.setTimeout(addListenersToDom, 300);	
	justReloaded = 1;
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

//form buttons' functions
function saveMonth(){
	
	//b4 save, remove any open admin note input forms and yellow edit classes.
	var yellowUL = $('#weeks').find(".yellow-bg");
	
	$.each(yellowUL, function(){	
		
		$(this).removeClass('yellow-bg');
		
		var noteInput = $(this).find('#x');
		
		$.each(noteInput, function(tr, rt){
			  
			  $(rt).remove();	  
			  
		});		
	});
	
	var allContextCSS = $("#weeks").find('.context-style').removeClass('context-style');
	
	if(allContextCSS.length){
		$.each(allContextCSS, function(){
			
			$(this).removeClass('context-style');
			
		});
	}
	
	//if there is an open #divContextMenu, got to close it so it isn't saved into the xml.
	/*var $openMenus = $('.month').find('#divContextMenu');
	$($openMenus).each(function(i,menu){
		$(menu).remove();
	});*/
		
	wait('start');
	var htmlDataToSave = $("#weeks").html();
	var data={"content":htmlDataToSave,"year":year,"month":month,"theDate":theDate,"company":curCompany,"method":"saveMonth"};
	
	//update the appropriate cell node in the xml
	$.ajax({	
		  url : "classes/calendar.php",
		  type: "POST",
		  data : data,
		  dataType:"json",
	   success: function(respData, textStatus, jqXHR)
	   {	
	   	  wait('end');
		  giveNotice('<span style="color: #009000">Success</span>: Your Updates have been Save.');
		  //giveNotice('<span style="color: #009000">Success</span>: Your Updates have been Save.');
		  //console.log(respData);
		  
	   },
	   error: function (jqXHR, textStatus, errorThrown)
	   {
		   wait('end');
		   giveNotice('<span style="color: #FF0000">Failed</span>: Server Response: "'+errorThrown+'"');
	   }	
  	});
}

function teamsShowAll(){
	
	//only show all if the modal popup is not visible.
	
	if( $('#modal').is(":visible") === false ){	
	
		$(listElements).each(function(i,entry)

		{   //console.log('showall teams fired for ' + $(entry).clone().html());
		//outputs <span id="job_98592">98592</span> WEN #05802 WO 98592 Parsippany, NJ
			$(entry).removeClass('hide');
		});

		var TeamChkBoxes = $("#teamSelection").find("input");
		$(TeamChkBoxes).each(function(x,box)
		{

				$(box).prop("checked", true);

		});
	} else {
		return false;
	}
}
	
function teamsHideAll(){
	//this.preventDefault();
	//alert('hide called');
	$(listElements).each(function(x,listItem)
	{  //console.log('Hide all fired');
		if( $(listItem).parent().hasClass('edit') ){			
			$(listItem).addClass('hide');
		}
	});	
	
	//alert ("teams hide all has been called");
	var TeamChkBoxes = $("#teamSelection").find("input");
	//var lengthOf = TeamChkBoxes; 
	//alert("length is " +lengthOf);
	$(TeamChkBoxes).each(function(d,cBox)
	{
		//if($(cBox).is("checked")){
			//alert("trying to uncheck a box");
			$(cBox).prop('checked', false);
		//}
	});
}
function openModal(modalContent){		
		$('#modalWindow').html(modalContent);
		$('#modalWindow').removeClass('.hide');		
		$('#modalWindow').children('#modal')
		
}
function closeModal(){
	$('#modalWindow').find('.context-style').removeClass('context-style');
	$('#modalWindow').html('');
	$('#modalWindow').html('<div id="modal" class="modal"><button class="smBtn" onclick="closeModal(this)">Close</button></div>');   
	$('#modalWindow').addClass('.hide');
}
	

	
//the pencil icon button click = clickedObj
function modalOpen(clickedObj) {
	
	//console.log("modal Open Called");
	
	hideSearchResult();	
		
	//if there is an opened contextMenu popup, remove it first.
	if( $('#divContextMenu').is(":visible") ){
		$('#divContextMenu').remove();
		$(document).find('.context-style').removeClass('context-style');
	} 
	
	
	//obj clicked is the "edit" button within the date cell user wants to edit
	//if there is any modal content, remove it
	//$("#modal").html('');
	//get user-requested content for the new modal

	
	//to prevent duplication after closing the modal, 
	//must know NOT ONLY the modalId of the date cell
	//but also the month and year of the cell.
	var $m = $(clickedObj).closest(".month");
	modalContent = $(clickedObj).parents('.date').children('ul');	
	var numerMonth = $($m).attr("ordinal");
	//console.log("Ordinal as a month is "+ numerMonth);
	var numerYr = $($m).attr("yr");
	$(modalContent).attr('ordinal',numerMonth);
	$(modalContent).attr('yr',numerYr);
	var numerDate = $(modalContent).attr('modalid').replace('d','');
	//will result in, ex: <ul modalid="d3" ordinal="5" yr="2017">
	//in the modal div, for a date of May 3rd, 2017 from the calendar.
		
	//display the date in the modal popup.
	var cellDate = '<span class="cursive" style="float:right;font-size:17px;color:#8AC72D">' + numerMonth +'/'+ numerDate + '/' + numerYr + '</span>';
	    
	    

	
     $( modalContent ).clone(true, true).appendTo( "#modal" );	
	$("#modal").prepend(cellDate);
	
	$('.blocker').removeClass('hide');
	$('.blocker').css('opacity',0).animate({opacity: 1}, 10);	
	//required to run add listeners again to preserve the onclick editable events for lists
	addListenersToDom("false");
	
}

function modalClose(clickedObj)
{
	//obj clicked is a button withino the open #modal element
	//model el's content child has attr of 'modalid'.
	//that unique modalid is the same as the class name of the
	//original content wrapper.  so val = attr('modalid'); 
	//so original content wrapper in the DOM can be located as: '$(getElementsByClassName(val)).html()';
	
	//if contents of ul with attr modalid = (ex: 'd8') 
	//does not equal contents of the modal (i.e., modal contents edited by user),
	//then modalSave is called before close the modal window.
	
	
	$('#modal').find('li.context-style').first().removeClass('context-style');
	
	
	//don't allow a close of the modal if a context menu is open.
	if($("#divContextMenu").is(':visible')){
	  	 alert("Please FIRST close the Options Menu Popup, THEN close this Window.");
		return;
	
	   } else {	
	
	var modalUL = $("#modal").children("ul.edit");
		//var modalContent = $("#modal").children('ul').html();	//ul with li contents edited by user.
	//modalContent = $(modalUL).children('li').clone( true, true );
	//modalContent = $(modalUL).children('li').clone( true, true );
	
		  /* if(modalContent){
			   console.log("modalContent is defined as "+modalContent);
		   }*/
	
	// BUG!!!!!  The modalid is the same value for all matching dates of the calendar:
	// EX: April 2 and Dec 2 BOTH HAVE modalid=2.
	// This causes the modal window when closed, to duplicate changes from the UL of the modal window
	// Across all dates of the calendar that match modalid's.  12 x.
	
	//TODO: Fix this bug by grabbing also the month as id=mo, attribute ordinal= [number of the month], and (when there are
	// multiple years in a calendar, to avoid duplication on the date and month), id=yr, ordinal [number of the year]
	
	var objMonth = $(modalUL).attr('ordinal');
	var objYear = $(modalUL).attr('yr');
	
	
	// the dom object to save changes to in the calendar.
	var saveTarget;
	var modalId = $(modalUL).attr('modalid'); //used to locate the original UL in the DOM
	//$(".month").each( function(){
		
		    //find each div.month.  Compare attribute values for ordinal and yr, compare to same values in the 
		 //  	if($(this).attr('yr') == objYear && $(this).attr('ordinal') == objMonth){
				//console.log('This Month\'s Ordinal: '+ $(this).attr('ordinal'));
				//console.log('This Month\'s yr: '+ $(this).attr('yr'));
				//console.log("objYear is " + objYear);
		    		//console.log("objMonth is " + objMonth);
				// correct month to update contents.  Find the correct date of this month:				
			//	saveTarget = $(this).find("ul[modalid='" + modalId + "']");
				//$("#modal").find('li').clone( true, true ).appendTo(saveTarget);
		   		//$(modalContent).html() = ""; //clear out the old html in the calendar cell.
		   
		   // modalUL is: var modalUL = $("#modal").children("ul.edit");;
		   // modalContent is the UL in the calendar of the clicked list that opened popup editor.
		   // that is: modalContent = $(clickedObj).parents('.date').children('ul');
		   
		   		//modalContent is the Calendar's UL DOM object, that was defined when modalOpen was last called.
		   
		   
		   
	
		   
		   
		   
		   
		   		$( modalContent ).replaceWith( $( modalUL ).clone(true, true) );
		          addListenersToDom("true");
		   	   
		   
		   		// add listeners afresh to the calendar target UL, modalContent:
		   		$(modalUL).siblings('.cursive').remove();
		   		$(modalUL).remove();
		  
				modalSource = '';
				$('.blocker').addClass('hide');
		   		
		   
		   /*
		   var overlay = '<div class="blocker" style="display:none; width:100%; position:absolute; z-index:99999"> U P D A T I N G . . .</div>';
		   	
*/
		   		
		   
				/*
		   		setTimeout(
						function(){
							addListenersToDom("false");							
							//$("body").append(overlay);
							giveNotice("Please Wait for Calendar UPDATE...");							
						}, 3500);
						*/
		   		//$("body").find(".blocker").remove();
				//modalSave(saveTarget, modalSource);
				//saveTarget is a .month div, where it contains attr values 'yr' & 'ordinal'
				//that match the exact DOM element with cell date (attr->modalid) value == to 
				//the original date that was the html source for the popup modal.
				
				//used to see if the popup model html (modalSource) is identical to the
				//original source, to determine if the modalSource needs to be written to the original source
				//upon closing the modal.
				
		
			//}
		    
		   // });
	//var objUl = $('div.month').attr('ordinal')$('.edit[modalid="'+modalId+'"]');
	
	
	 
	//console.log("Modal ID is " + modalId);
	//$('.edit[modalid="'+modalId+'"]');
	//console.log("modalId is "+modalId);
	
	//modalSource is html dom contents in the calendar
	//modalContent is the html dom contents in the modal popup
	//compare to see if they are different; save modal content to the source if true.
	
		   
	// Forego comparison.  The actions in the modal or non-action can be updated to the calendar cell.
	//if( modalContent !== modalSource )
	//{
		// compared contents are different; let's save new content to the calendar
				
		//addListenersToDom();
		// alert('Updates Saved');
	//}

	
	   }// else
}

function modalSave(destination, source)
{
	//clone it back to the source with event listeners intact.
	//need to add in your contextmenu event handler.  Clone could not 
	//copy those for some reason:
	
	//if the modal source contains contextMenu html, remove it first.
	/*if( $(source).find('#divContextMenu') ){
		$(source).find('#divContextMenu').remove();
	}*/
	
	//now save the modal content to the original date cell of the calendar
	//console.log("Source: " + source);
	destination.html(source);
	//console.log("The HTML to copy to the cal is: "+ $(source).html() );
	//addListenersToDom();
	
	
		 
	// alert("editboxes is: " + editboxes);
	 
	 $.each(destination, function(i, elem){		 
		 destination[i].addEventListener('click', startEdit, true);
		 //var dateBox = editboxes[i].parentNode; //parent of the edit box.
		//evt.stopImmediatePropagation();stopPropagation()
		
		bindListeners4EachList(destination);
		//listsIntoObjects(destination,i);
	 });	 
	 			
}


function hideSearchResult(){
	if( $('#srchResult').is(':visible') ) {
		$( '#srchResult').empty();
		$("#search").val('');
		$('#srchResult').addClass('hide');	
		
	}
}

//back and forward through the month navigation
function displayNewMonth(action)
{
	wait('start');
	var allMonths = $(".month");
	
	if(action==='next') //display next month
	{
		// parseInt(month); //global cur mo as string (e.g. "7" for July). Convert to int. for math.		
		// Check 1st if there is HTML available for job listings for the next month being requested
		// if ! class="month" ordinal="12" (if the DOM does not find that attr == to month+1 (12), 
		// then giveNotice that there is no job scheduled in that month yet.
		// +1 for next, -1 for previous month being requested.		
				
		if( parseInt(month) < 12 ){ //stay on the same year.
			var nextMonth =  String(parseInt(month) + 1);
			//console.log('the month is not dec.');
			var validMonth = monthHTMLexists( allMonths, nextMonth, year ); //does next mo exist in the DOM?
					//console.log(validMonth,allMonths);
		
		   if( validMonth !== 'ok'){
			   giveNotice('<span style="color: #FF0000">There is No Job Data Stored for that Month.</span>');

			   wait('end'); //hide the animated processing graphic
			   return; //get outta town
		   }
			
			$(allMonths).each(function(i,mo){
				/*
				if($(mo).attr("ordinal") == month){ //this is the current month we want to hide.
					$(mo).addClass("hide");
				}*/
				 /* hide all instead.. except the one we are nav to */
				$(mo).addClass("hide");   
				
				if($(mo).attr("ordinal") == nextMonth){
					$(mo).removeClass("hide");
					month = String(nextMonth); //set the month var to the new month displayed.
					curMonthCounter = month;
					 //$("#mo").html(monthName);
					$("#mo").html(monthName[month]);
					$("#mo").attr("ordinal",month);
					$("#mo").removeClass("hide");					
				} 
					
			});
			wait('end'); //hide the animated processing graphic
			   return; //get outta town
			//loadCalendar(curCompany, month, year);
		} 
		else //the month is dec (12)
		{ //need to roll over to next yr
				
			
			//console.log('the month is dec and the year is ' + year);	
			//all .month, ordinal month to search DOM, yr attrib val to seach DOM		
			var validMonth = monthHTMLexists( allMonths, "1", String( parseInt(year) +1 ) ); //does next mo exist in the DOM for on the year requested?
					
		   	if( validMonth !== 'ok'){
				//there is not a DOM element for that year/mo combination.			   	
			   	giveNotice('<span style="color: #FF0000">There is No Job Data Stored for that Month.</span>');
			  	wait('end'); //hide the animated processing graphic
			   	return; //get outta town
		   	} else {
				nextMonth = "1";
				year = String( parseInt(year) +1 );				
				$(allMonths).each(function(i,mo){				
				    /*if($(mo).attr("ordinal") == "12"){ //this is the current month we want to hide.
					    $(mo).addClass("hide");
				    }*/
				     /* hide all instead.. except the one we are nav to */
				    $(mo).addClass("hide");   
				    
				    if($(mo).attr("ordinal") == nextMonth){
					    $(mo).removeClass("hide");
					    month = String(nextMonth); //set the month var to the new month displayed.
					    curMonthCounter = month;
						//$("#mo").html(monthName);
					    $("#mo").html(monthName[month]);
					    $("#mo").attr("ordinal",month);
					    $("#mo").removeClass("hide");	
					   $("#yr").html(year);
					   $("#yr").attr('ordinal', year);		 			 
					   //$(mo).attr('yr', year);						
				    } 					
			}); 			   						
			    wait('end');
			    return;
			}// else is a valid month/yr combo in the DOM
		}		
	} //if nav is "next" month
	else //action is to display the 'prev' month
	{		
		if( parseInt(month) > 1 ){
			//no need to roll back the year
			var nextMonth = parseInt(month) - 1;
			var validMonth = monthHTMLexists( allMonths, nextMonth, year ); 
			//does next mo exist in the DOM for on the year requested?					
		   	if( validMonth !== 'ok'){
				//there is not a DOM element for that year/mo combination.
				 
			     giveNotice('<span style="color: #FF0000">There is No Job Data Stored for that Month.</span>');
			     wait('end'); //hide the animated processing graphic
			     return; //get outta town
		   	} else {
			    
			    $(allMonths).each(function(i,mo){				
				    /*if($(mo).attr("ordinal") == month){ //this is the current month we want to hide.
					    $(mo).addClass("hide");
				    }*/
				     /* hide all instead.. except the one we are nav to */
				    $(mo).addClass("hide");   				
				    if($(mo).attr("ordinal") == String(nextMonth) && $(mo).attr("yr") == String(year)){
					    $(mo).removeClass("hide");					
					    curMonthCounter = month;
						//$("#mo").html(monthName);
					    $("#mo").html(monthName[nextMonth]);
					    $("#mo").attr("ordinal",nextMonth);
					    $("#mo").removeClass("hide");
				    }
			    });
			    month = String(nextMonth); //set the month var to the new month displayed.
			}//end else is validMonth
		} else if(parseInt(month) == 1) { //need to roll back to prev yr				
			
			//console.log('the month is dec and the year is ' + year);	
			//all .month, ordinal month to search DOM, yr attrib val to seach DOM	
			var prevYr = parseInt(year) -1;	
			//console.log("prevYr is " + prevYr);
			var validMonth = monthHTMLexists( allMonths, "12", prevYr ); 
			//does next mo exist in the DOM for the prior year?					
		   	if( validMonth !== 'ok'){
				//there is not a DOM element for that year/mo combination.				 
			     giveNotice('<span style="color: #FF0000">There is No Job Data Stored for that Month.</span>');
			     wait('end'); //hide the animated processing graphic
			     return; //get outta town
		   	} else {			
			
			var nextMonth = "12";
				year = String( parseInt(year) -1 );				
				$(allMonths).each(function(i,mo){				
				    /*if($(mo).attr("ordinal") == "12"){ //this is the current month we want to hide.
					    $(mo).addClass("hide");
				    }*/
				     /* hide all instead.. except the one we are nav to */
				    $(mo).addClass("hide");  
				});
				 $(allMonths).each(function(i,mo){	   
				    if($(mo).attr("yr") == prevYr && $(mo).attr("ordinal") == nextMonth ){
					    $(mo).removeClass("hide");
					    month = String(nextMonth); //set the month var to the new month displayed.
					    curMonthCounter = month;
						//$("#mo").html(monthName);
					    $("#mo").html(monthName[month]);
					   // $("#mo").attr("ordinal",month);
					   // $("#mo").removeClass("hide");	
					    $("#yr").html(year);
					    //$("#yr").attr('ordinal', year);		 			 
					   //$(mo).attr('yr', year);						
				    } 					
			}); 				
			
		}//else validmonth ok
		
	}//else try roll back one year from month 1 to 12
	wait('end');
	var func = addListenersToDom("false");
	window.setTimeout(func, 100);
	
}//else prev
}

// verify DOM contains HTML of job listings for a user navigated month
// allMonths is all DOM els of class "month"
// action is "next" or "prev" nav request 
//the month to check
function monthHTMLexists(allMonths,checkMonth,yr){
	
	 var result = false;	 
	// TODO: Check allMonths is not null, empty
	
	checkMonth = String(checkMonth);	
	 
	//console.log('checkMonth is: ' + checkMonth + ' and yr is: ' + yr );
	$(allMonths).each(function(i,el){
		//get the value from the element's "ordinal" attribute that matches the
		//requested month to navigate to.  If it doesn't exist in DOM, then return false;	
		//console.log("EACH has fired!");
		if( $(el).attr("yr") === String(yr) ){
			if(  $(el).attr("ordinal")  === String(checkMonth) ){			
			  //e.g. el looks like: <div class="month" ordinal="10" yr="2016">
			  //we found a DOM el with the requested month and having the same (current) year.
			  //console.log($(el).attr("ordinal"));
			  result = 'ok';	
			}
		} 
	});
	
	return result; //if undefined, there is not a DOM for that month+yr
		
}


	
function cleanCalendarLayout(){
	 $( "#pageTitle" ).clone().appendTo('#print');	
	 $( "span#date" ).clone().appendTo('#print');			
	 $( "span#curTime" ).clone().appendTo('#print');
	 $( "#icons" ).clone().appendTo('#print');
	 $( "span#mo" ).clone().appendTo('#print');
	 $( "span#yr" ).clone().appendTo('#print');
	 $( "#headerDays" ).clone().appendTo('#print');
	 $( ".month" ).clone().appendTo('#print');
}
function printWindow(){
	$( "#print" ).removeClass( "hide" );
	 var printWindow = window.open('', '_blank', 'scrollbars=yes,resizable=yes,top=20,left=5,height=900,width=1200');
	 printWindow.document.write('<html><head><title>Print Calendar</title><link href="styles/print.css" media="all" rel="stylesheet" />');
	 
	 var $editULs = $("#print").find(".edit");
		
	//hide empty ul.edit -- this works, but the calendar does not really save any space
	//with the current layout used for printing.
	/*	$( $editULs ).each(function(i,ulEl){
			
			if( $(ulEl).find('li').length < 1){
				$(ulEl).parent('.date').addClass('hide');				
			}
			
		});
	*/
	
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
	 	 
	 printWindow.document.write('</head><body id="printBody">');
	 printWindow.document.write( $( "#print" ).html() );
	 printWindow.document.write('</body></html>');
	 printWindow.document.close();
	 printWindow.print(); 
	 $( "#print" ).html('');
	 $( "#print" ).addClass( "hide" );
	
}		
		
		
		// you could use set() which builds on the set only if it does not already exist.
		//$('#search').on('keyup',function(){
		$('#search').on('input',function(){
		   
			var searchTerm = $(this).val().toLowerCase();
			var results = [];
			var domObjs = [];
			//console.log('on input fired');
		   //require search terms of 3 chars or more
		   if(searchTerm.length > 2){ 
			//console.log('searchTerm len gtr than 2 fired');
		   $('li.lineEntry').each(function(i,list){
			  if(typeof list !== 'undefined'){
				  //console.log('LI is defined in .each');
			   	  var lineStr = $(this).text().toLowerCase().trim();
			  }
			   // -1 returned if searchTerm not found in LI string
			  if(lineStr.indexOf(searchTerm) === -1){
				 
			  }else{	
				
				 // results.push(lineStr);
				  domObjs.push(list); //lineEntry Ojb with matched content			
				  results.push(lineStr);
	
			  }	//else	   
		   }); //each
			
			// output to the view
			
			if(domObjs.length>0){	
				
				$('#srchResult').removeClass('hide');			
				$( '#srchResult').empty(); //clear out displayed results with each on.input		
				
				var br =  document.createElement("br");				
				$(domObjs).each(function(i,res){	
					
					var thedate = $(res).parent('.edit').attr('modalid'); //e.g., d21 for the 21st date of a month.	
				  	
					if(typeof thedate !== 'undefined' && thedate.length > 1){
						thedate = thedate.replace('d', '');
				  		var month = $(res).closest('div.month').attr('ordinal');
						var yr = $(res).closest('div.month').attr('yr');
					  	thedate = month + "/" + thedate + "/" + yr;
					  	//console.log("found "+ searchTerm + " on " + thedate);
						//results[i] = thedate + ': ' + results[i];						
					} // if defined
					
					if( typeof res !== 'undefined'){ //  && $(res).html().indexOf(searchTerm) !== -1 					
						
						//$('#srchResult').append( "<div class='result"+i+"' >Date: " + thedate + ", " + $(res).html() ).append("</div><br/>");
						$("#srchResult").append( "<div style='cursor:pointer' class='result"+i+"'  >Date: " + thedate + ", " + $(res).html() + "</div>");
						
						
						
						$(document.body).find('.result'+i).on('click', function() {
								$(res).addClass('context-style');
   								//modalOpen($(res).closest('.modalImg'));
							modalOpen(res);
						});
						
						
						
						
						/*$('.result'+i).on("click", function(){	
							$(this).addclass('context-style');
							modalOpen($(res));
						});*/
					}
					
					
				});  // each results
				
			/*	// display the current results to the user
				$(domObjs).each(function(i,res){		
					if(typeof res !== 'undefined'){
						$('#srchResult').append( $(res).html() );	
					}
				});
				*/
				
				
				
			}//if domObjs has members
			else {
				hideSearchResult();
			}	
			   
		   }//end if search term > 2
			else {
				$('#srchResult').removeClass('hide');
				$('#srchResult').html( '[ Search Requires 3+ Characters. ]' )
			}
				  			
		}); //on.input
		
		$('#srchResult').parent('form').on('focusout',function(){			
			if( $('.blocker').hasClass('.hide') ){				
				hideSearchResult();				
			}		
		});
		
//'<div id="x"><button onclick="saveNote(this,'+listEl+')">Save</button><br><input type="textarea" id="y" value="" /></div>	
	// obj param references dom 'save button', from the above html.
	function saveNote(obj){
		var r = $(obj).siblings('input#y').val();
		var LI = $(obj).closest('.lineEntry');
	 // $(listEl).attr("contenteditable", "false");
	//	$(this).on('mouseleave', function (){  

		    //$(inputArea).unbind('dblclick');		 
		    var user = $usr.slice(0,3);
		    
		    var notes = ' [<i style="color:#f00">' + user + '</i>]: ' + r;			
		    if( notes.length > 35 ){		
				
				$(obj).parent('#x').replaceWith('<br><span class="admin-note">'+notes+'</span>');
				
			} else {
				
				$(obj).parent('#x').remove();
				
			}			

		  /*  if(notes.length > 9){				    
			    $(listEl).append('<span class="admin-note">'+notes+'</span>');
		    }
		    
			$(listEl).unbind('mouseleave');
			
	   }); 		  
*/



		closeEditing(LI);


	}
		
		//general toggle show hide; param is the target DOM element
		
		function toggleVisibility(target){
			var $t = $(target)
			if( $t.hasClass( 'hide' ) ){
				$t.removeClass( "hide" );
			} else {
				$t.addClass( "hide" );
			}
			
		}
		


//universal confirm / cancel dialog func
function confirmRequest(msg)
{	    
   var r = confirm(msg);
   return r; //returns false if cancel/close or true if ok.	
}


/*
When editing via:


1. Reschedule
2. assignment type (colorize)
3. assignment process/status (icon)
4. Admin-Note (add, edit)
5. Copied



js object pendingUpdates

properties include:


"location":{
	"yr": 
	"mo": 
	"cell": //ordinal value
},

"jobNumber": nmbr,

"changes": { 

	"resched" : {		if not empty...
				"contents":cloned html,
				"target": save to cell
		 	},

	"classes" : { [ 0: "target":domObj, "classNames": 'hide unassigned ', 1: [ "target":domObj, "icon":   ]  },


	"icons" : {  [ 0: "target":domObj, "icon":  , 1: [ "target":domObj, "icon":   ] ] }
	
	"notes" : 
	
	
	"copies" : {}

				
*/



// record to obj 'pendingUpdates' all data changes to the calendar
// called by save()
function updateTransaction(){
	
	//acquire freshest state of calendar data from remote xml.
	loadCalendar(); // this temporarily removes user's updates from the live html.
	
	//apply each pendingUpdates row to its respective cell in live html.
	$.each(pendingUpdates, function(){
		
		
		
		
	}); //pendingUpdates each.
	
	
	
	
}

// 


// called by logout button event
// if pendingUpdates has content, warn user they exit without saving.
function confirmLogout(){
	
	
}





/*	TODO:
//Purpose: allow a logged in user to "chat" toward the mgr/admin occupying the editable calendar
//Check the Message Subsystem for User Msgs:
function CheckMessages() {
	
	_checkMessageCounter++;
   
    var msgPanel = document.getElementById("intercom");
    if (msgPanel)
        msgPanel.innerHTML = (MSG_TIMEOUT - _checkMessageCounter) + "";
    if (_checkMessageCounter >= MSG_TIMEOUT) {
        
	    //see if there are any recent message flat files.
	    
	    
	    
	    
        if (answer) 
        {
            //document.location.href = "login.php";   
            _idleSecondsCounter=0;
        }
        else{
            //window.open('', '_self', ''); window.close();
            document.location.href = "login.php";
        }

    }
 }
	
	*/



/* Possible TODO :: User Backups
/*	NEVER USED
function backUpCal(e){
	
	
		
	e.preventDefault;
	
	var today = new Date();
	var date = (today.getMonth()+1)+'-'+today.getDate() +'-'+today.getFullYear();
	/*
	if
	
	var filename = ;
	
	if($('input name[filenameSuffix]').val() !== '' && typeof $('input name[filenameSuffix]').val() !== 'undefined'){
		filename = date + '_' + $usr + '_' + curCompany.replace(' ', '-') + '_' $('input name[backupfilename]').val();
	} else {
		filename = date + '_' + $usr + '_' + curCompany.replace(' ', '-');
	} 
	*/
	/*
	$("#weeks").find('.context-style').removeClass('context-style');
	//if there is an open #divContextMenu, got to close it so it isn't saved into the xml.
	var $openMenus = $('.month').find('#divContextMenu');
	$($openMenus).each(function(i,menu){
		$(menu).remove();
		
	});
		
	wait('start');
	var htmlDataToSave = $("#weeks").html();
	var data={"content":htmlDataToSave,"company":curCompany};
	
	//update the appropriate cell node in the xml
	$.ajax({	
		  url : "classes/backup.php",
		  type: "POST",
		  data : data,
		  dataType:"json",
	   success: function(respData, textStatus, jqXHR)
	   {	
	   	  wait('end');
		  giveNotice('<span style="color: #009000">Success</span>: Your Updates have been Save.');
		  //giveNotice('<span style="color: #009000">Success</span>: Your Updates have been Save.');
		  //console.log(respData);
		  
	   },
	   error: function (jqXHR, textStatus, errorThrown)
	   {
		   wait('end');
		   giveNotice('<span style="color: #FF0000">Failed</span>: Server Response: "'+errorThrown+'"');
	   }	
  	});
	
	
}	
	
//create backup of cur cal
function backup(){

	var $calHtml = $("#weeks").html();
	//var $usr = $("#username").text();			
	$json = {"html":$calHtml,"company":curCompany,"username":$usr};

	$.ajax({
		url: "classes/backup.php",
		type: "post",
		data: $json,
		dataType: "json",			
		success: function(respData, textStatus, jqXHR){


		},
		error: function(respData, textStatus, er){



		}		    

	 });


} //backup()

*/


/*
function editHistory(action){
	//which action button clicked?
	if(typeof lastEditedCell != 'undefined'){
	    //undo last change
	    if(action=='undo'){
		    if(lastEditedCell.length < 1){
			    giveNotice('<span style="color: #FF0000">Failed</span>: No entries available to delete.');
		    } else {
			  // var index = lastEditedCell.length -1;
			   var undoCell=lastEditedCell.splice(-1,1);			   
			   redo.push({"redoText":undoCell[0][0].innerHTML,"redoHandler":undoCell});
		    	   undoCell[0].html(originalContent);//set the cell's content back to the original.
			   undoCell.slice(0,1);
			   giveNotice('<span style="color: #009000">Success</span>: Most Recent Change has been Reversed.');	
			   addListenersToDom();
		    }
		    // lastcellEdited = $(this);		    
	         // undo all changes; restore orig xml for the month
	    } else if(action=='undoall') {
		    //$("#weeks").html('');
		    //1st show all.  We probably cannot edit hidden elements
		    teamsShowAll();	    
		    
		    var undoAmt = lastEditedCell.length;
		    if( undoAmt > 0)
		    {
			 for(var i = 0; undoAmt > i; i++)
			 {
				var undoCell=lastEditedCell.splice(-1,1);	//remove one from the end of the array		   
				redo.push({"redoText":undoCell[0].innerHTML,"redoHandler":undoCell});
				undoCell[0].html(originalContent);//set the cell's content back to the original.
				undoCell.slice(0,1);
			 }
			  giveNotice('<span style="color: #009000">Success</span>: '+undoAmt+' change(s) have (has) been reversed.');
			  addListenersToDom();
		    }
		    else
		    {
			    giveNotice('<span style="color: #009000">OK</span>: There\'s Nothing to Undo.');
			    
		    }
		    	    
		    
		  
		    
		    
	    //action is 'redo'; redo last undo
	    } else  {
		    //lastEditedCell.html(content); old way when lastEditC was a simple variable, not an array
		    //new way with multiple undo/redo's:
		    if(Object.keys(redo).length<1){
			    giveNotice('<span style="color: #FF0000">Failed</span>: No entries available to undo.');
		    } else {
			    	//lastEditedCell.html(content); old way when lastEditC was a simple variable, not an array
				//new way with multiple undo/redo's:
				var index = Object.keys(redo).length -1; //find the index value of the last item of this obj.
				redoObj=redo.splice(index,1); //remove and assign the last redo item to redoObj before we redo it.
				//var hndlr = redoCell.handler; //the jquery object (edit div).
				redoObj[0].redoHandler[0].html(redoObj[0].redoText); //the old markup being restored to edit UL.
				//add the handler back onto the undo array:
				lastEditedCell.push(redoObj[0].redoHandler[0]);
				//redoCell = ''; //reset it.
				redoObj.slice(0,1);//clean it up
				giveNotice('<span style="color: #009000">Success</span>: Last Undo Request has been Reversed.');
				addListenersToDom();				
		    }
	    }
	 //no history saved
	} else {		
			giveNotice('<span style="color: #FF0000">Failed</span>: There\'s no revisions to update from.');
	}

}
*/
// end form buttons' functions

	




/* Not Used Anymore:

		//getUrlParams
		function getUrlParams(queryString){
			

		  // get query string from url (optional) or window
		 // var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

		  // we'll store the parameters here
		  var urlParams = {};

		  // if query string exists
		  if (queryString) {

		    // stuff after # is not part of query string, so get rid of it
		    queryString = queryString.split('#')[0];

		    // split our query string into its component parts
		    var arr = queryString.split('&');

		    for (var i=0; i<arr.length; i++) {
			 // separate the keys and the values
			 var a = arr[i].split('=');

			 // in case params look like: list[]=thing1&list[]=thing2
			 var paramNum = undefined;
			 var paramName = a[0].replace(/\[\d*\]/, function(v) {
			   paramNum = v.slice(1,-1);
			   return '';
			 });

			 // set parameter value (use 'true' if empty)
			 var paramValue = typeof(a[1])==='undefined' ? true : a[1];

			 // (optional) keep case consistent
			 paramName = paramName.toLowerCase();
			 paramValue = paramValue.toLowerCase();

			 // if parameter name already exists
			 if (urlParams[paramName]) {
			   // convert value to array (if still string)
			   if (typeof urlParams[paramName] === 'string') {
				urlParams[paramName] = [urlParams[paramName]];
			   }
			   // if no array index number specified...
			   if (typeof paramNum === 'undefined') {
				// put the value on the end of the array
				urlParams[paramName].push(paramValue);
			   }
			   // if array index number specified...
			   else {
				// put the value at that index number
				urlParams[paramName][paramNum] = paramValue;
			   }
			 }
			 // if param name doesn't exist yet, set it
			 else {
			   urlParams[paramName] = paramValue;
			 }
		    }
		  }

		  return urlParams;
			
	}//getUrlParams
	
	*/



		//tests whether obj is empty returns true if empty.
function isEmpty(obj) {
   //check if it's an Obj first
   var isObj = obj !== null 
   && typeof obj === 'object' 
   && Object.prototype.toString.call(obj) === '[object Object]';

   if (isObj) {
       for (var o in obj) {
           if (obj.hasOwnProperty(o)) {
               return false;
               break;
           }
       }
       return true;
   } else {
       console.error("isEmpty function only accept an Object");
   }
}
		
function unsetOverDueJob(){
	//alert("UnSet this as overdue");
	//called when user assigns a job "overdue";
	$(editableLI).removeClass("due");
	$(editableLI).removeClass("t21"); //this class is used in checkbox controls to hide/show entry
	$(editableLI).addClass('context-style');
	//remove this job from the global obj 'overdueJobs'
	//1. get the job # of current edited job in dom
		var jobNmbr = $(editableLI).children('span').first().text(); //the job number parent span is always the first one in the LI.
	//2. delete that property
		delete overdueJobs[jobNmbr]; 
	//3. Delete the job from the overdue display in the DOM.
		$("#"+jobNmbr).remove();	
	
}

//display overdue to the user interface.
//generally called only when page loads
function displayOverDueJobs(){
	
	let wks = document.body.querySelector('div#weeks');
	let $due = wks.querySelectorAll('li.due');
	$.each($due, function(i,v){
		//alert("Found a due LIST: "+ v);
		editableLI = v;
		setOverDueJob();
		
;	});
}

function onloadSetOverdueDisplay(){
	
	var $overdues = $("#weeks").find("li.due");
	
	$.each($overdues, function(i, d){
		//  alert("overdue found");
		  //list this in the view of overdue jobs.
		 var jobNmbr = $(this).children('span').first().text(); //the job number parent span is always the first one in the LI.
		//alert("Job# is " + jobNmbr);
		//2. get the date of the edited job's UL wrapper's modalid.
			var date = $(this).parent('ul').attr('modalid').substr(1);
		//3. trim off the first char ('d') from the modalid value, leaving just the numeral.
			//date = date.substr(1);
			var mo = $(d).closest( ".month" ).attr('ordinal');
			var yr = $(d).closest( ".month" ).attr('yr');
			date = '<span style="color: red">'+ mo + '/' + date + '/' + yr+'</span>';
		//get the job desc text and truncate it to the first 30 chars.
			var desc = $(this).text();
			desc = desc.slice(0,30);
		//concat into a line of text to save as a property of overdueJobs obj.
			var info = '<div class="ovrDue" id="'+jobNmbr+'">' + date +': '+ desc +'...</div>';
		if( overdueJobs.hasOwnProperty(jobNmbr) === false ){
			overdueJobs[jobNmbr] = info;
		}

		});
	


	if( isEmpty(overdueJobs) === false ){

		//first erase all the content of the overdue job list in the view:
		$('#OverDueJobsList').html('');

		//write all the updated overdue jobs to the view:
		//by iterating through the overdueJobs properties:
		Object.keys(overdueJobs).forEach(function(key) {
			$('#OverDueJobsList').append(overdueJobs[key]);
		});		
		$('#OverDueJobsList').prepend('<p><span class="due" style="padding: 3px 8px !important; font-size: 18px">Overdue Jobs</span></p>');
	}//if			
				
	
}


