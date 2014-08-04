<?php
/**
 * The structure view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='EXTENSION'><?php echo html::icon($lang->icons['extension']);?></span>
    <strong><?php echo $extension->name . '[' . $extension->code . '] ' .$lang->extension->structure . ':';?></strong>
  </div>
</div>
<div class='main with-padding'>
  <pre><?php
  $appRoot = $this->app->getAppRoot();
  $files   = json_decode($extension->files);
  foreach($files as $file => $md5) echo $appRoot . $file . "\n";
  ?></pre>  
</div>
<?php include '../../common/view/footer.lite.html.php';?>
