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
<main id="main">
  <div class="container">
    <div id="mainMenu" class="clearfix">
      <div class="btn-toolbar pull-left">
      <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->testtask->browse;?></span></span>
      </div>
      <div class="btn-toolbar pull-right">
        <?php
        common::printIcon('testreport', 'browse', "objectID=$projectID&objectType=project", '', 'button','flag');
        common::printIcon('testtask', 'create', "product=0&project=$projectID");
        ?>
      </div>
    </div>
    <div id="mainContent">
      <form class="main-table table-testtask" data-ride="table" method="post" target='hiddenwin' id='testtaskForm'>
        <table class="table has-sort-head tablesorter" id='taskList'>
          <thead>
            <tr>
              <th class="w-100px">
                <?php if($tasks):?>
                <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                  <label></label>
                </div>
                <?php endif;?>
                <?php echo $lang->idAB;?>
              </th>
              <th class='w-150px'><?php echo $lang->testtask->product;?></th>
              <th><?php echo $lang->testtask->name;?></th>
              <th><?php echo $lang->testtask->build;?></th>
              <th class='w-user'><?php echo $lang->testtask->owner;?></th>
              <th class='w-100px'><?php echo $lang->testtask->begin;?></th>
              <th class='w-100px'><?php echo $lang->testtask->end;?></th>
              <th class='w-80px'><?php echo $lang->statusAB;?></th>
              <th class='w-200px {sorter:false}'><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($tasks as $task):?>
            <tr>
              <td class="c-id">
                <div class="checkbox-primary">
                  <input type='checkbox' name='taskIdList[]' value='<?php echo $task->id;?>' />
                  <label></label>
                  <?php printf('%03d', $task->id);?>
                </div>
              </td>
              <td title="<?php echo zget($products, $task->product, '')?>"><?php echo zget($products, $task->product, '');?></td>
              <td class='text-left' title="<?php echo $task->name?>"><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id"), $task->name);?></td>
              <td title="<?php echo $task->buildName?>"><?php echo ($task->build == 'trunk' || empty($task->buildName)) ? $lang->trunk : html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName);?></td>
              <td><?php echo $users[$task->owner];?></td>
              <td><?php echo $task->begin?></td>
              <td><?php echo $task->end?></td>
              <td class='status-<?php echo $task->status?>'><?php echo $lang->testtask->statusList[$task->status];?></td>
              <td class='c-actions'>
                <?php
                common::printIcon('testtask', 'cases',    "taskID=$task->id", $task, 'list', 'sitemap');
                common::printIcon('testtask', 'linkCase', "taskID=$task->id", $task, 'list', 'link');
                common::printIcon('testtask', 'edit',     "taskID=$task->id", $task, 'list');
                common::printIcon('testreport', 'browse', "objectID=$task->product&objectType=product&extra=$task->id", $task, 'list','flag');

                if(common::hasPriv('testtask', 'delete', $task))
                {
                    $deleteURL = $this->createLink('testtask', 'delete', "taskID=$task->id&confirm=yes");
                    echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"taskList\",confirmDelete)", '<i class="icon-trash"></i>', '', "class='btn' title='{$lang->testtask->delete}'");
                }
                ?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <?php if($tasks):?>
        <div class="table-footer">
          <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
          <div class="table-actions btn-toolbar">
          <?php
          $actionLink = $this->createLink('testreport', 'browse', "objectID=$projectID&objctType=project");
          $misc       = common::hasPriv('testreport', 'browse') ? "onclick=\"setFormAction('$actionLink', '', '#testtaskForm')\"" : "disabled='disabled'";
          echo html::commonButton($lang->testreport->common, $misc);
          ?>
          </div>
        </div>
        <?php endif;?>
      </form>
    </div>
  </div>
</main>
<?php include '../../common/view/footer.html.php';?>
