<?php

namespace App\Imports;

use App\Models\SupplierPrice;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PriceImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        Log::info('Processing row', $row);
        try {
            $purchaseDate = isset($row['purchase_date']) ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['purchase_date']))->format('Y-m-d') : '';
            Log::info('Parsed purchase date', ['original' => $row['purchase_date'], 'parsed' => $purchaseDate]);

            $price = floatval($row['price']);
            $discount =  $price * 0.03;
            Log::info('Calculated discount', ['price' => $price, 'discountFormula' => $row['3_discount'], 'calculatedDiscount' => $discount]);

            $supplierPrice = new SupplierPrice([
                'items_code' => $row['items_code'],
                'purchase_date' => isset($purchaseDate) ? $purchaseDate : null,
                'item_name' => $row['item_name'],
                'supplier_name' => $row['supplier_name'],
                'uom' => $row['uom'],
                'price' => $price,
                'discount' =>   $price - $discount,
                'remarks' => $row['remarks'],
            ]);

            Log::info('Created SupplierPrice', $supplierPrice->toArray());

            return $supplierPrice;
        } catch (\Exception $e) {

            Log::error('Error processing row', [
                'row' => $row,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function calculateDiscount($price, $discountFormula)
    {
        // Remove the '=' sign if present
        $discountFormula = ltrim($discountFormula, '=');

        // Replace F2 with the actual price
        $discountFormula = str_replace('F2', $price, $discountFormula);

        // Evaluate the formula
        try {
            $result = eval("return $discountFormula;");
            return $price - $result; // Return the discount amount
        } catch (\Throwable $e) {
            Log::error('Error calculating discount', [
                'formula' => $discountFormula,
                'price' => $price,
                'error' => $e->getMessage()
            ]);
            return 0; // Return 0 if there's an error
        }
    }

    public function rules(): array
    {
        return [
            'items_code' => 'nullable',
            'purchase_date' => 'nullable',
            'item_name' => 'nullable',
            'supplier_name' => 'nullable',
            'uom' => 'nullable',
            'price' => 'nullable',
            '3_discount' => 'nullable',
            'remarks' => 'nullable',
        ];
    }
}
