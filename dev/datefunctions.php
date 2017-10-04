<?php
//time functions playground

//first day of the current month

//for date January 1 2017
$date = '1/1/2017';

echo "First Day of Month for 1/1/2017: ";

$firstDay = date('N', strtotime($date));//returns numeric representation of day of week for given date.
//where Monday is first day, 1.
 
echo $firstDay . "<br>";

//'w' Numeric representation of the day of the week	0 (for Sunday) through 6 (for Saturday)
$firstDay = date('w', strtotime($date));
echo 'Day of the week for Jan 1 2017 with sunday being 0: '.$firstDay. '<br>';
?>






