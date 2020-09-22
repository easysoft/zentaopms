<style>
.block-waterfallreport .col-left {width: 25%; text-align: center}
.block-waterfallreport .col-right{width: 75%;}
.block-waterfallreport .panel-body{padding-top: 0px;}
.block-waterfallreport .table-row{margin-bottom: 20px;}
.block-waterfallreport .stage{position: absolute; top: 14px; left: 90px;}
.block-waterfallreport .col-right .tiles { padding: 10px 0 0 16px; }
.block-waterfallreport .col-right .tile { width: 20%;}
.block-waterfallreport .col-right .tile .tile-title{font-weight: 700;}
.block-waterfallreport .progress {position: absolute; left: 45px; top: 90px; right: 40px;}
.block-waterfallreport .progress-num{font-size: 20px; font-weight: 700}
.block-waterfallreport .col-right .tile-amount{font-size: 20px}
</style>

<div class="panel-move-handler"><span class='stage text-muted'><?php echo $current;?></span></div>
<div class="panel-body conatiner-fluid">
  <div class='table-row'>
    <div class="col col-left hide-in-sm">
      <h4><?php echo $lang->program->PGMProgress;?></h4>
      <span class='progress-num'><?php echo $progress . '%';?></span>
      <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress;?>%">
        </div>
      </div>
    </div>
    <div class='col-right'>
      <div class='row tiles'>
        <div class="col tile">
          <div class="tile-title">PV</div>
          <div class="tile-amount"><?php echo $pv == 0 ? 0 : $pv;?></div>
        </div>
        <div class="col tile">
          <div class="tile-title">EV</div>
          <div class="tile-amount"><?php echo $ev == 0 ? 0 : $ev;?></div>
        </div>
        <div class="col tile">
          <div class="tile-title">AC</div>
          <div class="tile-amount"><?php echo $ac == 0 ? 0 : $ac;?></div>
        </div>
        <div class="col tile">
          <div class="tile-title">SV%</div>
          <div class="tile-amount"><?php echo $sv . '%';?></div>
        </div>
        <div class="col tile">
          <div class="tile-title">CV%</div>
          <div class="tile-amount"><?php echo $cv . '%';?></div>
        </div>
      </div>
    </div>
  </div>
</div>
