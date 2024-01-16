// Morris Donut
Morris.Donut({
	element: "donutColors",
	data: [
		{ value: 30, label: "foo" },
		{ value: 15, label: "bar" },
		{ value: 10, label: "baz" },
		{ value: 5, label: "A really really long label" },
	],
	backgroundColor: "#17181c",
	labelColor: "#17181c",
	colors: [
		"#e22132", "#e93443", "#f1505d", "#f3737e", "#f2a1a8", "#f6bcc1", "#fbd7da", "#fff2f3"
	],
	resize: true,
	hideHover: "auto",
	gridLineColor: "#dfd6ff",
	formatter: function (x) {
		return x + "%";
	},
});
