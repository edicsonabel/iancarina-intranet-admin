// Morris Bar Chart
Morris.Bar({
	element: "morrisBar",
	data: [
		{ x: "2016 Q1", y: 2, z: 4, a: 2 },
		{ x: "2016 Q2", y: 5, z: 3, a: 1 },
		{ x: "2016 Q3", y: 2, z: 7, a: 4 },
		{ x: "2016 Q4", y: 5, z: 6, a: 3 },
	],
	xkey: "x",
	ykeys: ["y", "z", "a"],
	labels: ["Y", "Z", "A"],
	resize: true,
	hideHover: "auto",
	gridLineColor: "#dfd6ff",
	barColors: [
		"#e22132", "#e93443", "#f1505d", "#f3737e", "#f2a1a8", "#f6bcc1", "#fbd7da", "#fff2f3"
	],
});
