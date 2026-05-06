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
    // recupere le nom service(reports par service) si exist
    private function ajouterNomService($reports){
        foreach ($reports as $report) {
            $report->service_name = null;

            if ($report->service) {
                $report->service_name = $report->service->name;
            }
        }

        return $reports;
    }

    public function store(Request $request){
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',//applique cette validation a chaque image dans le tab images
        ]);
        // dd($request->all());
        // dd( auth()->id());
        $validated['user_id']=auth()->id();
        $report=$this->serviceReport->createReport($validated);
        // return redirect()->back();
        if ($request->hasFile('images')) {//si user envoyer des imgs
            foreach ($request->file('images') as $image) {
                $path = $image->store('reports', 'public');//enregisterer dans doccier storage dans  public
                $report->images()->create([//sauvegarder leur chemain in db
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
        $reports = $this->ajouterNomService($reports);
        // dd($reports);
        // return view('reports.index',compact('reports'));
        return response()->json($reports);
    }
    public function updateStatus(Request $request, $id){
        $validated=$request->validate([
            'status'=>'nullable|in:pending,in_progress,resolved',
            'service_id' => 'nullable|exists:services,id',
        ]);
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        $data = [];
        if (isset($validated['status']) && $validated['status'] !== '') {
            $data['status'] = $validated['status'];
        }
        $requestData = $request->all();
        if (array_key_exists('service_id', $requestData)) {
            $data['service_id'] = $requestData['service_id'];
            if ($data['service_id'] === '') {
                $data['service_id'] = null;
            }
        }
        if (empty($data)) {
            return response()->json([
                'message' => 'aucune modification envoyee'
            ]);
        }

        $report = $this->serviceReport->getReportById($id);
        $report->update($data);
        $report->service_name = null;

        if ($report->service) {
            $report->service_name = $report->service->name;
        }

        return response()->json([
            'message' => 'report modifier',
            'report'=>$report,
        ]);
    }

    public function destroy($id){
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }
        $report = $this->serviceReport->getReportById($id);
        $isAdmin = auth()->user()->role === 'admin';
        $isOwner = (int) $report->user_id === (int) auth()->id();

        if (!$isAdmin && !$isOwner) {
            return response()->json([
                'message' => 'non autorise'
            ], 403);
        }
        if (!$isAdmin && $report->status !== 'pending') {
            return response()->json([
                'message' => "vous ne pouvez pas supprimer ce signalement que lorsqu'il est en attente."
            ], 403);
        }
        $this->serviceReport->deleteReport($id);

        return response()->json([
            'message' => 'report supprimer avce succes'
        ]);
    }
    public function lastReports(){
        $reports = $this->serviceReport->lastReports();
        $reports = $this->ajouterNomService($reports);

        return response()->json($reports);
    }
}
