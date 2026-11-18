<?php
namespace App\Http\Controllers;
use App\Models\Program;
use App\Models\SiteSetting;
use App\Models\MediaAsset;
use App\Models\Faq;
use App\Models\Testimonial;
class SiteController extends Controller {
 private function base(string $page,array $extra=[]){return view('site.page',array_merge(['page'=>$page,'programs'=>Program::where('is_active',true)->orderBy('sort_order')->get(),'settings'=>SiteSetting::pluck('value','key'),'trackingSettings'=>SiteSetting::whereIn('key',['ga4_id','gtm_id','meta_pixel_id','tiktok_pixel_id'])->pluck('value','key'),'faqs'=>Faq::where('published',true)->orderBy('sort_order')->limit(12)->get(),'testimonials'=>Testimonial::where('published',true)->where('consent_verified',true)->orderBy('sort_order')->limit(8)->get()],$extra));}
 public function home(){return $this->base('home');}
 public function programs(){return $this->base('programs');}
 public function program(string $slug){return $this->base('program',['selectedProgram'=>Program::where('slug',$slug)->where('is_active',true)->firstOrFail()]);}
 public function about(){return $this->base('about');}
 public function parents(){return $this->base('parents');}
 public function alumni(){return $this->base('alumni',['gallery'=>MediaAsset::where('is_public',true)->where('consent_verified',true)->latest()->limit(24)->get()]);}
 public function contact(){return $this->base('contact');}
 public function landing(){return $this->base('landing');}
}
