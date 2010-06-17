<?php
/**
 * The report view file of bug module of ZenTaoMS.
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
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
span {display:block}
</style>
<div class='yui-d0'>
  <div id='featurebar'>
    <div class='f-left'><?php echo $lang->bug->report->common;?></div>
    <div class='f-right'><?php common::printLink('bug', 'browse', "productID=$productID&browseType=$browseType&moduleID=$moduleID", $lang->goback); ?></div>
  </div>
</div>

<div class='yui-d0 yui-t1'>
  <div class='yui-b'>
    <div class='box-title'><?php echo $lang->bug->report->select;?></div>
    <div class='box-content'>
      <form method='post'>
      <?php echo html::checkBox('charts', $lang->bug->report->charts, $checkedCharts);?>
      <div class='a-center'><?php echo html::submitButton($lang->bug->report->create);?></div>
      </form>
    </div>
  </div>

  <div class="yui-main">
    <div class="yui-b">
      <table class='table-1'>
        <caption><?php echo $lang->bug->report->common;?></caption>
        <?php foreach($charts as $chartType => $chartContent):?>
        <tr valign='top'>
          <td><?php echo $chartContent;?></td>
          <td width='300'>
            <table class='table-1'>
              <tr>
                <th><?php echo $lang->report->item;?></th>
                <th><?php echo $lang->report->value;?></th>
                <th><?php echo $lang->report->percent;?></th>
              </tr>
              <?php foreach($datas[$chartType] as $key => $data):?>
              <tr class='a-center'>
                <td><?php echo $data->name;?></td>
                <td><?php echo $data->value;?></td>
                <td><?php echo ($data->percent * 100) . '%';?></td>
              </tr>
              <?php endforeach;?>
            </table>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
  </div>
</div>
<?php echo $rendJS;?>
<?php include '../../common/view/footer.html.php';?>
