<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<style>
    body.student-content-page { --student-blue:#1857ed; --student-navy:#122d70; --student-ink:#172b61; --student-muted:#66769f; --student-line:#e2e8fb; --student-surface:#fff; --student-bg:#f3f5ff; background:var(--student-bg); color:var(--student-ink); font-family:'DM Sans',sans-serif; }
    body.student-content-page h1,body.student-content-page h2,body.student-content-page h3,body.student-content-page h4 { font-family:'Manrope',sans-serif; letter-spacing:-.025em; }
    body.student-content-page .navbar { z-index:10; padding:.62rem 0; border-bottom:1px solid rgba(222,229,249,.8); background:rgba(255,255,255,.94); box-shadow:0 4px 18px rgba(37,66,143,.045); backdrop-filter:blur(14px); }
    body.student-content-page .navbar .container { max-width:1240px; }
    body.student-content-page .navbar-brand img { height:43px!important; }
    body.student-content-page .navbar .nav-link { position:relative; padding:.75rem .8rem; color:#304575!important; font-size:.82rem; font-weight:600; }
    body.student-content-page .navbar .nav-link:hover,body.student-content-page .navbar .nav-link.active { color:#1455ed!important; }
    body.student-content-page .navbar .nav-link.active::after { position:absolute; right:.8rem; bottom:0; left:.8rem; height:3px; border-radius:4px; background:#2462f4; content:''; }
    body.student-content-page .navbar .btn-primary { border-color:#2056eb; border-radius:10px; background:#2056eb; }
    body.student-content-page .navbar .btn-outline-primary { border-radius:10px; }
    body.student-content-page .student-page-hero { position:relative; overflow:hidden; min-height:250px; background-color:#e4ebff; background-image:linear-gradient(90deg,rgba(244,247,255,.99) 0%,rgba(244,247,255,.95) 38%,rgba(244,247,255,.46) 67%,rgba(244,247,255,.18) 100%),url('{{ asset('images/stg2.png') }}'); background-repeat:no-repeat; background-position:center,right 47%; background-size:cover,cover; }
    body.student-content-page .student-page-hero::before,body.student-content-page .student-page-hero::after { position:absolute; z-index:0; border:1px solid rgba(255,255,255,.52); border-radius:50%; content:''; }
    body.student-content-page .student-page-hero::before { top:-106px; left:45%; width:280px; height:280px; background:rgba(219,229,255,.28); }
    body.student-content-page .student-page-hero::after { bottom:-174px; left:-50px; width:310px; height:310px; background:rgba(216,228,255,.27); }
    body.student-content-page .student-page-hero-inner { position:relative; z-index:1; display:flex; max-width:1240px; min-height:250px; flex-direction:column; justify-content:center; margin:0 auto; padding:24px 24px 29px; }
    body.student-content-page .student-breadcrumb { display:flex; width:max-content; max-width:100%; align-items:center; gap:9px; margin-bottom:17px; padding:8px 13px; overflow:hidden; border:1px solid rgba(255,255,255,.75); border-radius:22px; color:#294778; background:rgba(255,255,255,.66); font-size:.74rem; font-weight:600; white-space:nowrap; }
    body.student-content-page .student-breadcrumb a { color:#294778; text-decoration:none; }
    body.student-content-page .student-breadcrumb a:hover { color:#1557ed; }
    body.student-content-page .student-breadcrumb>i { color:#7390ca; font-size:.62rem; }
    body.student-content-page .student-breadcrumb>span { overflow:hidden; color:#173979; text-overflow:ellipsis; }
    body.student-content-page .student-hero-copy { position:relative; z-index:1; max-width:700px; }
    body.student-content-page .student-hero-kicker { margin:0 0 7px; color:#1b5bef; font-size:.72rem; font-weight:800; letter-spacing:.08em; }
    body.student-content-page .student-hero-copy h1 { margin:0; color:#142f72; font-size:clamp(2rem,4vw,2.85rem); font-weight:800; line-height:1.13; }
    body.student-content-page .student-hero-copy h1 span { color:#155cf2; }
    body.student-content-page .student-hero-copy>p:not(.student-hero-kicker) { max-width:600px; margin:8px 0 0; color:#49608f; font-size:.93rem; line-height:1.5; }
    body.student-content-page .student-hero-underline { display:block; width:66px; height:5px; margin-top:13px; border-radius:5px; background:#1763f4; }
    body.student-content-page .student-hero-art { position:absolute; top:22px; right:8%; width:245px; height:205px; }
    body.student-content-page .student-hero-art-main { position:absolute; right:43px; bottom:8px; display:grid; width:144px; height:144px; place-items:center; border:1px solid rgba(255,255,255,.8); border-radius:32px; color:#fff; background:linear-gradient(145deg,#8eb8ff,#1d61e9 74%); box-shadow:0 20px 34px rgba(29,78,196,.25),inset 0 2px 6px rgba(255,255,255,.65); font-size:4rem; transform:rotate(-7deg); }
    body.student-content-page .student-hero-art-small { position:absolute; z-index:2; display:grid; width:53px; height:53px; place-items:center; border:1px solid rgba(255,255,255,.88); border-radius:16px; color:#2464e8; background:rgba(255,255,255,.84); box-shadow:0 8px 18px rgba(39,77,164,.14); font-size:1.3rem; }
    body.student-content-page .student-hero-art-one { top:0; right:25px; color:#f4a91d; }
    body.student-content-page .student-hero-art-two { top:34px; left:4px; }
    body.student-content-page .student-hero-art-three { right:0; bottom:30px; color:#4281f5; }
    body.student-content-page .student-page-content { width:min(1200px,calc(100% - 48px)); margin:0 auto; padding:24px 0 38px; }
    body.student-content-page .student-surface { border:1px solid #e3e9fb; border-radius:16px; background:rgba(255,255,255,.96); box-shadow:0 8px 22px rgba(36,67,150,.08); }
    body.student-content-page .student-surface-heading { display:flex; align-items:center; gap:11px; margin:0; color:#163373; font-size:1.2rem; font-weight:800; }
    body.student-content-page .student-heading-icon { display:grid; width:39px; height:39px; flex:0 0 39px; place-items:center; border-radius:12px; color:#f4a300; background:#fff3d4; font-size:1.05rem; }
    body.student-content-page .student-empty-state { display:flex; min-height:220px; align-items:center; flex-direction:column; justify-content:center; padding:24px; border:1.5px dashed #92d5ff; border-radius:12px; background:linear-gradient(120deg,#f7fbff,#eff5ff); text-align:center; }
    body.student-content-page .student-empty-art { display:grid; width:78px; height:70px; margin-bottom:12px; place-items:center; border-radius:22px; color:#4784f4; background:linear-gradient(145deg,#e7efff,#cbdcff); font-size:2.4rem; }
    body.student-content-page .student-empty-state h3 { margin:0 0 5px; color:#1a3678; font-size:1.02rem; font-weight:800; }
    body.student-content-page .student-empty-state p { max-width:520px; margin:0; color:#6b7da9; font-size:.84rem; line-height:1.55; }
    body.student-content-page .student-empty-cta { display:inline-flex; min-height:40px; align-items:center; justify-content:center; gap:8px; margin-top:15px; padding:0 17px; border-radius:22px; color:#fff!important; background:linear-gradient(100deg,#145df1,#0873f8); box-shadow:0 7px 15px rgba(35,91,224,.2); font-size:.79rem; font-weight:700; text-decoration:none; }
    body.student-content-page .student-empty-cta:hover { filter:brightness(1.07); }
    body.student-content-page .student-skill-layout { display:grid; grid-template-columns:1.55fr 1fr; gap:16px; }
    body.student-content-page .student-skill-panel,body.student-content-page .student-add-panel { padding:18px; }
    body.student-content-page .student-skill-list { display:flex; flex-wrap:wrap; gap:10px; margin-top:16px; }
    body.student-content-page .student-skill-chip { display:inline-flex; min-height:38px; align-items:center; gap:10px; padding:6px 11px 6px 14px; border:1px solid #d6e4ff; border-radius:22px; color:#1e4eb3; background:#edf4ff; font-size:.83rem; font-weight:700; }
    body.student-content-page .student-skill-chip form { display:inline-flex; margin:0; }
    body.student-content-page .student-skill-chip button { display:grid; width:22px; height:22px; place-items:center; border:0; border-radius:50%; color:#6680b4; background:#fff; }
    body.student-content-page .student-skill-chip button:hover { color:#c8334d; background:#ffe9ed; }
    body.student-content-page .student-add-panel .student-surface-heading { margin-bottom:18px; }
    body.student-content-page .student-add-icon { color:#078d5a; background:#dcf6e9; }
    body.student-content-page .student-form-label { display:block; margin:0 0 8px; color:#183879; font-size:.82rem; font-weight:700; }
    body.student-content-page .student-form-control { width:100%; min-height:43px; padding:9px 12px; border:1px solid #dbe4fa; border-radius:9px; color:#264477; background:#fff; font:500 .84rem 'DM Sans',sans-serif; }
    body.student-content-page .student-form-control:focus { border-color:#7da1f5; outline:0; box-shadow:0 0 0 3px rgba(50,102,238,.12); }
    body.student-content-page .student-form-error { display:block; margin-top:5px; color:#b42336; font-size:.77rem; }
    body.student-content-page .student-add-submit { display:flex; width:100%; min-height:43px; align-items:center; justify-content:center; gap:9px; margin-top:14px; border:0; border-radius:9px; color:#fff; background:#078c5b; font-size:.84rem; font-weight:800; }
    body.student-content-page .student-add-submit:hover { background:#06784f; }
    body.student-content-page .student-applications-card { padding:13px 16px 18px; }
    body.student-content-page .student-data-table-wrap { overflow-x:auto; }
    body.student-content-page .student-data-table { width:100%; border-collapse:separate; border-spacing:0; }
    body.student-content-page .student-data-table th { padding:13px 14px; color:#1b397a; background:#f3f6ff; font-size:.78rem; font-weight:800; white-space:nowrap; }
    body.student-content-page .student-data-table th:first-child { border-radius:8px 0 0 8px; }
    body.student-content-page .student-data-table th:last-child { border-radius:0 8px 8px 0; }
    body.student-content-page .student-data-table td { padding:15px 14px; border-bottom:1px solid #edf0fa; color:#4b5e8e; font-size:.8rem; }
    body.student-content-page .student-data-table tr:last-child td { border-bottom:0; }
    body.student-content-page .student-status { display:inline-flex; align-items:center; gap:6px; padding:6px 11px; border-radius:20px; font-size:.73rem; font-weight:800; white-space:nowrap; }
    body.student-content-page .student-status.accepted { color:#07814d; background:#ddf6e9; }
    body.student-content-page .student-status.refused { color:#b52f49; background:#ffeaee; }
    body.student-content-page .student-status.pending { color:#95620c; background:#fff2d5; }
    body.student-content-page .student-table-action { color:#2057e7; font-size:.77rem; font-weight:700; text-decoration:none; white-space:nowrap; }
    body.student-content-page .student-table-action:hover { text-decoration:underline; }
    body.student-content-page .student-favorites-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:15px; }
    body.student-content-page .student-favorite-card { display:flex; min-width:0; flex-direction:column; padding:17px; }
    body.student-content-page .student-favorite-header { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
    body.student-content-page .student-favorite-header h3 { margin:0 0 5px; color:#173575; font-size:1rem; font-weight:800; }
    body.student-content-page .student-favorite-header p { margin:0; color:#6b7da5; font-size:.78rem; }
    body.student-content-page .student-favorite-header button { display:grid; width:35px; height:35px; flex:0 0 35px; place-items:center; border:0; border-radius:50%; color:#e14362; background:#fff0f3; font-size:1rem; }
    body.student-content-page .student-favorite-details { display:grid; gap:9px; margin:18px 0; color:#5f719c; font-size:.8rem; }
    body.student-content-page .student-favorite-details i { width:17px; color:#4675e5; }
    body.student-content-page .student-favorite-actions { display:flex; align-items:center; justify-content:space-between; gap:8px; margin-top:auto; }
    body.student-content-page .student-favorite-actions span { color:#8793af; font-size:.72rem; }
    body.student-content-page .student-favorite-actions a { display:inline-flex; min-height:37px; align-items:center; gap:7px; padding:0 12px; border:1px solid #cbd9ff; border-radius:9px; color:#2156e5; font-size:.76rem; font-weight:700; text-decoration:none; }
    body.student-content-page .student-favorite-actions a:hover { color:#fff; background:#2156e5; }
    body.student-content-page .student-profile-card { padding:22px; }
    body.student-content-page .student-profile-grid { display:grid; grid-template-columns:1fr 1fr; gap:25px; }
    body.student-content-page .student-profile-photo { display:flex; align-items:center; flex-direction:column; margin-bottom:17px; }
    body.student-content-page .student-profile-photo>img,body.student-content-page .student-profile-placeholder { width:110px; height:110px; margin-bottom:11px; border:5px solid #e6efff; border-radius:50%; object-fit:cover; }
    body.student-content-page .student-profile-placeholder { display:grid; place-items:center; color:#fff; background:linear-gradient(145deg,#68a6ff,#2362df); font-size:2.8rem; }
    body.student-content-page .student-profile-photo label { margin-bottom:7px; color:#1c3979; font-size:.82rem; font-weight:700; }
    body.student-content-page .student-profile-docs { margin-top:19px; padding-top:17px; border-top:1px solid #e9edfa; }
    body.student-content-page .student-profile-docs h3 { display:flex; align-items:center; gap:8px; margin:0 0 15px; color:#173575; font-size:1rem; font-weight:800; }
    body.student-content-page .student-profile-docs h3 i { color:#2360ed; }
    body.student-content-page .student-profile-docs .form-text { color:#7785a6; font-size:.72rem; }
    body.student-content-page .student-profile-save { display:flex; justify-content:flex-end; margin-top:20px; }
    body.student-content-page .student-profile-save button { min-height:43px; padding:0 18px; border:0; border-radius:9px; color:#fff; background:#185cf0; font-size:.8rem; font-weight:800; }
    body.student-content-page .student-profile-save button:hover { background:#114bd2; }
    body.student-content-page footer { position:relative; overflow:hidden; padding:2.4rem 0 1.2rem; background:radial-gradient(ellipse at 92% 70%,rgba(27,96,226,.5),transparent 25%),linear-gradient(110deg,#0d2b63,#102e71 70%,#1744a6); }
    body.student-content-page footer::after { position:absolute; right:2%; bottom:-23px; color:rgba(4,22,63,.47); content:'\f64f'; font-family:'Font Awesome 6 Free'; font-size:8rem; font-weight:900; pointer-events:none; }
    body.student-content-page footer>.container { position:relative; z-index:1; }
    body.student-content-page footer h5 { font-size:.93rem; font-weight:800; }
    body.student-content-page footer p,body.student-content-page footer li,body.student-content-page footer .text-sm { font-size:.78rem; line-height:1.55; }
    html[data-theme="dark"] body.student-content-page { --student-ink:#e3ecff; --student-muted:#adbbdd; --student-line:#263864; --student-surface:#152345; --student-bg:#0d1730; background:#0d1730; color:#e3ecff; }
    html[data-theme="dark"] body.student-content-page .navbar { border-color:#263864; background:rgba(21,35,69,.96); }
    html[data-theme="dark"] body.student-content-page .navbar .nav-link,html[data-theme="dark"] body.student-content-page .navbar-brand { color:#d9e4ff!important; }
    html[data-theme="dark"] body.student-content-page .navbar .nav-link.active,html[data-theme="dark"] body.student-content-page .navbar .nav-link:hover { color:#7da7ff!important; }
    html[data-theme="dark"] body.student-content-page .student-page-hero { background-color:#17274d; background-image:linear-gradient(90deg,rgba(15,29,60,.97) 0%,rgba(15,29,60,.92) 38%,rgba(15,29,60,.64) 67%,rgba(15,29,60,.3) 100%),url('{{ asset('images/stg2.png') }}'); }
    html[data-theme="dark"] body.student-content-page .student-hero-copy h1,html[data-theme="dark"] body.student-content-page .student-surface-heading,html[data-theme="dark"] body.student-content-page .student-profile-docs h3,html[data-theme="dark"] body.student-content-page .student-favorite-header h3 { color:#e5edff; }
    html[data-theme="dark"] body.student-content-page .student-surface { border-color:#283a66; background:#152345; }
    html[data-theme="dark"] body.student-content-page .student-empty-state { border-color:#385a94; background:#172a4e; }
    html[data-theme="dark"] body.student-content-page .student-form-control { border-color:#354874; color:#e3ecff; background:#101c39; }
    html[data-theme="dark"] body.student-content-page .student-data-table th { color:#e3ecff; background:#1b2b50; }
    html[data-theme="dark"] body.student-content-page .student-data-table td { border-color:#263864; color:#b7c5e5; }
    @media(max-width:900px) {
        body.student-content-page .student-hero-art { right:3%; transform:scale(.82); transform-origin:right center; }
        body.student-content-page .student-skill-layout { grid-template-columns:1.2fr 1fr; }
        body.student-content-page .student-favorites-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media(max-width:767px) {
        body.student-content-page .student-page-hero,body.student-content-page .student-page-hero-inner { min-height:220px; }
        body.student-content-page .student-page-hero-inner { padding:18px 20px 25px; }
        body.student-content-page .student-hero-art { right:-13px; opacity:.42; transform:scale(.72); }
        body.student-content-page .student-hero-copy { max-width:75%; }
        body.student-content-page .student-page-content { width:calc(100% - 32px); }
        body.student-content-page .student-skill-layout,body.student-content-page .student-profile-grid { grid-template-columns:1fr; }
        body.student-content-page .student-profile-grid { gap:8px; }
        body.student-content-page .student-favorites-grid { grid-template-columns:1fr; }
    }
    @media(max-width:575px) {
        body.student-content-page .navbar .navbar-brand img { height:38px!important; }
        body.student-content-page .navbar .navbar-nav { padding-top:.35rem; }
        body.student-content-page .navbar .navbar-nav.ms-auto { align-items:stretch!important; }
        body.student-content-page .student-page-hero,body.student-content-page .student-page-hero-inner { min-height:205px; }
        body.student-content-page .student-page-hero { background-position:center,right center; }
        body.student-content-page .student-page-hero-inner { padding-inline:16px; }
        body.student-content-page .student-hero-copy { max-width:100%; }
        body.student-content-page .student-hero-copy h1 { max-width:100%; font-size:1.85rem; }
        body.student-content-page .student-hero-copy>p:not(.student-hero-kicker) { max-width:90%; font-size:.83rem; }
        body.student-content-page .student-hero-kicker { font-size:.63rem; }
        body.student-content-page .student-breadcrumb { max-width:100%; margin-bottom:13px; font-size:.66rem; }
        body.student-content-page .student-hero-art { top:29px; right:-72px; opacity:.22; transform:scale(.68); }
        body.student-content-page .student-page-content { width:calc(100% - 24px); padding-top:16px; }
        body.student-content-page .student-skill-panel,body.student-content-page .student-add-panel,body.student-content-page .student-profile-card { padding:15px; }
        body.student-content-page .student-applications-card { padding:10px; }
        body.student-content-page .student-data-table th,body.student-content-page .student-data-table td { padding:11px 9px; font-size:.71rem; }
        body.student-content-page .student-profile-save button { width:100%; }
        body.student-content-page footer { padding-top:2rem; }
    }
</style>
