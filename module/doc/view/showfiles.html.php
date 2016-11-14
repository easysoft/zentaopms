<?php
/**
 * The showFiles view file of doc module of ZenTaoPMS.
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
<div id='featurebar'>
  <strong><?php echo $lang->doclib->files?></strong>
  <div class='actions'><?php echo html::backButton();?></div>
</div>
<div class='lib-files cards'>
  <?php foreach($files as $file):?>
  <?php if(empty($file->pathname)) continue;?>
  <div class='col-md-3'>
    <div class='card lib-file'>
      <a class='media-wrapper' title='<?php echo $file->title  . '.' . $file->extension?>' target='_blank' href='<?php echo $this->createLink('file', 'download', "fileID=$file->id&mouse=left") ?>'>
        <?php
        $downloadLink = $this->createLink('file', 'download', "fileID=$file->id&mouse=left");
        if(in_array($file->extension, $config->file->imageExtensions))
        {
            echo "<div class='img-holder' style='background-image: url($file->webPath)'><img src='$file->webPath'/></div>";
        }
        else
        {
            $iconClass = 'icon-file';
            if(strpos('zip,tar,gz,bz2,rar', $file->extension) !== false) $iconClass = 'icon-file-archive';
            else if(strpos('csv,xls,xlsx', $file->extension) !== false) $iconClass = 'icon-file-excel';
            else if(strpos('doc,docx', $file->extension) !== false) $iconClass = 'icon-file-word';
            else if(strpos('ppt,pptx', $file->extension) !== false) $iconClass = 'icon-file-powerpoint';
            else if(strpos('pdf', $file->extension) !== false) $iconClass = 'icon-file-pdf';
            else if(strpos('mp3,ogg,wav', $file->extension) !== false) $iconClass = 'icon-file-audio';
            else if(strpos('avi,mp4,mov', $file->extension) !== false) $iconClass = 'icon-file-movie';
            else if(strpos('txt,md', $file->extension) !== false) $iconClass = 'icon-file-text-o';
            else if(strpos('html,htm', $file->extension) !== false) $iconClass = 'icon-globe';
            echo "<i class='icon-holder icon $iconClass'></i>";
        }
        ?>
        <i class='icon icon-download'></i>
      </a>
      <div class='card-heading'>
        <a href='<?php echo $this->createLink($file->objectType, 'view', "objectID=$file->objectID");?>'><?php echo $file->title . '.' . $file->extension . ' [' . strtoupper($file->objectType) . ' #' . $file->objectID . ']';?></a>
        <a href='<?php echo $this->createLink('file', 'delete', "fileID=$file->id"); ?>' target='hiddenwin' title='<?php echo $lang->delete?>' class='delete pull-right'><i class='icon icon-remove'></i></a>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<div class='clearfix pager-wrapper'><?php $pager->show();?></div>
<?php js::set('type', 'doc');?>
<?php include '../../common/view/footer.html.php';?>
