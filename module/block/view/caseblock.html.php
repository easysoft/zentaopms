<?php
/**
 * The case block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php if(empty($cases)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<div class='panel-body has-table scrollbar-hover'>
  <style>
  .block-cases.block-sm .c-status{text-align:center}
  .block-cases.block-sm .c-status .status-text{display:none;}
  .case-status-normal {color: #a6aab8;}
  .case-status-blocked { color: #a6aab8; }
  .case-status-investigate {color: #a6aab8;}
  </style>
  <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter block-cases <?php if(!$longBlock) echo 'block-sm';?>'>
    <thead>
      <tr class='text-center'>
        <th class='c-id-xs'><?php echo $lang->idAB?></th>
        <th class='c-pri'><?php echo $lang->priAB?></th>
        <th class='c-title text-left'><?php echo $lang->case->title;?></th>
        <?php if($longBlock):?>
        <th class='c-runtime'><?php echo $lang->testtask->lastRunTime;?></th>
        <th class='c-result'><?php echo $lang->testtask->lastRunResult;?></th>
        <th class='c-status'><?php echo $lang->statusAB;?></th> 
        <?php endif;?>
      </tr>
    </thead>
    <tbody>
      <?php foreach($cases as $case):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      $viewLink = $this->createLink('testcase', 'view', "caseID={$case->id}");
      ?>
      <tr class='text-center' <?php echo $appid?>>
        <td class='c-id-xs'><?php echo sprintf('%03d', $case->id);?></td>
        <td class='c-pri'><span class='label-pri label-pri-<?php echo $case->pri?>' title='<?php echo zget($lang->case->priList, $case->pri, $case->pri);?>'><?php echo zget($lang->case->priList, $case->pri, $case->pri)?></span></td>
        <td class='c-title text-left' style='color: <?php echo $case->color?>' title='<?php echo $case->title?>'><?php echo $case->title?></td>
        <?php if($longBlock):?>
        <td class='c-runtime'><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
        <td class='c-result'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
        <?php $status = $this->processStatus('testcase', $case);?>
        <td class='c-status' title='<?php echo $status;?>'>
          <span class="case-status-<?php echo $case->status?>">
            <span class='status-text'><?php echo $status;?></span>
          </span>
        </td>
        <?php endif;?>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
