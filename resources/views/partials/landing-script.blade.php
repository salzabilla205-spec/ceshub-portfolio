<script>
    const header = document.querySelector('.header');
    const sectionIds = ['beranda', 'kabar-riset', 'tim', 'project'];
    const navLinks = document.querySelectorAll('.header nav a');
    const projectMenu = document.querySelector('.desktop-nav .dropdown');
    let scrollPending = false;
    function updateHeader() {
        header.classList.toggle('is-scrolled', {{ request()->routeIs('home') ? 'false' : 'true' }} || window.scrollY > 24);
        const marker = header.offsetHeight + 100;
        let activeId = 'beranda';
        sectionIds.forEach(id => {
            const section = document.getElementById(id);
            if (section && section.getBoundingClientRect().height > 0 && section.getBoundingClientRect().top <= marker) activeId = id;
        });
        navLinks.forEach(link => {
                        const destination = new URL(link.href, location.href);
            const onHome = {{ request()->routeIs('home') ? 'true' : 'false' }};
            const current = destination.pathname === location.pathname && (!destination.hash || (onHome && destination.hash === '#' + activeId));
            if (current) link.setAttribute('aria-current', 'page');
            else link.removeAttribute('aria-current');
        });
        projectMenu?.classList.toggle('is-active', activeId === 'project');
        scrollPending = false;
    }
    window.addEventListener('scroll', () => {
        if (!scrollPending) { scrollPending = true; requestAnimationFrame(updateHeader); }
    }, { passive: true });
    window.addEventListener('resize', updateHeader);
    window.addEventListener('pageshow', updateHeader);
    updateHeader();
    document.querySelectorAll('.dropdown-menu a').forEach(link => link.addEventListener('click', () => link.closest('details').removeAttribute('open')));
    document.addEventListener('click', event => document.querySelectorAll('.dropdown[open]').forEach(menu => { if (!menu.contains(event.target)) menu.removeAttribute('open'); }));
    document.addEventListener('keydown', event => { if (event.key === 'Escape') document.querySelectorAll('.dropdown[open]').forEach(menu => { menu.removeAttribute('open'); menu.querySelector('summary').focus(); }); });
</script>
<script defer src="{{ asset('js/dashboard-mobile.js') }}?v={{ file_exists(public_path('js/dashboard-mobile.js')) ? filemtime(public_path('js/dashboard-mobile.js')) : '20261017' }}"></script>

<script defer src="{{ asset('js/public-ui.js') }}?v={{ filemtime(public_path('js/public-ui.js')) }}"></script>
