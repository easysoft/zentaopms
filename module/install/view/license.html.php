<?php
/**
 * The html file of license method of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     install
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='modal-header'>
      <h3><?php echo $lang->install->license;?></h3>
    </div>
    <div class='modal-body'>
      <?php echo html::textarea('license', $license, "class='form-control mgb-10' rows='12'")?>
      <div class='text-left mgb-10'>
        <label class='checkbox-inline'><input type='checkbox' id='agree' checked='checked' /><?php echo $lang->agreement;?></label>
      </div>
    </div>
    <div class='modal-footer'>
      <?php echo html::a(inlink('step1'), $lang->install->next, '', "class='btn btn-primary btn-install btn-wide'");?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
