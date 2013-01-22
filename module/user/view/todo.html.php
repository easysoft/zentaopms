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
<?php include "../../common/view/datepicker.html.php"; ?>
<?php include './featurebar.html.php';?>
<?php $onlybody = $config->requestType == 'GET' ? '&onlybody=yes' : '?onlybody=yes';?>
<script language='Javascript'>var account='<?php echo $account;?>'</script>
<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $lang->user->todo; ?></div>
      <div class='box-content'>
        <?php 
        foreach($lang->todo->periods as $period => $label)
        {
            $vars = "account={$app->user->account}&date=$period";
            if($period == 'before') $vars .= "&status=undone";
            echo "<span id='$period'>" . html::a(inlink('todo', $vars), $label) . '</span><br />';
        }
        ?>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <form method='post' target='hiddenwin' action='<?php echo $this->createLink('todo', 'import2Today');?>' id='todoform'>
        <table class='table-1 tablesorter'>
          <?php $vars = "type=$type&account=$account&status=$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
          <thead>
          <tr class='colhead'>
            <th class='w-id'>  <?php common::printOrderLink('id',    $orderBy, $vars, $lang->idAB);?></th>
            <th class='w-date'><?php common::printOrderLink('date',  $orderBy, $vars, $lang->todo->date);?></th>
            <th class='w-type'><?php common::printOrderLink('type',  $orderBy, $vars, $lang->todo->type);?></th>
            <th class='w-pri'> <?php common::printOrderLink('pri',   $orderBy, $vars, $lang->priAB);?></th>
            <th>               <?php common::printOrderLink('name',  $orderBy, $vars, $lang->todo->name);?></th>
            <th class='w-hour'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->todo->beginAB);?></th>
            <th class='w-hour'><?php common::printOrderLink('end',   $orderBy, $vars, $lang->todo->endAB);?></th>
            <th class='w-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->todo->status);?></th>
          </tr>
          </thead>
          <tbody>
          <?php foreach($todos as $todo):?>
          <tr class='a-center'>
            <td><?php echo $todo->id;?></td>
            <td><?php echo $todo->date == '2030-01-01' ? $lang->todo->periods['future'] : $todo->date;?></td>
            <td><?php echo $lang->todo->typeList[$todo->type];?></td>
            <td><span class='<?php echo 'pri' . $todo->pri;?>'><?php echo $todo->pri?></span></td>
            <td class='a-left'><?php echo html::a($this->createLink('todo', 'view', "id=$todo->id") . $onlybody, $todo->name, '', "class='colorbox'");?></td>
            <td><?php echo $todo->begin;?></td>
            <td><?php echo $todo->end;?></td>
            <td class='<?php echo $todo->status;?>'><?php echo $lang->todo->statusList[$todo->status];?></td>
          </tr>
          <?php endforeach;?>
          </tbody>
          <tfoot><tr><td colspan='8'><?php $pager->show();?></td></tr></tfoot>
        </table>
      </form>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
