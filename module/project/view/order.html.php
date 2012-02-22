<?php
/**
 * The order view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin'>
<table align='center' class='table-5'>
  <caption><?php echo $lang->project->statusUndone?></caption>
  <tr>
    <th class='w-80px'><?php echo $lang->project->id?></th>
    <th><?php echo $lang->project->name?></th>
    <th class='w-80px'><?php echo $lang->project->status?></th>
    <th class='w-80px'><?php echo $lang->project->order?></th>
  </tr>
  <?php foreach($projects as $project):?>
  <?php if($project->status == 'done') continue;?>
  <tr class='a-center'>
    <td><?php echo $project->id?></td>
    <td class='a-left'><?php echo $project->name?></td>
    <td><?php echo $lang->project->statusList[$project->status]?></td>
    <td><?php echo html::input($project->id, $project->order, "size='5'")?></td>
  </tr>
  <?php endforeach;?>
  <tr><td colspan='4' align='center'><?php echo html::submitButton() . html::resetButton()?></td></tr>
</table>
</form>
<form method='post' target='hiddenwin'>
<table align='center' class='table-5'>
  <caption><?php echo $lang->project->statusDone?></caption>
  <tr>
    <th class='w-80px'><?php echo $lang->project->id?></th>
    <th><?php echo $lang->project->name?></th>
    <th class='w-80px'><?php echo $lang->project->order?></th>
  </tr>
  <?php foreach($projects as $project):?>
  <?php if($project->status != 'done') continue;?>
  <tr class='a-center'>
    <td><?php echo $project->id?></td>
    <td class='a-left'><?php echo $project->name?></td>
    <td><?php echo html::input($project->id, $project->order, "size='5'")?></td>
  </tr>
  <?php endforeach;?>
  <tr><td colspan='3' align='center'><?php echo html::submitButton() . html::resetButton()?></td></tr>
</table>
</form>
<?php include '../../common/view/footer.html.php';?>

