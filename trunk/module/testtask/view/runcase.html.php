<?php
/**
 * The runrun view file of testtask of ZenTaoMS.
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
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.lite.html.php';?>
<div class='yui-d0'>
  <form method='post'>
  <table class='table-1 bd-1px'>
    <caption>CASE#<?php echo $run->case->id. $lang->colon . $run->case->title;?></caption>
    <tr>
      <th class='w-30px'><?php echo $lang->testcase->stepID;?></th>
      <th class='w-p40'><?php  echo $lang->testcase->stepDesc;?></th>
      <th class='w-p20'><?php  echo $lang->testcase->stepExpect;?></th>
      <th class='w-100px'><?php echo $lang->testcase->result;?></th>
      <th><?php echo $lang->testcase->real;?></th>
    </tr>
    <?php foreach($run->case->steps as $key => $step):?>
    <?php $defaultResult = $step->expect ? 'pass' : 'n/a';?>
    <tr>
      <th><?php echo $key + 1;?></th>
      <td><?php echo nl2br($step->desc);?></td>
      <td><?php echo nl2br($step->expect);?></td>
      <td class='a-center'><?php echo html::select("steps[$step->id]", $lang->testcase->resultList, $defaultResult);?></td>
      <td><?php echo html::textarea("reals[$step->id]", '', "rows=3 class='area-1'");?></td>
    </tr>
    <?php endforeach;?>
    <tr class='a-center'>
      <td colspan='5'>
        <?php
        echo html::submitButton();
        echo html::submitButton($lang->testtask->passAll, "onclick=$('#passall').val(1)");
        echo html::hidden('case', $run->case->id);
        echo html::hidden('version', $run->case->version);
        echo html::hidden('passall', 0);
        ?>
      </td>
    </tr>
  </table>
  </form>
</div>
