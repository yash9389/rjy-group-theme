(function () {
	'use strict';
	var toggle = document.querySelector('.menu-toggle');
	var nav = document.getElementById('mobile-nav');
	if (!toggle || !nav) return;
	var bars = toggle.querySelector('.hamburger');
	var close = toggle.querySelector('svg');
	function setOpen(open) {
		nav.hidden = !open;
		toggle.setAttribute('aria-expanded', String(open));
		toggle.setAttribute('aria-label', open ? toggle.dataset.closeLabel : toggle.dataset.openLabel);
		bars.hidden = open;
		if (open) { close.removeAttribute('hidden'); } else { close.setAttribute('hidden', ''); }
	}
	toggle.addEventListener('click', function () { setOpen(nav.hidden); });
	nav.addEventListener('click', function (event) { if (event.target.closest('a')) setOpen(false); });
	document.addEventListener('keydown', function (event) { if (event.key === 'Escape') setOpen(false); });
})();
