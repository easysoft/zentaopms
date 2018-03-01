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
<div class='panel-body has-table'>
  <style>
  .block-cases.block-sm .c-status{text-align:center}
  .block-cases.block-sm .c-status .status-text{display:none;}
  .case-status-normal {color: #a6aab8;}
  .case-status-normal > .label-dot {background-color: #006af1;}
  .case-status-blocked { color: #a6aab8; }
  .case-status-blocked > .label-dot { background-color: #00da88; }
  .case-status-investigate {color: #a6aab8;}
  .case-status-investigate > .label-dot {background-color: #006af1;}
  </style>
  <table class='table table-borderless table-hover table-fixed-head block-cases <?php if(!$longBlock) echo 'block-sm';?>'>
    <thead>
      <tr>
        <?php if($longBlock):?>
        <th class='c-id' width='50'><?php echo $lang->idAB?></th>
        <?php endif;?>
        <th class='c-pri' width='50'><?php echo $lang->priAB?></th>
        <th class='c-title'><?php echo $lang->case->title;?></th>
        <?php if($longBlock):?>
        <th class='c-runtime' width='120'> <?php echo $lang->testtask->lastRunTime;?></th>
        <th class='c-result' width='60'>  <?php echo $lang->testtask->lastRunResult;?></th>
        <?php endif;?>
        <th class='c-status' width='70'><?php echo $lang->statusAB;?></th> 
      </tr>
    </thead>
    <tbody>
      <?php foreach($cases as $case):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      $viewLink = $this->createLink('testcase', 'view', "caseID={$case->id}");
      ?>
      <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
        <?php if($longBlock):?>
        <td class='text-center c-id'><?php echo $case->id;?></td>
        <?php endif;?>
        <td class='text-center c-pri'><span class='label-pri label-pri-<?php echo $case->pri?>'><?php echo zget($lang->case->priList, $case->pri, $case->pri)?></span></td>
        <td class='c-title' style='color: <?php echo $case->color?>' title='<?php echo $case->title?>'><?php echo $case->title?></td>
        <?php if($longBlock):?>
        <td class='text-center c-runtime'><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
        <td class='text-center c-result'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
        <?php endif;?>
        <td class='text-left c-status' title='<?php echo zget($lang->testcase->statusList, $case->status)?>'>
          <span class="case-status-<?php echo $case->status?>">
            <span class="label label-dot"></span>
            <span class='status-text'><?php echo zget($lang->testcase->statusList, $case->status);?></span>
          </span>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
