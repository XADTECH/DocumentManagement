<?php

namespace App\Http\Controllers;

use App\Models\BudgetProject;
use App\Models\BusinessClient;
use App\Models\BusinessUnit;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\User;
use App\Models\CashFlow;
use App\Models\DirectCost;
use App\Models\TotalBudgetAllocated;
use App\Models\InDirectCost;
use App\Models\CapitalExpenditure;
use Illuminate\Http\Request;
use PDF;
use Dompdf\Dompdf;
use Carbon\Carbon;

class PdfController extends Controller
{
    public function download($POID)
    {
        // Load the view
        $purchaseOrder = PurchaseOrder::where('po_number', $POID)->first(); // Use first() to get a single record
        $purchaseOrderItem = PurchaseOrderItem::where('purchase_order_id', $purchaseOrder->id)->first(); // Use first() to get a single record
        $budget = BudgetProject::where('id', $purchaseOrder->project_id)->first();
        $clients = BusinessClient::where('id', operator: $budget->client_id)->first();
        $units = BusinessUnit::where('id', $budget->unit_id)->first();
        $budgets = Project::where('id', $budget->project_id)->first();
        $requested = User::where('id', $purchaseOrder->requested_by)->first();
        $prepared = User::where('id', $purchaseOrder->prepared_by)->first();
        $utilization = $purchaseOrderItem->budget_utilization;
        $poStatus = $purchaseOrder->status;

        $balanceBudget = $budget->getRemainingBudget();
        $pdf = PDF::loadView('content.pages.pdf.pages-budget-project-summary-report', compact('purchaseOrder', 'budget', 'clients', 'units', 'budgets', 'requested', 'prepared', 'utilization', 'balanceBudget', 'poStatus', 'purchaseOrderItem'));

        // Download the PDF
        return $pdf->stream('test.pdf');
    }

    public function budgetSummary($POID)
    {
        $budget = BudgetProject::where('id', $POID)->first();
        $amounts = $budget->calculateTotalAmount();
        // return response($amounts);
        $clients = BusinessClient::where('id', operator: $budget->client_id)->first();
        $units = BusinessUnit::where('id', $budget->unit_id)->first();
        $project = Project::where('id', $budget->project_id)->first();
        $directCost = DirectCost::where('budget_project_id', $budget->id)->first();
        $capitalExp = CapitalExpenditure::where('budget_project_id', $budget->id)->first();
        $totalCapExp = $capitalExp->sumTotalCost($budget->id);

        // Example start and end dates from your $budget object
        $start_date = Carbon::parse($budget->start_date);
        $end_date = Carbon::parse($budget->end_date);

        // Calculate the difference in months
        $months = $start_date->diffInMonths($end_date);
        $years = $months / 12;

        // return response($directCost);
        $inDirectCost = InDirectCost::where('budget_project_id', $budget->id)->first();
        $user = User::where('id', $budget->manager_id)->first();
        $totalDirectCost = $directCost->calculateTotalDirectCost();
        $totalInDirectCost = $inDirectCost->calculateTotalIndirectCost();
        $pdf = PDF::loadView('content.pages.pdf.project_approval', compact('budget', 'clients', 'units', 'project', 'user', 'amounts', 'totalDirectCost', 'totalInDirectCost', 'totalCapExp', 'months', 'years'));
        // Download the PDF
        return $pdf->stream('test.pdf');
    }

    public function downloadCashFlow($POID){

        $budget = BudgetProject::where('id', $POID)->first();
        $clients = BusinessClient::where('id',$budget->client_id)->first();
        $units = BusinessUnit::where('id', $budget->unit_id)->first();
        $project = Project::where('id', $budget->project_id)->first();
        $user = User::where('id', $budget->manager_id)->first();

        $cashFlows = CashFlow::where('reference_code', $budget->reference_code)->get();

        $allocatedBudgets = TotalBudgetAllocated::where('budget_project_id', $budget->id)->first();
        $dpm = $allocatedBudgets->total_dpm; 
        $lpo = $allocatedBudgets->total_lpo;
        $allocatedBudget = $allocatedBudgets->allocated_budget; 
        $remainingBudget = $allocatedBudget - ($dpm + $lpo);

        //return response($allocatedBudgets);

        $pdf = PDF::loadView('content.pages.pdf.cash-flow-report', compact('budget','clients','units','project','user','cashFlows','allocatedBudgets','dpm','lpo','allocatedBudget','remainingBudget'));
        return $pdf->stream('test.pdf');
    }
}
