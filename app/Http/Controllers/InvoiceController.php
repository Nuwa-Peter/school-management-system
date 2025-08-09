<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Stream;
use App\Models\FeeStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $invoices = Invoice::with('student')
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('unique_id', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        return view('finance.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // We need a way to select students. A search box would be good.
        // For now, we'll just pass all students. For a large school, this should be an AJAX search.
        $students = User::where('role', 'student')->orderBy('last_name')->get();
        return view('finance.invoices.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'academic_year' => 'required|string|max:255',
            'term' => 'required|string|max:255',
            'due_date' => 'required|date',
        ]);

        $student = User::with('streams.classLevel')->find($request->user_id);
        if (!$student || !$student->streams->first()) {
            return back()->with('error', 'Student is not assigned to a class.');
        }

        $classLevel = $student->streams->first()->classLevel;

        // Find all applicable fee structures for the student's class
        $feeStructures = FeeStructure::where('class_level_id', $classLevel->id)
            ->where('academic_year', $request->academic_year)
            ->get();

        if ($feeStructures->isEmpty()) {
            return back()->with('error', 'No fee structures found for the student\'s class and academic year.');
        }

        DB::beginTransaction();
        try {
            // Calculate total amount
            $totalAmount = $feeStructures->sum('amount');

            // Create the invoice
            $invoice = Invoice::create([
                'user_id' => $student->id,
                'total_amount' => $totalAmount,
                'due_date' => $request->due_date,
                'academic_year' => $request->academic_year,
                'term' => $request->term,
                'status' => 'unpaid',
            ]);

            // Create invoice items
            foreach ($feeStructures as $structure) {
                $invoice->items()->create([
                    'fee_structure_id' => $structure->id,
                    'description' => $structure->feeCategory->name,
                    'amount' => $structure->amount,
                ]);
            }

            DB::commit();

            return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create invoice. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load(['student', 'items.feeStructure.feeCategory', 'payments.recordedBy']);
        return view('finance.invoices.show', compact('invoice'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        // For safety, only allow deletion of unpaid invoices.
        if ($invoice->status !== 'unpaid') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Cannot delete an invoice that has payments or is not in unpaid status.');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Export the specified invoice to PDF.
     */
    public function exportPdf(Invoice $invoice)
    {
        $invoice->load(['student', 'items', 'payments']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.invoices.invoice_pdf', compact('invoice'));
        return $pdf->download('invoice-' . $invoice->id . '.pdf');
    }

    /**
     * Export the specified invoice to Excel.
     */
    public function exportExcel(Invoice $invoice)
    {
        $invoice->load(['student', 'items', 'payments']);
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\InvoiceExport($invoice), 'invoice-' . $invoice->id . '.xlsx');
    }
}
