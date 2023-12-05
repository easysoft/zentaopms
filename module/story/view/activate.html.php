<?php
/**
 * The activate view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: activate.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $story->id;?></span>
        <?php echo isonlybody() ? ("<span title='$story->title'>" . $story->title . '</span>') : html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?>
        <?php if(!isonlybody()):?>
        <small><?php echo $lang->arrow . $lang->story->activate;?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' enctype='multipart/form-data' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->story->assignedTo;?></th>
          <td class='w-p45'><?php echo html::select('assignedTo', $users, $story->closedBy, 'class="form-control chosen"');?></td><td></td>
        </tr>
        <?php if(!empty($story->twins)):?>
        <tr>
          <th></th>
          <td colspan='2'><i class='icon-exclamation-sign'></i> <?php echo $lang->story->activateSyncTip;?></td>
        </tr>
        <?php endif;?>
        <?php $this->printExtendFields($story, 'table');?>
        <tr>
          <th><?php echo $lang->story->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', 'rows=5 class="area-1"');?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php
            echo html::submitButton($lang->story->activate, '', 'btn btn-wide btn-primary');
            echo html::linkButton($lang->goback, $app->session->storyList ? $app->session->storyList : inlink('view', "storyID=$story->id"), 'self', '', 'btn btn-wide');
            ?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
