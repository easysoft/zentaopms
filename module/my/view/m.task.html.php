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
  <ul>
    <?php foreach($config->mobile->taskBar as $menu):?>
    <?php $active = $type == $menu ? 'ui-btn-active' : ''?>
    <li><?php echo html::a($this->createLink('my', 'task', "type=$menu"), $lang->my->taskMenu->{$menu . 'Me'}, '', "class='$active'")?></li>
    <?php endforeach;?>
  </ul>
</div>
<ul data-role='listview'>
    <?php foreach($tasks as $task):?>
    <li><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name)?></li>
    <?php endforeach;?>
</ul>
<p><?php $pager->show('right', 'short')?></p>
<?php include '../../common/view/m.footer.html.php';?>
