<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoritesCategory extends Model
{
	protected $table = 'favorites_category';

	protected $fillable = ['email', 'password', 'category_code'];
}
