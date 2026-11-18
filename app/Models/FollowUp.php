<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FollowUp extends Model {protected $fillable=['lead_id','user_id','due_at','status','note'];protected $casts=['due_at'=>'datetime'];public function lead(){return $this->belongsTo(Lead::class);}}
