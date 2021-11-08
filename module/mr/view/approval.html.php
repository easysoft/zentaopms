<?php

/**
 * The MR approval file of MR module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     mr
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<?php include '../../common/view/kindeditor.html.php'; ?>
<div id='mainContent' class='main-content'>
    <div class='center-block'>
        <div class='main-header'>
            <h2>
                <span class='label label-id'><?php echo $MR->id; ?></span>
                <?php echo isonlybody() ? ("<span title='$MR->title'>" . $MR->title . '</span>') : html::a($this->createLink('mr', 'view', 'MR=' . $MR->id), $MR->title); ?>
                <?php if (!isonlybody()) : ?>
                    <small><?php echo $lang->arrow . $lang->mr->approval; ?></small>
                <?php endif; ?>
            </h2>
        </div>
        <form method='post' target='hiddenwin' onsubmit='return checkLeft();'>
            <table class='table table-form'>
                <tr>
                    <th class='w-90px'><?php echo $lang->mr->assignee; ?></th>
                    <td class='w-p25-f'>
                        <?php echo html::select('assignedTo', $users, $MR->createdBy, "class='form-control chosen'"); ?>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <th><?php echo $lang->mr->approvalResult; ?></th>
                    <td>
                        <div class='input-group'>
                            <?php if (!empty($MR->approvalStatus)) $oldStatus = $MR->approvalStatus == 'rejected' ? 'reject' : 'approve'; ?>
                            <?php echo html::radio('approveResult', $options = $lang->mr->approvalResultList, $checked = $oldStatus); ?>
                    </td>
    </div>
    </td>
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