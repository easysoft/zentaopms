<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 1947 2011-06-29 11:58:03Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sparkline.html.php';?>
<?php css::import($defaultTheme . 'index.css',   $config->version);?>

<div class='dashboard' id='myDashboard'></div>
<script>
$(function()
{
    $('#myDashboard').dashboard(
    {
        draggable: true,
        data:
        [
            // Test block config data, like
            // {
            //    id: block id
            //    colWidth: grid width
            //    url: the url to get block content
            //    content: default content
            // }
            {id: 1, colWidth: 6, content: "<div class='panel-heading'>Panel 1</div><div class='panel-body'><h1>block 1</h1></div>"},
            {id: 2, colWidth: 3, content: "<div class='panel-heading'>Panel 2</div><div class='panel-body'><h1>block 2</h1></div>"},
            {id: 3, colWidth: 3, content: "<div class='panel-heading'>Panel 3</div><div class='panel-body'><h1>block 3</h1></div>"},
            {id: 4, colWidth: 4, content: "<div class='panel-heading'>Panel 4</div><div class='panel-body'><h1>block 4</h1></div>"},
            {id: 5, colWidth: 8, content: "<div class='panel-heading'>Panel 5</div><div class='panel-body'><h1>block 5</h1></div>"},
        ]
    });
});
</script>

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
