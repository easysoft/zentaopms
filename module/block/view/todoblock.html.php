<?php
/**
 * The todo block view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<table class='table tablesorter table-data table-hover block-todo table-fixed'>
  <thead>
  <tr>
    <th width='90'><?php echo $lang->todo->date?></th>
    <th width='40'><?php echo $lang->priAB?></th>
    <th>           <?php echo $lang->todo->name;?></th>
    <th width='50'><?php echo $lang->todo->beginAB;?></th>
    <th width='50'><?php echo $lang->todo->endAB;?></th>
  </tr>
  </thead>
  <?php foreach($todos as $id => $todo):?>
  <?php
  $appid = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
  $viewLink = $this->createLink('todo', 'view', "todoID={$todo->id}&from=my");
  ?>
  <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
    <td><?php echo $todo->date == '2030-01-01' ? $lang->todo->periods['future'] : $todo->date;?></td>
    <td><?php echo zget($lang->todo->priList, $todo->pri, $todo->pri)?></td>
    <td><?php echo $todo->name?></td>
    <td><?php echo $todo->begin?></td>
    <td><?php echo $todo->end?></td>
  </tr>
  <?php endforeach;?>
</table>
<script>
if(typeof(dataTable) == 'function')$('.block-todo').dataTable();
</script>
