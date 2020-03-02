<?php
/**
 * The browse view file of integration module of ZenTaoPMS.
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
    <?php echo html::a($this->createLink('integration', 'browse'), "<span class='text'>{$lang->ci->plan}</span>", '', "class='btn btn-link btn-active-text'");?>
    <?php echo html::a($this->createLink('compile', 'browse'), "<span class='text'>{$lang->ci->history}</span>", '', "class='btn btn-link'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('integration', 'create')) common::printLink('integration', 'create', "", "<i class='icon icon-plus'></i> " . $lang->integration->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='jobList' class='table has-sort-head table-fixed'>
      <thead>
        <tr class='text-center'>
          <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->integration->id);?></th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->integration->name);?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('repo', $orderBy, $vars, $lang->integration->repo);?></th>
          <th class='w-200px text-left'><?php echo $lang->integration->triggerType;?></th>
          <th class='w-300px text-left'><?php common::printOrderLink('jkHost', $orderBy, $vars, $lang->integration->jenkins);?></th>
          <th class='w-100px text-left'><?php common::printOrderLink('lastStatus', $orderBy, $vars, $lang->integration->lastStatus);?></th>
          <th class='w-120px text-left'><?php common::printOrderLink('lastExec', $orderBy, $vars, $lang->integration->lastExec);?></th>
          <th class='w-120px c-actions-3'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($jobList as $id => $job):?>
        <tr>
          <td class='text-center'><?php echo $id; ?></td>
          <td class='text' title='<?php echo $job->name; ?>'><?php echo $job->name; ?></td>
          <td class='text' title='<?php echo $job->repoName; ?>'><?php echo $job->repoName; ?></td>
          <?php
          $triggerType = zget($lang->integration->triggerTypeList, $job->triggerType);
          if($job->triggerType == 'tag' and !empty($job->svnDir)) $triggerType = $lang->integration->dirChange;

          $triggerConfig = '';
          if($job->triggerType == 'commit')
          {
              $triggerConfig = "({$job->comment})";
          }
          elseif($job->triggerType == 'schedule')
          {
              $atDay = '';
              foreach(explode(',', $job->atDay) as $day) $atDay .= zget($lang->datepicker->dayNames, trim($day), '') . ',';
              $atDay = trim($atDay, ',');

              $triggerConfig = "({$atDay}, {$job->atTime})";
          }
          ?>
          <td class='text' title='<?php echo $triggerType . $triggerConfig;?>'><?php echo $triggerType . $triggerConfig;?></td>
          <td class='text' title='<?php echo $job->jenkinsName; ?>'><?php echo urldecode($job->jkJob) . '@' . $job->jenkinsName; ?></td>
          <td class='text'><?php if($job->lastStatus) echo zget($lang->compile->statusList, $job->lastStatus);?></td>
          <td class='text'><?php if($job->lastStatus) echo $job->lastExec;?></td>
          <td class='c-actions text-center'>
            <?php
            common::printIcon('compile', 'browse', "integrationID=$id", '', 'list', 'file-text');
            common::printIcon('integration', 'edit', "integrationID=$id", '', 'list',  'edit');
            if(common::hasPriv('integration', 'delete')) echo html::a($this->createLink('integration', 'delete', "integrationID=$id"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->integration->delete}' class='btn'");
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
