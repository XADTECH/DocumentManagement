<?php

namespace App\Exports;

use App\Models\FacilityCost;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FacilityCostExport implements FromCollection, WithHeadings
{
    protected $id;

    // Constructor to receive the id
    public function __construct($id)
    {
        $this->id = $id;
    }
    public function collection()
    {
        return
            FacilityCost::where('budget_project_id', $this->id)-> // Eager load the project relationship
            get()
            ->map(function ($salary) {
                $project = Project::where('id', $salary->project)->first();
                return [
                    'type' => $salary->type,
                    'project' =>  $project->name,  // Assuming 'name' is the column in projects table
                    'po' => $salary->po,
                    'expenses' => $salary->expenses,
                    'description' => $salary->description,
                    'status' => $salary->status,
                    'cost_per_month' => $salary->cost_per_month,
                    'no_of_staff' => $salary->no_of_staff,
                    'no_of_months' => $salary->no_of_months,
                    'total_cost' => $salary->total_cost,
                    'average_cost' => $salary->average_cost,
                    'approval_status' => $salary->approval_status,
                    'percentage_cost' => $salary->percentage_cost,
                ];
            });
    }
    public function headings(): array
    {
        // Add your column headings here
        return [
            'Type',
            'Project',
            'PO',
            'Expenses Head',
            'Description',
            'Status',
            'Cost Per Month',
            'No of Staff',
            'No of Months',
            'Total Cost',
            'Average Cost',
            'Approval Status',
            'Percentage Cost',
        ];
    }
}
