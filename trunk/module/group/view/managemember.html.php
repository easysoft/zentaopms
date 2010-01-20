<?php
/**
 * The manage member view of group module of ZenTaoMS.
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
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<style>
#users input {display:block;float:left}
label{display:block; width:100px; float:left}
</style>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-1 a-left'> 
      <caption><?php echo $group->name . $lang->colon . $lang->group->manageMember;?></caption>
      <tr><td id='users'><?php echo html::checkbox('members', $allUsers, $groupUsers);?></td></tr>
      <tr><td class='a-center'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->createLink('group', 'browse'));?></td></tr>
    </table>
  </form>
</div>  
<?php include '../../common/footer.html.php';?>
