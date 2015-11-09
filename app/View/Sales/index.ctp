<?php
function isSelected($type, $val, $chart){
  $data = array();
  $data[1] = array(1 => "sTimePeriod", 2 => "dailyVisitsPeriod");
  $data[2] = array(1 => "rTimePeriod", 2 => "zincPercent");

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

function exportLink($chart_type){
  $params = $_GET;
  unset($params["export"]);
  $params["export"] = $chart_type;
  $new_query_string = http_build_query($params);

  return $new_query_string;
}

function printChart($datasource, $chartDiv, $line = null, $type = "bar"){
  //$colors = array("#4E77BD", "#767A87", "#68C701");
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
  //Define emty array to store series objects
  var mySeriesObject<?php echo $chartDiv ?> = [];
  $("#<?php echo $chartDiv ?>").dxChart({
    dataSource: <?=$chartDiv?>,
    commonSeriesSettings: {
        argumentField: "det",
        valueField: "visits",
        type: "<?=$type?>",
        hoverMode: "allArgumentPoints",
        selectionMode: "allArgumentPoints",
        label: {
            visible: true,
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
    },
    seriesClick: function (clickedSeries) {
        clickedSeries.select();
    },
    seriesSelected: function (selectedSeries) {
        //define series labels objects
        var mySeriesLabels = $('#<?php echo $chartDiv ?> .dxc-series-labels');
        
        //define series labels group
        var mySeriesLabelsGroup = $('#<?php echo $chartDiv ?> .dxc-labels-group');
        
        //check if series labels objects are stored in mySeriesObject Array
        if (mySeriesObject<?php echo $chartDiv ?>.length == 0)   {
            for(i = 0; i < mySeriesLabels.length; i++ ){
                mySeriesObject<?php echo $chartDiv ?>[i] = mySeriesLabels[i];
            }
        }
        
        //clear all labels
        mySeriesLabels.remove();
        
        //append selected series label
        mySeriesLabelsGroup.append(mySeriesObject<?php echo $chartDiv ?>[selectedSeries.index]);
        
    },
    done: function() {
        //define series labels objects
        var mySeriesLabels = $('#<?php echo $chartDiv ?> .dxc-series-labels');
        
        //check if series labels objects are stored in mySeriesObject Array
        if (mySeriesObject<?php echo $chartDiv ?>.length == 0)   {
            for(i = 0; i < mySeriesLabels.length; i++ ){
                mySeriesObject<?php echo $chartDiv ?>[i] = mySeriesLabels[i];
            }
        }
        
        //clear all labels
        mySeriesLabels.remove();
    }
  });

<?php
}

function select_detname($detId){
  if ($detId == @$_GET['detId']) {
    return "selected=\"selected\"";
  }
}
?>

<form action="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">
<div class="row">
  <div class="col-md-6">
      <div class="panel panel-default">
            <div class="panel-heading">
                Sales
                <div class="pull-right">
                        <div class="btn-group">
                            <select name="sTimePeriod" id="timePeriod">
                                <option value="1" <?php echo isSelected(1, 1, 1);?>>Jan '15</option>
                                <option value="2" <?php echo isSelected(1, 2, 1);?>>Feb '15</option>
                                <option value="3" <?php echo isSelected(1, 3, 1);?>>Mar '15</option>
                                <option value="4" <?php echo isSelected(1, 4, 1);?>>Apr '15</option>
                                <option value="5" <?php echo isSelected(1, 5, 1);?>>May '15</option>
                                <option value="6" <?php echo isSelected(1, 6, 1);?>>Jun '15</option>
                                <option value="7" <?php echo isSelected(1, 7, 1);?>>Jul '15</option>
                                <option value="8" <?php echo isSelected(1, 8, 1);?>>Aug '15</option>
                                <option value="9" <?php echo isSelected(1, 9, 1);?>>Sep '15</option>
                                <option value="10" <?php echo isSelected(1, 10, 1);?>>Oct '15</option>
                                <option value="11" <?php echo isSelected(1, 11, 1);?>>Nov '15</option>
                                <option value="12" <?php echo isSelected(1, 12, 1);?>>Dec '15</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-sm btn-circle">GO</button>
                </div>
            </div>
            <div class="panel-body">
              <div id="sales" style="height:250px;">
                <span id="chartEmpty-sales" style="position:absolute;top:150px;left:100px;color: #5f8b95; font-size: 20px;">There is no data for the selected time period.</span>
              </div>
            </div>
            <div style="text-align: right; padding-right: 10px; padding-top: 5px; padding-bottom: 5px">
              <a href="?<?php echo exportLink("sales_export"); ?>"><button class="btn btn-primary" type="button">Excel</button></a>
            </div>
            
        </div>
    </div>
    
    <div class="col-md-6">
      <div class="panel panel-default">
            <div class="panel-heading">
                Revenue
                <div class="pull-right">
                        <div class="btn-group">
                            <select name="rTimePeriod" id="timePeriod">
                                <option value="1" <?php echo isSelected(1, 1, 2);?>>Jan '15</option>
                                <option value="2" <?php echo isSelected(1, 2, 2);?>>Feb '15</option>
                                <option value="3" <?php echo isSelected(1, 3, 2);?>>Mar '15</option>
                                <option value="4" <?php echo isSelected(1, 4, 2);?>>Apr '15</option>
                                <option value="5" <?php echo isSelected(1, 5, 2);?>>May '15</option>
                                <option value="6" <?php echo isSelected(1, 6, 2);?>>Jun '15</option>
                                <option value="7" <?php echo isSelected(1, 7, 2);?>>Jul '15</option>
                                <option value="8" <?php echo isSelected(1, 8, 2);?>>Aug '15</option>
                                <option value="9" <?php echo isSelected(1, 9, 2);?>>Sep '15</option>
                                <option value="10" <?php echo isSelected(1, 10, 2);?>>Oct '15</option>
                                <option value="11" <?php echo isSelected(1, 11, 2);?>>Nov '15</option>
                                <option value="12" <?php echo isSelected(1, 12, 2);?>>Dec '15</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-sm btn-circle">GO</button>
                </div>
            </div>
            <div class="panel-body">
              <div id="revenue" style="height:250px;">
                       <span id="chartEmpty-revenue" style="position:absolute;top:150px;left:100px;color: #5f8b95; font-size: 20px;">There is no data for the selected time period.</span>
              </div>
            </div>
            <div style="text-align: right; padding-right: 10px; padding-top: 5px; padding-bottom: 5px">
              <a href="?<?php echo exportLink("revenue_export"); ?>"><button class="btn btn-primary" type="button">Excel</button></a>
            </div>
        </div>
    </div>
</div>
</form>
<canvas id="canvas"></canvas>

<script type="text/javascript">
  <?php
  printChart($sales, "sales");
  printChart($revenue, "revenue");
  ?>
</script>