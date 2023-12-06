<?php
/**
 * The complete file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     story
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $story->id;?></span>
        <?php echo isonlybody() ? ("<span title='$story->title'>" . $story->title . '</span>') : html::a($this->createLink('story', 'view', 'story=' . $story->id), $story->title);?>
        <?php if(!isonlybody()):?>
        <small> <?php echo $lang->arrow . $lang->story->assignTo;?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->story->assign;?></th>
          <td class='w-p25-f'><?php echo html::select('assignedTo', $users, $story->assignedTo, "class='form-control chosen'");?></td><td></td>
        </tr>
        <?php if(!empty($story->twins)):?>
        <tr>
          <th></th>
          <td colspan='2'><i class='icon-exclamation-sign'></i> <?php echo $lang->story->assignSyncTip;?></td>
        </tr>
        <?php endif;?>
        <tr class='hide'>
          <th><?php echo $lang->story->status;?></th>
          <td><?php echo html::hidden('status', $story->status);?></td>
        </tr>
        <?php $this->printExtendFields($story, 'table');?>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control w-p98'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::linkButton($lang->goback, $this->session->storyList, '', '', 'btn btn-wide');?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <?php include '../../common/view/action.html.php';?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
