colabsShortcodeMeta={
	attributes:[
		{
			label:"Twitter Username",
			id:"username",
			help:"Place your twitter username here. This would be http://twitter.com/<strong>colorlabs</strong>."
		}, 
		{
			label:"Button Color",
			id:"button_color",
			help:"Change the button color between the default blue and a grey option.",
			controlType:"select-control", 
			selectValues:['', 'grey'],
			defaultValue: '', 
			defaultText: 'blue (Default)'
		},  
		{
			label:"Text Color",
			id:"text_color",
			controlType:"colourpicker-control",
			help:"Values: &lt;empty&gt; for default or a color (e.g. red or #000000)."
		},
		{
			label:"Link Color",
			id:"link_color",
			controlType:"colourpicker-control",
			help:"Values: &lt;empty&gt; for default or a color (e.g. red or #000000)."
		},
		{
			label:"Include Counter",
			id:"count",
			help:"Show your follower count.",
			controlType:"select-control", 
			selectValues:['false', ''],
			defaultValue: '', 
			defaultText: 'true (Default)'
		}, 
		{
			label:"Language",
			id:"language",
			help:"Select the language in which you want to display the button (English, French, German, Italian, Spanish, Korean, Japanese).",
			controlType:"select-control", 
			selectValues:['en', 'fr', 'de', 'it', 'es', 'ko', 'ja'],
			defaultValue: '', 
			defaultText: 'en (Default)'
		}, 
		{
			label:"Width",
			id:"width",
			help:"An optional width, in percentage (<strong>50%</strong) or pixel (<strong>50px</strong>) format."
		},
		{
			label:"Align",
			id:"align",
			help:"Used in conjunction with 'width' to align the button within the shortcode container DIV tag.",
			controlType:"select-control", 
			selectValues:['', 'left', 'right'],
			defaultValue: '', 
			defaultText: 'none (Default)'
		}, 
		{
			label:"Float",
			id:"float",
			help:"Float left, right, or none.",
			controlType:"select-control", 
			selectValues:['', 'left', 'right'],
			defaultValue: '', 
			defaultText: 'none (Default)'
		}
		],
		defaultContent:"",
		shortcode:"twitter_follow"
};