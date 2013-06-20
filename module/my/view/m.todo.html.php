<?php
/**
 * The todo view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     my
 * @version     $Id: todo.html.php 4735 2013-05-03 08:30:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/m.header.html.php';?>
  <div data-role='navbar' id='subMenu'>
    <ul>
      <?php foreach($config->mobile->todoBar as $period):?>
      <?php $active = $type == $period ? 'ui-btn-active' : ''?>
      <li><?php echo html::a($this->createLink('my', 'todo', "type=$period"), $lang->todo->periods[$period], '', "class='$active' data-theme='d'")?></li>
      <?php endforeach;?>
    </ul>
  </div>
</div>
<ul data-role='listview'>
  <?php foreach($todos as $todo):?>
  <li><?php echo html::a($this->createLink('todo', 'view', "todoID=$todo->id&from=my"), $todo->name)?></li>
  <?php endforeach;?>
</ul>
<p><?php echo $pager->show('right', 'short')?></p>
<div data-role='footer' data-position='fixed'>
  <div data-role='navbar'>
    <ul>
      <li><?php echo html::a($this->createLink('todo', 'batchCreate'), $lang->todo->create)?></li>
    </ul>
  </div>
</div>
<?php include '../../common/view/m.footer.html.php';?>
