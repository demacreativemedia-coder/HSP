<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SiteSetting extends Model {
 public $timestamps=false;
 protected $primaryKey='key'; public $incrementing=false; protected $keyType='string';
 protected $fillable=['key','value'];
 public static function val(string $key,string $default=''):string {return (string)(static::query()->where('key',$key)->value('value')??$default);}
}
