<?php
/**
 * The checkExtension view file of upgrade module of ZenTaoPMS.
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
  <form method='post'>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <strong><?php echo $lang->upgrade->checkExtension;?></strong>
      </div>
      <div class='modal-body'>
      <?php echo $data;?>
      </div>
      <div class='modal-footer'><?php echo html::a(inlink('selectVersion'), $this->lang->upgrade->continue, '', "class='btn btn-wide'");?></div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
