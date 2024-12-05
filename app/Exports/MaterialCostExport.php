<?php

namespace App\Exports;

use App\Models\MaterialCost;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MaterialCostExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
    public function collection()
    {
        return MaterialCost::where('budget_project_id', $this->id)->get()->map(function ($salary) {
            #$
            $project = Project::where('id', $salary->project)->first();
            return [
                'type' => $salary->type, // Type of record (Material/Cost)
                'project' => $project->name, // Project name
                'po' => $salary->po, // Type of expense (e.g., OPEX)
                'expense_head' => str_replace('_', ' ', $salary->expense_head), // Specific expense (e.g., Salary, Materials)
                'expenses' => $salary->expenses, // Specific expense (e.g., Salary, Materials)
                'description' => $salary->description, // Description of the material or details
                'status' => $salary->status, // Status of the budget entry (e.g., New Hiring, Purchased)
                'quantity' => $salary->quantity, // Amount of material (e.g., 100, 50)
                'unit' => $salary->unit, // Unit of measurement (e.g., meters, units, liters)
                'unit_cost' => $salary->unit_cost, // Cost per unit of the material (e.g., 100 per meter)
                'total_cost' => $salary->total_cost, // Total calculated cost (quantity * unit_cost)
                'average_cost' => $salary->average_cost, // Average cost per unit, if needed
                'approval_status' => $salary->approval_status, // Approval status
                'percentage_cost' => $salary->percentage_cost,
            ];
        });
    }


    public function headings(): array
    {
        // Add your column headings here
        return [
            'Type', // Type of record (Material/Cost)
            'Project', // Project name
            'po', // Type of expense (e.g., OPEX)
            'Expense head', // Specific expense (e.g., Salary, Materials)
            'Expenses', // Specific expense (e.g., Salary, Materials)
            'Description', // Description of the material or details
            'Status', // Status of the budget entry (e.g., New Hiring, Purchased)
            'Quantity', // Amount of material (e.g., 100, 50)
            'Unit', // Unit of measurement (e.g., meters, units, liters)
            'Unit Cost', // Cost per unit of the material (e.g., 100 per meter)
            'total Cost', // Total calculated cost (quantity * unit_cost)
            'Average Cost', // Average cost per unit, if needed
            'Approval Status', // Approval status
            'Percentage Cost',
        ];
    }
}
