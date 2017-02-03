<?php
/**
 * The change view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: change.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['story']);?> <strong><?php echo $story->id;?></strong></span>
      <strong><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?></strong>
      <small><?php echo html::icon($lang->icons['change']) . ' ' . $lang->story->change;?></small>
    </div>
  </div>
  <form method='post' enctype='multipart/form-data' target='hiddenwin' class='form-condensed'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->story->reviewedBy;?></th>
        <td>
          <div class="input-group w-p35-f">
            <?php echo html::select('assignedTo', $users, $story->assignedTo, 'class="form-control chosen"');?>
            <?php if(!$this->story->checkForceReview()):?>
            <span class="input-group-addon">
            <?php echo html::checkbox('needNotReview', $lang->story->needNotReview, '', "id='needNotReview' {$needReview}");?>
            </span>
            <?php endif;?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->story->title;?></th>
        <td><?php echo html::input('title', $story->title, 'class="form-control"');?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->spec;?></th>
        <td><?php echo html::textarea('spec', htmlspecialchars($story->spec), 'rows=8 class="form-control"');?><span class='help-block'><?php echo $lang->story->specTemplate;?></span></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->verify;?></th>
        <td><?php echo html::textarea('verify', htmlspecialchars($story->verify), 'rows=6 class="form-control"');?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->comment;?></th>
        <td><?php echo html::textarea('comment', '', 'rows=5 class="form-control"');?></td>
      </tr>
      <tr>
        <th><?php echo $lang->attatch;?></th>
        <td><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->checkAffection;?></th>
        <td><?php include './affected.html.php';?></td>
      </tr>
      <tr>
        <td></td><td class='text-center'>
        <?php 
        echo html::hidden('lastEditedDate', $story->lastEditedDate);
        echo html::submitButton();
        echo html::linkButton($lang->goback, $app->session->storyList ? $app->session->storyList : inlink('view', "storyID=$story->id"));
        ?>
        </td>
      </tr>
    </table>
  </form>
  <hr class='small'>
  <div class='main'>
    <?php include '../../common/view/action.html.php';?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
