<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Program extends Model {
 protected $fillable=['name','slug','summary','description','is_active','sort_order'];
 protected $casts=['is_active'=>'boolean'];
}
