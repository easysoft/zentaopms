<?php
/**
 * The objectLibs view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if($app->openApp == 'execution'):;?>
<style>.panel-body{min-height: 180px}</style>
<?php endif;?>
<div class="fade main-row <?php if($app->openApp == 'doc') echo 'split-row';?>" id="mainRow">
  <?php include './side.html.php';?>
  <div class="main-col" data-min-width="400">
    <?php if($docID):?>
    <?php include './content.html.php';?>
    <?php else:?>
    <div class="cell">
      <div class="detail no-padding">
      <?php echo $lang->doc->noDoc;?>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
<?php js::set('type', 'doc');?>
<?php include '../../common/view/footer.html.php';?>
