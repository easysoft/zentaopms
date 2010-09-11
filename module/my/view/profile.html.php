<?php
/**
 * The profile view file of my module of ZenTaoMS.
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
 * @package     my
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
  <table align='center' class='table-4'>
    <caption><?php echo $lang->my->profile;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->user->account;?></th>
      <td><?php echo $user->account;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->user->realname;?></th>
      <td><?php echo $user->realname;?></td>
    </tr>
    <!--
    <tr>
      <?php //echo $lang->user->nickname;?>
      <?php //echo $user->nickname;?>
    </tr>
    -->
    <tr>
      <th class='rowhead'><?php echo $lang->user->email;?></th>
      <td><?php echo $user->email;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->user->join;?></th>
      <td><?php echo $user->join;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->user->visits;?></th>
      <td><?php echo $user->visits;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->user->ip;?></th>
      <td><?php echo $user->ip;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->user->last;?></th>
      <td><?php echo $user->last;?></td>
    </tr>
    <tr>
      <td colspan='2' class='a-center'>
        <?php 
        echo html::a($this->createLink('my', 'editprofile'), $lang->user->editProfile);
        echo html::a($this->createLink('user', 'logout'),    $lang->logout);
        ?>
      </td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
