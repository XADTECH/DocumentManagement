{{-- resources/views/trial_balance.blade.php --}}
{{-- resources/views/trial_balance.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Ledger {{$bank->bank_name}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            max-width: 90%;
            margin: 1rem auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #ffffff;
            padding: 1rem;
        }
        @media (min-width: 768px) {
            .table-container {
                max-width: 50%;
                padding: 1.5rem;
            }
        }
        .table-title {
            text-align: center;
            font-weight: bold;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .table-subtitle {
            text-align: center;
            font-style: italic;
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }
        .trial-balance-table th, .trial-balance-table td {
            text-align: right;
            vertical-align: middle;
            padding: 0.75rem;
        }
        .trial-balance-table th {
            background-color: #0067aa;
            color: #ffffff;
        }
        .trial-balance-table .account-name {
            text-align: left;
        }
        .trial-balance-table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .table-footer {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .home-button {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="table-container">
            <a href="{{ route('add-opening-balance') }}" class="btn btn-primary home-button">Home</a>
        
            <div class="table-title">XAD Technologies LLC</div>
            <div class="table-subtitle">{{ $bank->bank_name }}<br>{{ $bank->bank_address }}</div>
        
            <div class="table-responsive">
                <table class="table table-bordered trial-balance-table">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>Debit</th>
                            <th>Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ledgerEntries as $entry)
                            <tr>
                                <td class="account-name">{{ $entry->description }}</td>
                                <td>{{ $entry->type === 'debit' ? number_format($entry->amount, 0) : '' }}</td>
                                <td>{{ $entry->type === 'credit' ? number_format($entry->amount, 0) : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-footer">
                            <td class="account-name">Totals</td>
                            <td>
                                {{ number_format($ledgerEntries->where('type', 'debit')->sum('amount'), 0) }}
                            </td>
                            <td>
                                {{ number_format($ledgerEntries->where('type', 'credit')->sum('amount'), 0) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

