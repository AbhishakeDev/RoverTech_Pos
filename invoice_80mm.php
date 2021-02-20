<?php
//call the pdf library
require('fpdf182/fpdf.php');

include 'connectdb.php';


//for filling the customer related details
$id=$_GET['id'];

$select=$pdo->prepare("select * from tbl_invoice where invoice_id=".$id);

$select->execute();

$row=$select->fetch(PDO::FETCH_OBJ);


$pdf = new FPDF('P','mm',array(80,200));

$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(60,8,'RoverTech Inc',1,1,'C');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(60,5,"Address : Kamta , faizabad road , Lucknow",0,1,'C');
$pdf->Cell(60,5,"Phone Number : +91-6393122939",0,1,'C');
$pdf->Cell(60,5,"Email-Address : abhishekt.1181@gmail.com",0,1,'C');
$pdf->Cell(60,5,"Website : www.rovertech.com ",0,1,'C');


//Line(x1,y1,x2,y2)
$pdf->Line(7,38,72,38);

$pdf->Ln(1);//line break

$pdf->SetFont('Arial','BI',8);
$pdf->Cell(20,4,'Bill To : ',0,0,'');
$pdf->SetFont('Courier','BI',8);
$pdf->Cell(40,4,$row->customer_name,0,1,'');



$pdf->SetFont('Arial','BI',8);
$pdf->Cell(20,4,"Invoice no. : ",0,0,'');
$pdf->Cell(40,4,$row->invoice_id,0,1,'');

$pdf->SetFont('Arial','BI',8);
$pdf->Cell(20,4,"Date : ",0,0,'');
$pdf->Cell(40,4,$row->order_date,0,1,'');

$pdf->Cell(50,5,'',0,1,'');//empty cell



//Product Table code

$pdf->SetX(7);

$pdf->SetFont('Courier','B',8);
$pdf->Cell(34,5,'Product',1,0,'C');
$pdf->Cell(11,5,'Qty',1,0,'C');
$pdf->Cell(8,5,'Prc',1,0,'C');
$pdf->Cell(12,5,'Total',1,1,'C');



//for filling the product related details


$select=$pdo->prepare("select * from tbl_invoice_details where invoice_id=".$id);

$select->execute();

while($item=$select->fetch(PDO::FETCH_OBJ)){
    $pdf->SetX(7);

    $pdf->SetFont('Helvetica','B',8);
    $pdf->Cell(34,5,$item->product_name,1,0,'C');
    $pdf->Cell(11,5,$item->qty,1,0,'C');
    $pdf->Cell(8,5,$item->price,1,0,'C');
    $pdf->Cell(12,5,$item->price*$item->qty,1,1,'C');
    
    
}





// Customer details and invoice details
$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,5,'',0,0,'C');
// $pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25,5,'Subtotal',1,0,'C');
$pdf->Cell(20,5,$row->subtotal,1,1,'C');

$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,5,'',0,0,'C');
// $pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25,5,'Tax(5%)',1,0,'C');
$pdf->Cell(20,5,$row->tax,1,1,'C');

$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,5,'',0,0,'C');
// $pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25,5,'Discount',1,0,'C');
$pdf->Cell(20,5,$row->discount,1,1,'C');

$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,5,'',0,0,'C');
// $pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25,5,'Total',1,0,'C');
$pdf->Cell(20,5,$row->total,1,1,'C');

$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,5,'',0,0,'C');
// $pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25,5,'Paid',1,0,'C');
$pdf->Cell(20,5,$row->paid,1,1,'C');

$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,5,'',0,0,'C');
// $pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25,5,'Due',1,0,'C');
$pdf->Cell(20,5,$row->due,1,1,'C');

$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,5,'',0,0,'C');
// $pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25,5,'Payment Type',1,0,'C');
$pdf->Cell(20,5,$row->payment_type,1,1,'C');






$pdf->Cell(20,5,'',0,1,'');//empty cell

$pdf->SetX(7);

$pdf->SetFont('Courier','B',8);
$pdf->Cell(25,5,'Important Notice:',0,1,'C');

$pdf->SetX(7);

$pdf->SetFont('Arial','',5);
$pdf->Cell(75,5,'No item will be refunded or replaced if you don"t have the invoice with you.',0,2,'');

$pdf->SetX(7);

$pdf->SetFont('Arial','',5);
$pdf->Cell(75,5,'You can refund within 2 days of purchase.',0,1,'');


//Output the result
$pdf->Output();