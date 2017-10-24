<?php
/**
 * The log view file of entry module of RanZhi.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     entry 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include 'header.html.php';?>
<table class='table table-condensed table-hover table-striped'>
  <thead>
    <tr>
      <th class='w-120px'><?php echo $lang->action->date;?></th>
      <th class='w-100px'><?php echo $lang->action->actor;?></th>
      <th><?php echo $lang->entry->desc;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($actions as $action);?>
    <tr>
      <td><?php echo $action->date;?></td>
      <td><?php echo zget($users, $action->actor);?></td>
      <td><?php echo $action->extra;?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<?php include '../../common/view/footer.html.php';?>
