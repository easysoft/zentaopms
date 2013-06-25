<?php
/**
 * The browse view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(isonlybody()) die(include './caselist.html.php');?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/datepicker.html.php';
include '../../common/view/treeview.html.php';
include '../../common/view/colorize.html.php';
js::set('browseType', $browseType);
js::set('moduleID'  , $moduleID);
?>

<div id='featurebar'>
  <div class='f-left'>
    <?php
    echo "<span id='bymoduleTab' onclick=\"browseByModule('$browseType')\"><a href='#'>" . $lang->testcase->moduleCases . "</a></span> ";
    echo "<span id='allTab'>"         . html::a($this->createLink('testcase', 'browse', "productid=$productID&browseType=all&param=0&orderBy=$orderBy&recTotal=0&recPerPage=200"), $lang->testcase->allCases) . "</span>";
    echo "<span id='needconfirmTab'>" . html::a($this->createLink('testcase', 'browse', "productid=$productID&browseType=needconfirm&param=0"), $lang->testcase->needConfirm) . "</span>";
    echo "<span id='bysearchTab' onclick=\"browseBySearch('$browseType')\"><a href='#'><span class='icon-search'></span>{$lang->testcase->bySearch}</a></span> ";
    ?>
  </div>
  <div class='f-right'>
    <?php if($browseType != 'needconfirm') common::printIcon('testcase', 'export', "productID=$productID&orderBy=$orderBy"); ?>
    <?php common::printIcon('testcase', 'batchCreate', "productID=$productID&moduleID=$moduleID");?>
    <?php common::printIcon('testcase', 'create', "productID=$productID&moduleID=$moduleID"); ?>
  </div>
</div>
<div id='querybox' class='<?php if($browseType != 'bysearch') echo 'hidden';?>'></div>
<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side <?php echo $treeClass;?>'>
      <div class='box-title'><?php echo $productName;?></div>
      <div class='box-content'>
        <?php echo $moduleTree;?>
        <div class='a-right'>
          <?php common::printLink('tree', 'browse', "productID=$productID&view=case", $lang->tree->manage);?>
          <?php common::printLink('tree', 'fix',    "root=$productID&type=case", $lang->tree->fix, 'hiddenwin');?>
        </div>
      </div>
    </td>
    <td class='divider <?php echo $treeClass;?>'></td>
    <td>
      <form id='batchForm' method='post' action='<?php echo inLink('batchEdit', "from=testcaseBrowse&productID=$productID&orderBy=$orderBy");?>'>
      <?php include 'caselist.html.php';?>
      </form>
    </td>              
  </tr>              
</table>              
<?php include '../../common/view/footer.html.php';?>
