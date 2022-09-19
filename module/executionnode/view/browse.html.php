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
          <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
          <th class='w-200px'><?php common::printOrderLink('name', $orderBy, $vars, $lang->executionnode->name);?></th>
          <th class='w-100px'><?php common::printOrderLink('hostID', $orderBy, $vars, $lang->executionnode->hostName);?>
          <th class='w-120px'><?php common::printOrderLink('osType', $orderBy, $vars, $lang->executionnode->osType);?></th>
          <th class='w-100px'><?php common::printOrderLink('osCpu', $orderBy, $vars, $lang->executionnode->cpu);?></th>
          <th class='w-60px'><?php common::printOrderLink('osMemory', $orderBy, $vars, $lang->executionnode->memory);?></th>
          <th class='w-60px'><?php common::printOrderLink('osDisk', $orderBy, $vars, $lang->executionnode->disk);?></th>
          <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->executionnode->status);?></th>
          <th class='w-80px'><?php common::printOrderLink('createdBy', $orderBy, $vars, $lang->executionnode->creater);?></th>
          <th class='c-actions-5'><?php echo $lang->actions?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($nodeList as $node):?>
        <tr>
          <td><?php echo $node->id;?></td>
          <td title="<?php echo $node->name;?>"><?php echo html::a($this->inlink('view', "id=$node->id", 'html', true), $node->name, '',"class='iframe'");?></td>
          <td title="<?php echo $node->hostName;?>"><?php echo $node->hostName;?></td>
          <?php $osType = $config->executionnode->os->type[$node->osCategory][$node->osType] . ' ' . $lang->executionnode->versionList[$node->osType][$node->osVersion];?>
          <td title="<?php echo $osType;?>"><?php echo $osType;?></td>
          <td><?php echo zget($config->executionnode->os->cpu, $node->osCpu);?></td>
          <td><?php echo zget($config->executionnode->os->memory, $node->osMemory);?></td>
          <td><?php echo zget($config->executionnode->os->disk, $node->osDisk);?></td>
          <td><?php echo zget($lang->executionnode->statusList, $node->status);?></td>
          <td><?php echo zget($users, $node->createdBy);?></td>
          <td class='c-actions'>
            <?php
            $startClass = $node->status == 'running' ? 'disabled' : '';
            $stopClass  = $node->status == 'suspend' ? 'disabled' : '';
            common::printLink('executionnode', 'suspend', "executionnodeID={$node->id}", "<i class='icon icon-pause'></i> ", '', "title='{$lang->executionnode->suspend}' class='btn btn-primary' {$stopClass} target='hiddenwin' onclick='if(confirm(\"{$lang->executionnode->confirmSuspend}\")==false) return false;'");
            common::printLink('executionnode', 'resume', "executionnodeID={$node->id}", "<i class='icon icon-back'></i> ", '', "title='{$lang->executionnode->resume}' class='btn btn-primary' {$startClass} target='hiddenwin' onclick='if(confirm(\"{$lang->executionnode->confirmResume}\")==false) return false;'");
            common::printLink('executionnode', 'reboot', "executionnodeID={$node->id}", "<i class='icon icon-restart'></i> ", '', "title='{$lang->executionnode->reboot}' class='btn btn-primary' {$stopClass} target='hiddenwin' onclick='if(confirm(\"{$lang->executionnode->confirmReboot}\")==false) return false;'");
            common::printLink('executionnode', 'destroy', "executionnodeID={$node->id}", "<i class='icon icon-trash'></i> ", '', "title='{$lang->executionnode->destroy}' class='btn btn-primary' target='hiddenwin' onclick='if(confirm(\"{$lang->executionnode->confirmDelete}\")==false) return false;'");
            common::printIcon('executionnode', 'getVNC', "executionnodeID={$node->id}", '', 'list', 'link', 'hiddenwin', $stopClass);
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
