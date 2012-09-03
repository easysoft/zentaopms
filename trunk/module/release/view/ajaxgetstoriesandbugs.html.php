<th class='rowhead'><?php echo $lang->release->linkStoriesAndBugs;?></th>
<td>
  <div class="w-p90">

    <div class='half-left'>
      <table class='mainTable'>
        <tr style='border-bottom:none'>
          <td style='border-bottom:none; padding:0px'>
            <table class='headTable'>
              <caption><?php echo $lang->release->linkStories;?></caption>
              <tr>
                <th class='w-id a-left'><?php echo html::selectAll('story', 'checkbox') . $lang->idAB;?></th>
                <th><?php echo $lang->story->title;?></th>
                <th class='w-hour'><?php echo $lang->statusAB;?></th>
                <th class='w-100px'><?php echo $lang->story->stageAB;?></th>
              </tr>
            </table>
          </td>
        </tr>
        <tr style='border-bottom:none'>
          <td style='border-bottom:none; padding:0px'>
            <div class='contentDiv'>
              <table class='f-left table-1 fixed'>
                <?php foreach($stories as $storyID => $story):?>
                <?php $storyLink = $this->createLink('story', 'view', "storyID=$storyID");?>
                <tr class='a-center'>
                  <td id='story' class='w-id a-left'>
                    <input type='checkbox' name='stories[]' value="<?php echo $storyID;?>" <?php if($story->stage == 'developed' or $story->status == 'closed') echo 'checked';?>> <?php echo sprintf('%03d', $story->id);?>
                  </td>
                  <td id='preview<?php echo $story->id;?>' class='a-left nobr'><?php echo html::a($storyLink, $story->title, '', "class='preview'");?></td>
                  <td class='<?php echo $story->status;?> w-50px'><?php echo $lang->story->statusList[$story->status];?></td>
                  <td class='w-80px'><?php echo $lang->story->stageList[$story->stage];?></td>
                </tr>
                <?php endforeach;?>
              </table>
            </div>
          </td>
        </tr>
      </table>
    </div>

    <div class='half-right'>
      <table class='mainTable'>
        <tr style='border-bottom:none'>
          <td style='border-bottom:none; padding:0px'>
            <table class='headTable'>
              <caption><?php echo $lang->release->linkBugs;?></caption>
              <tr>
                <th class='w-id a-left'><?php echo html::selectAll('bug', 'checkbox') . $lang->idAB;?></th>
                <th><?php echo $lang->bug->title;?></th>
                <th class='w-100px'><?php echo $lang->bug->status;?></th>
              </tr>
            </table>
          </td>
        </tr>
        <tr style='border-bottom:none'>
          <td style='border-bottom:none; padding:0px'>
            <div class='contentDiv'>
              <table class='f-left table-1 fixed'>
                <?php foreach($bugs as $bug):?>
                <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id");?>
                <tr class='a-center'>
                  <td class='w-id a-left' id='bug'>
                    <input type='checkbox' name='bugs[]' value="<?php echo $bug->id;?>" <?php if($bug->status == 'closed' or $bug->status == 'resolved') echo "checked"; ?>> <?php echo sprintf('%03d', $bug->id);?>
                  </td>
                  <td class='a-left nobr'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
                  <td class='w-80px'><?php echo $lang->bug->statusList[$bug->status];?></td>
                </tr>
                <?php endforeach;?>
              </table>
            </div>
          </td>
        </tr>
      </table>
    </div>

  </div>
</td>
