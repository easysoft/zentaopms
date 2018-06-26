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
<?php js::set('confirmDelete', $lang->testtask->confirmDelete)?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!empty($tasks)):?>
    <div class="pull-left table-group-btns">
      <button type="button" class="btn btn-link group-collapse-all"><?php echo $lang->testtask->collapseAll;?> <i class="icon-fold-all muted"></i></button>
      <button type="button" class="btn btn-link group-expand-all"><?php echo $lang->testtask->expandAll;?> <i class="icon-unfold-all muted"></i></button>
    </div>
    <?php endif;?>
    <?php $total = 0;?>
    <?php foreach($tasks as $productTasks) $total += count($productTasks);?>
    <a href='' class='btn btn-link btn-active-text'>
      <span class='text'><?php echo $lang->testtask->browse;?></span>
      <span class="label label-light label-badge"><?php echo $total;?></span>
    </a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printIcon('testreport', 'browse', "objectID=$projectID&objectType=project", '', 'button','flag muted');?>
    <?php common::printLink('testtask', 'create', "product=0&project=$projectID", "<i class='icon icon-plus'></i> " . $lang->testtask->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($tasks)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->testtask->noTesttask;?></span>
      <?php if(common::hasPriv('testtask', 'create')):?>
      <span class="text-muted"><?php echo $lang->youCould;?></span>
      <?php echo html::a($this->createLink('testtask', 'create', "product=0&project=$projectID"), "<i class='icon icon-plus'></i> " . $lang->testtask->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class="main-table table-testtask" data-ride="table" data-group="true" method="post" target='hiddenwin' id='testtaskForm'>
    <table class="table table-grouped has-sort-head" id='taskList'>
      <thead>
        <?php $vars = "projectID=$projectID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <?php $canTestReport = common::hasPriv('testreport', 'browse');?>
        <tr class='<?php if($total) echo 'divider'; ?>'>
          <th class='c-side text-center'><?php common::printOrderLink('product', $orderBy, $vars, $lang->testtask->product);?></th>
          <th class="c-id">
            <?php if($canTestReport):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->testtask->name);?></th>
          <th><?php common::printOrderLink('build', $orderBy, $vars, $lang->testtask->build);?></th>
          <th class='w-user'><?php common::printOrderLink('owner', $orderBy, $vars, $lang->testtask->owner);?></th>
          <th class='w-100px'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->testtask->begin);?></th>
          <th class='w-100px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->testtask->end);?></th>
          <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->statusAB);?></th>
          <th class='c-actions-5 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($tasks as $product => $productTasks):?>
        <?php $productName = zget($products, $product, '');?>
        <?php foreach($productTasks as $task):?>
        <tr data-id='<?php echo $product;?>' <?php if($task == reset($productTasks)) echo "class='divider-top'";?>>
          <?php if($task == reset($productTasks)):?>
          <td rowspan='<?php echo count($productTasks);?>' class='c-side text-left group-toggle'>
            <a class='text-primary' title='<?php echo $productName;?>'><i class='icon icon-caret-down'></i> <?php echo $productName;?></a>
            <div class='small'><span class='text-muted'><?php echo $lang->testtask->allTasks;?></span> <?php echo count($productTasks);?></div>
          </td>
          <?php endif;?>
          <td class="cell-id">
            <?php if($canTestReport):?>
            <?php echo html::checkbox('taskIdList', array($task->id => sprintf('%03d', $task->id)));?>
            <?php else:?>
            <?php printf('%03d', $task->id);?>
            <?php endif;?>
          </td>
          <td class='text-left' title="<?php echo $task->name?>"><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id"), $task->name);?></td>
          <td title="<?php echo $task->buildName?>"><?php echo ($task->build == 'trunk' || empty($task->buildName)) ? $lang->trunk : html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName);?></td>
          <td><?php echo zget($users, $task->owner);?></td>
          <td><?php echo $task->begin?></td>
          <td><?php echo $task->end?></td>
          <td title='<?php echo $lang->testtask->statusList[$task->status];?>'>
            <span class='status-<?php echo $task->status?>'>
              <span class='label label-dot'></span>
              <span class='status-text'><?php echo $lang->testtask->statusList[$task->status];?></span>
            </span>
          </td>
          <td class='c-actions'>
            <?php
            common::printIcon('testtask',   'cases',    "taskID=$task->id", $task, 'list', 'sitemap');
            common::printIcon('testtask',   'linkCase', "taskID=$task->id", $task, 'list', 'link');
            common::printIcon('testreport', 'browse',   "objectID=$task->product&objectType=product&extra=$task->id", $task, 'list','flag');
            common::printIcon('testtask',   'edit',     "taskID=$task->id", $task, 'list');
            if(common::hasPriv('testtask', 'delete', $task))
            {
                $deleteURL = $this->createLink('testtask', 'delete', "taskID=$task->id&confirm=yes");
                echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"taskList\",confirmDelete)", '<i class="icon-trash"></i>', '', "class='btn' title='{$lang->testtask->delete}'");
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
        <tr data-id='<?php echo $product;?>' class='group-toggle group-summary divider hidden'>
          <td class='c-side text-left'>
            <a title='<?php echo $productName;?>'><i class='icon-caret-right text-muted'></i> <?php echo $productName;?></a>
          </td>
          <td colspan='8' class='text-left'>
            <div class='small with-padding'>
              <span class='text-muted'><?php echo $lang->testtask->allTasks;?></span> <?php echo count($productTasks);?>
            </div>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php if($canTestReport):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
      <?php
      $actionLink = $this->createLink('testreport', 'browse', "objectID=$projectID&objctType=project");
      $misc       = common::hasPriv('testreport', 'browse') ? "onclick=\"setFormAction('$actionLink', '', '#testtaskForm')\"" : "disabled='disabled'";
      echo html::commonButton($lang->testreport->common, $misc);
      ?>
      </div>
      <?php endif;?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
