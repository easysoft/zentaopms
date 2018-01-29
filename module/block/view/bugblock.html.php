<?php
/**
 * The bug block view file of block module of RanZhi.
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
  <table class='table table-borderless table-fixed table-hover block-bug'>
    <thead>
      <tr>
        <?php if($longBlock):?>
        <th width='50'><?php echo $lang->idAB?></th>
        <?php endif;?>
        <th width='50'><?php echo $lang->priAB?></th>
        <th>           <?php echo $lang->bug->title;?></th>
        <th width='50'><?php echo $lang->bug->severityAB?></th>
        <th width='<?php echo $longBlock ? '90' : '60';?>'><?php echo $lang->bug->statusAB;?></th>
      </tr>
    </thead>
    <?php foreach($bugs as $bug):?>
    <?php
    $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
    $viewLink = $this->createLink('bug', 'view', "bugID={$bug->id}");
    ?>
    <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
      <?php if($longBlock):?>
      <td class='text-center'><?php echo $bug->id;?></td>
      <?php endif;?>
      <td class='text-center'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></td>
      <td style='color: <?php echo $bug->color?>' title='<?php echo $bug->title?>'><?php echo $bug->title?></td>
      <td class='text-center'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity)?></td>
      <td class='text-left' title='<?php echo zget($lang->bug->statusList, $bug->status)?>'>
        <span class="project-status-<?php echo $bug->status?>">
          <span class="label label-dot"></span>
          <?php if($longBlock) echo zget($lang->bug->statusList, $bug->status);?>
        </span>
      </td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
