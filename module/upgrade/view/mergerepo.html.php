<?php
/**
 * The mergerepo view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <form method='post' target='hiddenwin'>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <strong><?php echo $lang->upgrade->mergeRepo;?></strong>
      </div>
      <div class='modal-body'>
        <div class='alert alert-info'>
        <?php echo $lang->upgrade->mergeRepoTips;?>
        </div>
        <div class='main-row'>
          <div class='table-col' id='source'>
            <div class='cell'>
              <div class='lineGroup-title'>
                <div class='item'><strong><?php echo $lang->upgrade->repo;?></strong></div>
              </div>
              <div class='lineGroup-body'>
              <?php echo html::checkBox("repos", $repos, "class='form-control'");?>
              </div>
            </div>
          </div>
          <div class='table-col divider strong'></div>
          <div class='table-col pgmWidth' id='programBox'>
            <div class='cell'>
              <table class='table table-form'>
                <?php if($products):?>
                <tr>
                  <th class='w-70px'><?php echo $lang->upgrade->product;?></th>
                  <td><?php echo html::select("products[]", $products, '', "class='form-control chosen' multiple");?></td>
                </tr>
                <?php else:?>
                <tr>
                  <th class='w-100px'><?php echo $lang->upgrade->existProgram;?></th>
                  <td><?php echo html::select("program", $programs, '', "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->upgrade->product;?></th>
                  <td><?php echo html::input("name", '', "class='form-control'");?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <td colspan='2' class='text-center form-actions'><?php echo html::submitButton();?></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
