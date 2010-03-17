<?php
/**
 * The linkcase view file of testtask module of ZenTaoMS.
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
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<script language="Javascript">
function checkall(checker)
{
    $('input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}
</script>
<div class='yui-d0'>
  <?php echo $searchForm;?>
  <form method='post'>
  <table class='table-1 colored tablesorter'>
    <caption><?php echo $lang->testtask->unlinkedCases;?></caption>
    <thead>
    <tr>
      <th><?php echo $lang->testcase->id;?></th>
      <th><?php echo $lang->testcase->pri;?></th>
      <th><?php echo $lang->testcase->title;?></th>
      <th><?php echo $lang->testcase->type;?></th>
      <th><?php echo $lang->testcase->openedBy;?></th>
      <th><?php echo $lang->testcase->status;?></th>
      <th class='w-50px'><nobr><?php echo $lang->testtask->linkVersion;?></nobr></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($cases as $case):?>
    <tr class='a-center'>
      <td><?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$case->id"), sprintf('%03d', $case->id));?></td>
      <td><?php echo $case->pri?></td>
      <td width='50%' class='a-left'>
        <?php
        echo $case->title . ' ( ';
        for($i = $case->version; $i >= 1; $i --)
        {
            echo html::a($this->createLink('testcase', 'view', "caseID=$case->id&version=$i"), "#$i", '_blank');
        }
        echo ')';
        ?>
      </td>
      <td><?php echo $lang->testcase->typeList[$case->type];?></td>
      <td><?php echo $users[$case->openedBy];?></td>
      <td><?php echo $lang->testcase->statusList[$case->status];?></td>
      <td class='a-left'><nobr>
        <input type='checkbox' name='cases[]' value='<?php echo $case->id;?>' />
        <?php echo html::select('versions[]', array_combine(range($case->version, 1), range($case->version, 1)), '', 'style=width:50px');?></nobr>
      </td>
    </tr>
    </tbody>
    <?php endforeach;?>
    <tr class='a-center'>
      <td colspan='6'><?php echo html::submitButton();?></td>
      <td class='a-left'><input type='checkbox' onclick='checkall(this);'><?php echo $lang->selectAll;?></td>
    </tr>
  </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
