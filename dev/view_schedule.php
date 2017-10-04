<?php
/**
 *
 * Display the Admin Reservations Schedule for WIP Cal Usage with Add, Update, Delete Functionality.
 * Chris Nichols - July 7, 2017. Ver. 1
 *
 **/


header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past



			if(!isset($_SESSION)){					
					include('classes/session.php');
				} else {
					header('Location: login.php');
				}
			

	
$s=new Session;

$PDOcx = $s->dbOpen( 'custo299_wipcalendar' );
 
$st = $PDOcx->prepare('SELECT `id`,`username`,`email` FROM `users` WHERE `company` = :co AND `role` = \'admin\'');
$st->bindParam(':co', $company, PDO::PARAM_STR);
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
$userSelect='<option class="red" value="false" selected="selected">-Select Username-</option>';
if($r){	
	//list of all admin users for company y
	foreach($r as $u)
	$userSelect .= '<option value='.$r['id'].'>'.$r['username'].'</option>';	
}

$today = date( 'Y-m-d', strtotime('today') );
$tomorrow = date( 'Y-m-d', strtotime('tomorrow'));
$tomorrowplus1 = date( 'Y-m-d', strtotime('today + 2 days'));
$tomorrowplus2 = date( 'Y-m-d', strtotime('today + 3 days'));


function auto_version( $file )
{
  if(strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file))
    return $file;

  $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
  return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}
/* USE: <link rel="stylesheet" href="<?php echo auto_version('/css/base.css'); ?>" type="text/css" />*/


?>

<!DOCTYPE html> 
<head>

	<link rel="stylesheet" href="<?php echo auto_version('styles/schedule.css'); ?>" type="text/css" media="screen" />
	   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	
</head>
<body>

<form name="day" method="post" action="" >
	
	
	<label for="username">User Name</label><br/>
	<select id="username" name="username">
	<?php echo $userSelect ?>
	</select>
	
	

	<label for="date">Show Schedule: </label><br/>
	<select id="sel" name="date" >
	
		<option name="sel" class="red" value="0" selected="selected">-Select Date-</option>
		<option name="today" value="<?php echo $today ?>">
			Today
		</option>
		<option name="tomorrow" value="<?php echo $tomorrow ?>">
			Tomorrow: <?php echo date('D, M jS', strtotime('tomorrow')) ?>
		</option>
		<option name="2days" value="<?php echo $tomorrowplus1 ?>">
			<?php echo date('D, M jS', strtotime('today + 2 days')) ?>
		</option>
		<option name="3days" value="<?php echo $tomorrowplus2 ?>">
			<?php echo date('D, M jS', strtotime('today + 3 days')) ?>
		</option>
	
	</select>

	<label for="company">Company</label><br/>
	<select id="sel2" name="company" >
		<option name="sel2" class="red" value="none" selected="selected">-Select Company-</option>
		<option name="customsigncenter" value="0">Custom Sign Center</option>
		<option name="boyer" value="1">Boyer</option>
		<option name="marion" value="2">Marion</option>
		<option name="outdoor" value="3">Outdoor</option>
		<option name="jg" value="4">JG</option>	
	</select>
	
	<label for="time">Company</label><br/>
	<select id="time" name="time" >
		<option name="800" value="800" selected="selected">8:00-8:30am</option>
		<option name="830" value="830">8:30-9:00am</option>
		<option name="900" value="900">9:00-9:30am</option>
		<option name="930" value="930">9:30-10:00am</option>
		<option name="1000" value="1000">10:00-10:30am</option>
		<option name="1030" value="1030">10:30-11:00am</option>
		<option name="1100" value="1100">11:00-11:30am</option>
		<option name="1130" value="1130">11:30-Noon</option>
		<option name="1200" value="1200">Noon-12:30pm</option>
		<option name="1230" value="1230">12:30-1:00pm</option>
		<option name="100" value="100">1:00-1:30pm</option>
		<option name="130" value="130">1:30-2:00pm</option>
		<option name="200" value="200">2:00-2:30pm</option>
		<option name="230" value="230">2:30-3:00pm</option>
		<option name="300" value="300">3:00-3:30pm</option>
		<option name="330" value="330">3:30-4:00pm</option>
		<option name="400" value="400">4:00-4:30pm</option>
		<option name="430" value="430">4:30-5:00pm</option>		
	</select><br/><br/>
	
	<button name="submit" type="submit" id="submitSchedRequest" class="smBtn" >Submit</button>

</form>



<div id="sched">

	<div id="<?php echo $today ?>" class="week">
	</div>
	
	<div id="<?php echo $tomorrow ?>" class="week">
	</div>
	
	<div id="<?php echo $tomorrowplus1 ?>" class="week">
	</div>


	<div id="<?php echo $tomorrowplus2 ?>" class="week">
	</div>


