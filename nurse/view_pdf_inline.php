<?php
if (isset($_GET['pdf_data'])) {
    $pdf_data = $_GET['pdf_data']; // Get the Base64 encoded PDF data

    // Decode the Base64 data to get the binary content of the PDF
    $pdf_content = base64_decode($pdf_data);

    // Set the headers to serve the PDF in the browser
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=\"test_result.pdf\"");

    // Output the PDF content
    echo $pdf_content;
} else {
    echo "No PDF data provided.";
}
?>
