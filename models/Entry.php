<?php

namespace Model;

class Entry extends \Illuminate\Database\Eloquent\Model 
{
	protected $table = 'entries';
	protected $primaryKey = null;
	public $incrementing = false;
	public $timestamps = false;
}