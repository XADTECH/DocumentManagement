<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\Salary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalaryExport implements FromCollection, WithHeadings
{
    protected $id;

    // Constructor to receive the id
    public function __construct($id)
    {
        $this->id = $id;
    }
    public function collection()
    {

        return Salary::where('budget_project_id', $this->id)->get()
            ->map(function ($salary) {
                $project = Project::where('id', $salary->project)->first();
                return [
                    'type' => $salary->type,
                    'project' =>  $project->name,  // Assuming 'name' is the column in projects table
                    'po' => $salary->po,
                    'expenses' => $salary->expenses,
                    'other_expense' => $salary->other_expense,
                    'description' => $salary->description,
                    'status' => $salary->status,
                    'cost_per_month' => $salary->cost_per_month,
                    'no_of_staff' => $salary->no_of_staff,
                    'overseeing_sites' => $salary->overseeing_sites,
                    'no_of_months' => $salary->no_of_months,
                    'total_cost' => $salary->total_cost,
                    'average_cost' => $salary->average_cost,
                    'approval_status' => $salary->approval_status,
                    'percentage_cost' => $salary->percentage_cost,
                    'visa_status' => $salary->visa_status
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
            'Expenses',
            'Other Expense',
            'Description',
            'Status',
            'Cost Per Month',
            'No of Staff',
            'Overseeing Sites',
            'No of Months',
            'Total Cost',
            'Average Cost',
            'Approval Status',
            'Percentage Cost',
            'Visa Status'
        ];
    }
}
