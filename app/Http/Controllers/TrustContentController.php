<?php
namespace App\Http\Controllers;
use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\Http\Request;
class TrustContentController extends Controller {
 public function index(){return view('admin.trust-content',['faqs'=>Faq::orderBy('sort_order')->get(),'testimonials'=>Testimonial::orderBy('sort_order')->get()]);}
 public function saveFaq(Request $r){$v=$r->validate(['id'=>'nullable|integer|exists:faqs,id','question'=>'required|string|max:250','answer'=>'required|string|max:5000','sort_order'=>'nullable|integer|min:0|max:99999']);$id=$v['id']??null;unset($v['id']);$v['published']=$r->boolean('published');Faq::updateOrCreate(['id'=>$id],$v);return back()->with('ok','FAQ disimpan.');}
 public function saveTestimonial(Request $r){$v=$r->validate(['id'=>'nullable|integer|exists:testimonials,id','display_name'=>'required|string|max:100','role'=>'nullable|string|max:100','quote'=>'required|string|max:2000','sort_order'=>'nullable|integer|min:0|max:99999']);$id=$v['id']??null;unset($v['id']);$existing=$id?Testimonial::findOrFail($id):null;$consent=$r->boolean('consent_verified');$v['consent_verified']=$consent;
 // Konten hanya dapat dipublikasi bila persetujuan terdokumentasi.
 $v['published']=$consent && $r->boolean('published');
 Testimonial::updateOrCreate(['id'=>$id],$v);return back()->with('ok','Testimoni disimpan.');}
 public function deleteFaq(Faq $faq){$faq->delete();return back()->with('ok','FAQ dihapus.');}
 public function deleteTestimonial(Testimonial $testimonial){$testimonial->delete();return back()->with('ok','Testimoni dihapus.');}
}
