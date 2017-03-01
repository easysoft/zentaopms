<?php
/**
 * The case block view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<table class='table tablesorter table-data table-hover block-case table-fixed'>
  <thead>
  <tr>
    <th width='50'><?php echo $lang->idAB?></th>
    <th width='50'><?php echo $lang->priAB?></th>
    <th>           <?php echo $lang->case->title;?></th>
    <th width='120'> <?php echo $lang->testtask->lastRunTime;?></th>
    <th width='60'>  <?php echo $lang->testtask->lastRunResult;?></th>
    <th width='70'><?php echo $lang->statusAB;?></th> 
  </tr>
  </thead>
  <?php foreach($cases as $case):?>
  <?php
  $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
  $viewLink = $this->createLink('testcase', 'view', "caseID={$case->id}");
  ?>
  <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
    <td class='text-center'><?php echo $case->id;?></td>
    <td class='text-center'><?php echo zget($lang->case->priList, $case->pri, $case->pri)?></td>
    <td style='color: <?php echo $case->color?>' title='<?php echo $case->title?>'><?php echo $case->title?></td>
    <td class='text-center'><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
    <td class='text-center'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
    <td class='text-center'><?php echo $lang->testcase->statusList[$case->status];?></td>
  </tr>
  <?php endforeach;?>
</table>
<script>
if(typeof(dataTable) == 'function')$('.block-case').dataTable();
</script>
