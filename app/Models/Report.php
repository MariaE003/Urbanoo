<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    protected $fillable = ['user_id','category_id','title','description','latitude','longitude','status'];    
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
    public function votes(){
        return $this->hasMany(Vote::class);
    }
    public function images(){
        return $this->hasMany(ReportImage::class);
    }

}
