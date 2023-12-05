<?php
/**
 * The burn view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: cfd.html.php 4164 2013-01-20 08:27:55Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::import($jsRoot . 'echarts/echarts.common.min.js'); ?>
<?php js::import($jsRoot . 'html2canvas/min.js'); ?>
<?php js::import($jsRoot . 'misc/base64.js');?>
<?php js::set('executionID', $executionID); ?>
<?php js::set('withWeekend', $withWeekend); ?>
<?php js::set('chartData', $chartData); ?>
<?php js::set('YUnit', $lang->execution->count); ?>
<?php js::set('XUnit', $lang->execution->burnXUnit); ?>
<?php js::set('dateRangeTip', $lang->execution->charts->cfd->dateRangeTip); ?>
<?php js::set('minDate', $minDate); ?>
<?php js::set('maxDate', $maxDate); ?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php
    if(strpos('wait,doing', $execution->status) !== false)
    {
        common::printLink('execution', 'computeCFD', "reload=yes&executionID=$executionID", '<i class="icon icon-refresh"></i> ' . $lang->execution->computeCFD, 'hiddenwin', "title='{$lang->execution->computeCFD}' class='btn btn-primary' id='computeCFD'");
        echo '<div class="space"></div>';
    }
    $weekend = $withWeekend == 'true' ? 'noweekend' : 'withweekend';
    echo html::a('#', $lang->execution->$weekend, '', "class='btn btn-link' id='weekend'");
    ?>
    <?php if(!$features['story']) unset($lang->execution->cfdTypeList['story']);?>
    <?php if(!$features['qa'])    unset($lang->execution->cfdTypeList['bug']);?>
    <div class='input-control w-140px'>
      <?php echo html::select('type', $lang->execution->cfdTypeList, $type, "class='form-control chosen'");?>
    </div>
    <div id="cfdDateSelect">
    <form method='post' class='form-ajax not-watch no-stash'>
      <div class='input-group'>
      <?php echo html::input('begin', $begin, "class='form-control form-date' onchange='$(\"#datePreview\").removeClass(\"hidden\")' placeholder='" . $lang->execution->charts->cfd->begin . "'");?>
      <span class='input-group-addon'><?php echo $lang->project->to;?></span>
      <?php echo html::input('end', $end, "class='form-control form-date' onchange='$(\"#datePreview\").removeClass(\"hidden\")' placeholder='" . $lang->execution->charts->cfd->end . "'");?>
      </div>
      <?php echo html::commonButton($lang->preview, "onclick='$(this).closest(\"form\").submit()' id='datePreview'", 'btn btn-primary hidden');?>
    </form>
    </div>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <h2 class='text-center'><?php echo $executionName . ' - ' . zget($lang->execution->cfdTypeList, $type) . $lang->execution->CFD;?> <i class="icon icon-help" data-toggle="tooltip" data-tip-class="tooltip-help" data-html='true' data-placement="bottom" title="<?php echo $lang->execution->charts->cfd->cfdTip;?>"></i></h2>
  <?php if(isset($chartData['labels']) and count($chartData['labels']) != 1): ?>
  <div id="cfdWrapper">
    <div id="cfdChart" style="width: 1200px; height: 600px"></div>
    <div id="burnStatistics" class="hidden">
      <div class="stat-title"><span class="bg-primary">&nbsp;</span> <?php echo $lang->execution->charts->cfd->cycleTime;?> <i class="icon icon-help" data-toggle="tooltip" data-tip-class="tooltip-help" data-placement="bottom" title="<?php echo $lang->execution->charts->cfd->cycleTimeTip;?>"></i></div>
      <h3><?php echo !empty($cycleTimeAvg) ? ($cycleTimeAvg . $lang->day) : $lang->noData;?></h3>
      <div class="stat-title"><span class="bg-primary">&nbsp;</span> <?php echo $lang->execution->charts->cfd->throughput;?> <i class="icon icon-help" data-toggle="tooltip" data-tip-class="tooltip-help" data-placement="bottom" title="<?php echo $lang->execution->charts->cfd->throughputTip;?>"></i></div>
      <h3><?php echo !empty($throughput) ? $throughput : $lang->noData;?></h3>
    </div>
  </div>
  <?php else:?>
  <div class="table-empty-tip">
  <p><span class="text-muted"><?php echo $lang->execution->noPrintData;?></span></p>
  </div>
  <?php endif; ?>
</div>
<?php include '../../common/view/footer.html.php';?>