</div>



<?php 
	
	
			   
?>

<script type="text/javascript">
	
	
	//globals
	
	var times = [830,9,930,10,1030,11,1120,12,1230,1,130,2,230,3,330,4,430,5];
	var htmlRow = {
					'morning':{
						'time':
							[
								{'t':'830'},
								{'t':'9'},
								{'t':'930'},
								{'t':'10'},
								{'t':'1030'},
								{'t':'11'},
								{'t':'1130'},
								{'t':'12'}
							]
					},

					"afternoon":{
						'time':
							[
								{'t':'1230'},
								{'t':'1'},
								{'t':'130'},
								{'t':'2}'},
								{'t':'230'},
								{'t':'3'},
								{'t':'330'},
								{'t':'4'}
							]
					},

					"lateafternoon":{
						'time':
							[
								{'t':'430'},
								{'t':'5'}
							]
					}
			    };
	
	
	
	//toggle betw military and standard time.
	function toggleTimeFormat(){
		
		
		
	}
	
	//target is the html wrapper's ID where html children will insert	
	function buildHTMLsched(target){
		
		if(typeof htmlRow.target !== 'undefined'){
			for($1=0; htmlRow.target.time.length>$i; $i++){

				$section = document.getElementById(target);			
				$div = $document.createElement('div');
				$div.addClass('slot');
				$div.createAttribute('id', 't'+this);	
				$section.appendChild($div);

			}
		}
		
	}
	
	
	//(user)id, (user)name, company, date, time ('8:30'), method ('new'), desc
	function ajaxSubmit(){		
		
		//verify form data		
		if(  value === '0' || value2 === 'none'){
			
			alert('Company and Day are BOTH required.  Try again.');
			return;
			
		}
		
		var data = JSON.stringify({'date':value,'company':value2,'method':'getSchedule','time':value3,'id':'1','name':'Chris','desc':'testing'});
		var req = new XMLHttpRequest();

		//req.addEventListener("progress", updateProgress);
		//req.addEventListener("load", transferComplete);
		req.addEventListener("error", transferFailed);
		//req.addEventListener("abort", transferCanceled);

		
		req.onreadystatechange = function() {
		    if (req.readyState == XMLHttpRequest.DONE) {		   
			   
			    $.each(req, function(){
					 
					 makeHtmlSched(req);
					 
				}); 			    
		    }
		}
		
		req.open('POST','classes/Reservations.php');
		req.send(data);
		
	}
	
	
	function transferFailed(evt) {
		
		userMsg("An error occurred retrieving data.");
		
	}

	
	function userMsg(msg){
		
		var display = document.getElementById('msg');
		
		display.textContent(msg);
		display.classList.remove('hide');
		
		setTimeOut(function(){		
		
			display.classList.add('hide');
		
		}, 3600);		
		
	}//userMsg
	
	function makeHtmlSched(sched){
		
		
		if(sched){
		
		
		
		
		var keyname = sched.keys();
		var dateHtmlArray = [];
		/*sched.id_company; sched.schedule; sched.date; sched.id;
		
		sched.schedule looks like:
		
		a:18:{
		i:800;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:3:"830";}
		i:830;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:3:"900";}
		i:900;a:3:{s:4:"user";a:2:{s:2:"id";s:2:"22";s:4:"name";s:3:"Bob";}s:4:"desc";s:21:"Permit Work on Wendys";s:7:"endTime";s:3:"930";}
		i:930;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:4:"1000";}
		i:1000;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:4:"1030";}
		i:1030;a:3:{s:4:"user";a:2:{s:2:"id";s:2:"22";s:4:"name";s:3:"Bob";}s:4:"desc";s:22:"Permit Work on Wendy's";s:7:"endTime";s:4:"1100";}
		i:1100;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:4:"1130";}
		i:1130;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:4:"1200";}
		i:1200;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:4:"1230";}i:1230;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:3:"100";}i:100;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:3:"130";}i:130;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:3:"200";}i:200;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:3:"230";}i:230;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:3:"300";}i:300;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:3:"330";}i:330;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:3:"400";}i:400;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:3:"430";}i:430;a:3:{s:4:"user";a:2:{s:2:"id";s:0:"";s:4:"name";s:0:"";}s:4:"desc";s:0:"";s:7:"endTime";s:3:"500";}}
		
		So sched.schedule.800.endTime = (e.g., 830);
		*/
		var rowID ='';
		//TODO: just append each directly to the DOM....
		switch( parseInt(sched['endTime']) ) {
		    case  parseInt(sched['endTime']) < 1130 && parseInt(sched['endTime']) >= 830 :
			   $rowID = $("#sched1");
			   break;
		    case parseInt(sched['endTime']) < 330 && parseInt(sched['endTime']) > 1200 :
			   $rowID = $("#sched2");
			   break;
		    default:
			   $rowID = $("#sched3");
		}
		$rowID.append('<div data-time="'+keyname[0]+'" class="timeblock"><div class="time">'+keyname[0]+'</div>'+sched['name']+': '+sched['desc']+'</div>');
		} else {
			
			//make an empty date time html structure since there is no sched from db
			console.log('there was no sched from the database.')
			var $row;
			var times = [
				'800','830','900','930','1000','1030','1100','1130','1200','1230','100','130','200','230','300','330','400','430'			
			];		
			
			$.each(times, function(){
				
				if( 1130 >= parseInt(this) >= 800 ){
				   	//morning
					$row = $("sched1");
				} else if( 300 >= parseInt(this) > 1130 ){
					//noon
					$row = $("sched2");
				} else {
					//late noon
					$row = $("sched3");
				}					
				$($row).append('<div data-time="'+this+'" class="timeblock"><div class="time">'+this+'</div>: </div>');
				
			});
			
		}
		
	}

	
