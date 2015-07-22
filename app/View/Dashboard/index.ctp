<div class="row">
  <div class="col-md-6">
      <div class="panel panel-default">
            <div class="panel-heading">Median Daily Visits by Detailers</div>
            <div class="panel-body">
              <div id="detailer-visits" style="height:250px;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
      <div class="panel panel-default">
            <div class="panel-heading">Zinc/ORS Availability by Detailer (%)</div>
            <div class="panel-body">
              <div id="zinc-availability" style="height:250px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-md-6">
      <div class="panel panel-default">
            <div class="panel-heading">Zinc Price by Detailer (UGX)</div>
            <div class="panel-body">
              <div id="zinc-price" style="height:250px;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
      <div class="panel panel-default">
            <div class="panel-heading">ORS Price by Detailer (UGX)</div>
            <div class="panel-body">
              <div id="ors-price" style="height:250px;"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  /*******************************************
  Simple Bar Chart
  *******************************************/

  $("#detailer-visits").dxChart({
  dataSource: [
      <?php
      foreach ($detailer_visits as $key => $value) {
        echo "{det: \"$key\", visits: $value},";
      }
      ?>
      ],
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
      argumentField: "det",
      valueField: "visits",
      name: "Daily Visits",
  visible: false,
      type: "bar",
      color: '#a49bc4'
  }
  });

  $("#zinc-availability").dxChart({
    dataSource: [
        <?php
        foreach ($zinc_stats as $key => $value) {
          echo "{det: \"$key\", zinc_percent: $value},";
        }
        ?>
        ],
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
        argumentField: "det",
        valueField: "zinc_percent",
        name: "Zinc Availability",
    visible: false,
        type: "bar",
        color: '#a49bc4'
    }
  });

$("#zinc-price").dxChart({
    dataSource: [
        <?php
        foreach ($zinc_price as $key => $value) {
          echo "{det: \"$key\", zinc_price: $value},";
        }
        ?>
        ],
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
        argumentField: "det",
        valueField: "zinc_price",
        name: "Zinc Price",
    visible: false,
        type: "bar",
        color: '#a49bc4'
    }
  });

$("#ors-price").dxChart({
    dataSource: [
        <?php
        foreach ($ors_price as $key => $value) {
          echo "{det: \"$key\", ors_price: $value},";
        }
        ?>
        ],
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
        argumentField: "det",
        valueField: "ors_price",
        name: "ORS Price",
    visible: false,
        type: "bar",
        color: '#a49bc4'
    }
  });
</script>