<?php
/**
 * The html template file of select version method of upgrade module of ZenTaoMS.
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
  <form method='post' action='<?php echo inlink('confirm');?>'>
  <table align='center' class='table-5 f-14px'>
    <caption><?php echo $lang->upgrade->selectVersion;?></caption>
    <tr>
      <th class='w-p20 rowhead'><?php echo $lang->upgrade->fromVersion;?></th>
      <td><?php echo html::select('fromVersion', $lang->upgrade->fromVersions, $version) . $lang->upgrade->noteVersion;?></td>
    </tr>
    <tr>
      <th class='w-p20 rowhead'><?php echo $lang->upgrade->toVersion;?></th>
      <td><?php echo $config->version;?></td>
    </tr>
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton($lang->upgrade->common);?></td>
    </tr>
  </table>
  </form>
</div>
<?php include '../../common/footer.html.php';?>
