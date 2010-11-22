<?php
/**
 * The browse view file of bug module of ZenTaoPMS.
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
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<?php include '../../common/view/table2csv.html.php';?>
<script language='Javascript'>
/* Browse by module. */
function browseByModule(active)
{
    $('#mainbox').addClass('yui-t1');
    $('#treebox').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#querybox').addClass('hidden');
    $('#' + active + 'Tab').removeClass('active');
}
/* Search bugs. */
function browseBySearch(active)
{
    $('#mainbox').removeClass('yui-t1');
    $('#treebox').addClass('hidden');
    $('#querybox').removeClass('hidden');
    $('#bymoduleTab').removeClass('active');
    $('#' + active + 'Tab').removeClass('active');
    $('#bysearchTab').addClass('active');
}

$(document).ready(function()
{
    $("a.iframe").colorbox({width:640, height:420, iframe:true, transition:'none'});
});
</script>

<div class='yui-d0'>
  <div id='featurebar'>
    <div class='f-left'>
      <?php
      echo "<span id='bymoduleTab' onclick=\"browseByModule('$browseType')\"><a href='#'>" . $lang->bug->moduleBugs . "</a></span> ";
      echo "<span id='assigntomeTab'>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=assignToMe&param=0"),    $lang->bug->assignToMe)    . "</span>";
      echo "<span id='openedbymeTab'>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=openedByMe&param=0"),    $lang->bug->openedByMe)    . "</span>";
      echo "<span id='resolvedbymeTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=resolvedByMe&param=0"),  $lang->bug->resolvedByMe)  . "</span>";
      echo "<span id='assigntonullTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=assignToNull&param=0"),  $lang->bug->assignToNull)  . "</span>";
      echo "<span id='longlifebugsTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=longLifeBugs&param=0"),  $lang->bug->longLifeBugs)  . "</span>";
      echo "<span id='postponedbugsTab'>" . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=postponedBugs&param=0"), $lang->bug->postponedBugs) . "</span>";
      echo "<span id='allTab'>"         . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=all&param=0&orderBy=$orderBy&recTotal=0&recPerPage=200"), $lang->bug->allBugs) . "</span>";
      echo "<span id='needconfirmTab'>" . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=needconfirm&param=0"), $lang->bug->needConfirm) . "</span>";
      echo "<span id='bysearchTab' onclick=\"browseBySearch('$browseType')\"><a href='#'>{$lang->bug->byQuery}</a></span> ";
      ?>
    </div>
    <div class='f-right'>
      <?php common::printLink('bug', 'customFields', '', $lang->bug->customFields, '', "class='iframe'"); ?>
      <?php echo html::export2csv($lang->exportCSV, $lang->setFileName);?>
      <?php common::printLink('bug', 'report', "productID=$productID&browseType=$browseType&moduleID=$moduleID", $lang->bug->report->common); ?>
      <?php common::printLink('bug', 'create', "productID=$productID&extra=moduleID=$moduleID", $lang->bug->create); ?>
    </div>
  </div>
  <div id='querybox' class='<?php if($browseType !='bysearch') echo 'hidden';?>'><?php echo $searchForm;?></div>
</div>

<?php if($customed){include 'browse.custom.html.php'; exit;}?>
<div class='yui-d0 <?php if($browseType == 'bymodule') echo 'yui-t1';?>' id='mainbox'>

  <div class="yui-main">
    <div class="yui-b">
      <?php $vars = "productID=$productID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <table class='table-1 fixed colored tablesorter datatable'>
        <thead>
        <tr class='colhead'>
          <th class='w-id'>      <?php common::printOrderLink('id',       $orderBy, $vars, $lang->idAB);?></th>
          <th class='w-severity'><?php common::printOrderLink('severity', $orderBy, $vars, $lang->bug->severityAB);?></th>
          <th class='w-pri'>     <?php common::printOrderLink('pri',      $orderBy, $vars, $lang->priAB);?></th>
          <th><?php common::printOrderLink('title', $orderBy, $vars, $lang->bug->title);?></th>
          <?php if($browseType == 'needconfirm'):?>
          <th class='w-p40'><?php common::printOrderLink('story', $orderBy, $vars, $lang->bug->story);?></th>
          <th class='w-50px'><?php echo $lang->actions;?></th>
          <?php else:?>
          <th class='w-user'><?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='w-user'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
          <th class='w-user'><?php common::printOrderLink('resolvedBy', $orderBy, $vars, $lang->bug->resolvedByAB);?></th>
          <th class='w-resolution'><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->resolutionAB);?></th>
          <th class='w-120px {sorter:false}'><?php echo $lang->actions;?></th>
          <?php endif;?>
        </tr>
        </thead>
        <tbody>
        <?php foreach($bugs as $bug):?>
        <?php $bugLink = inlink('view', "bugID=$bug->id");?>
        <tr class='a-center'>
          <td class='linkbox'><?php echo html::a($bugLink, sprintf('%03d', $bug->id));?></td>
          <td><?php echo $lang->bug->severityList[$bug->severity]?></td>
          <td><?php echo $lang->bug->priList[$bug->pri]?></td>
          <td class='a-left nobr'><?php echo html::a($bugLink, $bug->title);?></td>
          <?php if($browseType == 'needconfirm'):?>
          <td class='a-left nobr'><?php echo html::a($this->createLink('story', 'view', "stoyID=$bug->story"), $bug->storyTitle, '_blank');?></td>
          <td><?php echo html::a(inlink('confirmStoryChange', "bugID=$bug->id"), $lang->confirm, 'hiddenwin')?></td>
          <?php else:?>
          <td><?php echo $users[$bug->openedBy];?></td>
          <td <?php if($bug->assignedTo == $this->app->user->account) echo 'class="red"';?>><?php echo $users[$bug->assignedTo];?></td>
          <td><?php echo $users[$bug->resolvedBy];?></td>
          <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
          <td>
            <?php
            $params = "bugID=$bug->id";
            if(!($bug->status == 'active'   and common::printLink('bug', 'resolve', $params, $lang->bug->buttonResolve))) echo $lang->bug->buttonResolve . ' ';
            if(!($bug->status == 'resolved' and common::printLink('bug', 'close', $params, $lang->bug->buttonClose)))     echo $lang->bug->buttonClose . ' ';
            common::printLink('bug', 'edit', $params, $lang->bug->buttonEdit);
            ?>
          </td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php $pager->show();?>
    </div>
  </div>

  <div class='yui-b  <?php if($browseType != 'bymodule') echo 'hidden';?>' id='treebox'>
    <div class='box-title'><?php echo $productName;?></div>
    <div class='box-content'>
      <?php echo $moduleTree;?>
      <div class='a-right'>
        <?php if(common::hasPriv('tree', 'browse')) echo html::a($this->createLink('tree', 'browse', "productID=$productID&view=bug"), $lang->tree->manage);?>
      </div>
    </div>
  </div>

</div>  
<script language='javascript'>
$("#<?php echo $browseType;?>Tab").addClass('active'); 
$("#module<?php echo $moduleID;?>").addClass('active'); 
</script>
<?php include '../../common/view/footer.html.php';?>
