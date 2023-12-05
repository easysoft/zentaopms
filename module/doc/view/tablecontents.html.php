<?php
/**
 * The table contents view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     doc
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('treeData', $libTree);?>
<?php js::set('linkParams', "objectID=$objectID&%s&browseType=&orderBy=$orderBy&param=0");?>
<?php js::set('docLang', $lang->doc);?>
<?php js::set('libType', $libType);?>
<div id="mainMenu" class="clearfix">
  <div id="leftBar" class="btn-toolbar pull-left">
    <?php echo $objectDropdown;?>
    <?php if(!empty($libTree)):?>
    <?php if($libType != 'api'):?>
    <?php foreach($lang->doc->featureBar['tableContents'] as $barType => $barName):?>
    <?php $active     = $barType == $browseType ? 'btn-active-text' : '';?>
    <?php $linkParams = "objectID=$objectID&libID=$libID&moduleID=$moduleID&browseType=$barType";?>
    <?php echo html::a($this->createLink($app->rawModule, $app->rawMethod, $linkParams), "<span class='text'>{$barName}</span>" . ($active ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link $active' id='{$barType}Tab'");?>
    <?php endforeach;?>
    <?php endif;?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->doc->searchDoc;?></a>
    <?php endif;?>
  </div>
  <div class="btn-toolbar pull-right">
  <?php
  if($libType == 'api')
  {
      if(common::hasPriv('api', 'struct'))        echo html::a($this->createLink('api', 'struct',        "libID=$libID"), "<i class='icon-treemap muted'> </i>" . $lang->api->struct, '', "class='btn btn-link'");
      if(common::hasPriv('api', 'releases'))      echo html::a($this->createLink('api', 'releases',      "libID=$libID", 'html', true), "<i class='icon-version muted'> </i>" . $lang->api->releases, '', "class='btn btn-link iframe' data-width='800px'");
      if(common::hasPriv('api', 'createRelease')) echo html::a($this->createLink('api', 'createRelease', "libID=$libID"), "<i class='icon-publish muted'> </i>" . $lang->api->createRelease, '', "class='btn btn-link iframe' data-width='800px'");
  }

  if($canExport)
  {
      $exportLink = $this->createLink('doc', $exportMethod, "libID=$libID&moduleID=$moduleID", 'html', true);
      if($libType == 'api') $exportLink = $this->createLink('api', $exportMethod, "libID=$libID&version=0&release=$release&moduleID=$moduleID", 'html', true);
      echo html::a($exportLink, "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export' data-width='480px' id='{$exportMethod}'");
  }

  if(common::hasPriv('doc', 'createLib'))
  {
      echo html::a(helper::createLink('doc', 'createLib', "type=$type&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $this->lang->doc->createLib, '', 'class="btn btn-secondary iframe" data-width="800px"');
  }

  if($libType == 'api')
  {
      if(common::hasPriv('api', 'create')) echo html::a($this->createLink('api', 'create',    "libID=$libID&moduleID=$moduleID", '', true), '<i class="icon icon-plus"></i> ' . $lang->api->createApi, '', 'class="btn btn-primary iframe" data-width="95%"');
  }
  elseif($libID and common::hasPriv('doc', 'create'))
  {
      echo $this->doc->printCreateBtn($lib, $moduleID);
  }
  ?>
  </div>
</div>
<div id='mainContent'class="fade <?php if(!empty($libTree)) echo 'flex';?>">
<?php if(empty($libTree)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->doc->noLib;?></span>
      <?php
      if(common::hasPriv('doc', 'createLib'))
      {
          echo html::a(helper::createLink('doc', 'createLib', "type=$type&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $this->lang->doc->createLib, '', 'class="btn btn-info iframe"');
      }
      ?>
    </p>
  </div>
<?php else:?>
  <div id='sideBar' class="panel side side-col col overflow-auto">
    <?php include 'lefttree.html.php';?>
  </div>
  <div class="sidebar-toggle flex-center"><i class="icon icon-angle-left"></i></div>
  <div class="main-col flex-full overflow-visible flex-auto" data-min-width="500">
    <div class="cell<?php if($browseType == 'bySearch') echo ' show';?>" style="min-width: 400px" id="queryBox" data-module=<?php echo $type . $libType . 'Doc';?>></div>
    <?php
    if($browseType == 'annex')
    {
        include 'showfiles.html.php';
    }
    elseif($libType == 'api')
    {
        include '../../api/view/apilist.html.php';
    }
    else
    {
        include 'doclist.html.php';
    }
    ;?>
  </div>
<?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
