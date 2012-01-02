<?php $stories = $stories;?>
<th class='rowhead'><?php echo $lang->release->linkStoriesAndBugs;?></th>
<td>
  <div class="w-p90">
    <div class='half-left' style="height:225px; overflow-y:auto">
      <table class='table-1'>
        <caption>story</caption>
        <tr class='colhead'>
        <?php $vars = "productID=$productID&orderBy=%s"; ?>
          <th class='w-id {sorter:false}'>    <?php common::printOrderLink('id',     "$orderBy", $vars, $lang->idAB);?></th>
          <th class= '{sorter:false}'>        <?php common::printOrderLink('title',  "$orderBy", $vars, $lang->story->title);?></th>
          <th class='w-100px {sorter:false}'> <?php common::printOrderLink('stage',  "$orderBy", $vars, $lang->story->stageAB);?></th>
        </tr>
        <?php foreach($stories as $storyID => $story):?>
        <?php $storyLink = $this->createLink('story', 'view', "storyID=$storyID");?>
        <tr class='a-center'>
          <td><input type='checkbox' name='stories[]' value="<?php echo $storyID;?>" <?php if($story->stage == 'developed') echo 'checked';?> <?php echo sprintf('%03d', $story->id);?></td>
          <td class='a-left nobr'><?php echo html::a($storyLink,$story->title);?></td>
          <td><?php echo $lang->story->stageList[$story->stage];?></td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
    <div class='half-right' style="height:225px; overflow-y:auto">
      <table class='table-1'>
        <caption>bug</caption>
        <tr class='colhead'>
          <th class='w-id'>       <?php echo $lang->idAB;?></th>
          <th><?php echo $lang->bug->title;?></th>
        </tr>
        <?php foreach($bugs[$key] as $bug):?>
        <tr class='a-center'>
          <td><input type='checkbox' name='bugs[]' value="<?php echo $bug->id;?>" checked> <?php echo sprintf('%03d', $bug->id);?></td>
          <td class='a-left nobr'><?php common::printLink('bug', 'view', "bugID=$bug->id", $bug->title, '', "class='preview'");?></td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
  </div>
</td>
