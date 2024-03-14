<?php
session_start();
// Include your database connection file
include "../../auth/db-connection/config.php";

// Include PHPExcel library
require '../../vendor/autoload.php'; // Include Composer autoload file

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Create new PHPExcel object
$objPHPExcel = new Spreadsheet();

// Set properties
$objPHPExcel->getProperties()->setCreator("Your Name")
                             ->setLastModifiedBy("Your Name")
                             ->setTitle("Orders Overview")
                             ->setSubject("Orders Overview")
                             ->setDescription("Orders Overview");

// Add column headers
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Status')
            ->setCellValue('B1', 'Count')
            ->setCellValue('C1', 'Total Sales');

// Fetch data for complete orders
$sql_complete = "SELECT 'Complete' AS status, COUNT(*) AS count, SUM(total_price) AS total_sales FROM orders WHERE order_status_id = 4";
// Execute the SQL query to fetch data
$complete_data = $connection->query($sql_complete)->fetch(PDO::FETCH_ASSOC);

// Fetch data for pending orders
$sql_pending = "SELECT 'Pending' AS status, COUNT(*) AS count, SUM(total_price) AS total_sales FROM orders WHERE order_status_id = 1";
$pending_data = $connection->query($sql_pending)->fetch(PDO::FETCH_ASSOC);

// Fetch data for payment received orders
$sql_payment_received = "SELECT 'Payment Received' AS status, COUNT(*) AS count, SUM(total_price) AS total_sales FROM orders WHERE order_status_id = 2";
$payment_received_data = $connection->query($sql_payment_received)->fetch(PDO::FETCH_ASSOC);

// Fetch data for canceled orders
$sql_canceled = "SELECT 'Canceled' AS status, COUNT(*) AS count, SUM(total_price) AS total_sales FROM orders WHERE order_status_id = 5";
$canceled_data = $connection->query($sql_canceled)->fetch(PDO::FETCH_ASSOC);

// Add data to the Excel sheet
$data = array($complete_data, $pending_data, $payment_received_data, $canceled_data);
$row = 2;
foreach ($data as $order) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $order['status']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $order['count']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $order['total_sales']);
    $row++;
}

// Set filename and headers for download
$filename = "orders_overview_" . date('YmdHis') . ".xlsx";

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Save Excel file to output
$objWriter = new Xlsx($objPHPExcel);
$objWriter->save('php://output');
exit;
?>
