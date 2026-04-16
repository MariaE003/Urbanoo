<?php
namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService{
   protected $categorieRepo;
   public function __construct(CategoryRepository $categorieRepo){
        $this->categorieRepo=$categorieRepo;
   }
   public function allCategories(){
        return $this->categorieRepo->getAll();
   }
   public function createCategory($data){
        return $this->categorieRepo->create($data);
   }
}

?>