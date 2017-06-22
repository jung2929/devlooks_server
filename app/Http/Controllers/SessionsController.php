<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
	public function store(Request $request){
		$data = $request->json()->all();

		$messages = array(
			'email.required' => '이메일 혹은 비밀번호가 없습니다.',
			'email.email' => '이메일 형식이 올바르지 않습니다.',
			'email.max' => '이메일은 최대 30자리이하 입력해야합니다.',
			'password.min' => '비밀번호는 최소 6 자리이상 입력해야합니다.',
			'password.max' => '비밀번호는 최대 60자리이하 입력해야합니다.',
		);
		
		$validator = \Validator::make($data, [
			'email' => 'required|email|max:30',
			'password' => 'required|min:6|max:60',
		], $messages);
		
		if ($validator->fails()){
			#return $this->makeJson(false, $validator->errors(), 400);
			return $this->makeJson(false, $validator->messages()->first(), 200);
		}

		$result = \DB::select('SELECT COUNT(*) AS CNT FROM users WHERE email = ? AND password = ?',
				[$data['email'], $data['password']]);
		
		if ($result[0]->CNT == 0){
			return $this->makeJson(false, array(array('name'=>'해당하는 이메일 혹은 비밀번호가 일치하지 않습니다.', 'phoneNumber'=>'')), 200);
		}

		$user_info = \DB::select('SELECT name, phone_number AS phoneNumber
						FROM users 
						WHERE email = ? 
						AND password = ?',
				[$data['email'], $data['password']]);

		return $this->makeJson(true, $user_info, 200);
	}
}
