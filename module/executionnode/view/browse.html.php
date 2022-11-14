<?php
/**
 * The browse view file of executionnode module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      xiawenlong <xiawenlong@cnezsoft.com>
 * @package     executionnode
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->executionnode->confirmDelete)?>
<?php js::set('confirmBoot', $lang->executionnode->confirmBoot)?>
<?php js::set('confirmReboot', $lang->executionnode->confirmReboot)?>
<?php js::set('confirmShutdown', $lang->executionnode->confirmShutdown)?>
<?php js::set('actionSuccess', $lang->executionnode->actionSuccess)?>
<div id='mainMenu' class='clearfix'>
  <div class='pull-left btn-toolbar'>
    <?php echo html::a($this->createLink('executionnode', 'browse'), "<span class='text'>{$lang->executionnode->all}</span>", '', "class='btn btn-link btn-active-text'");?>
    <a href='#' id='bysearchTab' class='btn btn-link querybox-toggle'><i class='icon-search icon'></i>&nbsp;<?php echo $lang->executionnode->byQuery;?></a>
  </div>

  <?php if(common::hasPriv('executionnode', 'create')):?>
  <div class="btn-toolbar pull-right" id='createActionMenu'>
    <?php
    $misc = "class='btn btn-primary' data-width='600px'";
    $link = $this->createLink('executionnode', 'create');
    echo html::a($link, "<i class='icon icon-plus'></i>" . $lang->executionnode->create, '', $misc);
    ?>
  </div>
  <?php endif;?>
</div>
<div id='queryBox' class='cell <?php if($browseType =='bysearch') echo 'show';?>' data-module='executionnode'></div>
<div id='mainContent' class='main-table'>
<?php $vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
  <div class="table-responsive">
    <?php if(empty($nodeList)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->executionnode->empty;?></span>
        <?php if(common::hasPriv('executionnode', 'create')) common::printLink('executionnode', 'create', '', '<i class="icon icon-plus"></i> ' . $lang->executionnode->create, '', 'class="btn btn-info"');?>
      </p>
    </div>
    <?php else:?>
    <table class='table has-sort-head table-fixed' id='nodeList'>
      <thead>
        <tr>
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
          <th class='c-name'><?php common::printOrderLink('name', $orderBy, $vars, $lang->executionnode->name);?></th>
          <th class='c-ip'><?php common::printOrderLink('address', $orderBy, $vars, $lang->executionnode->ip);?></th>
          <th class='c-cpu'><?php common::printOrderLink('cpu', $orderBy, $vars, $lang->executionnode->cpu);?></th>
          <th class='c-number'><?php common::printOrderLink('memory', $orderBy, $vars, $lang->executionnode->memory);?></th>
          <th class='c-number'><?php common::printOrderLink('disk', $orderBy, $vars, $lang->executionnode->disk);?></th>
          <th class='c-os'><?php common::printOrderLink('os', $orderBy, $vars, $lang->executionnode->os);?></th>
          <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->executionnode->status);?></th>
          <th class='c-host'><?php common::printOrderLink('hostID', $orderBy, $vars, $lang->executionnode->hostName);?>
          <th class='c-actions-4'><?php echo $lang->actions?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($nodeList as $node):?>
        <tr>
          <td><?php echo $node->id;?></td>
          <td title="<?php echo $node->name;?>"><?php echo $node->name;?></td>
          <td><?php echo $node->hostIP;?></td>
          <td><?php echo zget($config->executionnode->os->cpu, $node->cpu);?></td>
          <td><?php echo $node->memory . $this->lang->zahost->unitList['GB'];?></td>
          <td><?php echo $node->disk . zget($this->lang->zahost->unitList, $node->unit);?></td>
          <td><?php echo zget($config->executionnode->os->list, $node->os);?></td>
          <td><?php echo zget($lang->executionnode->statusList, $node->status);?></td>
          <td title="<?php echo $node->hostName;?>"><?php echo $node->hostName;?></td>
          <td class='c-actions'>
            <?php
            $suspendAttr  = "title='{$lang->executionnode->suspend}' class='btn btn-primary' target='hiddenwin'";
            $suspendAttr .= $node->status == 'suspend' ? ' disabled' : " target='hiddenwin' onclick='if(confirm(\"{$lang->executionnode->confirmSuspend}\")==false) return false;'";

            $resumeAttr  = "title='{$lang->executionnode->resume}' class='btn btn-primary' target='hiddenwin'";
            $resumeAttr .= $node->status == 'running' ? 'disabled' : " target='hiddenwin' onclick='if(confirm(\"{$lang->executionnode->confirmResume}\")==false) return false;'";
            $rebootAttr  = "title='{$lang->executionnode->reboot}' class='btn btn-primary' target='hiddenwin'";
            $rebootAttr .= $node->status == 'suspend' ? ' disabled' : " target='hiddenwin' onclick='if(confirm(\"{$lang->executionnode->confirmReboot}\")==false) return false;'";
            common::printLink('executionnode', 'suspend', "executionnodeID={$node->id}", "<i class='icon icon-pause'></i> ", '', $suspendAttr);
            common::printLink('executionnode', 'resume', "executionnodeID={$node->id}", "<i class='icon icon-back'></i> ", '', $resumeAttr);
            common::printLink('executionnode', 'reboot', "executionnodeID={$node->id}", "<i class='icon icon-restart'></i> ", '', $rebootAttr);
            common::printLink('executionnode', 'destroy', "executionnodeID={$node->id}", "<i class='icon icon-trash'></i> ", '', "title='{$lang->executionnode->destroy}' class='btn btn-primary' target='hiddenwin' onclick='if(confirm(\"{$lang->executionnode->confirmDelete}\")==false) return false;'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <div class='table-footer'>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>

<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
