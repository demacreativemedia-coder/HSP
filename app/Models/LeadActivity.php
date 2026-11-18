<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LeadActivity extends Model {protected $fillable=['lead_id','user_id','action','details'];}
