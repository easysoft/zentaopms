<?php
/**
 * The MR approval file of MR module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     mr
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<?php include '../../common/view/kindeditor.html.php'; ?>
<style>.main-header .label {top: 6px;}</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <span class='label label-id'><?php echo $MR->id; ?></span>
      <h2>
        <?php echo isonlybody() ? ("<span title='$MR->title'>" . $MR->title . ' - ' . zget($lang->mr->approvalResultList, $action) . '</span>') : html::a($this->createLink('mr', 'view', 'MRID=' . $MR->id), $MR->title); ?>
        <?php if(!isonlybody()): ?>
          <small><?php echo $lang->arrow . $lang->mr->approval; ?></small>
        <?php endif; ?>
      </h2>
    </div>
    <form id='ajaxForm' class='form-ajax' method='post' action='<?php echo $this->createLink('mr', 'approval', "mr=$MR->id&action=$action")?>'>
      <table class='table table-form'>
        <?php if($MR->needCI and $showCompileResult): ?>
          <tr>
            <th class='w-90px'><?php echo $lang->compile->result; ?></th>
            <td class='w-p25-f'>
              <?php echo html::a($compileUrl, $lang->compile->statusList[$MR->compileStatus], '_blank'); ?>
            </td>
            <td></td>
          </tr>
        <?php endif; ?>
        <tr>
          <th class='w-90px'><?php echo $lang->mr->assignee; ?></th>
          <td class='w-p25-f'>
            <?php echo html::select('assignedTo', $users, $MR->createdBy, "class='form-control chosen'"); ?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment; ?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control'"); ?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton($lang->save); ?>
            <?php echo html::linkButton($lang->goback, $this->createLink('mr', 'view', 'MR=' . $MR->id), 'self', '', 'btn btn-wide'); ?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php'; ?></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
