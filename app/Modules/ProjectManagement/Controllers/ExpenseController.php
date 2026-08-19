<?php

namespace Modules\ProjectManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Expense;
use App\Modules\ProjectManagement\Resources\ExpenseResource;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('project')->get();
        return ExpenseResource::collection($expenses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $expense = Expense::create($validated);

        return (new ExpenseResource($expense->load('project')))
            ->response();
    }

    public function show(Expense $expense)
    {
        return new ExpenseResource($expense->load('project'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'project_id' => 'sometimes|required|exists:projects,id',
            'title' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric|min:0',
            'date' => 'sometimes|required|date',
        ]);

        $expense->update($validated);

        return new ExpenseResource($expense->load('project'));
    }

    public function destroy(Expense $expense)
    {
        $expense->load('project');

        $expense->delete();

        return response()->json([
            'message' => 'Data deleted successfully',
        ]);
    }
}
