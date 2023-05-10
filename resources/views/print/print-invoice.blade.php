<?php
/**
 * Created by PhpStorm.
 * User: Harshadeva
 * Date: 7/31/2019
 * Time: 8:24 PM
 */
//call the FPDF library
require( base_path().'/public/pdf/fpdf.php');

$pdf = new FPDF();

//A4 size : 210x297mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm
$leftMargin = 0;
$rightMargin = 0;
$pageWidth = 50.8;
$width= $pageWidth-$rightMargin-$leftMargin;
$height = 4;
$pageHeight = 65;
$aditionalHeight = 0;
$separatorHeight = 5;

class Dash extends FPDF{
    function printDash($w,$h){
        for($i=0;$i< $w-2 ;$i = $i+2 ){
            $this->Cell(2,$h,'-','0',0,'L');
        }
        $this->Cell(2,$h,'-','',1,'L');
    }
}

$actualHeight = $pageHeight+$aditionalHeight+$separatorHeight;

$pdf = new Dash('P','mm',array($pageWidth,$actualHeight));
$pdf->SetMargins($leftMargin, 0 , $rightMargin);
$pdf->SetAutoPageBreak(true,0);

//add new page
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);

//Cell(width , height , text , border , end line , [align] )

$pdf->Cell($width,$height,'',0,1,'L');//Horizontal Space

$pdf->Cell($width,$height/1.5,'Monarch Kitchen','0',1,'C');

$pdf->SetFont('Arial','',7);//set font to arial, regular, 8pt

$pdf->Cell($width,$height/1.5,'No 85 Moratuwa-Piliyandala Rd, Piliyandala.','0',1,'C');
$pdf->Cell($width,$height/1.5,'Sri Lanka','0',1,'C');

$pdf->Cell($width,$height/10,'','0',1,'C');//Horizontal Line

$customer=\App\User::find($invoice->user_master_iduser_master);
$pdf->SetFont('Arial','B',5);
$pdf->Cell($width,$height,'Customer :'.$customer->first_name.' '.$customer->last_name,'0',1,'L');
$pdf->SetFont('Arial','',5);

$customer=\App\User::find($invoice->sales_person_id);
$pdf->SetFont('Arial','B',5);
$pdf->Cell($width,$height,'Sales person :'.$customer->first_name.' '.$customer->last_name,'0',1,'L');
$pdf->SetFont('Arial','',5);

$pdf->SetFont('Arial','B',5);
$pdf->Cell($width,$height,'Order ID : '.str_pad($invoice->idorder,6,'0',STR_PAD_LEFT),'0',1,'L');
$pdf->SetFont('Arial','',5);

$pdf->SetFont('Arial','B',5);
$pdf->Cell($width,$height,'Received Date : '.$invoice->date,'0',1,'L');
$pdf->SetFont('Arial','',5);

$pdf->SetFont('Arial','B',5);
$pdf->Cell($width,$height,'Placement Date : '.$invoice->date,'0',1,'L');
$pdf->SetFont('Arial','',5);

$pdf->SetFont('Arial','B',5);
$pdf->Cell($width,$height,'Address : '.$invoice->address,'0',1,'L');
$pdf->SetFont('Arial','',5);

$pdf->SetFont('Arial','B',5);
$pdf->Cell($width,$height,'Contact No : '.$customer->contact_no,'0',1,'L');
$pdf->SetFont('Arial','',5);

$pdf->Cell($width,3,'','T',1,'C');//Horizontal Space

foreach ($data as $value) {
    $x=1;
   $pdf->SetFont('Arial','',6);//set font to arial, Bold, 10pt
   
   $pdf->Cell($width/2,3,$x.') '.$value['name'],'0',0,'L');
   $pdf->Cell($width/2,4,$value['qty'],'0',1,'R');
   $x++;
}

$pdf->Cell($width,$height,'','T',1,'C');//Horizontal Space



$pdf->SetFont('Arial','B',6);//set font to arial, Bold, 10pt
$pdf->Cell($width/2,3,'Total Amount','0',0,'L');
$pdf->Cell($width/2,4,number_format($invoice->total_cost,2),'0',1,'R');



$pdf->Cell($width,$height/2,'','0',1,'T');//Horizontal space
$pdf->SetFont('Arial','',6);//set font to arial, Bold, 10pt
$pdf->Cell($width,$height,'Thank You!','0',1,'C');
//output the result
//$pdf->AutoPrint();
$pdf->Output();
exit();
?>