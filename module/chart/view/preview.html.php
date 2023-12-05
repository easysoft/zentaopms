<?php
/**
 * The preview view file of chart module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     chart
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::import($jsRoot . 'moment/moment.min.js');?>
<?php js::import($jsRoot . 'echarts/echarts.common.min.js');?>
<?php js::set('groupID', isset($group->id) ? $group->id : 0);?>
<?php js::set('charts', $charts);?>
<?php js::set('noChartSelected', $lang->chart->noChartSelected);?>
<?php js::set('chartLang', $lang->chart);?>
<?php js::set('WIDTH_INPUT',  $config->chart->widthInput);?>
<?php js::set('WIDTH_DATE',   $config->chart->widthDate);?>
<?php js::set('pickerHeight', $config->bi->pickerHeight);?>
<?php $queryDom = "<div class='queryBtn query-inside hidden'> <button type='submit' id='submit' onclick='queryData(this)' class='btn btn-primary btn-query' data-loading='Loading...'>{$lang->chart->query}</button></div>";?>
<?php js::set('queryDom', $queryDom);?>

<div id='mainMenu' class='clearfix main-position'>
  <div class='btn-toolBar pull-left parent-position'>
    <?php
    foreach($groups as $groupID => $groupName)
    {
        if(!$groupID) continue;
        $activeClass = $groupID == $group->id ? 'btn-active-text' : '';
        echo html::a(inlink('preview', "dimensionID={$dimensionID}&groupID={$groupID}"), $groupName, '', "class='btn btn-link {$activeClass}' id='{$groupID}Tab'");
    }
    ?>
  </div>
  <?php if($this->config->edition == 'biz' or $this->config->edition == 'max'):?>
  <div class='btn-toolbar pull-right child-position'>
    <?php common::printLink('chart', 'export', '', "<i class='icon icon-export muted'> </i> " . $lang->export, '', "class='btn btn-link btn-export' id='exportchart'");?>
    <?php common::printLink('chart', 'browse', '', $lang->chart->toDesign, '', "class='btn btn-primary '");?>
  </div>
  <?php endif;?>
</div>

<div id="mainContent" class='main-row'>
  <div class='side-col col-lg'>
    <div class="cell">
      <div class='panel'>
        <div class='panel-heading text-ellipsis'>
          <div class='panel-title'><?php echo isset($group->name) ? $group->name : '';?></div>
        </div>
        <div class='panel-body'>
          <?php if(!$chartTree):?>
          <hr class="space">
          <div class="text-center text-muted">
            <?php echo $lang->chart->noChart;?>
          </div>
          <hr class="space">
          <?php elseif($chartTree):?>
          <form method='post'>
            <?php echo $chartTree;?>
            <div class='btn-toolbar'>
              <?php echo html::selectAll();?>
              <?php echo html::submitButton($lang->chart->preview, '', 'btn btn-primary');?>
            </div>
          </form>
          <?php endif;?>
          <?php if($this->config->edition == 'open'):?>
          <div class='text biz-version'>
            <span class='text-important'><?php echo (!empty($config->isINT)) ? $lang->bizVersionINT : $lang->bizVersion;?></span>
          </div>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
  <div class='main-col'>
    <div class='cell'>
      <?php if(!$chartTree):?>
      <hr class="space">
      <div class="text-center text-muted">
        <?php echo $lang->chart->noChart;?>
      </div>
      <hr class="space">
      <?php else:?>
      <div class='btn-toolbar pull-right'>
      </div>
      <?php foreach($charts as $chart):?>
      <div class='panel'>
        <div class='panel-heading'>
          <div class='panel-title'>
            <?php echo $chart->name;?>
            <?php if(!empty($chart->desc)):?>
            <a data-toggle='tooltip' data-placement='auto' title='<?php echo $chart->desc;?>'><i class='icon-help'></i></a>
            <?php endif;?>
          </div>
        </div>
        <div class='panel-body'>
          <div id="filterItems<?php echo $chart->currentGroup . '_';?><?php echo $chart->id;?>" data-chart="<?php echo $chart->currentGroup . '_' . $chart->id?>"class='filterBox'>
            <div class='filter-items'></div>
            <?php if(!empty($chart->filters)):?>
            <div class='queryBtn query-outside'><?php echo html::submitButton($lang->chart->query, "onclick='queryData(this)'", 'btn btn-primary btn-query');?></div>
            <?php endif;?>
          </div>
          <div id="chartDraw<?php echo $chart->currentGroup . '_';?><?php echo $chart->id;?>" data-group="<?php echo $chart->currentGroup;?>" data-id="<?php echo $chart->id;?>" class='echart-content'></div>
        </div>
      </div>
      <?php endforeach;?>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

<template id='filterItemTpl'>
  <div class='filter-item filter-item-{index}'>
    <div class='input-group'>
      <span class='input-group-addon field-name'>{name}</span>
      {search}
    </div>
  </div>
</template>
