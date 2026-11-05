<style>
    .team-page {
        padding: 96px 0 64px;
        min-height: calc(100svh - 95px);
    }
    .team-page .breadcrumb { margin-bottom: 24px; }
    .team-heading { margin-bottom: 32px; }
    .team-heading h1 { margin: 0 0 10px; font-size: 34px; line-height: 1.2; letter-spacing: -.8px; }
    .team-heading p { margin: 0; color: var(--muted); font-size: 14px; }
    .team-supervisor {
        display: grid;
        grid-template-columns: 160px minmax(0, 1fr);
        align-items: center;
        gap: 32px;
        padding: 28px;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: #f8fafc;
        margin-bottom: 40px;
    }
    .team-photo { position: relative; aspect-ratio: 1; border-radius: 12px; background: #f1f5f9; overflow: hidden; display: grid; place-items: center; }
    .team-photo > span { color: #94a3b8; font-size: 40px; font-weight: 600; }
    .team-photo img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center top; }
    .team-supervisor .eyebrow { color: var(--blue); }
    .team-supervisor h2 { margin: 10px 0 8px; font-size: 25px; line-height: 1.35; }
    .team-supervisor p { color: var(--muted); margin: 0; font-size: 14px; }
    .team-cohorts { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 32px; }
    .team-cohorts a { padding: 7px 14px; border: 1px solid var(--line); border-radius: 999px; font-size: 12px; }
    .team-cohorts a:hover { background: #f1f5f9; color: var(--blue); }
    .team-group { margin-bottom: 40px; }
    .team-group-heading { display: flex; justify-content: space-between; align-items: baseline; gap: 20px; border-bottom: 1px solid var(--line); padding-bottom: 14px; margin-bottom: 22px; }
    .team-group-heading h2 { margin: 0; font-size: 20px; font-weight: 600; }
    .team-group-heading span { font-size: 12px; color: var(--muted); }
    .team-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 24px; align-items: start; }
    .team-card { padding: 18px; border: 1px solid var(--line); border-radius: 14px; }
    .team-card h3 { margin: 17px 0 5px; font-size: 16px; line-height: 1.4; overflow-wrap: anywhere; }
    .team-card .team-role { font-size: 12px; color: var(--muted); margin: 0 0 14px; }
    .team-status { display: inline-block; font-size: 10px; padding: 4px 9px; background: #f1f5f9; color: #64748b; border-radius: 999px; }
    .team-status--active { background: #eff6ff; color: var(--blue); }
    .team-detail { margin-top: 18px; border-top: 1px solid var(--line); padding-top: 12px; }
    .team-detail summary { cursor: pointer; font-size: 12px; color: var(--blue); }
    .team-detail h4 { font-size: 12px; margin: 18px 0 8px; }
    .team-detail ul { padding-left: 17px; margin: 0; }
    .team-detail li, .team-detail p { font-size: 12px; color: var(--muted); margin-bottom: 8px; }
    .team-detail li a:hover { color: var(--blue); }
    .team-detail li span { display: block; font-size: 11px; }
    .team-contact { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 16px; font-size: 12px; color: var(--blue); overflow-wrap: anywhere; }
    @media(max-width:1000px) { .team-grid { grid-template-columns: repeat(3,minmax(0,1fr)); } }
    @media(max-width:700px) {
        .team-page { padding-top: 82px; }
        .team-heading h1 { font-size: 28px; }
        .team-supervisor { grid-template-columns: 90px minmax(0,1fr); padding: 18px; gap: 18px; }
        .team-supervisor h2 { font-size: 19px; }
        .team-grid { grid-template-columns: repeat(2,minmax(0,1fr)); gap: 12px; }
        .team-card { padding: 12px; }
    }
    @media(max-width:360px) { .team-grid { grid-template-columns: 1fr; } }
    .team-page .team-grid { grid-template-columns: repeat(auto-fill, minmax(0, 180px)) !important; align-items: stretch; }
    .team-page .team-card { display: flex; flex-direction: column; min-height: 282px; box-sizing: border-box; }
    .team-page .team-card h3 { min-height: 54.6px; margin-top: 14px; }
    .team-page .team-card .team-role { min-height: 34px; line-height: 17px; }
    .team-page .team-card .team-status { align-self: flex-start; }
    .team-page .team-card .team-detail { margin-top: auto; padding-top: 12px; }
    @media(max-width:420px) { .team-page .team-grid { grid-template-columns: repeat(2,minmax(0,1fr)) !important; } }
</style>
