<?php

namespace App\Http\Controllers\api\v1\admin\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\trait\image;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

use App\Models\QuestionIssues;

class QuestionIssuesController extends Controller
{
    public function __construct(private QuestionIssues $question_issues){}
    protected $issuesRequest = [
        'title',
        'description',
        'status',
    ];
    use image;

    public function show(){
        // https://bdev.elmanhag.shop/admin/Settings/questionIssues
        $question_issues = $this->question_issues->get();

        return response()->json([
            'question_issues' => $question_issues
        ]);
    }

    public function add(Request $request){
        // https://bdev.elmanhag.shop/admin/Settings/questionIssues/add
        // Keys
        // title, description, status, thumbnail
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'status' => 'required|boolean',
        ]);
        if ($validator->fails()) { // if Validate Make Error Return Message Error
            return response()->json([
                'error' => $validator->errors(),
            ],400);
        }
        $issues_data = $request->only($this->issuesRequest); // Get data of question request
        $thumbnail = $this->upload($request,'thumbnail','admin/questionIssues/thumbnail'); // Upload Thumbnail
        if (!empty($thumbnail) && $thumbnail != null) {
            $issues_data['thumbnail'] = $thumbnail;
        }
        $this->question_issues->create($issues_data); // create question issues

        return response()->json([
            'success' => 'You add data success'
        ]);
    }

    public function modify(Request $request, $id){ 
        // https://bdev.elmanhag.shop/admin/Settings/questionIssues/update/{id}
        // Keys
        // title, description, status, thumbnail
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'status' => 'required|boolean',
        ]);
        if ($validator->fails()) { // if Validate Make Error Return Message Error
            return response()->json([
                'error' => $validator->errors(),
            ],400);
        }
        $issues_data = $request->only($this->issuesRequest); // Get data of question request
        $thumbnail = $this->upload($request,'thumbnail','admin/questionIssues/thumbnail'); // Upload Thumbnail
        if (!empty($thumbnail) && $thumbnail != null) {
            $issues_data['thumbnail'] = $thumbnail;
        }
        $question_issues = $this->question_issues
        ->where('id', $id)
        ->first(); // Get question issue
        $this->deleteImage($question_issues->thumbnail);
        $question_issues->update($issues_data); // Update question issues

        return response()->json([
            'success' => 'You update data success'
        ]);
    }

    public function delete($id){
        // https://bdev.elmanhag.shop/admin/Settings/questionIssues/delete/{id}
        $this->question_issues
        ->where('id', $id)
        ->delete();

        return response()->json([
            'success' => 'You delete data success'
        ]);
    }

}
