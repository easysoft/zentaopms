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
      <tr class="permission-row">
        <td class="flex-sm text-center"> 首页 </td>
        <td class="flex-sm text-center"> 首页 </td>
        <td class="flex-content">
          <div class="group-item">
            <div class="checkbox-primary">
              <input type="checkbox" name="actions[my][]" value="index" checked="checked" title="地盘仪表盘" id="actions[my]index"></input>
              <label for="actions[my]index"> 地盘仪表盘 </label>
            </div>
          </div>
        </td>
      </tr>
      <tr class="permission-row">
        <td class="flex-sm text-center"> 地盘 </td>
        <td class="flex-sm text-center"> 地盘</td>
        <td class="flex-content sorter-group">
          <div class="group-item">
            <div class="checkbox-primary">
              <input type="checkbox" name="actions[my][]" value="index" checked="checked" title="地盘仪表盘" id="actions[my]index"></input>
              <label for="actions[my]index"> 地盘仪表盘0 </label>
            </div>
          </div> 
          <div class="group-item">
            <div class="checkbox-primary">
              <input type="checkbox" name="actions[my][]" value="index" checked="checked" title="地盘仪表盘" id="actions[my]index"></input>
              <label for="actions[my]index"> 地盘仪表盘1 </label>
            </div>
          </div> 
          <div class="group-item">
            <div class="checkbox-primary">
              <input type="checkbox" name="actions[my][]" value="index" checked="checked" title="地盘仪表盘" id="actions[my]index"></input>
              <label for="actions[my]index"> 地盘仪表盘2 </label>
            </div>
          </div> 
          <div class="group-item">
            <div class="checkbox-primary">
              <input type="checkbox" name="actions[my][]" value="index" checked="checked" title="地盘仪表盘" id="actions[my]index"></input>
              <label for="actions[my]index"> 地盘仪表盘3 </label>
            </div>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</form>

