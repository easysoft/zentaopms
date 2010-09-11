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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<script language="Javascript">
function check(checker, module)
{
    $('#' + module + ' input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}

function checkall(checker)
{
    $('input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}
</script>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table class='table-1 a-left'> 
      <caption class='caption-tl'><?php echo $group->name . $lang->colon . $lang->group->managePriv;?></caption>
      <tr class='colhead'>
        <th><?php echo $lang->group->module;?></th>
        <th><?php echo $lang->group->method;?></th>
      </tr>  
      <?php foreach($lang->resource as $moduleName => $moduleActions):?>
      <tr class='f-14px <?php echo cycle('even, bg-yellow');?>'>
        <th class='a-right'><?php echo $this->lang->$moduleName->common;?> <input type='checkbox' onclick='check(this, "<?php echo $moduleName;?>")'></td>
        <td id='<?php echo $moduleName;?>' class='pv-10px'>
          <?php $i = 1;?>
          <?php foreach($moduleActions as $action => $actionLabel):?>
          <div class='w-p20 f-left'><input type='checkbox' name='actions[<?php echo $moduleName;?>][]' value='<?php echo $action;?>' <?php if(isset($groupPrivs[$moduleName][$action])) echo "checked";?> /> <?php echo $lang->$moduleName->$actionLabel;?></div>
          <?php if(($i %  4) == 0) echo "<div class='c-both'></div>"; $i ++;?>
          <?php endforeach;?>
        </td>
      </tr>
      <?php endforeach;?>
      <tr>
        <th class='rowhead'><?php echo $lang->group->checkall;?><input type='checkbox' onclick='checkall(this);'></th>
        <td class='a-center'><?php echo html::submitButton($lang->save, 'name="submit"') . html::linkButton($lang->goback, $this->createLink('group', 'browse'));?></td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
