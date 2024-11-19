

/* Transactions-chart */
var options = {
  series: [{
    name: "Total Orders",
    type: 'line',
    data: [0, 45, 30, 75, 15, 94, 40, 115, 30, 105, 65, 110]

  }, {
    name: "Total Sales",
    type: 'line',
    data: [0, 60, 20, 130, 75, 130, 75, 140, 64, 130, 85, 120]
  }, {
    name: "",
    type: 'area',
    data: [0, 105, 70, 175, 85, 154, 90, 185, 120, 145, 185, 130]
  }],
  chart: {
      toolbar: {
          show: false
      },
      type: 'line',
      height: 320,
      dropShadow: {
        enabled: true,
        opacity: 0.1,
      },
  },
  grid: {
      borderColor: '#f1f1f1',
      strokeDashArray: 3
  },
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
  dataLabels: {
      enabled: false
  },
  stroke: {
    curve: "smooth",
    width: [3, 3, 0],
    dashArray: [0, 4],
    lineCap: "round"
  },
  legend: {
      show: true,
      position: 'top',
  },
  xaxis: {
      axisBorder: {
          color: '#e9e9e9',
      },
  },
  plotOptions: {
      bar: {
          columnWidth: "20%",
          borderRadius: 2
      }
  },
  grid: {
    show: true,
    padding: {
        right: 0,
        left: 0
    },  
},

  colors: ["rgba(98, 89, 202, 1)", "rgba(249, 148, 51, 1)", 'rgba(119, 119, 142, 0.05)'],
};
document.querySelector("#totaltransactions").innerHTML = ""
var chart2 = new ApexCharts(document.querySelector("#totaltransactions"), options);
chart2.render();
function totaltransactions() {
  chart2.updateOptions({
      colors: ["rgba(" + myVarVal + ", 1)", '#23b7e5'],
  })
}


// Recent Orders
var options1 = {
  chart: {
    height: 305,
    type: 'radialBar',
    responsive: 'true',
    offsetX: 0,
    offsetY: 10,
  },
  plotOptions: {
    radialBar: {
      startAngle: -135,
      endAngle: 135,
      size: 120,
      imageWidth: 50,
      imageHeight: 50,
      track: {
        strokeWidth: "80%",
      },
      dropShadow: {
        enabled: false,
        top: 0,
        left: 0,
        bottom: 0,
        blur: 3,
        opacity: 0.5
      },
      dataLabels: {
        name: {
          fontSize: '16px',
          color: undefined,
          offsetY: 30,
        },
        hollow: {
          size: "60%"
        },
        value: {
          offsetY: -10,
          fontSize: '22px',
          color: undefined,
          formatter: function (val) {
            return val + "%";
          }
        }
      }
    }
  },
  colors: ["rgb(132, 90, 223)"],
  fill: {
    type: 'gradient',
    gradient: {
      shadeIntensity: 1,
      type: "horizontal",
      gradientToColors: ["rgb(255, 93, 158)"],
      opacityFrom: 1,
      opacityTo: 1,
      stops: [0, 100]
    }
  },

  stroke: {
    dashArray: 4
  },
  series: [83],
  labels: [""]
};
var options1 = new ApexCharts(document.querySelector("#recent-orders"), options1);
options1.render();

function recentorders() {
  options1.updateOptions({
    colors: ["rgba(" + myVarVal + ", 1)"],
  });
}
