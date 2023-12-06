<?php
/**
 * The deny view file of editor module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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

