<?php
/**
 * The view file of GitLab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      dave.li  <lichengjun@cnezsoft.com>
 * @package     GitLab
 * @version     $Id: view.html.php 4728 2013-05-03 06:14:34Z david18810279601@gmail.com $
 * @link        http://www.zentao.net
 * */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('sysurl', common::getSysUrl());?>
<?php $browseLink = $app->session->gitlabList ? $app->session->gitlabList : inlink('browse');?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $gitlab->id?></span>
      <span class="text" title="<?php echo $gitlab->name;?>" style='color: #3c4354'><?php echo $gitlab->name;?></span>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <?php $this->printExtendFields($gitlab, 'div', "position=left&inForm=0&inCell=1");?>
    <div class='cell'><?php include '../../common/view/action.html.php';?></div>
    <?php
    $params        = "gitlabID=$gitlab->id";
    ?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!$gitlab->deleted):?>
        <div class='divider'></div>
        <?php
        common::printIcon('gitlab', 'delete', $params, $gitlab, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>

<div id="mainActions" class='main-actions'>
  <?php common::printPreAndNext($preAndNext);?>
</div>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>

