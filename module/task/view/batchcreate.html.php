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
<?php js::set('batchCreateNum', $config->task->batchCreate);?>
<form method='post'>
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->task->project . $lang->colon . $lang->task->batchCreate;?></caption>
    <tr>
      <th class='w-20px'><?php echo $lang->idAB;?></th> 
      <th><?php echo $lang->task->story;?></th>
      <th class='red'><?php echo $lang->task->name;?></th>
      <th class='w-60px red'><?php echo $lang->typeAB;?></th>
      <th class='w-80px'><?php echo $lang->task->assignedTo;?></th>
      <th class='w-50px'><?php echo $lang->task->estimateAB;?></th>
      <th class='w-200px'><?php echo $lang->task->desc;?></th>
      <th class='w-50px'><?php echo $lang->task->pri;?></th>
    </tr>
    <?php for($i = 0; $i < $config->task->batchCreate; $i++):?>
    <?php $story = ($i == 0 and $storyID != 0) ? $storyID : 'ditto';?>
    <?php
    $lang->task->typeList['ditto'] = $lang->task->ditto; $type = $i == 0 ? '' : 'ditto';
    $members['ditto'] = $lang->task->ditto; $member = $i == 0 ? '' : 'ditto';
    ?>

    <?php $pri = 3;?>
    <tr class='a-center'>
      <td><?php echo $i+1;?></td>
      <td class='a-left' style='overflow:visible'><?php echo html::select("story[$i]", $stories, $story, "class='select-1'");?></td>
      <td><?php echo html::input("name[$i]", '', 'class=text-1');?></td>
      <td><?php echo html::select("type[$i]", $lang->task->typeList, $type, 'class=select-1');?></td>
      <td><?php echo html::select("assignedTo[$i]", $members, $member, 'class=select-1');?></td>
      <td><?php echo html::input("estimate[$i]", '', 'class=text-1');?></td>
      <td><?php echo html::textarea("desc[$i]", '', "class=text-1 rows='1'");?></td>
      <td><?php echo html::select("pri[$i]", (array)$lang->task->priList, $pri, 'class=select-1');?></td>
    </tr>  
    <?php endfor;?>
    <tr><td colspan='8' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
