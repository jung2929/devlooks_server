<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommonDetail extends Model
{
	protected $table = 'common_detail';

	protected $fillable = ['code', 'code_detail', 'code_detail_name'];
}
