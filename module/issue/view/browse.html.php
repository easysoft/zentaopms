<?php
/**
 * The batch close view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div>
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('issue', 'browse', 'browseType=all'), '<span class="text">' . $lang->issue->browse . '</span>', '', 'class="btn btn-link btn-active-text"');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('issue', 'create', '', "<i class='icon icon-plus'></i>" . $lang->issue->create, '', "class='btn btn-primary'");?>
    <?php common::printLink('issue', 'batchCreate', '', "<i class='icon icon-plus'></i>" . $lang->issue->batchCreate, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>

