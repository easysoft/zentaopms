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
<?php $this->session->set('todoType', $type);?>
<?php foreach($todos as $todo):?>
<?php if(!$todo->private or ($todo->private and $todo->account == $app->user->account)):?>
<div data-role="collapsible-set">
  <div data-role="collapsible" data-collapsed="true" class='collapsible'>
    <h1 onClick="showDetail('todo', <?php echo $todo->id;?>)"><?php echo $todo->name;?></h1>
    <div><?php echo $todo->desc;?></div>
    <div id='item<?php echo $todo->id;?>'><?php echo $todo->desc;?></div>
    <div data-role='content' class='text-center'>
      <?php
      common::printIcon('todo', 'finish', "id=$todo->id", $todo, 'button', '', 'hiddenwin');
      if($todo->account == $app->user->account)
      {
          if($type != 'today') common::printIcon('todo', 'import2Today',   "todoID=$todo->id");
          common::printIcon('todo', 'delete', "todoID=$todo->id", '', 'button', '', 'hiddenwin');
      }
      ?>
    </div>
    <?php if(end($todos) == $todo) echo "<hr />";?>
  </div>
</div>
<?php endif;?>
<?php endforeach;?>
<?php echo $pager->show('left', 'mobile')?>
<div data-role='footer' data-position='fixed'>
  <div data-role='navbar' data-iconpos="left">
    <ul>
      <li><?php echo html::a($this->createLink('todo', 'batchCreate'), $lang->todo->create, '', "data-icon='plus' data-theme='e'")?></li>
    </ul>
  </div>
</div>
<?php include '../../common/view/m.footer.html.php';?>
