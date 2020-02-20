<?php
/**
 * The browse view file of ci job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     ci
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('integration', 'browse'), "<span class='text'>{$lang->ci->task}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('integration', 'create')) common::printLink('integration', 'create', "", "<i class='icon icon-plus'></i> " . $lang->integration->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='jobList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->integration->id);?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->integration->name);?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('repo', $orderBy, $vars, $lang->integration->repo);?></th>
          <th class='w-150px text-left'><?php echo $lang->integration->triggerType;?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('jenkins', $orderBy, $vars, $lang->integration->jenkins);?></th>
          <th class='w-200px text-left'><?php echo $lang->integration->jenkinsJob;?></th>
          <th class='text-left'><?php echo $lang->integration->lastExec;?></th>
          <th class='w-120px c-actions-4'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($jobList as $id => $job):?>
        <tr>
          <td class='text-center'><?php echo $id; ?></td>
          <td class='text' title='<?php echo $job->name; ?>'><?php echo $job->name; ?></td>
          <td class='text' title='<?php echo $job->repoName; ?>'><?php echo $job->repoName; ?></td>
          <?php $triggerType = zget($lang->integration->triggerTypeList, $job->triggerType);?>
          <td class='text' title='<?php echo $triggerType;?>'><?php echo $triggerType;?></td>
          <td class='text' title='<?php echo $job->jenkinsName; ?>'><?php echo $job->jenkinsName; ?></td>
          <td class='text' title='<?php echo $job->jenkinsJob; ?>'><?php echo $job->jenkinsJob; ?></td>
          <td class='text'><?php if($job->lastStatus) echo zget($lang->compile->statusList, $job->lastStatus) . $lang->ci->at . $job->lastExec;?></td>
          <td class='c-actions text-center'>
            <?php
            common::printIcon('compile', 'browse', "jobID=$id", '', 'list', 'file-text');
            common::printIcon('integration', 'edit', "jobID=$id", '', 'list',  'edit');
            if(common::hasPriv('integration', 'delete')) echo html::a($this->createLink('integration', 'delete', "jobID=$id"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->integration->delete}' class='btn'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($jobList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </form>
</div>
<?php include '../../common/view/footer.html.php'; ?>
