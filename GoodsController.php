<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;     //访问DB类 针对数据库操作
use Illuminate\Support\Facades\Input;  //获取get，post的值
use Illuminate\Support\Facades\Cookie; //获取cookie的命名空间
use Illuminate\Support\Facades\Session;//获取session命名空间
use Illuminate\Support\Facades\Storage;//上传文件类

class GoodsController extends Controller
{
	public function index(){
		//return view('');
	}
	
	//查询
	public function searchdo(){
		$search=Input::post('search');    //  $search=Input::post('search','123');   相当于三元运算符   search是前台的name  123是前台没有传递数据时的替代值
		$arr1=\DB::select("select * from press where gname='{$search}'");   //查询之前最好先访问表  $a=\DB::table('customer'); 
		$arr2=\DB::select("select * from goods where name like ");    //where('user_name', 'like', '%'.$search['value'].'%');
	}
	
	
	
	//添加
	public function add(){
		return view('home/add');
	}
	
	public function adddo(){   //怎么联表查
		//添加数据
		var_dump($_POST);
	}
	
	
	//文件上传
	public function upload(){
		return view('home\uploads');
	}
	public function uploaddo(Request $request){    //将获取的数据全部保存至数据库
		//上传文件处理  怎么获取上传的文件  怎么存储  获取当前时间戳  怎么存储当前商品的id   表单提交数据怎么接收
		if($request->isMethod('POST')){
			//使用input类获取数据
			$gname=Input::post('gname');
			$content=Input::post('content');
			$price=Input::post('price');
			$time=date('Y-m-d H:i:s');
			
			$file=$request->file('picture');
			if($file->isValid()){	
                //获取原文件名  
                $originalName = $file->getClientOriginalName();

                //扩展名  
                $ext = $file->getClientOriginalExtension();  

                //文件类型  
                $type = $file->getClientMimeType(); 
				
                //临时绝对路径  
                $realPath = $file->getRealPath();

				//配置上传后的文件名   这个存到数据库中
                $filename = date('Y-m-d-H-i-S').'-'.uniqid().'-'.$ext;

				//将文件保存至本地项目storage/app/public/uploads内
                $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));
				
				//获取文件路径保存至数据库中   具体本地文件放在public目录下
				$path="/uploads/{$filename}";
				//$path = $request->file('picture')->store('uploads');	
				if($bool){
					$res=DB::insert("insert into goods (gname,content,picture,price,time) values(?,?,?,?,?)",["{$gname}","{$content}","{$path}","{$price}","{$time}"]);
					if($res){
						$res2=DB::select("select picture from goods");  
						return view('home\picture',['arr'=>$res2]);
					}
				}else{
					//return false;
				}
            }
			//return view('');
		}else{
			
		}
	}
	
	
	//删除
	public function delete(){
		//获取当前id
	}
	
	
	//修改
	public function update(){
		return view('home/update');
	}
	public function updatedo(){
		//接受数据 处理
		var_dump($_POST);
	}
}
