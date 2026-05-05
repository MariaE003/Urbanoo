<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(){
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }
        return view('admin.services.index');
    }

    public function listJson(){
        return response()->json(
            Service::orderBy('name')->get()
        );
    }
    public function store(Request $request){
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $service = Service::create([
            'name' => $validated['name'],
        ]);
        return response()->json([
            'message' => 'service ajoute',
            'service' => $service,
        ], 201);
    }

    public function update(Request $request, $id){
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $service = Service::findOrFail($id);
        $service->update([
            'name' => $validated['name'],
        ]);
        return response()->json([
            'message' => 'service modifie',
            'service' => $service,
        ]);
    }

    public function destroy($id){
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $service = Service::findOrFail($id);
        Report::where('service_id', $service->id)->update([
            'service_id' => null,
        ]);
        $service->delete();
        return response()->json([
            'message' => 'service supprime',
        ]);
    }
}
