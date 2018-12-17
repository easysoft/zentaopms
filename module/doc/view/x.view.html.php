<?php
/**
 * The view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: view.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php echo css::internal($keTableCSS);?>
<?php $browseLink = $this->session->docList ? $this->session->docList : inlink('browse');?>
<style>
body{padding:0px;}
.xuanxuan-card{padding-bottom:55px;}
</style>
<div class='xuanxuan-card'>
  <div id="mainContent" class="main-row">
    <div class='panel-heading strong'>
      <span class="label label-id"><?php echo $doc->id;?></span> <span class="text" title='<?php echo $doc->title;?>'><?php echo $doc->title;?></span>
    </div>
  </div>
  <div class="main-col">
    <div class="cell">
      <div class="detail no-padding">
        <div class="detail-title"><?php echo $lang->doc->content;?></div>
          <div class="detail-content article-content no-margin no-padding">
            <?php echo $doc->content;?>
            <?php foreach($doc->files as $file):?>
            <?php if(in_array($file->extension, $config->file->imageExtensions)):?>
            <div class='file-image'>
              <img onload="setImageSize(this, 0)" src="<?php echo $this->createLink('file', 'read', "fileID={$file->id}");?>" alt="<?php echo $file->title?>">
            </div>
            <?php unset($doc->files[$file->id]);?>
            <?php endif;?>
            <?php endforeach;?>
          </div>
          <?php echo $this->fetch('file', 'printFiles', array('files' => $doc->files, 'fieldset' => 'true'));?>
        </div>
        <?php include '../../common/view/action.html.php';?>
      </div>
    </div>
  </div>
  <div class='xuancard-actions fixed'>
  <?php
  $url  = common::getSysURL() . $this->createLink('doc', 'edit', "docID=$doc->id");
  $url .= strpos($url, '?') === false ? '?' : '&';
  $url .= 'width=100%&height=100%';
  echo html::a('xxc:openUrlInDialog/' . urlencode($url), "<i class='icon-edit'></i>", '_blank', "class='btn btn-link'");
  ?>
  </div>
</div>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.lite.html.php';?>
