<?php

namespace App\Imports;

use App\Models\Bank;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\Log;
use App\Models\LedgerEntry;

class BankImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        Log::info('Processing row', $row);

        try {
            // Ensure account is a string, even if it is not passed as a string
            $account = (string) $row['account']; // Force account to be a string

            // Create a new Bank model instance with mapped data from the row
            $bank = new Bank([
                'bank_name' => $row['bank_name'], // string
                'iban' => $row['iban'], // string
                'account' => $account, // Ensure account is a string
                'swift_code' => $row['swift_code'], // string
                'bank_address' => $row['bank_address'], // text (nullable)
                'branch' => $row['branch'], // string
                'rm_detail' => $row['rm_detail'], // text (nullable)
                'country' => $row['country'], // text (nullable)
                'region' => $row['region'], // text (nullable)
                'balance_amount' => $row['balance_amount'] ?? 0, // decimal (nullable), default to 0
            ]);

            // Save the bank record to the database
            $bank->save();

            Log::info('Created Bank', $bank->toArray());

            // Create a ledger entry for the bank
            LedgerEntry::create([
                'bank_id' => $bank->id,
                'amount' => $bank->balance_amount,
                'type' => 'credit', // Assuming initial balance is a credit
                'description' => 'Initial balance',
            ]);

            Log::info('Ledger entry created for Bank ID: ' . $bank->id);

            return $bank;
        } catch (\Exception $e) {
            Log::error('Error processing row', [
                'row' => $row,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'bank_name' => 'required|string',
            'iban' => 'nullable|string',
            'account' => 'nullable|string', // Treat account as a string
            'swift_code' => 'nullable|string',
            'branch' => 'nullable|string',
            'rm_detail' => 'nullable|string',
            'country' => 'nullable|string',
            'region' => 'nullable|string',
            'balance_amount' => 'nullable|numeric', // Validate balance amount as numeric
        ];
    }
}
