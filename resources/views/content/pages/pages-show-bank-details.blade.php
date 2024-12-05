<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Detail XAD Technologies</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e3e6eb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .neumorph-card {
            background: #e3e6eb;
            border-radius: 20px;
            box-shadow: 5px 5px 10px #c2c8d1, -5px -5px 10px #ffffff;
            padding: 30px;
            width: 360px;
            max-width: 90%;
            text-align: left;
            position: relative;
        }

        /* Decorative bar for card */
        .neumorph-card::before {
            content: "";
            position: absolute;
            top: 15px;
            left: 20px;
            height: 8px;
            width: 80px;
            background-color: #cfd4db;
            border-radius: 4px;
        }

        /* Chip icon */
        .chip-icon {
            position: absolute;
            top: 40px;
            left: 20px;
            width: 40px;
            height: 28px;
            background: linear-gradient(135deg, #d1d6dd, #e3e6eb);
            border-radius: 4px;
        }

        .neumorph-card h1 {
            font-weight: 600;
            margin-top: 70px;
            margin-bottom: 20px;
            color: #333;
            font-size: 1.2rem;
            text-align: center;
        }

        .bank-details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            color: #555;
        }

        .bank-details-table th,
        .bank-details-table td {
            padding: 8px;
            text-align: left;
        }

        .bank-details-table th {
            color: #333;
            font-weight: 600;
            width: 40%;
        }

        .neumorph-card button {
            display: block;
            width: 100%;
            cursor: pointer;
            font-weight: 600;
            background-color: #0067aa;
            color: #fff;
            padding: 12px;
            margin-top: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 3px 3px 6px #a0b2c6, -3px -3px 6px #ffffff;
            transition: background-color 0.3s ease;
        }

        .neumorph-card button:hover {
            background-color: #00558c;
        }

        .neumorph-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

        .back-button {
            display: block;
            width: 100%;
            cursor: pointer;
            font-weight: 600;
            background-color: #0067aa;
            color: #fff;
            text-align: center;
            padding: 12px;
            margin-top: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 3px 3px 6px #a0b2c6, -3px -3px 6px #ffffff;
            text-decoration: none;
            /* Remove underline */
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #00558c;
        }
    </style>
</head>

<body>
    <div class="neumorph-card">
        <!-- Decorative chip for the debit card feel -->
        <div class="chip-icon"></div>

        <h1>Bank Details</h1>
        <table class="bank-details-table">
            <tr>
                <th>Bank Name:</th>
                <td>{{ $bank->bank_name }}</td>
            </tr>
            <tr>
                <th>Address:</th>
                <td>{{ $bank->bank_address }}</td>
            </tr>
            <tr>
                <th>IBAN:</th>
                <td>{{ $bank->iban }}</td>
            </tr>
            <tr>
                <th>SWIFT Code:</th>
                <td>{{ $bank->swift_code }}</td>
            </tr>
            <tr>
                <th>Account Number:</th>
                <td>{{ $bank->account }}</td>
            </tr>
            <tr>
                <th>Branch:</th>
                <td>{{ $bank->branch }}</td>
            </tr>
            <tr>
                <th>Country:</th>
                <td>{{ $bank->country }}</td>
            </tr>
            <tr>
                <th>Region:</th>
                <td>{{ $bank->region }}</td>
            </tr>
        </table>

        <a href="{{ route('add-opening-balance') }}" class="back-button">Back</a>
        <div class="neumorph-footer">XAD Technologies LLC</div>
    </div>
</body>

</html>
