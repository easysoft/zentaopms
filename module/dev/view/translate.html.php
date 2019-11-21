<?php
/**
 * The translate view file of dev module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     dev
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
#subNavbar li[data-id='dev'].active > a {font-weight: normal; color: #3c4353;}
</style>
<div id='mainContent' class='main-content'>
  <div class='alert alert-danger'><?php echo $lang->dev->noteTranslate;?></div>
</div>
<?php include '../../common/view/footer.html.php';?>
