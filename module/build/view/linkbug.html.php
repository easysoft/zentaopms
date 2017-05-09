<?php
/**
 * The link bug view of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     build
 * @version     $Id: linkbug.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<div id='querybox' class='show'></div>
<div id='unlinkBugList'>
  <form method='post' id='unlinkedBugsForm' target='hiddenwin' action='<?php echo $this->createLink('build', 'linkBug', "buildID={$build->id}&browseType=$browseType&param=$param");?>'>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed table-selectable'> 
      <caption class='text-left text-special'><?php echo html::icon('unlink');?> &nbsp;<strong><?php echo $lang->productplan->unlinkedBugs;?></strong></caption>
      <thead>
      <tr class='colhead'>
        <th class='w-id {sorter:"currency"}'><?php echo $lang->idAB;?></th>
        <th class='w-pri'>   <?php echo $lang->priAB;?></th>
        <th>                 <?php echo $lang->bug->title;?></th>
        <th class='w-user'>  <?php echo $lang->openedByAB;?></th>
        <th class='w-150px'> <?php echo $lang->bug->resolvedBy;?></th>
        <th class='w-80px'>  <?php echo $lang->statusAB;?></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach($allBugs as $bug):?>
      <?php if(strpos(",{$build->bugs},", ",$bug->id,") !== false) continue;?>
      <?php if($build->product != $bug->product) continue; ?>
      <tr>
        <td class='cell-id'>
          <input type='checkbox' name='bugs[<?php echo $bug->id?>]'  value='<?php echo $bug->id;?>' <?php if($bug->status == 'resolved' or $bug->status == 'closed') echo "checked";?> /> 
          <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id);?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bug->pri, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></span></td>
        <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), $bug->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
        <td><?php echo $users[$bug->openedBy];?></td>
        <td style='overflow:visible;padding-top:1px;padding-bottom:1px;'><?php echo ($bug->status == 'resolved' or $bug->status == 'closed') ? $users[$bug->resolvedBy] : html::select("resolvedBy[{$bug->id}]", $users, $this->app->user->account, "class='form-control chosen'");?></td>
        <td class='text-center bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status];?></td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot>
      <tr>
        <td colspan='6' class='text-left'>
          <?php if(count($allBugs))
          {
              echo "<div class='table-actions clearfix'>";
              echo html::selectButton() . html::submitButton($lang->build->linkBug);
              echo html::a(inlink('view', "buildID={$build->id}&type=bug"), $lang->goback, '', "class='btn'");
              echo '</div>';
          }
          ?>
        </td>
      </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(function()
{
    ajaxGetSearchForm('#bugs .linkBox #querybox');
    setModal();
})
</script>
