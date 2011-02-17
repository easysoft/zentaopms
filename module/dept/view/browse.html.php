<?php
/**
 * The browse view file of dept module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<div class="yui-d0 yui-t3">                 
  <div class="yui-main">
    <div class="yui-b">
    <form method='post' target='hiddenwin' action='<?php echo $this->createLink('dept', 'manageChild');?>'>
      <table align='center' class='table-1'>
        <caption><?php echo $lang->dept->manageChild;?></caption>
        <tr>
          <td width='10%'>
            <nobr>
            <?php
            echo html::a($this->createLink('dept', 'browse'), $this->app->company->name);
            echo $lang->arrow;
            foreach($parentDepts as $dept)
            {
                echo html::a($this->createLink('dept', 'browse', "deptID=$dept->id"), $dept->name);
                echo $lang->arrow;
            }
            ?>
            </nobr>
          </td>
          <td> 
            <?php
            foreach($sons as $sonDept) echo html::input("depts[id$sonDept->id]", $sonDept->name) . '<br />';
            for($i = 0; $i < DEPT::NEW_CHILD_COUNT ; $i ++) echo html::input("depts[]") . '<br />';
           ?>
          </td>
        </tr>
        <tr>
          <td class='a-center' colspan='2'>
            <?php echo html::submitButton() . html::resetButton();?>
            <input type='hidden' value='<?php echo $deptID;?>' name='parentDeptID' />
          </td>
        </tr>
      </table>
      </form>
    </div>
  </div>

  <div class="yui-b">
    <form method='post' target='hiddenwin' action='<?php echo $this->createLink('dept', 'updateOrder');?>'>
    <table class='table-1'>
      <caption><?php echo $header->title;?></caption>
      <tr>
        <td>
          <div id='main'><?php echo $depts;?></div>
          <div class='a-center'><?php echo html::submitButton($lang->dept->updateOrder);?></div>
        </td>
      </tr>
    </table>
    </form>
  </div>

</div>  
<?php include '../../common/view/footer.html.php';?>
