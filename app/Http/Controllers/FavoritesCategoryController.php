<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesCategoryController extends Controller
{

	public function call(Request $request){
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
		
		/*
		$favorites_data = \DB::select('SELECT category_code 
						FROM favorites_category 
						WHERE email = ? 
						AND password = ?',
				[$data['email'], $data['password']]);
		
		if (empty($favorites_data)){
			return $this->makeJson(true, array('category_code'=>''), 200);
		} else {
			return $this->makeJson(true, $favorites_data, 200);
		}
		*/
		$favorites_data = \DB::select('SELECT category_code AS categoryCode 
						FROM favorites_category AS A 
						INNER JOIN common_detail AS B 
							ON B.code = ? 
							AND A.category_code = B.code_detail 
						WHERE A.email = ? 
						AND A.password = ?',
				['AA', $data['email'], $data['password']]);
		
		if (empty($favorites_data)){
			return $this->makeJson(true, array(array('categoryCode'=>'')), 200);
		} else {
			return $this->makeJson(true, $favorites_data, 200);
		}
	}

	public function store(Request $request){
		$data = $request->json()->all();
		
		$messages = array(
			'email.required' => '이메일을 적어주시기 바랍니다.',
			'email.email' => '이메일 형식이 올바르지 않습니다.',
			'email.max' => '이메일은 최대 30자리이하 입력해야합니다.',
			'password.required' => '비밀번호를 적어주시기 바랍니다.',
			'password.min' => '비밀번호는 최소 6자리이상 입력해야합니다.',
			'password.max' => '비밀번호는 최대 60자리이하 입력해야합니다.',
			'favorites_code.required' => '즐겨찾기 코드를 입력해주시기 바랍니다.',
			'favorites_code.min' => '카테고리 즐겨찾기 코드는 3자리만 입력가능합니다.',
			'favorites_code.max' => '카테고리 즐겨찾기 코드는 3자리만 입력가능합니다.',
			'is_selected.required' => '카테고리 즐겨찾기 등록/삭제 여부를 입력해주세요.',
		);

		$validator = \Validator::make($data, [
			'email' => 'required|email|max:30',
			'password' => 'required|min:6|max:60',
			'favorites_code' => 'required|min:3|max:3',
			'is_selected' => 'required',
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
		
		if ($data['is_selected']){
			$max_count = \DB::select('SELECT COUNT(*) AS MAX_CNT 
					FROM favorites_category 
					WHERE email = ? 
					AND password = ?',
				[$data['email'], $data['password']]);
			
			if ($max_count[0]->MAX_CNT >= 4){
				return $this->makeJson(false, "더이상 카테고리 즐겨찾기를 등록 할 수 없습니다.", 200);
			}
	
			$overlap_count = \DB::select('SELECT COUNT(*) AS OVL_CNT 
						FROM favorites_category 
						WHERE email = ? 
						AND password = ? 
						AND category_code = ?',
				[$data['email'], $data['password'], $data['favorites_code']]);
			
			if ($overlap_count[0]->OVL_CNT > 0){
				return $this->makeJson(false, "이미 해당 카테고리 즐겨찾기를 등록하셨습니다.", 200);
			}
	
			$favorites = \App\FavoritesCategory::create([
				'email'=>$data['email'],
				'password'=>$data['password'],
				'category_code'=>$data['favorites_code']
			]);
	
			return $this->makeJson(true, "카테고리 즐겨찾기 등록에 성공하였습니다.", 200);
		} else {
			/*
			$search_favorites = \App\FavoritesCategory::where([
				'email'=>$data['email'],
				'password'=>$data['password'],
				'category_code'=>$data['favorites_code']
			]);
			*/
			$search_favorites = \App\FavoritesCategory::where('email', $data['email'])->where('password', $data['password'])->where('category_code', $data['favorites_code'])->delete();
			
			if ($search_favorites == 0) {
				return $this->makeJson(false, "카테고리 즐겨찾기 삭제에 실패하였습니다.", 200);
			}
			
			return $this->makeJson(true, "카테고리 즐겨찾기 삭제에 성공하였습니다.", 200);
		}
	}
}
