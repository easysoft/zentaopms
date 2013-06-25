<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(isonlybody())die(include './storylist.html.php'); ?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<script language='Javascript'>
var browseType = '<?php echo $browseType;?>';
</script>
<div id='featurebar'>
  <div class='f-left'>
    <span id='bymoduleTab' onclick='browseByModule()'><?php echo html::a($this->inlink('browse',"productID=$productID"), $lang->product->moduleStory);?></span>
    <span id='assignedtomeTab'> <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=assignedtome"), $lang->product->assignedToMe);?></span>
    <span id='openedbymeTab'>   <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=openedByMe"),   $lang->product->openedByMe);?></span>
    <span id='reviewedbymeTab'> <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=reviewedByMe"), $lang->product->reviewedByMe);?></span>
    <span id='closedbymeTab'>   <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=closedByMe"),   $lang->product->closedByMe);?></span>
    <span id='draftstoryTab'>   <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=draftStory"),   $lang->product->draftStory);?></span>
    <span id='activestoryTab'>  <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=activeStory"),  $lang->product->activeStory);?></span>
    <span id='changedstoryTab'> <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=changedStory"), $lang->product->changedStory);?></span>
    <span id='closedstoryTab'>  <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=closedStory"),  $lang->product->closedStory);?></span>
    <span id='allstoryTab'>     <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=allStory"),     $lang->product->allStory);?></span>
    <span id='bysearchTab' ><a href='#'><span class='icon-search'></span><?php echo $lang->product->searchStory;?></a></span>
  </div>
  <div class='f-right'>
    <?php common::printIcon('story', 'report', "productID=$productID&browseType=$browseType&moduleID=$moduleID");?>
    <?php common::printIcon('story', 'export', "productID=$productID&orderBy=$orderBy");?>
    <?php common::printIcon('story', 'batchCreate', "productID=$productID&moduleID=$moduleID");?>
    <?php common::printIcon('story', 'create', "productID=$productID&moduleID=$moduleID"); ?>
  </div>
</div>
<div id='querybox' class='<?php if($browseType !='bysearch') echo 'hidden';?>'></div>
<form method='post' id='productStoryForm'>
  <table class='cont-lt1'>
    <tr valign='top'>
      <td class='side <?php echo $treeClass;?>' id='treebox'>
        <div class='box-title'><?php echo $productName;?></div>
        <div class='box-content'>
          <?php echo $moduleTree;?>
          <div class='a-right'>
            <?php common::printLink('tree', 'browse', "rootID=$productID&view=story", $lang->tree->manage);?>
            <?php common::printLink('tree', 'fix',    "root=$productID&type=story", $lang->tree->fix, 'hiddenwin');?>
          </div>
        </div>
      </td>
      <td class='divider <?php echo $treeClass;?>'></td>
      <td>
        <?php include "./storylist.html.php";?>
      </td>              
    </tr>
  </table>
</form>
<script language='javascript'>
$('#module<?php echo $moduleID;?>').addClass('active')
$('#<?php echo $browseType;?>Tab').addClass('active')
</script>
<?php include '../../common/view/footer.html.php';?>
