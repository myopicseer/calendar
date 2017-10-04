<?php


/*


$html = '';


//==============================================================
//==============================================================
//==============================================================

include("../mpdf.php");
$mpdf=new mPDF('c'); 

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;

//==============================================================
//==============================================================
//==============================================================

*/











include 'lib/mpdf60/mpdf.php';
ob_start();  // start output buffering
include 'index.php';
$content = ob_get_clean(); // get content of the buffer and clean the buffer
$mpdf = new mPDF('c'); 
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($content);
$mpdf->Output('result.pdf','F'); // output as inline content
?>