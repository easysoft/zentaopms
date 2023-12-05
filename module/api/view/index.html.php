<?php
/**
 * The index view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->api->confirmDelete);?>
<?php js::set('treeData', $libTree);?>
<div id="mainMenu" class="clearfix">
  <div id="leftBar" class="btn-toolbar pull-left">
    <?php echo $objectDropdown;?>
    <?php if(!empty($libTree)):?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->doc->searchDoc;?></a>
    <?php endif;?>
  </div>
  <div class="btn-toolbar pull-right">
  <?php
    if($libTree and common::hasPriv('api', 'struct'))        echo html::a($this->createLink('api', 'struct',        "libID=$libID"), "<i class='icon-treemap muted'> </i>" . $lang->api->struct, '', "class='btn btn-link'");
    if($libTree and common::hasPriv('api', 'releases'))      echo html::a($this->createLink('api', 'releases',      "libID=$libID", 'html', true), "<i class='icon-version muted'> </i>" . $lang->api->releases, '', "class='btn btn-link iframe' data-width='800px'");
    if($libTree and common::hasPriv('api', 'createRelease')) echo html::a($this->createLink('api', 'createRelease', "libID=$libID"), "<i class='icon-publish muted'> </i>" . $lang->api->createRelease, '', "class='btn btn-link iframe' data-width='800px'");
    if($libTree and common::hasPriv('api', 'export') and $config->edition != 'open') echo html::a($this->createLink('api', 'export', "libID=$libID&version=$version&release=$release&moduleID=$moduleID", 'html', true), "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export' data-width='480px' id='export'");
    if(common::hasPriv('api', 'createLib')) echo html::a($this->createLink('api', 'createLib',     "type=" . ($objectType ? $objectType : 'nolink') . "&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $lang->api->createLib, '', 'class="btn btn-secondary iframe" data-width="800px"');
    if($libTree and common::hasPriv('api', 'create')) echo html::a($this->createLink('api', 'create',        "libID=$libID&moduleID=$moduleID"), '<i class="icon icon-plus"></i> ' . $lang->api->createApi, '', 'class="btn btn-primary"');
  ?>
  </div>
</div>
<div id='mainContent' class="fade <?php if(!empty($libTree)) echo 'flex';?>">
<?php if(empty($libTree)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->doc->noLib;?></span>
      <?php
      if(common::hasPriv('api', 'createLib')) echo html::a(helper::createLink('api', 'createLib', "type=" . ($objectType ? $objectType : 'nolink')), '<i class="icon icon-plus"></i> ' . $lang->api->createLib, '', 'class="btn btn-info iframe" data-width="800px"');
      ?>
    </p>
  </div>
<?php else:?>
  <div id='sideBar' class="panel side side-col col overflow-auto" data-min-width="150">
    <?php include '../../doc/view/lefttree.html.php';?>
  </div>
  <div class="sidebar-toggle flex-center"><i class="icon icon-angle-left"></i></div>
  <div class="main-col flex-full overflow-visible flex-auto overflow-visible" data-min-width="500">
    <div class="cell<?php if($browseType == 'bySearch') echo ' show';?>" style="min-width: 400px" id="queryBox" data-module='api'></div>
    <?php include 'apilist.html.php';?>
  </div>
<?php endif;?>
</div>
<div class='hidden' id='dropDownData'>
  <ul class='libDorpdown'>
    <?php if(common::hasPriv('tree', 'browse')):?>
    <li data-method="addCataLib" data-has-children='%hasChildren%'  data-libid='%libID%' data-moduleid="%moduleID%" data-type="add"><a><i class="icon icon-add-directory"></i><?php echo $lang->doc->libDropdown['addModule'];?></a></li>
    <?php endif;?>
    <?php if(common::hasPriv('api', 'editLib')):?>
    <li data-method="editLib"><a href='<?php echo inlink('editLib', 'libID=%libID%');?>' data-toggle='modal' data-type='iframe'><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['editLib'];?></a></li>
    <?php endif;?>
    <?php if(common::hasPriv('api', 'deleteLib')):?>
    <li data-method="deleteLib"><a href='<?php echo inlink('deleteLib', 'libID=%libID%');?>' target='hiddenwin'><i class="icon icon-trash"></i><?php echo $lang->doc->libDropdown['deleteLib'];?></a></li>
    <?php endif;?>
  </ul>
  <ul class='moduleDorpdown'>
    <?php if(common::hasPriv('tree', 'browse')):?>
    <li data-method="addCataBro" data-type="add" data-id="%moduleID%"><a><i class="icon icon-add-directory"></i><?php echo $lang->doc->libDropdown['addSameModule'];?></a></li>
    <li data-method="addCataChild" data-type="add" data-id="%moduleID%" data-has-children='%hasChildren%'><a><i class="icon icon-add-directory"></i><?php echo $lang->doc->libDropdown['addSubModule'];?></a></li>
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
  <a href="###" data-has-children="false" title="%name%" data-id="%id%">
    <div class="text h-full w-full flex-between overflow-hidden" style="position: relative;">
      <div>%name%</div>
      <i class="icon icon-drop icon-ellipsis-v hidden file-drop-icon" data-iscatalogue="true"></i>
    </div>
  </a>
</div>
<?php include '../../common/view/footer.html.php';?>
