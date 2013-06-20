<?php
/**
 * The view file of view method of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: view.html.php 4642 2013-04-11 05:38:37Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/m.header.html.php';?>
</div>
<?php if(!$todo->private or ($todo->private and $todo->account == $app->user->account)):?>
  <h3 class='title'><?php echo "TODO #$todo->id $todo->name"?></h3>
  <div class='textContent'><?php echo $todo->desc;?></td>
<?php include '../../common/view/m.action.html.php';?>
<?php else:?>
<?php echo $lang->todo->thisIsPrivate;?>
<?php endif;?>
<div data-role='footer' data-position='fixed'>
  <div data-role='navbar'>
    <ul>
  <?php
  if($this->session->todoList)
  {
      $browseLink = $this->session->todoList;
  }
  else
  {
      $browseLink = $this->createLink('my', 'todo');
  }
  if(common::hasPriv('todo', 'finish') and $todo->status != 'done') echo '<li>' . html::a($this->createLink('todo', 'finish', "id=$todo->id"), $lang->todo->finish, 'hiddenwin') . '</li>';
  if($todo->account == $app->user->account and common::hasPriv('todo', 'delete'))
  {
      echo '<li>' . html::a($this->createLink('todo', 'delete', "todoID=$todo->id"), $lang->delete, 'hiddenwin') . '</li>';
  }
  echo '<li>' . html::a($browseLink, $lang->goback) . '</li>';
?>
    </ul>
  </div>
</div>
<?php include '../../common/view/m.footer.html.php';?>
