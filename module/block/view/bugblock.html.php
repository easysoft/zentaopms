<?php
/**
 * The bug block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(empty($bugs)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.block-bugs .c-id {width: 55px;}
.block-bugs .c-level {width: 60px;text-align: center;}
.block-bugs .c-pri {width: 35px;text-align: center;}
.block-bugs .c-date {width: 140px;}
.block-bugs .c-status {width: 80px;}
.block-bugs.block-sm .c-status {text-align: center;}
</style>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-fixed table-fixed-head table-hover tablesorter block-bugs <?php if(!$longBlock) echo 'block-sm'?>'>
    <thead>
      <tr>
        <th class='c-id'><?php echo $lang->idAB?></th>
        <th class='c-name'><?php echo $lang->bug->title;?></th>
        <?php if($longBlock):?>
        <th class='c-level'><?php echo $lang->bug->abbr->severity?></th>
        <th class='c-pri'><?php echo $lang->priAB?></th>
        <th class='c-date'><?php echo $lang->bug->deadline;?></th>
        <?php endif;?>
        <th class='c-status'><?php echo $lang->bug->abbr->status;?></th>
      </tr>
    </thead>
    <?php
    $hasCustomSeverity = false;
    foreach($lang->bug->severityList as $severityKey => $severityValue)
    {
        if(!empty($severityKey) && (string)$severityKey != (string)$severityValue)
        {
            $hasCustomSeverity = true;
            break;
        }
    }
    ?>
    <tbody>
      <?php foreach($bugs as $bug):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      ?>
      <tr>
        <td class='c-id-xs'><?php echo sprintf('%03d', $bug->id);?></td>
        <td class='c-name' title='<?php echo $bug->title?>'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, '', "style='color: $bug->color'")?></td>
        <?php if($longBlock):?>
        <td class='c-severity'>
          <?php if($hasCustomSeverity):?>
          <span class='<?php echo 'label-severity-custom';?>' title='<?php echo zget($lang->bug->severityList, $bug->severity);?>' data-severity='<?php echo $bug->severity;?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?></span>
          <?php else:?>
          <span class='label-severity' title='<?php echo zget($lang->bug->severityList, $bug->severity);?>' data-severity='<?php echo $bug->severity;?>'></span>
          <?php endif;?>
        </td>
        <td class='c-pri'><span class='label-pri label-pri-<?php echo $bug->pri?>' title='<?php echo zget($lang->bug->priList, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri)?></span></td>
        <td class='c-date'><?php echo helper::isZeroDate($bug->deadline) ? '' : $bug->deadline;?></td>
        <?php endif;?>
        <?php $status = $this->processStatus('bug', $bug);?>
        <td class='c-status' title='<?php echo $status;?>'>
          <span class="status-bug status-<?php echo $bug->status?>"><?php echo $status;?></span>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
