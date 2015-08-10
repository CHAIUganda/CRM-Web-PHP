<?php
function isSelected($type, $val, $chart){
  $data = array();
  $data[1] = array(1 => "visitClassification", 2 => "dailyVisitsPeriod");
  $data[2] = array(1 => "orsAvailClassification", 2 => "zincPercent");
  $data[3] = array(1 => "zincClassification", 2 => "zincPrice");
  $data[4] = array(1 => "orsClassification", 2 => "ORSPrice");

  $fieldName = $data[$chart][$type];

  if (empty($_GET[$fieldName]) && $type == 2 && $val == ceil(date("n")/3)) {
    return "selected=\"selected\"";
  }
  if (@$_GET[$fieldName] == $val) {
    return "selected=\"selected\"";
  } else {
    return "";
  }
}

function printChart($datasource, $chartDiv, $line = null){
  $colors = array(" #243C63", "#4E77BD", "#788fb5", "#acb9ce");
  ?>
  var <?=$chartDiv?> = [
          <?php
          $months = array();
          foreach ($datasource as $detName => $monthData) {
            foreach($monthData as $monthName=>$average){
              $months[$monthName] = 0;
              echo "{det: \"$detName\", month:\"$monthName\", visits: $monthData[$monthName]}, ";
            }

            if($line != null){
              echo "{det: \"$detName\", month:\"Target RRP UGX: $line\", visits: $line}, ";
            }
          }
          ?>
        ];
  var colors = [<?php foreach ($colors as $color) {
    echo "\"$color\",";
  } ?>];
  var months = [<?php foreach (array_keys($months) as $month) {
    echo "\"$month\",";
  } ?>];
  if (<?=$chartDiv?>.length > 0) {
    $("#chartEmpty-<?=$chartDiv?>").css("display", "none");
  };
  $("#<?php echo $chartDiv ?>").dxChart({
    dataSource: <?=$chartDiv?>,
    commonSeriesSettings: {
        argumentField: "det",
        valueField: "visits",
        type: "bar",
        hoverMode: "allArgumentPoints",
        selectionMode: "allArgumentPoints",
        label: {
            visible: false,
            format: "fixedPoint",
            precision: 0
        }
    },
    
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
    },
    seriesTemplate: {
        nameField: "month",
        customizeSeries: function(valueFromNameField) {
            return valueFromNameField === "Target RRP UGX: <?=$line?>" ? { type: "line", label: { text:"stuff", visible: false }, color: "#68C701" } : {color: colors[months.indexOf(valueFromNameField) % 4]};
        }
    },
    onLegendClick: function(e) {
        var hiddenPoints, shownPoints;
        if (lastClickedSeriesName != e.target.name) {        
            hiddenPoints = lastClickedSeriesName ? e.component.getSeriesByName(lastClickedSeriesName).getAllPoints() : [];
            shownPoints = e.target.getAllPoints();
            lastClickedSeriesName = e.target.name;
        }
        else {
            hiddenPoints = e.component.getSeriesByName(lastClickedSeriesName).getAllPoints();
            shownPoints = [];
            lastClickedSeriesName = null;
        }
        $.each(hiddenPoints, function(index, point) {
           point.getLabel().hide();
        });
        $.each(shownPoints, function(index, point) {
           point.getLabel().show();
        });        
    }
  });

<?php
}
?>

