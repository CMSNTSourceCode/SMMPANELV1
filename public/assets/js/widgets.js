(function () {
    "use strict";


     /* this-week-orders */
     var options = {
        series: [70],
        chart: {
            height: 160,
            type: 'radialBar',
        },
         colors: ["rgba(0,0,0, 0.2)"],
        plotOptions: {
            radialBar: {
                hollow: {
                    size: '63%',
                }
            },
        },
        stroke: {
            lineCap: "round",
          },
        
        labels: ['orders'],
    };
    var chart = new ApexCharts(document.querySelector("#this-week-orders1"), options);
    chart.render();

     /* this-week-orders */
     var options = {
        series: [17],
        chart: {
            height: 160,
            type: 'radialBar',
        },
         colors: ["rgba(0,0,0, 0.2)"],
        plotOptions: {
            radialBar: {
                hollow: {
                    size: '63%',
                }
            },
        },
        stroke: {
            lineCap: "round",
          },
        
        labels: ['Views'],
    };
    var chart = new ApexCharts(document.querySelector("#this-week-orders2"), options);
    chart.render();

     /* this-week-orders */
     var options = {
        series: [42],
        chart: {
            height: 160,
            type: 'radialBar',
        },
        colors: ["rgba(0,0,0, 0.2)"],
        plotOptions: {
            radialBar: {
                hollow: {
                    size: '63%',
                }
            },
        },
        stroke: {
            lineCap: "round",
          },
        
        labels: ['Earnings'],
    };
    var chart = new ApexCharts(document.querySelector("#this-week-orders3"), options);
    chart.render();

     /* this-week-orders */
     var options = {
        series: [37],
        chart: {
            height: 160,
            type: 'radialBar',
        },
        colors: ["rgba(0,0,0, 0.2)"],
        plotOptions: {
            radialBar: {
                hollow: {
                    size: '63%',
                }
            },
        },
        stroke: {
            lineCap: "round",
          },
        
        labels: ['Comments'],
    };
    var chart = new ApexCharts(document.querySelector("#this-week-orders4"), options);
    chart.render();

})();