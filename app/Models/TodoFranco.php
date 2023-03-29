<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoFranco extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
   
    protected $table = 'to_do_franco';
    protected $fillable = ['name', 'email', 'age'];
    
}
