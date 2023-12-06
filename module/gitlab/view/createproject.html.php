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
<?php $publicTip = "<span id='publicTip' class='text-danger'>" . $lang->gitlab->project->publicTip . '</span>';?>
<?php js::set('publicTip', $publicTip);?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->gitlab->project->create;?></h2>
      </div>
      <form id='gitlabForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th class='w-150px'><?php echo $lang->gitlab->project->name;?></th>
            <td class='required'><?php echo html::input('name', '', "class='form-control' placeholder='{$lang->gitlab->project->name}'");?></td>
            <td class="tips-git w-p20"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->project->url;?></th>
            <td>
              <div class='input-group'>
                <?php if(count($namespaces) < 2):?>
                  <?php echo html::input('url', $gitlab->url . '/' . $user->username . '/', "class='form-control' disabled");?>
                <?php else:?>
                  <span class='input-group-addon'><?php echo $gitlab->url . '/';?></span>
                  <?php echo html::select('namespace_id', $namespaces, $user->username, "class='form-control'");?>
                <?php endif;?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->project->path;?></th>
            <td class='required'><?php echo html::input('path', '', "class='form-control' placeholder='{$lang->gitlab->placeholder->projectPath}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->project->description;?></th>
            <td><?php echo html::textarea('description', '', "rows='10' class='form-control' placeholder='{$lang->gitlab->project->description}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->project->visibility;?></th>
            <td colspan='2'><?php echo nl2br(html::radio('visibility', $lang->gitlab->project->visibilityList, 'private', "", 'block'));?></td>
          </tr>
          <tr>
            <td colspan="2" class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a(inlink('browseProject', "gitlabID=$gitlabID"), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
