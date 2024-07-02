<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index(){
        $title = "Announcement";
        $announcements = Announcement::get();
        return view('backend.announcement',compact('title','announcements'));
    }

    public function destroy(Request $request){
        $announcement = Announcement::find($request->id);
        $announcement->delete();
        return redirect()->back()->with('success','Announcement deleted successfully');
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('storage/announcement'), $imageName);
        Announcement::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return redirect()->back()->with('success','Announcement created successfully');
    }

    public function update(Request $request){
        $request->validate([
            'title' => 'string|nullable',
            'description' => 'string|nullable',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        $announcement = Announcement::find($request->id);
        $announcement->title = $request->title;
        $announcement->description = $request->description;
        if($request->image){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('storage/announcement'), $imageName);
            $announcement->image = $imageName;
        }
        $announcement->updated_at = date('Y-m-d H:i:s');
        $announcement->save();
        return redirect()->back()->with('success','Announcement updated successfully');
    }
}
