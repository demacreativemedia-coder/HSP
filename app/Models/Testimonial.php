<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Testimonial extends Model {
 protected $fillable=['display_name','role','quote','consent_verified','published','sort_order'];
 protected $casts=['published'=>'boolean','consent_verified'=>'boolean'];
}
