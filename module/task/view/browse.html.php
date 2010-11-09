<?php
/**
 * The browse view file of task module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='doc3'>
  <table align='center' class='table-4'>
    <caption><?php echo $lang->task->browse;?></caption>
    <tr>
      <th><?php echo $lang->task->id;?></th>
      <th><?php echo $lang->task->name;?></th>
      <th><?php echo $lang->task->owner;?></th>
    </tr>
    <?php foreach($tasks as $task):?>
    <tr>
      <td><?php echo $task->id;?></td>
      <td><?php echo $task->name;?></td>
      <td><?php echo $task->owner;?></td>
    </tr>
    <?php endforeach;?>
  </table>
  <?php 
  $vars['project'] = $project;
  $addLink = $this->createLink($this->moduleName, 'create', $vars);
  echo "<a href='$addLink'>{$lang->task->create}</a>";
  ?>
</div>  
<?php include '../../common/view/footer.html.php';?>
