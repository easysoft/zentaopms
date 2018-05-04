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
  <table class='table table-borderless table-fixed-head table-hover block-bugs <?php if(!$longBlock) echo 'block-sm'?>'>
    <thead>
      <tr>
        <th class='c-id'><?php echo $lang->idAB?></th>
        <?php if($longBlock):?>
        <th class='c-pri'><?php echo $lang->priAB?></th>
        <?php endif;?>
        <th class='c-level'><?php echo $lang->bug->severityAB?></th>
        <th class='c-name'><?php echo $lang->bug->title;?></th>
        <th class='c-status'><?php echo $lang->bug->statusAB;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($bugs as $bug):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      $viewLink = $this->createLink('bug', 'view', "bugID={$bug->id}");
      ?>
      <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
        <td class='c-id'><?php echo $bug->id;?></td>
        <?php if($longBlock):?>
        <td class='c-pri'><span class='label-pri label-pri-<?php echo $bug->pri?>'><?php echo zget($lang->bug->priList, $bug->pri)?></span></td>
        <?php endif;?>
        <td class='c-level'><span class='label-level' data-level='<?php echo zget($lang->bug->severityList, $bug->severity);?>'></span></td>
        <td class='c-name' style='color: <?php echo $bug->color?>' title='<?php echo $bug->title?>'><?php echo $bug->title?></td>
        <td class='c-status' title='<?php echo zget($lang->bug->statusList, $bug->status)?>'>
          <span class="bug-status-<?php echo $bug->status?>">
            <span class="label label-dot"></span>
            <span class='status-text'><?php echo zget($lang->bug->statusList, $bug->status);?></span>
          </span>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
