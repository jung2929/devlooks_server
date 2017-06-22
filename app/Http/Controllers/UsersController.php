<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
	public function store(Request $request){
		$data = $request->json()->all();
		
		$messages = array(
			'name.required' => '이름을 적어주시기 바랍니다.',
			'name.max' => '이름은 최대 한글 10자, 영어 20자까지 가능합니다.',
			'email.required' => '이메일을 적어주시기 바랍니다.',
			'email.email' => '이메일 형식이 올바르지 않습니다.',
			'email.max' => '이메일은 최대 30자리이하 입력해야합니다.',
			'email.unique' => '이미 존재하는 이메일입니다.',
			'password.required' => '비밀번호를 적어주시기 바랍니다.',
			'password.min' => '비밀번호는 최소 6자리이상 입력해야합니다.',
			'password.max' => '비밀번호는 최대 60자리이하 입력해야합니다.',
			'password.confirmed' => '비밀번호 확인 값이 일치하지 않습니다.',
			'password_confirmation.required' => '비밀번호 확인을 적어주시기 바랍니다.',
			'password_confirmation.min' => '비밀번호 확인은 최소 6자리이상 입력해야합니다.',
			'password_confirmation.max' => '비밀번호 확인은 최대 60자리이하 입력해야합니다.',
			'phone_number.required' => '핸드폰번호를 입력해주시기 바랍니다.',
			'phone_number.min' => '핸드폰번호는 최소 10자리이상 입력해야합니다.',
			'phone_number.max' => '핸드폰번호는 최대 11자리이하 입력해야합니다.',
		);
		
		$validator = \Validator::make($data, [
			'name' => 'required|max:20',
			'email' => 'required|email|max:30|unique:users',
			'password' => 'required|min:6|max:60|confirmed',
			'password_confirmation' => 'required|min:6|max:60',
			'phone_number' => 'required|min:10|max:11',
		], $messages);

		if ($validator->fails()){
			return $this->makeJson(false, $validator->messages()->first(), 200);
		}

		$user = \App\User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => $data['password'],
			'phone_number' => $data['phone_number'],
		]);
		
		return $this->makeJson(true, "회원가입에 성공하였습니다.", 200);
	}

	public function update(Request $request){
		$data = $request->json()->all();
		
		$messages = array(
			'name.required' => '이름을 적어주시기 바랍니다.',
			'name.max' => '이름은 최대 한글 10자, 영어 20자까지 가능합니다.',
			'email.required' => '이메일을 적어주시기 바랍니다.',
			'email.email' => '이메일 형식이 올바르지 않습니다.',
			'email.max' => '이메일은 최대 30자리이하 입력해야합니다.',
			'password.required' => '비밀번호를 적어주시기 바랍니다.',
			'password.min' => '비밀번호는 최소 6자리이상 입력해야합니다.',
			'password.max' => '비밀번호는 최대 60자리이하 입력해야합니다.',
			'new_password.required' => '비밀번호를 적어주시기 바랍니다.',
			'new_password.min' => '비밀번호는 최소 6자리이상 입력해야합니다.',
			'new_password.max' => '비밀번호는 최대 60자리이하 입력해야합니다.',
			'new_password.confirmed' => '비밀번호 확인 값이 일치하지 않습니다.',
			'new_password_confirmation.required' => '비밀번호 확인을 적어주시기 바랍니다.',
			'new_password_confirmation.min' => '비밀번호 확인은 최소 6자리이상 입력해야합니다.',
			'new_password_confirmation.max' => '비밀번호 확인은 최대 60자리이하 입력해야합니다.',
			'phone_number.required' => '핸드폰번호를 입력해주시기 바랍니다.',
			'phone_number.min' => '핸드폰번호는 최소 10자리이상 입력해야합니다.',
			'phone_number.max' => '핸드폰번호는 최대 11자리이하 입력해야합니다.',
		);
		
		$validator = \Validator::make($data, [
			'name' => 'required|max:20',
			'email' => 'required|email|max:30',
			'password' => 'required|min:6|max:60',
			'new_password' => 'required|min:6|max:60|confirmed',
			'new_password_confirmation' => 'required|min:6|max:60',
			'phone_number' => 'required|min:10|max:11',
		], $messages);

		if ($validator->fails()){
			return $this->makeJson(false, $validator->messages()->first(), 200);
		}

		$user_count = \DB::select('SELECT COUNT(*) AS CNT 
					FROM users 
					WHERE email = ? 
					AND password = ?',
				[$data['email'], $data['password']]);

		if ($user_count[0]->CNT == 0){
			return $this->makeJson(false, "해당하는 이메일 혹은 비밀번호가 존재하지 않습니다.", 200);
		}

		$update_user = \App\User::where('email', $data['email'])->where('password', $data['password'])->update(['password'=>$data['new_password_confirmation'], 'name'=>$data['name'], 'phone_number'=>$data['phone_number']]);
		
		if ($update_user == 0) {
			return $this->makeJson(false, "회원 정보 수정에 실패하였습니다.", 200);
		}
		
		$update_favorites_category = \App\FavoritesCategory::where('email', $data['email'])->where('password', $data['password'])->update(['password'=>$data['new_password_confirmation']]);
		/*	
		if ($update_favorites_category == 0) {
			return $this->makeJson(false, "회원 정보 카테고리 즐겨찾기 수정에 실패하였습니다.", 200);
		}
		*/
		$update_favorites_content = \App\FavoritesContent::where('email', $data['email'])->where('password', $data['password'])->update(['password'=>$data['new_password_confirmation']]);
		/*
		if ($update_favorites_content == 0) {
			return $this->makeJson(false, "회원 정보 즐겨찾기 수정에 실패하였습니다.", 200);
		}
		*/
		return $this->makeJson(true, "회원 정보 수정에 성공하였습니다.", 200);
	}

	public function destroy(Request $request){
		$data = $request->json()->all();

		$messages = array(
			'email.required' => '이메일을 적어주시기 바랍니다.',
			'email.email' => '이메일 형식이 올바르지 않습니다.',
			'email.max' => '이메일은 최대 30자리이하 입력해야합니다.',
			'password.required' => '비밀번호를 적어주시기 바랍니다.',
			'password.min' => '비밀번호는 최소 6자리이상 입력해야합니다.',
			'password.max' => '비밀번호는 최대 60자리이하 입력해야합니다.',
		);

		$validator = \Validator::make($data, [
			'email' => 'required|email|max:30',
			'password' => 'required|min:6|max:60',
		], $messages);

		if ($validator->fails()){
			return $this->makeJson(false, $validator->messages()->first(), 200);
		}

		$user_count = \DB::select('SELECT COUNT(*) AS CNT 
					FROM users 
					WHERE email = ? 
					AND password = ?',
				[$data['email'], $data['password']]);

		if ($user_count[0]->CNT == 0){
			return $this->makeJson(false, "해당하는 이메일 혹은 비밀번호가 존재하지 않습니다.", 200);
		}
		
		$delete_user = \App\User::where('email', $data['email'])->where('password', $data['password'])->delete();
			
		if ($delete_user == 0) {
			return $this->makeJson(false, "회원 탈퇴에 실패하였습니다.", 200);
		}
		
		return $this->makeJson(true, "회원 탈퇴에 성공하였습니다.", 200);
	}
}
