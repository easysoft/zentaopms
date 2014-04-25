<?php
/**
 * The edit view of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class='container'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['release']);?> <strong><?php echo $release->id;?></strong></span>
      <strong><?php echo html::a(inlink('view', "release=$release->id"), $release->name);?></strong>
      <small class='text-muted'> <?php echo $lang->release->edit;?> <i class='icon icon-pencil'></i></small>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform' enctype='multipart/form-data'>
    <table class='table table-form'> 
      <tr>
        <th class='w-90px'><?php echo $lang->release->name;?></th>
        <td class='w-p25-f'><?php echo html::input('name', $release->name, "class='form-control'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->release->build;?></th>
        <td><?php echo html::select('build', $builds, $release->build, "class='form-control' onchange=loadStoriesAndBugs(this.value,$release->product)"); ?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->release->date;?></th>
        <td><?php echo html::input('date', $release->date, "class='form-control form-date'");?></td><td></td>
      </tr>  
      <tr id='linkStoriesAndBugs'>
        <th><?php echo $lang->release->linkStoriesAndBugs;?></th>
        <td colspan='2'>
          <div class='row pd-0' style='margin: 0 0 0 -15px'>
            <div class='col-md-6'>
              <div class='panel panel-sm'>
                <div class='panel-heading'>
                  <?php echo html::icon($lang->icons['story'], 'icon') . ' ' . $lang->release->linkStories;?>
                </div>
                <table class='mainTable table table-condensed table-hover table-borderless'>
                  <thead>
                    <tr>
                      <th class='w-id text-left'><?php echo html::selectAll('story', 'checkbox') . ' ' .  $lang->idAB;?></th>
                      <th><?php echo $lang->story->title;?></th>
                      <th class='w-hour'><?php echo $lang->statusAB;?></th>
                      <th class='w-100px'><?php echo $lang->story->stageAB;?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($stories as $key => $story):?>
                    <?php $storyLink = $this->createLink('story', 'view', "storyID=$story->id", '', true); ?>
                    <tr class='text-center'>
                      <td id='story' class='w-id text-left'><input type='checkbox' name='stories[]' value="<?php echo $story->id;?>" <?php if(strpos($release->stories, $story->id) !== false) echo 'checked';?>> <?php echo sprintf('%03d', $story->id);?></td>
                      <td class='text-left nobr'><?php echo html::a($storyLink, $story->title, '', "class='preview'");?></td>
                      <td class='story-<?php echo $story->status;?> w-50px'><?php echo $lang->story->statusList[$story->status];?></td>
                      <td class='w-80px'><?php echo $lang->story->stageList[$story->stage];?></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='panel panel-sm'>
                <div class='panel-heading'>
                  <?php echo html::icon($lang->icons['bug'], 'icon') . ' ' . $lang->release->linkBugs;?>
                </div>
                <table class='mainTable table table-condensed table-hover table-borderless'>
                  <thead>
                    <tr>
                      <th class='w-id text-left'><?php echo html::selectAll('bug', 'checkbox') . ' ' . $lang->idAB;?></th>
                      <th><?php echo $lang->bug->title;?></th>
                      <th class='w-100px'><?php echo $lang->bug->status;?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($bugs as $bug):?>
                    <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
                    <tr class='text-center'>
                      <td id='bug' class='w-id text-left'><input type='checkbox' name='bugs[]' value="<?php echo $bug->id;?>" <?php if(strpos($release->bugs, $bug->id) !== false) echo 'checked';?>> <?php echo sprintf('%03d', $bug->id);?></td>
                      <td class='text-left nobr'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
                      <td class='bug-<?php echo $bug->status;?> w-80px'><?php echo $lang->bug->statusList[$bug->status];?></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->release->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', $release->desc, "rows=10 class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->files;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildform', array('fileCount' => 1));?></td>
      </tr>  
      <tr>
        <td></td>
        <td colspan='2'><?php echo html::submitButton() . html::backButton() . html::hidden('product', $release->product);?></td>
      </tr>
    </table>
  </form>  
</div>
<?php include '../../common/view/footer.html.php';?>
