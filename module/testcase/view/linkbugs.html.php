<?php
/**
 * The linkbugs view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     testcase
 * @version     $Id: linkbugs.html.php 4411 2016-03-09 11:02:04Z Chen Fei $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id="mainContent" class="main-content">
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $case->id;?></span>
      <?php echo html::a($this->createLink('case', 'view', "caseID={$case->id}"), $case->title, '_blank');?>
      <small class='text-muted'> <?php echo $lang->arrow . $lang->testcase->linkBugs;?></small>
    </h2>
  </div>
  <div id='queryBox' data-module='bug' class='show divider'></div>
  <?php if($bugs2Link):?>
  <form class='main-table' method='post' target='hiddenwin' id='linkBugsForm' data-ride='table'>
    <table class='table tablesorter' id='caseList'>
      <thead>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-pri' title=<?php echo $lang->pri;?>><?php echo $lang->priAB;?></th>
          <th><?php echo $lang->bug->title;?></th>
          <th class='c-type'><?php echo $lang->bug->type;?></th>
          <th class='c-user'><?php echo $lang->openedByAB;?></th>
          <th class='c-status'><?php echo $lang->statusAB;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $bugCount = 0;?>
        <?php foreach($bugs2Link as $bug2Link):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='bugs[]' value='<?php echo $bug2Link->id;?>' />
              <label></label>
            </div>
            <?php printf('%03d', $bug2Link->id);?>
          </td>
          <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bug2Link->pri, $bug2Link->pri)?>'><?php echo zget($lang->bug->priList, $bug2Link->pri, $bug2Link->pri)?></span></td>
          <td class='c-name' title='<?php echo $bug2Link->title;?>'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug2Link->id"), $bug2Link->title, '_blank');?></td>
          <td><?php echo zget($lang->bug->typeList, $bug2Link->type);?></td>
          <td><?php echo zget($users, $bug2Link->openedBy);?></td>
          <td class='status-<?php echo $bug2Link->status?>'><?php echo $this->bug->processStatus('bug', $bug2Link);?></td>
        </tr>
        <?php $bugCount ++;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar"><?php if($bugCount) echo html::submitButton('', '', 'btn btn-default');?></div>
      <?php echo html::hidden('case', $case->id);?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<script>
$(function()
{
    <?php if($bugs2Link):?>
    $('#linkBugsForm').table();
    setTimeout(function(){$('#linkBugsForm .table-footer').removeClass('fixed-footer');}, 100);
    <?php endif;?>

    $('#submit').click(function(){
        var output = '';
        $('#linkBugsForm').find('tr.checked').each(function(){
            var bugID    = $(this).find('td.c-id').find('div.checkbox-primary input').attr('value');
            var bugTitle = "#" + bugID + ' ' + $(this).find('td').eq(2).attr('title');
            var checkbox = "<li title='" + bugTitle + "'><div class='checkbox-primary'><input type='checkbox' checked='checked' name='linkBug[]' " + "value=" + bugID + " /><label class='linkBugTitle'>" + bugTitle + "</label></div></li>";

            output += checkbox;
        });
        $.closeModal();
        parent.$('#linkBugBox').html(output);
        parent.$('#linkBugBox').closest('tr').removeClass('hide');
        return false;
    });
});
</script>
<?php include '../../common/view/footer.html.php';?>
