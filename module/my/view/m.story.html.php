<?php
/**
 * The story view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     my
 * @version     $Id: story.html.php 4735 2013-05-03 08:30:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/m.header.html.php';?>
<div data-role='navbar' style='margin:-15px 0 15px 0;'>
  <ul>
    <?php foreach($config->mobile->storyBar as $menu):?>
    <?php $active = $type == $menu ? 'ui-btn-active' : ''?>
    <li><?php echo html::a($this->createLink('my', 'story', "type=$menu"), $lang->my->storyMenu->{$menu . 'Me'}, '', "class='$active'")?></li>
    <?php endforeach;?>
  </ul>
</div>
<ul data-role='listview'>
    <?php foreach($stories as $story):?>
    <li><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title)?></li>
    <?php endforeach;?>
</ul>
<p>
<?php $pager->show('right', 'short')?>
</p>
<?php include '../../common/view/m.footer.html.php';?>
