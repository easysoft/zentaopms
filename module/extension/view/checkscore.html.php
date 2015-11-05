<?php
/**
 * The install view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='EXTENSION'><?php echo html::icon($lang->icons['extension']);?></span>
    <strong><?php echo $title;?></strong>
  </div>
</div>
<?php if(isset($error) and $error):?>
<div class='alert alert-success with-icon'>
  <i class='icon-ok-sign'></i>
  <div class='content'>
    <h3><?php echo $lang->extension->needSorce;?></h3>
    <p><?php echo $error;?></p>
  </div>
</div>
<?php endif;?>
</body>
</html>
</body>
</html>
