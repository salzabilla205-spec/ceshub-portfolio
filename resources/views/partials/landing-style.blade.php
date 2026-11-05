@include('partials.favicon')
<link rel="stylesheet" href="{{ asset('css/site-motion.css') }}?v={{ file_exists(public_path('css/site-motion.css')) ? filemtime(public_path('css/site-motion.css')) : '20261017' }}">
<script defer src="{{ asset('js/media-carousel.js') }}"></script>
<script defer src="{{ asset('js/site-motion.js') }}?v={{ file_exists(public_path('js/site-motion.js')) ? filemtime(public_path('js/site-motion.js')) : '20261017' }}"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dashboard-typography.css') }}?v={{ file_exists(public_path('css/dashboard-typography.css')) ? filemtime(public_path('css/dashboard-typography.css')) : '20261017' }}">
<link rel="stylesheet" href="{{ asset('css/site-theme.css') }}?v={{ file_exists(public_path('css/site-theme.css')) ? filemtime(public_path('css/site-theme.css')) : '20261017' }}">
<script src="{{ asset('js/site-theme.js') }}?v={{ filemtime(public_path('js/site-theme.js')) }}"></script>
<style>
    :root {
        --ink:#0f172a;
        --muted:#64748b;
        --line:#e2e8f0;
        --blue:#0865b5;
    }
    * {
        box-sizing:border-box;
    }
    html {
        scroll-behavior:smooth;
        scroll-padding-top:30px;
    }
    body {
        margin:0;
        color:var(--ink);
        background:#fff;
        font-family:Inter,Arial,sans-serif;
        line-height:1.65;
    }
    a {
        color:inherit;
        text-decoration:none;
    }
    button,summary {
        font:inherit;
    }
    a:focus-visible,summary:focus-visible {
        outline:3px solid #60a5fa;
        outline-offset:6px;
    }
    .wrap {
        width:min(1320px,calc(100% - 80px));
        margin:auto;
    }
    .skip {
        position:absolute;
        top:-80px;
        left:20px;
        z-index:50;
        background:#fff;
        color:var(--ink);
        padding:12px;
    }
    .skip:focus {
        top:10px;
    }
    .hero {
        position:relative;
        min-height:760px;
        min-height:max(760px,100svh);
        background:#334155;
        color:#fff;
        isolation:isolate;
        display:flex;
        flex-direction:column;
    }
    .hero-photo {
        position:absolute;
        inset:0;
        width:100%;
        height:100%;
        object-fit:cover;
        z-index:-2;
    }
    .hero:after {
        content:'';
        position:absolute;
        inset:0;
        background:linear-gradient(180deg,#0f172a66 0%,#0f172a33 32%,#0f172ab8 100%);
        z-index:-1;
    }
    .header {
        width:100%;
        border-bottom:1px solid #ffffff26;
    }
    .nav {
        min-height:112px;
        display:flex;
        justify-content:flex-start;
        align-items:center;
        gap:30px;
    }
    .brand {
        display:flex;
        gap:16px;
        align-items:center;
    }
    .brand img {
        width:56px;
        height:56px;
        object-fit:contain;
        background:#fff;
        border-radius:50%;
        padding:7px;
    }
    .brand strong {
        font-size:24px;
        line-height:1.2;
        display:block;
    }
    .brand small {
        font-size:13px;
        color:#e2e8f0;
    }
    .desktop-nav {
        display:flex;
        align-items:center;
        gap:36px;
        font-size:16px;
        font-weight:500;
    }
    .desktop-nav>a:hover {
        opacity:.75;
    }
    .dropdown {
        position:relative;
    }
    .dropdown summary {
        list-style:none;
        cursor:pointer;
        display:flex;
        align-items:center;
        gap:18px;
        background:#ffffff24;
        border:1px solid #ffffff35;
        border-radius:999px;
        padding:16px 24px;
    }
    .dropdown summary::-webkit-details-marker {
        display:none;
    }
    .dropdown[open] summary {
        background:#ffffff40;
    }
    .dropdown-menu {
        position:absolute;
        top:calc(100% + 12px);
        right:0;
        width:255px;
        padding:9px;
        background:#fff;
        color:var(--ink);
        border-radius:14px;
        box-shadow:0 16px 50px #0f172a30;
        z-index:20;
    }
    .dropdown-menu a {
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:12px 15px;
        font-size:13px;
        border-radius:8px;
    }
    .dropdown-menu a:hover {
        background:#f1f5f9;
        color:var(--blue);
    }
    .mobile-nav {
        display:none;
    }
    .hero-content {
        display:grid;
        grid-template-columns:1.25fr 1fr;
        gap:70px;
        align-items:end;
        margin-top:auto;
        padding-top:140px;
        padding-bottom:72px;
    }
    .hero h1 {
        font-size:clamp(62px,6.9vw,112px);
        line-height:1.08;
        letter-spacing:-5px;
        font-weight:500;
        margin:0;
    }
    .hero-copy {
        padding-bottom:0;
    }
    .values {
        display:flex;
        gap:23px;
        flex-wrap:wrap;
        font-size:13px;
        margin-bottom:32px;
    }
    .values span:before {
        content:'◆';
        font-size:10px;
        margin-right:9px;
    }
    .hero-copy h2 {
        font-size:clamp(24px,2.8vw,40px);
        line-height:1.3;
        font-weight:500;
        letter-spacing:-1px;
        margin:0 0 25px;
    }
    .hero-copy p {
        font-size:14px;
        color:#e2e8f0;
        max-width:480px;
    }
    .hero-link {
        display:inline-flex;
        gap:36px;
        align-items:center;
        border-bottom:1px solid #ffffff88;
        padding:8px 0;
        margin-top:12px;
        font-size:14px;
    }
    .hero-meta {
        display:flex;
        justify-content:space-between;
        gap:20px;
        padding:20px 0;
        border-top:1px solid #ffffff40;
        font-size:11px;
        color:#e2e8f0;
    }
    .section {
        padding:100px 0;
    }
    .eyebrow {
        text-transform:uppercase;
        letter-spacing:2px;
        font-size:10px;
        font-weight:600;
        color:#94a3b8;
    }
    .section h2 {
        font-size:clamp(30px,3.7vw,48px);
        font-weight:500;
        line-height:1.2;
        letter-spacing:-1.5px;
        margin:18px 0 22px;
    }
    .about-grid {
        display:grid;
        grid-template-columns:1.3fr 1fr;
        gap:100px;
        align-items:center;
    }
    .about-grid p {
        font-size:15px;
        color:var(--muted);
    }
    .tags {
        display:flex;
        flex-wrap:wrap;
        gap:10px;
        margin-top:28px;
    }
    .tags span {
        border:1px solid var(--line);
        padding:8px 15px;
        border-radius:999px;
        color:#475569;
        font-size:12px;
    }
    .facts {
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:30px;
    }
    .fact {
        border-top:1px solid var(--line);
        padding-top:18px;
    }
    .fact strong {
        font-size:36px;
        font-weight:500;
        display:block;
    }
    .fact span {
        color:var(--muted);
        font-size:12px;
    }
    .pale {
        background:#f8fafc;
        border-block:1px solid #f1f5f9;
    }
    .section-head {
        display:flex;
        align-items:end;
        justify-content:space-between;
        gap:30px;
        margin-bottom:36px;
    }
    .section-head p {
        color:var(--muted);
        font-size:14px;
        max-width:560px;
        margin:0;
    }
    .text-link {
        font-size:13px;
        border-bottom:1px solid #cbd5e1;
        padding-bottom:6px;
        white-space:nowrap;
    }
    .cards {
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:26px;
    }
    .card {
        border:1px solid var(--line);
        border-radius:14px;
        overflow:hidden;
        background:#fff;
    }
    .card-image {
        aspect-ratio:16/10;
        background:#e2e8f0;
        display:grid;
        place-items:center;
        color:#94a3b8;
        font-size:42px;
    }
    .card-image img {
        width:100%;
        height:100%;
        object-fit:cover;
    }
    .card-body {
        padding:25px;
    }
    .card small {
        font-size:10px;
        color:var(--muted);
    }
    .card h3 {
        font-size:20px;
        line-height:1.4;
        font-weight:500;
        margin:12px 0;
        overflow-wrap:anywhere;
    }
    .card p {
        font-size:13px;
        color:var(--muted);
    }
    .news-detail {
        border-top:1px solid var(--line);
        padding-top:14px;
        margin-top:20px;
    }
    .news-detail summary {
        cursor:pointer;
        font-size:12px;
        font-weight:600;
    }
    .news-detail p {
        white-space:pre-line;
    }
    .empty {
        grid-column:1/-1;
        padding:36px;
        border:1px dashed #cbd5e1;
        border-radius:12px;
        color:var(--muted);
        font-size:14px;
    }
    .team {
        display:grid;
        grid-template-columns:repeat(4,minmax(0,1fr));
        gap:24px;
    }
    .member {
        padding:26px;
        border:1px solid var(--line);
        border-radius:14px;
    }
    .avatar {
        width:72px;
        height:72px;
        border-radius:50%;
        background:#f1f5f9;
        display:grid;
        place-items:center;
        overflow:hidden;
        color:#64748b;
        font-size:26px;
        margin-bottom:24px;
    }
    .avatar img {
        width:100%;
        height:100%;
        object-fit:cover;
    }
    .member h3 {
        font-size:15px;
        margin:0 0 7px;
        overflow-wrap:anywhere;
    }
    .member p {
        font-size:12px;
        color:var(--muted);
        margin:0;
    }
    .project-list {
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:18px;
    }
    .project-link {
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:25px;
        padding:26px;
        border:1px solid var(--line);
        border-radius:12px;
        background:#fff;
        transition:border-color .2s,transform .2s;
    }
    .project-link:hover {
        border-color:#94a3b8;
        transform:translateY(-3px);
    }
    .project-link strong {
        font-size:17px;
        font-weight:500;
        display:block;
    }
    .project-link small {
        font-size:11px;
        color:var(--muted);
    }
    .project-link>span {
        font-size:24px;
        color:var(--blue);
    }
    .footer {
        padding:20px 0;
    }
    .footer-inner {
        display:flex;
        justify-content:space-between;
        gap:20px;
        font-size:11px;
        color:var(--muted);
    }
    @media(max-width:1000px) {
        .wrap {
            width:calc(100% - 48px);
        }
        .desktop-nav {
            gap:22px;
            font-size:14px;
        }
        .brand small {
            font-size:11px;
        }
        .hero-content {
            gap:40px;
        }
        .hero h1 {
            font-size:76px;
            letter-spacing:-3px;
        }
        .about-grid {
            gap:45px;
        }
        .cards,.project-list {
            grid-template-columns:repeat(2,minmax(0,1fr));
        }
        .team {
            grid-template-columns:repeat(3,minmax(0,1fr));
        }
    }
    @media(max-width:700px) {
        .wrap {
            width:calc(100% - 36px);
        }
        .hero {
            min-height:820px;
            min-height:max(820px,100svh);
        }
        .nav {
            min-height:88px;
            gap:14px;
        }
        .brand img {
            width:44px;
            height:44px;
        }
        .brand {
            gap:10px;
        }
        .brand strong {
            font-size:21px;
        }
        .brand small {
            font-size:10px;
        }
        .desktop-nav {
            display:none;
        }
        .mobile-nav {
            display:block;
        }
        .mobile-nav summary {
            padding:10px 16px;
            font-size:13px;
        }
        .mobile-nav .dropdown-menu {
            width:250px;
        }
        .mobile-project-label {
            display:block;
            margin:10px 15px 4px;
            color:#94a3b8;
            font-size:10px;
            letter-spacing:1px;
            text-transform:uppercase;
        }
        .hero-content {
            grid-template-columns:1fr;
            gap:32px;
            padding-top:95px;
            padding-bottom:35px;
        }
        .hero h1 {
            font-size:clamp(52px,12vw,78px);
            letter-spacing:-2.5px;
        }
        .hero-copy h2 {
            font-size:27px;
            max-width:450px;
        }
        .values {
            gap:16px;
            font-size:11px;
            margin-bottom:23px;
        }
        .hero-copy p {
            font-size:13px;
        }
        .hero-meta {
            flex-direction:row;
            align-items:start;
            gap:14px;
            font-size:10px;
            padding:17px 0;
        }
        .hero-meta>span {
            min-width:0;
        }
        .hero-meta>span:last-child {
            text-align:right;
            max-width:58%;
        }
        .section {
            padding:60px 0;
        }
        .section-head {
            align-items:start;
            flex-direction:column;
            gap:20px;
        }
        .about-grid {
            grid-template-columns:1fr;
            gap:35px;
        }
        .cards,.project-list {
            grid-template-columns:1fr;
        }
        .team {
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:12px;
        }
        .member {
            padding:20px 14px;
        }
        .footer-inner {
            flex-direction:row;
            align-items:flex-start;
            gap:16px;
        }
        .footer-inner>span {
            min-width:0;
            flex:1;
        }
        .footer-inner>a {
            flex-shrink:0;
            text-align:right;
        }
        .facts {
            gap:20px;
        }
    }
    @media(prefers-reduced-motion:reduce) {
        html {
            scroll-behavior:auto;
        }
        * {
            transition:none!important;
        }
    }
    html {
        scroll-padding-top:90px;
    }
    .header {
        position:fixed;
        top:0;
        left:0;
        z-index:100;
        color:#fff;
        background:transparent;
        transition:background .25s ease,color .25s ease,box-shadow .25s ease,border-color .25s ease;
    }
    .nav {
        min-height:76px;
    }
    .brand {
        gap:11px;
    }
    .brand img {
        width:42px;
        height:42px;
        padding:5px;
    }
    .brand strong {
        font-size:20px;
    }
    .brand small {
        font-size:11px;
        transition:color .25s ease;
    }
    .desktop-nav {
        gap:28px;
        font-size:13px;
    }
    .desktop-nav>a {
        position:relative;
        padding:10px 0;
        transition:color .2s ease;
    }
    .desktop-nav>a:after {
        content:'';
        position:absolute;
        left:0;
        right:0;
        bottom:2px;
        height:2px;
        background:currentColor;
        transform:scaleX(0);
        transform-origin:left;
        transition:transform .2s ease;
    }
    .desktop-nav>a:hover:after,.desktop-nav>a[aria-current="page"]:after {
        transform:scaleX(1);
    }
    .desktop-nav>a:hover {
        opacity:1;
    }
    .dropdown summary {
        padding:10px 19px;
        gap:15px;
        font-size:13px;
        transition:background .2s ease,border-color .2s ease;
    }
    .dropdown summary>span {
        display:inline-block;
        transition:transform .2s ease;
    }
    .dropdown[open] summary>span {
        transform:rotate(180deg);
    }
    .dropdown-menu {
        max-height:calc(100svh - 105px);
        overflow-y:auto;
        animation:menu-appear .18s ease-out;
    }
    .header.is-scrolled {
        background:rgba(255,255,255,.97);
        color:#0f172a;
        border-bottom-color:#e2e8f0;
        box-shadow:0 4px 24px #0f172a09;
        backdrop-filter:blur(12px);
    }
    .header.is-scrolled .brand small {
        color:#94a3b8;
    }
    .header.is-scrolled .dropdown summary {
        background:#f8fafc;
        border-color:#e2e8f0;
    }
    .header.is-scrolled .dropdown summary:hover,.header.is-scrolled .dropdown[open] summary {
        background:#eaf2fb;
        border-color:#bfdbfe;
    }
    .header.is-scrolled .desktop-nav>a:hover,.header.is-scrolled .desktop-nav>a[aria-current="page"] {
        color:var(--blue);
    }
    .desktop-nav .dropdown.is-active>summary {
        border-color:currentColor;
    }
    .header.is-scrolled .dropdown.is-active>summary {
        color:var(--blue);
        border-color:#bfdbfe;
    }
    .hero {
        padding-top:76px;
    }
    .skip:focus {
        z-index:110;
    }
    @keyframes menu-appear {
        from {
            opacity:0;
            transform:translateY(-6px);
        }
        to {
            opacity:1;
            transform:translateY(0);
        }
    }
    @media(max-width:700px) {
        .nav {
            min-height:66px;
        }
        .hero {
            padding-top:66px;
        }
        .brand img {
            width:36px;
            height:36px;
        }
        .brand strong {
            font-size:18px;
        }
        .brand small {
            font-size:9px;
        }
        .mobile-nav summary {
            padding:8px 14px;
            font-size:12px;
        }
    }
    @media(prefers-reduced-motion:reduce) {
        .dropdown-menu {
            animation:none;
        }
    }
    .hero {
        min-height:100svh;
    }
    .hero-content {
        padding-top:40px;
        padding-bottom:14px;
        gap:50px;
        align-items:end;
    }
    .hero h1 {
        font-size:clamp(52px,min(6.9vw,12svh),112px);
    }
    .hero-copy h2 {
        font-size:clamp(24px,min(2.8vw,4.8svh),40px);
    }
    .values {
        margin-bottom:24px;
    }
    .hero-meta {
        flex-shrink:0;
        padding:16px 0;
    }
    @media(max-width:700px) {
        .hero {
            min-height:100svh;
        }
        .hero-content {
            gap:22px;
            padding-top:30px;
            padding-bottom:12px;
        }
        .hero h1 {
            font-size:clamp(40px,min(12vw,8svh),68px);
        }
        .hero-copy h2 {
            font-size:clamp(21px,3.3svh,27px);
            margin-bottom:14px;
        }
        .hero-copy p {
            font-size:12px;
            margin:10px 0;
        }
        .values {
            margin-bottom:14px;
        }
        .hero-link {
            margin-top:4px;
            padding:6px 0;
            font-size:12px;
        }
        .hero-meta {
            padding:12px 0;
        }
    }
    @media(max-height:600px) and (min-width:701px) {
        .hero-content {
            padding-top:20px;
            padding-bottom:12px;
        }
        .hero h1 {
            font-size:clamp(38px,11svh,66px);
        }
        .hero-copy h2 {
            font-size:24px;
            margin-bottom:12px;
        }
        .values {
            margin-bottom:12px;
        }
        .hero-copy p {
            font-size:12px;
            margin:8px 0;
        }
        .hero-link {
            margin-top:4px;
        }
        .hero-meta {
            padding:10px 0;
        }
    }
    .hero-meta {
        padding-top:10px;
        padding-bottom:0;
        transform:translateY(17px);
    }
    @media(max-width:700px) {
        .hero-meta {
            padding-top:10px;
            padding-bottom:0;
        }
    }
    .brand>span {
        display:flex;
        flex-direction:column;
        gap:3px;
    }
    .brand strong {
        line-height:1.05;
    }
    .brand small {
        display:block;
        line-height:1.2;
    }
    .news-page {
        padding-top:76px;
        min-height:calc(100svh - 95px);
    }
    .news-page .section {
        padding-top:55px;
    }
    .breadcrumb {
        font-size:12px;
        color:#94a3b8;
        margin-bottom:35px;
    }
    .news-heading {
        max-width:720px;
        margin-bottom:40px;
    }
    .news-heading h1 {
        font-size:clamp(34px,4vw,52px);
        line-height:1.2;
        font-weight:500;
        letter-spacing:-1.5px;
        margin:15px 0;
    }
    .news-heading p {
        font-size:14px;
        color:var(--muted);
    }
    .news-count {
        display:flex;
        justify-content:space-between;
        gap:20px;
        border-bottom:1px solid var(--line);
        padding-bottom:18px;
        margin-bottom:28px;
        font-size:13px;
        color:var(--muted);
    }
    .pagination {
        display:flex;
        justify-content:center;
        align-items:center;
        gap:20px;
        margin-top:36px;
        font-size:13px;
    }
    .pagination a {
        padding:9px 16px;
        border:1px solid var(--line);
        border-radius:8px;
    }
    .pagination a:hover {
        background:#f1f5f9;
    }
    @media(max-width:700px) {
        .news-page {
            padding-top:66px;
        }
        .news-page .section {
            padding-top:32px;
        }
    }
    /* Keep homepage section titles close to their supporting text. */
    #team-title, #news-title, #project-title { margin-bottom:0; }
    #team-title + p, #news-title + p, #project-title + p { margin-top:4px; margin-bottom:0; }
    /* Shared public shell: keep navigation right and the footer below the page. */
    .header>.nav { width:calc(100% - 48px); max-width:none; }
    .header .nav>.desktop-nav { margin-inline-start:auto; flex-shrink:0; }
    body:has(>.header) { display:flex; flex-direction:column; min-height:100vh; min-height:100dvh; }
    body:has(>.header)>main,body:has(>.header)>.digital-twin-page { flex:1 0 auto; min-height:0; }
    body:has(>.header)>.footer { flex-shrink:0; margin-top:auto; }
    @media(max-width:820px) {
        .header .nav>.header-actions { margin-inline-start:auto; }
    }
    @media(max-width:600px) {
        body>.header>.nav { width:calc(100% - 40px); min-height:62px; gap:8px; }
        .header .nav>.brand { flex:1; min-width:0; gap:9px; }
        .header .nav>.brand img { width:32px; height:32px; flex-shrink:0; padding:3px; border-radius:8px; background:#fff; }
        .header .nav>.brand strong { display:none; }
        .header .nav>.brand small { display:block; font-size:clamp(12px,3.4vw,14px); font-weight:650; line-height:1.3; color:inherit; letter-spacing:-.25px; }
        .header .nav>.header-actions { gap:4px; flex-shrink:0; }
        .header .nav .mobile-nav>summary { width:38px; height:38px; min-height:38px; border:1px solid var(--line)!important; border-radius:11px; color:inherit; background:transparent!important; }
        .header:not(.is-scrolled) .nav .mobile-nav>summary { border-color:#ffffff66!important; }
        body:has(>.header):not(:has(>.hero)) :is(.lab-page,.hess-main,.team-page,.research-page,.research-detail) { padding-top:76px; }
    }
</style>
