<?php
/**
 * The submit review file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     story
 * @version     $Id: submitreview.html.php 935 2022-07-20 09:49:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('lastReviewer', explode(',', $lastReviewer))?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $story->id;?></span>
        <?php echo isonlybody() ? ("<span title='$story->title'>" . $story->title . '</span>') : html::a($this->createLink('story', 'view', 'story=' . $story->id), $story->title);?>
        <?php if(!isonlybody()):?>
        <small> <?php echo $lang->arrow . $lang->story->submitReview;?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->story->reviewedBy;?></th>
          <td colspan='2' id='reviewerBox' <?php if($this->story->checkForceReview() or !empty($story->reviewer)) echo "class='required'";?>>
            <div class="table-row">
              <?php if(!$this->story->checkForceReview()):?>
              <div class="table-col">
                <?php echo html::select('reviewer[]', $reviewers, $story->reviewer, "class='form-control picker-select' multiple");?>
              </div>
              <div class="table-col needNotReviewBox">
                <span class="input-group-addon">
                  <div class='checkbox-primary'>
                    <input id='needNotReview' name='needNotReview' value='1' type='checkbox' class='no-margin' <?php echo $needReview;?>/>
                    <label for='needNotReview'><?php echo $lang->story->needNotReview;?></label>
                  </div>
                </span>
              </div>
              <?php else:?>
              <div class="table-col">
                <?php echo html::select('reviewer[]', $reviewers, $story->reviewer, "class='form-control picker-select' multiple");?>
              </div>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::hidden('id', $story->id);?>
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
