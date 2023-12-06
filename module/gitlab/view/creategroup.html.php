<?php
/**
 * The create view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gitlab
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php $publicTip = "<span id='publicTip' class='text-danger'>" . $lang->gitlab->group->publicTip . '</span>';?>
<?php js::set('publicTip', $publicTip);?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->gitlab->group->create;?></h2>
      </div>
      <form id='gitlabForm' method='post' class='form-ajax' enctype="multipart/form-data">
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->gitlab->group->name;?></th>
            <td class='required'><?php echo html::input('name', '', "class='form-control' placeholder='{$lang->gitlab->group->name}'");?></td>
            <td class="tips-git"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->group->path;?></th>
            <td class='required'>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $gitlab->url . '/';?></span>
                <span><?php echo html::input('path', '', "class='form-control' placeholder='{$lang->gitlab->group->path}'");?></span>
              </div>
            </td>
            <td class="tips-git"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->group->description;?></th>
            <td><?php echo html::textarea('description', '', "rows='4' class='form-control' placeholder='{$lang->gitlab->group->description}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->group->visibility;?></th>
            <td colspan='2'><?php echo nl2br(html::radio('visibility', $lang->gitlab->group->visibilityList, 'private', "", 'block'));?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->group->permission;?></th>
            <td>
              <div class="checkbox-primary">
                <input type="checkbox" name='request_access_enabled' id="requestAccessEnabled" value='1' checked /><label for="external" class="no-margin"><?php echo $lang->gitlab->group->requestAccessEnabledTip; ?></label>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->group->lfsEnabled;?></th>
            <td colspan='2'>
              <div class="checkbox-primary">
                <input type="checkbox" name='lfs_enabled' id="lfsEnabled" value='1' checked /><label for="external" class="no-margin"><?php echo $lang->gitlab->group->lfsEnabledTip; ?></label>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->group->projectCreationLevel;?></th>
            <td><?php echo html::select('project_creation_level', $lang->gitlab->group->projectCreationLevelList, 'developer', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->group->subgroupCreationLevel;?></th>
            <td><?php echo html::select('subgroup_creation_level', $lang->gitlab->group->subgroupCreationLevelList, 'maintainer', "class='form-control'");?></td>
          </tr>
          <tr>
            <td colspan="2" class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a(inlink('browseGroup', "gitlabID=$gitlabID"), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
