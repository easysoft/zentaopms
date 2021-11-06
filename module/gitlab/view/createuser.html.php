<?php
/**
 * The create view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gitlab
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::import($jsRoot . 'misc/base64.js');?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->gitlab->user->create;?></h2>
      </div>
      <form id='gitlabForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->gitlab->user->name;?></th>
            <td class='required'><?php echo html::input('name', '', "class='form-control' placeholder='{$lang->gitlab->user->name}'");?></td>
            <td class="tips-git"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->username;?></th>
            <td class='required'><?php echo html::input('username', '', "class='form-control' placeholder='{$lang->gitlab->user->username}'");?></td>
            <td class="tips-git"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->email;?></th>
            <td class='required'><?php echo html::input('email', '', "class='form-control' placeholder='{$lang->gitlab->user->email}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->password;?></th>
            <td class='required'><?php echo html::password('password', '', "class='form-control' placeholder='{$lang->gitlab->user->password}'");?></td>
            <td class="tips-git"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->passwordRepeat;?></th>
            <td class='required'><?php echo html::password('password_repeat', '', "class='form-control' placeholder='{$lang->gitlab->user->passwordRepeat}'");?></td>
            <td class="tips-git"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->projectsLimit;?></th>
            <td><?php echo html::input('projects_limit', 100000, "class='form-control' placeholder='{$lang->gitlab->user->projectsLimit}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->canCreateGroup;?></th>
            <td>
              <div class="checkbox-primary">
                <input type="checkbox" name='can_create_group' id="canCreateGroup" value='1' checked /><label for="external" class="no-margin">&nbsp;</label>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->external;?></th>
            <td colspan='2'>
              <div class="checkbox-primary">
                <input type="checkbox" name='external' id="external" value='1'/><label for="external" class="no-margin"><?php echo $lang->gitlab->user->externalTip; ?></label>
              </div>
            </td> 
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->skype;?></th>
            <td><?php echo html::input('skype', '', "class='form-control' placeholder='{$lang->gitlab->user->skype}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->linkedin;?></th>
            <td><?php echo html::input('linkedin', '', "class='form-control' placeholder='{$lang->gitlab->user->linkedin}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->twitter;?></th>
            <td><?php echo html::input('twitter', '', "class='form-control' placeholder='{$lang->gitlab->user->twitter}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->websiteUrl;?></th>
            <td><?php echo html::input('website_url', '', "class='form-control' placeholder='{$lang->gitlab->user->websiteUrl}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->note;?></th>
            <td><?php echo html::textarea('note', '', "rows='10' class='form-control' placeholder='{$lang->gitlab->user->note}'");?></td>
          </tr>
          <tr>
            <th></th>
            <td class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a(inlink('projectbrowse', "gitlabID=$gitlabID"), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
