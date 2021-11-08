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
        <h2><?php echo $lang->gitlab->project->create;?></h2>
      </div>
      <form id='gitlabForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->gitlab->project->name;?></th>
            <td class='required'><?php echo html::input('name', '', "class='form-control' placeholder='{$lang->gitlab->project->name}'");?></td>
            <td class="tips-git"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->project->url;?></th>
            <td><?php echo html::input('url', $gitlab->url . '/' . $user->username . '/', "class='form-control' disabled");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->project->path;?></th>
            <td class='required'><?php echo html::input('path', '', "class='form-control' placeholder='{$lang->gitlab->project->path}'");?></td>
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
