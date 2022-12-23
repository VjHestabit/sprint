<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aRows = User::where('role_id','!=',CustomHelper::ADMIN)
                ->where('id','!=',Auth::user()->id)
                ->where('role_id','!=',CustomHelper::TEACHER);
        if(Auth::user()->role_id == CustomHelper::TEACHER){
            $aRows = $aRows->where('role_id',CustomHelper::STUDENT);
        }

        $aRows = $aRows->get();

        return view('student.index',compact('aRows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function approveStudent($id,$status)
    {
        $sUpdate = User::where('id',$id)->update(['status' => $status]);

        return redirect()->back()->with(['success','Student Approved Successfully']);
    }
}
