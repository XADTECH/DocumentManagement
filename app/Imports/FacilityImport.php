<?php

namespace App\Imports;

use App\Models\BudgetProject;
use App\Models\DirectCost;
use App\Models\FacilityCost;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class FacilityImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
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
        // Create a new facility cost record
        $facility = new FacilityCost();
        $facility->direct_cost_id = $directCost->id;
        $facility->type = $row['type'];
        $facility->project = $bd->project_id;
        $facility->po = $row['po'];
        $facility->expenses =  $row['expenses_head'];
        $facility->cost_per_month = $row['cost_per_month'] ?? 0;
        $facility->description = $row['description'];
        $facility->status = $row['status'];
        $facility->no_of_staff =  $row['no_of_staff'] ?? 0; // Map to your model attribute
        $facility->no_of_months = $row['months'] ?? 0;
        $facility->budget_project_id = $this->bg_id;;

        $facility->calculateTotalCost();
        $facility->calculateAverageCost();
        // $facility->calculatePercentageCost();
        $facility->save();

        // Update total direct cost
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
            'description' => 'nullable',
            'status' => 'nullable',
            'cost_per_month' => 'nullable',
            'no_of_months' => 'nullable',
            'no_of_staff' => 'nullable',
            'No_of_months' => 'nullable',
        ];
    }
}
