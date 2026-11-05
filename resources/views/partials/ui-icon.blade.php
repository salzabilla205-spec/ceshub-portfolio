<svg class="ces-ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">@switch($icon)
@case('more')<circle cx="12" cy="5" r="1.5" fill="currentColor" stroke="none"/><circle cx="12" cy="12" r="1.5" fill="currentColor" stroke="none"/><circle cx="12" cy="19" r="1.5" fill="currentColor" stroke="none"/>@break
@case('menu')<path d="M5 6h14M5 12h14M5 18h14"/>@break
@case('play')<path d="m9 5 10 7-10 7z"/>@break
@case('pause')<path d="M8 5v14M16 5v14"/>@break
@case('left')<path d="m15 6-6 6 6 6"/>@break
@case('right')<path d="m9 6 6 6-6 6"/>@break
@case('external')<path d="M7 17 17 7M7 7h10v10"/>@break
@case('sun')<circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M2 12h2m16 0h2M5 5l1.4 1.4M17.6 17.6 19 19M5 19l1.4-1.4M17.6 6.4 19 5"/>@break
@case('share')<path d="M12 16V3m-4 4 4-4 4 4M5 12v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-8"/>@break
@case('reset')<path d="M3 10a9 9 0 1 1 2.7 8.5M3 4v6h6"/>@break
@default<path d="m6 9 6 6 6-6"/>
@endswitch</svg>
