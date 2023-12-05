<?php
/**
 * The change view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: change.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<?php js::set('lastReviewer', explode(',', $lastReviewer))?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $story->id;?></span>
        <?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title, '', 'class="story-title"');?>
        <small><?php echo $lang->arrow . ' ' . $lang->story->change;?></small>
      </h2>
    </div>
    <form class="main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->story->reviewedBy;?></th>
          <td id='reviewerBox'>
            <div class="input-group">
              <?php echo html::select('reviewer[]', $productReviewers, $reviewer, "class='form-control picker-select' multiple" . ($this->story->checkForceReview() ? ' required' : ''));?>
              <?php if(!$this->story->checkForceReview()):?>
              <span class="input-group-addon">
              <?php echo html::checkbox('needNotReview', $lang->story->needNotReview, '', "id='needNotReview' {$needReview}");?>
              </span>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->story->status;?></th>
          <td><?php echo html::hidden('status', $story->status);?></td>
        </tr>
        <?php $this->printExtendFields($story, 'table');?>
        <tr>
          <th><?php echo $lang->story->title;?></th>
          <td>
            <?php $titleGroup = empty($story->twins) ? '' : 'title-group';?>
            <div class='input-group <?php echo $titleGroup?>'>
              <div class="input-control has-icon-right">
                <?php echo html::input('title', $story->title, 'class="form-control story-title"');?>
                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" title="<?php echo $lang->task->colorTag ?>"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                    <li class="heading"><?php echo $lang->story->colorTag; ?><i class="icon icon-close"></i></li>
                  </ul>
                  <input type="hidden" class="colorpicker" id="color" name="color" value="<?php echo $story->color ?>" data-icon="color" data-wrapper="input-control-icon-right" data-update-color=".story-title"  data-provide="colorpicker">
                </div>
              </div>
              <?php if(!empty($story->twins)):?>
              <div class="input-group-addon">
                <?php echo html::checkbox('relievedTwins', $lang->story->relievedTwinsRelation, '', "id='relievedTwins' title='{$lang->story->changeRelievedTwinsTips}'");?>
              </div>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->story->spec;?></th>
          <td><?php echo html::textarea('spec', htmlSpecialString($story->spec), 'rows=8 class="form-control"');?><span class='help-block'><?php echo $lang->story->specTemplate;?></span></td>
        </tr>
        <tr>
          <th><?php echo $lang->story->verify;?></th>
          <td><?php echo html::textarea('verify', htmlSpecialString($story->verify), 'rows=6 class="form-control"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->story->comment;?></th>
          <td><?php echo html::textarea('comment', '', 'rows=5 class="form-control"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->attach;?></th>
          <td>
          <?php echo $this->fetch('file', 'printFiles', array('files' => $story->files, 'fieldset' => 'false', 'object' => $story, 'method' => 'edit'));?>
          <?php echo $this->fetch('file', 'buildform');?>
          </td>
        </tr>
        <?php if($this->config->vision != 'or'):?>
        <tr>
          <th><?php echo $lang->story->checkAffection;?></th>
          <td><?php include './affected.html.php';?></td>
        </tr>
        <?php endif;?>
        <tr>
          <td></td>
          <td class='text-center form-actions'>
            <?php
            echo html::hidden('lastEditedDate', $story->lastEditedDate);
            echo html::commonButton($lang->save, "id='saveButton'", 'btn btn-primary btn-wide');
            echo html::commonButton($lang->story->doNotSubmit, "id='saveDraftButton'", 'btn btn-secondary btn-wide');
            if(!isonlybody()) echo html::backButton();
            ?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
</div>
<?php js::set('storyID', $story->id);?>
<?php js::set('oldStoryTitle', $story->title);?>
<?php js::set('oldStorySpec', $story->spec);?>
<?php js::set('oldStoryVerify', $story->verify);?>
<?php js::set('changed', 0);?>
<?php js::set('storyType', $story->type);?>
<?php js::set('rawModule', $this->app->rawModule);?>
<?php js::set('page', $this->app->rawMethod);?>
<?php include '../../common/view/footer.html.php';?>
