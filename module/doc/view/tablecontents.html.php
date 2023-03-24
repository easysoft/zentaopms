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
<?php js::set('objectType', $type);?>
<?php js::set('objectID', $objectID);?>
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
      echo html::a(helper::createLink('doc', 'createLib', "type=$type&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $this->lang->doc->createLib, '', 'class="btn btn-secondary iframe"');
  }

  if($libID and (common::hasPriv('doc', 'create') or (common::hasPriv('api', 'create') and !$apiLibID)))
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
    <div id="fileTree" class="file-tree"></div>
  </div>
  <div class="sidebar-toggle flex-center"><i class="icon icon-angle-left"></i></div>
  <div class="main-col flex-full col overflow-visible flex-auto overflow-visible" data-min-width="500">
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
<div class='hidden' id='dropDownData'>
  <div class='libDorpdown'>
    <?php if(common::hasPriv('tree', 'browse')):?>
    <li data-method="addCataLib" data-has-children='%hasChildren%'  data-libid='%libID%' data-moduleid="%moduleID%" data-type="add"><a><i class="icon icon-controls"></i><?php echo $lang->doc->libDropdown['addModule'];?></a></li>
    <?php endif;?>
    <?php if(common::hasPriv('doc', 'editLib')):?>
    <li data-method="editLib"><a href='<?php echo inlink('editLib', 'libID=%libID%');?>' data-toggle='modal' data-type='iframe'><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['editLib'];?></a></li>
    <?php endif;?>
    <?php if(common::hasPriv('doc', 'deleteLib')):?>
    <li data-method="deleteLib"><a href='<?php echo inlink('deleteLib', 'libID=%libID%');?>' target='hiddenwin'><i class="icon icon-trash"></i><?php echo $lang->doc->libDropdown['deleteLib'];?></a></li>
    <?php endif;?>
  </div>
  <div class='moduleDorpdown'>
    <?php if(common::hasPriv('tree', 'browse')):?>
    <li data-method="addCataBro" data-type="add" data-id="%moduleID%"><a><i class="icon icon-controls"></i><?php echo $lang->doc->libDropdown['addSameModule'];?></a></li>
    <li data-method="addCataChild" data-type="add" data-id="%moduleID%" data-has-children='%hasChildren%'><a><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['addSubModule'];?></a></li>
    <li data-method="editCata" class='edit-module'><a data-href='<?php echo helper::createLink('tree', 'edit', 'moduleID=%moduleID%&type=doc');?>'><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['editModule'];?></a></li>
    <li data-method="deleteCata"><a href='<?php echo helper::createLink('tree', 'delete', 'rootID=%libID%&moduleID=%moduleID%');?>' target='hiddenwin'><i class="icon icon-trash"></i><?php echo $lang->doc->libDropdown['delModule'];?></a></li>
    <?php endif;?>
  </div>
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
    <div class="text h-full w-full overflow-hidden" style="position: relative;">
      %name%
      <i class="icon icon-drop icon-ellipsis-v hidden file-drop-icon" data-iscatalogue="true"></i>
    </div>
  </a>
</div>
<?php include '../../common/view/footer.html.php';?>
