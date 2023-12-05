<?php
/**
 * The detect view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <wwccss@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . 'message/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='mw-700px'>
    <form class='pdt-20' method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr><th style='width:140px'><?php echo $lang->mail->inputFromEmail; ?></th><td class='w-p50'><?php echo html::input('fromAddress', $fromAddress, "class='form-control'");?></td><td><?php echo html::submitButton($lang->mail->nextStep, '', 'btn btn-primary');?></td></tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
