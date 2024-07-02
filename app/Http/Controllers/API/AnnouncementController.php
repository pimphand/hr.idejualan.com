<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index(){
        $announcements = Announcement::latest('created_at')->get();
        foreach($announcements as $announcement){
            $announcement->image = url('storage/announcement/'.$announcement->image);
        }
        return response()->json([
            'status' => "success",
            'data' => $announcements
        ]);
    }
}
