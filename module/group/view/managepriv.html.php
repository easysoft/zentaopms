<?php
/**
 * The manage privilege view of group module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     group
 * @version     $Id: managepriv.html.php 1280 2009-09-07 05:41:14Z wwccss $
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<div id='doc3'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-1 a-left'> 
      <caption><?php echo $group->name . $lang->colon . $lang->group->managePriv;?></caption>
      <tr class='rowhead'>
        <th><?php echo $lang->group->module;?></th>
        <th><?php echo $lang->group->method;?></th>
      </tr>  
      <?php foreach($lang->resource as $moduleName => $moduleActions):?>
      <tr>
        <th class='rowhead'><?php echo $this->lang->$moduleName->common;?></th>
        <td>
        <?php foreach($moduleActions as $action):?>
        <input type='checkbox' name='actions[<?php echo $moduleName;?>][]' value='<?php echo $action;?>' <?php if(isset($groupPrivs[$moduleName][$action])) echo "checked";?> /> <?php echo $lang->$moduleName->$action;?>
        <?php endforeach;?>
        </td>
      </tr>
      <?php endforeach;?>
      <tr>
        <td colspan='3' class='a-center'><input type='submit' name='submit' /></td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/footer.html.php';?>
