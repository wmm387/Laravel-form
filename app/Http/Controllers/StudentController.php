<?php
namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use Validator;

class StudentController extends Controller
{
    public function index()
    {
    	$students = Student::paginate(5);

        return view('student.index', [
        	'students' => $students,
        ]);
    }

    public function create(Request $request)
    {
    	$student = new Student();

    	if ($request->isMethod('POST')) {

    		//验证表达信息,并输出错误信息

    		//1. 控制器验证
    		// $this->validate($request, [
    		// 	'Student.name' => 'required|min:2|max:20',
    		// 	'Student.age' => 'required|integer',
    		// 	'Student.sex' => 'required|integer',
    		// ], [
    		// 	'required' => ':attribute 为必填项',
    		// 	'min' => ':attribute 长度过小',
    		// 	'max' => ':attribute 长度过大',
    		// 	'integer' => ':attribute 必须为整数',
    		// ], [
    		// 	'Student.name' => '姓名',
    		// 	'Student.age' => '年龄',
    		// 	'Student.sex' => '性别',
    		// ]);

    		//2. Validator类验证
    		$validator = Validator::make($request->input(), [
    			'Student.name' => 'required|min:2|max:20',
    			'Student.age' => 'required|integer',
    			'Student.sex' => 'required|integer',
    		], [
    			'required' => ':attribute 为必填项',
    			'min' => ':attribute 长度过小',
    			'max' => ':attribute 长度过大',
    			'integer' => ':attribute 必须为整数',
    		], [
    			'Student.name' => '姓名',
    			'Student.age' => '年龄',
    			'Student.sex' => '性别',
    		]);

    		if ($validator->fails()) {
    			return redirect()->back()->withErrors($validator)->withInput();
    		}

    		$data = $request->input('Student');

    		if (Student::create($data)) {
    			//带有数据的重定向操作
    			return redirect('student/index')->with('success', '添加成功!');
        	}else {
        		return redirect()->back();
        	}
    	}

    	return view('student.create', [
    		'student' => $student
    	]);
    }

    // public function save(Request $request)
    // {
    //     $data =  $request->input('Student');

    //     $student = new Student();
    //     $student->name = $data['name'];
    //     $student->age = $data['age'];
    //     $student->sex = $data['sex'];

    //     if ($student->save()) {
    //     	return redirect('student/index');
    //     }else {
    //     	return redirect()->back();
    //     }
    // }

    public function update(Request $request, $id)
    {
    	$student = Student::find($id);

    	if ($request->isMethod('POST')) {

    		$this->validate($request, [
    			'Student.name' => 'required|min:2|max:20',
    			'Student.age' => 'required|integer',
    			'Student.sex' => 'required|integer',
    		], [
    			'required' => ':attribute 为必填项',
    			'min' => ':attribute 长度过小',
    			'max' => ':attribute 长度过大',
    			'integer' => ':attribute 必须为整数',
    		], [
    			'Student.name' => '姓名',
    			'Student.age' => '年龄',
    			'Student.sex' => '性别',
    		]);

    		$data = $request->input('Student');
    		$student->name = $data['name'];
        	$student->age = $data['age'];
        	$student->sex = $data['sex'];

        	if ($student->save()) {
        		return redirect('student/index')->with('success', $id.'-修改成功!');
        	}else {
        		return redirect()->back();
        	}
    	}

    	return view('student.update', [
    		'student' => $student
    	]);
    }

    public function detail($id)
    {
    	$student = Student::find($id);

    	return view('student.detail', [
    		'student' => $student
    	]);
    }

    public function delete($id)
    {
    	$student = Student::find($id);

    	if ($student->delete()) {
    		return redirect('student/index')->with('success', $id . '删除成功');
    	}else {
    		return redirect('student/index')->with('error', $id . '删除失败');
    	}
    }
}