<?php
/**
 * The todo view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<form method='post' id='todoform'>
  <div id='featurebar'>
    <div class='f-left'>
      <?php 
      foreach($lang->todo->periods as $period => $label)
      {
          $vars = "date=$period";
          if($period == 'before') $vars .= "&account={$app->user->account}&status=undone";
          echo "<span id='$period'>" . html::a(inlink('todo', $vars), $label) . '</span>';
      }
      echo "<span id='byDate'>" . html::input('date', $date,"class='w-date date' onchange='changeDate(this.value)'") . '</span>';

      if($type == 'bydate') 
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
    </div>
    <div class='f-right'>
      <?php 
      common::printIcon('todo', 'export', "account=$account&orderBy=id_desc");
      common::printIcon('todo', 'batchCreate');
      common::printIcon('todo', 'create', "date=" . str_replace('-', '', $date));
      ?>
    </div>
  </div>
  <table class='table-1 tablesorter colored'>
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
        common::printIcon('todo', 'edit',   "id=$todo->id", '', 'list');
        common::printIcon('todo', 'delete', "id=$todo->id", '', 'list', '', 'hiddenwin');
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
    <?php if(count($todos)):?>
    <tfoot>
      <tr>
        <td colspan='9'>
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
            echo html::commonButton($lang->todo->import2Today, "onclick=\"changeAction('todoform', 'import2Today', '$actionLink')\"");
        }
        ?>
        </div>
        <?php $pager->show();?>
        </td>
      </tr>
    </tfoot>
    <?php endif;?>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
