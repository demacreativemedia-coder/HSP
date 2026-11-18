<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class CreateHikariAdmin extends Command {
 protected $signature='hikari:create-admin {email}';protected $description='Create first administrator account';
 public function handle():int{$email=$this->argument('email');if(!filter_var($email,FILTER_VALIDATE_EMAIL)){$this->error('Email tidak valid');return self::FAILURE;}if(User::where('email',$email)->exists()){$this->error('Akun sudah ada');return self::FAILURE;}$password=$this->secret('Kata sandi (min. 12 karakter)');if(strlen((string)$password)<12){$this->error('Kata sandi terlalu pendek');return self::FAILURE;}User::create(['name'=>'Administrator','role'=>'super_admin','email'=>$email,'password'=>Hash::make($password)]);$this->info('Akun dibuat');return self::SUCCESS;}
}
