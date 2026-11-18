<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Registration extends Model {
 protected $fillable=['lead_id','program_id','registration_code','status','registered_on','admin_notes','verified_by'];
 protected $casts=['registered_on'=>'date'];
 public function lead(){return $this->belongsTo(Lead::class);}
 public function program(){return $this->belongsTo(Program::class);}
}
