<form action="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">
<div class="row">
  <div class="col-md-6">
      <div class="panel panel-default">
            <div class="panel-heading">
                Average Daily Visits by Detailers
                <div class="pull-right">
                        <div class="btn-group">
                            <select name="visitClassification" onchange="updateOptions(event, 'dailyVisits')">
                                <option value="1">Monthly</option>
                                <option value="2">Quarterly</option>
                            </select>
                            <select name="dailyVisits" id="dailyVisits">
                                <option value="jan">Jan '15</option><option value="feb">Feb '15</option><option value="mar">Mar '15</option><option value="apr">Apr '15</option><option value="may">May '15</option><option value="jun">Jun '15</option><option value="jul">Jul '15</option><option value="aug">Aug '15</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-sm btn-circle">GO</button>
                </div>
            </div>
            <div class="panel-body">
              <div id="detailer-visits" style="height:250px;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
      <div class="panel panel-default">
            <div class="panel-heading">
            Zinc/ORS Availability by Detailer (%)
                <div class="pull-right">
                        <div class="btn-group">
                            <select name="orsAvailClassification" onchange="updateOptions(event, 'zincPercent')">
                                <option value="1">Monthly</option>
                                <option value="2">Quarterly</option>
                            </select>
                            <select name="zincPercent" id="zincPercent">
                                <option value="jan">Jan '15</option><option value="feb">Feb '15</option><option value="mar">Mar '15</option><option value="apr">Apr '15</option><option value="may">May '15</option><option value="jun">Jun '15</option><option value="jul">Jul '15</option><option value="aug">Aug '15</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-sm btn-circle">GO</button>
                </div>
            </div>
            <div class="panel-body">
              <div id="zinc-availability" style="height:250px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-md-6">
      <div class="panel panel-default">
            <div class="panel-heading">
              Zinc Price by Detailer (UGX)
                <div class="pull-right">
                        <div class="btn-group">
                            <select name="zincClassification" onchange="updateOptions(event, 'zincPrice')">
                                <option value="1">Monthly</option>
                                <option value="2">Quarterly</option>
                            </select>
                            <select name="zincPrice" id="zincPrice">
                                <option value="jan">Jan '15</option><option value="feb">Feb '15</option><option value="mar">Mar '15</option><option value="apr">Apr '15</option><option value="may">May '15</option><option value="jun">Jun '15</option><option value="jul">Jul '15</option><option value="aug">Aug '15</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-sm btn-circle">GO</button>
                </div>
            </div>
            <div class="panel-body">
              <div id="zinc-price" style="height:250px;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
      <div class="panel panel-default">
            <div class="panel-heading">
                ORS Price by Detailer (UGX)
                <div class="pull-right">
                        <div class="btn-group">
                            <select name="orsClassification" onchange="updateOptions(event, 'ORSPrice')">
                                <option value="1">Monthly</option>
                                <option value="2">Quarterly</option>
                            </select>
                            <select name="ORSPrice" id="ORSPrice">
                                <option value="jan">Jan '15</option><option value="feb">Feb '15</option><option value="mar">Mar '15</option><option value="apr">Apr '15</option><option value="may">May '15</option><option value="jun">Jun '15</option><option value="jul">Jul '15</option><option value="aug">Aug '15</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-sm btn-circle">GO</button>
                    
                </div>
            </div>
            <div class="panel-body">
              <div id="ors-price" style="height:250px;"></div>
            </div>
        </div>
    </div>
</div>

</form>
<script type="text/javascript">
    /* Dropdown adjustment */
    function updateOptions(event, destSelect){
        var months = {
            "jan": "Jan \'15",
            "feb": "Feb \'15",
            "mar": "Mar \'15",
            "apr": "Apr \'15",
            "may": "May \'15",
            "jun": "Jun \'15",
            "jul": "Jul \'15",
            "aug": "Aug \'15",
        };

        var quarters = {
            "q1": "Q1 \'15",
            "q2": "Q2 \'15",
            "q3": "Q3 \'15",
            "q4": "Q3 \'15"
        };
        var mode = event.srcElement.value;
        var newOptions;
        if (mode === "1") {
            newOptions = months;
        } else {
            newOptions = quarters;
        }
        var $el = $("#" + destSelect);
        $el.empty(); // remove old options
        $.each(newOptions, function(value,key) {
          $el.append($("<option></option>")
             .attr("value", value).text(key));
        });

    }

  /*******************************************
  Simple Bar Chart
  *******************************************/

  $("#detailer-visits").dxChart({
    dataSource: [
          <?php
          foreach ($detailer_visits as $detName=>$monthData) {
            echo "{det: \"$detName\", visitsJul: " . @$monthData['July'] . ", visitsJun: ". @$monthData['June'] .", visitsMay: " . @$monthData['May'] . "},";
          }
          ?>
        ],
    commonSeriesSettings: {
        argumentField: "det",
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
        { valueField: "visitsMay", name: "May", color: '#485D81' },
        { valueField: "visitsJun", name: "June", color: '#243C63' },
        { valueField: "visitsJul", name: "July", color: '#4E77BD' },
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
    },
    legend: {
            verticalAlignment: 'top',
            horizontalAlignment: 'right'
        }
  });

  $("#zinc-availability").dxChart({
        dataSource: [
          <?php
          foreach ($zinc_stats as $detName => $monthData) {
            echo "{det: \"$detName\", availJul: " . @$monthData['July'] . ", availJun: ". @$monthData['June'] .", availMay: " . @$monthData['May'] . "},";
          }
          ?>
        ],
        commonSeriesSettings: {
            argumentField: "det",
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
            { valueField: "availMay", name: "May", color: '#485D81' },
            { valueField: "availJun", name: "June", color: '#243C63' },
            { valueField: "availJul", name: "July", color: '#4E77BD' },
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
        },
        legend: {
                verticalAlignment: 'top',
                horizontalAlignment: 'right'
            }
  });

$("#zinc-price").dxChart({
    dataSource: [
      <?php
      foreach ($zinc_price as $detName => $monthData) {
        echo "{det: \"$detName\", availJul: " . @$monthData['July'] . ", availJun: ". @$monthData['June'] .", availMay: " . @$monthData['May'] . "},";
      }
      ?>
    ],
    commonSeriesSettings: {
        argumentField: "det",
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
        { valueField: "availMay", name: "May", color: '#485D81' },
        { valueField: "availJun", name: "June", color: '#243C63' },
        { valueField: "availJul", name: "July", color: '#4E77BD' },
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
    },
    legend: {
            verticalAlignment: 'top',
            horizontalAlignment: 'right'
        }
  });

$("#ors-price").dxChart({
    dataSource: [
      <?php
      foreach ($ors_price as $detName => $monthData) {
        echo "{det: \"$detName\", availJul: " . @$monthData['July'] . ", availJun: ". @$monthData['June'] .", availMay: " . @$monthData['May'] . "},";
      }
      ?>
    ],
    commonSeriesSettings: {
        argumentField: "det",
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
        { valueField: "availMay", name: "May", color: '#485D81' },
        { valueField: "availJun", name: "June", color: '#243C63' },
        { valueField: "availJul", name: "July", color: '#4E77BD' },
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
    },
    legend: {
            verticalAlignment: 'top',
            horizontalAlignment: 'right'
        }
  });
</script>