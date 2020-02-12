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
<?php if(common::checkNotCN()):?>
<style>
.user-addon{padding-right: 16px; padding-left: 16px;}
</style>
<?php endif;?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->repo->create;?></h2>
    </div>
    <form class='form-indicator main-form' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'> 
        <tr>
          <th><?php echo $lang->repo->SCM;?></th>
          <td><?php echo html::select('SCM', $lang->repo->scmList, 'Subversion', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->repo->name;?></th>
          <td class='required'><?php echo html::input('name', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->repo->path;?></th>
          <td class='required'><?php echo html::input('path', '', "class='form-control'")?></td>
          <td class='muted'><?php echo $lang->repo->example->path;?></td>
        </tr>  
        <tr>
          <th><?php echo $lang->repo->encoding;?></th>
          <td class='required'><?php echo html::input('encoding', 'utf-8', "class='form-control'");?></td>
        </tr> 
        <tr>
          <th><?php echo $lang->repo->client;?></th>
          <td class='required'><?php echo html::input('client', '', "class='form-control'")?></td>
          <td class='muted'><?php echo $lang->repo->example->client;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->repo->account;?></th>
          <td><?php echo html::input('account', '', "class='form-control'");?></td>
        </tr>  
        <tr>
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
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
