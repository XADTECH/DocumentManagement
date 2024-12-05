<?php

namespace App\Imports;

use App\Models\BudgetProject;
use App\Models\CapitalExpenditure;
use App\Models\DirectCost;
use App\Models\FacilityCost;
use App\Models\IndirectCost;
use App\Models\MaterialCost;
use App\Models\NocPayment;
use App\Models\PettyCash;
use App\Models\RevenuePlan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class MaterialImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
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

        // dd($row);
        // Find the related budget project
        $budgetProject = BudgetProject::find($this->bg_id);
        $directCost = DirectCost::firstOrNew([
            'budget_project_id' => $this->bg_id,
        ]);

        // If DirectCost was not found, create it and save
        if (!$directCost->exists) {
            $directCost->save();
        }

        // Conditionally store fields based on the expense type
        if ($row['expense_head'] === 'consumed_material' || $row['expense_head'] === 'consumed material') {


            $materialCost = new MaterialCost();
            $materialCost->expense_head = $row['expense_head'];
            $materialCost->expenses = $row['expenses'];
            $materialCost->quantity = $row['quantity'];
            $materialCost->unit = $row['unit'];
            $materialCost->unit_cost = $row['unit_cost'];
            $materialCost->description = $row['description'];
            $materialCost->status = $row['status'];
            $materialCost->direct_cost_id = $directCost->id; // Foreign key reference
            $materialCost->budget_project_id = $this->bg_id; // Budget project ID
            $materialCost->project = $budgetProject->project_id; // Set project field
            $materialCost->type = $row['type'];
            $materialCost->po = $row['po'];

            // Calculate total and average cost
            $materialCost->calculateTotalCost();
            $materialCost->calculateAverageCost();
            $materialCost->calculateAverageCostPercentage();

            // Save the material cost
            $materialCost->save();

            // Redirect back to the edit page with a success message
            return;
        } elseif ($row['expenses'] === 'petty_cash') {
            // Check if the petty cash amount already exists for the project
            $existingPettyCash = PettyCash::where('project_id', $row['project_id'])
                ->where('amount', $row['petty_cash_amount'])
                ->first();

            if ($existingPettyCash) {
                return redirect('/pages/edit-project-budget/' . $row['project_id'])->withErrors(['amount' => 'Amount already exists for this project.']);
            }

            PettyCash::create([
                'project_id' => $row['project_id'],
                'description' => 'Amount for Petty Cash',
                'amount' => $row['petty_cash_amount'],
            ]);

            return redirect('/pages/edit-project-budget/' . $row['project_id'])->with('success', 'Petty Cash added successfully!');
        } elseif ($row['expense'] === 'noc_payment') {
            // Check if the NOC amount already exists for the project
            $existingNocPayment = NocPayment::where('project_id', $row['project_id'])
                ->where('amount', $row['noc_amount'])
                ->first();

            if ($existingNocPayment) {
                return redirect('/pages/edit-project-budget/' . $row['project_id'])->withErrors(['amount' => 'Amount already exists for this project.']);
            }

            NocPayment::create([
                'project_id' => $row['project_id'],
                'description' => 'Amount for NOC Payment',
                'amount' => $row['noc_amount'],
            ]);

            return;
        }
    }

    public function rules(): array
    {
        return [
            'type' => 'nullable', // Type of record (Material/Cost)
            'project' => 'nullable', // Project name
            'po' => 'nullable', // Type of expense (e.g., OPEX)
            'expense_head' => 'nullable', // Specific expense (e.g., Salary, Materials)
            'expense' => 'nullable', // Specific expense (e.g., Salary, Materials)
            'description' => 'nullable', // Description of the material or details
            'status' => 'nullable', // Status of the budget entry (e.g., New Hiring, Purchased)
            'quantity' => 'nullable', // Amount of material (e.g., 100, 50)
            'unit' => 'nullable', // Unit of measurement (e.g., meters, units, liters)
            'unit_cost' => 'nullable', // Cost per unit of the material (e.g., 100 per meter)
            'total_cost' => 'nullable', // Total calculated cost (quantity * unit_cost)
            'average_cost' => 'nullable', // Average cost per unit, if needed
            'approval_status' => 'nullable', // Approval status
            'percentage_cost' => 'nullable',
        ];
    }
}
