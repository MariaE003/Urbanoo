<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function all(){
        return response()->json(auth()->user()->notifications);
    }
    public function unread(){
        return response()->json(auth()->user()->unreadNotifications);
    }
    public function markAsReads($id){
        $notif=auth()->user()->notifications()->findOrFail($id);
        $notif->markAsRead();
        return response()->json([
            'message'=>'notif marquer comme lue'
        ]);
    }
    public function markAllAsRead(){
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json([
            'message' => 'toutes les notification ont ete marquer comme lues'
        ]);
    }
}
