<?php
// payment.php

session_start();

function processPayment($cardNumber, $cardExpiry, $cardCVC, $amount) {
    // Mock validation and processing
    $validCard = "4242424242424242"; // Mock valid card number for testing
    if ($cardNumber !== $validCard) {
        return [
            "success" => false,
            "message" => "Invalid card number. Payment failed."
        ];
    }

    if ($amount <= 0) {
        return [
            "success" => false,
            "message" => "Invalid amount. Payment must be greater than zero."
        ];
    }

    // Simulate a transaction ID and status
    $transactionId = strtoupper(uniqid("TXN"));
    return [
        "success" => true,
        "message" => "Payment successful!",
        "transaction_id" => $transactionId,
        "amount" => $amount,
        "date" => date("Y-m-d H:i:s")
    ];
}

$paymentResult = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardNumber = trim($_POST['card_number']);
    $cardExpiry = trim($_POST['card_expiry']);
    $cardCVC = trim($_POST['card_cvc']);
    $amount = floatval($_POST['amount']);

    $paymentResult = processPayment($cardNumber, $cardExpiry, $cardCVC, $amount);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Gateway</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .container h1 { text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #218838; }
        .error { color: red; margin-top: 10px; }
        .receipt { margin-top: 20px; padding: 20px; background: #e7ffe7; border: 1px solid #28a745; border-radius: 8px; }
        .receipt p { margin: 5px 0; }
    </style>
</head>
<body>
<div class="container">
    <h1>Payment Gateway</h1>

    <?php if ($paymentResult && $paymentResult['success']): ?>
        <div class="receipt">
            <h2>Payment Receipt</h2>
            <p><strong>Transaction ID:</strong> <?= htmlspecialchars($paymentResult['transaction_id']) ?></p>
            <p><strong>Amount:</strong> $<?= htmlspecialchars(number_format($paymentResult['amount'], 2)) ?></p>
            <p><strong>Status:</strong> Success</p>
            <p><strong>Date:</strong> <?= htmlspecialchars($paymentResult['date']) ?></p>
        </div>
    <?php else: ?>
        <?php if ($paymentResult): ?>
            <p class="error"><?= htmlspecialchars($paymentResult['message']) ?></p>
        <?php endif; ?>

        <form method="POST" action="payment.php">
            <div class="form-group">
                <label for="card_number">Card Number</label>
                <input type="text" id="card_number" name="card_number" maxlength="16" required>
            </div>
            <div class="form-group">
                <label for="card_expiry">Card Expiry (MM/YY)</label>
                <input type="text" id="card_expiry" name="card_expiry" maxlength="5" required>
            </div>
            <div class="form-group">
                <label for="card_cvc">CVC</label>
                <input type="text" id="card_cvc" name="card_cvc" maxlength="3" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount (USD)</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0.01" required>
            </div>
            <button type="submit" class="btn">Submit Payment</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
