<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
Route::get('/',[SiteController::class,'home'])->name('home');
Route::get('/program',[SiteController::class,'programs'])->name('programs');
Route::get('/program/{slug}',[SiteController::class,'program'])->name('program');
Route::get('/tentang-kami',[SiteController::class,'about'])->name('about');
Route::get('/informasi-orang-tua',[SiteController::class,'parents'])->name('parents');
Route::get('/alumni',[SiteController::class,'alumni'])->name('alumni');
Route::get('/kontak',[SiteController::class,'contact'])->name('contact');
Route::get('/lp/kerja-jepang',[SiteController::class,'landing'])->name('landing');
Route::post('/konsultasi',[LeadController::class,'store'])->middleware('throttle:10,1')->name('leads.store');
Route::get('/admin/login',[AuthController::class,'login'])->name('login');
Route::post('/admin/login',[AuthController::class,'authenticate'])->middleware('throttle:5,1')->name('admin.authenticate');
Route::post('/admin/logout',[AuthController::class,'logout'])->middleware('auth')->name('admin.logout');
Route::middleware(['auth','can:dashboard.view'])->prefix('admin')->name('admin.')->group(function(){
 Route::get('/',[AdminController::class,'dashboard'])->name('dashboard');
 Route::get('/leads',[AdminController::class,'leads'])->middleware('can:leads.view')->name('leads');
 Route::patch('/leads/{lead}',[AdminController::class,'updateLead'])->middleware('can:leads.edit')->name('leads.update');
 Route::get('/settings',[AdminController::class,'settings'])->middleware('can:settings.manage')->name('settings');
 Route::post('/settings',[AdminController::class,'saveSettings'])->middleware('can:settings.manage')->name('settings.save');
 Route::get('/programs',[AdminController::class,'programs'])->middleware('can:content.manage')->name('programs');
 Route::post('/programs',[AdminController::class,'saveProgram'])->middleware('can:content.manage')->name('programs.save');
});

// CMS dan CRM V2 — route spesifik sebelum wildcard
Route::get('/artikel',[\App\Http\Controllers\ContentController::class,'publicArticles'])->name('articles');
Route::get('/artikel/{slug}',[\App\Http\Controllers\ContentController::class,'publicArticle'])->name('article');
Route::get('/halaman/{slug}',[\App\Http\Controllers\ContentController::class,'publicPage'])->name('content.page');
Route::middleware(['auth','can:dashboard.view'])->prefix('admin')->name('admin.')->group(function(){
 Route::get('/halaman',[\App\Http\Controllers\ContentController::class,'pages'])->middleware('can:content.manage')->name('pages');
 Route::post('/halaman',[\App\Http\Controllers\ContentController::class,'savePage'])->middleware('can:content.manage')->name('pages.save');
 Route::get('/artikel',[\App\Http\Controllers\ContentController::class,'articles'])->middleware('can:content.manage')->name('articles');
 Route::post('/artikel',[\App\Http\Controllers\ContentController::class,'saveArticle'])->middleware('can:content.manage')->name('articles.save');
 Route::get('/follow-ups',[\App\Http\Controllers\FollowUpController::class,'index'])->middleware('can:leads.view')->name('followups');
 Route::post('/follow-ups',[\App\Http\Controllers\FollowUpController::class,'store'])->middleware('can:leads.edit')->name('followups.store');
 Route::patch('/follow-ups/{followUp}/complete',[\App\Http\Controllers\FollowUpController::class,'complete'])->middleware('can:leads.edit')->name('followups.complete');
 Route::get('/leads/export',[\App\Http\Controllers\FollowUpController::class,'export'])->middleware('can:leads.export')->name('leads.export');
});

// V3: pendaftaran dan media library (admin only)
Route::middleware(['auth','can:dashboard.view'])->prefix('admin')->name('admin.')->group(function(){
 Route::get('/registrations',[\App\Http\Controllers\RegistrationController::class,'index'])->middleware('can:registrations.manage')->name('registrations');
 Route::post('/registrations',[\App\Http\Controllers\RegistrationController::class,'store'])->middleware('can:registrations.manage')->name('registrations.store');
 Route::patch('/registrations/{registration}',[\App\Http\Controllers\RegistrationController::class,'update'])->middleware('can:registrations.manage')->name('registrations.update');
 Route::get('/media',[\App\Http\Controllers\MediaController::class,'index'])->middleware('can:media.manage')->name('media');
 Route::post('/media',[\App\Http\Controllers\MediaController::class,'store'])->middleware('can:media.manage')->name('media.store');
 Route::patch('/media/{asset}/publish',[\App\Http\Controllers\MediaController::class,'publish'])->middleware('can:media.manage')->name('media.publish');
 Route::delete('/media/{asset}',[\App\Http\Controllers\MediaController::class,'destroy'])->middleware('can:media.manage')->name('media.destroy');
});

// V4: halaman konfirmasi bila WhatsApp resmi belum dikonfigurasi
Route::get('/konsultasi/diterima',function(){abort_unless(session()->has('reference'),404);return view('site.received');})->name('consultation.received');

// V5: FAQ dan testimoni, akses admin saja
Route::middleware(['auth','can:dashboard.view'])->prefix('admin')->name('admin.')->group(function(){
 Route::get('/trust-content',[\App\Http\Controllers\TrustContentController::class,'index'])->middleware('can:content.manage')->name('trust.index');
 Route::post('/faqs',[\App\Http\Controllers\TrustContentController::class,'saveFaq'])->middleware('can:content.manage')->name('faqs.save');
 Route::delete('/faqs/{faq}',[\App\Http\Controllers\TrustContentController::class,'deleteFaq'])->middleware('can:content.manage')->name('faqs.delete');
 Route::post('/testimonials',[\App\Http\Controllers\TrustContentController::class,'saveTestimonial'])->middleware('can:content.manage')->name('testimonials.save');
 Route::delete('/testimonials/{testimonial}',[\App\Http\Controllers\TrustContentController::class,'deleteTestimonial'])->middleware('can:content.manage')->name('testimonials.delete');
});

// V6: konfigurasi tracking (hanya admin)
Route::middleware(['auth','can:dashboard.view'])->prefix('admin')->name('admin.')->group(function(){
 Route::get('/tracking',[\App\Http\Controllers\TrackingSettingsController::class,'edit'])->middleware('can:tracking.manage')->name('tracking');
 Route::post('/tracking',[\App\Http\Controllers\TrackingSettingsController::class,'update'])->middleware('can:tracking.manage')->name('tracking.update');
});

// V7: audit log hanya direktur / super admin
Route::middleware(['auth','can:audit.view'])->get('/admin/audit',[\App\Http\Controllers\AuditController::class,'index'])->name('admin.audit');
