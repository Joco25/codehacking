<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //
	protected $fillable = ['file'];



	public function role(){
		return $this->belongsTo('App\Role');
	}

	public function photo(){
		return $this->belongsTo('App\Photo');
	}
}
