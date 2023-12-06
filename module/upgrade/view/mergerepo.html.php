<?php
/**
 * The mergerepo view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        https://www.zentao.net
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
               <div class="checkbox-primary item" title="<?php echo $lang->selectAll?>">
                 <input type='checkbox' id='checkAllRepos'><label for='checkAllRepos'><strong><?php echo $lang->upgrade->repo;?></strong></label>
               </div>
              </div>
              <div class='lineGroup-body'>
              <?php echo html::checkBox("repoes", $repoes, "class='form-control'");?>
              </div>
            </div>
          </div>
          <div class='table-col divider strong'></div>
          <div class='table-col pgmWidth' id='programBox'>
            <div class='cell'>
              <table class='table table-form'>
                <tr>
                  <th class='w-70px'><?php echo $lang->upgrade->product;?></th>
                  <td><?php echo html::select("products[]", $products, '', "class='form-control chosen' multiple");?></td>
                </tr>
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
