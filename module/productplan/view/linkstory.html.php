<?php
/**
 * The link story view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<form method='post'>
  <table class='table-1 tablesorter a-center'> 
    <caption class='caption-tl'><?php echo $plan->title .$lang->colon . $lang->productplan->unlinkedStories;?></caption>
    <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th class='w-pri'><?php echo $lang->priAB;?></th>
      <th><?php echo $lang->story->plan;?></th>
      <th><?php echo $lang->story->title;?></th>
      <th><?php echo $lang->openedByAB;?></th>
      <th><?php echo $lang->assignedToAB;?></th>
      <th><?php echo $lang->story->estimateAB;?></th>
      <th><?php echo $lang->statusAB;?></th>
      <th class='w-40px {sorter: false}'><?php echo $lang->link;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($allStories as $story):?>
    <?php
    if(isset($planStories[$story->id])) continue;
    if($story->plan and helper::diffDate($plans[$story->plan], helper::today()) > 0) continue;
    ?>
    <tr>
      <td><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->id);?></td>
      <td><?php echo $story->pri;?></td>
      <td><?php echo $story->planTitle;?></td>
      <td class='a-left nobr'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?></td>
      <td><?php echo $users[$story->openedBy];?></td>
      <td><?php echo $users[$story->assignedTo];?></td>
      <td><?php echo $story->estimate;?></td>
      <td><?php echo $lang->story->statusList[$story->status];?></td>
      <td><input type='checkbox' name='stories[]'  value='<?php echo $story->id;?>' /></td>
    </tr>
    <?php endforeach;?>
    </tbody>
    <tfoot>
    <tr>
      <td colspan='9'><?php echo html::submitButton($lang->story->linkStory);?></td>
    </tr>
    </tfoot>
  </table>
</form>

<table class='table-1 tablesorter a-center'> 
  <caption class='caption-tl'><?php echo $plan->title .$lang->colon . $lang->productplan->linkedStories;?></caption>
  <thead>
  <tr class='colhead'>
    <th class='w-id'><?php echo $lang->idAB;?></th>
    <th class='w-pri'><?php echo $lang->priAB;?></th>
    <th><?php echo $lang->story->title;?></th>
    <th><?php echo $lang->openedByAB;?></th>
    <th><?php echo $lang->assignedToAB;?></th>
    <th><?php echo $lang->story->estimateAB;?></th>
    <th><?php echo $lang->statusAB;?></th>
    <th><?php echo $lang->story->stageAB;?></th>
    <th class='w-p10 {sorter:false}'><?php echo $lang->actions?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($planStories as $story):?>
  <tr>
    <td><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->id);?></td>
    <td><?php echo $story->pri;?></td>
    <td class='a-left nobr'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?></td>
    <td><?php echo $users[$story->openedBy];?></td>
    <td><?php echo $users[$story->assignedTo];?></td>
    <td><?php echo $story->estimate;?></td>
    <td><?php echo $lang->story->statusList[$story->status];?></td>
    <td><?php echo $lang->story->stageList[$story->stage];?></td>
    <td><?php common::printLink('productplan', 'unlinkStory', "story=$story->id", $lang->productplan->unlinkStory, 'hiddenwin');?></td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
<?php include '../../common/view/footer.html.php';?>
