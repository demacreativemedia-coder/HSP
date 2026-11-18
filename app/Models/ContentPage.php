<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ContentPage extends Model {protected $fillable=['slug','title','meta_description','body','published'];protected $casts=['published'=>'boolean'];}
