<?php
/**
 * The edit view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php if(common::checkNotCN()):?>
<style>
.user-addon{padding-right: 16px; padding-left: 16px;}
</style>
<?php endif;?>
<?php js::set('scm',  $repo->SCM)?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->repo->edit; ?></h2>
      </div>
      <form id='repoForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th class='thWidth'><?php echo $lang->repo->type; ?></th>
            <td style="width:550px"><?php echo html::select('SCM', $lang->repo->scmList, $repo->SCM, "onchange='scmChanged(this.value)' class='form-control'"); ?></td>
            <td>
                <span class="tips-git"><?php echo $lang->repo->syncTips; ?></span>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->name; ?></th>
            <td class='required'><?php echo html::input('name', $repo->name, "class='form-control'"); ?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->path; ?></th>
            <td class='required'><?php echo html::input('path', $repo->path, "class='form-control'"); ?></td>
            <td class='muted'>
                <span class="tips-git"><?php echo $lang->repo->example->path->git;?></span>
                <span class="tips-svn"><?php echo $lang->repo->example->path->svn;?></span>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->encoding; ?></th>
            <td class='required'><?php echo html::input('encoding', $repo->encoding, "class='form-control'"); ?></td>
            <td class='muted'><?php echo $lang->repo->encodingsTips; ?></td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->client;?></th>
            <td class='required'><?php echo html::input('client',  $repo->client, "class='form-control'")?></td>
            <td class='muted'>
                <span class="tips-git"><?php echo $lang->repo->example->client->git;?></span>
                <span class="tips-svn"><?php echo $lang->repo->example->client->svn;?></span>
            </td>
          </tr>
          <tr class="account-fields">
            <th><?php echo $lang->repo->account;?></th>
            <td><?php echo html::input('account', $repo->account, "class='form-control'");?></td>
          </tr>
          <tr class="account-fields">
            <th><?php echo $lang->repo->password;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::password('password', $repo->password, "class='form-control'");?>
                <span class='input-group-addon fix-border fix-padding'></span>
                <?php echo html::select('encrypt', $lang->repo->encryptList, $repo->encrypt, "class='form-control'");?>
              </div>
            </td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->acl;?></th>
            <td>
              <div class='input-group mgb-10'>
                <span class='input-group-addon'><?php echo $lang->repo->group?></span>
                <?php echo html::select('acl[groups][]', $groups, empty($repo->acl->groups) ? '' : join(',', $repo->acl->groups), "class='form-control chosen' multiple")?>
              </div>
              <div class='input-group'>
                <span class='input-group-addon user-addon'><?php echo $lang->repo->user?></span>
                <?php echo html::select('acl[users][]', $users, empty($repo->acl->users) ? '' : join(',', $repo->acl->users), "class='form-control chosen' multiple")?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->desc; ?></th>
            <td colspan='2'><?php echo html::textarea('desc', $repo->desc, "rows='3' class='form-control'"); ?></td>
          </tr>
          <tr>
            <th></th>
            <td colspan='2' class='text-center form-actions'>
              <?php echo html::submitButton(); ?>
              <?php echo html::backButton() ?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
