<?php

namespace App\Imports;

use App\Models\BudgetProject;
use App\Models\CapitalExpenditure;
use App\Models\DirectCost;
use App\Models\FacilityCost;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CapitalImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
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
        $bd = BudgetProject::findOrFail($this->bg_id);
        $capitalExpenditure = new CapitalExpenditure();

        $capitalExpenditure->budget_project_id =  $this->bg_id;
        $capitalExpenditure->type = $row['type'];
        $capitalExpenditure->project =  $bd->project_id;
        $capitalExpenditure->po = $row['po'];
        $capitalExpenditure->expenses =  $row['expense'];
        $capitalExpenditure->description = $row['description'];
        $capitalExpenditure->status = $row['status'];
        $capitalExpenditure->total_number = $row['total_number'];
        $capitalExpenditure->cost = floatval($row['cost']);
        $capitalExpenditure->total_cost = $row['total_number'] * floatval($row['cost']);
        // Save the record to the database
        $capitalExpenditure->save();

        return;
    }

    public function rules(): array
    {
        return [
            'type' => 'nullable',
            'project' => 'nullable',
            'po' => 'nullable',
            'expense' => 'nullable',
            'description' => 'nullable',
            'status' => 'nullable',
            'total_number' => 'nullable',
            'cost' => 'nullable',
        ];
    }
}
