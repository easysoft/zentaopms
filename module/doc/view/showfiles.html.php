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
<div class="main-row fade <?php if($this->from == 'doc') echo 'split-row';?>" id="mainRow">
  <?php if($this->from == 'doc'):?>
  <?php include './side.html.php';?>
  <div class="col-spliter"></div>
  <?php endif;?>
  <div class="main-col" data-min-width="400">
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal"><i class="icon icon-folder-open-o text-muted"></i> <?php echo $lang->doclib->files;?></div>
        <nav class="panel-actions btn-toolbar">
          <div class="btn-group">
            <form class='input-control has-icon-right table-col' method='get'>
              <?php
              if($config->requestType == 'GET')
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
              <?php echo html::hidden('onlybody', isonlybody() ? 'yes' : 'no');?>
              <?php echo html::input('title', $this->get->title, "class='form-control' placeholder='{$lang->doc->fileTitle}'");?>
              <?php echo html::submitButton("<i class='icon icon-search'></i>", '', "btn  btn-icon btn-link input-control-icon-right");?>
            </form>
          </div>
          <div class="btn-group">
            <?php echo html::a(inlink('showFiles', "type=$type&objectID=$objectID&viewType=card"), "<i class='icon icon-cards-view'></i>", '', "title={$lang->doc->browseTypeList['grid']} class='btn btn-icon" . ($viewType != 'list' ? ' text-primary' : '') . "'");?>
            <?php echo html::a(inlink('showFiles', "type=$type&objectID=$objectID&viewType=list"), "<i class='icon icon-bars'></i>" , '',  "title={$lang->doc->browseTypeList['list']} class='btn btn-icon" . ($viewType == 'list' ? ' text-primary' : '') . "'");?>
          </div>
        </nav>
      </div>

      <?php if($viewType == 'list'):?>
      <div class="panel-body">
        <table class="table table-borderless table-hover table-files table-fixed no-margin">
          <thead>
            <tr class="text-center">
              <th class="w-80px"><?php echo $lang->doc->id;?></th>
              <th class='text-left'><?php echo $lang->doc->fileTitle;?></th>
              <th class='text-left'><?php echo $lang->doc->filePath;?></th>
              <th class="w-80px"><?php echo $lang->doc->extension;?></th>
              <th class="w-60px"><?php echo $lang->doc->size;?></th>
              <th class="w-100px"><?php echo $lang->doc->addedBy;?></th>
              <th class="w-160px"><?php echo $lang->doc->addedDate;?></th>
              <th class="w-80px"><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($files as $file):?>
            <?php if(empty($file->pathname)) continue;?>
              <tr>
                <td class="text-center"><?php echo sprintf('%03d', $file->id);?></td>
                <td class="c-url">
                  <a href='<?php echo $this->createLink($file->objectType, 'view', "objectID=$file->objectID");?>'><?php echo $file->title . ' [' . strtoupper($file->objectType) . ' #' . $file->objectID . ']';?></a>
                </td>
                <td> <?php echo $file->pathname;?> </td>
                <td class="text-center"><?php echo $file->extension;?></td>
                <td><?php echo number_format($file->size / 1024 , 1) . 'K';?></td>
                <td class="text-center"><?php echo isset($file->addedBy) ? zget($users, $file->addedBy) : '';?></td>
                <td class="text-center"><?php echo isset($file->addedDate) ? substr($file->addedDate, 0, 10) : '';?></td>
                <td class="c-actions">
                  <?php
                  common::printLink('file', 'download', "fileID=$file->id", '<i class="icon-import"></i>', "data-toggle='modal'", "class='btn' title={$lang->doc->download}", true, false, $file);
                  common::printLink('file', 'delete',   "fileID=$file->id", '<i class="icon-trash"></i>', 'hiddenwin', "class='btn' title={$lang->delete}", true, false, $file);
                  ?>
                </td>
              </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
      </div>
      <?php else:?>
      <div class="panel-body">
        <div class="row row-grid files-grid" data-size="300">
          <?php foreach($files as $file):?>
          <?php if(empty($file->pathname)) continue;?>
          <div class='col'>
            <div class='lib-file'>
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
              <div class='file'>
                <a href='<?php echo $url;?>' title='<?php echo $file->title;?>' target='_blank' onclick="return downloadFile(<?php echo $file->id?>, '<?php echo $file->extension?>', <?php echo $imageWidth?>)">
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
                    else if(strpos('avi,mp4,mov', $file->extension) !== false) $iconClass = 'icon-file-video';
                    else if(strpos('txt,md', $file->extension) !== false) $iconClass = 'icon-file-text';
                    else if(strpos('html,htm', $file->extension) !== false) $iconClass = 'icon-globe';
                    echo "<i class='file-icon icon $iconClass'></i>";
                }
                ?>
                </a>
                <div class='file-name'><a href='<?php echo $this->createLink($file->objectType, 'view', "objectID=$file->objectID");?>' title='<?php echo substr($file->addedDate, 0, 10)?>'><?php echo $file->title . ' [' . strtoupper($file->objectType) . ' #' . $file->objectID . ']';?></a></div>
              </div>
              <div class='actions'>
                <?php if(common::hasPriv('file', 'delete')): ?>
                <a href='<?php echo $this->createLink('file', 'delete', "fileID=$file->id"); ?>' target='hiddenwin' title='<?php echo $lang->delete?>' class='delete btn btn-link'><i class='icon icon-trash'></i></a>
                <?php endif?>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
        <?php if(!empty($files)):?>
        <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
        <?php else:?>
        <div class='table-empty-tip text-muted'><?php echo $lang->pager->noRecord;?></div>
        <?php endif?>
        </div>
      </div>
      <?php endif?>
    </div>
  </div>
</div>

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
