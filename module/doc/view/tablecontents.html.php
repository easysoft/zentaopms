<?php
/**
 * The table contents view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     doc
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('treeData', $libTree);?>
<?php js::set('linkParams', "type=$type&objectID=$objectID%s&browseType=&orderBy=$orderBy");?>
<?php js::set('docLang', $lang->doc);?>
<?php js::set('libType', $libType);?>
<?php js::set('objectType', $type);?>
<?php js::set('objectID', $objectID);?>
<?php js::set('canViewFiles', common::hasPriv('doc', 'showfiles'));?>
<div id="mainMenu" class="clearfix">
  <div id="leftBar" class="btn-toolbar pull-left">
    <?php echo $objectDropdown;?>
    <?php if(!empty($libTree)):?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->doc->searchDoc;?></a>
    <?php endif;?>
  </div>
  <div class="btn-toolbar pull-right">
  <?php
  if($canExport)
  {
      echo html::a($this->createLink('doc', $exportMethod, "libID=$libID&docID=0", 'html', true), "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export' id='{$exportMethod}'");
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
      echo $this->doc->printCreateBtn($lib, $type, $objectID, $moduleID, $apiLibID);
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
  <div id='sideBar' class="panel side side-col col overflow-auto" data-min-width="150">
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
