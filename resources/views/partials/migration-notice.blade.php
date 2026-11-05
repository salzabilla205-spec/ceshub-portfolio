<dialog id="ces-migration-notice" aria-labelledby="ces-migration-title" aria-describedby="ces-migration-description">
    <h2 id="ces-migration-title">Website CES pindah alamat.</h2>
    <p id="ces-migration-description">Informasi dan monitoring project sekarang tersedia di <span>ceshub.id</span>.</p>
    <div class="ces-migration-deadline-block">
        <span>Alihkan Kode Project ke Website ceshub.id sebelum</span>
        <time id="ces-migration-deadline" datetime="2026-10-24T11:22:00+07:00">24 Oktober 2026, 11.22 WIB</time>
    </div>
    <div class="ces-migration-countdown">
        <span id="ces-migration-label">Sisa waktu pengalihan</span>
        <div id="ces-migration-timer" role="timer" aria-label="Sisa waktu pengalihan kode project">
            <div><span id="ces-migration-days">02</span><small>hari</small></div>
            <div><span id="ces-migration-hours">00</span><small>jam</small></div>
            <div><span id="ces-migration-minutes">00</span><small>menit</small></div>
            <div><span id="ces-migration-seconds">00</span><small>detik</small></div>
        </div>
    </div>
    <a id="ces-migration-link" href="https://ceshub.id/" rel="noreferrer">Buka ceshub.id <span aria-hidden="true">→</span></a>
</dialog>
<style>
#ces-migration-notice{box-sizing:border-box;width:min(440px,calc(100% - 32px));max-height:calc(100dvh - 32px);overflow:auto;margin:auto;padding:28px;border:1px solid #e1e5e8;border-radius:6px;background:#fff;color:#17232e;font-family:Arial,Helvetica,sans-serif;text-align:left;box-shadow:0 4px 14px #17232e12}
#ces-migration-notice:not([open]){display:none!important}
#ces-migration-notice::backdrop{background:rgba(8,28,47,.56);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px)}
#ces-migration-notice h2{margin:0 0 13px;font-size:22px;line-height:1.25;font-weight:500;letter-spacing:-.02em;color:#17232e}
#ces-migration-description{margin:0;font-size:14px;line-height:1.6;font-weight:400;color:#65717c}
#ces-migration-description>span{color:#17232e}
#ces-migration-notice .ces-migration-deadline-block{border-top:1px solid #e1e5e8;margin-top:23px;padding-top:17px;display:flex;flex-direction:column;gap:5px;font-size:13px;line-height:1.5;color:#17232e}
#ces-migration-notice .ces-migration-deadline-block>span{color:#65717c}
#ces-migration-notice .ces-migration-countdown{margin-top:22px}
#ces-migration-label{font-size:12px;line-height:1.5;color:#65717c}
#ces-migration-timer{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;max-width:310px;margin-top:7px}
#ces-migration-timer>div{display:flex;flex-direction:column;align-items:flex-start;gap:3px;min-width:0}
#ces-migration-timer>div>span{font-size:42px;line-height:1.1;letter-spacing:-.04em;font-weight:500;color:#b33e25;font-variant-numeric:tabular-nums}
#ces-migration-timer small{font-size:12px;line-height:1.5;color:#65717c;font-weight:400}
#ces-migration-link{box-sizing:border-box;display:inline-flex;align-items:center;justify-content:center;gap:24px;margin-top:21px;padding:12px 16px;border-radius:4px;background:#164768;color:#fff;text-decoration:none;font-size:14px;line-height:1.35;font-weight:500}
#ces-migration-link:hover{background:#103951}
#ces-migration-link:focus-visible{outline:2px solid #164768;outline-offset:4px}
@media(max-width:480px){#ces-migration-notice{padding:23px 21px}#ces-migration-notice h2{font-size:21px}#ces-migration-timer{gap:10px}#ces-migration-timer>div>span{font-size:36px}}
@media(max-height:500px){#ces-migration-notice{padding:20px}#ces-migration-notice .ces-migration-deadline-block{margin-top:16px;padding-top:12px}#ces-migration-notice .ces-migration-countdown{margin-top:16px}#ces-migration-link{margin-top:16px}}
</style>
<script>
(() => {
    // ceshub.id temporarily reads monitoring HTML from the old server.
    // Browser-side isolation keeps the new site free of the old-site notice.
    if (!['cespv.com', 'www.cespv.com'].includes(location.hostname)) return;
    const dialog = document.getElementById('ces-migration-notice');
    if (!dialog || typeof dialog.showModal !== 'function') return;
    const deadline = Date.parse('2026-10-24T11:22:00+07:00');
    if (!Number.isFinite(deadline)) return;
    const timer = document.getElementById('ces-migration-timer');
    const label = document.getElementById('ces-migration-label');
    const digits = ['days', 'hours', 'minutes', 'seconds'].map(part => document.getElementById('ces-migration-' + part));
    const link = document.getElementById('ces-migration-link');
    link.href = new URL(location.pathname, 'https://ceshub.id').href;
    dialog.addEventListener('cancel', event => event.preventDefault());
    dialog.addEventListener('keydown', event => {
        if (event.key === 'Escape') { event.preventDefault(); event.stopPropagation(); }
    });
    dialog.addEventListener('close', () => { dialog.showModal(); link.focus(); });
    const update = () => {
        const seconds = Math.max(0, Math.ceil((deadline - Date.now()) / 1000));
        const values = [Math.floor(seconds / 86400), Math.floor(seconds / 3600) % 24, Math.floor(seconds / 60) % 60, seconds % 60];
        digits.forEach((digit, index) => { digit.textContent = String(values[index]).padStart(2, '0'); });
        timer.setAttribute('aria-label', values[0] + ' hari ' + values[1] + ' jam ' + values[2] + ' menit ' + values[3] + ' detik tersisa');
        if (seconds === 0) label.textContent = 'Batas waktu pengalihan telah selesai';
    };
    update();
    dialog.showModal();
    link.focus();
    setInterval(update, 1000);
})();
</script>
