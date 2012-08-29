<?php
/**
 * The todo view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include './featurebar.html.php';?>
<script language='Javascript'>var account='<?php echo $account;?>'</script>
<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo html::input('date', $date, "class='w-date date' onchange=changeDate(this.value)"); ?></div>
      <div class='box-content'>
        <?php 
        echo html::a(inLink('todo', "account=$account&type=today"),    $lang->todo->todayTodos)    . '<br />';
        echo html::a(inLink('todo', "account=$account&type=thisweek"), $lang->todo->thisWeekTodos) . '<br />';
        echo html::a(inLink('todo', "account=$account&type=lastweek"), $lang->todo->lastWeekTodos) . '<br />';
        echo html::a(inLink('todo', "account=$account&type=future"),   $lang->todo->futureTodos)   . '<br />';
        echo html::a(inLink('todo', "account=$account&type=all"),      $lang->todo->allDaysTodos)  . '<br />';
        echo html::a(inLink('todo', "account=$account&type=before"),   $lang->todo->allUndone)     . '<br />';
        ?>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <form method='post' target='hiddenwin' action='<?php echo $this->createLink('todo', 'import2Today');?>' id='todoform'>
        <table class='table-1 tablesorter'>
          <thead>
          <tr class='colhead'>
            <th class='w-id'><?php echo $lang->idAB;?></th>
            <th class='w-date'><?php echo $lang->todo->date;?></th>
            <th class='w-type'><?php echo $lang->todo->type;?></th>
            <th class='w-pri'><?php echo $lang->priAB;?></th>
            <th><?php echo $lang->todo->name;?></th>
            <th class='w-hour'><?php echo $lang->todo->beginAB;?></th>
            <th class='w-hour'><?php echo $lang->todo->endAB;?></th>
            <th class='w-status'><?php echo $lang->todo->status;?></th>
          </tr>
          </thead>

          <tbody>
          <?php foreach($todos as $todo):?>
          <tr class='a-center'>
            <td><?php echo $todo->id;?></td>
            <td><?php echo $todo->date == '2030-01-01' ? $lang->todo->dayInFuture : $todo->date;?></td>
            <td><?php echo $lang->todo->typeList->{$todo->type};?></td>
            <td><?php echo $todo->pri;?></td>
            <td class='a-left'><?php if(!common::printLink('todo', 'view', "todo=$todo->id", $todo->name)) echo $todo->name;?></td>
            <td><?php echo $todo->begin;?></td>
            <td><?php echo $todo->end;?></td>
            <td class='<?php echo $todo->status;?>'><?php echo $lang->todo->statusList[$todo->status];?></td>
          </tr>
          <?php endforeach;?>
          </tbody>
          <?php if($type == 'all'):?><tfoot><tr><td colspan='8'><?php $pager->show();?></td></tr></tfoot><?php endif;?>
        </table>
      </form>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
