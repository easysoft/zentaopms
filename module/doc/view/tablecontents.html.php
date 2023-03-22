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
<?php js::set('linkParams', "type=$type&objectID=$objectID");?>
<?php js::set('docLang', $lang->doc);?>
<div id="mainMenu" class="clearfix">
  <div id="leftBar" class="btn-toolbar pull-left">
    <?php echo $objectDropdown;?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->doc->searchDoc;?></a>
  </div>
  <div class="btn-toolbar pull-right">
  <?php
  $exportMethod = $type . '2export';
  if(common::hasPriv('doc', $exportMethod))
  {
      echo html::a($this->createLink('doc', $exportMethod, "libID=$libID&docID=0", 'html', true), "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export' id='$exportMethod'");
  }

  if(common::hasPriv('doc', 'createLib'))
  {
      echo html::a(helper::createLink('doc', 'createLib', "type=$type&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $this->lang->doc->createLib, '', 'class="btn btn-secondary iframe"');
  }

  if(common::hasPriv('doc', 'create'))
  {
      if($libID)
      {
          $html  = "<div class='dropdown btn-group createDropdown'>";
          $html .= html::a($this->createLink('doc', 'createBasicInfo', "objectType=$type&objectID=$objectID&libID=$libID&moduleID=$moduleID&type=html", '', true), "<i class='icon icon-plus'></i> {$lang->doc->create}", '', "class='btn btn-primary iframe'");
          $html .= "<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
          $html .= "<ul class='dropdown-menu pull-right'>";

          foreach($this->lang->doc->createList as $typeKey => $typeName)
          {
              if($config->edition != 'max' and $typeKey == 'template') continue;
              $class  = (strpos($this->config->doc->officeTypes, $typeKey) !== false or strpos($this->config->doc->textTypes, $typeKey) !== false) ? 'iframe' : '';
              $module = $typeKey == 'api' ? 'api' : 'doc';
              $method = strpos($this->config->doc->textTypes, $typeKey) !== false ? 'createBasicInfo' : 'create';

              $params = "objectType=$type&objectID=$objectID&libID=$libID&moduleID=$moduleID&type=$typeKey";
              if($typeKey == 'api') $params = "libID=$libID&moduleID=$moduleID";
              if($typeKey == 'template') $params = "objectType=$type&objectID=$objectID&libID=$libID&moduleID=$moduleID&type=html&fromGlobal=&from=template";

              $html .= "<li>";
              $html .= html::a(helper::createLink($module, $method, $params, '', $class ? true : false), $typeName, '', "class='$class' data-app='{$this->app->tab}'");
              $html .= "</li>";
              if($typeKey == 'template') $html .= '<li class="divider"></li>';
              if($config->edition != 'max' and $typeKey == 'api') $html .= '<li class="divider"></li>';
          }

          $html .= '</ul></div>';
          echo $html;
      }
  }
  ?>
  </div>
</div>
<div id='mainContent'class="fade flex split-row">
  <div id='sideBar' class="panel side side-col col overflow-x-auto flex-none" data-min-width="150">
    <div id="fileTree" class="file-tree"></div>
  </div>
  <div id="spliter" class="spliter col-spliter sidebar-toggle">
    <i class="icon icon-angle-left"></i>
    <div class="spliter-btn flex-center hidden">
      <div class="spliter-inner"></div>
    </div>
  </div>
  <div class="main-col flex-full col overflow-x-auto flex-auto" data-min-width="500">
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
</div>
<div class='hidden' id='dropDownData'>
  <div class='libDorpdown'>
    <?php if(common::hasPriv('tree', 'browse')):?>
    <li data-method="addCatalogue"><a><i class="icon icon-controls"></i><?php echo $lang->doc->libDropdown['addModule'];?></a></li>
    <?php endif;?>
    <?php if(common::hasPriv('doc', 'editLib')):?>
    <li data-method="editLib"><a href='<?php echo inlink('editLib', 'libID=%libID%');?>' class='iframe'><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['editLib'];?></a></li>
    <?php endif;?>
    <?php if(common::hasPriv('doc', 'deleteLib')):?>
    <li data-method="deleteLib"><a href='<?php echo inlink('deleteLib', 'libID=%libID%');?>' target='hiddenwin'><i class="icon icon-trash"></i><?php echo $lang->doc->libDropdown['deleteLib'];?></a></li>
    <?php endif;?>
  </div>
  <div class='moduleDorpdown'>
    <?php if(common::hasPriv('tree', 'browse')):?>
    <li data-method="addCata"><a><i class="icon icon-controls"></i><?php echo $lang->doc->libDropdown['addSameModule'];?></a></li>
    <li data-method="addCataChild"><a><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['addSubModule'];?></a></li>
    <li data-method="editCata"><a href='<?php echo helper::createLink('tree', 'edit', 'moduleID=%moduleID%');?>' class='iframe'><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['editModule'];?></a></li>
    <li data-method="deleteCata"><a href='<?php echo helper::createLink('tree', 'delete', 'rootID=%libID%&moduleID=%moduleID%');?>' target='hiddenwin'><i class="icon icon-trash"></i><?php echo $lang->doc->libDropdown['delModule'];?></a></li>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
