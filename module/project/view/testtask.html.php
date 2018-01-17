<?php
/**
 * The browse view file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: browse.html.php 1914 2011-06-24 10:11:25Z yidong@cnezsoft.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmDelete', $lang->testtask->confirmDelete)?>
<div id='titlebar'>
  <div class='heading'>
    <?php echo html::icon($lang->icons['test']);?> <?php echo $lang->testtask->browse;?>
  </div>
  <div class='actions'>
    <?php
    common::printIcon('testreport', 'browse', "objectID=$projectID&objectType=project", '', 'button','flag');
    common::printIcon('testtask', 'create', "product=0&project=$projectID");
    ?>
  </div>
</div>

<form method='post' target='hiddenwin' id='testtaskForm'>
<table class='table tablesorter table-fixed' id='taskList'>
  <thead>
  <tr>
    <th class='w-id'><?php echo $lang->idAB;?></th>
    <th class='w-150px'><?php echo $lang->testtask->product;?></th>
    <th><?php echo $lang->testtask->name;?></th>
    <th><?php echo $lang->testtask->build;?></th>
    <th class='w-user'><?php echo $lang->testtask->owner;?></th>
    <th class='w-100px'><?php echo $lang->testtask->begin;?></th>
    <th class='w-100px'><?php echo $lang->testtask->end;?></th>
    <th class='w-80px'><?php echo $lang->statusAB;?></th>
    <th class='w-120px {sorter:false}'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($tasks as $task):?>
  <tr class='text-center'>
    <td class='cell-id'>
    <?php
    echo "<input type='checkbox' name='taskIdList[]' value='{$task->id}' /> ";
    echo html::a($this->createLink('testtask', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));
    ?>
    </td>
    <td title="<?php echo zget($products, $task->product, '')?>"><?php echo zget($products, $task->product, '');?></td>
    <td class='text-left' title="<?php echo $task->name?>"><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id"), $task->name);?></td>
    <td title="<?php echo $task->buildName?>"><?php echo ($task->build == 'trunk' || empty($task->buildName)) ? $lang->trunk : html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName);?></td>
    <td><?php echo $users[$task->owner];?></td>
    <td><?php echo $task->begin?></td>
    <td><?php echo $task->end?></td>
    <td class='status-<?php echo $task->status?>'><?php echo $lang->testtask->statusList[$task->status];?></td>
    <td>
      <?php
      common::printIcon('testtask', 'cases',    "taskID=$task->id", $task, 'list', 'sitemap');
      common::printIcon('testtask', 'linkCase', "taskID=$task->id", $task, 'list', 'link');
      common::printIcon('testtask', 'edit',     "taskID=$task->id", $task, 'list');
      common::printIcon('testreport', 'browse', "objectID=$task->product&objectType=product&extra=$task->id", $task, 'list','flag');

      if(common::hasPriv('testtask', 'delete', $task))
      {
          $deleteURL = $this->createLink('testtask', 'delete', "taskID=$task->id&confirm=yes");
          echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"taskList\",confirmDelete)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->testtask->delete}'");
      }
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='9'>
        <div class='table-actions clearfix'>
          <?php
          echo html::selectButton();

          $actionLink = $this->createLink('testreport', 'browse', "objectID=$projectID&objctType=project");
          $misc       = common::hasPriv('testreport', 'browse') ? "onclick=\"setFormAction('$actionLink', '', '#testtaskForm')\"" : "disabled='disabled'";
          echo html::commonButton($lang->testreport->common, $misc);
          ?>
        </div>
      </td>
    </tr>
  </tfoot>
</table>
</form>
<?php include '../../common/view/footer.html.php';?>
