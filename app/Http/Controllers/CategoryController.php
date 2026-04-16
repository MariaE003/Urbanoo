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
        $cate=$this->categorieService->allCategories();
        return response()->json(['data'=>$cate]);
    }
    
}
