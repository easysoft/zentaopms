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
    echo html::a($this->createLink('integration', 'browse'), "<span class='text'>{$lang->ci->task}</span>", '', "class='btn btn-link'");
    echo html::a($this->createLink('compile', 'browse'), "<span class='text'>{$lang->ci->history}</span>", '', "class='btn btn-link btn-active-text'");
    ?>
  </div>
</div>

<div id='mainContent'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='buildList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "jobID={$job->id}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->compile->id);?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->compile->name);?></th>
          <th class='w-200px text-left'><?php echo $lang->integration->repo;?></th>
          <th class='w-200px text-left'><?php echo $lang->integration->jenkins;?></th>
          <th class='w-200px text-left'><?php echo $lang->integration->triggerType;?></th>
          <th class='w-150px text-left'><?php common::printOrderLink('status', $orderBy, $vars, $lang->compile->status);?></th>
          <th class='text-left'><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->compile->time);?></th>
          <th class='w-100px c-actions-4'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($buildList as $id => $build): ?>
        <tr>
          <td class='text-center'><?php echo $id; ?></td>
          <td class='text' title='<?php echo $build->name; ?>'><?php echo $build->name; ?></td>
          <td class='text' title='<?php echo $build->repoName; ?>'><?php echo $build->repoName; ?></td>
          <td class='text' title='<?php echo $build->jenkinsName; ?>'><?php echo $build->jenkinsName; ?></td>
          <?php $triggerType = zget($lang->integration->triggerTypeList, $build->triggerType);?>
          <td class='text' title='<?php echo $triggerType;?>'><?php echo $triggerType;?></td>
          <?php $buildStatus = zget($lang->compile->statusList, $build->status);?>
          <td class='text' title='<?php echo $buildStatus;?>'><?php echo $buildStatus;?></td>
          <td class='text' title='<?php echo $build->createDate; ?>'><?php echo $build->createdDate; ?></td>
          <td class='c-actions text-center'>
            <?php common::printIcon('compile', 'logs', "buildID=$id", '', 'list', 'file-text', '', '', '', '', $lang->compile->logs);?>
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
<?php include '../../common/view/footer.html.php'; ?>
