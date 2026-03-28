<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    protected $fillable = ['user_id','category_id','title','description','latitude','longitude','status'];    
    public function categorie(){
        return $this->belongsTo(Category::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function comment(){
        return $this->hasMany(Comment::class);
    }
    public function vote(){
        return $this->hasMany(Vote::class);
    }
    public function reportimage(){
        return $this->hasMany(ReportImage::class);
    }

}
