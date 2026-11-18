{{-- V6: konfigurasi diambil dari database, tag tidak dimuat sebelum opt-in --}}
@php
$trackingSettings = \App\Models\SiteSetting::whereIn('key',['ga4_id','gtm_id','meta_pixel_id','tiktok_pixel_id'])->pluck('value','key');
$trackingIds = [
 'ga4' => preg_match('/^G-[A-Z0-9]{6,20}$/', (string)($trackingSettings['ga4_id'] ?? '')) ? $trackingSettings['ga4_id'] : '',
 'gtm' => preg_match('/^GTM-[A-Z0-9]{4,15}$/', (string)($trackingSettings['gtm_id'] ?? '')) ? $trackingSettings['gtm_id'] : '',
 'meta' => preg_match('/^[0-9]{8,25}$/', (string)($trackingSettings['meta_pixel_id'] ?? '')) ? $trackingSettings['meta_pixel_id'] : '',
 'tiktok' => preg_match('/^[A-Z0-9]{10,30}$/', (string)($trackingSettings['tiktok_pixel_id'] ?? '')) ? $trackingSettings['tiktok_pixel_id'] : '',
];
@endphp
<style>
#hikari-consent{position:fixed;bottom:16px;left:16px;right:16px;z-index:9999;max-width:610px;margin:auto;background:#fff;color:#172b49;padding:18px 20px;border:1px solid #c9d5e6;border-radius:14px;box-shadow:0 12px 42px #071b3840;display:none;font-size:14px;line-height:1.55}
#hikari-consent button{margin:8px 8px 0 0;padding:10px 15px;border-radius:8px;border:1px solid #9db0c7;background:#fff;color:#163359;font-weight:700;cursor:pointer}
#hikari-consent button.primary{background:#173c6a;color:white;border-color:#173c6a}
#hikari-privacy-control{background:transparent;border:0;color:inherit;text-decoration:underline;cursor:pointer;font:inherit;padding:0}
</style>
<div id="hikari-consent" role="dialog" aria-label="Pengaturan cookies" aria-live="polite">
 <strong>Preferensi cookies</strong>
 <div>Cookies esensial dipakai untuk fungsi situs. Analytics dan pixel iklan hanya diaktifkan jika kamu menyetujuinya.</div>
 <button type="button" class="primary" id="hikari-accept">Setujui analytics & iklan</button>
 <button type="button" id="hikari-reject">Tolak yang opsional</button>
</div>
<script>
(function(){
 'use strict';
 const ids=@json($trackingIds);
 const key='hikari_tracking_consent_v1';
 const banner=document.getElementById('hikari-consent');
 function addScript(url){const s=document.createElement('script');s.async=true;s.src=url;document.head.appendChild(s);}
 function enableTracking(){
  // Only load external scripts after explicit opt-in. No user-provided PII is sent.
  if(ids.gtm){window.dataLayer=window.dataLayer||[];window.dataLayer.push({'gtm.start':Date.now(),event:'gtm.js'});addScript('https://www.googletagmanager.com/gtm.js?id='+encodeURIComponent(ids.gtm));}
  if(ids.ga4){window.dataLayer=window.dataLayer||[];window.gtag=function(){window.dataLayer.push(arguments)};window.gtag('js',new Date());window.gtag('config',ids.ga4,{send_page_view:true});addScript('https://www.googletagmanager.com/gtag/js?id='+encodeURIComponent(ids.ga4));}
  if(ids.meta){window.fbq=function(){(window.fbq.callMethod)?window.fbq.callMethod.apply(window.fbq,arguments):window.fbq.queue.push(arguments)};window.fbq.queue=[];window.fbq.loaded=true;window.fbq.version='2.0';addScript('https://connect.facebook.net/en_US/fbevents.js');window.fbq('init',ids.meta);window.fbq('track','PageView');}
  if(ids.tiktok){(function(w,d,t){w.TiktokAnalyticsObject=t;var q=w[t]=w[t]||[];q.methods=['page','track','identify','instances','debug','on','off','once','ready','alias','group','enableCookie','disableCookie'];q.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<q.methods.length;i++)q.setAndDefer(q,q.methods[i]);q.load=function(id){addScript('https://analytics.tiktok.com/i18n/pixel/events.js?sdkid='+encodeURIComponent(id)+'&lib='+t)};q.load(ids.tiktok);q.page();})(window,document,'ttq');}
 }
 let value=null;
 try{value=localStorage.getItem(key)}catch(e){}
 if(value==='yes')enableTracking();else if(value!=='no')banner.style.display='block';
 function choose(yes){try{localStorage.setItem(key,yes?'yes':'no')}catch(e){}banner.style.display='none';if(yes)enableTracking();}
 document.getElementById('hikari-accept').addEventListener('click',function(){choose(true)});
 document.getElementById('hikari-reject').addEventListener('click',function(){choose(false)});
 document.getElementById('hikari-privacy-control')?.addEventListener('click',function(){banner.style.display='block'});
})();
</script>
