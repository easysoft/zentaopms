<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 1947 2011-06-29 11:58:03Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sparkline.html.php';?>
<?php css::import($defaultTheme . 'index.css',   $config->version);?>
<div class="row">
  <div class="col-md-8">
    <?php include './blockprojects.html.php';?>
    <?php include './blockproducts.html.php';?>
  </div>
  <div class="col-md-4">
    <?php if(common::hasPriv('company', 'dynamic')) include './blockdynamic.html.php';?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 col-sm-6">
    <?php include './blocktodoes.html.php';?>
  </div>
  <?php if($app->user->role and strpos('qa|qd', $app->user->role) !== false):?>
  <div class="col-md-4 col-sm-6">
    <?php include './blockbugs.html.php';?>
  </div>
  <div class="col-md-4 col-sm-6">
    <?php include './blocktasks.html.php';?>
  </div>
  <?php elseif($app->user->role and strpos('po|pd', $app->user->role) !== false):?>
  <div class="col-md-4 col-sm-6">
    <?php include './blockstories.html.php';?>
  </div>
  <div class="col-md-4 col-sm-6">
    <?php include './blockbugs.html.php';?>
  </div>
  <?php else:?>
  <div class="col-md-4 col-sm-6">
    <?php include './blocktasks.html.php';?>
  </div>
  <div class="col-md-4 col-sm-6">
    <?php include './blockbugs.html.php';?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>  
