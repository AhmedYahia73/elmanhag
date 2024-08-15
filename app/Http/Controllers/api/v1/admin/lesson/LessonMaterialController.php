<?php

namespace App\Http\Controllers\api\v1\admin\lesson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\trait\image;

use App\Models\LessonResource;

class LessonMaterialController extends Controller
{
    use image;

    public function show( $lesson_id ){
        $materials = LessonResource::where('lesson_id', $lesson_id)
        ->get();
        foreach ($materials as $item) {
            $item->file = url('storage/app/public/' . $item->file);
        }

        return response()->json([
            'materials' => $materials
        ]);
    }
    
    public function create( Request $request, $lesson_id ){
        //Keys 
        // voice, voice_link
        // voice_source لما يكون link فقط
        // video, video_link
        // video_source  لما يكون link فقط
        // pdf, pdf_link
        // pdf_source لما يكون link فقط

        // Add Voice Source
        if ( isset($request->voice) ) {
            // Upload voice
            $voice_paths = $this->upload($request, 'voice', 'admin/lessons/voice');
            // Create lesson source
            LessonResource::create([
                'type' => 'voice', 
                'source' => 'upload', 
                'file' => $voice_paths, 
                'lesson_id' => $lesson_id,
            ]);
        }

        // if source voice and link
        if ( isset($request->voice_link) ) {
            // Create Source
            LessonResource::create([
                'type' => 'voice', 
                'source' => $request->voice_source, 
                'link' => $request->voice_link, 
                'lesson_id' => $lesson_id,
            ]);
        }
        
        // Add Video Source
        if ( isset($request->video) ) {
            // Upload video 
            $video_paths = $this->upload($request, 'video', 'admin/lessons/video');
            // Create Source
            LessonResource::create([
                'type' => 'video', 
                'source' => 'upload', 
                'file' => $video_paths,
                'lesson_id' => $lesson_id,
            ]);
        }

        // if source video and link
        if ( isset($request->video_link) ) {
            // Create Source
            LessonResource::create([
                'type' => 'video', 
                'source' => $request->video_source, 
                'link' => $request->video_link, 
                'lesson_id' => $lesson_id,
            ]);
        }
        
        // Add PDF Source
        if ( isset($request->pdf) ) {
            // Upload pdf 
            $pdf_paths = $this->upload($request, 'pdf', 'admin/lessons/pdf'); 
            // Create Source
            LessonResource::create([
                'type' => 'pdf', 
                'source' => 'upload', 
                'file' => $pdf_paths,
                'lesson_id' => $lesson_id,
            ]); 
        }

        // if source pdf and link
        if ( isset($request->pdf_link) ) {
            // Create Source
            LessonResource::create([
                'type' => 'pdf', 
                'source' => $request->pdf_source, 
                'link' => $request->pdf_link, 
                'lesson_id' => $lesson_id,
            ]);
        }
    

        return response()->json([
            'success' => 'You add data success'
        ]);
    }
    
    public function delete( $id ){
        $material = LessonResource::
        where('id', $id)
        ->first();
        $this->deleteImage($material->file);
        $material->delete();

        return response()->json([
            'success' => 'You delete data success'
        ]);
    }
}