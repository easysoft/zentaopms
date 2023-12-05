<?php
/**
 * The project view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: project.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include './featurebar.html.php';?>
<div id='mainContent'>
  <div class='main-table'>
    <table class='table has-sort-head table-fixed'>
      <?php $vars = "userID={$user->id}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
      <thead>
        <tr class='colhead'>
          <th class='c-id'>     <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
          <th class="text-left"><?php common::printOrderLink('name', $orderBy, $vars, $lang->user->name);?></th>
          <th class='c-status'> <?php common::printOrderLink('status', $orderBy, $vars, $lang->statusAB);?></th>
          <th class='c-user'>   <?php echo $lang->team->role;?></th>
          <th class='c-date'>   <?php common::printOrderLink('begin', $orderBy, $vars, $lang->execution->begin);?></th>
          <th class='c-date'>   <?php common::printOrderLink('end', $orderBy, $vars, $lang->execution->end);?></th>
          <th class='c-date'>   <?php echo $lang->team->join;?></th>
          <th class='c-hours'>  <?php echo $lang->team->hours;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($executions as $execution):?>
        <?php $executionLink = $this->createLink('execution', 'view', "executionID=$execution->id");?>
        <tr>
          <td><?php echo html::a($executionLink, $execution->id, '', "data-app='execution'");?></td>
          <td>
            <?php if(in_array($this->config->edition, array('max', 'ipd'))):?>
            <span class='project-type-label label label-info label-outline'><?php echo zget($lang->user->executionTypeList, $execution->type);?></span>
            <?php endif;?>
            <?php echo html::a($executionLink, $execution->name, '', "data-app='execution'");?>
          </td>
          <?php if(isset($execution->delay)):?>
          <td class='project-delay'><?php echo $lang->execution->delayed;?></td>
          <?php else:?>
          <td class='project-<?php echo $execution->status?>'><?php echo $this->processStatus('execution', $execution);?></td>
          <?php endif;?>
          <td><?php echo $execution->role;?></td>
          <td><?php echo $execution->begin;?></td>
          <td><?php echo $execution->end;?></td>
          <td><?php echo $execution->join;?></td>
          <td><?php echo $execution->hours;?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($executions):?>
    <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
