var options = {
	chart: {
		height: 242,
		type: "line",
		stacked: false,
		toolbar: {
			show: false,
		},
	},
	dataLabels: {
		enabled: false,
	},
	plotOptions: {
		bar: {
			horizontal: false,
			borderRadius: 3,
			columnWidth: "60%",
		},
	},
	series: [
		{
			name: "Visitors",
			type: "column",
			data: [50, 60, 70, 80, 90, 100, 100, 90, 80, 70, 60, 50],
		},
		{
			name: "Clicks",
			type: "column",
			data: [40, 50, 60, 70, 80, 90, 90, 80, 70, 60, 50, 40],
		},
	],
	stroke: {
		width: [0, 0],
	},
	grid: {
		xaxis: {
			lines: {
				show: false,
			},
		},
		yaxis: {
			lines: {
				show: false,
			},
		},
		padding: {
			top: -50,
			right: 0,
			bottom: 0,
			left: 10,
		},
	},
	colors: ["#e22132", "#e93443", "#f1505d", "#f3737e", "#f2a1a8", "#f6bcc1", "#fbd7da", "#fff2f3"],
	xaxis: {
		categories: [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"May",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Oct",
			"Nov",
			"Dec",
		],
	},
	legend: {
		show: false,
	},
	yaxis: {
		show: false,
	},
};

var chart = new ApexCharts(document.querySelector("#analytics"), options);
chart.render();
