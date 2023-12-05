<?php
/**
 * The todo view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: todo.html.php 4735 2013-05-03 08:30:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('confirmDelete', $lang->todo->confirmDelete)?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->my->featureBar['todo'] as $period => $label):?>
    <?php
    $vars = "date=$period";
    if($period == 'before') $vars .= "&userID={$app->user->id}&status=undone";
    $label  = "<span class='text'>$label</span>";
    $active = '';
    if($period == $type)
    {
        $active = 'btn-active-text';
        $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    }
    echo html::a(inlink('todo', $vars), $label, '', "class='btn btn-link $active' id='{$period}'")
    ?>
    <?php endforeach;?>
    <div class="input-control has-icon-right space">
      <?php echo html::input('date', $date,"class='form-control form-date' onchange='changeDate(this.value)'");?>
      <label for="date" class="input-control-icon-right"><i class="icon icon-delay"></i></label>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if($config->edition != 'open' and !empty($app->user) and common::hasPriv('todo', 'calendar')):?>
    <div class="btn-group panel-actions">
      <?php echo html::a(helper::createLink('todo', 'calendar'), "<i class='icon-cards-view'></i> &nbsp;", '', "class='btn btn-icon' title='{$lang->todo->calendar}' id='switchButton'");?>
      <?php echo html::a(helper::createLink('my', 'todo', "type=all"), "<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon text-primary' title='{$lang->todo->list}' id='switchButton'");?>
    </div>
    <?php endif;?>
    <?php if(common::hasPriv('todo', 'export')) echo html::a(helper::createLink('todo', 'export', "userID={$user->id}&orderBy=$orderBy", 'html', true), "<i class='icon-export muted'> </i> " . $lang->todo->export, '', "class='btn btn-link export' data-width='600px'");?>
    <?php if(common::hasPriv('todo', 'create') or common::hasPriv('todo', 'batchCreate')):?>
    <div class='btn-group dropdown'>
    <?php common::printLink('todo', common::hasPriv('todo', 'create') ? 'create' : 'batchCreate', '', "<i class='icon icon-plus'></i> " . (common::hasPriv('todo', 'create') ? $lang->todo->create : $lang->todo->batchCreate), '', "id='create' class='btn btn-primary iframe' data-width='80%' data-app='my'", '', 'true');?>
    <?php if(common::hasPriv('todo', 'create') and common::hasPriv('todo', 'batchCreate')):?>
    <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
    <ul class='dropdown-menu pull-right'>
      <li><?php echo html::a($this->createLink('todo', 'create', '', '', true), $lang->todo->create, '', "class='iframe' data-width='80%'");?></li>
      <li><?php echo html::a($this->createLink('todo', 'batchCreate', '', '', true), $lang->todo->batchCreate, '', "class='iframe' data-width='80%'");?></li>
    </ul>
    <?php endif;?>
    </div>
    <?php endif;?>
  </div>
</div>
<?php
$waitCount  = 0;
$doingCount = 0;
?>
<div id="mainContent">
  <?php if(empty($todos)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->my->noTodo;?></span>
      <?php if(common::hasPriv('todo', 'create')):?>
      <?php echo html::a($this->createLink('todo', 'create'), "<i class='icon icon-plus'></i> " . $lang->todo->create, '', "class='btn btn-info' data-app='my'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class="main-table table-todo" method="post" id='todoForm'>
    <?php
    $canBatchEdit   = common::hasPriv('todo', 'batchEdit');
    $canBatchFinish = common::hasPriv('todo', 'batchFinish');
    $canBatchClose  = common::hasPriv('todo', 'batchClose');

    $canbatchAction = ($type != 'cycle' and ($canBatchEdit or $canBatchFinish or $canBatchClose or (common::hasPriv('todo', 'import2Today') and $importFuture)));
    ?>
    <table class="table has-sort-head" id='todoList'>
      <?php $vars = "type=$type&userID={$user->id}&status=$status&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
      <thead>
        <tr>
          <th class="c-id">
            <?php if($canbatchAction):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class="c-name">   <?php common::printOrderLink('name',       $orderBy, $vars, $lang->todo->name);?></th>
          <?php $style = $this->app->clientLang == 'en' ? "style='width:80px'" : '';?>
          <th class="c-pri" <?php echo $style;?> title=<?php echo $lang->todo->pri;?>> <?php common::printOrderLink('pri',    $orderBy, $vars, $lang->priAB);?></th>
          <th class="c-date text-center"><?php common::printOrderLink('date',       $orderBy, $vars, $lang->todo->date);?></th>
          <th class="c-status"> <?php common::printOrderLink('status',     $orderBy, $vars, $lang->todo->status);?></th>
          <th class="c-type"><?php common::printOrderLink('type',       $orderBy, $vars, $lang->todo->type);?></th>
          <?php if($type == 'assignedToOther'):?>
          <th class="c-user">   <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->todo->assignedTo);?></th>
          <?php else:?>
          <th class="c-user">   <?php common::printOrderLink('assignedBy', $orderBy, $vars, $lang->todo->assignedBy);?></th>
          <?php endif;?>
          <th class="c-begin">  <?php common::printOrderLink('begin',      $orderBy, $vars, $lang->todo->beginAB);?></th>
          <th class="c-end">    <?php common::printOrderLink('end',        $orderBy, $vars, $lang->todo->endAB);?></th>
          <th class="c-actions-5 text-center"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($todos as $todo):?>
        <?php if($todo->status == 'wait')  $waitCount ++;?>
        <?php if($todo->status == 'doing') $doingCount ++;?>
        <tr data-status='<?php echo $todo->status;?>'>
          <td class="c-id">
            <?php if($canbatchAction):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='todoIDList[<?php echo $todo->id;?>]' value='<?php echo $todo->id;?>' />
              <label></label>
            </div>
            <?php endif;?>
            <?php echo $todo->id?>
          </td>
          <td class="c-name" title="<?php echo $todo->name;?>"><?php echo html::a($this->createLink('todo', 'view', "id=$todo->id&from=my", '', true), $todo->name, '', "data-toggle='modal' data-width='80%' data-type='iframe' data-title='" . $lang->todo->view . "' data-icon='check'");?></td>
          <td class="c-pri"><span title="<?php echo zget($lang->todo->priList, $todo->pri);?>" class='label-pri <?php echo 'label-pri-' . $todo->pri;?>' title='<?php echo zget($lang->todo->priList, $todo->pri, $todo->pri);?>'><?php echo zget($lang->todo->priList, $todo->pri)?></span></td>
          <td class="c-date text-center"><?php echo $todo->date == '2030-01-01' ? $lang->todo->future : $todo->date;?></td>
          <td class="c-status"><span class="status-todo status-<?php echo $todo->status;?>"><?php echo $lang->todo->statusList[$todo->status];?></span></td>
          <td class="c-type"><?php echo zget($lang->todo->typeList, $todo->type, '');?></td>
          <?php if($type == 'assignedToOther'):?>
          <td><?php echo zget($users, $todo->assignedTo);?></td>
          <?php else:?>
          <td><?php echo zget($users, $todo->assignedBy);?></td>
          <?php endif;?>
          <td class="c-begin"><?php echo $todo->begin;?></td>
          <td class="c-end"><?php echo $todo->end;?></td>
          <td class="c-actions">
            <?php
            common::printIcon('todo', 'start',  "id=$todo->id", $todo, 'list', 'play', 'hiddenwin');
            if($todo->status == 'done' or $todo->status == 'closed')
            {
                common::printIcon('todo', 'activate', "id=$todo->id", $todo, 'list', 'magic', 'hiddenwin');
                if($todo->status == 'done')
                {
                    common::printIcon('todo', 'close', "id=$todo->id", $todo, 'list', 'off', 'hiddenwin');
                }
                else
                {
                    echo html::a('javascript:;', "<i class='icon-todo-close icon-off'></i>", '', "class='btn disabled'");
                }
            }
            else
            {
                common::printIcon('todo', 'assignTo', "todoID=$todo->id", $todo, 'list', 'hand-right', '', "iframe", false, "data-width='600'");
                common::printIcon('todo', 'finish', "id=$todo->id", $todo, 'list', 'checked', 'hiddenwin');
            }
            common::printIcon('todo', 'edit',   "id=$todo->id", '', 'list', 'edit', '', 'iframe', true);

            if(common::hasPriv('todo', 'delete'))
            {
                $deleteURL = $this->createLink('todo', 'delete', "todoID=$todo->id&confirm=yes");
                echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"todoList\", confirmDelete)", '<i class="icon-trash"></i>', '', "class='btn' title='{$lang->todo->delete}'");
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php if($canbatchAction):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
      <?php
      if($canBatchEdit)
      {
          $actionLink = $this->createLink('todo', 'batchEdit', "from=myTodo&type=$type&userID={$user->id}&status=$status");
          echo html::commonButton($lang->edit, "onclick=\"setFormAction('$actionLink')\"");
      }
      if($canBatchFinish)
      {
          $actionLink = $this->createLink('todo', 'batchFinish');
          echo html::commonButton($lang->todo->finish, "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"");
      }
      if($canBatchClose)
      {
          $actionLink = $this->createLink('todo', 'batchClose');
          echo html::commonButton($lang->todo->close, "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"");
      }
      if(common::hasPriv('todo', 'import2Today') and $importFuture)
      {
          $actionLink = $this->createLink('todo', 'import2Today');
          echo "<div class='input-control has-icon-right space'>";
          echo '<input type="text" name="date" id="importDate" value="' . date('Y-m-d') . '" class="form-control form-date">';
          echo '<label for="importDate" class="input-control-icon-right iconCenter"><i class="icon icon-delay"></i></label>';
          echo '</div>';
          echo html::commonButton($lang->todo->changeDate, "onclick=\"setFormAction('$actionLink')\"");
      }
      ?>
      </div>
      <div class="table-statistic"><?php echo sprintf($lang->todo->summary, count($todos), $waitCount, $doingCount);?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php js::set('listName', 'todoList');?>
<?php js::set('pageSummary', sprintf($lang->todo->summary, count($todos), $waitCount, $doingCount));?>
<?php js::set('checkedSummary', $lang->todo->checkedSummary);?>
<?php include '../../common/view/footer.html.php';?>
