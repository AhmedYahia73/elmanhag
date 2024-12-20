<?php
namespace Admin;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\v1\lang\LangController;

use App\Http\Controllers\api\v1\admin\student\CreateStudentController;
use App\Http\Controllers\api\v1\admin\student\StudentsDataController;
use App\Http\Controllers\api\v1\admin\student\SubjectController as Stu_SubjectController;

use App\Http\Controllers\api\v1\admin\Category\CategoryController;
use App\Http\Controllers\api\v1\admin\Category\CreateCategoryController;

use App\Http\Controllers\api\v1\admin\Subject\SubjectController;
use App\Http\Controllers\api\v1\admin\Subject\CreateSubjectController;

use App\Http\Controllers\api\v1\admin\chapter\ChapterController;
use App\Http\Controllers\api\v1\admin\chapter\CreateChapterController;

use App\Http\Controllers\api\v1\admin\materials\MaterialsController;

use App\Http\Controllers\api\v1\admin\lesson\LessonMaterialController;
use App\Http\Controllers\api\v1\admin\lesson\CreateLessonController;

use App\Http\Controllers\api\v1\admin\bundle\BundleController;
use App\Http\Controllers\api\v1\admin\bundle\CreateBundleController;

use App\Http\Controllers\api\v1\admin\question\QuestionController;
use App\Http\Controllers\api\v1\admin\question\CreateQuestionController;

use App\Http\Controllers\api\v1\admin\homework\HomeworkController;
use App\Http\Controllers\api\v1\admin\homework\CreateHomeworkController;

use App\Http\Controllers\api\v1\admin\discount\DiscountController;
use App\Http\Controllers\api\v1\admin\discount\CreateDiscountController;

use App\Http\Controllers\api\v1\admin\promocode\PromocodeController;
use App\Http\Controllers\api\v1\admin\promocode\CreatePromocodeController;

use App\Http\Controllers\api\v1\admin\live\LiveController;
use App\Http\Controllers\api\v1\admin\live\CreateLiveController;

use App\Http\Controllers\api\v1\admin\popup\PopupController;
use App\Http\Controllers\api\v1\admin\popup\CreatePopupController;

use App\Http\Controllers\api\v1\admin\payment\PaymentController;

use App\Http\Controllers\api\v1\admin\parent\ParentController;

use App\Http\Controllers\api\v1\admin\teacher\TeacherController;
use App\Http\Controllers\api\v1\admin\teacher\SubjectController as T_SubjectController;
use App\Http\Controllers\api\v1\admin\teacher\LiveController as T_LiveController;

use App\Http\Controllers\api\v1\admin\issues\QuestionIssuesController;
use App\Http\Controllers\api\v1\admin\issues\VideoIssuesController;

use App\Http\Controllers\api\v1\admin\admin\AdminController;

use App\Http\Controllers\api\v1\admin\role\RoleController;

use App\Http\Controllers\api\v1\admin\complaint\ComplaintController;

use App\Http\Controllers\api\v1\admin\revision\RevisionController;
use App\Http\Controllers\api\v1\admin\revision\CreateRevisionController;

use App\Http\Controllers\api\v1\admin\live_recorded\LiveRecordedController;
use App\Http\Controllers\api\v1\admin\live_recorded\CreateLiveRecordedController;

use App\Http\Controllers\api\v1\admin\affilate\AffilateController;
use App\Http\Controllers\api\v1\admin\affilate\Aff_CommessionController;
use App\Http\Controllers\api\v1\admin\affilate\Aff_PayoutController;
use App\Http\Controllers\api\v1\admin\affilate\Aff_PaymentMethodController;
use App\Http\Controllers\api\v1\admin\affilate\Aff_BonusController;
use App\Http\Controllers\api\v1\admin\affilate\AffVideoGroupController;
use App\Http\Controllers\api\v1\admin\affilate\AffVideoController;

