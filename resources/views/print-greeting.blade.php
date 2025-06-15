<!DOCTYPE html>
<html>
<head>
    <title>Print Greeting</title>
    <style>
        @page {
            size: 100mm 150mm;
            margin: 0;
        }
        body {
            width: 100mm;
            height: 150mm;
            margin: 0;
            padding: 10mm;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }
        .container {
            text-align: center;
            border: 2px dashed #000;
            padding: 20px;
            height: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .section {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="section">
            <div class="label">To:</div>
            <div>{{ $stoReceiverName }}</div>
            <div>{{ $stoReceiverPhone }}</div>
        </div>

        <div class="section">
            <div class="label">From:</div>
            <div>{{ $stoPicName }}</div>
            <div>{{ $stoPicPhone }}</div>
        </div>

        @if ($stoNote)
        <div class="section">
            <div class="label">Note:</div>
            <div>{{ $stoNote }}</div>
        </div>
        @endif
    </div>
</body>
</html>
