<table class='table-1 tablesorter colored' id='todoList'>
  <?php $vars = "type=$type&account=$account&status=$status&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
  <thead>
  <tr class='colhead'>
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
  <tr class='a-center'>
    <td class='a-left'>
      <?php if(common::hasPriv('todo', 'batchEdit') or (common::hasPriv('todo', 'import2Today') and $importFuture)):?>
      <input type='checkbox' name='todoIDList[<?php echo $todo->id;?>]' value='<?php echo $todo->id;?>' />         
      <?php endif;?>
      <?php echo $todo->id; ?>
    </td>
    <td><?php echo $todo->date == '2030-01-01' ? $lang->todo->periods['future'] : $todo->date;?></td>
    <td><?php echo $lang->todo->typeList[$todo->type];?></td>
    <td><span class='<?php echo 'pri' . $todo->pri;?>'><?php echo $todo->pri?></span></td>
    <td class='a-left'><?php echo html::a($this->createLink('todo', 'view', "id=$todo->id&from=my", '', true), $todo->name, '', "class='colorbox'");?></td>
    <td><?php echo $todo->begin;?></td>
    <td><?php echo $todo->end;?></td>
    <td class='<?php echo $todo->status;?>'><?php echo $lang->todo->statusList[$todo->status];?></td>
    <td class='a-right'>
      <?php 
      common::printIcon('todo', 'finish', "id=$todo->id", $todo, 'list', '', 'hiddenwin');
      common::printIcon('todo', 'edit',   "id=$todo->id", '', 'list', '', '', 'iframe', true);
      echo html::a("javascript:deleteTodo($todo->id)", '&nbsp;', '', "class='icon-green-common-delete' title='{$lang->todo->delete}'");
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <?php if(count($todos)):?>
  <tfoot>
    <tr>
      <td colspan='9' align='left'>
      <div class='f-left'>
      <?php 
      if(common::hasPriv('todo', 'batchEdit') or (common::hasPriv('todo', 'import2Today') and $importFuture))
      {
          echo html::selectAll() . html::selectReverse();
      }
      if(common::hasPriv('todo', 'batchEdit'))
      {
          $actionLink = $this->createLink('todo', 'batchEdit', "from=myTodo&type=$type&account=$account&status=$status");
          echo html::commonButton($lang->edit, "onclick=\"changeAction('todoform', 'batchEdit', '$actionLink')\"");

      }
      if(common::hasPriv('todo', 'batchFinish'))
      {
          $actionLink = $this->createLink('todo', 'batchFinish');
          echo html::commonButton($lang->todo->finish, "onclick=\"changeAction('todoform', 'batchFinish', '$actionLink')\"");

      }
      if(common::hasPriv('todo', 'import2Today') and $importFuture)
      {
          $actionLink = $this->createLink('todo', 'import2Today');
          echo html::commonButton($lang->todo->import, "onclick=\"changeAction('todoform', 'import2Today', '$actionLink')\"");
          echo html::input('date', date('Y-m-d'), "class='date w-80px'");
      }
      ?>
      </div>
      <?php $pager->show();?>
      </td>
    </tr>
  </tfoot>
  <?php endif;?>
</table>
