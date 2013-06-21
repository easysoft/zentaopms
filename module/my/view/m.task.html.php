<?php
/**
 * The task view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     my
 * @version     $Id: task.html.php 4735 2013-05-03 08:30:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/m.header.html.php';?>
  <div data-role='navbar' id='subMenu'>
    <ul>
      <?php foreach($config->mobile->taskBar as $menu):?>
      <?php $active = $type == $menu ? 'ui-btn-active' : ''?>
      <li><?php echo html::a($this->createLink('my', 'task', "type=$menu"), $lang->my->taskMenu->{$menu . 'Me'}, '', "class='$active' data-theme='d'")?></li>
      <?php endforeach;?>
    </ul>
  </div>
</div>
<?php foreach($tasks as $task):?>
<div  data-role="collapsible-set">
  <div data-role="collapsible" data-collapsed="true">
    <h1><?php echo $task->name;?></h1>
    <div><?php echo $task->desc;?></div>
    <div data-role='navbar'>
      <ul>
        <?php 
        common::printIcon('task', 'assignTo',       "projectID=$task->project&taskID=$task->id", $task);
        common::printIcon('task', 'start',          "taskID=$task->id", $task);
        common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task);
        common::printIcon('task', 'finish',         "taskID=$task->id", $task);
        common::printIcon('task', 'close',          "taskID=$task->id", $task);
        common::printIcon('task', 'activate',       "taskID=$task->id", $task);
        ?>
      </ul>
    </div>
  </div>
</div>
<?php endforeach;?>
<p><?php $pager->show('right', 'short')?></p>
<?php include '../../common/view/m.footer.html.php';?>
