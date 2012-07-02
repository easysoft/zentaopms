<?php
/**
 * The edit view of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<form method='post' target='hiddenwin' id='dataform'>
  <table class='table-1'> 
    <caption><?php echo $lang->release->edit;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->release->name;?></th>
      <td><?php echo html::input('name', $release->name, "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->release->build;?></th>
      <td><?php echo html::select('build', $builds, $release->build, "class='select-3' onchange=loadStoriesAndBugs(this.value,$release->product)"); ?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->release->date;?></th>
      <td><?php echo html::input('date', $release->date, "class='text-3 date'");?></td>
    </tr>  
    <tr id='linkStoriesAndBugs'>
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
                      <?php foreach($stories as $key => $story):?>
                      <?php
                      $storyLink = $this->createLink('story', 'view', "storyID=$story->id");
                      ?>
                      <tr class='a-center'>
                        <td id='story' class='w-id a-left'><input type='checkbox' name='stories[]' value="<?php echo $story->id;?>" <?php if(strpos($release->stories, $story->id) !== false) echo 'checked';?>> <?php echo sprintf('%03d', $story->id);?></td>
                        <td class='a-left nobr'><?php echo html::a($storyLink, $story->title, '', "class='preview'");?></td>
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
                        <td id='bug' class='w-id a-left'><input type='checkbox' name='bugs[]' value="<?php echo $bug->id;?>" <?php if(strpos($release->bugs, $bug->id) !== false) echo 'checked';?>> <?php echo sprintf('%03d', $bug->id);?></td>
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
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->release->desc;?></th>
      <td><?php echo html::textarea('desc', htmlspecialchars($release->desc), "rows='20' class='area-1'");?></td>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton() . html::hidden('product', $release->product);?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
