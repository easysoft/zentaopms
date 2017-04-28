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
  <div class='actions'>
    <form class='pull-left' method='get'>
      <?php
      if($this->config->requestType == 'GET')
      {
          echo html::hidden('m',          'doc');
          echo html::hidden('f',          'showFiles');
          echo html::hidden('type',       $type);
          echo html::hidden('objectID',   $object->id);
          echo html::hidden('recTotal',   isset($this->get->recTotal) ? $this->get->recTotal : 0);
          echo html::hidden('recPerPage', isset($this->get->recPerPage) ? $this->get->recPerPage : 0);
          echo html::hidden('pageID',     isset($this->get->pageID) ? $this->get->pageID : 0);
      }
      ?>
      <div class='input-group input-group-sm'>
        <?php echo html::input('title', $this->get->title, "class='form-control search-query' placeholder='{$lang->doc->fileTitle}'");?>
        <span class='input-group-btn'>
          <?php echo html::submitButton($lang->doc->search);?>
        </span>
      </div>
    </form>
    <?php echo html::backButton();?>
  </div>
</div>
<div class='lib-files cards'>
  <?php foreach($files as $file):?>
  <?php if(empty($file->pathname)) continue;?>
  <div class='col-md-3'>
    <div class='card lib-file'>
      <?php
      $imageWidth = 0;
      if(stripos('jpg|jpeg|gif|png|bmp', $file->extension) !== false and file_exists($file->realPath))
      {
          $imageSize  = getimagesize($file->realPath);
          $imageWidth = $imageSize ? $imageSize[0] : 0;
      }
      ?>
      <a class='media-wrapper' href='###' title='<?php echo $file->title  . '.' . $file->extension?>' target='_blank' onclick="return downloadFile(<?php echo $file->id?>, '<?php echo $file->extension?>', <?php echo $imageWidth?>)">
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
<script>
<?php
$sessionString  = $config->requestType == 'PATH_INFO' ? '?' : '&';
$sessionString .= session_name() . '=' . session_id();
?>
function downloadFile(fileID, extension, imageWidth)
{
    if(!fileID) return;
    var fileTypes     = 'txt,jpg,jpeg,gif,png,bmp';
    var sessionString = '<?php echo $sessionString;?>';
    var windowWidth   = $(window).width();
    var url           = createLink('file', 'download', 'fileID=' + fileID + '&mouse=left') + sessionString;
    width = (windowWidth > imageWidth) ? ((imageWidth < windowWidth*0.5) ? windowWidth*0.5 : imageWidth) : windowWidth;
    if(fileTypes.indexOf(extension) >= 0)
    {
        $('<a>').modalTrigger({url: url, type: 'iframe', width: width}).trigger('click');
    }
    else
    {
        window.open(url, '_blank');
    }
    return false;
}
</script>
<?php include '../../common/view/footer.html.php';?>
