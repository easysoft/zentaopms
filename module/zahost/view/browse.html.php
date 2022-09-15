<?php
/**
 * The browse view file of host module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      pengjiangxiu <pengjiangxiu@cnezsoft.com>
 * @package     host
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->zahost->confirmDelete)?>
<?php js::set('browseType', $browseType)?>
<div id='mainMenu' class='clearfix'>
  <div class='pull-left btn-toolbar'>
    <a href='#' class='btn btn-link querybox-toggle' id='bysearchTab'><i class='icon-search icon'></i> <?php echo $lang->zahost->byQuery;?></a>
  </div>
  <?php if(common::hasPriv('zahost', 'create')):?>
  <div class="btn-toolbar pull-right" id='createActionMenu'>
    <?php
    $misc = "class='btn btn-primary'";
    $link = $this->createLink('zahost', 'create');
    echo html::a($link, "<i class='icon icon-plus'></i>" . $lang->zahost->create, '', $misc);
    ?>
  </div>
  <?php endif;?>
</div>
<div id='queryBox' class='cell <?php if($browseType =='bysearch') echo 'show';?>' data-module='zahost'></div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-table' id='hostList'>
    <?php if(empty($hostList)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->zahost->empty;?></span>
        <?php if(common::hasPriv('zahost', 'create')) common::printLink('zahost', 'create', '', '<i class="icon icon-plus"></i> ' . $lang->zahost->create, '', 'class="btn btn-info"');?>
      </p>
    </div>
    <?php else:?>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
          <th class='c-id'>         <?php common::printOrderLink('t1.id',           $orderBy, $vars, $lang->idAB);?></th>
          <th class='c-name'>       <?php common::printOrderLink('name',            $orderBy, $vars, $lang->zahost->name);?></th>
          <th class='c-type'>       <?php common::printOrderLink('t1.type',         $orderBy, $vars, $lang->zahost->type);?></th>
          <th class='c-ip'>         <?php common::printOrderLink('publicIP',        $orderBy, $vars, $lang->zahost->IP);?></th>
          <th class='c-cpu'>        <?php common::printOrderLink('cpuCores',        $orderBy, $vars, $lang->zahost->cpuCores);?></th>
          <th class='c-memory'>     <?php common::printOrderLink('memory',          $orderBy, $vars, $lang->zahost->memory);?></th>
          <th class='c-disk'>       <?php common::printOrderLink('diskSize',        $orderBy, $vars, $lang->zahost->diskSize);?></th>
          <th class='c-software'>   <?php common::printOrderLink('virtualSoftware', $orderBy, $vars, $lang->zahost->virtualSoftware);?></th>
          <th class='c-status'>     <?php common::printOrderLink('t2.status',       $orderBy, $vars, $lang->zahost->status);?></th>
          <th class='c-instanceNum'><?php common::printOrderLink('instanceNum',     $orderBy, $vars, $lang->zahost->instanceNum);?></th>
          <th class='c-datetime'>   <?php common::printOrderLink('registerDate',    $orderBy, $vars, $lang->zahost->registerDate);?></th>
          <th class='c-actions-2 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($hostList as $host):?>
        <tr class='text-left'>
          <td><?php printf('%03d', $host->id);?></td>
          <td title='<?php echo $host->name?>'><?php echo common::hasPriv('zahost', 'browsetemplate') ? html::a($this->inlink('browsetemplate', "id=$host->id"), $host->name) : $host->name;?></td>
          <td><?php echo zget($lang->zahost->zaHostTypeList, $host->hostType);?></td>
          <td><?php echo $host->publicIP;?></td>
          <td><?php echo $host->cpuCores;?></td>
          <td><?php echo $host->memory . $lang->zahost->unitList['GB'];?></td>
          <td><?php echo $host->diskSize . zget($lang->zahost->unitList, $host->unit);?></td>
          <td><?php echo $host->virtualSoftware;?></td>
          <td><?php echo zget($lang->host->statusList, $host->status);?></td>
          <td><?php echo $host->instanceNum;?></td>
          <td><?php echo $host->registerDate;?></td>
          <td class='c-actions'>
            <?php common::printIcon('zahost', 'edit', "hostID={$host->hostID}", $host, 'list');?>
            <?php if(common::hasPriv('zahost', 'delete')) echo html::a($this->createLink('zahost', 'delete', "hostID={$host->id}"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->zahost->delete}' class='btn'");;?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
