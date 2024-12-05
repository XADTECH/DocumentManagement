<?php

namespace App\Imports;

use App\Models\BudgetProject;
use App\Models\DirectCost;
use App\Models\Salary;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class SalaryImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;
    protected $bg_id;

    // Constructor to receive the id
    public function __construct($bg_id)
    {
        $this->bg_id = $bg_id;
    }
    public function model(array $row)
    {


        $directCost = DirectCost::firstOrNew([
            'budget_project_id' => $this->bg_id,
        ]);
        $bd = BudgetProject::findOrFail($this->bg_id);
        if (!$directCost->exists) {
            $directCost->save();
        }
        // Create a new Salary record
        $salary = new Salary();
        $salary->direct_cost_id = $directCost->id;
        $salary->type = $row['type'];
        $salary->project = $bd->project_id;
        $salary->po = $row['po'];
        // Set the `expenses` field based on `other_expense`
        $salary->expenses = $row['other_expense'] ?? $row['expenses'];

        $salary->cost_per_month = $row['cost_per_month'];
        $salary->description = $row['description'];
        $salary->status = $row['status'];
        $salary->no_of_staff = $row['no_of_staff']; // Map to your model attribute
        $salary->no_of_months = $row['no_of_months']; // Map to your model attribute
        $salary->overseeing_sites = $row['overseeing_sites']  ?? 0;
        $salary->budget_project_id =  $this->bg_id; // Map to your model attribute
        $salary->visa_status = $row['visa_status']; // Set visa status

        // Calculate total and average cost
        $salary->calculateTotalCost();
        $salary->calculateAverageCost();
  
        $salary->save();

        $cost = $directCost->calculateTotalDirectCost();

        return;
    }

    public function rules(): array
    {
        return [
            'type' => 'nullable',
            'project' => 'nullable',
            'po' => 'nullable',
            'expenses' => 'nullable',
            'other_expense' => 'nullable',
            'description' => 'nullable',
            'status' => 'nullable',
            'cost_per_month' => 'nullable',
            'no_of_months' => 'nullable',
            'no_of_staff' => 'nullable',
            'overseeing_sites' => 'nullable',
            'No_of_months' => 'nullable',

        ];
    }
}
