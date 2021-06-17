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

// Route::any('api/login', 'Home\Api_zjy_Controller@login');
// Route::any('api/Getsubject', 'Home\Api_zjy_Controller@Getsubject');
// Route::any('api/Twomonc', 'Home\Api_zjy_Controller@Twomonc');
// Route::any('api/Getclassinfo','Home\Api_zjy_Controller@Getclassinfo');//获取班级信息
// Route::any('api/GetBrainstorming','Home\Api_zjy_Controller@GetBrainstorming');//头脑风暴答案
Route::any('api/GetWorkaw', 'Home\Api_zjy_Controller@GetWorkaw'); //获取作业答案
// Route::any('api/Getexam','Home\Api_zjy_Controller@Getexam');//获取考试答案
// Route::any('api/Signin','Home\Api_zjy_Controller@Signin');//签到

// Route::any('api/Getallwork','Home\Api_zjy_Controller@Getallwork');
// Route::any('api/Getallexam','Home\Api_zjy_Controller@Getallexam');
// Route::any('api/Nosigninall','Home\Api_zjy_Controller@Nosigninall');
// Route::any('api/Getnowclass','Home\Api_zjy_Controller@Getnowclass');

// Route::any('api/Getdsaw','Home\Api_zjy_Controller@Getdsaw');//获取讨论答案。。。


// Route::any('api/GetnewHomeworkaw','Home\Api_zjy_Controller@NEWGetdsaw');//新的获取作业答案
// Route::any('api/GetnewExamaw','Home\Api_zjy_Controller@GetnewExamaw');//新的获取考试答案

// Route::any('api/Resawdata','Home\Api_zjy_Controller@Resawdata');//返回考试id
// Route::any('api/Reswkawdata','Home\Api_zjy_Controller@Reswkawdata');//返回作业id
// Route::any('api/Restextdata','Home\Api_zjy_Controller@Restextdata');//返回测验id

// Route::any('api/Resallaw','Home\Api_zjy_Controller@Resallaw');//获取考试和作业单个题目答案

// Route::any('api/zskz','Home\Api_zjy_Controller@zskz');//职教云刷课 -分
// Route::any('api/moocf','Home\Api_zjy_Controller@moocf');//慕课刷课 -分
// Route::any('api/mooczxkc','Home\Api_zjy_Controller@mooczxkc');//慕课课程



/*
    职教管家APP php后端2020年8月20日更新
    A：Luan Shi Liu Nian
*/

Route::any('newapi/Login', 'Home\Newzjy_Controller@Login'); //登录验证
Route::any('newapi/huanbang', 'Home\Newzjy_Controller@huanbang'); //换绑账号
Route::any('newapi/Get_all_subjects', 'Home\Newzjy_Controller@Get_all_subjects'); //获取所有科目
Route::any('newapi/Get_course_list', 'Home\Newzjy_Controller@Get_course_list'); //获取课程列表
Route::any('newapi/Get_Class_info', 'Home\Newzjy_Controller@Get_Class_info'); //获取课程详细信息
Route::any('newapi/GetBrainstorming', 'Home\Newzjy_Controller@GetBrainstorming'); //获取头脑风暴答案
Route::any('newapi/Getdsaw', 'Home\Newzjy_Controller@Getdsaw'); //获取讨论答案。。。

//
Route::any('newapi/Signin', 'Home\Newzjy_Controller@Signin'); //签到
Route::any('newapi/Getnowclass', 'Home\Newzjy_Controller@Getnowclass'); //获取今天未签到的课程







Route::any('newapi/Getallwork', 'Home\Newzjy_Controller@Getallwork'); //获取所选科目的所有作业
Route::any('newapi/Getallexam', 'Home\Newzjy_Controller@Getallexam'); //获取所选科目的所有考试

Route::any('newapi/mooczxkc', 'Home\Newzjy_Controller@mooczxkc'); //慕课课程

Route::any('newapi/zskz', 'Home\Newzjy_Controller@zskz'); //职教云刷课 -分
Route::any('newapi/moocf', 'Home\Newzjy_Controller@moocf'); //慕课刷课 -分

Route::any('newapi/MoocWork', 'Home\Newzjy_Controller@moocwork'); //慕课作业指纹
Route::any('newapi/MoocTest', 'Home\Newzjy_Controller@mooctest'); //慕课测验指纹
Route::any('newapi/MoocExam', 'Home\Newzjy_Controller@moocexam'); //慕课考试指纹



Route::any('newapi/GethomeWorkId', 'Home\Newzjy_Controller@GethomeWorkId'); //获取作业答案
Route::any('newapi/GetexamId', 'Home\Newzjy_Controller@GetexamId'); //获取考试答案
Route::any('newapi/GettextId', 'Home\Newzjy_Controller@GettextId'); //返回测验答案

Route::any('newapi/GethomeWorkInfo', 'Home\Newzjy_Controller@GethomeWorkInfo'); //获取作业学生作答信息
Route::any('newapi/GethomeWorkRecord', 'Home\Newzjy_Controller@GethomeWorkRecord'); //获取作业作答记录
Route::any('newapi/GethomeWorkAnswer', 'Home\Newzjy_Controller@GethomeWorkAnswer'); //获取作业作答内容  题库作业  与附件作业 通用

Route::any('newapi/GetexamInfo', 'Home\Newzjy_Controller@GetexamInfo'); //获取考试学生作答信息
Route::any('newapi/GetexamAnswer', 'Home\Newzjy_Controller@GetexamAnswer');//获取考试作答内容  题库考试  与附件考试 通用