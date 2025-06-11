<!DOCTYPE html>
<html>
<head>
    <title>Print Invoice Barcode</title>
    <style>
        @page {
            size: 50mm 20mm;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            width: 50mm;
            height: 20mm;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .barcode-container {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .barcode-img {
            width: auto;
            height: 16mm;
            display: block;
        }

        .barcode-text {
            font-size: 8px;
            margin-top: 1mm;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="barcode-container">
        <img class="barcode-img" src="{{ $barcodeUrl }}" alt="Barcode: {{ $barcodeCode }}">
        <div class="barcode-text">{{ $invoiceNo }}</div>
    </div>
</body>
</html>
