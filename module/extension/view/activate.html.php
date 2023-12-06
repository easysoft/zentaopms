<?php
/**
 * The deactivate view file of extension module of ZenTaoPMS.
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
      </h2>
    </div>
    <?php if(isset($error) and $error):?>
    <div class='text-center'>
      <div class='text-danger'><?php $error;?></div>
    </div>
    <?php else:?>
    <div class='text-center with-padding'>
      <div class='content'>
        <h3 class='text-success'><?php echo $title;?></h3>
        <hr>
        <p class='text-center'><?php echo html::commonButton($lang->extension->viewInstalled, 'onclick=parent.location.href="' . inlink('browse', 'type=installed') . '"');?></p>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
</body>
</html>
