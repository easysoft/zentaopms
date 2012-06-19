<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<script>
  var batchCreateNum = '<?php echo $config->task->batchCreate;?>'; 
  var noResultsMatch = '<?php echo $lang->noResultsMatch;?>';
</script>
<form method='post'>
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->task->project . $lang->colon . $lang->task->batchCreate;?></caption>
    <tr>
      <th class='w-20px'><?php echo $lang->idAB;?></th> 
      <th><?php echo $lang->task->story;?></th>
      <th class='w-300px'><?php echo $lang->task->name;?></th>
      <th class='w-200px'><?php echo $lang->task->desc;?></th>
      <th class='w-60px'><?php echo $lang->typeAB;?></th>
      <th class='w-100px'><?php echo $lang->task->assignedTo;?></th>
      <th class='w-50px'><?php echo $lang->task->pri;?></th>
      <th class='w-60px'><?php echo $lang->task->estimate;?></th>
    </tr>
    <?php for($i = 0; $i < $config->task->batchCreate; $i++):?>
    <?php $story = $i == 0 ? '' : 'same';?>
    <?php $lang->task->typeList['same'] = $lang->task->same; $type = $i == 0 ? '' : 'same';?>

    <?php $pri = 3;?>
    <tr class='a-center'>
      <td><?php echo $i+1;?></td>
      <td class='a-left' style='overflow:visible'><?php echo html::select("story[$i]", $stories, $story, "class='select-1'");?></td>
      <td><?php echo html::input("name[$i]", '', 'class=text-1'); echo "<span class='star'>*</span>";?></td>
      <td><?php echo html::textarea("desc[$i]", '', "class=text-1 rows='1'");?></td>
      <td><?php echo html::select("type[$i]", $lang->task->typeList, $type, 'class=select-1'); echo "<span class='star'>*</span>";?></td>
      <td><?php echo html::select("assignedTo[$i]", $members, '', 'class=select-1');?></td>
      <td><?php echo html::select("pri[$i]", (array)$lang->task->priList, $pri, 'class=select-1'); echo "<span class='star'>*</span>";?></td>
      <td><?php echo html::input("estimate[$i]", '', 'class=text-1'); echo "<span class='star'>*</span>";?></td>
    </tr>  
    <?php endfor;?>
    <tr>
      <td colspan='8'>
        <div class='half-left red'><?php echo $lang->task->notes;?></div>
        <div class='half-right'><?php echo html::submitButton() . html::resetButton();?></div>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
