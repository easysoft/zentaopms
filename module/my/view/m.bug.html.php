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
    <?php foreach($config->mobile->bugBar as $menu):?>
    <?php $active = $type == $menu ? 'ui-btn-active' : ''?>
    <li>
    <?php 
    $subMenuName = $menu == 'assignedTo' ? $lang->bug->assignToMe : $lang->bug->{$menu . 'Me'};
    echo html::a($this->createLink('my', 'bug', "type=$menu"), $subMenuName, '', "class='$active'");
    ?>
    </li>
    <?php endforeach;?>
  </ul>
</div>
<ul data-role='listview'>
    <?php foreach($bugs as $bug):?>
    <li><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title)?></li>
    <?php endforeach;?>
</ul>
<?php include '../../common/view/m.footer.html.php';?>
