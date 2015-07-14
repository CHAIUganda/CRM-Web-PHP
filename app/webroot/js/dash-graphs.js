/*******************************************
Simple Bar Chart
*******************************************/

$("#bar-chart").dxChart({
    dataSource: [
        {day: "Mon", oranges: 3},
        {day: "Tue", oranges: 2},
        {day: "Wed", oranges: 3},
        {day: "Thu", oranges: 4},
        {day: "Fri", oranges: 6},
        {day: "Sat", oranges: 11},
        {day: "Sun", oranges: 4} ],
  valueAxis:{
    grid:{
      color: '#9D9EA5',
      width: 0.1
      }
  },
  legend: {
    visible: false,
  },
    series: {
        argumentField: "day",
        valueField: "oranges",
        name: "My oranges",
    visible: false,
        type: "bar",
        color: '#a49bc4'
    }
});

/*******************************************
Side by Side Bar
*******************************************/

$("#side-by-side-bar").dxChart({
    dataSource: [
          { state: "Illinois", year1998: 423.721, year2001: 476.851, year2004: 528.904 },
          { state: "Indiana", year1998: 178.719, year2001: 195.769, year2004: 227.271 },
          { state: "Michigan", year1998: 308.845, year2001: 335.793, year2004: 372.576 },
          { state: "Ohio", year1998: 348.555, year2001: 374.771, year2004: 418.258 },
          { state: "Wisconsin", year1998: 160.274, year2001: 182.373, year2004: 211.727 }
        ],
    commonSeriesSettings: {
        argumentField: "state",
        type: "bar",
        hoverMode: "allArgumentPoints",
        selectionMode: "allArgumentPoints",
        label: {
            visible: true,
            format: "fixedPoint",
            precision: 0
        }
    },
    series: [
        { valueField: "year2004", name: "2004", color: '#ff6c60' },
        { valueField: "year2001", name: "2001", color: '#ff897f' },
        { valueField: "year1998", name: "1998", color: '#ffa69f' }
    ],
    legend: {
        visible: false
    },
  valueAxis:{
    grid:{
      color: '#9D9EA5',
      width: 0.1
      }
  },
    pointClick: function (point) {
        this.select();
    }
});

/*******************************************
Barwidth Bar
*******************************************/


$("#barwidth-bar").dxChart({
    dataSource: [
          { state: "Saudi Arabia", year1970: 192.2, year1980: 509.8, year1990: 342.6, year2000: 456.3, year2008: 515.3, year2009: 459.5 },
          { state: "USA", year1970: 533.5, year1980: 480.2, year1990: 416.6, year2000: 352.6, year2008: 304.9, year2009: 325.3 },
          { state: "China", year1970: 30.7, year1980: 106, year1990: 138.3, year2000: 162.6, year2008: 195.1, year2009: 189 },
          { state: "Canada", year1970: 70.1, year1980: 83.3, year1990: 92.6, year2000: 126.9, year2008: 157.7, year2009: 155.7 },
          { state: "Mexico", year1970: 24.2, year1980:  107.2, year1990: 146.3, year2000: 171.2, year2008: 157.7, year2009: 147.5}
        ],
    equalBarWidth: {
        width: 5
    },
    commonSeriesSettings: {
        argumentField: "state",
        type: "bar"
    },
    series: [
        { valueField: "year1970", name: "1970", color: '#4ecdc4' },
        { valueField: "year1980", name: "1980", color: '#ff6c60' },
        { valueField: "year1990", name: "1990", color: '#edd655' },
        { valueField: "year2000", name: "2000", color: '#ac92ec' },
        { valueField: "year2008", name: "2008", color: '#e1e1e3' },
        { valueField: "year2009", name: "2009" }
    ],
    legend: {
        visible: false
    },
  valueAxis:{
    grid:{
      color: '#9D9EA5',
      width: 0.1
      }
  },
});

/*******************************************
Stacked Bar
*******************************************/
$("#stacked-bar").dxChart({
    dataSource: [
          { state: "Germany", young: 6.7, middle: 28.6, older: 5.1 },
          { state: "Japan", young: 9.6, middle: 43.4, older: 9},
          { state: "Russia", young: 13.5, middle: 49, older: 5.8 },
          { state: "USA", young: 30, middle: 90.3, older: 14.5 }
        ],
    commonSeriesSettings: {
        argumentField: "state",
        type: "stackedBar"
    },
    series: [
        { valueField: "young", name: "0-14", color: '#4ecdc4' },
        { valueField: "middle", name: "15-64", color: '#ff6c60' },
        { valueField: "older", name: "65 and older", color: '#edd655' }
    ],
    legend: {
        visible: false
    },
  valueAxis:{
    grid:{
      color: '#9D9EA5',
      width: 0.1
      }
  },
    tooltip: {
        enabled: true,
        customizeText: function () {
            return this.seriesName + " years: " + this.valueText;
        },
    font: { size: 16 }
    }
});