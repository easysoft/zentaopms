<?php
/**
 * The install view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='prefix' title='EXTENSION'><?php echo html::icon($lang->icons['extension']);?></span>
        <strong title='<?php echo $title;?>'><?php echo $title;?></strong>
      </h2>
    </div>
    <?php if($error):?>
    <div class='alert alert-danger with-icon'>
      <i class='icon-exclamation-sign'></i>
      <div class='content'>
        <h3><?php echo $lang->extension->waringInstall;?></h3>
        <p><?php echo $error;?></p>
        <p class='text-center'><?php echo html::commonButton($lang->extension->refreshPage, 'onclick=location.href=location.href', 'btn btn-primary');?></p>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
</body>
</html>
