<th><?php echo $lang->release->linkStoriesAndBugs;?></th>
<td colspan='2'>
  <div class='row pd-0' style='margin: 0 0 0 -15px'>
    <div class='col-md-6'>
      <div class='panel panel-sm contentDiv'>
        <div class='panel-heading'><?php echo html::icon($lang->icons['story']) . ' ' . $lang->release->linkStories;?></div>
        <table class='table table table-borderless table-condensed table-hover'>
          <thead>
            <th class='w-id text-left'><?php echo html::selectAll('story', 'checkbox') . $lang->idAB;?></th>
            <th><?php echo $lang->story->title;?></th>
            <th class='w-hour'><?php echo $lang->statusAB;?></th>
            <th class='w-100px'><?php echo $lang->story->stageAB;?></th>
          </thead>
          <?php foreach($stories as $storyID => $story):?>
          <?php $storyLink = $this->createLink('story', 'view', "storyID=$storyID", '', true);?>
          <tr class='text-center'>
            <td id='story' class='w-id text-left'>
              <input type='checkbox' name='stories[]' value="<?php echo $storyID;?>" <?php if($story->stage == 'developed' or $story->status == 'closed') echo 'checked';?>> <?php echo sprintf('%03d', $story->id);?>
            </td>
            <td id='preview<?php echo $story->id;?>' class='text-left nobr'><?php echo html::a($storyLink, $story->title, '', "class='preview'");?></td>
            <td class='<?php echo $story->status;?> w-50px'><?php echo $lang->story->statusList[$story->status];?></td>
            <td class='w-80px'><?php echo $lang->story->stageList[$story->stage];?></td>
          </tr>
          <?php endforeach;?>
        </table>
      </div>
    </div>
    <div class='col-md-6'>
      <div class='panel panel-sm contentDiv'>
        <div class='panel-heading'><?php echo html::icon($lang->icons['bug']) . ' ' . $lang->release->linkBugs;?></div>
        <table class='table table table-borderless table-condensed table-hover'>
          <thead>
            <tr>
              <th class='w-id text-left'><?php echo html::selectAll('bug', 'checkbox') . $lang->idAB;?></th>
              <th><?php echo $lang->bug->title;?></th>
              <th class='w-100px'><?php echo $lang->bug->status;?></th>
            </tr>
          </thead>
          <?php foreach($bugs as $bug):?>
          <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
          <tr class='text-center'>
            <td class='w-id text-left' id='bug'>
              <input type='checkbox' name='bugs[]' value="<?php echo $bug->id;?>" <?php if($bug->status == 'closed' or $bug->status == 'resolved') echo "checked"; ?>> <?php echo sprintf('%03d', $bug->id);?>
            </td>
            <td class='text-left nobr'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
            <td class='w-80px'><?php echo $lang->bug->statusList[$bug->status];?></td>
          </tr>
          <?php endforeach;?>
        </table>
      </div>
    </div>
  </div>
</td>
