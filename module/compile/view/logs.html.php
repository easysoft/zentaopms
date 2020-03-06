<?php
/**
 * The browse view file of compile module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     compile
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class="page-title">
      <strong><?php echo $lang->compile->logs;?></strong>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php echo html::a(helper::createLink('compile', "browse", "jobID=$build->job"), "<i class='icon icon-back icon-sm'></i> ". $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent'>
  <div class='main-content'><?php echo $logs;?></div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
