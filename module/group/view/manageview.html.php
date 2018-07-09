<?php
/**
 * The manage view by group view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     group
 * @version     $Id: managepriv.html.php 1517 2011-03-07 10:02:57Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2 title='<?php echo $group->name;?>'>
      <span class='label-id'><?php echo $group->id;?></span>
      <small> <?php echo $lang->arrow . $lang->group->manageView;?></small>
    </h2>
  </div>
  <form class="load-indicator main-form form-ajax" id="manageViewForm" method="post" target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='w-180px'>
          <?php echo $lang->group->viewList;?>
        </th>
        <td class='w-p60'>
          <?php foreach($lang->menu as $menu):?>
          <?php if(!is_string($menu)) continue;?>
          <?php list($moduleName, $module) = explode('|', $menu);?>
          <?php if($module == 'my') continue;?>
          <?php $moduleName = strip_tags($moduleName);?>
          <div class='group-item'>
            <div class='checkbox-primary'>
              <input type='checkbox' id='<?php echo $module?>' name='actions[views][<?php echo strtolower($module);?>]' value='<?php echo $module;?>' <?php if(isset($group->acl['views'][$module]) or empty($group->acl['views'])) echo "checked";?> />
              <label class='priv' for='<?php echo $module?>'>
                <?php echo $moduleName;?>
              </label>
            </div>
          </div>
        <?php endforeach;?>
          <div class='group-item'>
            <div class='checkbox-primary'>
              <input type="checkbox" id='allchecker' name="allchecker" onclick="selectAll(this, '', 'checkbox')" <?php if(empty($group->acl['views'])) echo "checked";?> />
              <label class='priv' for='allchecker'>
                <?php echo $lang->selectAll?>
              </label>
            </div>
          </div>
        </td>
        <td></td>
      </tr>
      <tr id='productBox' style='display:none'>
        <th class='text-right'><?php echo $lang->group->productList?></th>
        <td><?php echo html::select("actions[products][]", $products, isset($group->acl['products']) ? join(',', $group->acl['products']) : '', "class='form-control chosen' multiple")?></td>
        <td><?php echo $lang->group->noticeVisit?></td>
      </tr>
      <tr id='projectBox' style='display:none'>
        <th class='text-right'><?php echo $lang->group->projectList?></th>
        <td><?php echo html::select("actions[projects][]", $projects, isset($group->acl['projects']) ? join(',', $group->acl['projects']) : '', "class='form-control chosen' multiple")?></td>
        <td><?php echo $lang->group->noticeVisit?></td>
      </tr>
      <tr>
        <th><span class='hidden'><?php echo html::selectAll('', 'checkbox')?></span></th>
        <td colspan='2' class='form-actions'>
          <?php echo html::submitButton('', "onclick='setNoChecked()'", 'btn btn-wide btn-primary');?>
          <?php echo html::backButton('', '', 'btn btn-wide');?>
          <?php echo html::hidden('foo'); // Just a hidden var, to make sure $_POST is not empty.?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
