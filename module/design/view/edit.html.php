<?php
/**
 * The edit view of design module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     design
 * @version     $Id: edit.html.php 4903 2020-09-02 09:32:59Z tianshujie@easycorp.ltd $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('type', $design->type);?>
<?php js::set('projectID', $design->project);?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2 id="short-content" title="<?php echo $design->name;?>">
        <span class='label label-id'><?php echo $design->id;?></span>
        <?php echo html::a($this->createLink('design', 'view', "id=$design->id"), $design->name, '');?>
      </h2>
      <h2>
        <small><?php echo $lang->arrow . ' ' . $lang->design->edit;?></small>
      </h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr <?php if(empty($project->hasProduct)) echo "class='hide'";?>>
            <th class='w-120px'><?php echo $lang->design->product;?></th>
            <td><?php echo html::select('product', $products, $design->product, "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th class='w-120px'><?php echo $lang->design->story;?></th>
            <td><?php echo html::select('story', empty($stories) ? '' : $stories, $design->story, "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->type;?></th>
            <td><?php echo html::select('type', $typeList, $design->type, "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->name;?></th>
            <td><?php echo html::input('name', $design->name, "class='form-control'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->desc;?></th>
            <td colspan='2'><?php echo html::textarea('desc', $design->desc, 'class="form-control"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->file;?></th>
            <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=1&percent=0.85');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->checkAffection;?></th>
            <td colspan='2'>
              <div class='tabs'>
                <ul class='nav nav-tabs'>
                  <li class='active'><a data-toggle='tab' href='#affectedTasks'><?php echo $lang->design->affectedTasks;?><span class='label label-danger label-badge label-circle'><?php echo count($design->tasks);?></span></a></li>
                </ul>
                <div class='tab-content'>
                  <div class='tab-pane active' id='affectedTasks'>
                    <table class='table'>
                      <thead>
                        <tr class='text-center'>
                          <th class='c-id'>    <?php echo $lang->task->id;?></th>
                          <th class='c-name'>  <?php echo $lang->task->name;?></th>
                          <th class='w-100px'> <?php echo $lang->task->assignedTo;?></th>
                          <th class='c-status'><?php echo $lang->task->status;?></th>
                          <th class='w-100px'> <?php echo $lang->task->consumed;?></th>
                          <th class='w-90px'>  <?php echo $lang->task->left;?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($design->tasks as $task):?>
                        <tr class='text-center'>
                          <td><?php $task->id?></td>
                          <td class='text-left'><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name, '_blank');?></td>
                          <td><?php echo zget($users, $task->assignedTo);?></td>
                          <td><span class='status-task status-<?php echo $task->status;?>'><?php $this->processStatus('task', $task);?></span></td>
                          <td><?php echo $task->consumed;?></td>
                          <td><?php echo $task->left;?></td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan='3' class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
