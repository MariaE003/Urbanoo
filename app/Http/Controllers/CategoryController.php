<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected $categorieService;
    public function __construct(CategoryService $categorieService){
        $this->categorieService=$categorieService;
    }
    public function index(){
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/');
        }
        $categories =$this->categorieService->allCategories();
        return view('admin.categories.index', compact('categories'));
    }
    // pour fetcher les cat
    public function listJson(){
        $categories = $this->categorieService->allCategories();
        return response()->json(['data' => $categories]);
    }
    public function create(){
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/');
        }
        return view('admin.categories.create');
    }

    public function store(Request $request){
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $this->categorieService->createCategory($validated);
        return redirect()->route('admin.categories.index');
    }

    public function edit($id){
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/');
        }
        $category = $this->categorieService->getCategoryById($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id){
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $this->categorieService->updateCategory($id, $validated);

        return redirect()->route('admin.categories.index');
    }
    public function destroy($id){
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/');
        }
        $this->categorieService->deleteCategory($id);
        return redirect()->route('admin.categories.index');
    }
    
}
