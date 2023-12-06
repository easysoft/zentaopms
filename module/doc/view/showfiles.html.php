<?php
/**
 * The showFiles view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('treeData', $libTree);?>
<?php js::set('docLang', $lang->doc);?>
<?php js::set('libType', 'annex');?>
<?php js::set('canViewFiles', common::hasPriv('doc', 'showfiles'));?>
<?php js::set('linkParams', "objectID=$objectID&%s");?>
<?php js::set('type', $type);?>
<?php js::set('tab', $this->app->tab);?>
<?php js::set('searchLink', helper::createLink('doc', 'showFiles', "type=$type&objectID=$objectID&viewType=$viewType&orderBy=id_desc&recTotal=0&recPerPage=20&pageID=1&searchTitle=%s"));?>
<div id="mainMenu" class="clearfix">
  <div id="leftBar" class="btn-toolbar pull-left">
    <?php echo $objectDropdown;?>
    <div class='btn-group searchBox'>
      <form class="input-control has-icon-right table-col not-watch" method="post">
        <?php echo html::input('title', $searchTitle, "class='form-control' placeholder='{$lang->doc->fileTitle}'");?>
        <?php echo html::submitButton("<i class='icon icon-search'></i>", '', "btn  btn-icon btn-link input-control-icon-right");?>
      </form>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <div class='btn-group'>
      <?php echo html::a(inlink('showFiles', "type=$type&objectID=$objectID&viewType=list&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&searchTitle=$searchTitle"), "<i class='icon icon-bars'></i>", '', "title={$this->lang->doc->browseTypeList['list']} class='btn btn-icon" . ($viewType == 'list' ? ' text-primary' : '') . "' data-app='{$this->app->tab}'");?>
      <?php echo html::a(inlink('showFiles', "type=$type&objectID=$objectID&viewType=card&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&searchTitle=$searchTitle"), "<i class='icon icon-cards-view'></i>", '', "title={$this->lang->doc->browseTypeList['grid']} class='btn btn-icon" . ($viewType != 'list' ? ' text-primary' : '') . "' data-app='{$this->app->tab}'");?>
    </div>
    <?php
    if(common::hasPriv('doc', 'createLib'))
    {
        echo html::a(helper::createLink('doc', 'createLib', "type=$type&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $this->lang->doc->createLib, '', 'class="btn btn-secondary iframe" data-width="800px"');
    }
    ?>
  </div>
</div>
<div class="main-row fade <?php if(!empty($libTree)) echo 'flex';?>" id="mainContent">
  <div id='sideBar' class="panel side side-col col overflow-auto" data-min-width="150">
    <?php include 'lefttree.html.php';?>
  </div>
  <div class="sidebar-toggle flex-center"><i class="icon icon-angle-left"></i></div>
  <div class="main-col flex-full overflow-visible flex-auto" data-min-width="500">
    <?php if($viewType == 'list'):?>
    <?php if(!empty($files)):?>
    <div class='main-table'>
      <table class="table has-sort-head" id='#filesTable'>
        <thead>
          <tr>
            <?php $this->app->rawMethod = 'showfiles';?>
            <?php $vars = "type=$type&objectID=$objectID&viewType=$viewType&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID&searchTitle=$searchTitle";?>
            <th class="c-id"><?php common::printOrderLink('id', $orderBy, $vars, $lang->doc->id);?></th>
            <th class="c-name"><?php common::printOrderLink('title', $orderBy, $vars, $lang->doc->fileTitle);?></th>
            <th class="c-source"><?php common::printOrderLink('objectID', $orderBy, $vars, $lang->doc->source);?></th>
            <th class="c-type"><?php common::printOrderLink('extension', $orderBy, $vars, $lang->doc->extension);?></th>
            <th class="c-size"><?php common::printOrderLink('size', $orderBy, $vars, $lang->doc->size);?></th>
            <th class="c-size"><?php common::printOrderLink('addedBy', $orderBy, $vars, $lang->doc->addedBy);?></th>
            <th class="c-size"><?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->doc->addedDate);?></th>
            <th class="c-actions-1"><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($files as $file):?>
          <?php if(empty($file->pathname)) continue;?>
            <tr>
              <?php
              $imageSize  = $this->file->getImageSize($file);
              $imageWidth = isset($imageSize[0]) ? $imageSize[0] : 0;
              ?>
              <td><?php echo sprintf('%03d', $file->id);?></td>
              <td class='c-name' title='<?php echo str_replace('.' . $file->extension, '', $file->title);?>'>
                <?php if(in_array($file->extension, $config->file->imageExtensions)):?>
                <div style='display: inline-block'><img onload='setImageSize(this, 19)' src='<?php echo $file->webPath;?>' data-extension="<?php echo $file->extension;?>" data-id="<?php echo $file->id;?>" data-width="<?php echo $imageWidth;?>"/></div>
                <?php else:?>
                <?php echo $fileIcon[$file->id];?>
                <?php endif;?>
                <?php echo str_replace('.' . $file->extension, '', $file->title);?>
              </td>
              <td class='c-name'>
                <?php
                if($file->objectType == 'requirement')
                {
                    $commonTitle = $lang->URCommon . ' : ';
                }
                else
                {
                    if(!isset($lang->{$file->objectType}->common)) $app->loadLang($file->objectType);
                    $commonTitle = $lang->{$file->objectType}->common . ' : ';
                }
                echo $commonTitle;
                ?>
                <?php $isonlybody = $file->objectType != 'doc';?>
                <a title='<?php if(isset($sourcePairs[$file->objectType][$file->objectID])) echo $sourcePairs[$file->objectType][$file->objectID];?>' href='<?php echo $this->createLink(($file->objectType == 'requirement' ? 'story' : $file->objectType), 'view', "objectID=$file->objectID", '', $isonlybody);?>' class='<?php if($isonlybody) echo "iframe";?>' data-width='90%'>
                  <?php if(isset($sourcePairs[$file->objectType][$file->objectID])) echo $sourcePairs[$file->objectType][$file->objectID];?>
                </a>
              </td>
              <td><?php echo $file->extension;?></td>
              <td><?php echo number_format($file->size / 1024, 1) . 'K';?></td>
              <td><?php echo isset($file->addedBy) ? zget($users, $file->addedBy) : '';?></td>
              <td><?php echo isset($file->addedDate) ? substr($file->addedDate, 0, 10) : '';?></td>
              <td class="c-actions"><?php common::printLink('file', 'download', "fileID=$file->id", '<i class="icon-import"></i>', "", "class='btn' title={$lang->doc->download}", true, false, $file);?></td>
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
              $imageSize  = $this->file->getImageSize($file);
              $imageWidth = isset($imageSize[0]) ? $imageSize[0] : 0;

              $fileID = $file->id;
              $url    = helper::createLink('file', 'download', 'fileID=' . $fileID);
              $url   .= strpos($url, '?') === false ? '?' : '&';
              $url   .= session_name() . '=' . session_id();
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
                <div class='file-name text-muted'>
                  <?php
                  if($file->objectType == 'requirement')
                  {
                      $commonTitle = $lang->URCommon . ' : ';
                  }
                  else
                  {
                      if(!isset($lang->{$file->objectType}->common)) $app->loadLang($file->objectType);
                      $commonTitle = $lang->{$file->objectType}->common . ' : ';
                  }
                  echo $commonTitle;
                  ?>
                  <?php $isonlybody = $file->objectType != 'doc';?>
                  <a href='<?php echo $this->createLink(($file->objectType == 'requirement' ? 'story' : $file->objectType), 'view', "objectID=$file->objectID", '', $isonlybody);?>' title='<?php if(isset($sourcePairs[$file->objectType][$file->objectID])) echo $sourcePairs[$file->objectType][$file->objectID];?>' class='<?php if($isonlybody) echo "iframe";?>' data-width='90%'>
                    <?php if(isset($sourcePairs[$file->objectType][$file->objectID])) echo $sourcePairs[$file->objectType][$file->objectID];?>
                  </a>
                </div>
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
<div class='hidden' id='dropDownData'>
  <ul class='libDorpdown'>
    <?php if(common::hasPriv('tree', 'browse')):?>
    <li data-method="addCataLib" data-has-children='%hasChildren%'  data-libid='%libID%' data-moduleid="%moduleID%" data-type="add"><a><i class="icon icon-controls"></i><?php echo $lang->doc->libDropdown['addModule'];?></a></li>
    <?php endif;?>
    <?php if(common::hasPriv('doc', 'editLib')):?>
    <li data-method="editLib"><a href='<?php echo inlink('editLib', 'libID=%libID%');?>' data-toggle='modal' data-type='iframe'><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['editLib'];?></a></li>
    <?php endif;?>
    <?php if(common::hasPriv('doc', 'deleteLib')):?>
    <li data-method="deleteLib"><a href='<?php echo inlink('deleteLib', 'libID=%libID%');?>' target='hiddenwin'><i class="icon icon-trash"></i><?php echo $lang->doc->libDropdown['deleteLib'];?></a></li>
    <?php endif;?>
  </ul>
  <ul class='moduleDorpdown'>
    <?php if(common::hasPriv('tree', 'browse')):?>
    <li data-method="addCataBro" data-type="add" data-id="%moduleID%"><a><i class="icon icon-controls"></i><?php echo $lang->doc->libDropdown['addSameModule'];?></a></li>
    <li data-method="addCataChild" data-type="add" data-id="%moduleID%" data-has-children='%hasChildren%'><a><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['addSubModule'];?></a></li>
    <li data-method="editCata" class='edit-module'><a data-href='<?php echo helper::createLink('tree', 'edit', 'moduleID=%moduleID%&type=doc');?>'><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['editModule'];?></a></li>
    <li data-method="deleteCata"><a href='<?php echo helper::createLink('tree', 'delete', 'rootID=%libID%&moduleID=%moduleID%');?>' target='hiddenwin'><i class="icon icon-trash"></i><?php echo $lang->doc->libDropdown['delModule'];?></a></li>
    <?php endif;?>
  </ul>
</div>
<div class='hidden' data-id="ulTreeModal">
  <ul data-id="liTreeModal" class="menu-active-primary menu-hover-primary has-input">
    <li data-id="insert" class="has-input">
      <input data-target="%target%" class="form-control input-tree"></input>
    </li>
  </ul>
</div>
<div class="hidden" data-id="aTreeModal">
  <a href="###" style="position: relative" data-has-children="false" data-action="true" title="%name%" data-id="%id%">
    <div class="text h-full w-full flex-between overflow-hidden" style="position: relative;">
      <span style="padding-left: 5px;">%name%</span>
      <i class="icon icon-drop icon-ellipsis-v tree-icon hidden" data-iscatalogue="true"></i>
    </div>
  </a>
</div>
<script>
<?php $sessionString = session_name() . '=' . session_id();?>
function downloadFile(fileID, extension, imageWidth)
{
    if(!fileID) return;
    var fileTypes   = 'jpg,jpeg,gif,png,bmp';
    var windowWidth = $(window).width();

    var url = createLink('file', 'download', 'fileID=' + fileID + '&mouse=left');
    url    += url.indexOf('?') >= 0 ? '&' : '?';
    url    += '<?php echo $sessionString;?>';

    width = (windowWidth > imageWidth) ? ((imageWidth < windowWidth * 0.5) ? windowWidth * 0.5 : imageWidth) : windowWidth;
    if(fileTypes.indexOf(extension) >= 0)
    {
        $('<a>').modalTrigger({url: url, type: 'iframe', width: width}).trigger('click');
    }
    else
    {
        window.open(url, '_self');
    }
    return false;
}

/**
 * Set the max with of image.
 *
 * @access public
 * @return void
 */
