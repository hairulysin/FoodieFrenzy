<?php
require "FPDF/fpdf.php";
require "components\connect.php";

$pdf = new FPDF('L', 'mm', 'A4');
$pdf->SetMargins(40, 20);
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(225, 13, 'LAPORAN DATA MENU', 0, 1, 'C');
$pdf->Ln();
$pdf->SetFont('Times', 'B', 15);
$pdf->Cell(15, 7, 'ID', 1, 0, 'C');
$pdf->Cell(65, 7, 'Name', 1, 0, 'C');
$pdf->Cell(65, 7, 'Category', 1, 0, 'C');
$pdf->Cell(65, 7, 'Price', 1, 0, 'C');
$pdf->Cell(15, 7, '', 0, 1);
$pdf->SetFont('Times', '', 15);

if (isset($_GET['all']) && $_GET['all'] === 'true') {
    $select_products = $conn->prepare("SELECT * FROM `products`");
    $select_products->execute();
    $products = $select_products->fetchAll();
    foreach ($products as $product) {
        $pdf->Cell(15, 6, $product['id'], 1, 0, 'C');
        $pdf->Cell(65, 6, $product['name'], 1, 0);
        $pdf->Cell(65, 6, $product['category'], 1, 0);
        $pdf->Cell(65, 6, $product['price'], 1, 1);
    }
} else {
    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];
        $select_product = $conn->prepare("SELECT * FROM `products` WHERE id like '%$product_id%' or category like '%$product_id%'");
        // $select_product->execute([$product_id]);
        $product = $select_product->fetch();
        if ($product) {
            $pdf->Cell(15, 6, $product['id'], 1, 0, 'C');
            $pdf->Cell(65, 6, $product['name'], 1, 0);
            $pdf->Cell(65, 6, $product['category'], 1, 0);
            $pdf->Cell(65, 6, $product['price'], 1, 1);
        }
    }
}

$pdf->Ln();
$pdf->Cell(0, 7, 'Date : ' . date('d-M-Y'), 0, 1);
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(20, 10, 'Founder : Hairul & Nina', 0, 1);
// $pdf->Cell(0, 10, 'NIM: F55121011', 0, 1);

$pdf->Ln();
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 5, 'Notes :', 0, 1);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 7, 'Dalam laporan ini, disajikan data menu dari Restaurant FoodieFrenzy. Laporan ini dapat digunakan untuk memantau dan mengelola menu yang tersedia.', 0, 'L');


$pdf->Output();
?>7
