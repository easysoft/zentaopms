<?php
/**
 * The browse view file of compile module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     compile
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    echo html::a($this->createLink('job', 'browse'), "<span class='text'>{$lang->ci->task}</span>", '', "class='btn btn-link'");
    echo html::a($this->createLink('compile', 'browse'), "<span class='text'>" . ($jobID ? $job->name : '') . " {$lang->ci->history}</span>", '', "class='btn btn-link btn-active-text'");
    ?>
  </div>
</div>

<div id='mainContent'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='buildList' class='table has-sort-head table-fixed'>
      <thead>
        <tr class='text-center'>
          <?php $vars = "jobID={$jobID}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->compile->id);?></th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->compile->name);?></th>
          <th class='w-150px text-left'><?php echo $lang->job->repo;?></th>
          <th class='w-250px text-left'><?php echo $lang->job->jenkins;?></th>
          <th class='text-left'><?php echo $lang->job->triggerType;?></th>
          <th class='w-80px text-center'><?php common::printOrderLink('status', $orderBy, $vars, $lang->compile->status);?></th>
          <th class='w-130px text-center'><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->compile->time);?></th>
          <th class='w-120px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody class='text-left'>
        <?php foreach($buildList as $id => $build):?>
        <tr>
          <td class='text-center'><?php echo $id;?></td>
          <td title='<?php echo $build->name;?>'><?php echo common::hasPriv('job', 'view') ? html::a($this->createLink('job', 'view', "jobID={$build->job}&compileID={$build->id}", 'html', true), $build->name, '', "class='iframe' data-width='90%'") : $build->name;?></td>
          <td title='<?php echo $build->repoName;?>'><?php echo $build->repoName;?></td>
          <?php $jenkins = urldecode($build->jkJob) . '@' . $build->jenkinsName;?>
          <td title='<?php echo $jenkins; ?>'><?php echo $jenkins; ?></td>
          <?php $triggerConfig = $this->loadModel('job')->getTriggerConfig($build);?>
          <td title='<?php echo $triggerConfig;?>'><?php echo $triggerConfig;?></td>
          <?php $buildStatus = zget($lang->compile->statusList, $build->status);?>
          <td class='text-center' title='<?php echo $buildStatus;?>'><?php echo $buildStatus;?></td>
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
<?php include '../../common/view/footer.html.php';?>