function setImageSize(image, maxWidth, maxHeight)
{
    var $image = $(image);
    if($image.parent().prop('tagName').toLowerCase() == 'a') return;

    /* If not set maxWidth, set it auto. */
    if(!maxWidth)
    {
        bodyWidth = $('body').width();
        maxWidth  = bodyWidth - 470; // The side bar's width is 336, and add some margins.
    }
    if(!maxHeight) maxHeight = $(top.window).height();

    setTimeout(function()
    {
        maxHeightStyle = $image.height() > 0 ? 'max-height:' + maxHeight + 'px' : '';
        if(!document.getElementsByClassName('xxc-embed').length && $image.width() > 0 && $image.width() > maxWidth) $image.attr('width', maxWidth);
        $image.wrap('<a href="javascript:;" style="display:inline-block;position:relative;overflow:hidden;' + maxHeightStyle + '" onclick="return downloadFile(' + $image.attr('data-id') + ",'" + $image.attr('data-extension') + "', " +  $image.attr('data-width') + ')"></a>');
        if($image.height() > 0 && $image.height() > maxHeight) $image.closest('a').append("<a href='###' class='showMoreImage' onclick='showMoreImage(this)'>" + lang.expand + " <i class='icon-angle-down'></i></a>");
    }, 50);
}
</script>
<?php include '../../common/view/footer.html.php';?>
