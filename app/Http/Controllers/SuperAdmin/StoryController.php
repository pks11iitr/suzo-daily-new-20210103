<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class StoryController extends Controller
{
     public function index(Request $request){

               $stories=Story::where(function($stories) use($request){
                   $stories->where('title','LIKE','%'.$request->search.'%');
                 });

            if($request->ordertype)
                $stories=$stories->orderBy('created_at', $request->ordertype);

         $stories=$stories->paginate(10);
            return view('admin.story.view',['stories'=>$stories]);
              }


    public function create(Request $request){
            return view('admin.story.add');
               }

   public function store(Request $request){
               $request->validate([
                  			'isactive'=>'required',
                  			'title'=>'required',
                  			'description'=>'required',
                  			'image'=>'required|image'
                               ]);

          if($stories=Story::create([
                      'isactive'=>$request->isactive,
                      'description'=>$request->description,
                      'title'=>$request->title,
                      'image'=>'a']))
            {
                $stories->saveImage($request->image, 'stories');
             return redirect()->route('story.list')->with('success', 'stories has been created');
            }
             return redirect()->back()->with('error', 'stories create failed');
          }

    public function edit(Request $request,$id){
             $newsupdate = Story::findOrFail($id);
             return view('admin.story.edit',['newsupdate'=>$newsupdate]);
             }

    public function update(Request $request,$id){
             $request->validate([
                            'isactive'=>'required',
                  			'description'=>'required',
                  			'title'=>'required',
                  			'image'=>'image'
                               ]);
             $newsupdate = Story::findOrFail($id);
          if($request->image){
			 $newsupdate->update([
                      'isactive'=>$request->isactive,
                      'description'=>$request->description,
                      'title'=>$request->title,
                      'image'=>'a']);
             $newsupdate->saveImage($request->image, 'newsupdate');
        }else{
             $newsupdate->update([
                      'isactive'=>$request->isactive,
                      'title'=>$request->title,
                      'description'=>$request->description
                           ]);
             }
          if($newsupdate)
             {
           return redirect()->route('story.list')->with('success', 'stories has been updated');
              }
           return redirect()->back()->with('error', 'stories update failed');

      }


     public function delete(Request $request, $id){
         Story::where('id', $id)->delete();
           return redirect()->back()->with('success', 'stories has been deleted');
        }
  }
