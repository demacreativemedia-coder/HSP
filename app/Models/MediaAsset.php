<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MediaAsset extends Model {
 protected $fillable=['title','path','mime_type','size_bytes','alt_text','is_public','consent_verified','uploaded_by'];
 protected $casts=['is_public'=>'boolean','consent_verified'=>'boolean'];
}
