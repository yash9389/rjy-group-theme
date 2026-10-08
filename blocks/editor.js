(function (wp) {
	const { registerBlockType } = wp.blocks;
	const { createElement: h, Fragment } = wp.element;
	const { InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
	const { Button, PanelBody, TextControl, TextareaControl, ToggleControl } = wp.components;
	const ServerSideRender = wp.serverSideRender;
	const shared = {
		eyebrow: { type: "string", default: "" },
		title: { type: "string", default: "" },
		copy: { type: "string", default: "" },
		image: { type: "string", default: "" },
		imageAlt: { type: "string", default: "" },
		buttonLabel: { type: "string", default: "" },
		buttonUrl: { type: "string", default: "" },
		secondaryButtonLabel: { type: "string", default: "" },
		secondaryButtonUrl: { type: "string", default: "" },
	};

	function mediaControl(attributes, setAttributes, label) {
		return h(MediaUploadCheck, {}, h(MediaUpload, {
			allowedTypes: ["image"],
			value: attributes.image,
			onSelect: (media) => setAttributes({ image: media.url, imageAlt: media.alt || "" }),
		render: ({ open }) => h(Button, { variant: "secondary", onClick: open }, attributes.image ? label.replace("Select", "Replace") : label),
		}));
	}

	function sharedControls(attributes, setAttributes, includeImage) {
		const fields = [
			h(TextControl, { label: "Eyebrow", value: attributes.eyebrow, onChange: (eyebrow) => setAttributes({ eyebrow }) }),
			h(TextControl, { label: "Heading", value: attributes.title, onChange: (title) => setAttributes({ title }) }),
			h(TextareaControl, { label: "Text", value: attributes.copy, onChange: (copy) => setAttributes({ copy }), rows: 5 }),
		];
		if (includeImage) {
			fields.push(mediaControl(attributes, setAttributes, "Select image"));
			fields.push(h(TextControl, { label: "Image description", value: attributes.imageAlt, onChange: (imageAlt) => setAttributes({ imageAlt }) }));
		}
		fields.push(h(TextControl, { label: "Button label", value: attributes.buttonLabel, onChange: (buttonLabel) => setAttributes({ buttonLabel }) }));
		fields.push(h(TextControl, { label: "Button link", value: attributes.buttonUrl, onChange: (buttonUrl) => setAttributes({ buttonUrl }) }));
		fields.push(h(TextControl, { label: "Second button label", value: attributes.secondaryButtonLabel, onChange: (secondaryButtonLabel) => setAttributes({ secondaryButtonLabel }) }));
		fields.push(h(TextControl, { label: "Second button link", value: attributes.secondaryButtonUrl, onChange: (secondaryButtonUrl) => setAttributes({ secondaryButtonUrl }) }));
		return fields;
	}

	function itemControls(items, setAttributes, label) {
		return items.map((item, index) => h(PanelBody, { title: label + " " + (index + 1), initialOpen: index === 0, key: index },
			h(TextControl, { label: "Value / title", value: item.value || item.title || "", onChange: (value) => updateItem(items, setAttributes, index, item.value !== undefined ? "value" : "title", value) }),
			h(TextControl, { label: "Label / description", value: item.label || item.copy || "", onChange: (value) => updateItem(items, setAttributes, index, item.label !== undefined ? "label" : "copy", value) }),
			label !== "Credential" ? h(MediaUploadCheck, {}, h(MediaUpload, { allowedTypes: ["image"], onSelect: (media) => updateItem(items, setAttributes, index, "image", media.url), render: ({ open }) => h(Button, { variant: "secondary", onClick: open }, item.image ? "Replace image" : "Select image") })) : null,
			label === "Card" ? h(Fragment, {},
				h(TextControl, { label: "Image description", value: item.imageAlt || "", onChange: (imageAlt) => updateItem(items, setAttributes, index, "imageAlt", imageAlt) }),
				h(TextControl, { label: "Card link label", value: item.buttonLabel || "", onChange: (buttonLabel) => updateItem(items, setAttributes, index, "buttonLabel", buttonLabel) }),
				h(TextControl, { label: "Card link URL", value: item.buttonUrl || "", onChange: (buttonUrl) => updateItem(items, setAttributes, index, "buttonUrl", buttonUrl) })
			) : null,
			h(Button, { variant: "tertiary", isDestructive: true, onClick: () => setAttributes({ items: items.filter((_, i) => i !== index) }) }, "Remove " + label.toLowerCase())
		));
	}

	function updateItem(items, setAttributes, index, key, value) {
		const next = items.map((item, i) => i === index ? Object.assign({}, item, { [key]: value }) : item);
		setAttributes({ items: next });
	}

	function createEdit(name, options) {
		return function Edit({ attributes, setAttributes }) {
			const controls = options.basic === false ? [] : sharedControls(attributes, setAttributes, options.image);
			if (options.items) {
				const label = options.items === "cards" ? "Card" : options.items === "credentials" ? "Credential" : "Stat";
				if (options.items === "credentials") controls.push(h(TextControl, { label: "Strip heading", value: attributes.heading, onChange: (heading) => setAttributes({ heading }) }));
				controls.push(...itemControls(attributes.items || [], setAttributes, label));
				const newItem = options.items === "cards" ? { title: "New card", copy: "Add a description", image: "", buttonLabel: "Learn more", buttonUrl: "#" } : options.items === "credentials" ? { value: "New credential" } : { value: "0", label: "New metric", image: "" };
				controls.push(h(Button, { variant: "primary", onClick: () => setAttributes({ items: [...(attributes.items || []), newItem] }) }, "Add " + label.toLowerCase()));
			}
			if (options.reverse) controls.push(h(ToggleControl, { label: "Place image before text", checked: !!attributes.reverse, onChange: (reverse) => setAttributes({ reverse }) }));
			if (options.wash) controls.push(h(ToggleControl, { label: "Use light background", checked: !!attributes.wash, onChange: (wash) => setAttributes({ wash }) }));
			return h(Fragment, {},
				h(InspectorControls, {}, h(PanelBody, { title: "Section content", initialOpen: true }, ...controls)),
				h("div", { className: "rjy-editor-preview" }, h(ServerSideRender, { block: name, attributes }))
			);
		};
	}

	const definitions = [
		{ name: "rjy/hero", title: "RJY Hero", description: "Image-backed page introduction with editable copy and buttons.", attributes: shared, options: { image: true }, icon: "cover-image" },
		{ name: "rjy/stats", title: "RJY Stats", description: "Image-backed facility metrics.", attributes: { items: { type: "array", default: [] } }, options: { items: "stats", basic: false }, icon: "chart-bar" },
		{ name: "rjy/content", title: "RJY Content", description: "Editorial split section with image and optional calls to action.", attributes: Object.assign({}, shared, { reverse: { type: "boolean", default: false } }), options: { image: true, reverse: true }, icon: "align-left" },
		{ name: "rjy/cards", title: "RJY Card Grid", description: "Reusable image cards with editable descriptions and links.", attributes: Object.assign({}, shared, { items: { type: "array", default: [] }, wash: { type: "boolean", default: false } }), options: { image: false, items: "cards", wash: true }, icon: "screenoptions" },
		{ name: "rjy/credentials", title: "RJY Credentials Strip", description: "Reusable certification and readiness labels.", attributes: { heading: { type: "string", default: "" }, items: { type: "array", default: [] } }, options: { items: "credentials", basic: false }, icon: "awards" },
		{ name: "rjy/cta", title: "RJY Call to Action", description: "Full-width call to action with editable text and buttons.", attributes: shared, options: { image: false }, icon: "megaphone" },
	];

	definitions.forEach((definition) => registerBlockType(definition.name, {
		apiVersion: 3,
		title: definition.title,
		description: definition.description,
		icon: definition.icon,
		category: "design",
		attributes: definition.attributes,
		supports: { align: ["wide", "full"], reusable: true, html: false },
		edit: createEdit(definition.name, definition.options),
		save: () => null,
	}));
})(window.wp);