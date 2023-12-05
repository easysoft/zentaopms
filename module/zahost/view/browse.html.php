<?php
/**
 * The browse view file of host module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      pengjiangxiu <pengjiangxiu@cnezsoft.com>
 * @package     host
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->zahost->confirmDelete)?>
<?php js::set('browseType', $browseType);?>
<?php js::set('showFeature', $showFeature);?>
<?php js::set('webRoot', getWebRoot());?>
<div id='mainMenu' class='clearfix'>
  <div class='pull-left btn-toolbar'>
    <a href='#' class='btn btn-link querybox-toggle' id='bysearchTab'><i class='icon-search icon'></i> <?php echo $lang->zahost->byQuery;?></a>
    <a href='#' class='btn btn-link' id='helpTab'><i class='icon-help icon'></i> <?php echo $lang->help;?></a>
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
    <table class='table has-sort-head' id='hostTable'>
      <thead>
        <tr>
          <?php $vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
          <th class='c-id'>         <?php common::printOrderLink('id',              $orderBy, $vars, $lang->idAB);?></th>
          <th class='c-name'>       <?php common::printOrderLink('name',            $orderBy, $vars, $lang->zahost->name);?></th>
          <th class='c-type'>       <?php common::printOrderLink('type',            $orderBy, $vars, $lang->zahost->type);?></th>
          <th class='c-ip'>         <?php common::printOrderLink('extranet',         $orderBy, $vars, $lang->zahost->extranet);?></th>
          <th class='c-cpu'>        <?php common::printOrderLink('cpuCores',             $orderBy, $vars, $lang->zahost->cpuCores);?></th>
          <th class='c-memory'>     <?php common::printOrderLink('memory',          $orderBy, $vars, $lang->zahost->memory);?></th>
          <th class='c-diskSize'>   <?php common::printOrderLink('diskSize',            $orderBy, $vars, $lang->zahost->diskSize);?></th>
          <th class='c-software'>   <?php common::printOrderLink('vsoft', $orderBy, $vars, $lang->zahost->vsoft);?></th>
          <th class='c-status'>     <?php common::printOrderLink('status',       $orderBy, $vars, $lang->zahost->status);?></th>
          <th class='c-datetime'>   <?php common::printOrderLink('heartbeat',    $orderBy, $vars, $lang->zahost->registerDate);?></th>
          <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($hostList as $host):?>
        <tr class='text-left' data-status="<?php echo $host->status;?>">
          <td><?php printf('%03d', $host->hostID);?></td>
          <td title='<?php echo $host->name?>'><?php echo html::a($this->inlink('view', "id=$host->hostID"), $host->name, '', "");?></td>
          <td><?php echo zget($lang->zahost->zaHostTypeList, $host->hostType);?></td>
          <td><?php echo $host->extranet;?></td>
          <td><?php echo $host->cpuCores;?></td>
          <td><?php echo $host->memory . $lang->zahost->unitList['GB'];?></td>
          <td><?php echo $host->diskSize . $lang->zahost->unitList['GB'];?></td>
          <td><?php echo zget($lang->zahost->softwareList, $host->vsoft);?></td>
          <td class="status-<?php echo $host->status;?>"><?php echo zget($lang->host->statusList, $host->status);?></td>
          <td><?php echo helper::isZeroDate($host->heartbeat) ? '' : $host->heartbeat;?></td>
          <td class='c-actions'>
            <?php $disabled = ($host->status == 'wait') ? 'disabled' : '';?>
            <?php $title    = ($host->status == 'wait') ? $lang->zahost->uninitNotice: $lang->zahost->image->browseImage;?>
            <?php common::printIcon('zahost', 'browseImage', "hostID={$host->hostID}", $host, 'list', 'mirror', '', "iframe $disabled", true, "data-width='80%'", $title);?>
            <?php $disabled = !empty($nodeList[$host->hostID]) ? 'disabled' : '';?>
            <?php $title    = !empty($nodeList[$host->hostID]) ? $lang->zahost->undeletedNotice : $lang->zahost->delete;?>
            <?php common::printIcon('zahost', 'edit', "hostID={$host->hostID}", $host, 'list');?>
            <?php if(common::hasPriv('zahost', 'delete')) echo html::a($this->createLink('zahost', 'delete', "hostID={$host->id}"), '<i class="icon-trash"></i>', 'hiddenwin', "title='$title' class='btn $disabled'");;?>
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
