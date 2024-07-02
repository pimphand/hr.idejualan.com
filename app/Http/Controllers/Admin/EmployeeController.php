<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title="employees";
        $designations = Designation::get();
        $departments = Department::get();
        $employees = Employee::with('department','designation')->get();
        $summaries = [];
        $summaries['total_employees'] = Employee::count();
        $summaries['total_leader'] = $employees->where('role','leader')->count();
        $summaries['total_staff'] = $employees->where('role','staff')->count();
        $summaries['total_resign'] = Employee::onlyTrashed()->count();

        return view('backend.employees',
        compact('title','designations','departments','employees','summaries'));
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function list()
   {
       $title="employees";
       $designations = Designation::get();
       $departments = Department::get();
       $employees = Employee::with('department','designation')->get();
       
       return view('backend.employees-list',
       compact('title','designations','departments','employees'));
   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>'required|email|unique:employees',
            'phone'=>'nullable|max:15',
            'company'=>'required|max:200',
            'password' => 'required|min:6',
            'avatar'=>'file|image|mimes:jpg,jpeg,png,gif',
            'department'=>'required',
            'designation'=>'required',
            'birthday'=>'nullable',
            'role'=>'required',
            'leaves_quota'=>'required',
        ]);
        $imageName = Null;
        if ($request->hasFile('avatar')){
            $imageName = time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('storage/employees'), $imageName);
        }
        $uuid = $this->generateUUID();
        Employee::create([
            'uuid' =>$uuid,
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'password'=>bcrypt($request->password),
            'company'=>$request->company,
            'department_id'=>$request->department,
            'designation_id'=>$request->designation,
            'avatar'=>$imageName,
            'role'=>$request->role,
            'birthday'=>$request->birthday,
            'leaves_quota'=>$request->leaves_quota,
        ]);
        
        return back()->with('success',"Employee has been added");
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request,[
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>'required|email',
            'phone'=>'nullable|max:15',
            'password'=>'nullable|min:6',
            'company'=>'required|max:200',
            'avatar'=>'file|image|mimes:jpg,jpeg,png,gif',
            'department'=>'required',
            'designation'=>'required',
            'birthday'=>'nullable',
            'role'=>'required',
            'leaves_quota'=>'required',
        ]);
        if ($request->hasFile('avatar')){
            $imageName = time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('storage/employees'), $imageName);
        }else{
            $imageName = Null;
        }
        
        $employee = Employee::find($request->id);
        $employee->update([
            'uuid' => $employee->uuid,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => !empty($request->password) ? bcrypt($request->password) : $employee->password, // Keep the existing password if no new one is provided
            'company' => $request->company,
            'department_id' => $request->department,
            'designation_id' => $request->designation,
            'avatar' => $imageName,
            'role' => $request->role,
            'birthday' => $request->birthday,
            'leaves_quota' => $request->leaves_quota,
        ]);
        return back()->with('success',"Employee details has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $employee = Employee::find($request->id);
        $employee->delete();
        return back()->with('success',"Employee has been deleted");
    }

    public function generateUUID(){
        $business_id = Auth::user()->business_id;
        $date = date('my', strtotime(now()));
        $count = sprintf("%03d", Employee::where('business_id',$business_id)->count());
        return $business_id.$date.$count;
    }
}
