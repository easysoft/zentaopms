<?php
/**
 * The manage view file of branch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     branch
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class="center-block mw-700px">
    <div class="main-header">
      <h2><?php printf($lang->branch->manageTitle, $this->lang->product->branchName[$this->session->currentProductType])?></h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class="table table-form">
        <tr>
          <td class='w-50px'></td>
          <td class="w-300px">
            <div id='branches'>
              <?php foreach($branches as $branchID => $branch):?>
              <div class='input-group' data-id='<?php echo $branchID?>'>
                <?php echo html::input("branch[$branchID]", $branch, "class='form-control'")?>
                <?php if(common::hasPriv('branch', 'sort')):?>
                <span class='input-group-addon sort-handler'><a><i class='icon icon-move'></i></a></span>
                <?php endif;?>
                <?php if(common::hasPriv('branch', 'delete')):?>
                <span class='input-group-addon'><?php echo html::a(inlink('delete', "branchID=$branchID"), "<i class='icon icon-close'></i>", 'hiddenwin')?></span>
                <?php endif;?>
              </div>
              <?php endforeach;?>
             </div>
             <div id='newbranches'>
              <?php for($i = 0; $i < 2; $i++):?>
              <div class='input-group'>
                <?php echo html::input("newbranch[]", '', "class='form-control'")?>
                 <span class='input-group-addon'><a href='javascript:;' onclick='addItem(this)'><i class='icon icon-plus'></i></a></span>
                 <span class='input-group-addon'><a href='javascript:;' onclick='deleteItem(this)'><i class='icon icon-close'></i></a></span>
              </div>
              <?php endfor;?>
             </div>
           </td>
          <td></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center'><?php echo html::submitButton();?> </td><td></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
