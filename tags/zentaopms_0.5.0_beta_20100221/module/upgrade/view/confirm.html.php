<?php
/**
 * The html template file of confirm method of upgrade module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     upgrade
 * @version     $Id$
 */
?>
<?php include '../../common/header.lite.html.php';?>
<div class='yui-d0' style='margin-top:50px'>
  <form method='post' action='<?php echo inlink('execute');?>'>
  <table align='center' class='table-6 f-14px'>
    <caption><?php echo $lang->upgrade->confirm;?></caption>
    <tr>
      <td><textarea rows='20' class='area-1'><?php echo $confirm;?></textarea></td>
    <tr>
      <td class='a-center'><?php echo html::submitButton($lang->upgrade->sureExecute) . html::hidden('fromVersion', $fromVersion);?></td>
    </tr>
  </table>
  </form>
</div>
<?php include '../../common/footer.html.php';?>
