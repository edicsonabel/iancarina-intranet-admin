Morris.Donut({
	element: "donutFormatter",
	data: [
		{ value: 155, label: "voo", formatted: "at least 70%" },
		{ value: 12, label: "bar", formatted: "approx. 15%" },
		{ value: 10, label: "baz", formatted: "approx. 10%" },
		{ value: 5, label: "A really really long label", formatted: "at most 5%" },
	],
	resize: true,
	hideHover: "auto",
	formatter: function (x, data) {
		return data.formatted;
	},
	labelColor: "#17181c",
	colors: ["#e22132", "#e93443", "#f1505d", "#f3737e", "#f2a1a8", "#f6bcc1", "#fbd7da", "#fff2f3"],
});