use App\Http\Controllers\api\v1\admin\settings\RelationController;
use App\Http\Controllers\api\v1\admin\settings\CountriesController;
use App\Http\Controllers\api\v1\admin\settings\CitiesController;
use App\Http\Controllers\api\v1\admin\settings\JobsController;
use App\Http\Controllers\api\v1\admin\settings\PaymentMethodsController;
use App\Http\Controllers\api\v1\admin\settings\QuestionIssuesController as ListQuestionIssuesController;
use App\Http\Controllers\api\v1\admin\settings\VideoIssuesController as ListVideoIssuesController;


Route::middleware(['auth:sanctum','IsAdmin'])->group(function () {
    // Start Module Translation
    Route::prefix('translation')->group(function () {
        Route::controller(LangController::class)->group(function () {
            Route::get('/', 'languages_api')->name('translation.link');
        });
    });

    // Start Module Issues
    Route::prefix('issues')->middleware('can:isIssue')->group(function () {
        Route::controller(QuestionIssuesController::class)
        ->prefix('question')->group(function () {
            Route::get('/', 'show')->name('issues.show_questions');
            Route::put('/seen/{id}', 'seen')->name('issues.seen_question_issue');
        });
        Route::controller(VideoIssuesController::class)
        ->prefix('video')->group(function () {
            Route::get('/', 'show')->name('issues.show_videos');
            Route::put('/seen/{id}', 'seen')->name('issues.seen_video_issue');
        });
    });

    // Start Module Student Sign UP
    Route::prefix('student')->middleware('can:isStudent')->group(function () {
        Route::controller(StudentsDataController::class)->group(function () {
            Route::get('/', 'show')->name('student.show');
            Route::get('/student/{id}', 'student_item')->name('student.student');
            Route::get('/loginHistory/{id}', 'login_history')->name('student.login_history');
            Route::post('/purchases', 'purchases')->name('student.purchases');
            Route::post('/purchasesData', 'purchases_data')->name('student.purchases_data');
            Route::post('/addPurchases', 'add_purchases')->name('student.add_purchases');
            Route::post('/subjectProgress/{id}', 'subject_progress')->name('student.subject_progress');
        });
        Route::controller(CreateStudentController::class)->group(function () {
            Route::post('/add', 'store')->name('student.add');
            Route::post('/update/{id}', 'modify')->name('student.modify');
            Route::delete('/delete/{id}', 'delete')->name('student.delete');
            Route::put('/status/{id}', 'status')->name('student.status');
        });
        Route::controller(Stu_SubjectController::class)->prefix('progress')->group(function () {
            Route::get('/{id}', 'progress')->name('student.progress');
        });
        
    });

    // Start Parent Module
    Route::prefix('parent')->middleware('can:isParent')->group(function () {
        Route::controller(ParentController::class)->group(function () {
            Route::get('/', 'show')->name('parent.show');
        });
    });

    // Start Popup Module
    Route::prefix('popup')->middleware('can:isPopup')->group(function () {
        Route::controller(PopupController::class)->group(function () {
            Route::get('/', 'show')->name('popup.show');
        });
        Route::controller(CreatePopupController::class)->group(function(){
            Route::post('/add', 'create')->name('popup.add');
            Route::put('/update/{id}', 'modify')->name('popup.update');
            Route::delete('/delete/{id}', 'delete')->name('popup.delete');
        });
    });
  

    // Start Revision Module
    Route::prefix('revisions')->middleware('can:isRevision')->group(function () {
        Route::controller(RevisionController::class)->group(function(){
            Route::get('/', 'show')->name('revision.show');
        }); 
        Route::controller(CreateRevisionController::class)->group(function(){
            Route::post('/add', 'create')->name('revision.add');
            Route::post('/file/add/{id}', 'add_video')->name('revision.add_video');
            Route::delete('/file/delete/{id}', 'delete_video')->name('revision.delete_video');
            Route::post('/update/{id}', 'modify')->name('revision.update');
            Route::delete('/delete/{id}', 'delete')->name('revision.delete');
        });
    });

    // Start Admin Module
    Route::prefix('admins')->middleware('can:isAdmins')->group(function () {
        Route::controller(AdminController::class)->group(function(){
            Route::get('/', 'show')->name('admins.show');
            Route::put('/status/{id}', 'status')->name('admins.status');
            Route::get('/admin/{id}', 'admin')->name('admins.admin');
            Route::post('/add', 'add')->name('admins.add');
            Route::post('/update/{id}', 'modify')->name('admins.update');
            Route::delete('/delete/{id}', 'delete')->name('admins.delete');
        }); 
    });
    
    // Start Admin Role Module
    Route::prefix('adminRole')->middleware('can:isAdminRoles')->group(function () {
        Route::controller(RoleController::class)->group(function(){
            Route::get('/', 'show')->name('role.show');
            Route::get('/role/{id}', 'role')->name('role.role');
            Route::post('/add', 'add')->name('role.add');
            Route::post('/update/{id}', 'modify')->name('role.update');
            Route::delete('/delete/{id}', 'delete')->name('role.delete');
        }); 
    });

    // Start Category Module
    Route::prefix('category')->middleware('can:isCategories')->group(function () {
        Route::controller(CategoryController::class)->group(function(){
            Route::get('/', 'show')->name('category.show');
        });
        Route::controller(CreateCategoryController::class)->group(function(){
            Route::post('/add', 'create')->name('category.add');
            Route::put('/update/{id}', 'modify')->name('category.update');
            Route::delete('/delete/{id}', 'delete')->name('category.delete');
        });
    });

    // Start Subject Module
    Route::prefix('subject')->middleware('can:isSubjects')->group(function () {
        Route::controller(SubjectController::class)->group(function(){
            Route::get('/', 'show')->name('subject.show');
            Route::get('/progress/{id}', 'subject_progress')->name('subject.progress');
        });
        Route::controller(CreateSubjectController::class)->group(function(){
            Route::post('/add', 'create')->name('subject.add');
            Route::post('/update/{id}', 'modify')->name('subject.update');
            Route::delete('/delete/{id}', 'delete')->name('subject.delete');
        });
    });
    
    // Start Chapter Module
    Route::prefix('chapter')->middleware('can:isChapters')->group(function () {
        Route::controller(ChapterController::class)->group(function(){
            Route::get('/{subject_id}', 'show')->name('subject.show');
        });
        Route::controller(CreateChapterController::class)->group(function(){
            Route::post('/add/{sub_id}', 'create')->name('subject.add');
            Route::put('/update/{id}', 'modify')->name('subject.update');
            Route::delete('/delete/{id}', 'delete')->name('subject.delete');
        });
    });
    
    // Start Lesson Module
    Route::prefix('lesson')->middleware('can:isLessons')->group(function () {
        Route::controller(CreateLessonController::class)->group(function(){
            Route::get('/{lesson_id}', 'lesson')->name('lesson.lesson');
            Route::post('/add/{sub_id}', 'create')->name('lesson.add');
            Route::put('/update/{id}', 'modify')->name('lesson.update');
            Route::delete('/delete/{id}', 'delete')->name('lesson.delete');

            Route::put('/switch/{id}', 'switch')->name('lesson.switch');
        });
    });

    // Start Module Materials
    Route::prefix('materials')->middleware('can:isMaterial')->group(function () {
        Route::controller(MaterialsController::class)->group(function(){
            Route::get('/', 'view')->name('material.view');
        });
    });

    // Start Lesson Material Module
    Route::prefix('lessonMaterial')->middleware('can:isLessons')->group(function () {
        Route::controller(LessonMaterialController::class)->group(function(){
            Route::get('/{lesson_id}', 'show')->name('lessonMaterial.show');
            Route::post('/add/{lesson_id}', 'create')->name('lessonMaterial.add');
            Route::delete('/delete/{id}', 'delete')->name('lessonMaterial.delete');
        });
    });

    // Start Bundle Module
    Route::prefix('bundle')->middleware('can:isBundles')->group(function () {
        Route::controller(BundleController::class)->group(function(){
            Route::get('/', 'show')->name('bundle.show');
            Route::get('/{id}', 'bundle_data')->name('bundle.bundle_data');
        });
        Route::controller(CreateBundleController::class)->group(function(){
            Route::post('/add', 'create')->name('bundle.add');
            Route::post('/update/{id}', 'modify')->name('bundle.update');
            Route::delete('/delete/{id}', 'delete')->name('bundle.delete');
        });
    });

    // Start Question Module
    Route::prefix('question')->middleware('can:isQuestions')->group(function () {
        Route::controller(QuestionController::class)->group(function(){
            Route::get('/', 'show')->name('question.show');
        });
        Route::controller(CreateQuestionController::class)->group(function(){
            Route::post('/add', 'create')->name('question.add');
            Route::put('/update/{id}', 'modify')->name('question.update');
            Route::delete('/delete/{id}', 'delete')->name('question.delete');
        });
    });

    // Start H.W Module
    Route::prefix('homework')->middleware('can:isHw')->group(function () {
        Route::controller(HomeworkController::class)->group(function(){
            Route::get('/', 'show')->name('homework.show');
        });
        Route::controller(CreateHomeworkController::class)->group(function(){
            Route::post('/add', 'create')->name('homework.add');
            Route::put('/update/{id}', 'modify')->name('homework.update');
            Route::delete('/delete/{id}', 'delete')->name('homework.delete');
        });
    });
    
    // Start Discount Module
    Route::prefix('discount')->middleware('can:isDiscounts')->group(function () {
        Route::controller(DiscountController::class)->group(function(){
            Route::get('/', 'show')->name('discount.show');
        });
        Route::controller(CreateDiscountController::class)->group(function(){
            Route::post('/add', 'create')->name('discount.add');
            Route::put('/update/{id}', 'modify')->name('discount.update');
            Route::delete('/delete/{id}', 'delete')->name('discount.delete');
        });
    });
    
    // Start Promo Code Module
    Route::prefix('promoCode')->middleware('can:isPromocode')->group(function () {
        Route::controller(PromocodeController::class)->group(function(){
            Route::get('/', 'show')->name('promoCode.show');
        });
        Route::controller(CreatePromocodeController::class)->group(function(){
            Route::post('/add', 'create')->name('promoCode.add');
            Route::put('/update/{id}', 'modify')->name('promoCode.update');
            Route::delete('/delete/{id}', 'delete')->name('promoCode.delete');
        });
    });
    
    // Start Payment Module
    Route::prefix('payment')->middleware('can:isPayments')->group(function() {
        Route::controller(PaymentController::class)->group(function(){
            Route::get('/pendding', 'pendding_payment')->name('payment.pendding');
            Route::put('/pendding/rejected/{id}', 'rejected_payment')->name('payment.rejected');
            Route::put('/pendding/approve/{id}', 'approve_payment')->name('payment.approve');

            Route::get('/', 'payments')->name('payment.payments');
        });
    });

    // Start Teacher Module
    Route::prefix('teacher')->middleware('can:isTeachers')->group(function() {
        Route::controller(TeacherController::class)->group(function(){
            Route::get('/', 'teachers_list')->name('teachers.list');
            Route::get('/loginHistory/{id}', 'login_history')->name('teachers.login_history');
            Route::get('/profile/{id}', 'teacher_profile')->name('teachers.profile');
            Route::put('/profile/update/{id}', 'teacher_profile_update')->name('teachers.profile_update');
            Route::post('/add', 'add_teacher')->name('teachers.add_teacher');
            Route::delete('/delete/{id}', 'delete')->name('teachers.delete');
            Route::put('/status/{id}', 'status')->name('teachers.status');
        });
        Route::controller(T_SubjectController::class)->prefix('subjects')->group(function(){
            Route::post('/', 'view')->name('teachers.subject.view');
            Route::post('/add', 'add')->name('teachers.subject.add');
            Route::delete('/delete/{id}', 'delete')->name('teachers.subject.delete');
        });
        Route::controller(T_LiveController::class)->prefix('lives')->group(function(){
            Route::post('/', 'view')->name('teachers.live.view');
        });
    });
    
    // Start Complaints Module
    Route::prefix('complaint')->middleware('can:isComplaint')->group(function() {
        Route::controller(ComplaintController::class)->group(function(){
            Route::get('/', 'pendding')->name('complaint.pendding');
            Route::get('/history', 'history')->name('complaint.history');
            Route::put('/active/{id}', 'active')->name('complaint.active');
        });
    });
    
    // Start Live Module
    Route::prefix('live')->middleware('can:isLive')->group(function() {
        Route::controller(LiveController::class)->group(function(){
            Route::get('/', 'show')->name('live.show');
            Route::get('/{id}', 'live')->name('live.live');
        });
        Route::controller(CreateLiveController::class)->group(function(){
            Route::post('/add', 'create')->name('live.add');
            Route::put('/update/{id}', 'modify')->name('live.update');
            Route::delete('/delete/{id}', 'delete')->name('live.delete');
        });
    });

    // Start Recorded Live Module
    Route::prefix('recordedLive')->middleware('can:isLive')->group(function () {
        Route::controller(LiveRecordedController::class)->group(function () {
            Route::get('/', 'view')->name('recordedLive.show');
            Route::get('/live_item/{id}', 'live_item')->name('recordedLive.show_item');
        });
        Route::controller(CreateLiveRecordedController::class)->group(function () {
            Route::post('/add', 'add')->name('recordedLive.add');
            Route::post('/update/{id}', 'modify')->name('recordedLive.update');
            Route::delete('/delete/{id}', 'delete')->name('recordedLive.delete');
        });
    });
    
    
    // Start Affilate Module
    Route::prefix('affilate')->middleware('can:isAffilate')->group(function() {
        Route::controller(AffilateController::class)->group(function(){
            Route::get('/', 'affilate')->name('affilate.affilate');
            Route::get('/loginHistory/{id}', 'login_history')->name('affilate.login_history');
            Route::post('/add', 'create')->name('affilate.add');
            Route::put('/update/{id}', 'modify')->name('affilate.update');
            Route::put('/banned/{id}', 'banned')->name('affilate.banned');
            Route::put('/unblock/{id}', 'unblock')->name('affilate.unblock');
            Route::get('/signups/{affilate_id}', 'signups')->name('affilate.signups');
            Route::get('/revenue/{affilate_id}', 'revenue')->name('affilate.revenue');
            Route::get('/payout/{affilate_id}', 'payout')->name('affilate.payout');
            Route::post('/payout/approve/{payout_id}', 'approve_payout')->name('affilate.approve_payout');
            Route::post('/payout/rejected/{payout_id}', 'rejected_payout')->name('affilate.rejected_payout');
            Route::get('/payout_history/{affilate_id}', 'payout_history')->name('affilate.payout_history');
        });

        Route::controller(AffVideoGroupController::class)
        ->prefix('groups')->group(function(){
            Route::get('/', 'show')->name('affilate_groups.show');
            Route::post('/add', 'add')->name('affilate_groups.add');
        });

        Route::controller(AffVideoController::class)
        ->prefix('videos')->group(function(){
            Route::get('/{id}', 'show')->name('affilate_videos.show');
            Route::post('/add', 'add')->name('affilate_videos.add');
            Route::post('/update/{id}', 'modify')->name('affilate_videos.update');
            Route::delete('/delete/{id}', 'delete')->name('affilate_videos.delete');
        });

        Route::controller(Aff_CommessionController::class)->group(function(){
            Route::get('/commession', 'commession')->name('affilate.commession');
            Route::put('/addCommession', 'add_commession')->name('affilate.addCommession');
        });

        Route::controller(Aff_PayoutController::class)->group(function(){
            Route::get('/payouts', 'payouts')->name('affilate.payouts');
            Route::get('/payoutsHistory', 'payouts_history')->name('affilate.payouts_history');
        });

        Route::controller(Aff_PaymentMethodController::class)->group(function(){
            Route::get('/affilateMethod', 'affilate_method')->name('affilate.affilate_method');
            Route::get('/affilateMethod/{id}', 'payment_method')->name('affilate.payment_method');
            Route::post('/affilateMethodAdd', 'add')->name('affilate.affilate_method_add');
            Route::post('/affilateMethodUpdate/{id}', 'update')->name('affilate.affilate_method_update');
            Route::delete('/affilateMethodDelete/{id}', 'delete')->name('affilate.affilate_method_delete');
        });
        
        Route::controller(Aff_BonusController::class)->group(function(){
            Route::get('/bonus', 'show')->name('affilate.bonus');
            Route::get('/bonus/affilates', 'affilates')->name('affilate.bonus_affilates');
            Route::post('/bonus/add', 'add')->name('affilate.add_bonus');
            Route::put('/bonus/update/{id}', 'update')->name('affilate.update_bonus');
            Route::delete('/bonus/delete/{id}', 'delete')->name('affilate.delete_bonus');
        });
    });

    // Start Settings Module
    Route::prefix('Settings')->middleware('can:isSettings')->group(function () {
        // Start Parent Relations
        Route::prefix('relation')->group(function () {
            Route::controller(RelationController::class)->group(function(){
                Route::get('/', 'show')->name('relation.show');
                Route::post('/add', 'create')->name('relation.add');
                Route::put('/update/{id}', 'modify')->name('relation.update');
                Route::delete('/delete/{id}', 'delete')->name('relation.delete');
            });
        }); 
        // Start Countries
        Route::prefix('countries')->group(function () {
            Route::controller(CountriesController::class)->group(function(){
                Route::get('/', 'show')->name('countries.show');
                Route::post('/add', 'create')->name('countries.add');
                Route::put('/update/{id}', 'modify')->name('countries.update');
                Route::delete('/delete/{id}', 'delete')->name('countries.delete');
            });
        });
        // Start Cities
        Route::prefix('cities')->group(function () {
            Route::controller(CitiesController::class)->group(function(){
                Route::get('/', 'show')->name('countries.show');
                Route::post('/add', 'create')->name('countries.add');
                Route::put('/update/{id}', 'modify')->name('countries.update');
                Route::delete('/delete/{id}', 'delete')->name('countries.delete');
            });
        });
        // Start Jobs
        Route::prefix('jobs')->group(function () {
            Route::controller(JobsController::class)->group(function(){
                Route::get('/', 'show')->name('jobs.show');
                Route::post('/add', 'create')->name('jobs.add');
                Route::put('/update/{id}', 'modify')->name('jobs.update');
                Route::delete('/delete/{id}', 'delete')->name('jobs.delete');
            });
        });
        // Start Payment Methods
        Route::prefix('paymentMethods')->group(function () {
            Route::controller(PaymentMethodsController::class)->group(function(){
                Route::get('/', 'show')->name('payment_methods.show');
                Route::post('/add', 'create')->name('payment_methods.add');
                Route::post('/update/{id}', 'modify')->name('payment_methods.update');
                Route::delete('/delete/{id}', 'delete')->name('payment_methods.delete');
            });
        });
        // Start Question Issues
        Route::prefix('questionIssues')->group(function() {
            Route::controller(ListQuestionIssuesController::class)->group(function(){
                Route::get('/', 'show')->name('question_issues.show');
                Route::get('/issue/{id}', 'question_issue')->name('question_issues.question_issue');
                Route::put('/status/{id}', 'status')->name('question_issues.status');
                Route::post('/add', 'add')->name('question_issues.add');
                Route::put('/update/{id}', 'modify')->name('question_issues.update');
                Route::delete('/delete/{id}', 'delete')->name('question_issues.delete');
            });
        });
        // Start Video Issues
        Route::prefix('videoIssues')->group(function() {
            Route::controller(ListVideoIssuesController::class)->group(function(){
                Route::get('/', 'show')->name('video_issues.show');
                Route::get('/issue/{id}', 'video_issue')->name('video_issues.video_issue');
                Route::put('/status/{id}', 'status')->name('video_issues.status');
                Route::post('/add', 'add')->name('video_issues.add');
                Route::put('/update/{id}', 'modify')->name('video_issues.update');
                Route::delete('/delete/{id}', 'delete')->name('video_issues.delete');
            });
        });

    });
});
