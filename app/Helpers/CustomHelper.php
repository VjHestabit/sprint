<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomHelper{

    const ADMIN = 999;
    const STUDENT = 100;
    const TEACHER = 101;

    public static $userType = [
        self::STUDENT => 'Student',
        self::TEACHER => 'Teacher',
    ];

    const ASSIGNED = 1;
    const UNASSIGNED = 2;

    public static $studentStatus = [
        self::ASSIGNED => 'Assigned',
        self::UNASSIGNED => 'Un Assigned'
    ];

    public static $studentBtnStatus = [
        self::ASSIGNED => 'btn-success',
        self::UNASSIGNED => 'btn-danger'
    ];

    const APPROVE = 1;
    const NOTAPPROVE = 2;

    public static $getUserStatus = [
        self::APPROVE => 'Approved',
        self::NOTAPPROVE => 'Not Approved'
    ];

    public static $getUserBtnStatus = [
        self::APPROVE => 'btn-success',
        self::NOTAPPROVE => 'btn-danger'
    ];

    const LESSTHAN1= 1;
    const ONETOTWO = 2;
    const TWOTOTHREE = 3;
    const THREETOFOUR = 4;
    const MORETHAN5 = 5;

    public static $getTeacherExp = [
        self::LESSTHAN1 => "Less than 1 Year",
        self::ONETOTWO => "1 to 2 Year",
        self::TWOTOTHREE => "2 to 3 Year",
        self::THREETOFOUR => "3 to 4 Year",
        self::MORETHAN5 => "More than 5 Year"
    ];

    const MATH = 1;
    const SCIENCE = 2;
    const ENGLISH = 3;
    const HISTORY = 4;
    const COMPUTER = 5;

    public static $getSubjectList = [
        self::MATH => 'Maths',
        self::SCIENCE => 'Science',
        self::ENGLISH => 'English',
        self::HISTORY => 'History',
        self::COMPUTER => 'Computers'
    ];

    public static function notificationCount(){
        $count = 0;
        $data = Notification::where('notifiable_id',Auth::user()->id)->whereNull('read_at')->count();
        if($data){
            $count = $data;
        }
        return $count;
    }

    public static function notificationData(){
        $data = array();
        $notify = Notification::where('notifiable_id',Auth::user()->id)->whereNull('read_at')->get();
        if($notify){
            $data = $notify;
        }
        return $data;
    }

    public static function uploadImage($image, $chkext = false)
    {
        $imageArray = array("png", "jpg", "jpeg", "gif", "bmp");
        $imagename = "";
        if($image) {
            $imageext = $image->getClientOriginalExtension();
            $imgname = $image->getClientOriginalName();
            if (!in_array($imageext, $imageArray) && $chkext) {
                return "";
            }
            $imagename = rand(100, 999) . '_' . time() . '.' . $imageext;
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $imagename);
        }
        return $imagename;
    }
}
?>
