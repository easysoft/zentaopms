<?php
/**
 * The report view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $lang->bug->report->select;?></div>
      <div class='box-content'>
        <form method='post'>
        <?php echo html::checkBox('charts', $lang->bug->report->charts, $checkedCharts);?>
        <?php echo html::selectAll();?>
        <?php echo html::selectReverse(); ?>
        <br /><br />
        <?php echo html::submitButton($lang->bug->report->create);?>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1'>
        <caption>
          <div class='f-left'><?php echo $lang->bug->report->common;?></div>
          <div class='f-right'><?php echo html::a($this->createLink('bug', 'browse', "productID=$productID&browseType=$browseType&moduleID=$moduleID"), $lang->goback);?></div>
        </caption>
        <?php foreach($charts as $chartType => $chartContent):?>
        <tr valign='top'>
          <td><?php echo $chartContent;?></td>
          <td width='300'>
            <?php $height = zget($lang->bug->report->$chartType, 'height', $lang->bug->report->options->height) . 'px'; ?>
            <div style="height:<?php echo $height;?>; overflow:auto">
              <table class='table-1 colored'>
                <caption><?php echo $lang->bug->report->charts[$chartType];?></caption>
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
            </div>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
    </td>
  </tr>
</table>
<?php echo $renderJS;?>
<?php include '../../common/view/footer.html.php';?>
