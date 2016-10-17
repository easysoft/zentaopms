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
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $object->name?></strong>
  </div>
  <div class='panel-body cards'>
    <?php foreach($files as $file):?>
    <div class='col-md-3'>
      <div class='card' title='<?php echo $file->title . '.' . $file->extension?>'>
        <?php
        $downloadLink = $this->createLink('file', 'download', "fileID=$file->id&mouse=left");
        if(in_array($file->extension, $config->file->imageExtensions))
        {
            echo html::a($downloadLink, "<img src='$file->webPath' />", '_blank', "class='file'");
        }
        else
        {
            $iconClass = 'icon-file';
            if(strpos('zip,tar,gz,bz2,rar', $file->extension) !== false) $iconClass = 'icon-file-archive';
            if(strpos('csv,xls,xlsx', $file->extension) !== false) $iconClass = 'icon-file-excel';
            if(strpos('doc,docx', $file->extension) !== false) $iconClass = 'icon-file-word';
            if(strpos('ppt,pptx', $file->extension) !== false) $iconClass = 'icon-file-powerpoint';
            if(strpos('pdf', $file->extension) !== false) $iconClass = 'icon-file-pdf';
            echo html::a($downloadLink, "<i class='icon $iconClass'></i>", '_blank', "class='file'");
        }
        ?>
        <div class="card-heading"><strong><?php echo html::a($this->createLink($file->objectType, 'view', "objectID=$file->objectID"), $lang->doc->fileObject . ' ' . strtoupper($file->objectType) . ' #' . $file->objectID)?></strong></div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
</div>
<?php js::set('type', $type);?>
<?php include '../../common/view/footer.html.php';?>
