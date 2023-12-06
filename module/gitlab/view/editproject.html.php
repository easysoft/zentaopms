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
<?php js::set('visibility', $project->visibility);?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->gitlab->project->edit;?></h2>
      </div>
      <form id='gitlabForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th class='w-150px'><?php echo $lang->gitlab->project->id;?></th>
            <td><?php echo html::input('id', $project->id, "class='form-control' readonly placeholder='{$lang->gitlab->project->id}'");?></td>
            <td class="tips-git w-p20"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->project->name;?></th>
            <td class='required'><?php echo html::input('name', $project->name, "class='form-control' placeholder='{$lang->gitlab->project->name}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->project->description;?></th>
            <td><?php echo html::textarea('description', $project->description, "rows='10' class='form-control' placeholder='{$lang->gitlab->project->description}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->project->visibility;?></th>
            <td colspan='2'><?php echo nl2br(html::radio('visibility', $lang->gitlab->project->visibilityList, $project->visibility, "", 'block'));?></td>
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
