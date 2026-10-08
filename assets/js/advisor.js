/* Service advisor: matches the described building and needs to RJY services, using the result layout of the React advisor. */
(function () {
	'use strict';
	var data = window.rjyAdvisor;
	var form = document.getElementById('rjy-advisor');
	var output = document.getElementById('rjy-advisor-result');
	if (!data || !form || !output) return;

	var serviceKeywords = [
		['bas', 'automation', 'controls', 'control', 'alarm', 'alarms', 'setpoint', 'schedule', 'energy', 'bms', 'trend', 'sequence'],
		['cyber', 'security', 'ot', 'ics', 'remote access', 'vendor', 'vendors', 'network', 'firewall', 'scada', 'compliance', 'segmentation'],
		['hvac', 'chiller', 'chillers', 'cooling', 'heating', 'boiler', 'air handler', 'ahu', 'comfort', 'temperature', 'humidity'],
		['generator', 'generators', 'standby', 'backup power', 'emergency power', 'power', 'transfer switch', 'ats', 'load bank', 'outage'],
		['staff', 'staffing', 'technician', 'technicians', 'coverage', 'shortage', 'turnover', 'team', 'operator', 'operators'],
		['water', 'treatment', 'corrosion', 'scale', 'legionella', 'cooling tower', 'closed loop', 'chemical', 'sampling']
	];
	var industryKeywords = [
		['hospital', 'medical', 'clinic', 'healthcare', 'patient', 'bed'],
		['government', 'public safety', 'police', 'fire', 'municipal', 'federal', 'county', 'city', 'courthouse', 'emergency operations'],
		['industrial', 'plant', 'manufacturing', 'warehouse', 'commercial', 'office', 'campus', 'data center'],
		['university', 'college', 'school', 'education', 'academic', 'dorm', 'residence hall', 'lab'],
		['transit', 'transportation', 'utility', 'utilities', 'airport', 'rail', 'port', 'substation', 'water plant']
	];

	function score(text, words) {
		return words.reduce(function (total, word) { return total + (text.indexOf(word) !== -1 ? (word.indexOf(' ') !== -1 ? 2 : 1) : 0); }, 0);
	}
	function escapeHtml(value) {
		var div = document.createElement('div');
		div.textContent = value;
		return div.innerHTML;
	}

	form.addEventListener('submit', function (event) {
		event.preventDefault();
		var building = form.querySelector('#bt').value.trim();
		var needs = form.querySelector('#needs').value.trim();
		var text = (' ' + building + ' ' + needs + ' ').toLowerCase();

		var ranked = data.services.map(function (service, index) { return { service: service, score: score(text, serviceKeywords[index] || []) }; })
			.filter(function (item) { return item.score > 0; })
			.sort(function (a, b) { return b.score - a.score; });
		if (!ranked.length) ranked = [{ service: data.services[0], score: 1 }, { service: data.services[2], score: 1 }];
		ranked = ranked.slice(0, 4);

		var industry = data.industries.map(function (item, index) { return { item: item, score: score(text, industryKeywords[index] || []) }; })
			.sort(function (a, b) { return b.score - a.score; })[0];
		industry = industry && industry.score > 0 ? industry.item : null;

		var top = ranked[0].score;
		var services = ranked.map(function (entry) {
			var priority = entry.score >= top ? 'High' : (entry.score >= top / 2 ? 'Medium' : 'Low');
			return '<a href="' + entry.service.url + '" class="group flex flex-col rounded-2xl border bg-background p-5 transition hover:-translate-y-0.5 hover:shadow-md"><span class="mb-2 w-fit rounded-full bg-primary/10 px-3 py-1 text-xs font-extrabold text-primary">' + priority + ' priority</span><span class="text-lg font-black">' + escapeHtml(entry.service.title) + '</span><span class="mt-1 flex-1 text-sm text-muted-foreground">' + escapeHtml(entry.service.description) + '</span><span class="mt-3 flex items-center gap-1 text-sm font-extrabold text-primary">Learn more ' + data.icons.arrow + '</span></a>';
		}).join('');

		var names = ranked.map(function (entry) { return entry.service.title; });
		var summary = 'Based on what you shared about your ' + (building || 'facility').toLowerCase() + ', the strongest fit is ' + names[0] + (names.length > 1 ? ', supported by ' + names.slice(1).join(', ') : '') + '.';

		output.innerHTML = '<div class="space-y-6">' +
			'<div class="rounded-2xl border bg-background p-6"><h2 class="text-2xl font-black">Your recommendation</h2><p class="mt-2 text-muted-foreground">' + escapeHtml(summary) + '</p>' +
			(industry ? '<a href="' + industry.url + '" class="mt-4 block rounded-xl bg-muted p-4 hover:bg-accent/10"><span class="text-xs font-extrabold uppercase tracking-widest text-primary">Your industry</span><span class="mt-1 block font-extrabold">' + escapeHtml(industry.title) + '</span><span class="text-sm text-muted-foreground">' + escapeHtml(industry.description) + '</span></a>' : '') +
			'</div>' +
			'<div><h3 class="mb-3 text-xl font-black">Recommended services</h3><div class="grid gap-4 md:grid-cols-2">' + services + '</div></div>' +
			'<div><h3 class="mb-3 text-xl font-black">Helpful resources</h3><ul class="space-y-3">' +
			'<li><a href="' + data.resourcesUrl + '" class="block rounded-xl border bg-background p-4 hover:bg-muted"><span class="font-extrabold">Resources &amp; Downloads</span><span class="block text-sm text-muted-foreground">Checklists and guides to prepare your team before an inspection.</span></a></li>' +
			'<li><a href="' + data.knowledgeUrl + '" class="block rounded-xl border bg-background p-4 hover:bg-muted"><span class="font-extrabold">Knowledge Center</span><span class="block text-sm text-muted-foreground">Practical guidance for controls, maintenance, resilience, and OT security.</span></a></li>' +
			'</ul></div>' +
			'<div class="flex flex-col gap-4 rounded-2xl bg-primary p-6 text-primary-foreground md:flex-row md:items-center md:justify-between"><p class="font-bold">Schedule an inspection so an RJY specialist can confirm scope and priorities on site.</p><div class="flex flex-wrap gap-3"><a href="' + data.quoteUrl + '" class="rounded-lg bg-background px-5 py-3 font-extrabold text-primary">Request an Inspection</a><a href="' + data.tollFreeHref + '" class="flex items-center gap-2 rounded-lg border border-primary-foreground/40 px-5 py-3 font-extrabold">' + data.icons.phone + ' ' + escapeHtml(data.tollFree) + '</a></div></div>' +
			'</div>';
	});
})();
