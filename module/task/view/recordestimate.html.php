<?php
/**
 * The record file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     task
 * @version     $Id: record.html.php 935 2013-01-08 07:49:24Z wwccss@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('confirmFinish', $lang->task->confirmFinish);?>
<form method='post' target='hiddenwin'>
  <table class='table-1'>
    <caption><?php echo $task->name;?></caption>
    <tr>
      <th class="w-id"><?php echo $lang->idAB;?></th>
      <th class="w-100px"><?php echo $lang->task->date;?></th>
      <th class="w-60px"><?php echo $lang->task->consumedThisTime;?></th>
      <th class="w-60px"><?php echo $lang->task->leftThisTime;?></th>
      <th><?php echo $lang->comment;?></th>
      <th class="w-60px"><?php echo $lang->actions;?></th>
    </tr>
    <?php foreach($estimates as $estimate):?>
    <tr class="a-center">
      <td><?php echo $estimate->id;?></td>
      <td><?php echo $estimate->date;?></td>
      <td><?php echo $estimate->consumed;?></td>
      <td><?php echo $estimate->left;?></td>
      <td class="a-left"><?php echo $estimate->work;?></td>
      <td align='center'>
        <?php
        if($task->status == 'wait' or $task->status == 'doing')
        {
            common::printIcon('task', 'editEstimate', "estimateID=$estimate->id", '', 'list', '', '', '', true);
            common::printIcon('task', 'deleteEstimate', "estimateID=$estimate->id", '', 'list', '', 'hiddenwin');
        }
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    <?php for($i = 1; $i <= 5; $i++):?>
    <tr class="a-center">
      <td><?php echo $i . html::hidden("id[$i]", $i);?></td>
      <td><?php echo html::input("dates[$i]", '', "class='text-6 date'");?></td>
      <td><?php echo html::input("consumed[$i]", '', "class='text-1'");?></td>
      <td><?php echo html::input("left[$i]", '', "class='text-1'");?></td>
      <td class="a-left"><?php echo html::textarea("work[$i]", '', "class='text-1' rows='1'");?></td>
      <td><?php echo '';?></td>
      <td align='center'></td>
    </tr>
    <?php endfor;?>
    <tr>
      <td colspan='6' class='a-center'><?php echo html::submitButton() . html::resetButton(); ?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
