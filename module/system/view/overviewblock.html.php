<?php $this->app->loadLang('cne');?>
<div class="cell overview-container">
    <div class="table-row">
      <div class="col col-left text-center">
        <h3 class=""><?php echo $lang->system->cneStatus;?></h3>
        <div><?php echo zget($this->lang->CNE->statusIcons, $cneMetrics->status, $this->lang->CNE->statusIcons['unknown']);?></div>
        <div class='cne-status-text'><?php echo zget($lang->CNE->statusList, $cneMetrics->status, $lang->CNE->statusList['unknown']);?></div>
      </div>
      <div class="col col-right">
        <h3 class="text-center"><?php echo $lang->system->cneStatistic;?></h3>
        <div class="row tiles">
          <div class="col tile">
            <div class="tile-title"><?php echo $lang->system->nodeQuantity;?></div>
            <div class="tile-amount"><?php echo $cneMetrics->node_count;?></div>
          </div>
          <div class="col tile">
            <div class="tile-title"><?php echo $lang->system->serviceQuantity;?></div>
            <div class="tile-amount"><?php echo $this->instance->totalServices();?></div>
          </div>
          <div class="col tile">
            <div class="tile-title"><?php echo $lang->system->cpuUsage;?></div>
            <?php $this->cne->printCpuUsage($cneMetrics->metrics->cpu);?>
          </div>
          <div class="col tile">
            <div class="tile-title"><?php echo $lang->system->memUsage;?></div>
            <?php $this->cne->printMemUsage($cneMetrics->metrics->memory);?>
          </div>
        </div>
      </div>
    </div>
</div>

