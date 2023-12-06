<?php
/**
 * The browse view file of host module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      pengjiangxiu <pengjiangxiu@cnezsoft.com>
 * @package     host
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->host->confirmDelete)?>
<?php js::set('browseType', $browseType)?>
<div id='mainMenu' class='clearfix'>
  <div class='pull-left btn-toolbar'>
    <?php foreach($lang->host->featureBar['browse'] as $type => $name):?>
    <?php $class = $type == 'all' ? 'btn-active-text' : '';?>
    <?php echo html::a(inlink($type == 'all' ? 'browse' : 'treemap', $type == 'all' ? '' : "type=$type"), "<span class='text'>{$name}</span>", '', "class='btn btn-link $class' id='{$type}Tab'")?>
    <?php endforeach;?>
    <a href='#' class='btn btn-link querybox-toggle' id='bysearchTab'><i class='icon-search icon'></i> <?php echo $lang->host->byQuery;?></a>
  </div>
  <?php if(common::hasPriv('host', 'create')):?>
  <div class="btn-toolbar pull-right" id='createActionMenu'>
    <?php
    $misc = "class='btn btn-primary'";
    $link = $this->createLink('host', 'create');
    echo html::a($link, "<i class='icon icon-plus'></i>" . $lang->host->create, '', $misc);
    ?>
  </div>
  <?php endif;?>
</div>
<div id='queryBox' class='cell <?php if($browseType =='bysearch') echo 'show';?>' data-module='host'></div>

<div id='mainContent' class='main-row'>
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class='cell'>
      <div class='panel panel-sm'>
        <div class='panel-heading nobr'><strong><?php echo $lang->host->group;?></strong></div>
        <div class='panel-body'>
          <?php echo $moduleTree;?>
          <div class="text-center">
            <?php common::printLink('tree', 'browseHost', "moduleID=0", $lang->host->groupMaintenance, '', "class='btn btn-info btn-wide' data-group='ops'");?>
            <hr class="space-sm" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class='main-col main-table' id='hostList'>
    <?php if(empty($hostList)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->host->empty;?></span>
        <?php if(common::hasPriv('host', 'create')) common::printLink('host', 'create', '', '<i class="icon icon-plus"></i> ' . $lang->host->create, '', 'class="btn btn-info"');?>
      </p>
    </div>
    <?php else:?>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
          <th class='w-60px'>   <?php common::printOrderLink('id',     $orderBy, $vars, $lang->idAB);?></th>
          <th class='w-100px'>  <?php common::printOrderLink('group',     $orderBy, $vars, $lang->host->group);?></th>
          <th class='text-left'><?php common::printOrderLink('name',      $orderBy, $vars, $lang->host->name);?></th>
          <th class='text-left'><?php common::printOrderLink('admin',     $orderBy, $vars, $lang->host->admin);?></th>
          <th class='text-left'><?php common::printOrderLink('serverRoom',$orderBy, $vars, $lang->host->serverRoom);?></th>
          <th class='w-110px'>  <?php common::printOrderLink('intranet', $orderBy, $vars, $lang->host->intranet);?></th>
          <th class='w-110px'>  <?php common::printOrderLink('extranet',  $orderBy, $vars, $lang->host->extranet);?></th>
          <th class='w-100px'>  <?php common::printOrderLink('osVersion', $orderBy, $vars, $lang->host->osVersion);?></th>
          <th class='w-70px'>   <?php common::printOrderLink('t2.status', $orderBy, $vars, $lang->host->status);?></th>
          <th class='w-120px'>  <?php echo $lang->actions?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($hostList as $host):?>
        <tr class='text-left'>
          <td><?php printf('%03d', $host->id);?></td>
          <td class='hidden-xs hidden-sm' title='<?php echo zget($optionMenu, $host->group, '');?>'><?php echo zget($optionMenu, $host->group, '');?></td>
          <td title='<?php echo $host->name?>'><?php echo html::a($this->inlink('view', "id=$host->id", 'html', true), $host->name, '', "class='iframe'");?></td>

          <!-- show which account does the host belong to -->
          <?php $accountName = zget($accounts, $host->admin, "");?>
          <td title='<?php echo $accountName?>'><?php echo $accountName ? html::a($this->createLink('account', 'view', "id=$host->admin", 'html', true), $accountName, '', "class='iframe'") : ''?></td>

          <?php $serverRoomName = zget($rooms, $host->serverRoom, "");?>
          <td title='<?php echo $serverRoomName?>'><?php echo $serverRoomName ? html::a($this->createLink('serverroom', 'view', "id=$host->serverRoom", 'html', true), $serverRoomName, '', "class='iframe'") : ''?></td>
          <td><?php echo $host->intranet;?></td>
          <td><?php echo $host->extranet;?></td>
          <td class='hidden-xs hidden-sm'><?php echo $host->osName ? $lang->host->{$host->osName.'List'}[$host->osVersion] : '';?></td>
          <td class='hidden-xs hidden-sm'><?php echo $host->status ? $lang->host->statusList[$host->status] : '';?></td>
          <td class='c-actions'>
            <?php
            $icon  = $host->status == 'offline' ? '<i class="icon icon-arrow-up"></i>' : '<i class="icon icon-arrow-down"></i>';
            $title = $host->status == 'offline' ? $lang->host->online : $lang->host->offline;
            if (common::hasPriv('host', 'changeStatus', $host)) echo html::a($this->inlink('changeStatus', "id={$host->id}&hostID={$host->id}&status=$host->status", 'html', true), $icon, '', "class='btn iframe' title='{$title}'");

            common::printIcon('host','edit',"id=$host->id", $host, '', 'edit');
            if(common::hasPriv('host', 'delete', $host))
            {
                $deleteURL = $this->createLink('host', 'delete', "id=$host->id&confirm=yes");
                echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"hostList\",confirmDelete)", '<i class="icon-trash"></i>', '', "class='btn' title='{$lang->host->delete}'");
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<script>
$(function()
{
    <?php if($browseType == 'bymodule'):?>
    $('#module<?php echo $param?>').closest('li').addClass('active');
    <?php endif;?>
    if(browseType == 'bysearch') $.toggleQueryBox(true);
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
