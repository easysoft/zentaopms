<?php
$sideLibs = array();
foreach($lang->doclib->tabList as $libType => $typeName) $sideLibs[$libType] = $this->doc->getLimitLibs($libType);
$allModules = $this->loadModel('tree')->getDocStructure();

$sideSubLibs = array();
$sideSubLibs['product'] = $this->doc->getSubLibGroups('product', array_keys($sideLibs['product']));
$sideSubLibs['project'] = $this->doc->getSubLibGroups('project', array_keys($sideLibs['project']));
if($this->methodName != 'browse')
{
    $browseType = '';
    $moduleID   = '';
}
if(empty($type)) $type = 'product';
?>
<div class="side-col" style="width: 230px" data-min-width="230">
  <div class="cell">
    <div class="tabs">
      <ul class='nav nav-tabs'>
        <?php foreach($lang->doclib->tabList as $tabValue => $tabName):?>
        <?php if($this->config->global->flow == 'onlyTask' and $tabValue == 'product') continue;?>
        <?php if(($this->config->global->flow == 'onlyStory' or $this->config->global->flow == 'onlyTest') and $tabValue == 'project') continue;?>
        <?php $activeClass = $tabValue == $type ? 'active' : '';?>
        <li class='<?php echo $activeClass;?>'><?php echo html::a("#{$tabValue}", $tabName, '', "data-tab");?></li>
        <?php endforeach;?>
      </ul>
      <div class="tab-content">
        <?php foreach($lang->doclib->tabList as $tabValue => $tabName):?>
        <?php if($this->config->global->flow == 'onlyTask' and $tabValue == 'product') continue;?>
        <?php if(($this->config->global->flow == 'onlyStory' or $this->config->global->flow == 'onlyTest') and $tabValue == 'project') continue;?>
        <?php $activeClass = $tabValue == $type ? 'active' : '';?>
        <div class="tab-pane <?php echo $activeClass;?>" id="<?php echo "$tabValue";?>">
          <ul data-name="docsTree" data-ride="tree" data-initial-state="preserve" class="tree no-margin">
            <?php if(isset($sideSubLibs[$tabValue])):?>
            <?php foreach($sideLibs[$tabValue] as $tabMenu):?>
            <?php
            $customLibCount = 0;
            $mainLibID      = 0;
            if(isset($sideSubLibs[$tabValue][$tabMenu->id]))
            {
                foreach($sideSubLibs[$tabValue][$tabMenu->id] as $subLibID => $subLibName)
                {
                    if(is_numeric($subLibID) and !empty($mainLibID)) $customLibCount += 1;
                    if(is_numeric($subLibID) and empty($mainLibID)) $mainLibID = $subLibID;
                }
            }

            $icon        = $tabValue == 'product' ? "<i class='icon icon-cube'></i> " : "<i class='icon icon-stack'></i> ";
            $activeClass = ($this->methodName == 'objectlibs' && $type == $tabValue && $object->id == $tabMenu->id) ? 'active' : '';
            $activeClass = ($this->methodName == 'browse' && isset($currentLib->id) && $currentLib->id == $mainLibID) ? 'active' : $activeClass;
            ?>
            <li <?php echo "class='$activeClass'";?>>
              <?php if($customLibCount > 0):?>
              <?php echo html::a($this->createLink('doc', 'objectLibs', "type=$tabValue&objectID=$tabMenu->id"), $icon . $tabMenu->name, '', "class='text-ellipsis' title='{$tabMenu->name}'");?>
              <?php else:?>
              <?php echo html::a($this->createLink('doc', 'browse', "libID=$mainLibID"), $icon . $tabMenu->name, '', "class='text-ellipsis' title='{$tabMenu->name}'");?>
              <?php endif;?>
              <?php if(isset($sideSubLibs[$tabValue][$tabMenu->id])):?>
              <ul>
                <?php foreach($sideSubLibs[$tabValue][$tabMenu->id] as $subLibID => $subLibName):?>
                <?php
                if($subLibID == 'project')
                {
                    $subLibLink  = inlink('allLibs', "type=project&product=$tabMenu->id");
                    $activeClass = ($this->methodName == 'alllibs' && $type == 'project' && $$tabValue == $tabMenu->id) ? "class='active'" : '';
                    $icon        = 'icon-stack';
                }
                elseif($subLibID == 'files')
                {
                    $subLibLink  = inlink('showFiles', "type=$tabValue&objectID=$tabMenu->id");
                    $activeClass = ($this->methodName == 'showfiles' && $type == $tabValue && $object->id == $tabMenu->id) ? "class='active'" : '';
                    $icon        = 'icon-paper-clip';
                }
                else
                {
                    $subLibLink  = inlink('browse', "libID=$subLibID");
                    $activeClass = ($this->methodName == 'browse' && $browseType != 'bymodule' && $subLibID == $libID) ? "class='active'" : '';
                    $icon        = 'icon-folder-outline';
                }
                ?>
                <?php if($customLibCount > 0):?>
                <li <?php echo $activeClass;?>>
                  <?php echo html::a($subLibLink, "<i class='icon {$icon}'></i> " . $subLibName, '', "class='text-ellipsis' title='{$subLibName}'");?>
                <?php endif;?>
                  <?php if(isset($allModules[$subLibID])):?>
                  <?php if($customLibCount > 0):?>
                  <ul>
                  <?php endif;?>
                    <?php foreach($allModules[$subLibID] as $module):?>
                    <?php if($module->parent != 0) continue;?>
                    <li <?php if($this->methodName == 'browse' && $browseType == 'bymodule' && $moduleID == $module->id) echo "class='active'";?>>
                      <?php echo html::a($this->createLink('doc', 'browse', "libID=$subLibID&browseType=byModule&param={$module->id}"), "<i class='icon icon-folder-outline'></i> " . $module->name, '', "class='text-ellipsis' title='{$module->name}'");?>
                      <?php $this->doc->printChildModule($module, $subLibID, $this->methodName, $browseType, $moduleID);?>
                    </li>
                    <?php endforeach;?>
                  <?php if($customLibCount > 0):?>
                  </ul>
                  <?php endif;?>
                  <?php endif;?>
                <?php if($customLibCount > 0):?>
                </li>
                <?php endif;?>
                <?php if($customLibCount == 0 and !is_numeric($subLibID)):?>
                <li>
                <?php echo html::a($subLibLink, "<i class='icon {$icon}'></i> " . $subLibName, '', "class='text-ellipsis' title='{$subLibName}'");?>
                </li>
                <?php endif;?>
                <?php endforeach;?>
              </ul>
              <?php endif;?>
            </li>
            <?php endforeach;?>
            <?php else:?>

            <?php foreach($sideLibs[$tabValue] as $sideLibID => $sideLibName):?>
              <?php if($tabValue == 'book'):?>
              <?php include './bookside.html.php';?>
              <?php else:?>
              <?php
              $activeClass = ($this->methodName == 'objectlibs' && $type == $tabValue && $object->id == $sideLibID) ? 'active' : '';
              $activeClass = ($this->methodName == 'browse' && isset($currentLib->id) && $currentLib->id == $sideLibID) ? 'active' : $activeClass;
              ?>
              <li <?php echo "class='$activeClass'";?>>
                <?php echo html::a($this->createLink('doc', 'browse', "libID=$sideLibID"), "<i class='icon icon-folder-o'></i> " . $sideLibName, '', "class='text-ellipsis' title='{$sideLibName}'");?>
                <?php if(isset($allModules[$sideLibID])):?>
                <ul>
                  <?php foreach($allModules[$sideLibID] as $module):?>
                  <?php if($module->parent != 0) continue;?>
                  <li <?php if($this->methodName == 'browse' && $browseType == 'bymodule' && $moduleID == $module->id) echo "class='active'";?>>
                    <?php echo html::a($this->createLink('doc', 'browse', "libID=$sideLibID&browseType=byModule&param={$module->id}"), "<i class='icon icon-folder-outline'></i> " . $module->name, '', "class='text-ellipsis' title='{$module->name}'");?>
                    <?php $this->doc->printChildModule($module, $sideLibID, $this->methodName, $browseType, $moduleID);?>
                  </li>
                  <?php endforeach;?>
                </ul>
                <?php endif;?>
              </li>
              <?php endif;?>
            <?php endforeach;?>

            <?php endif;?>
          </ul>
        </div>
        <?php endforeach;?>
      </div>
    </div>
    <div class='side-footer'><?php echo html::a('###', "<i class='icon-cog'></i> {$lang->doc->customShowLibs}", '', "class='setting text-secondary small' data-target='#settingModal' data-toggle='modal'");?></div>
  </div>
  <div class='modal fade' id='settingModal' aria-hidden="true">
    <div class='modal-dialog mw-400px'>
      <div class='modal-content'>
        <div class='modal-header'>
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang->close;?></span></button>
          <strong><?php echo $lang->doc->customShowLibs;?></strong>
        </div>
        <div class='modal-body'>
          <form action='<?php echo $this->createLink('custom', 'ajaxSetDoc');?>' target='hiddenwin' method='post'>
            <table class='table table-form'>
              <tr>
                <td><?php echo html::checkbox('showLibs', $lang->doc->customShowLibsList, $config->doc->custom->showLibs);?></td>
              </tr>
              <tr>
                <td><?php echo html::submitButton();?></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
