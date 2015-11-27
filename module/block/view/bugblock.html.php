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
<table class='table table-data table-hover block-bug table-fixed'>
  <thead>
  <tr>
    <th width='50'><?php echo $lang->idAB?></th>
    <th width='40'><?php echo $lang->bug->severityAB?></th>
    <th width='40'><?php echo $lang->priAB?></th>
    <th>           <?php echo $lang->bug->title;?></th>
  </tr>
  </thead>
  <?php foreach($bugs as $bug):?>
  <?php $appid = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : ''?>
  <tr data-url='<?php echo $sso . $sign . 'referer=' . base64_encode($this->createLink('bug', 'view', "bugID={$bug->id}")); ?>' <?php echo $appid?>>
    <td><?php echo $bug->id;?></td>
    <td><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity)?></td>
    <td><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></td>
    <td title='<?php echo $bug->title?>'><?php echo $bug->title?></td>
  </tr>
  <?php endforeach;?>
</table>
<p class='hide block-bug-link'><?php echo $listLink;?></p>
<script>
$('.block-bug').dataTable();
$('.block-bug-link').closest('.panel').find('.panel-heading .more').attr('href', $('.block-bug-link').html());
</script>
