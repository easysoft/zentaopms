<?php
/**
 * The init jira user view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->convert->jira->initJiraUser;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" id="createForm" method="post" target='hiddenwin'>
      <table align='center' class="table table-form">
        <tr>
          <th><?php echo $lang->user->password;?></th>
          <td>
            <input type='password' style="display:none"> <!-- for disable autocomplete all browser -->
            <span class='input-group'>
              <?php echo html::password('password1', '', "class='form-control' required");?>
            </span>
          </td>
          <td><?php echo $lang->convert->jira->passwordNotice;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->password2;?></th>
          <td><?php echo html::password('password2', '', "class='form-control' required");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->group;?></th>
          <td><?php echo html::select('group', $groups, '', "class='form-control chosen'");?></td>
          <td><?php echo $lang->convert->jira->groupNotice;?></td>
        </tr>
        <tr>
          <td class='text-center form-actions' colspan='3'>
            <?php echo html::a(inlink('mapJira2Zentao', "method=$method&dnname={$this->session->jiraDB}&step=4"), $lang->goback, '', "class='btn btn-wide'");?>
            <?php echo html::submitButton($lang->convert->jira->next);?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
