<?php
/**
 * The view file of view method of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: view.html.php 4955 2013-07-02 01:47:21Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php if(!$todo->private or ($todo->private and $todo->account == $app->user->account)):?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <div class="page-title">
      <span class="label label-id"><?php echo $todo->id?></span>
      <span class='text' title='<?php echo $todo->name;?>'><?php echo $todo->name;?></span>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class='main-col col-8'>
    <div class='cell'>
      <div class='detail'>
        <div class='detail-title'>
          <?php
          echo $lang->todo->desc;
          if($todo->type == 'bug')   common::printLink('bug',   'view', "id={$todo->idvalue}", '  BUG#'   . $todo->idvalue);
          if($todo->type == 'task')  common::printLink('task',  'view', "id={$todo->idvalue}", '  TASK#'  . $todo->idvalue);
          if($todo->type == 'story') common::printLink('story', 'view', "id={$todo->idvalue}", '  STORY#' . $todo->idvalue);
          ?>
        </div>
        <div class='detail-content'><?php echo $todo->desc;?></div>
      </div>
      <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=todo&objectID=$todo->id");?>
      <?php include '../../common/view/action.html.php';?>
    </div>

    <div class='main-actions'>
      <div class="btn-toolbar">
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

        if($this->app->user->admin or ($this->app->user->account == $todo->account) or ($this->app->user->account == $todo->assignedTo))
        {
            if($todo->status == 'wait') common::printLink('todo', 'start', "todoID=$todo->id", "<i class='icon icon-play'></i>", 'hiddenwin', "title='{$lang->todo->start}' class='btn showinonlybody'");
            if($todo->status == 'done' || $todo->status == 'closed') common::printLink('todo', 'activate', "todoID=$todo->id", "<i class='icon icon-magic'></i>", 'hiddenwin', "title='{$lang->todo->activate}' class='btn showinonlybody'");
            if($todo->status == 'done') common::printLink('todo', 'close', "todoID=$todo->id", "<i class='icon icon-off'></i>", 'hiddenwin', "title='{$lang->todo->close}' class='btn showinonlybody'");
            common::printLink('todo', 'edit', "todoID=$todo->id", "<i class='icon icon-edit'></i>", '', "title='{$lang->todo->edit}' class='btn showinonlybody'");
            common::printLink('todo', 'delete', "todoID=$todo->id", "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->todo->delete}' class='btn showinonlybody'");

            if($todo->status != 'done' && $todo->status != 'closed')
            {
                echo "<div class='btn-group dropup'>";
                echo html::a($this->createLink('todo', 'finish', "id=$todo->id", 'html', true), "<i class='icon icon-checked'></i>", 'hiddenwin', "title='{$lang->todo->finish}' class='btn showinonlybody btn-success'");
                $createStoryPriv = common::hasPriv('story', 'create');
                $createTaskPriv  = common::hasPriv('task', 'create');
                $createBugPriv   = common::hasPriv('bug', 'create');
                if($createStoryPriv or $createTaskPriv or $createBugPriv)
                {
                    $isonlybody = isonlybody();
                    unset($_GET['onlybody']);
                    echo "<button type='button' class='btn btn-success dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
                    echo "<ul class='dropdown-menu pull-right' role='menu'>";
                    if($createStoryPriv) echo '<li>' . html::a('###', $lang->todo->reasonList['story'], '', "data-toggle='modal' data-target='#productModal' data-backdrop='false' data-moveable='true' data-position='center' id='toStoryLink'") . '</li>';
                    if($createTaskPriv)  echo '<li>' . html::a('###', $lang->todo->reasonList['task'], '', "data-toggle='modal' data-target='#projectModal' data-backdrop='false' data-moveable='true' data-position='center' id='toTaskLink'") . '</li>';
                    if($createBugPriv)   echo '<li>' . html::a('###', $lang->todo->reasonList['bug'], '', "data-toggle='modal' data-target='#productModal' data-backdrop='false' data-moveable='true' data-position='center' id='toBugLink'") . '</li>';
                    echo "</ul>";
                    if($isonlybody) $_GET['onlybody'] = 'yes';
                }
                echo "</div>";
            }
        }
        common::printRPN($browseLink);
        ?>
      </div>
    </div>
  </div>
  <div class='side-col col-4'>
    <div class='cell'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->todo->legendBasic;?></div>
        <div class='detail-content'>
          <table class='table table-data'>
            <tr>
              <th class='thWidth'><?php echo $lang->todo->pri;?></th>
              <td><span title="<?php echo zget($lang->todo->priList, $todo->pri);?>" class='label-pri <?php echo 'label-pri-' . $todo->pri;?>' title='<?php echo zget($lang->todo->priList, $todo->pri, $todo->pri);?>'><?php echo zget($lang->todo->priList, $todo->pri)?></span></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->status;?></th>
              <td><span class="status-todo status-<?php echo $todo->status;?>"><span class="label label-dot"></span> <?php echo $lang->todo->statusList[$todo->status];?></span></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->type;?></th>
              <td><?php echo $lang->todo->typeList[$todo->type];?></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->account;?></th>
              <td><?php echo zget($users, $todo->account);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->date;?></th>
              <td><?php echo $todo->date == '20300101' ? $lang->todo->periods['future'] : formatTime($todo->date, DT_DATE1);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->beginAndEnd;?></th>
              <td><?php if(isset($times[$todo->begin])) echo $times[$todo->begin]; if(isset($times[$todo->end])) echo ' ~ ' . $times[$todo->end];?></td>
            </tr>
            <?php if(isset($todo->assignedTo)):?>
            <tr>
              <th><?php echo $lang->todo->assignTo;?></th>
              <td><?php echo zget($users, $todo->assignedTo);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->assignedDate;?></th>
              <td><?php echo formatTime($todo->assignedDate, DT_DATE1);?></td>
            </tr>
            <?php endif;?>
          </table>
        </div>
      </div>
      <?php if($todo->cycle):?>
      <?php $todo->config = json_decode($todo->config);?>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->todo->cycle;?></div>
        <div class='detail-content'>
          <table class='table table-data'>
            <tr>
              <th class='thWidth'><?php echo $lang->todo->beginAndEnd?></th>
              <td><?php echo $todo->config->begin . " ~ " . $todo->config->end;?></td>
            </tr>
            <tr>
              <th class='thWidth text-top'><?php echo $lang->todo->cycleConfig?></th>
              <td>
                <?php
                if($todo->config->type == 'day')
                {
                    echo $lang->todo->every . $todo->config->day . $lang->day;
                }
                elseif($todo->config->type == 'week')
                {
                    foreach(explode(',', $todo->config->week) as $week) echo $lang->todo->dayNames[$week] . ' ';
                }
                elseif($todo->config->type == 'month')
                {
                    foreach(explode(',', $todo->config->month) as $month) echo $month . ' ';
                }
                echo '<br />';
                if($todo->config->beforeDays) printf($lang->todo->lblBeforeDays, $todo->config->beforeDays);
                ?>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<div id="mainActions" class='main-actions'>
  <div class="container"></div>
</div>
<div class="modal fade" id="projectModal">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->project->selectProject;?></h4>
      </div>
      <div class="modal-body">
        <?php if(empty($projects)):?>
        <div class="table-empty-tip">
          <p>
            <span class="text-muted"><?php echo $lang->project->noProject;?></span>
            <?php echo html::a("javascript:createProject()", "<i class='icon icon-plus'></i> " . $lang->project->create, '', "class='btn btn-info'");?>
          </p>
        </div>
        <?php else:?>
        <div class='input-group'>
          <?php echo html::select('project', $projects, '', "class='form-control chosen'");?>
          <span class='input-group-btn'><?php echo html::commonButton($lang->todo->reasonList['task'], "id='toTaskButton'", 'btn btn-primary');?></span>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="productModal">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->product->select;?></h4>
      </div>
      <div class="modal-body">
        <?php if(empty($products)):?>
        <div class="table-empty-tip">
          <p>
            <span class="text-muted"><?php echo $lang->product->noProduct;?></span>
            <?php echo html::a("javascript:createProduct()", "<i class='icon icon-plus'></i> " . $lang->product->create, '', "class='btn btn-info'");?>
          </p>
        </div>
        <?php else:?>
        <div class='input-group'>
          <?php echo html::select('product', $products, '', "class='form-control chosen'");?>
          <span class='input-group-btn'><?php echo html::commonButton($lang->todo->reasonList['story'], "id='toStoryButton'", 'btn btn-primary');?></span>
          <span class='input-group-btn'><?php echo html::commonButton($lang->todo->reasonList['bug'], "id='toBugButton'", 'btn btn-primary');?></span>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>
<?php js::set('todoID', $todo->id);?>
<?php else:?>
<?php echo $lang->todo->thisIsPrivate;?>
<?php endif;?>
<script>
$(function() {parent.$('body.hide-modal-close').removeClass('hide-modal-close'); })
</script>
<?php include '../../common/view/footer.html.php';?>
