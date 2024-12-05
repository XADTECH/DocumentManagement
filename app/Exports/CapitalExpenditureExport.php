<?php

namespace App\Exports;

use App\Models\CapitalExpenditure;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CapitalExpenditureExport implements FromCollection, WithHeadings
{
    protected $id;

    // Constructor to receive the id
    public function __construct($id)
    {
        $this->id = $id;
    }
    public function collection()
    {
        return CapitalExpenditure::where('budget_project_id', $this->id)->get()->map(function ($salary) {
            $project = Project::where('id', $salary->project)->first();
            return [
                'type' => $salary->type,
                'project' =>  $project->name,
                'po' => $salary->po,
                'expense' => $salary->expenses,
                'description' => $salary->description,
                'status' => $salary->status,
                'total_number' => $salary->total_number,
                'cost' => $salary->cost,
            ];
        });;
    }

    public function headings(): array
    {
        // Add your column headings here
        return [
            'Type',
            'Project',
            'PO',
            'Expense',
            'Description',
            'Status',
            'Total Number',
            'Cost',
        ];
    }
}
