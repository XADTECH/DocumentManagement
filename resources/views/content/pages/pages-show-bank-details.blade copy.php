<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <style>
        /* General reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #e9edf4; /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            max-width: 900px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .payment-details, .order-list {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); /* Added shadow for cards */
        }

        .payment-details h2, .order-list h3 {
            margin-bottom: 16px;
            font-size: 20px;
            font-weight: bold;
        }

        .logos {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .logos img {
            width: 40px;
            height: auto;
        }

        .card-mockup {
            background: linear-gradient(135deg, #0066ff, #0033cc);
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 16px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-mockup .card-number {
            font-size: 18px;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }

        .card-mockup .card-holder,
        .card-mockup .card-expiry {
            font-size: 14px;
            margin-top: 8px;
            display: flex;
            justify-content: space-between;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .form-group .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group input[type="checkbox"] {
            width: auto;
        }

        .pay-now-btn {
            background: #0066ff;
            color: white;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 102, 255, 0.3); /* Button shadow */
        }

        .pay-now-btn:hover {
            background: #0056d4;
        }

        .order-list ul {
            list-style: none;
            margin-bottom: 20px;
        }

        .order-list ul li {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .order-list ul li:last-child {
            border-bottom: none;
        }

        .total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Payment Details -->
        <div class="payment-details">
            <h2>Payment details</h2>
            <div class="logos">
                <img src="https://via.placeholder.com/40x24?text=VISA" alt="Visa">
                <img src="https://via.placeholder.com/40x24?text=MC" alt="Mastercard">
                <img src="https://via.placeholder.com/40x24?text=PP" alt="PayPal">
                <img src="https://via.placeholder.com/40x24?text=AP" alt="Apple Pay">
            </div>
            <!-- Debit Card Mockup -->
            <div class="card-mockup">
                <div class="card-number">5303 6084 2402 3649</div>
                <div class="card-holder">
                    <span>Lois Pewterschmidt Griffin</span>
                    <span>10/24</span>
                </div>
                <div class="card-expiry">
                    <span>CVV: ***</span>
                </div>
            </div>
            <form>
                <div class="form-group">
                    <label for="holder-name">Holder name</label>
                    <input type="text" id="holder-name" placeholder="Enter cardholder's name">
                </div>
                <div class="form-group">
                    <div class="checkbox-label">
                        <input type="checkbox" id="save-card">
                        <label for="save-card">Save card detail?</label>
                    </div>
                </div>
                <button type="submit" class="pay-now-btn">Pay now</button>
            </form>
        </div>

        <!-- Order List -->
        <div class="order-list">
            <h3>Your order list:</h3>
            <ul>
                <li>
                    <span>Pizza pepperoni (16" inch, slim)</span>
                    <span>$12</span>
                </li>
                <li>
                    <span>Pizza margherita (16" inch, slim)</span>
                    <span>$18</span>
                </li>
                <li>
                    <span>Lemonade (2L)</span>
                    <span>$3</span>
                </li>
                <li>
                    <span>Cheesecake (Small)</span>
                    <span>$5</span>
                </li>
            </ul>
            <div class="total">
                <span>Total:</span>
                <span>$38</span>
            </div>
        </div>
    </div>
</body>
</html>
