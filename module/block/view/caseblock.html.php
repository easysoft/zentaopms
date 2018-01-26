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
<?php $longBlock = $block->grid >= 6;?>
<table class='table table-borderless table-hover table-fixed block-case'>
  <thead>
  <tr>
    <?php if($longBlock):?>
    <th width='50'><?php echo $lang->idAB?></th>
    <?php endif;?>
    <th width='50'><?php echo $lang->priAB?></th>
    <th>           <?php echo $lang->case->title;?></th>
    <?php if($longBlock):?>
    <th width='120'> <?php echo $lang->testtask->lastRunTime;?></th>
    <th width='60'>  <?php echo $lang->testtask->lastRunResult;?></th>
    <?php endif;?>
    <th width='70'><?php echo $lang->statusAB;?></th> 
  </tr>
  </thead>
  <?php foreach($cases as $case):?>
  <?php
  $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
  $viewLink = $this->createLink('testcase', 'view', "caseID={$case->id}");
  ?>
  <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
    <?php if($longBlock):?>
    <td class='text-center'><?php echo $case->id;?></td>
    <?php endif;?>
    <td class='text-center'><?php echo zget($lang->case->priList, $case->pri, $case->pri)?></td>
    <td style='color: <?php echo $case->color?>' title='<?php echo $case->title?>'><?php echo $case->title?></td>
    <?php if($longBlock):?>
    <td class='text-center'><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
    <td class='text-center'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
    <?php endif;?>
    <td class='text-left' title='<?php echo zget($lang->testcase->statusList, $case->status)?>'>
      <span class="project-status-<?php echo $case->status?>">
        <span class="label label-dot"></span>
        <?php if($longBlock) echo zget($lang->testcase->statusList, $case->status);?>
      </span>
    </td>
  </tr>
  <?php endforeach;?>
</table>
