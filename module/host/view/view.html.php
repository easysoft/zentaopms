<?php
/**
 * The view view file of host module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     host
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->host->view;?></h2>
  </div>
  <div class='main'>
    <div class='detail'>
      <table class='table table-striped'>
        <tr>
          <th class='thWidth'><?php echo $lang->host->name;?></th>
          <td><?php echo $host->name;?></td>
          <th></th><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->host->group;?></th>
          <td><?php echo $optionMenu[$host->group];?></td>
          <th><?php echo $lang->host->serverRoom;?></th>
          <td><?php echo zget($rooms, $host->serverRoom, "")?></td>
        </tr>
        <tr>
          <th><?php echo $lang->host->serverModel;?></th>
          <td><?php echo $host->serverModel;?></td>
          <th><?php echo $lang->host->hostType;?></th>
          <td><?php echo $lang->host->hostTypeList[$host->hostType];?></td>
        </tr>
        <tr>
          <th><?php echo $lang->host->cpuBrand;?></th>
          <td><?php echo $host->cpuBrand;?></td>
          <th><?php echo $lang->host->cpuModel;?></th>
          <td><?php echo $host->cpuModel;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->host->cpuNumber;?></th>
          <td><?php echo $host->cpuNumber;?></td>
          <th><?php echo $lang->host->cpuCores;?></th>
          <td><?php echo $host->cpuCores;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->host->memory;?></th>
          <td><?php if($host->memory) echo $host->memory . ' GB';?></td>
          <th><?php echo $lang->host->diskSize;?></th>
          <td><?php if($host->diskSize) echo $host->diskSize . ' GB';?></td>
        </tr>
        <tr>
          <th><?php echo $lang->host->intranet;?></th>
          <td><?php echo $host->intranet;?></td>
          <th><?php echo $lang->host->extranet;?></th>
          <td><?php echo $host->extranet;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->host->osName;?></th>
          <td><?php echo $host->osName;?></td>
          <th><?php echo $lang->host->osVersion;?></th>
          <td><?php echo $lang->host->{$host->osName.'List'}[$host->osVersion];?>
        </tr>
        <tr>
          <th><?php echo $lang->host->status;?></th>
          <td colspan='3'><?php echo $lang->host->statusList[$host->status];?></td>
        </tr>
      </table>
    </div>
    <?php include $app->getModuleRoot() . 'common/view/action.html.php'?>
  </div>
  <div id='mainActions' class='main-actions'>
    <nav class='container'></nav>
    <div class='btn-toolbar'>
      <?php
      if($host->deleted == 0)
      {
          common::printLink('host', 'edit', "id=$host->hostID&hostID=$host->hostID", "<i class='icon-edit'></i> " . $lang->edit, '', "class='btn'", '', '', $host);
          common::printLink('host', 'delete', "id=$host->hostID", "<i class='icon-trash'></i> " . $lang->delete, 'hiddenwin', "class='btn'", '', '', $host);
      }
      if(!isonlybody()) echo html::backButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', 'btn btn-secondary');
      ?>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
