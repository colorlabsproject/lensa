colabsShortcodeMeta={
	attributes:[
		{
			label:"Style",
			id:"style",
			help:"Values: vertical, horizontal, none (default: vertical).", 
			controlType:"select-control", 
			selectValues:['', 'vertical', 'horizontal'],
			defaultValue: 'vertical', 
			defaultText: 'vertical (Default)'
		},
		{
			label:"Url",
			id:"url",
			help:"Optional. Specify URL directly."
		},
		{
			label:"Source",
			id:"source",
			help:"Optional. Username to mention in tweet."
		},
		{
			label:"Related",
			id:"related",
			help:"Optional. Related account."
		},
		{
			label:"Text",
			id:"text",
			help:"Optional tweet text (default: title of page)."
		},
		{
			label:"Float",
			id:"float",
			help:"Values: none, left, right (default: left).",
			controlType:"select-control", 
			selectValues:['', 'left', 'right'],
			defaultValue: 'left', 
			defaultText: 'left (Default)'
		},
		{
			label:"Lang",
			id:"lang",
			help:"Values: fr, de, es, js (default: english).", 
			controlType:"select-control", 
			selectValues:['', 'fr', 'de', 'es', 'js'],
			defaultValue: '', 
			defaultText: 'english (Default)'
		},
		{
			label:"Use Post URL",
			id:"use_post_url",
			help:"Values: true, false (default: false).",
			controlType:"select-control", 
			selectValues:['', 'true'],
			defaultValue: '', 
			defaultText: 'false (Default)'
		}
		],
		defaultContent:"",
		shortcode:"twitter"
};