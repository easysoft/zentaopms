<?php
/**
 * The submit review file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     story
 * @version     $Id: submitreview.html.php 935 2022-07-20 09:49:24Z $
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
        <small> <?php echo $lang->arrow . $lang->story->recallAction;?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <td class='text-center'><?php echo html::radio('recallList', $lang->story->recallList, 'recallReview');?></td>
        </tr>
        <tr>
          <td class='text-center form-actions'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <?php include '../../common/view/action.html.php';?>
  </div>
</div>
<style>
.table tr td label{display:block; margin-left: 0px !important; padding-top: 10px;}
</style>
<?php include '../../common/view/footer.html.php';?>
