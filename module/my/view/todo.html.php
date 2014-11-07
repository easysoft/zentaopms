<?php
/**
 * The todo view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: todo.html.php 4735 2013-05-03 08:30:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('confirmDelete', $lang->todo->confirmDelete)?>
<form method='post' id='todoform'>
  <div id='featurebar'>
    <ul class='nav'>
      <?php 
      foreach($lang->todo->periods as $period => $label)
      {
          $vars = "date=$period";
          if($period == 'before') $vars .= "&account={$app->user->account}&status=undone";
          echo "<li id='$period'>" . html::a(inlink('todo', $vars), $label) . '</li>';
      }
      echo "<li id='byDate' class='datepicker-wrapper datepicker-date'>" . html::input('date', $date,"class='form-control form-date' onchange='changeDate(this.value)'") . '</li>';

      if(is_numeric($type)) 
      {
          if($date == date('Y-m-d'))
          {
              $type = 'today'; 
          }
          else if($date == date('Y-m-d', strtotime('-1 day')))
          {
              $type = 'yesterday'; 
          }
      }
      ?>
      <script>$('#<?php echo $type;?>').addClass('active')</script>
    </ul>  
    <div class='actions'>
      <?php echo html::a(helper::createLink('todo', 'export', "account=$account&orderBy=id_desc"), "<i class='icon-download-alt'></i> " . $lang->todo->export, '', "class='btn export'") ?>
      <?php echo html::a(helper::createLink('todo', 'batchCreate'), "<i class='icon-plus-sign'></i> " . $lang->todo->batchCreate, '', "class='btn'") ?>
      <?php echo html::a(helper::createLink('todo', 'create', "date=" . str_replace('-', '', $date)), "<i class='icon-plus'></i> " . $lang->todo->create, '', "class='btn'") ?>
    </div>
  </div>
  <table class='table table-condensed table-hover table-striped tablesorter table-fixed' id='todoList'>
    <?php $vars = "type=$type&account=$account&status=$status&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
    <thead>
      <tr class='text-center'>
        <th class='w-id'>    <?php common::printOrderLink('id',     $orderBy, $vars, $lang->idAB);?></th>
        <th class='w-date'>  <?php common::printOrderLink('date',   $orderBy, $vars, $lang->todo->date);?></th>
        <th class='w-type'>  <?php common::printOrderLink('type',   $orderBy, $vars, $lang->todo->type);?></th>
        <th class='w-pri'>   <?php common::printOrderLink('pri',    $orderBy, $vars, $lang->priAB);?></th>
        <th>                 <?php common::printOrderLink('name',   $orderBy, $vars, $lang->todo->name);?></th>
        <th class='w-hour'>  <?php common::printOrderLink('begin',  $orderBy, $vars, $lang->todo->beginAB);?></th>
        <th class='w-hour'>  <?php common::printOrderLink('end',    $orderBy, $vars, $lang->todo->endAB);?></th>
        <th class='w-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->todo->status);?></th>
        <th class='w-80px {sorter:false}'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($todos as $todo):?>
    <tr class='text-center'>
      <td class='text-left'>
        <?php if(common::hasPriv('todo', 'batchEdit') or (common::hasPriv('todo', 'import2Today') and $importFuture)):?>
        <input type='checkbox' name='todoIDList[<?php echo $todo->id;?>]' value='<?php echo $todo->id;?>' />
        <?php endif;?>
        <?php echo $todo->id; ?>
      </td>
      <td><?php echo $todo->date == '2030-01-01' ? $lang->todo->periods['future'] : $todo->date;?></td>
      <td><?php echo $lang->todo->typeList[$todo->type];?></td>
      <td><span class='<?php echo 'pri' . zget($lang->todo->priList, $todo->pri, $todo->pri);?>'><?php echo zget($lang->todo->priList, $todo->pri, $todo->pri)?></span></td>
      <td class='text-left'><?php echo html::a($this->createLink('todo', 'view', "id=$todo->id&from=my", '', true), $todo->name, '', "data-toggle='modal' data-type='iframe' data-title='" . $lang->todo->view . "' data-icon='check'");?></td>
      <td><?php echo $todo->begin;?></td>
      <td><?php echo $todo->end;?></td>
      <td class='<?php echo $todo->status;?>'><?php echo $lang->todo->statusList[$todo->status];?></td>
      <td class='text-right'>
        <?php 
        common::printIcon('todo', 'finish', "id=$todo->id", $todo, 'list', 'ok-sign', 'hiddenwin');
        common::printIcon('todo', 'edit',   "id=$todo->id", '', 'list', 'pencil', '', 'iframe', true);

        if(common::hasPriv('todo', 'delete'))
        {
            $deleteURL = $this->createLink('todo', 'delete', "todoID=$todo->id&confirm=yes");
            echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"todoList\",confirmDelete)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->todo->delete}'");
        }
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
    <?php if(count($todos)):?>
    <tfoot>
      <tr>
        <td colspan='9' align='left'>
          <?php $pager->show();?>
          <div class='table-actions clearfix'>
          <?php 
          if(common::hasPriv('todo', 'batchEdit') or (common::hasPriv('todo', 'import2Today') and $importFuture))
          {
            echo "<div class='btn-group'>" . html::selectButton() . '</div>';
          }
          echo "<div class='btn-group'>";
          if(common::hasPriv('todo', 'batchEdit'))
          {
              $actionLink = $this->createLink('todo', 'batchEdit', "from=myTodo&type=$type&account=$account&status=$status");
              echo html::commonButton($lang->edit, "onclick=\"setFormAction('$actionLink')\"");

          }
          if(common::hasPriv('todo', 'batchFinish'))
          {
              $actionLink = $this->createLink('todo', 'batchFinish');
              echo html::commonButton($lang->todo->finish, "onclick=\"setFormAction('$actionLink','hiddenwin')\"");

          }
          echo '</div>';
          if(common::hasPriv('todo', 'import2Today') and $importFuture)
          {
              $actionLink = $this->createLink('todo', 'import2Today');
              echo "<div class='pull-left'><div class='input-group'>";
              echo "<div class='datepicker-wrapper datepicker-date'>" . html::input('date', date('Y-m-d'), "class='form-control form-date'") . '</div>';
              echo "<span class='input-group-btn'>";
              echo html::commonButton($lang->todo->import, "onclick=\"setFormAction('$actionLink')\"");
              echo '</span>';
              echo '</div></div>';
          }
          ?>
          </div>
        </td>
      </tr>
    </tfoot>
    <?php endif;?>
  </table>
</form>
<?php js::set('listName', 'todoList')?>
<?php include '../../common/view/footer.html.php';?>
