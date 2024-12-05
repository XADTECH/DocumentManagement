<?php

namespace App\Imports;

use App\Models\BudgetProject;
use App\Models\CapitalExpenditure;
use App\Models\DirectCost;
use App\Models\FacilityCost;
use App\Models\IndirectCost;
use App\Models\RevenuePlan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class RevenueImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
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
        // Find the related budget project
        $budgetProject = BudgetProject::find($this->bg_id);
        // Initialize DirectCost and IndirectCost for the project
        $directCost = DirectCost::firstOrNew(['budget_project_id' =>  $this->bg_id]);
        $indirectCost = IndirectCost::firstOrNew(['budget_project_id' =>  $this->bg_id]);

        // Calculate total costs
        $totalDirectCost = $directCost->exists ? $directCost->calculateTotalDirectCost() : 0;
        $totalIndirectCost = $indirectCost->exists ? $indirectCost->calculateTotalIndirectCost() : 0;

        // Create the new revenue plan instance
        $revenuePlan = new RevenuePlan();

        // Set revenue plan properties
        $revenuePlan->budget_project_id = $budgetProject->id;
        $revenuePlan->type = $row['type'];
        $revenuePlan->project =  $budgetProject->project_id;
        $revenuePlan->amount = $row['amount'];
        $revenuePlan->description = $row['description'];
        $revenuePlan->approval_status = $row['approval_status'];

        // Run calculations before saving
        $revenuePlan->calculateTotalProfit();
        $revenuePlan->calculateNetProfitBeforeTax($totalDirectCost, $totalIndirectCost);
        // Perform remaining calculations
        $revenuePlan->calculateTax();
        $revenuePlan->calculateNetProfitAfterTax();
        //$revenuePlan->calculateProfitPercentage();

        // Save the revenue plan data
        $revenuePlan->save();

        return;
    }

    public function rules(): array
    {
        return [
            'type'  => 'nullable',
            'project' => 'nullable',  // Assuming 'name' is the column in projects table
            'contract'  => 'nullable',
            'description'  => 'nullable',
            'amount'  => 'nullable',
        ];
    }
}
