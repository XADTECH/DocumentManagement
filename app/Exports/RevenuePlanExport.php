<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\RevenuePlan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RevenuePlanExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
    public function collection()
    {
        return RevenuePlan::where('budget_project_id', $this->id)->select()->get()->map(function ($salary) {
            $project = Project::where('id', $salary->project)->first();
            return [
                'type' => $salary->type,
                'project' =>  $project->name,  // Assuming 'name' is the column in projects table
                'contract' => $salary->contract,
                'description' => $salary->description,
                'amount' => $salary->amount,
                'total_profit' => $salary->total_profit,
                'net_profit_before_tax' => $salary->net_profit_before_tax,
                'tax' => $salary->tax,
                'net_profit_after_tax' => $salary->net_profit_after_tax,
                'profit_percentage' => $salary->profit_percentage,
                'approval_status' => $salary->approval_status,
            ];
        });;
    }

    public function headings(): array
    {
        // Add your column headings here
        return [
            'Type',
            'Project',
            'Contract',
            'Description',
            'Amount',
            'Total Profit',
            'Net profit before tax',
            'Tax',
            'Net Profit After Tax',
            'Profit Percentage',
            'Approval Status'

        ];
    }
}
