<footer class="footer {{ request()->routeIs('home') ? '' : 'pale' }}">
    <div class="wrap footer-inner">
        <span class="footer-copyright"><span class="footer-copy-full">© {{ date('Y') }} CES · Teknik Elektro, Universitas Sebelas Maret</span><span class="footer-copy-short">© {{ date('Y') }} CES · Teknik Elektro UNS</span></span>
        <div class="footer-actions"><small class="footer-version-label">CES v2.0 · </small><a href="{{ url('/admin/login') }}" class="footer-admin">Admin</a>
            @unless(request()->routeIs('home'))
                <a href="{{ route('home') }}"><span class="footer-link-full">Kembali ke Beranda</span><span class="footer-link-short">Beranda</span> ↗</a>
            @endunless
        </div>
    </div>
</footer>
