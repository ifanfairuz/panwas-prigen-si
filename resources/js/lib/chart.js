import {
    Chart,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    LineController,
    plugins,
    BarElement,
} from "chart.js";

Chart.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    LineController,
    plugins.Filler,
    plugins.Legend
);
Chart.defaults.datasets.line.borderColor = "transparent";
Chart.defaults.datasets.line.pointBackgroundColor = "#FFFFFF";
Chart.defaults.datasets.line.pointBorderColor = "#FFFFFF";
Chart.defaults.datasets.line.tension = 0.4;
Chart.defaults.datasets.line.pointRadius = 0;
Chart.defaults.datasets.line.pointHitRadius = 5;
Chart.defaults.datasets.line.pointHoverRadius = 5;
Chart.defaults.datasets.line.fill = true;
Chart.defaults.scales.linear.beginAtZero = true;
Chart.defaults.responsive = true;
Chart.defaults.plugins.legend.align = "end";
Chart.defaults.plugins.legend.labels.boxWidth = 10;
Chart.defaults.plugins.legend.labels.boxHeight = 10;
Chart.defaults.plugins.legend.labels.useBorderRadius = true;
Chart.defaults.plugins.legend.labels.borderRadius = 10 / 2;
