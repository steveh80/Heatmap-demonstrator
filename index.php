<?php 

  $numRows = $_REQUEST['numRows'] ? $_REQUEST['numRows'] : 14;
  $numCols = $_REQUEST['numCols'] ? $_REQUEST['numCols'] : 55;
  $maxTimePastDays = $_REQUEST['maxTimePastDays'] ? $_REQUEST['maxTimePastDays'] : 365;
  $defaultDaysValue = round($maxTimePastDays/3*2);

?>
<!DOCTYPE html>
<meta charset="UTF-8">
<html>
  
<head>    
  <link href="css/bootstrap/bootstrap.css" rel="stylesheet">
  <link href="css/bootstrap/font-awesome.css" rel="stylesheet">
  <link href="css/jquery-ui.css" rel="stylesheet">

  <title>Heatmap Demonstrator</title>

  <style type="text/css">
    body {
      padding:10px;
      background-color: #EEE !important;
    }
    td {
      text-align: center !important;
      background-color: white;
    }
    .thumbnail {
      background-color: white;
    }

    #slider {
      margin: 10px;

    }
  </style>
</head>  
  
<body>

<div class="container">
<div class="row">
  <div class="span6">
    <div class="thumbnail">
      <form class="form-horizontal">
        <legend>Heatmap demonstrator</legend>
        <p>Moving the slider will change the number of days to highlight:</p>
        <div id="slider"></div>
        <p id="jqueryTime"></p>
      </form>
    </div>
  </div>
  <div class="span6">
    <div class="thumbnail">
      <form class="form-horizontal" method="post">
        <legend>Table grid size</legend>
        <div class="control-group">
          <label class="control-label" for="numRows">Rows</label>
          <div class="controls">
            <input type="text" id="numRows" placeholder="<?= $numRows ?>" name="numRows" value="<?= $_REQUEST['numRows'] ?>">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="numCols">Columns</label>
          <div class="controls">
            <input type="text" id="numCols" placeholder="<?= $numCols ?>" name="numCols" value="<?= $_REQUEST['numCols'] ?>">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="numCols">Max age of random date</label>
          <div class="controls">
            <input type="text" id="maxTimePastDays" placeholder="<?= $maxTimePastDays ?>" name="maxTimePastDays" value="<?= $_REQUEST['maxTimePastDays'] ?>"> days
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
            <button type="submit" class="btn btn-warning">Update table</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="row">
  <div class="span12">

  <p>
    <?= ($numCols*$numRows) ?> table cells are displayed. 
    Oldest date in table is <?= date('d.m.Y', time() - $maxTimePastDays * 60 * 60 * 24) ?>
    which is <?= $maxTimePastDays ?> days ago.
  </p>
  
<table class="table table-hover table-bordered table-condensed">
<?php

function randomTime() {
  global $maxTimePastDays;
  return time() - rand(0, $maxTimePastDays * 60 * 60 * 24);
}

for ( $i=0; $i<$numRows; $i++) {
  print '<tr>';
  for ( $j=0; $j<$numCols; $j++) {
    $time = randomTime();
    print '<td lastupdate="' . $time  . '" title="' . date('d.m.y H:i', $time) . ' Uhr">' . rand(1, 6) . '</td>';
  }
  print '</tr>';
}
?>

</table>
</div>
</div>
    
</div>

<script src="js/jquery/jquery-1.8.0.min.js"></script>
<script src="js/jquery/jquery-ui.min.js"></script>
<script src="js/bootstrap/bootstrap.min.js"></script>

<script>

function jqueryCss() {
  $('#jqueryTime').html('loading...');
  var float_ts = new Date().getTime();    

  var daysToHighlight = $('#slider').slider('option', 'value');
  var minTimeToHighlight = Math.round(float_ts / 1000) - daysToHighlight * 60 * 60 * 24;
  var maxTimeToHighlight = Math.round(float_ts / 1000);
  var diff = maxTimeToHighlight - minTimeToHighlight;
  
  //reset colors
  $('td').css('background-color', 'rgba(255,255,0,0.5)');
  
  $('td[lastupdate]').filter(function() {
    return $(this).attr("lastupdate") > minTimeToHighlight;
  }).each(function(){
    var multiplier = (($(this).attr('lastupdate')-minTimeToHighlight)/diff).toFixed(2);
    $(this).css('background-color', 'rgba(255, ' + (255 * (1-multiplier)).toFixed(0) + ', 0, ' + (multiplier/5+0.5) + ')');
  });

  $('#jqueryTime').html('Highlighting the past <b>' + daysToHighlight + ' days</b>. jQuery Update took '+ ((new Date().getTime() - float_ts)/1000) + ' sec.');

  //return false;
}

function clearTable() {
  $('td').css('background-color', 'white');
}

function test() {
  alert($('#slider').slider('option', 'value'));
}

$(document).ready(function() {
  $("#slider").slider({ max: <?= $maxTimePastDays ?>, value : <?= $defaultDaysValue ?>, slide:jqueryCss });
  jqueryCss();
});

</script>
</body>
</html>
