<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<style>
    body.auth-page { min-height:100vh; overflow-x:hidden; color:#fff; background:#101a3c; font-family:'DM Sans',sans-serif; }
    body.auth-page .navbar,body.auth-page footer { display:none; }
    body.auth-page main { display:flex; flex:1; min-height:100vh; }
    body.auth-page> .container.mt-3 { position:fixed; z-index:20; top:14px; left:50%; width:min(600px,calc(100% - 32px)); margin:0!important; transform:translateX(-50%); }
    body.auth-page .login-screen { position:relative; display:grid; width:100%; min-height:100vh; grid-template-columns:minmax(0,1.08fr) minmax(420px,.92fr); align-items:center; gap:clamp(28px,6vw,90px); padding:48px clamp(28px,6.2vw,96px); background-color:#101a3c; background-image:linear-gradient(90deg,rgba(8,20,57,.04) 0%,rgba(8,20,57,.08) 42%,rgba(8,17,48,.65) 59%,#101a3c 100%),url('{{ asset('images/login-bg.jpg') }}'); background-repeat:no-repeat; background-position:center,left center; background-size:100% 100%,58% 100%; }
    body.auth-page .login-story { position:relative; display:flex; min-height:min(660px,calc(100vh - 96px)); flex-direction:column; justify-content:space-between; }
    body.auth-page .login-brand { display:inline-flex; align-items:center; width:max-content; padding:8px 13px; border:1px solid rgba(255,255,255,.25); border-radius:12px; background:rgba(13,31,76,.46); backdrop-filter:blur(8px); }
    body.auth-page .login-brand img { width:128px; height:auto; object-fit:contain; }
    body.auth-page .login-story-copy { max-width:540px; margin:0 0 12px; padding:24px; border-left:3px solid #3476ff; background:linear-gradient(90deg,rgba(8,22,61,.64),transparent); }
    body.auth-page .login-eyebrow { display:flex; align-items:center; gap:9px; margin:0 0 17px; color:#d5e0ff; font-size:.72rem; font-weight:700; letter-spacing:.14em; }
    body.auth-page .login-eyebrow span { width:8px; height:8px; border-radius:50%; background:#4d8dff; box-shadow:0 0 12px #4d8dff; }
    body.auth-page .login-story h1 { margin:0; color:#fff; font-family:'Manrope',sans-serif; font-size:clamp(36px,4.2vw,60px); font-weight:800; line-height:1.08; letter-spacing:-.04em; }
    body.auth-page .login-story h1 span { color:#73a6ff; }
    body.auth-page .login-story-copy>p:last-child { max-width:420px; margin:18px 0 0; color:#e0e8ff; font-size:1rem; line-height:1.65; }
    body.auth-page .login-panel-wrap { display:flex; justify-content:center; }
    body.auth-page .login-panel { width:100%; max-width:500px; padding:34px clamp(26px,3.3vw,48px) 28px; border:1px solid rgba(255,255,255,.35); border-radius:22px; background:rgba(221,230,255,.16); box-shadow:0 24px 70px rgba(2,8,31,.27),inset 0 1px 0 rgba(255,255,255,.15); backdrop-filter:blur(18px); }
    body.auth-page .login-heading { margin-bottom:25px; text-align:center; }
    body.auth-page .login-mobile-brand { display:none; }
    body.auth-page .login-heading h2 { margin:0; color:#fff; font-family:'Manrope',sans-serif; font-size:2.55rem; font-weight:800; }
    body.auth-page .login-heading-rule { display:block; width:64px; height:4px; margin:10px auto 13px; border-radius:4px; background:#3d77ff; }
    body.auth-page .login-heading>p:last-child { margin:0; color:#e1e8ff; font-size:.88rem; }
    body.auth-page .login-field { margin-bottom:18px; }
    body.auth-page .login-field>label { display:block; margin-bottom:7px; color:#fff; font-size:.88rem; font-weight:700; }
    body.auth-page .login-input-wrap { display:flex; align-items:center; min-height:54px; padding:0 14px; border:1px solid rgba(220,228,255,.55); border-radius:12px; color:#27365f; background:rgba(255,255,255,.94); transition:box-shadow .18s ease,border-color .18s ease; }
    body.auth-page .login-input-wrap:focus-within { border-color:#81a9ff; box-shadow:0 0 0 3px rgba(89,137,255,.24); }
    body.auth-page .login-input-wrap>i { flex:0 0 22px; margin-right:12px; color:#344777; font-size:1rem; text-align:center; }
    body.auth-page .login-input-wrap input { width:100%; min-width:0; height:50px; padding:0; border:0; outline:0; color:#202d4d; background:transparent; font:500 .9rem 'DM Sans',sans-serif; }
    body.auth-page .login-input-wrap input::placeholder { color:#8490ae; opacity:1; }
    body.auth-page .login-input-wrap input.is-invalid { color:#b42318; }
    body.auth-page .login-password-toggle { display:grid; width:34px; height:34px; flex:0 0 34px; place-items:center; border:0; border-radius:7px; color:#344777; background:transparent; }
    body.auth-page .login-password-toggle:hover { background:#e9edfa; }
    body.auth-page .login-error { margin-top:6px; color:#ffe0e0; font-size:.8rem; }
    body.auth-page .login-options { display:flex; align-items:center; justify-content:space-between; gap:12px; margin:2px 0 22px; color:#f0f3ff; font-size:.78rem; }
    body.auth-page .login-remember { display:inline-flex; align-items:center; gap:8px; cursor:pointer; }
    body.auth-page .login-remember input { width:17px; height:17px; margin:0; accent-color:#3476ff; }
    body.auth-page .login-options a,body.auth-page .login-signup a { color:#77a5ff; font-weight:700; text-decoration:none; }
    body.auth-page .login-options a:hover,body.auth-page .login-signup a:hover { color:#b3cdff; text-decoration:underline; }
    body.auth-page .login-submit { display:flex; width:100%; min-height:54px; align-items:center; justify-content:center; gap:12px; border:0; border-radius:13px; color:#fff; background:linear-gradient(100deg,#1456f5,#477bff); box-shadow:0 10px 24px rgba(15,76,237,.25); font-size:1rem; font-weight:800; transition:transform .18s ease,filter .18s ease; }
    body.auth-page .login-submit:hover { filter:brightness(1.08); transform:translateY(-1px); }
    body.auth-page .login-submit:focus-visible,body.auth-page .login-password-toggle:focus-visible { outline:3px solid #a9c5ff; outline-offset:3px; }
    body.auth-page .login-divider { display:flex; align-items:center; gap:12px; margin:18px 0; color:#d5def8; font-size:.75rem; }
    body.auth-page .login-divider::before,body.auth-page .login-divider::after { height:1px; flex:1; background:rgba(220,228,255,.35); content:''; }
    body.auth-page .login-google { display:flex; min-height:50px; align-items:center; justify-content:center; gap:10px; border:1px solid rgba(220,228,255,.55); border-radius:12px; color:#fff; background:rgba(255,255,255,.08); font-size:.88rem; font-weight:700; text-decoration:none; transition:background .18s ease,border-color .18s ease; }
    body.auth-page .login-google:hover { border-color:rgba(220,228,255,.8); color:#fff; background:rgba(255,255,255,.16); }
    body.auth-page .login-google i { color:#9bbaff; }
    body.auth-page .login-password-hint { margin:6px 0 0; color:#d5def8; font-size:.72rem; }
    body.auth-page .login-secure { display:flex; align-items:center; justify-content:center; gap:9px; margin:20px 0 0; color:#d4defc; font-size:.76rem; }
    body.auth-page .login-secure span { width:7px; height:7px; border-radius:50%; background:#55d69e; box-shadow:0 0 9px rgba(85,214,158,.8); }
    body.auth-page .login-signup { margin:20px 0 0; color:#e6ebff; font-size:.82rem; text-align:center; }
    body.auth-page .register-fields-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:0 14px; }
    body.auth-page .register-fields-grid .login-field { min-width:0; }
    body.auth-page .register-optional { color:#d5def8; font-size:.72rem; font-weight:500; }
    body.auth-page .register-submit { margin-top:4px; }
    html[data-theme="dark"] body.auth-page { color:#fff; background:#101a3c; }
    html[data-theme="dark"] body.auth-page .login-panel { color:#fff; border-color:rgba(255,255,255,.35); background:rgba(221,230,255,.16); }
    @media(max-width:900px) {
        body.auth-page .login-screen { grid-template-columns:minmax(0,.8fr) minmax(390px,1fr); gap:28px; padding-inline:28px; background-size:100% 100%,58% 100%; }
        body.auth-page .login-story h1 { font-size:clamp(34px,5vw,48px); }
        body.auth-page .login-panel { padding-inline:28px; }
    }
    @media(max-width:820px) {
        body.auth-page main { min-height:100svh; }
        body.auth-page .login-screen { min-height:100svh; grid-template-columns:1fr; gap:0; padding:24px 18px; background-image:linear-gradient(90deg,rgba(9,20,55,.75),rgba(9,20,55,.82)),url('{{ asset('images/login-bg.jpg') }}'); background-size:cover; background-position:center; }
        body.auth-page .login-story { display:none; }
        body.auth-page .login-panel-wrap { align-items:center; }
        body.auth-page .login-panel { max-width:460px; padding:28px 23px 24px; border-radius:18px; background:rgba(31,43,86,.76); }
        body.auth-page .login-mobile-brand { display:block; margin:0 0 7px; color:#a9c4ff; font-size:.7rem; font-weight:800; letter-spacing:.16em; }
        body.auth-page .login-heading h2 { font-size:2.15rem; }
        body.auth-page .login-input-wrap { min-height:50px; }
        body.auth-page .login-input-wrap input { height:46px; }
        body.auth-page> .container.mt-3 { top:8px; }
    }
    @media(max-width:520px) {
        body.auth-page .register-fields-grid { grid-template-columns:1fr; gap:0; }
    }
    @media(max-width:380px) {
        body.auth-page .login-screen { padding-inline:12px; }
        body.auth-page .login-panel { padding-inline:18px; }
        body.auth-page .login-options { align-items:flex-start; flex-direction:column; }
    }
</style>
