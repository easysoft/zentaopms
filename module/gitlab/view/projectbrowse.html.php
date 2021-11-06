<?php
/**
 * The browse view file of gitlab module of ZenTaoPMS.
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
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('gitlab', 'createProject')) common::printLink('gitlab', 'createProject', "gitlabID=$gitlabID", "<i class='icon icon-plus'></i> " . $lang->gitlab->project->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($gitlabProjectList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(common::hasPriv('gitlab', 'createProject')):?>
    <?php echo html::a($this->createLink('gitlab', 'createProject', "gitlabID=$gitlabID"), "<i class='icon icon-plus'></i> " . $lang->gitlab->project->create, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gitlabProjectList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <th class='c-id'><?php echo $lang->gitlab->project->id;?></th>
          <th class='c-name text-left'><?php echo $lang->gitlab->project->name;?></th>
          <th class='text-left'></th>
          <th class='text-left'><?php echo $lang->gitlab->lastUpdate;?></th>
          <th class='c-actions-4'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gitlabProjectList as $id => $gitlabProject): ?>
        <tr class='text'>
          <td class='text-center'><?php echo $gitlabProject->id;?></td>
          <td class='text-c-name' title='<?php echo $gitlabProject->name;?>'><?php echo $gitlabProject->name;?></td>
          <td class='text text-c-counts'>
            <span title="<?php echo $lang->gitlab->project->star;?>"><i class="icon icon-star"></i> <?php echo $gitlabProject->star_count;?></span>
            <span title="<?php echo $lang->gitlab->project->fork;?>"><i class="icon icon-code-fork"></i> <?php echo $gitlabProject->forks_count;?></span>
          </td>
          <td class='text' title='<?php echo substr($gitlabProject->last_activity_at, 0, 10);?>'><?php echo substr($gitlabProject->last_activity_at, 0, 10);?></td>
          <td class='c-actions text-left'>
            <?php
            common::printLink('gitlab', 'editProject', "gitlabID=$gitlabID&projectID=$gitlabProject->id", "<i class='icon icon-edit'></i> ", '', "title={$lang->gitlab->project->edit} class='btn btn-primary'");
            if(common::hasPriv('gitlab', 'delete')) echo html::a($this->createLink('gitlab', 'deleteProject', "gitlabID=$gitlabID&projectID=$gitlabProject->id"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->gitlab->project->confirmDelete}' class='btn'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
