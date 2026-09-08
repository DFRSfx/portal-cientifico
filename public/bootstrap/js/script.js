const sidenav = document.getElementById("main-sidenav");
const sidenavInstance = mdb.Sidenav.getInstance(sidenav);

let innerWidth = null;

const setMode = (e) => {
  // Check necessary for Android devices
  if (window.innerWidth === innerWidth) {
    return;
  }

  innerWidth = window.innerWidth;

  if (window.innerWidth < 1400) {
    sidenavInstance.changeMode("over");
    sidenavInstance.hide();
  } else {
    sidenavInstance.changeMode("side");
    sidenavInstance.show();
  }
};

setMode();

// Event listeners
window.addEventListener("resize", setMode);

const searchFocus = document.getElementById('search-focus');
const keys = [
  { keyCode: 'AltLeft', isTriggered: false },
  { keyCode: 'ControlLeft', isTriggered: false },
];

window.addEventListener('keydown', (e) => {
  keys.forEach((obj) => {
    if (obj.keyCode === e.code) {
      obj.isTriggered = true;
    }
  });

  const shortcutTriggered = keys.filter((obj) => obj.isTriggered).length === keys.length;

  if (shortcutTriggered) {
    searchFocus.focus();
  }
});

window.addEventListener('keyup', (e) => {
  keys.forEach((obj) => {
    if (obj.keyCode === e.code) {
      obj.isTriggered = false;
    }
  });
});

const chartMarketingSourcesOption = {
  dataLabelsPlugin: true,
  options: {
    legend: {
      position: "right",
      labels: {
        boxWidth: 10,
      },
    },
    plugins: {
      tooltip: {
        callbacks: {
          label: function (context) {
            return " WATTS: " + context.formattedValue;
          },
        },
      },
      datalabels: {
        formatter: (value, ctx) => {
          let sum = 0;
          let dataArr = dataChartMarketingSources.data.datasets[0].data;
          dataArr.map((data) => {
            sum += data;
          });
          let percentage = ((value * 100) / sum).toFixed(2) + "%";
          return percentage;
        },
        color: "white",
        labels: {
          title: {
            font: {
              size: "13",
            },
          },
        },
      },
    },
  },
};

// Chart devices 1
const dataChartMarketingSources = {
  type: "pie",
  data: {
    labels: ["Kitchen", "Bedroom", "Living room"],
    datasets: [
      {
        label: "Energy consumption",
        data: [81, 53, 35],
        backgroundColor: [
          "rgba(63, 81, 181, 0.5)",
          "rgba(77, 182, 172, 0.5)",
          "rgba(66, 133, 244, 0.5)",
        ],
      },
    ],
  },
};

new mdb.Chart(
  document.getElementById("chart-consumption-by-room"),
  dataChartMarketingSources,
  chartMarketingSourcesOption
);