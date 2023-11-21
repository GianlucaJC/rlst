<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class db_utenti extends Model
{
	protected $table="db";
	protected $connection = 'online';	
    use HasFactory;
}
