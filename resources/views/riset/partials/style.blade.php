<style>
    .research-page { padding: 96px 0 64px; min-height: calc(100svh - 95px); }
    .research-page .breadcrumb { margin-bottom: 18px; }
    .research-heading { margin-bottom: 24px; }
    .research-heading h1 { font-size: 32px; line-height: 1.2; letter-spacing: -.8px; margin: 0 0 8px; }
    .research-heading p { font-size: 14px; color: var(--muted); margin: 0; }
    .research-layout { display: grid; grid-template-columns: minmax(0, 1fr) 280px; gap: 32px; align-items: start; }
    .research-search { display: flex; gap: 8px; margin-bottom: 14px; }
    .research-search input { flex: 1; min-width: 0; border: 1px solid var(--line); border-radius: 8px; padding: 12px 15px; font: inherit; font-size: 13px; color: var(--ink); background: #fff; }
    .research-search button { border: 0; border-radius: 8px; background: var(--blue); color: white; padding: 0 20px; cursor: pointer; }
    .research-search input:focus-visible, .research-search button:focus-visible { outline: 3px solid #93c5fd; outline-offset: 2px; }
    .research-topics { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 28px; }
    .research-topics a { padding: 7px 13px; font-size: 11px; border: 1px solid var(--line); border-radius: 999px; color: #475569; }
    .research-topics a:hover { background: #f1f5f9; }
    .research-topics a[aria-current] { color: #fff; background: var(--ink); border-color: var(--ink); }
    .research-section-title { font-size: 18px; margin: 0 0 16px; font-weight: 600; letter-spacing: -.3px; }
    .research-featured { display: grid; grid-template-columns: 1.3fr 1fr; gap: 20px; margin-bottom: 30px; }
    .research-featured--single { grid-template-columns: 1fr; }
    .research-story { display: grid; grid-template-columns: 120px minmax(0, 1fr); gap: 16px; padding: 18px 0; border-bottom: 1px solid var(--line); }
    .research-story:first-child { padding-top: 0; }
    .research-story__image { min-width: 0; background: #f1f5f9; border-radius: 10px; overflow: hidden; aspect-ratio: 4/3; display: grid; place-items: center; align-self: start; color: #94a3b8; }
    .research-story__image img { width: 100%; height: 100%; object-fit: cover; }
    .research-story__image>span { font-size: 24px; font-weight: 600; text-align: center; }
    .research-story__image small { display: block; font-size: 8px; font-weight: 400; }
    .research-story__category { font-size: 9px; font-weight: 600; color: var(--blue); text-transform: uppercase; letter-spacing: .3px; }
    .research-story h3 { margin: 5px 0 8px; font-size: 15px; font-weight: 600; line-height: 1.4; overflow-wrap: anywhere; }
    .research-story h3 a:hover { color: var(--blue); }
    .research-story p { color: var(--muted); font-size: 12px; margin: 0 0 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .research-story__meta { display: flex; flex-wrap: wrap; gap: 8px; font-size: 10px; color: var(--muted); }
    .research-story--featured { display: block; padding: 0; border: 1px solid var(--line); border-radius: 12px; overflow: hidden; align-self: start; }
    .research-story--featured .research-story__image { aspect-ratio: 16/10; border-radius: 0; }
    .research-story--featured .research-story__body { padding: 18px; }
    .research-story--featured h3 { font-size: 21px; letter-spacing: -.4px; }
    .research-story--featured p { -webkit-line-clamp: 3; }
    .research-aside { border-left: 1px solid var(--line); padding-left: 24px; }
    .research-aside section { padding-bottom: 24px; margin-bottom: 24px; border-bottom: 1px solid var(--line); }
    .research-aside h2 { font-size: 17px; margin: 0 0 18px; }
    .research-topic-list { list-style: none; margin: 0; padding: 0; }
    .research-topic-list a { display: flex; justify-content: space-between; gap: 12px; font-size: 12px; padding: 12px 0; }
    .research-topic-list a:hover, .research-latest a:hover { color: var(--blue); }
    .research-topic-list span { background: #f1f5f9; border-radius: 6px; padding: 1px 7px; color: var(--muted); }
    .research-latest { padding-left: 20px; margin: 0; }
    .research-latest li { padding: 0 0 16px 5px; font-size: 12px; }
    .research-latest time { display: block; font-size: 10px; color: var(--muted); margin-top: 5px; }
    .research-about { background: #f8fafc; padding: 20px; border-radius: 10px; }
    .research-about p, .research-aside .empty-label { font-size: 12px; color: var(--muted); }
    .research-about a, .research-reset { font-size: 12px; color: var(--blue); }
    .research-result-count { font-size: 12px; color: var(--muted); margin: -6px 0 20px; }
    @media(max-width:1100px) { .research-layout { grid-template-columns: minmax(0,1fr) 240px; gap: 24px; } .research-featured { grid-template-columns: 1fr; } }
    @media(max-width:800px) { .research-layout { grid-template-columns: 1fr; } .research-aside { border-left: 0; padding-left: 0; border-top: 1px solid var(--line); padding-top: 28px; } }
    @media(max-width:700px) { .research-page { padding-top: 82px; } .research-heading h1 { font-size: 28px; } .research-story { grid-template-columns: 96px minmax(0,1fr); gap: 12px; } .research-story--featured { display: block; } }
    .research-page .research-story:not(.research-story--featured) { grid-template-columns: 180px minmax(0,1fr); gap: 20px; padding: 16px; align-items: center; }
    .research-page .research-story:not(.research-story--featured) .research-story__image { width: 100%; height: 136px !important; max-height: 136px !important; aspect-ratio: auto; min-width: 0; align-self: center; box-sizing: border-box; }
    .research-page .research-story__body { min-width: 0; }
    .research-page .research-story:not(.research-story--featured) .research-story__body { padding: 0; }
    .research-page .research-story:not(.research-story--featured) .research-story__image > .media-carousel { width: 100%; height: 100%; min-width: 0; aspect-ratio: auto; }
    @media(max-width:700px) { .research-page .research-story:not(.research-story--featured) { grid-template-columns: 96px minmax(0,1fr); gap: 14px; padding: 12px; } .research-page .research-story:not(.research-story--featured) .research-story__image { height: 90px !important; max-height: 90px !important; } }
</style>
