<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/init', function () {
    Schema::dropIfExists('student');
    if (!Schema::hasTable('student')) {
        Schema::create('student', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
        });
    }
    echo "init table.";
});
Route::get('/getall', function () {
    $student = DB::select('select * from student');
    echo "records:<br/>";      
    foreach($student as $a_student)
    {
        echo "record:".$a_student->id."|".$a_student->name."|".$a_student->email."<br/>";
    }  
});
Route::get('/insert/{name}/{email}',function($name,$email){
    $timestamp = time();
    DB::insert('insert into student (id, name,email) 
        values (?, ?,?)', 
        [$timestamp,$name.'_'.$timestamp,$email.'_'.$timestamp]);
    echo "record inserted.<br/>";
});
Route::get('/getone/{id}',function($id){ 
    $result = DB::select('select * from student where id = ?', [$id]);
    foreach($result as $a_result)
    {
        echo "record:<br/>".$a_result->id."|".$a_result->name."|".$a_result->email."<br/>";
    }  
});
Route::get('/update/{id}/{name}/{email}',function($id,$name,$email){
    $affected = DB::update(
    'update student set name = ?, email=? where id = ?',
    [$name,$email,$id]);
    echo "record updated:".$affected;
});
Route::get('/update1/{id}/{name}/{email}',function($id,$name,$email){
    $affected = DB::table('student')
      ->where('id',$id)
      ->update([
         'name'=>$name,
         'email'=>$email,
      ]);
    echo "record updated:".$affected;
});
Route::get('/delete/{id}',function($id){
    $deleted = DB::delete(
    'delete from student where id = ?',
    [$id]);
    echo "record deleted:".$deleted;
});
Route::get('/delete1/{id}',function($id){
    $deleted = DB::table('student')
    ->where('id', '=',$id)
    ->delete();
    echo "record deleted:".$deleted;
});