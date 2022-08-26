<?php
/**
 * The table contents view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     doc
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('moduleTree', $moduleTree);?>
<?php js::set('docLang', $lang->doc);?>
<?php
$sideLibs = array();
foreach($lang->doclib->tabList as $libType => $typeName) $sideLibs[$libType] = $this->doc->getLimitLibs($libType);
$allModules = $this->loadModel('tree')->getDocStructure();

$sideSubLibs = array();
$sideSubLibs['product']   = $this->doc->getSubLibGroups('product', array_keys($sideLibs['product']));
$sideSubLibs['execution'] = $this->doc->getSubLibGroups('execution', array_keys($sideLibs['execution']));
if($this->methodName != 'browse')
{
    $browseType = '';
    $moduleID   = '';
}
if(empty($type)) $type = 'product';
?>
<div class="cell<?php if($browseType == 'bySearch') echo ' show';?>" id="queryBox" data-module=<?php echo $type . 'Doc';?>></div>
<div class="main-content">
  <div class="cell" id="<?php echo $type;?>">
    <div class="detail">
      <li class="detail-title"><?php echo $lang->doc->tableContents;?></li>
      <?php
      $canEditLib    = common::hasPriv('doc', 'editLib');
      $canManageBook = common::hasPriv('doc', 'manageBook');
      $canManageMenu = common::hasPriv('tree', 'browse');
      $canEditLib    = common::hasPriv('doc', 'editLib');
      $canDeleteLib  = common::hasPriv('doc', 'deleteLib');
      if($type != 'book' and ($canManageMenu or $canEditLib or $canDeleteLib) and !empty($libs))
      {
          echo "<div class='menu-actions'>";
          echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");
          echo "<ul class='dropdown-menu pull-left'>";
          if($canManageMenu)
          {
              echo '<li>' . html::a($this->createLink('tree', 'browse', "rootID=$libID&view=doc&currentModuleID=0&branch=0&from={$this->app->tab}", '', true), '<i class="icon-cog-outline"></i> ' . $this->lang->doc->manageType, '', "class='iframe'") . '</li>';
              echo "<li class='divider'></li>";
          }
          if($canEditLib) echo '<li>' . html::a($this->createLink('doc', 'editLib', "rootID=$libID"), '<i class="icon-edit"></i> ' . $lang->doc->editLib, '', "class='iframe'") . '</li>';
          if($canDeleteLib) echo '<li>' . html::a($this->createLink('doc', 'deleteLib', "rootID=$libID&confirm=no&type=lib&from=tableContents"), '<i class="icon-trash"></i> ' . $lang->doc->deleteLib, 'hiddenwin') . '</li>';
          echo '</ul></div>';
      }

      if($type == 'book' and ($canEditLib or $canManageBook) and !empty($libs))
      {
          echo "<div class='menu-actions'>";
          echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");
          echo "<ul class='dropdown-menu pull-left'>";
          if($canEditLib) echo '<li>' . html::a($this->createLink('doc', 'editLib', "rootID=$libID"), '<i class="icon-edit"></i> ' . $lang->doc->editBook, '', "class='iframe'") . '</li>';
          if($canManageBook) echo '<li>' . html::a($this->createLink('doc', 'manageBook', "bookID=$libID"), '<i class="icon-cog-outline"></i> ' . $lang->doc->manageBook) . '</li>';
          echo '</ul></div>';
      }
      ?>
    </div>
    <div class="detail">
      <?php if($moduleTree):?>
      <?php if($type == 'book'):?>
      <?php include './bookside.html.php';?>
      <?php else:?>
      <?php echo $moduleTree;?>
      <?php endif;?>
      <?php else:?>
      <div class="no-content"><img src="<?php echo $config->webRoot . 'theme/default/images/main/no_content.png'?>"/></div>
      <div class="notice text-muted"><?php echo (empty($libs) and $type == 'custom') ? $lang->doc->noLib : $lang->doc->noDoc;?></div>
      <div class="no-content-button">
        <?php
        $html = '';
        if($type == 'book' and common::hasPriv('doc', 'createLib'))
        {
            $html = html::a(helper::createLink('doc', 'createLib', "type=$type&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $lang->doc->createBook, '', 'class="btn btn-info btn-wide iframe"');
        }
        elseif(empty($libs) and $type == 'custom' and common::hasPriv('doc', 'createLib'))
        {
            $html = html::a(helper::createLink('doc', 'createLib', "type=$type&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $lang->doc->createLib, '', 'class="btn btn-info btn-wide iframe"');
        }
        elseif($libID and common::hasPriv('doc', 'create'))
        {
            $html  = "<div class='dropdown' id='createDropdown'>";
            $html .= "<button class='btn btn-info btn-wide' type='button' data-toggle='dropdown'><i class='icon icon-plus'></i> " . $lang->doc->createAB . " <span class='caret'></span></button>";
            $html .= "<ul class='dropdown-menu' style='left:0px'>";
            foreach($this->lang->doc->typeList as $typeKey => $typeName)
            {
                $icon   = zget($this->config->doc->iconList, $typeKey);
                $class  = (strpos($this->config->doc->officeTypes, $typeKey) !== false or strpos($this->config->doc->textTypes, $typeKey) !== false) ? 'iframe' : '';
                $method = strpos($this->config->doc->textTypes, $typeKey) !== false ? 'createBasicInfo' : 'create';
                $html  .= "<li>";
                $html  .= html::a(helper::createLink('doc', $method, "objectType=$type&objectID=$objectID&libID=$libID&moduleID=0&type=$typeKey", '', $class ? true : false), "<i class='icon-$icon text-muted'></i> " . $typeName, '', "class='$class' data-app='{$this->app->tab}'");
                $html  .= "</li>";
                if($typeKey == 'url') $html .= '<li class="divider"></li>';
            }
            $html .= "</ul></div>";
        }

        echo $html;
        ?>
        <?php
        if(!empty($libs))
        {
            if($type == 'book')
            {
                common::printLink('doc', 'manageBook', "bookID=$libID", $lang->doc->manageBook, '', "class='btn btn-info btn-wide'");
            }
            else
            {
                common::printLink('tree', 'browse', "rootID=$libID&view=doc&currentModuleID=0&branch=0&from={$this->app->tab}", $lang->doc->manageType, '', "class='btn btn-info btn-wide iframe'", '', true);
            }
        }
        ?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
