<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesContentController extends Controller
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
		
		$favorites_data = \DB::select('SELECT content_title AS contentTitle,
							content_url AS contentUrl,
							content_tag AS contentTag,
							content_summary AS contentSummary,
							content_watch_count AS contentWatchCount,
							content_favorites_count AS contentFavoritesCount
						FROM favorites_content 
						WHERE email = ? 
						AND password = ?',
				[$data['email'], $data['password']]);
		
		if (empty($favorites_data)){
			return $this->makeJson(true, array(array('contentTitle'=>'', 'contentUrl'=>'', 'contentTag'=>'', 'contentSummary'=>'', 'contentWatchCount'=>'', 'contentFavoritesCount'=>'')), 200);
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
			'content_title.required' => '즐겨잧기 제목을 입력해주시기 바랍니다.',
			'content_title.max' => '즐겨찾기 제목은 최대 191자리만 입력가능합니다.',
			'content_url.required' => '즐겨찾기 주소를 입력해주시기 바랍니다.',
			'content_url.max' => '즐겨찾기 주소는 최대 191자리만 입력가능합니다.',
			'content_tag.required' => '즐겨찾기 태그를 입력해야합니다.',
			'content_tag.max' => '즐겨찾기 태그는 최대 191자리만 입력가능합니다.',
			'content_summary.required' => '즐겨찾기 요약내용을 입력해야 합니다.',
			'content_summary.max' => '즐겨찾기 요약내용은 최대 191자리만 입력가능합니다.',
			'content_watch_count.required' => '즐겨찾기 조회수를 입력해야합니다.',
			'content_watch_count.max' => '즐겨찾기 조회수 최대 10자리만 입력가능합니다.',
			'content_favorites_count.required' => '즐겨찾기 좋아요수를 입력해야합니다.',
			'content_favorites_count.max' => '즐겨찾기 좋아요수는 최대 10자리만 입력가능합니다.',
		);

		$validator = \Validator::make($data, [
			'email' => 'required|email|max:30',
			'password' => 'required|min:6|max:60',
			'content_title'=>'required|max:191',
			'content_url' => 'required|max:191',
			'content_tag' => 'required|max:291',
			'content_summary' => 'required|max:191',
			'content_watch_count' => 'required|max:10',
			'content_favorites_count' => 'required|max:10',
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

		$overlap_count = \DB::select('SELECT COUNT(*) AS OVL_CNT 
					FROM favorites_content 
					WHERE email = ? 
					AND password = ? 
					AND content_url = ?',
			[$data['email'], $data['password'], $data['content_url']]);
		
		if ($overlap_count[0]->OVL_CNT == 0){
			$favorites = \App\FavoritesContent::create([
				'email'=>$data['email'],
				'password'=>$data['password'],
				'content_title'=>$data['content_title'],
				'content_url'=>$data['content_url'],
				'content_tag'=>$data['content_tag'],
				'content_summary'=>$data['content_summary'],
				'content_watch_count'=>$data['content_watch_count'],
				'content_favorites_count'=>$data['content_favorites_count']
			]);

			return $this->makeJson(true, "즐겨찾기 등록에 성공하였습니다.", 200);
		} else {
			$search_favorites = \App\FavoritesContent::where('email', $data['email'])->where('password', $data['password'])->where('content_url', $data['content_url'])->delete();
		
			if ($search_favorites == 0) {
				return $this->makeJson(false, "즐겨찾기 삭제에 실패하였습니다.", 200);
			}		
			
			return $this->makeJson(true, "즐겨찾기 삭제에 성공하였습니다.", 200);
		}
	}
}
