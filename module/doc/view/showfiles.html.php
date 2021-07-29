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
<div class="main-row fade" id="mainRow">
  <div class="main-col" data-min-width="400">
    <?php if($viewType == 'list'):?>
    <?php if(!empty($files)):?>
    <div class='main-table'>
      <table class="table has-sort-head">
        <thead>
          <tr>
            <?php $this->app->rawMethod = 'showfiles';?>
            <?php $vars = "type=$type&objectID=$objectID&viewType=$viewType&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";?>
            <th class="w-60px"><?php common::printOrderLink('id', $orderBy, $vars, $lang->doc->id);?></th>
            <th class="c-name"><?php common::printOrderLink('title', $orderBy, $vars, $lang->doc->fileTitle);?></th>
            <th class="w-300px"><?php common::printOrderLink('objectID', $orderBy, $vars, $lang->doc->source);?></th>
            <th class="w-100px"><?php common::printOrderLink('extension', $orderBy, $vars, $lang->doc->extension);?></th>
            <th class="w-100px"><?php common::printOrderLink('size', $orderBy, $vars, $lang->doc->size);?></th>
            <th class="w-100px"><?php common::printOrderLink('addedBy', $orderBy, $vars, $lang->doc->addedBy);?></th>
            <th class="w-100px"><?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->doc->addedDate);?></th>
            <th class="c-actions-1"><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($files as $file):?>
          <?php if(empty($file->pathname)) continue;?>
            <tr>
              <td><?php echo sprintf('%03d', $file->id);?></td>
              <td class='c-name' title='<?php echo str_replace('.' . $file->extension, '', $file->title);?>'>
              <?php
              if(in_array($file->extension, $config->file->imageExtensions))
              {
                  echo "<div style='display: inline-block'><img width='19' height='19' src='$file->webPath'/></div>";
              }
              else
              {
                  echo $fileIcon[$file->id];
              }
              ?>
              <?php echo str_replace('.' . $file->extension, '', $file->title);?>
              </td>
              <td class='c-name'><?php echo $lang->{$file->objectType}->common . ' : ';?><a title='<?php echo $sourcePairs[$file->objectType][$file->objectID];?>' href='<?php echo $this->createLink($file->objectType, 'view', "objectID=$file->objectID");?>'><?php echo $sourcePairs[$file->objectType][$file->objectID];?></a></td>
              <td><?php echo $file->extension;?></td>
              <td><?php echo number_format($file->size / 1024, 1) . 'K';?></td>
              <td><?php echo isset($file->addedBy) ? zget($users, $file->addedBy) : '';?></td>
              <td><?php echo isset($file->addedDate) ? substr($file->addedDate, 0, 10) : '';?></td>
              <td class="c-actions"><?php common::printLink('file', 'download', "fileID=$file->id", '<i class="icon-import"></i>', "data-toggle='modal'", "class='btn' title={$lang->doc->download}", true, false, $file);?></td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php else:?>
    <div class='table-empty-tip text-muted'><?php echo $lang->pager->noRecord;?></div>
    <?php endif?>
    <?php else:?>
    <div class="panel block-files block-sm no-margin">
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
                      echo $fileIcon[$file->id];
                  }
                  ?>
                </a>
                <div class='file-name' title='<?php echo $file->title;?>'><?php echo $file->title;?></a></div>
                <div class='text-muted'><?php echo $lang->{$file->objectType}->common . ' : ';?><a href='<?php echo $this->createLink($file->objectType, 'view', "objectID=$file->objectID");?>' title='<?php echo $sourcePairs[$file->objectType][$file->objectID];?>'><?php echo $sourcePairs[$file->objectType][$file->objectID];?></a></div>
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
    </div>
    <?php endif?>
  </div>
</div>
<?php js::set('type', $type);?>
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
