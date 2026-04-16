<?php

namespace App\Http\Controllers;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $serviceReport;
    public function __construct(ReportService $serviceReport){
        $this->serviceReport=$serviceReport;
    }

    public function store(Request $request){
        $validated = $request->validate([
        'title' => 'required',
        'description' => 'required',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'category_id' => 'required|exists:categories,id',
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);
    // dd($request->all());
    // dd( auth()->id());
        $validated['user_id']=auth()->id();
        $report=$this->serviceReport->createReport($validated);
        // return redirect()->back();
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reports', 'public');

                $report->images()->create([
                    'image_path' => $path,
                ]);
            }
        }
        return response()->json([
            'message' => 'report created',
            'report'=>$report,
        ] );
    }
    public function index(){
        $reports=$this->serviceReport->allReports();
        // return view('reports.index',compact('reports'));
        return response()->json($reports);
    }
    public function updateStatus(Request $request, $id){
        $validated=$request->validate([
            'status'=>'required|string',
        ]);
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        $report = $this->serviceReport->updateReportStatus($id,$validated);
        return response()->json([
            'message' => 'status modifier',
            'report'=>$report,
        ]);
    }
}