</script>
<div id="msg" class="hide"></div>
<div id="sched1" class="hide">
	
	<div class="timeRow" id="morning">
		<h2>MORNING</h2>
			
	</div>
	
	<div class="timeRow" id="afternoon">
		<h2>AFTERNOON</h2>
	</div>
	
	<div class="timeRow" id="lateafternoon">
		<h2>LATE AFTERNOON</h2>
	</div>

</div>
<div id="sched2" class="hide">
	
	<div class="timeRow" id="morning2">
		<h2>MORNING</h2>		
	</div>
	
	<div class="timeRow" id="afternoon2">
		<h2>AFTERNOON</h2>
	</div>
	
	<div class="timeRow" id="lateafternoon2">
		<h2>LATE AFTERNOON</h2>
	</div>

</div>
<div id="sched3" class="hide">
	
	<div class="timeRow" id="morning3">
		<h2>MORNING</h2>		
	</div>
	
	<div class="timeRow" id="afternoon3">
		<h2>AFTERNOON</h2>
	</div>
	
	<div class="timeRow" id="lateafternoon3">
		<h2>LATE AFTERNOON</h2>
	</div>

</div>

<script type="text/javascript">
	
	//document scope vars	
	var elem, elem2, elem3, value, value2, value3, time, sel2, sel;
	
	
	(function(){
		
		// event listeners to assign selected options from the form to a variable
		sel = document.getElementById('sel');
		sel2 = document.getElementById('sel2');
		time = document.getElementById('time');
		
		sel.onchange = function() {
    			elem = (typeof this.selectedIndex === "undefined" ? window.event.srcElement : this);
    			value = elem.value || elem.options[elem.selectedIndex].value;
		}
		
		sel2.onchange = function() {
    			elem2 = (typeof this.selectedIndex === "undefined" ? window.event.srcElement : this);
    			value2 = elem2.value || elem2.options[elem2.selectedIndex].value;
		}
		
		time.onchange = function() {
    			elem3 = (typeof this.selectedIndex === "undefined" ? window.event.srcElement : this);
    			value3 = elem.value || elem.options[elem.selectedIndex].value;
		}
		
	 	document.querySelector("#submitSchedRequest").addEventListener("click", function(event)  {			
			event.preventDefault();
			ajaxSubmit();
		});
		
	 	var target = ['morning','afternoon','lateafternoon'];
		
		// build html for time slots
		/*for($i=0;target.length>$i;$i++){
			buildHTMLsched(this);
		}
		
		/* Load some calendar on startup: */
		var d=new Date;
		var today =d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
		var data = JSON.stringify({'date':today,'method':'loadSched'});
		var req = new XMLHttpRequest();

		//req.addEventListener("progress", updateProgress);
		//req.addEventListener("load", transferComplete);
		req.addEventListener("error", transferFailed);
		//req.addEventListener("abort", transferCanceled);
		req.onreadystatechange = function() {
		    if (req.readyState == XMLHttpRequest.DONE) {		   
			   
			    if(req.length){
				    $.each(req, function(){

						 makeHtmlSched(req);

					});	 			    
			    } else {
				    //just make an empty structure of dates and times:
				    makeHtmlSched(null);
				    
			    }
			}
		}
		
		req.open('POST','classes/Reservations.php');
		req.send(data);
		
		
		
	 
	 })();
	
</script>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw=" crossorigin="anonymous"></script>
	</body>
	</html>




