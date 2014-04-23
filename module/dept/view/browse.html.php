<?php
/**
 * The browse view file of dept module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: browse.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<div id='titlebar'>
  <div class='heading'><?php echo html::icon($lang->icons['dept']);?> <?php echo $lang->dept->common;?></div>
</div>
<div class='main'>
  <div class='row'>
    <div class='col-sm-4'>
      <div class='panel panel-sm'>
        <div class='panel-heading'>
          <?php echo html::icon($lang->icons['dept']);?> <strong><?php echo $title;?></strong>
        </div>
        <div class='panel-body'>
          <form method='post' target='hiddenwin' action='<?php echo $this->createLink('dept', 'updateOrder');?>'>
            <div id='main'><?php echo $depts;?></div>
            <div class='text-center'><?php echo html::submitButton($lang->dept->updateOrder);?></div>
          </form>
        </div>
      </div>
    </div>
    <div class='col-sm-8'>
      <div class='panel panel-sm'>
        <div class='panel-heading'>
          <i class='icon-sitemap'></i> <strong><?php echo $lang->dept->manageChild;?></strong>
        </div>
        <div class='panel-body'>
          <form method='post' target='hiddenwin' action='<?php echo $this->createLink('dept', 'manageChild');?>' class='form-condensed'>
            <table class='table table-form'>
              <tr>
                <td>
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
                <td class='w-300px'> 
                  <?php
                  $maxOrder = 0;
                  foreach($sons as $sonDept)
                  {
                      if($sonDept->order > $maxOrder) $maxOrder = $sonDept->order;
                      echo html::input("depts[id$sonDept->id]", $sonDept->name, "class='form-control'");
                  }
                  for($i = 0; $i < DEPT::NEW_CHILD_COUNT ; $i ++) echo html::input("depts[]", '', "class='form-control'");
                 ?>
                </td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td>
                  <?php echo html::submitButton() . html::backButton() . html::hidden('maxOrder', $maxOrder);?>
                  <input type='hidden' value='<?php echo $deptID;?>' name='parentDeptID' />
                </td>
              </tr>
            </table>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
