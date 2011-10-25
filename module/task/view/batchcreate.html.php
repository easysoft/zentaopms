<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' enctype='multipart/form-data'>
  <table align='center' class='table-1'> 
    <caption><?php echo $lang->task->project . $lang->colon . $lang->task->batchCreate;?></caption>
    <tr>
      <th class='w-id'><?php echo $lang->idAB;?></th> 
      <th><?php echo $lang->task->story;?></th>
      <th><?php echo $lang->task->type;?></th>
      <th><?php echo $lang->task->name;?></th>
      <th><?php echo $lang->task->desc;?></th>
      <th><?php echo $lang->task->assignedTo;?></th>
      <th><?php echo $lang->task->pri;?></th>
      <th><?php echo $lang->task->estimateBatch;?></th>
    </tr>
    <?php for($i = 0; $i < $config->task->batchCreate; $i++):?>
    <?php $story = $i == 0 ? '' : 'same';?>
    <?php $lang->task->typeList['same'] = $lang->task->same; $type = $i == 0 ? '' : 'same';?>

    <?php $pri = 3;?>
    <tr class='a-center'>
      <td><?php echo $i+1;?></td>
      <td><?php echo html::select("story[$i]", $stories, $story, 'class=select-2');?></td>
      <td><?php echo html::select("type[$i]", $lang->task->typeList, $type, "class=select-2"); echo "<span class='star'>*</span>";?></td>
      <td><?php echo html::input("name[$i]", '', "class='text-1'"); echo "<span class='star'>*</span>";?></td>
      <td><?php echo html::textarea("desc[$i]", '', "rows='1' class='text-1'");?></td>
      <td><?php echo html::select("assignedTo[$i]", $members, '', "class=select-2");?></td>
      <td><?php echo html::select("pri[$i]", (array)$lang->task->priList, $pri, 'class=w-50px'); echo "<span class='star'>*</span>";?></td>
      <td><?php echo html::input("estimate[$i]", '', "class='w-50px'"); echo "<span class='star'>*</span>";?></td>
    </tr>  
    <?php endfor;?>
    <tr>
      <td colspan='4' class='a-center'><?php echo "<span class='star'><small>{$lang->task->notes}</small></span>";?></td>
      <td colspan='4' class='a-left'><?php echo html::submitButton() . html::resetButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
