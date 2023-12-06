<?php
/**
 * The deny view file of editor module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<style>
html{height:100%}
body{height:100%;padding-bottom:0px;}
#mainContent{height:100%}
</style>
<div id='mainContent' class='main-content'>
  <div class='alert alert-warning'><?php echo $this->lang->editor->onlyLocalVisit;?></div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>

