<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoritesContent extends Model
{
	protected $table = 'favorites_content';

	protected $fillable = ['email', 'password' , 'content_title', 'content_url', 'content_tag', 'content_summary', 'content_watch_count', 'content_favorites_count'];
}
