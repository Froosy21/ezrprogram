<?php
session_start();

$payment_url = isset($_SESSION['payment_url']) ? $_SESSION['payment_url'] : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - EZReborn</title>
    <style>
        .payment-container {
            padding: 20px;
            text-align: center;
        }
        .payment-link {
            margin-top: 20px;
            font-size: 18px;
            color: blue;
            word-wrap: break-word;
        }
        .copy-btn {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
    <script>
        function copyToClipboard() {
            var copyText = document.getElementById("paymentLink");
            var tempInput = document.createElement("input");
            tempInput.value = copyText.innerText;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            alert("Payment link copied to clipboard!");
        }
    </script>
</head>
<body>
    <div class="payment-container">
        <h1>Payment</h1>
        <?php if ($payment_url): ?>
            <div class="payment-link" id="paymentLink"><?php echo htmlspecialchars($payment_url); ?></div>
            <button class="copy-btn" onclick="copyToClipboard()">Copy Payment Link</button>
        <?php else: ?>
            <p>No payment link available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
 