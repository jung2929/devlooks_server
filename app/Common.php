<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Common extends Model
{
	protected $table = 'common';

	protected $fillable = ['code', 'code_name'];
}
