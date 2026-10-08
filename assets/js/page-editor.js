/* Page content panel: repeatable rows and image pickers. */
(function () {
	'use strict';
	var box = document.getElementById('rjy-page-content');
	if (!box) return;

	box.addEventListener('click', function (event) {
		var add = event.target.closest('.rjy-row-add');
		if (add) {
			var group = add.closest('.rjy-rows');
			var list = group.querySelector('.rjy-row-list');
			var index = Date.now();
			list.insertAdjacentHTML('beforeend', group.querySelector('template').innerHTML.replace(/__i__/g, index));
			var first = list.lastElementChild.querySelector('input, textarea');
			if (first) first.focus();
			return;
		}
		var remove = event.target.closest('.rjy-row-remove');
		if (remove) {
			remove.closest('.rjy-row').remove();
			return;
		}
		var field = event.target.closest('.rjy-image-field');
		if (!field) return;
		var input = field.querySelector('input[type=hidden]');
		var preview = field.querySelector('img');
		if (event.target.closest('.rjy-image-clear')) {
			input.value = '';
			preview.style.display = 'none';
			return;
		}
		if (event.target.closest('.rjy-image-choose') && window.wp && wp.media) {
			var frame = wp.media({ title: 'Choose image', library: { type: 'image' }, multiple: false });
			frame.on('select', function () {
				var image = frame.state().get('selection').first().toJSON();
				input.value = image.url;
				preview.src = image.url;
				preview.style.display = 'block';
			});
			frame.open();
		}
	});
})();
