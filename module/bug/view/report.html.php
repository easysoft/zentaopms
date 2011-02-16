<?php
/**
 * The report view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>span {display:block}</style>
<script language='javascript'>
/* 全选 */
function checkAll()
{
    var checkOBJ = $('input');
    for(var i = 0; i < checkOBJ.length; i++)
    {
        checkOBJ.get(i).checked = true;
    }
}

/* 反选 */
function checkReverse()
{
    var checkOBJ = $('input');
    for(var i = 0; i < checkOBJ.length; i++)
    {
        checkOBJ.get(i).checked = !checkOBJ.get(i).checked;
    }
    return;
}
</script>

<div class='yui-d0'><div class='u-1'>
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
      <input type='button' value='<?php echo $lang->bug->report->selectAll;?>'     onclick='checkAll()' />
      <input type='button' value='<?php echo $lang->bug->report->selectReverse;?>' onclick='checkReverse()' />
      <br /><br />
      <?php echo html::submitButton($lang->bug->report->create);?>
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
<?php echo $renderJS;?>
<?php include '../../common/view/footer.html.php';?>
