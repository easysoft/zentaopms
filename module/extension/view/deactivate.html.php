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
    <div class='with-padding'>
      <h3 class='mgb-20 text-center'><?php echo $title;?></h3>
      <?php if($removeCommands):?>
      <div class='container mw-500px'>
        <p><strong><?php echo $lang->extension->unremovedFiles;?></strong></p>
        <code><?php echo join('<br />', $removeCommands);?></code>
      </div>
      <?php endif;?>
      <hr>
      <p><?php echo html::commonButton($lang->extension->viewDeactivated, 'onclick=parent.location.href="' . inlink('browse', 'type=deactivated') . '"');?></p>
    </div>
  </div>
</div>
</body>
</html>
