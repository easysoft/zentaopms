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
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->gitlab->project->edit;?></h2>
      </div>
      <form id='gitlabForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->gitlab->project->id;?></th>
            <td><?php echo html::input('id', $project->id, "class='form-control' readonly placeholder='{$lang->gitlab->project->id}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->project->name;?></th>
            <td class='required'><?php echo html::input('name', $project->name, "class='form-control' placeholder='{$lang->gitlab->project->name}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->project->tagList;?></th>
            <td><?php echo html::input('tag_list', join(',', $project->tag_list), "class='form-control'");?></td>
            <td class="tips-git"><?php echo $lang->gitlab->project->tagListTips;?></td>
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
            <th></th>
            <td class='text-center form-actions'>
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
