<?php
/**
* The editmanageprivbycard view file of group module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
* @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Feilong Guo <guofeilong@easycorp.ltd>
* @package     group
* @version     $Id: editmanageprivbylist.html.php 4769 2021-07-23 11:16:21Z $
* @link        https://www.zentao.net
*/
?>

<form class="load-indicator main-form form-ajax" id="permissionEditForm" method="post" target='hiddenwin'>
  <table class='table table-hover table-striped table-bordered' id='privList'>
    <thead>
      <tr class="text-center permission-head">
        <th class="td-sm"><?php echo $lang->group->module?></th>
        <th class="td-sm"><?php echo $lang->privpackage->common?></th>
        <th class="flex-content"><?php echo $lang->group->acl?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($privList as $module => $packages):?>
      <?php if(!$this->group->checkMenuModule($view, $module)) continue;?>
      <?php $i = 1;?>
      <?php foreach($packages as $packageID => $privs):?>
      <tr class="permission-row">
        <?php if($i == 1):?>
        <td class="td-sm text-center" rowspan="<?php echo $i == 1 ? count($packages) : 1;?>"><?php echo zget($moduleLang, $module, $module);?></td>
        <?php endif;?>
        <?php $packageTdStyle = $i == 1 ? 'td-sm' : 'td-md';?>
        <td class="<?php echo $packageTdStyle;?> text-center"><?php echo zget($privPackages, $packageID, $lang->group->unassigned);?></td>
        <td class="flex-content sorter-group">
          <?php foreach($privs as $privID => $priv):?>
          <div class="group-item">
            <div class="checkbox-primary">
              <?php $action = $priv->method;?>
              <?php echo html::checkbox("actions[$module][]", array($action => $priv->name), '', "title='{$priv->name}' id='actions[$module]$action' data-id='$priv->id'");?>
            </div>
          </div>
          <?php endforeach;?>
        </td>
      </tr>
      <?php $i ++;?>
      <?php endforeach;?>
      <?php endforeach;?>
    </tbody>
  </table>
</form>
