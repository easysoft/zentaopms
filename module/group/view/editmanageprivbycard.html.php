<?php
/**
* The editmanageprivbycard view file of group module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
        <th class="flex-sm">模块</th>
        <th class="flex-sm">权限包</th>
        <th class="flex-content">权限</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($privList as $module => $packages):?>
      <tr class="permission-row">
        <td class="flex-sm text-center" rowspan="<?php echo count($packages);?>"><?php echo zget($moduleLang, $module, $module);?></td>
        <?php foreach($packages as $packageID => $privs):?>
        <td class="flex-sm text-center"><?php echo zget($privPackages, $packageID, $lang->group->unassigned);?></td>
        <td class="flex-content sorter-group">
          <?php foreach($privs as $privID => $priv):?>
          <div class="group-item">
            <div class="checkbox-primary">
              <?php $action = $priv->methodName;?>
              <?php $label  = zget($privLang, $privID, $action);?>
              <?php echo html::checkbox("actions[$module][]", array($action => $label), $action, "title='{$label}' id='actions[$module]$action' data-id='$privID'");?>
            </div>
          </div>
          <?php endforeach;?>
        </td>
        <?php endforeach;?>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</form>

