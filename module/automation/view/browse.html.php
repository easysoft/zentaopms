<?php
/**
 * The view file of automation module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     automation
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class="row">
    <div class="col-xs-3">
      <ul class="nav nav-tabs nav-stacked">
        <li class="active"><a href="###" data-target="#tab3Content1" data-toggle="tab"><?php echo $lang->automation->description;?></a></li>
        <li><a href="###" data-target="#tab3Content2" data-toggle="tab"><?php echo $lang->automation->ztf;?></a></li>
        <li><a href="###" data-target="#tab3Content3" data-toggle="tab"><?php echo $lang->automation->zendata;?></a></li>
      </ul>
    </div>
    <div class="col-xs-9">
      <div class="tab-content col-xs-9">
        <div class="tab-pane fade active in" id="tab3Content1">
          <p>我是标签1。</p>
        </div>
        <div class="tab-pane fade" id="tab3Content2">
          <p>标签2的内容。</p>
        </div>
        <div class="tab-pane fade" id="tab3Content3">
          <p>这是标签3的内容。</p>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
