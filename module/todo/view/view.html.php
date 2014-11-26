<?php
/**
 * The view file of view method of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: view.html.php 4955 2013-07-02 01:47:21Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if(!$todo->private or ($todo->private and $todo->account == $app->user->account)):?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix' title='TODO'><?php echo html::icon($lang->icons['todo']);?> <strong><?php echo $todo->id;?></strong></span>
      <strong><?php echo $todo->name;?></strong>
    </div>
  </div>
  <div class='row-table'>
    <div class='col-main'>
      <div class='main'>
        <fieldset>
          <legend>
            <?php 
            echo $lang->todo->desc;
            if($todo->type == 'bug')    echo html::a($this->createLink('bug',  'view', "id={$todo->idvalue}"), '  BUG#' . $todo->idvalue);
            if($todo->type == 'task')   echo html::a($this->createLink('task', 'view', "id={$todo->idvalue}"), '  TASK#' . $todo->idvalue);
            ?>
          </legend>
          <div><?php echo $todo->desc;?></div>
        </fieldset>
        <?php $actionTheme = 'fieldset'; include '../../common/view/action.html.php';?>
      </div>
    </div>
    <div class='col-side'>
      <div class='main main-side'>
        <fieldset>
        <legend><?php echo $lang->todo->legendBasic;?></legend>
          <table class='table table-data table-condensed table-borderless'> 
            <tr>
              <th><?php echo $lang->todo->pri;?></th>
              <td><?php echo $lang->todo->priList[$todo->pri];?></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->status;?></th>
              <td class='todo-<?php echo $todo->status?>'><?php echo $lang->todo->statusList[$todo->status];?></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->type;?></th>
              <td><?php echo $lang->todo->typeList[$todo->type];?></td>
            </tr>
            <tr>
              <th class='w-80px'><?php echo $lang->todo->account;?></th>
              <td><?php echo $todo->account;?></td>
            </tr>
            <tr>
              <th class='w-80px'><?php echo $lang->todo->date;?></th>
              <td><?php echo $todo->date == '20300101' ? $lang->todo->periods['future'] : date(DT_DATE1, strtotime($todo->date));?></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->beginAndEnd;?></th>
              <td><?php if(isset($times[$todo->begin])) echo $times[$todo->begin]; if(isset($times[$todo->end])) echo ' ~ ' . $times[$todo->end];?></td>
            </tr>
          </table>
      </div>
    </div>
  </div>
  <div class='panel-footer text-center'>
    <?php
    if($this->session->todoList)
    {
        $browseLink = $this->session->todoList;
    }
    elseif($todo->account == $app->user->account)
    {
        $browseLink = $this->createLink('my', 'todo');
    }
    else
    {
        $browseLink = $this->createLink('user', 'todo', "account=$todo->account");
    }

    common::printIcon('todo', 'finish', "id=$todo->id", $todo, 'button', '', 'hiddenwin', 'showinonlybody btn-success');
    if($todo->account == $app->user->account)
    {
        common::printIcon('todo', 'edit',   "todoID=$todo->id");
        common::printIcon('todo', 'delete', "todoID=$todo->id", '', 'button', '', 'hiddenwin');
    }
    common::printRPN($browseLink);
    ?>
  </div>
</div>
<?php else:?>
<?php echo $lang->todo->thisIsPrivate;?>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
