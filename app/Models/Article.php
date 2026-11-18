<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Article extends Model {protected $fillable=['slug','title','excerpt','body','published','published_at'];protected $casts=['published'=>'boolean','published_at'=>'datetime'];}
