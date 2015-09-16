<?php

function isSelected($type, $val){
  $data = array("district", "detailer", "stock");

  $fieldName = $data[$type];

  if (empty($_GET[$fieldName]) && $type == 2 && $val == "All") {
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
  //Define emty array to store series objects
  var mySeriesObject<?php echo $chartDiv ?> = [];
  $("#<?php echo $chartDiv ?>").dxChart({
    dataSource: <?=$chartDiv?>,
    commonSeriesSettings: {
        argumentField: "det",
        valueField: "visits",
        type: "line",
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
?>

<form action="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">
<div class="row">
  <div class="col-md-12">
      <div class="panel panel-default">
            <div class="panel-heading">
                Availability (%)
                <div class="pull-right">
                        <div class="btn-group">
                            <select name="district" onchange="updateOptions(event, 'dailyVisits')">
                              <option value="1" <?php echo isSelected(0, 1);?>>All</option>
                            </select>
                            <select name="detailer" id="dailyVisits">
                              <option value="All" <?php echo isSelected(1, "All");?>>All</option>
                              <?php foreach ($detailers as $detailer): ?>
                                <option value="<?=$detailer?>" <?php echo isSelected(1, $detailer);?>><?=$detailer?></option>
                              <?php endforeach ?>
                            </select>
                            <select name="stock" id="dailyVisits">
                              <option value="ors" <?php echo isSelected(2, "ors");?>>ORS</option>
                              <option value="zinc" <?php echo isSelected(2, "zinc");?>>Zinc</option>
                              <option value="rdt" <?php echo isSelected(2, "rdt");?>>RDT</option>
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
            <div style="text-align: right; padding-right: 10px; padding-top: 5px; padding-bottom: 5px">
              <a href="?<?php echo exportLink("nzinc_avail"); ?>"><button class="btn btn-primary" type="button">Excel</button></a>
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
  printChart($availabilityData, "detailer_visits");
  ?>
</script>