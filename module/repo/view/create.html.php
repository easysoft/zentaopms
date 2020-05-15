<?php
/**
 * The create view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong 
 * @package     repo
 * @version     $Id: create.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php if(common::checkNotCN()):?>
<style>
.user-addon{padding-right: 16px; padding-left: 16px;}
</style>
<?php endif;?>
<?php js::set('scm',  'Git')?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->repo->create; ?></h2>
      </div>
      <form id='repoForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th class='thWidth'><?php echo $lang->repo->type; ?></th>
            <td style="width:550px"><?php echo html::select('SCM', $lang->repo->scmList, 'Git', "onchange='scmChanged(this.value)' class='form-control'"); ?></td>
            <td class="tips-git"><?php echo $lang->repo->syncTips; ?></td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->name; ?></th>
            <td class='required'><?php echo html::input('name', '', "class='form-control'"); ?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->path; ?></th>
            <td class='required'><?php echo html::input('path', '', "class='form-control'"); ?></td>
            <td class='muted'>
                <span class="tips-git"><?php echo $lang->repo->example->path->git;?></span>
                <span class="tips-svn"><?php echo $lang->repo->example->path->svn;?></span>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->encoding; ?></th>
            <td class='required'><?php echo html::input('encoding', 'utf-8', "class='form-control'"); ?></td>
            <td class='muted'><?php echo $lang->repo->encodingsTips; ?></td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->client;?></th>
            <td class='required'><?php echo html::input('client', '', "class='form-control'")?></td>
            <td class='muted'>
                <span class="tips-git"><?php echo $lang->repo->example->client->git;?></span>
                <span class="tips-svn"><?php echo $lang->repo->example->client->svn;?></span>
            </td>
          </tr>
          <tr class="account-fields">
            <th><?php echo $lang->repo->account;?></th>
            <td><?php echo html::input('account', '', "class='form-control'");?></td>
          </tr>
          <tr class="account-fields">
            <th><?php echo $lang->repo->password;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::password('password', '', "class='form-control'");?>
                <span class='input-group-addon fix-border fix-padding'></span>
                <?php echo html::select('encrypt', $lang->repo->encryptList, 'base64', "class='form-control'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->acl;?></th>
            <td class='acl'>
              <div class='input-group mgb-10'>
                <span class='input-group-addon'><?php echo $lang->repo->group?></span>
                <?php echo html::select('acl[groups][]', $groups, '', "class='form-control chosen' multiple")?>
              </div>
              <div class='input-group'>
                <span class='input-group-addon user-addon'><?php echo $lang->repo->user?></span>
                <?php echo html::select('acl[users][]', $users, '', "class='form-control chosen' multiple")?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->desc; ?></th>
            <td colspan='2'><?php echo html::textarea('desc', '', "rows='3' class='form-control'"); ?></td>
          </tr>
          <tr>
            <th></th>
            <td colspan='2' class='text-center form-actions'>
              <?php echo html::submitButton(); ?>
              <?php echo html::backButton(); ?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