<form action="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">
<div class="row">
  <div class="col-md-6">
      <div class="panel panel-default">
            <div class="panel-heading">
                Zinc Price by Region (UGX) 10 Tablets
                <div class="pull-right">
                        <div class="btn-group">
                            <select name="visitClassification" onchange="updateOptions(event, 'dailyVisits')">
                            <option value="2" <?php echo isSelected(1, 2, 1);?>>Quarter</option>
                                <option value="1" <?php echo isSelected(1, 1, 1);?>>Month</option>
                            </select>
                            <select name="dailyVisitsPeriod" id="dailyVisits">
                              <?php if(@$_GET['visitClassification'] == 1){ ?>
                                <option value="1" <?php echo isSelected(2, 1, 1);?>>Jan '15</option>
                                <option value="2" <?php echo isSelected(2, 2, 1);?>>Feb '15</option>
                                <option value="3" <?php echo isSelected(2, 3, 1);?>>Mar '15</option>
                                <option value="4" <?php echo isSelected(2, 4, 1);?>>Apr '15</option>
                                <option value="5" <?php echo isSelected(2, 5, 1);?>>May '15</option>
                                <option value="6" <?php echo isSelected(2, 6, 1);?>>Jun '15</option>
                                <option value="7" <?php echo isSelected(2, 7, 1);?>>Jul '15</option>
                                <option value="8" <?php echo isSelected(2, 8, 1);?>>Aug '15</option>
                                <option value="9" <?php echo isSelected(2, 9, 1);?>>Sep '15</option>
                                <option value="10" <?php echo isSelected(2, 10, 1);?>>Oct '15</option>
                                <option value="11" <?php echo isSelected(2, 11, 1);?>>Nov '15</option>
                                <option value="12" <?php echo isSelected(2, 12, 1);?>>Dec '15</option>
                              <?php } else { ?>
                                <option value="1" <?php echo isSelected(2, 1, 1);?>>Q1 '15</option>
                                <option value="2"<?php echo isSelected(2, 2, 1);?>>Q2 '15</option>
                                <option value="3"<?php echo isSelected(2, 3, 1);?>>Q3 '15</option>
                                <option value="4"<?php echo isSelected(2, 4, 1);?>>Q4 '15</option>
                              <?php } ?>
                                
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-sm btn-circle">GO</button>
                </div>
            </div>
            <div class="panel-body">
              <div id="detailer_visits" style="height:250px;">
                       <span id="chartEmpty-detailer_visits" style="position:absolute;top:150px;left:100px;color: #5f8b95; font-size: 20px;">There is no data for the selected time period.</span>
              </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
      <div class="panel panel-default">
            <div class="panel-heading">
            ORS Price by Region (UGX) 10 Tablets
                <div class="pull-right">
                        <div class="btn-group">
                            <select name="orsAvailClassification" onchange="updateOptions(event, 'zincPercent')">
                                <option value="2" <?php echo isSelected(1, 2, 2);?>>Quarter</option>
                                <option value="1" <?php echo isSelected(1, 1, 2);?>>Month</option>
                            </select>
                            <select name="zincPercent" id="zincPercent">
                              <?php if(@$_GET['orsAvailClassification'] == 1){ ?>
                                <option value="1" <?php echo isSelected(2, 1, 2);?>>Jan '15</option>
                                <option value="2" <?php echo isSelected(2, 2, 2);?>>Feb '15</option>
                                <option value="3" <?php echo isSelected(2, 3, 2);?>>Mar '15</option>
                                <option value="4" <?php echo isSelected(2, 4, 2);?>>Apr '15</option>
                                <option value="5" <?php echo isSelected(2, 5, 2);?>>May '15</option>
                                <option value="6" <?php echo isSelected(2, 6, 2);?>>Jun '15</option>
                                <option value="7" <?php echo isSelected(2, 7, 2);?>>Jul '15</option>
                                <option value="8" <?php echo isSelected(2, 8, 2);?>>Aug '15</option>
                                <option value="9" <?php echo isSelected(2, 9, 2);?>>Sep '15</option>
                                <option value="10" <?php echo isSelected(2, 10, 2);?>>Oct '15</option>
                                <option value="11" <?php echo isSelected(2, 11, 2);?>>Nov '15</option>
                                <option value="12" <?php echo isSelected(2, 12, 2);?>>Dec '15</option>
                              <?php } else { ?>
                                <option value="1" <?php echo isSelected(2, 1, 2);?>>Q1 '15</option>
                                <option value="2"<?php echo isSelected(2, 2, 2);?>>Q2 '15</option>
                                <option value="3"<?php echo isSelected(2, 3, 2);?>>Q3 '15</option>
                                <option value="4"<?php echo isSelected(2, 4, 2);?>>Q4 '15</option>
                              <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-sm btn-circle">GO</button>
                </div>
            </div>
            <div class="panel-body">
              <div id="zinc_availability" style="height:250px;">
                <span id="chartEmpty-zinc_availability" style="position:absolute;top:150px;left:100px;color: #5f8b95; font-size: 20px;">There is no data for the selected time period.</span>
              </div>
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
                                <option value="2" <?php echo isSelected(1, 2, 3);?>>Quarter</option>
                                <option value="1" <?php echo isSelected(1, 1, 3);?>>Month</option>
                            </select>
                            <select name="zincPrice" id="zincPrice">
                              <?php if(@$_GET['zincClassification'] == 1){ ?>
                                <option value="1" <?php echo isSelected(2, 1, 3);?>>Jan '15</option>
                                <option value="2" <?php echo isSelected(2, 2, 3);?>>Feb '15</option>
                                <option value="3" <?php echo isSelected(2, 3, 3);?>>Mar '15</option>
                                <option value="4" <?php echo isSelected(2, 4, 3);?>>Apr '15</option>
                                <option value="5" <?php echo isSelected(2, 5, 3);?>>May '15</option>
                                <option value="6" <?php echo isSelected(2, 6, 3);?>>Jun '15</option>
                                <option value="7" <?php echo isSelected(2, 7, 3);?>>Jul '15</option>
                                <option value="8" <?php echo isSelected(2, 8, 3);?>>Aug '15</option>
                                <option value="9" <?php echo isSelected(2, 9, 3);?>>Sep '15</option>
                                <option value="10" <?php echo isSelected(2, 10, 3);?>>Oct '15</option>
                                <option value="11" <?php echo isSelected(2, 11, 3);?>>Nov '15</option>
                                <option value="12" <?php echo isSelected(2, 12, 3);?>>Dec '15</option>
                              <?php } else { ?>
                                <option value="1" <?php echo isSelected(2, 1, 3);?>>Q1 '15</option>
                                <option value="2"<?php echo isSelected(2, 2, 3);?>>Q2 '15</option>
                                <option value="3"<?php echo isSelected(2, 3, 3);?>>Q3 '15</option>
                                <option value="4"<?php echo isSelected(2, 4, 3);?>>Q4 '15</option>
                              <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-sm btn-circle">GO</button>
                </div>
            </div>
            <div class="panel-body">
              <div id="zinc_price" style="height:250px;">
                <span id="chartEmpty-zinc_price" style="position:absolute;top:150px;left:100px;color: #5f8b95; font-size: 20px;">No There is no data for the selected time period.</span>
              </div>
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
                                <option value="2" <?php echo isSelected(1, 2, 4);?>>Quarter</option>
                                <option value="1" <?php echo isSelected(1, 1, 4);?>>Month</option>
                            </select>
                            <select name="ORSPrice" id="ORSPrice">
                              <?php if(@$_GET['orsClassification'] == 1){ ?>
                                <option value="1" <?php echo isSelected(2, 1, 4);?>>Jan '15</option>
                                <option value="2" <?php echo isSelected(2, 2, 4);?>>Feb '15</option>
                                <option value="3" <?php echo isSelected(2, 3, 4);?>>Mar '15</option>
                                <option value="4" <?php echo isSelected(2, 4, 4);?>>Apr '15</option>
                                <option value="5" <?php echo isSelected(2, 5, 4);?>>May '15</option>
                                <option value="6" <?php echo isSelected(2, 6, 4);?>>Jun '15</option>
                                <option value="7" <?php echo isSelected(2, 7, 4);?>>Jul '15</option>
                                <option value="8" <?php echo isSelected(2, 8, 4);?>>Aug '15</option>
                                <option value="9" <?php echo isSelected(2, 9, 4);?>>Sep '15</option>
                                <option value="10" <?php echo isSelected(2, 10, 4);?>>Oct '15</option>
                                <option value="11" <?php echo isSelected(2, 11, 4);?>>Nov '15</option>
                                <option value="12" <?php echo isSelected(2, 12, 4);?>>Dec '15</option>
                              <?php } else { ?>
                                <option value="1" <?php echo isSelected(2, 1, 4);?>>Q1 '15</option>
                                <option value="2"<?php echo isSelected(2, 2, 4);?>>Q2 '15</option>
                                <option value="3"<?php echo isSelected(2, 3, 4);?>>Q3 '15</option>
                                <option value="4"<?php echo isSelected(2, 4, 4);?>>Q4 '15</option>
                              <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-sm btn-circle">GO</button>
                    
                </div>
            </div>
            <div class="panel-body">
              <div id="ors_price" style="height:250px;">
                <span id="chartEmpty-ors_price" style="position:absolute;top:150px;left:100px;color: #5f8b95; font-size: 20px;">No There is no data for the selected time period.</span>
              </div>
            </div>
        </div>
    </div>
</div>

</form>
<script type="text/javascript">
    /* Dropdown adjustment */
    function updateOptions(event, destSelect){
        var months = {
            "1": "Jan \'15",
            "2": "Feb \'15",
            "3": "Mar \'15",
            "4": "Apr \'15",
            "5": "May \'15",
            "6": "Jun \'15",
            "7": "Jul \'15",
            "8": "Aug \'15",
            "9": "Sep \'15",
            "10": "Oct \'15",
            "11": "Nov \'15",
            "12": "Dec \'15",
        };

        var quarters = {
            "1": "Q1 \'15",
            "2": "Q2 \'15",
            "3": "Q3 \'15",
            "4": "Q4 \'15"
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
        var d = new Date();

        $.each(newOptions, function(value,key) {
          if ((d.getMonth() + 1) == value) {
            $el.append($("<option selected=\"selected\"></option>")
             .attr("value", value).text(key));
          } else {
            $el.append($("<option></option>")
             .attr("value", value).text(key));
          }
        });
    }

  <?php
  printChart($regional_zinc_price, "detailer_visits", 900);
  printChart($regional_ors_price, "zinc_availability", 300);
  printChart($zinc_price, "zinc_price", 900);
  printChart($ors_price, "ors_price", 300);
  ?>
</script>