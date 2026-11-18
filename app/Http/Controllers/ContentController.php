<?php
namespace App\Http\Controllers;
use App\Models\ContentPage;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ContentController extends Controller {
 public function pages(){return view('admin.content-pages',['items'=>ContentPage::orderBy('title')->get()]);}
 public function savePage(Request $r){$v=$r->validate(['id'=>'nullable|integer|exists:content_pages,id','slug'=>'required|alpha_dash|max:100','title'=>'required|string|max:180','meta_description'=>'nullable|string|max:160','body'=>'nullable|string|max:30000']);$v['published']=$r->boolean('published');$id=$v['id']??null;unset($v['id']);$v['slug']=Str::slug($v['slug']);if($id)ContentPage::findOrFail($id)->update($v);else ContentPage::create($v);return back()->with('ok','Halaman tersimpan.');}
 public function articles(){return view('admin.articles',['items'=>Article::latest()->get()]);}
 public function saveArticle(Request $r){$v=$r->validate(['id'=>'nullable|integer|exists:articles,id','title'=>'required|string|max:180','slug'=>'required|alpha_dash|max:100','excerpt'=>'nullable|string|max:500','body'=>'nullable|string|max:50000']);$v['published']=$r->boolean('published');$id=$v['id']??null;unset($v['id']);$v['slug']=Str::slug($v['slug']);$v['published_at']=$v['published']?now():null;if($id)Article::findOrFail($id)->update($v);else Article::create($v);return back()->with('ok','Artikel tersimpan.');}
 public function publicArticles(){return view('site.articles',['items'=>Article::where('published',true)->latest('published_at')->paginate(12)]);}
 public function publicArticle(string $slug){return view('site.article',['item'=>Article::where('published',true)->where('slug',$slug)->firstOrFail()]);}
 public function publicPage(string $slug){return view('site.content-page',['item'=>ContentPage::where('published',true)->where('slug',$slug)->firstOrFail()]);}
}
