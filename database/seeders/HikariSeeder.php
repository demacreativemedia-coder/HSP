<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
class HikariSeeder extends Seeder {
 public function run():void {
  foreach(['Kaigo','Pengolahan Makanan','Perhotelan','Konstruksi','Otomotif','Restoran','Handling Bandara','Pertanian & Peternakan','Driver','Bahasa Jepang Dasar'] as $i=>$name) Program::firstOrCreate(['slug'=>Str::slug($name)],['name'=>$name,'summary'=>'Konsultasikan persyaratan dan jadwal program kepada admin.','sort_order'=>$i]);
  foreach(['site_name'=>'LPK Hikari Sumut','site_city'=>'Ponorogo','whatsapp'=>'','address'=>'','legal_text'=>'','registration_fee'=>'','training_fee'=>'','promo_until'=>''] as $key=>$value) SiteSetting::firstOrCreate(['key'=>$key],['value'=>$value]);
 }
}
