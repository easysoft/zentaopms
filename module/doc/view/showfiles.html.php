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
          echo html::hidden('viewType',   ($viewType == 'list' ? 'list' : 'card'));
          echo html::hidden('recTotal',   isset($this->get->recTotal) ? $this->get->recTotal : 0);
          echo html::hidden('recPerPage', isset($this->get->recPerPage) ? $this->get->recPerPage : 0);
          echo html::hidden('pageID',     isset($this->get->pageID) ? $this->get->pageID : 0);
      }
      ?>
      <div class='input-group input-group-sm w-200px'>
        <?php echo html::input('title', $this->get->title, "class='form-control search-query' placeholder='{$lang->doc->fileTitle}'");?>
        <span class='input-group-btn'>
          <?php echo html::submitButton($lang->doc->search);?>
        </span>
      </div>
    </form>
    <div class="btn-group">
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class='icon <?php echo $viewType == 'list' ? 'icon-list' : 'icon-th'?>'></i> <?php echo $lang->doc->browseTypeList[$viewType]?> <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
        <li><?php echo html::a(inlink('showFiles', "type=$type&objectID=$objectID&viewType=card"), "<i class='icon icon-th'></i> {$lang->doc->browseTypeList['card']}");?></li>
        <li><?php echo html::a(inlink('showFiles', "type=$type&objectID=$objectID&viewType=list"), "<i class='icon icon-list'></i> {$lang->doc->browseTypeList['list']}");?></li>
      </ul>
    </div>
    <?php echo html::backButton();?>
  </div>
</div>

<?php if($viewType == 'list'):?>
<table class='table table-hover table-striped tablesorter table-fixed' id='orderList'>
  <thead>
    <?php $vars = "type=$type&objectID=$objectID&viewType=list&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage";?>
    <tr class='text-center'>
      <th class='w-80px'>  <?php common::printOrderLink('t1.id',     $orderBy, $vars, $lang->doc->id);?>        </th>
      <th> <?php common::printOrderLink('title',    $orderBy, $vars, $lang->doc->fileTitle);?> </th>
      <th> <?php common::printOrderLink('pathname', $orderBy, $vars, $lang->doc->filePath);?>  </th>
      <th class='w-60px'>  <?php common::printOrderLink('extension', $orderBy, $vars, $lang->doc->extension);?> </th>
      <th class='w-80px'>  <?php common::printOrderLink('size',      $orderBy, $vars, $lang->doc->size);?>      </th>
      <th class='w-100px'> <?php common::printOrderLink('addedBy',   $orderBy, $vars, $lang->doc->addedBy);?>   </th>
      <th class='w-160px'> <?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->doc->addedDate);?> </th>
      <th class='w-80px'>  <?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($files as $file):?>
    <?php if(empty($file->pathname)) continue;?>
      <tr class='text-center text-middle'>
        <td><?php echo $file->id;?></td>
        <td class='text-left'>
          <a href='<?php echo $this->createLink($file->objectType, 'view', "objectID=$file->objectID");?>'><?php echo $file->title . '.' . $file->extension . ' [' . strtoupper($file->objectType) . ' #' . $file->objectID . ']';?></a>
        </td>
        <td class='text-left'> <?php echo $file->pathname;?> </td>
        <td><?php echo $file->extension;?></td>
        <td><?php echo number_format($file->size / 1024 , 1) . 'K';?></td>
        <td><?php echo isset($file->addedBy) ? zget($users, $file->addedBy) : '';?></td>
        <td><?php echo isset($file->addedDate) ? substr($file->addedDate, 0, 10) : '';?></td>
        <td class='text-center'>
          <?php
          common::printLink('file', 'download', "fileID=$file->id", $lang->doc->download, "data-toggle='modal'", '', true, false, $file);
          common::printLink('file', 'delete',   "fileID=$file->id", $lang->delete, 'hiddenwin', '', true, false, $file);
          ?>
        </td>
      </tr>
    <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='8'> <?php $pager->show();?> </td>
    </tr>
  </tfoot>
</table>
<?php else:?>
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

      $sessionString  = $config->requestType == 'PATH_INFO' ? '?' : '&';
      $sessionString .= session_name() . '=' . session_id();
      $fileID         = $file->id;
      $url            = helper::createLink('file', 'download', 'fileID=' . $fileID) . $sessionString ;
      ?>
      <a class='media-wrapper' href='<?php echo $url;?>' title='<?php echo $file->title  . '.' . $file->extension?>' target='_blank' onclick="return downloadFile(<?php echo $file->id?>, '<?php echo $file->extension?>', <?php echo $imageWidth?>)">
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
        <a href='<?php echo $this->createLink($file->objectType, 'view', "objectID=$file->objectID");?>' title='<?php echo substr($file->addedDate, 0, 10)?>'><?php echo $file->title . '.' . $file->extension . ' [' . strtoupper($file->objectType) . ' #' . $file->objectID . ']';?></a>
        <?php if(common::hasPriv('file', 'delete')): ?>
        <a href='<?php echo $this->createLink('file', 'delete', "fileID=$file->id"); ?>' target='hiddenwin' title='<?php echo $lang->delete?>' class='delete pull-right'><i class='icon icon-remove'></i></a>
        <?php endif?>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<div class='clearfix pager-wrapper'><?php $pager->show();?></div>
<?php endif?>

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
