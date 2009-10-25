<?php
/**
 * The browse view file of dept module of ZenTaoMS.
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
 * @package     dept
 * @version     $Id: browse.html.php 1451 2009-10-22 09:01:21Z wwccss $
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/treeview.html.php';?>
<div class="yui-d0 yui-t3">                 
  <div class="yui-b">
    <form method='post' target='hiddenwin' action='<?php echo $this->createLink('dept', 'updateOrder');?>'>
    <table class='table-1'>
      <caption>
        <?php echo $header['title'];?>
      </caption>
      <tr>
        <td>
          <div id='main'><?php echo $depts;?></div>
          <div class='a-center'>
            <input type='submit' value='<?php echo $lang->dept->updateOrder;?>' />
          </div>
        </td>
      </tr>
    </table>
    </form>
  </div>
  <div class="yui-main">
    <div class="yui-b">
    <form method='post' target='hiddenwin' action='<?php echo $this->createLink('dept', 'manageChild');?>'>
      <table align='center' class='table-1'>
        <caption><?php echo $lang->dept->manageChild;?></caption>
        <tr>
          <td width='10%'>
            <nobr>
            <?php
            echo html::a($this->createLink('dept', 'browse'), $this->app->company->name);
            echo $lang->arrow;
            foreach($parentDepts as $dept)
            {
                echo html::a($this->createLink('dept', 'browse', "deptID=$dept->id"), $dept->name);
                echo $lang->arrow;
            }
            ?>
            </nobr>
          </td>
          <td> 
            <?php
            foreach($sons as $sonDept) echo html::input("depts[id$sonDept->id]", $sonDept->name) . '<br />';
            for($i = 0; $i < DEPT::NEW_CHILD_COUNT ; $i ++) echo html::input("depts[]") . '<br />';
           ?>
          </td>
        </tr>
        <tr>
          <td class='a-center' colspan='2'>
            <?php echo html::submitButton() . html::resetButton();?>
            <input type='hidden' value='<?php echo $deptID;?>' name='parentDeptID' />
          </td>
        </tr>
      </table>
      </form>
    </div>
  </div>
</div>  
<?php include '../../common/footer.html.php';?>
