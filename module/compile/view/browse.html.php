<?php
/**
 * The browse view file of compile module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     compile
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $menus = customModel::getFeatureMenu('job', 'browse');
    foreach($menus as $menuItem)
    {
        $active = $menuItem->name == 'compile' ? 'btn-active-text' : '';
        echo html::a($this->createLink($menuItem->name, 'browse', "repoID=$repoID"), "<span class='text'>{$menuItem->text}</span>", '', "class='btn btn-link $active'");
    }
    ?>
  </div>
</div>
<?php if(empty($buildList)):?>
<div class="table-empty-tip">
  <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
</div>
<?php else:?>
<div id='mainContent'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='buildList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "repoID=$repoID&jobID={$jobID}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->compile->id);?></th>
          <th class='text'><?php common::printOrderLink('name', $orderBy, $vars, $lang->compile->name);?></th>
          <th class='c-build-type'><?php echo $lang->compile->buildType;?></th>
          <th class='c-repo'><?php echo $lang->job->repo;?></th>
          <th class='text'><?php echo $lang->job->triggerType;?></th>
          <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->compile->status);?></th>
          <th class='c-date'><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->compile->time);?></th>
          <th class='c-actions-2'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody class='text-left'>
        <?php foreach($buildList as $id => $build):?>
        <tr>
          <td class='text'><?php echo $id;?></td>
          <td class='c-name' title='<?php echo $build->name;?>'><?php echo common::hasPriv('job', 'view') ? html::a($this->createLink('job', 'view', "jobID={$build->job}&compileID={$build->id}", 'html', true), $build->name, '', "class='iframe' data-width='90%'") : $build->name;?></td>
          <td title='<?php echo $build->engine;?>'><?php echo $build->engine;?></td>
          <td title='<?php echo $build->repoName;?>'><?php echo $build->repoName;?></td>
          <?php $jenkins = urldecode($build->pipeline) . '@' . $build->jenkinsName;?>
          <?php $triggerConfig = $this->loadModel('job')->getTriggerConfig($build);?>
          <td title='<?php echo $triggerConfig;?>'><?php echo $triggerConfig;?></td>
          <?php $buildStatus = zget($lang->compile->statusList, $build->status);?>
          <td class='text' title='<?php echo $buildStatus;?>'><?php echo $buildStatus;?></td>
          <td title='<?php echo $build->createdDate;?>'><?php echo $build->createdDate;?></td>
          <td class='c-actions text-left'>
            <?php
            common::printLink('compile', 'logs', "buildID=$id", $lang->compile->logs);
            if($build->testtask)
            {
                common::printLink('testtask', 'unitCases', "taskID=$build->testtask", $lang->compile->result, '', "class='iframe' data-width='90%'", true, true);
            }
            ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if($buildList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif; ?>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
