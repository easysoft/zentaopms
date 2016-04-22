<?php
/**
 * The start view file of tutorial module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     tutorial
 * @version     $Id: browse.html.php 4728 2013-05-03 06:14:34Z sunhao@cnezsoft.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='start'>
  <div class='start-icon'><i class='icon icon-certificate icon-spin icon-back'></i><i class='icon icon-flag icon-front'></i></div>
  <h1><?php echo $lang->tutorial->common ?></h1>
  <p><?php echo $lang->tutorial->desc ?></p>
  <?php echo html::a(inlink('index'), $lang->tutorial->start, '_top', "class='btn btn-primary btn-lg'");;?>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
