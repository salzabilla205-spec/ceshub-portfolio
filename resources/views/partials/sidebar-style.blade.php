<style>
    :root {
        --sidebar-width: 240px;
        --sidebar-mini: 64px;
        --topbar-h: 64px;
        --transition: .25s ease;
    }

    body.has-sidebar { overflow-x: hidden; }

    .app-sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        top: 0; left: 0;
        z-index: 200;
        background: #fff;
        border-right: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        transition: width var(--transition), transform var(--transition);
    }

    .app-sidebar .logo-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0 16px;
        min-height: var(--topbar-h);
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        flex-shrink: 0;
    }

    .app-sidebar.close { width: var(--sidebar-mini); }
    .app-sidebar.close .logo-text { display: none; }
    .app-sidebar.close .nav-section-title { display: none; }
    .app-sidebar.close .nav-link span,
    .app-sidebar.close .nav-sub-link span { display: none; }
    .app-sidebar.close .nav-link,
    .app-sidebar.close .nav-sub-link { justify-content: center; padding-left: 0; padding-right: 0; }
    .app-sidebar .nav-icon { flex-shrink: 0; }
    #app-sidebar-toggle { display: flex; }

    .app-sidebar .nav-link {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px; margin: 2px 10px;
        border-radius: 10px;
        font-size: 14px; font-weight: 500;
        color: #475569;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
    }
    .app-sidebar .nav-link:hover { background: #f8fafc; color: #0f172a; }

    .app-sidebar .nav-section-title {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px; margin: 2px 10px;
        font-size: 14px; font-weight: 500;
        color: #475569;
    }

    .app-sidebar .nav-sub-link {
        display: flex; align-items: center; gap: 10px;
        padding: 8px 14px 8px 25px;
        margin: 2px 10px;
        border-radius: 5px;
        font-size: 13px; font-weight: 500;
        color: #58677A;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
    }
    .app-sidebar .nav-sub-link:hover { background: #f8fafc; color: #475569; }

    /* ==== BARU: bagian "Dashboard" yang bisa dibuka/tutup (accordion) ==== */
    .app-sidebar .nav-section-toggle {
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
        padding: 10px 14px; margin: 2px 10px;
        border-radius: 10px;
        font-size: 14px; font-weight: 500;
        color: #475569;
        cursor: pointer;
        user-select: none;
        background: none;
        border: none;
        width: calc(100% - 20px);
        text-align: left;
        font-family: inherit;
    }
    .app-sidebar .nav-section-toggle:hover { background: #f8fafc; color: #0f172a; }
    .app-sidebar .nav-section-toggle .chevron {
        flex-shrink: 0;
        transition: transform var(--transition);
        color: #94a3b8;
    }
    .app-sidebar .nav-section-toggle .chevron.open { transform: rotate(90deg); }
    .app-sidebar.close .nav-section-toggle span:not(.chevron) { display: none; }
    .app-sidebar.close .nav-section-toggle { justify-content: center; }
    .app-sidebar.close .nav-section-toggle .chevron { display: none; }

    .app-sidebar .dashboard-accordion {
        max-height: 0;
        overflow: hidden;
        transition: max-height var(--transition);
    }
    .app-sidebar .dashboard-accordion.open { max-height: 800px; }
    .app-sidebar.close .dashboard-accordion { display: none; }

    .app-sidebar .project-toggle {
        display: flex; align-items: center; gap: 10px;
        padding: 8px 14px 8px 25px;
        margin: 2px 10px;
        border-radius: 5px;
        font-size: 13px; font-weight: 500;
        color: #94a3b8;
        background: #f8fafc;
        cursor: pointer;
        border: none;
        width: calc(100% - 20px);
        text-align: left;
        white-space: nowrap;
        overflow: hidden;
        font-family: inherit;
    }
    .app-sidebar .project-toggle:hover { background: #f1f5f9; color: #64748b; }

    .app-sidebar .project-submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height var(--transition);
    }
    .app-sidebar .project-submenu.open { max-height: 220px; }

    .app-sidebar .project-submenu .nav-sub-link2 {
        display: flex; align-items: center; gap: 10px;
        padding: 7px 14px 7px 40px;
        margin: 1px 10px;
        border-radius: 5px;
        font-size: 12.5px; font-weight: 500;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
    }
    .app-sidebar .project-submenu a.nav-sub-link2:hover { opacity: .75; }

    /* Tema warna per project */
    .theme-living-lab { color: #1e3a5f; }
    .app-sidebar .project-submenu a.theme-living-lab:hover { background: #eaf0f7; }
    .theme-lab-scale { color: #2f5d50; }
    .app-sidebar .project-submenu a.theme-lab-scale:hover { background: #e9f2ef; }
    .theme-jamur { color: #6b4423; }
    .app-sidebar .project-submenu a.theme-jamur:hover { background: #f5eee6; }

    .app-topbar {
        position: fixed; top: 0; left: var(--sidebar-width); right: 0;
        min-height: var(--topbar-h);
        display: none;
        align-items: center; gap: 12px;
        padding: 0 20px;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        z-index: 100;
        transition: left var(--transition);
    }
    .app-topbar.expand { left: var(--sidebar-mini); }

    .app-hamburger {
        display: none;
        width: 38px; height: 38px;
        align-items: center; justify-content: center;
        border: none; background: transparent; color: #475569;
        cursor: pointer;
        flex-shrink: 0;
    }
    
    .app-topbar-brand { display: none; }

    .main, .app-main {
        margin-left: var(--sidebar-width);
        padding-top: 0;
        transition: margin-left var(--transition);
        min-height: 100vh;
    }
    .main.expand, .app-main.expand { margin-left: var(--sidebar-mini); }
    .main.expand .max-w-6xl, .app-main.expand .max-w-6xl {
        max-width: 96rem;
        transition: max-width var(--transition);
    }

    #app-sidebar-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(15, 23, 42, .4);
        z-index: 150;
    }

    @media (max-width: 900px) {
        .app-sidebar { transform: translateX(-100%); }
        .app-sidebar.mobile-open { transform: translateX(0); }
        .app-hamburger { display: flex; }
        .app-topbar, .app-topbar.expand { left: 0 !important; display: flex; }
        .main, .main.expand, .app-main, .app-main.expand { margin-left: 0 !important; padding-top: var(--topbar-h) !important; }
        .app-topbar-brand { display: flex; }
        #app-sidebar-toggle { display: none; }
    }
</style>
