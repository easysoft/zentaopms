<?php
/**
 * The view file of review method of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testcase
 * @version     $Id: review.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['testcase']);?> <strong><?php echo $case->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('case', 'view', "caseID=$case->id"), $case->title);?></strong>
    <small><?php echo html::icon($lang->icons['review']) . ' ' . $lang->testcase->review;?></small>
  </div>
</div>
<form method='post' target='hiddenwin' class='form-condensed'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->testcase->reviewedDate;?></th>
        <td class='w-p25-f'><?php echo html::input('reviewedDate', helper::today(), "class='form-control form-date'");?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->testcase->reviewResult;?></th>
        <td><?php echo html::select('result', $lang->testcase->reviewResultList, '', 'class=form-control');?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->testcase->reviewedBy;?></th>
        <td colspan='2'><?php echo html::select('reviewedBy[]', $users, $app->user->account, "class='form-control chosen' multiple");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->comment;?></th>
        <td colspan='2'><?php echo html::textarea('comment', '', "rows='8' class='form-control'");?></td>
      </tr>
      <tr>
        <td></td>
        <td colspan='2'>
        <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
</form>
<hr class='small'>
<div class='main'><?php include '../../common/view/action.html.php';?></div>
<?php include '../../common/view/footer.lite.html.php';?>
