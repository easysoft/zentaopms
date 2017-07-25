<?php
/**
 * The link bug view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: linkbug.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
$jsRoot = $this->app->getWebRoot() . "js/";
include '../../common/view/tablesorter.html.php';
?>
<div id='querybox' class='show'></div>
<div id='unlinkBugList'>
  <form method='post' id='unlinkedBugsForm' target='hiddenwin' action='<?php echo $this->createLink('productplan', 'linkBug', "planID=$plan->id&browseType=$browseType&param=$param&orderBy=$orderBy")?>'>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed table-selectable'> 
      <caption class='text-left text-special'><?php echo html::icon('unlink');?> &nbsp;<strong><?php echo $lang->productplan->unlinkedBugs;?></strong></caption>
      <thead>
      <tr class='colhead'>
        <th class='w-id {sorter:"currency"}'><?php echo $lang->idAB;?></th>
        <th class='w-pri'>   <?php echo $lang->priAB;?></th>
        <th>                 <?php echo $lang->bug->title;?></th>
        <th class='w-user'>  <?php echo $lang->openedByAB;?></th>
        <th class='w-user'>  <?php echo $lang->assignedToAB;?></th>
        <th class='w-status'><?php echo $lang->statusAB;?></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach($allBugs as $bug):?>
      <?php
      if(isset($planBugs[$bug->id])) continue;
      if($bug->plan and helper::diffDate($plans[$bug->plan], helper::today()) > 0) continue;
      ?>
      <tr>
        <td class='cell-id'>
          <input type='checkbox' name='bugs[]'  value='<?php echo $bug->id;?>'/> 
          <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id);?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bug->pri, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></span></td>
        <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), $bug->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
        <td class='text-center'><?php echo $users[$bug->openedBy];?></td>
        <td class='text-center'><?php echo $users[$bug->assignedTo];?></td>
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
              echo html::selectButton() . html::submitButton($lang->productplan->linkBug);
              echo html::a(inlink('view', "planID=$plan->id&type=bug&orderBy=$orderBy"), $lang->goback, '', "class='btn'");
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
