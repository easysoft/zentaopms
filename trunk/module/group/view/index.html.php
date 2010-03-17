<?php
/**
 * The index view file of group module of ZenTaoMS.
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
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class="yui-d0 yui-t2">                 
  <div class="yui-b a-center">
    <table class='table-1'>
      <caption><?php echo $lang->group->global;?></caption>
      <tr>
        <td>
          <?php echo html::a($this->createLink('company', 'browse'), $lang->company->browse);?><br />
          <?php echo html::a($this->createLink('company', 'create'), $lang->company->create);?><br />
          <?php echo html::a($this->createLink('company', 'edit'), $lang->company->edit);?><br />
        </td>
      </tr>
    </table>
    <table class='table-1'>
      <caption><?php echo $lang->group->user;?></caption>
      <tr>
        <td>
          <?php echo html::a($this->createLink('user', 'browse'), $lang->user->browse);?><br />
          <?php echo html::a($this->createLink('user', 'create'), $lang->user->create);?><br />
          <?php echo html::a($this->createLink('group', 'browse'), $lang->group->browse);?><br />
          <?php echo html::a($this->createLink('group', 'create'), $lang->group->create);?><br />
        </td>
      </tr>
    </table>
  </div>
  <div class="yui-main">
    <div class="yui-b">
      <table align='center' class='table-1'>
        <caption><?php echo $lang->group->index;?></caption>
      </table>
    </div>
  </div>
</div>  
<?php include '../../common/view/footer.html.php';?>
