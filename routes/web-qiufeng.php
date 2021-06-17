<?php

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
    return "没事别瞎看！请你吃老八秘制小汉堡！";
});

Route::any('api/login', 'Home\Api_zjy_Controller@login');
Route::any('api/Getsubject', 'Home\Api_zjy_Controller@Getsubject');
Route::any('api/Twomonc', 'Home\Api_zjy_Controller@Twomonc');
Route::any('api/Getclassinfo','Home\Api_zjy_Controller@Getclassinfo');//获取班级信息
Route::any('api/GetBrainstorming','Home\Api_zjy_Controller@GetBrainstorming');//头脑风暴答案
Route::any('api/GetWorkaw','Home\Api_zjy_Controller@GetWorkaw');//获取作业答案
Route::any('api/Getexam','Home\Api_zjy_Controller@Getexam');//获取考试答案
Route::any('api/Signin','Home\Api_zjy_Controller@Signin');//签到

Route::any('api/Getallwork','Home\Api_zjy_Controller@Getallwork');
Route::any('api/Getallexam','Home\Api_zjy_Controller@Getallexam');
Route::any('api/Nosigninall','Home\Api_zjy_Controller@Nosigninall');
Route::any('api/Getnowclass','Home\Api_zjy_Controller@Getnowclass');

Route::any('api/Getdsaw','Home\Api_zjy_Controller@Getdsaw');//获取讨论答案。。。


Route::any('api/GetnewHomeworkaw','Home\Api_zjy_Controller@NEWGetdsaw');//新的获取作业答案
Route::any('api/GetnewExamaw','Home\Api_zjy_Controller@GetnewExamaw');//新的获取考试答案

Route::any('api/Resawdata','Home\Api_zjy_Controller@Resawdata');//返回考试id
Route::any('api/Reswkawdata','Home\Api_zjy_Controller@Reswkawdata');//返回作业id
Route::any('api/Restextdata','Home\Api_zjy_Controller@Restextdata');//返回测验id

Route::any('api/Resallaw','Home\Api_zjy_Controller@Resallaw');//获取考试和作业单个题目答案

Route::any('api/zskz','Home\Api_zjy_Controller@zskz');//职教云刷课 -分
Route::any('api/moocf','Home\Api_zjy_Controller@moocf');//慕课刷课 -分
Route::any('api/mooczxkc','Home\Api_zjy_Controller@mooczxkc');//慕课课程



/*
    职教管家APP php后端2020年8月20日更新
    User：Luan Shi Liu Nian
*/

Route::any('newapi/Login','Home\Newzjy_Controller@Login');//登录验证
Route::any('newapi/Setuser','Home\Newzjy_Controller@Setuser');//登录验证


Route::any('newapi/Get_all_subjects', 'Home\Newzjy_Controller@Get_all_subjects');//获取所有科目
Route::any('newapi/Get_course_list', 'Home\Newzjy_Controller@Get_course_list');//获取课程列表
Route::any('newapi/Get_Class_info', 'Home\Newzjy_Controller@Get_Class_info');//获取课程详细信息
Route::any('newapi/Get_awanswer','Home\Newzjy_Controller@Get_awanswer');//获取考试和作业的答案