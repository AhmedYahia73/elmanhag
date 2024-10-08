<?php

namespace App\Http\Controllers\api\v1\admin\Subject;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\trait\image;
use App\trait\translaion;
use App\Http\Requests\api\admin\subject\SubjectRequest;

use App\Models\subject;

class CreateSubjectController extends Controller
{
    use image;
    use translaion;
    protected $subjectRequest = [
        'name',
        'ar_name',
        'price',
        'category_id',
        'url',
        'description',
        'education_id',
        'status',
        'semester',
        'expired_date',
        'tags'
    ];

    public function create( SubjectRequest $request ){
        // https://bdev.elmanhag.shop/admin/subject/add
        // Keys
        // name, ar_name, price, category_id, url, description, education_id
        // status, semester, expired_date, demo_video, cover_photo, thumbnail
        $data = $request->only($this->subjectRequest); // Get Data
        $demo_video = $this->upload($request,'demo_video','admin/subjects/demo_video'); // Upload Demo Video
        $cover_photo = $this->upload($request,'cover_photo','admin/subjects/cover_photo'); // Upload Cover Photo
        $thumbnail = $this->upload($request,'thumbnail','admin/subjects/thumbnail'); // Upload thumbnail
        $this->translate($data['name'], $data['ar_name']); // Translate in file json
        $data['demo_video'] = $demo_video;
        $data['cover_photo'] = $cover_photo;
        $data['thumbnail'] = $thumbnail;
        subject::create($data); // Create item at database

        return response()->json([
            'success' => 'You Added Success', 200
        ]);
    }
    
    public function modify( SubjectRequest $request, $id ){
        // https://bdev.elmanhag.shop/admin/subject/update/{id}
        // Keys
        // name, ar_name, price, category_id, url, description, education_id
        // status, semester, expired_date, demo_video, cover_photo, thumbnail
        $data = $request->only($this->subjectRequest); // Get Data
        $subject = subject::where('id', $id)
        ->first();
        $demo_video = null;
        $cover_photo = null;
        $thumbnail = null;
        if ($request->demo_video != $subject->demo_video_url) {
            $demo_video = $this->upload($request,'demo_video','admin/subjects/demo_video');// Upload new video
        }
        if ($request->cover_photo != $subject->cover_photo_url) {
            $cover_photo = $this->upload($request,'cover_photo','admin/subjects/cover_photo');// Upload new Cover Photo
        }
        if ($request->thumbnail != $subject->thumbnail_url) {
            $thumbnail = $this->upload($request,'thumbnail','admin/subjects/thumbnail');// Upload new thumbnail
        }
        $this->translate($data['name'], $data['ar_name']); // Translate in file json
        // If new Video is found delete old image
        if ( !empty($demo_video) && $demo_video != null ) {
            $this->deleteImage($subject->demo_video);
            $data['demo_video'] =$demo_video; // Image Value From traid Image 
        }
        // If new image is found delete old image
        if ( !empty($cover_photo) && $cover_photo != null ) {
            $this->deleteImage($subject->cover_photo);
            $data['cover_photo'] =$cover_photo; // Image Value From traid Image 
        }
        // If new image is found delete old image
        if ( !empty($thumbnail) && $thumbnail != null ) {
            $this->deleteImage($subject->thumbnail);
            $data['thumbnail'] =$thumbnail; // Image Value From traid Image 
        }
        $subject->update($data);

        return response()->json([
            'demo_video' => $demo_video,
            'cover_photo' => $cover_photo,
            'thumbnail' => $thumbnail,
        ]);
    }
    
    public function delete( $id ){
        // https://bdev.elmanhag.shop/admin/subject/delete/{id}
        $subject = subject::where('id', $id)
        ->first();    
        $this->deleteImage($subject->demo_video);
        $subject->delete();

        return response()->json([
            'success' => 'You Are Deleted Success', 200
        ]);
    }
}
