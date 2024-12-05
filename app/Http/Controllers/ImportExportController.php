<?php

namespace App\Http\Controllers;

use App\Exports\BankExport;
use App\Exports\CapitalExpenditureExport;
use App\Exports\FacilityCostExport;
use App\Exports\MaterialCostExport;
use App\Exports\RevenuePlanExport;
use App\Exports\SalaryExport;
use App\Imports\CapitalImport;
use App\Imports\FacilityImport;
use App\Imports\MaterialImport;
use App\Imports\RevenueImport;
use App\Imports\SalaryImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportExportController extends Controller
{
    public function salaryexport($id)
    {
        $export = new SalaryExport($id);
        $date = now()->format('Y-m-d_H-i-s');
        return Excel::download($export, "Salary_{$date}.xlsx");
    }

    public function facilitycostexport($id)
    {
        $export = new FacilityCostExport($id);
        $date = now()->format('Y-m-d_H-i-s');
        return Excel::download($export, "FacilityCost_{$date}.xlsx");
    }

    public function materialcostexport($id)
    {
        $export = new MaterialCostExport($id);
        $date = now()->format('Y-m-d_H-i-s');
        return Excel::download($export, "MaterialCost_{$date}.xlsx");
    }

    public function capitalexpenditureexport($id)
    {
        $export = new CapitalExpenditureExport($id);
        $date = now()->format('Y-m-d_H-i-s');
        return Excel::download($export, "CapitalExpenditure_{$date}.xlsx");
    }

    public function revenueplanexport($id)
    {
        $export = new RevenuePlanExport($id);
        $date = now()->format('Y-m-d_H-i-s');
        return Excel::download($export, "RevenuePlan_{$date}.xlsx");
    }

    public function bankexport()
    {
        $date = now()->format('Y-m-d_H-i-s');
        return Excel::download(new BankExport, "Bank_{$date}.xlsx");
    }


    public function uploadSalary(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Use the BankImport class instead of PriceImport
            $import = new SalaryImport($request->bg_id);

            // Perform the import operation
            Excel::import($import, $request->file('file'));

            // Get the number of failures from the import
            $failureCount = $import->failures()->count();
            Log::info('Import completed', ['failures' => $failureCount]);

            // If there are any import failures, log and show them
            if ($failureCount > 0) {
                $failures = $import->failures();
                Log::warning('Import had failures', ['failureCount' => $failureCount, 'firstFailure' => $failures->first()]);
                return redirect()->back()->withFailures($failures);
            }

            // Return success message if no failures
            return redirect()->back()->with('success', 'Data Imported Successfully');
        } catch (ValidationException $e) {
            // Handle validation errors during the import
            dd($e->errors());

            Log::error('Validation failed during import', [
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'failures' => $e->failures(),
            ]);
            return redirect()
                ->back()
                ->with('error', 'Validation Failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Handle general exceptions during the import
            dd($e->getMessage());
            Log::error('Import failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()
                ->back()
                ->with('error', 'Data Import Failed: ' . $e->getMessage());
        }
    }

    public function uploadFacilities(Request $request)
    {
        $request->validate([
            'facilities-file' => 'required|mimes:xlsx,xls,csv',
        ]);
        try {
            // Use the BankImport class instead of PriceImport
            $import = new FacilityImport($request->bg_id);

            // Perform the import operation
            Excel::import($import, $request->file('facilities-file'));

            // Get the number of failures from the import
            $failureCount = $import->failures()->count();
            Log::info('Import completed', ['failures' => $failureCount]);

            // If there are any import failures, log and show them
            if ($failureCount > 0) {
                $failures = $import->failures();
                Log::warning('Import had failures', ['failureCount' => $failureCount, 'firstFailure' => $failures->first()]);
                return redirect()->back()->withFailures($failures);
            }

            // Return success message if no failures
            return redirect()->back()->with('success', 'Data Imported Successfully');
        } catch (ValidationException $e) {
            // Handle validation errors during the import
            dd($e->errors());

            Log::error('Validation failed during import', [
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'failures' => $e->failures(),
            ]);
            return redirect()
                ->back()
                ->with('error', 'Validation Failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Handle general exceptions during the import
            dd($e->getMessage());
            Log::error('Import failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()
                ->back()
                ->with('error', 'Data Import Failed: ' . $e->getMessage());
        }
    }
    public function uploadCapital(Request $request)
    {
        $request->validate([
            'capital-file' => 'required|mimes:xlsx,xls,csv',
        ]);
        try {
            // Use the BankImport class instead of PriceImport
            $import = new CapitalImport($request->bg_id);

            // Perform the import operation
            Excel::import($import, $request->file('capital-file'));

            // Get the number of failures from the import
            $failureCount = $import->failures()->count();
            Log::info('Import completed', ['failures' => $failureCount]);

            // If there are any import failures, log and show them
            if ($failureCount > 0) {
                $failures = $import->failures();
                Log::warning('Import had failures', ['failureCount' => $failureCount, 'firstFailure' => $failures->first()]);
                return redirect()->back()->withFailures($failures);
            }

            // Return success message if no failures
            return redirect()->back()->with('success', 'Data Imported Successfully');
        } catch (ValidationException $e) {
            // Handle validation errors during the import
            dd($e->errors());

            Log::error('Validation failed during import', [
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'failures' => $e->failures(),
            ]);
            return redirect()
                ->back()
                ->with('error', 'Validation Failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Handle general exceptions during the import
            dd($e->getMessage());
            Log::error('Import failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()
                ->back()
                ->with('error', 'Data Import Failed: ' . $e->getMessage());
        }
    }
    public function uploadRevenue(Request $request)
    {
        $request->validate([
            'revenue-file' => 'required|mimes:xlsx,xls,csv',
        ]);
        try {
            // Use the BankImport class instead of PriceImport
            $import = new RevenueImport($request->bg_id);

            // Perform the import operation
            Excel::import($import, $request->file('revenue-file'));

            // Get the number of failures from the import
            $failureCount = $import->failures()->count();
            Log::info('Import completed', ['failures' => $failureCount]);

            // If there are any import failures, log and show them
            if ($failureCount > 0) {
                $failures = $import->failures();
                Log::warning('Import had failures', ['failureCount' => $failureCount, 'firstFailure' => $failures->first()]);
                return redirect()->back()->withFailures($failures);
            }

            // Return success message if no failures
            return redirect()->back()->with('success', 'Data Imported Successfully');
        } catch (ValidationException $e) {
            // Handle validation errors during the import
            dd($e->errors());

            Log::error('Validation failed during import', [
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'failures' => $e->failures(),
            ]);
            return redirect()
                ->back()
                ->with('error', 'Validation Failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Handle general exceptions during the import
            dd($e->getMessage());
            Log::error('Import failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()
                ->back()
                ->with('error', 'Data Import Failed: ' . $e->getMessage());
        }
    }


    public function uploadMaterial(Request $request)
    {
        $request->validate([
            'material-file' => 'required|mimes:xlsx,xls,csv',
        ]);
        try {
            // Use the BankImport class instead of PriceImport
            $import = new MaterialImport($request->bg_id);

            // Perform the import operation
            Excel::import($import, $request->file('material-file'));
            // Get the number of failures from the import
            $failureCount = $import->failures()->count();
            Log::info('Import completed', ['failures' => $failureCount]);

            // If there are any import failures, log and show them
            if ($failureCount > 0) {
                $failures = $import->failures();
                Log::warning('Import had failures', ['failureCount' => $failureCount, 'firstFailure' => $failures->first()]);
                return redirect()->back()->withFailures($failures);
            }

            // Return success message if no failures
            return redirect()->back()->with('success', 'Data Imported Successfully');
        } catch (ValidationException $e) {
            // Handle validation errors during the import
            dd($e->errors());

            Log::error('Validation failed during import', [
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'failures' => $e->failures(),
            ]);
            return redirect()
                ->back()
                ->with('error', 'Validation Failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Handle general exceptions during the import
            dd($e->getMessage());
            Log::error('Import failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()
                ->back()
                ->with('error', 'Data Import Failed: ' . $e->getMessage());
        }
    }
}
