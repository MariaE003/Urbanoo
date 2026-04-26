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
     public function getCategoryById($id){
          return $this->categorieRepo->findById($id);
     }
     public function updateCategory($id, $data){
          return $this->categorieRepo->update($id, $data);
     }
     public function deleteCategory($id){
          return $this->categorieRepo->delete($id);
     }
}

?>