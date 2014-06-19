<?php
/**
 * The edit view of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
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
      <span class='prefix'><?php echo html::icon($lang->icons['build']);?> <strong><?php echo $build->id;?></strong></span>
      <strong><?php echo html::a($this->createLink('build', 'view', 'build=' . $build->id), $build->name, '_blank');?></strong>
      <small class='text-muted'> <?php echo $lang->build->edit;?> <?php echo html::icon($lang->icons['edit']);?></small>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform' enctype='multipart/form-data'>
    <table class='table table-form'> 
      <tr>
        <th class='w-110px'><?php echo $lang->build->product;?></th>
        <td class='w-p25-f'><?php echo html::select('product', $products, $build->product, "class='form-control'");?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->name;?></th>
        <td><?php echo html::input('name', $build->name, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->builder;?></th>
        <td><?php echo html::select('builder', $users, $build->builder, 'class="form-control"');?></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->date;?></th>
        <td><?php echo html::input('date', $build->date, "class='form-control form-date'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->scmPath;?></th>
        <td colspan='2'><?php echo html::input('scmPath', $build->scmPath, "class='form-control' placeholder='{$lang->build->placeholder->scmPath}'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->filePath;?></th>
        <td colspan='2'><?php echo html::input('filePath', $build->filePath, "class='form-control' placeholder='{$lang->build->placeholder->filePath}'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->files;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildForm', array('fileCount' => 1));?></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->linkStoriesAndBugs;?></th>
        <td colspan='2'>
          <div class='row pd-0' style='margin: 0 0 0 -15px'>
            <div class='col-md-6'>
              <div class='panel panel-sm contentDiv'>
                <div class='panel-heading'><?php echo html::icon($lang->icons['story']) . ' ' . $lang->build->linkStories;?></div>
                <table class='table table table-borderless table-condensed table-hover'>
                  <thead>
                    <tr class='text-center'>
                      <th class='w-id text-left'><?php echo html::selectAll('story', 'checkbox') . ' ' . $lang->idAB;?></th>
                      <th><?php echo $lang->story->title;?></th>
                      <th class='w-hour'><?php echo $lang->statusAB;?></th>
                      <th class='w-100px'><?php echo $lang->story->stageAB;?></th>
                    </tr>
                  </thead>
                  <?php foreach($stories as $key => $story):?>
                  <?php $storyLink = $this->createLink('story', 'view', "storyID=$story->id", '', true);?>
                  <tr class='text-center'>
                    <td class='w-id text-left' id='story'><input type='checkbox' name='stories[]' value="<?php echo $story->id;?>" <?php if(strpos(',' . $build->stories . ',', ',' . $story->id . ',') !== false) echo 'checked';?>> <?php echo sprintf('%03d', $story->id);?></td>
                    <td class='text-left nobr'><?php echo html::a($storyLink, $story->title, '', "class='preview iframe'");?></td>
                    <td class='story-<?php echo $story->status;?> w-50px'><?php echo $lang->story->statusList[$story->status];?></td>
                    <td><?php echo $lang->story->stageList[$story->stage];?></td>
                  </tr>
                  <?php endforeach;?>
                </table>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='panel panel-sm contentDiv'>
                <div class='panel-heading'><?php echo html::icon($lang->icons['bug']) . ' ' . $lang->build->linkBugs;?></div>
                <table class='table table table-borderless table-condensed table-hover'>
                  <thead>
                    <tr class='text-center'>
                      <th class='w-id text-left'><?php echo html::selectAll('bug', 'checkbox') . ' ' . $lang->idAB;?></th>
                      <th><?php echo $lang->bug->title;?></th>
                      <th class='w-100px'><?php echo $lang->bug->status;?></th>
                      <th class='w-80px'><?php echo $lang->bug->resolvedBy;?></th>
                    </tr>
                  </thead>
                  <?php foreach($bugs as $bug):?>
                  <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
                  <tr class='text-center'>
                    <td class='w-id text-left' id='bug'><input type='checkbox' name='bugs[]' value="<?php echo $bug->id;?>" <?php if(strpos(',' . $build->bugs . ',', ',' . $bug->id . ',') !== false) echo 'checked';?>> <?php echo sprintf('%03d', $bug->id);?></td>
                    <td class='text-left nobr'><?php echo html::a($bugLink, $bug->title, '', "class='preview iframe'");?></td>
                    <td><?php echo $lang->bug->statusList[$bug->status];?></td>
                    <td><?php echo ($bug->status == 'resolved' or $bug->status == 'closed') ? substr($users[$bug->resolvedBy], 2) : html::select('resolvedBy[]', $users, $this->app->user->account, "class='w-70px'");?></td>
                  </tr>
                  <?php endforeach;?>
                </table>
              </div>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->build->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', $build->desc, "rows='10' class='form-control'");?></td>
      </tr>
      <tr><td></td><td colspan='2'><?php echo html::submitButton() . html::backButton() .html::hidden('project', $build->project);?></td></tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
