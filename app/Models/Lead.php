<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Lead extends Model {
 protected $fillable=['name','phone','program_id','status','source','utm_source','utm_medium','utm_campaign','utm_content','landing_page','assigned_to','notes'];
 public function activities(){return $this->hasMany(LeadActivity::class);}
 public function registration(){return $this->hasOne(Registration::class);}
 public function program(){return $this->belongsTo(Program::class);}
}
