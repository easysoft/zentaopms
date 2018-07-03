<?php
$products   = $this->doc->getLimitLibs('product');
$projects   = $this->doc->getLimitLibs('project');
$customLibs = $this->doc->getLimitLibs('custom');
$allModules = $this->loadModel('tree')->getDocStructure();

$productSubLibs = $this->doc->getSubLibGroups('product', array_keys($products));
$projectSubLibs = $this->doc->getSubLibGroups('project', array_keys($projects));
?>
<div class="side-col" style="width: 220px" data-min-width="220">
  <div class="cell">
    <header class="table-row space">
      <form method='post' action='<?php echo $this->createLink('doc', 'browse', "libID=&browseType=fastsearch");?>' class='input-control has-icon-right table-col'>
        <input id="searchDoc" type="searchDoc" name='searchDoc' class="form-control" placeholder="<?php echo $this->lang->doc->searchDoc;?>">
        <button type="submit" class="btn btn-icon btn-link input-control-icon-right"><i class="icon icon-search"></i></button>
      </form>
      <div class="table-col text-right c-sm">
        <?php echo html::a($this->createLink('doc', 'createLib'), "<i class='icon icon-plus'></i>", '', "class='btn btn-secondary btn-icon iframe'");?>
      </div>
    </header>
    <ul id="docsTree" data-name="docsTree" data-ride="tree" data-initial-state="preserve" class="tree no-margin">
      <li class="open">
        <a class="text-muted tree-toggle"><?php echo $lang->doc->fast;?></a>
        <ul>
          <?php foreach($lang->doc->fastMenuList as $menuType => $menu):?>
          <li <?php if($this->methodName == 'browse' && $browseType == $menuType) echo "class='active'";?>><?php echo html::a($this->createLink('doc', 'browse', "libID=0&browseType={$menuType}"), "<i class='icon {$lang->doc->fastMenuIconList[$menuType]}'></i> {$menu}");?>
          <?php endforeach;?>
        </ul>
      </li>
      <?php if($this->config->global->flow != 'onlyTask'):?>
      <li class='open'>
        <?php echo html::a($this->createLink('doc', 'allLibs', "type=product"), $lang->productCommon, '', "class='text-muted'");?>
        <ul>
          <?php foreach($products as $productMenu):?>
          <li <?php if($this->methodName == 'objectlibs' && $type == 'product' && $object->id == $productMenu->id) echo "class='active'";?>>
            <?php echo html::a($this->createLink('doc', 'objectLibs', "type=product&objectID=$productMenu->id"), "<i class='icon icon-cube'></i> " . $productMenu->name, '', "class='text-ellipsis' title='{$productMenu->name}'");?>
            <?php if(isset($productSubLibs[$productMenu->id])):?>
            <ul>
              <?php foreach($productSubLibs[$productMenu->id] as $subLibID => $subLibName):?>
              <?php
              if($subLibID == 'project')
              {
                  $subLibLink  = inlink('allLibs', "type=project&product=$productMenu->id");
                  $activeClass = ($this->methodName == 'alllibs' && $type == 'project' && $product == $productMenu->id) ? "class='active'" : '';
                  $icon        = 'icon-stack';
              }
              elseif($subLibID == 'files')
              {
                  $subLibLink  = inlink('showFiles', "type=product&objectID=$productMenu->id");
                  $activeClass = ($this->methodName == 'showfiles' && $type == 'product' && $object->id == $productMenu->id) ? "class='active'" : '';
                  $icon        = 'icon-paper-clip';
              }
              else  
              {
                  $subLibLink  = inlink('browse', "libID=$subLibID");
                  $activeClass = ($this->methodName == 'browse' && $browseType != 'bymodule' && $subLibID == $libID) ? "class='active'" : '';
                  $icon        = 'icon-folder-outline';
              }
              ?>
              <li <?php echo $activeClass;?>>
                <?php echo html::a($subLibLink, "<i class='icon {$icon}'></i> " . $subLibName, '', "class='text-ellipsis' title='{$subLibName}'");?>
                <?php if(isset($allModules[$subLibID])):?>
                <ul>
                  <?php foreach($allModules[$subLibID] as $module):?>
                  <li <?php if($this->methodName == 'browse' && $browseType == 'bymodule' && $moduleID == $module->id) echo "class='active'";?>>
                    <?php echo html::a($this->createLink('doc', 'browse', "libID=$subLibID&browseType=byModule&param={$module->id}"), "<i class='icon icon-folder-outline'></i> " . $module->name, '', "class='text-ellipsis' title='{$module->name}'");?>
                  </li>
                  <?php endforeach;?>
                </ul>
                <?php endif;?>
              </li>
              <?php endforeach;?>
            </ul>
            <?php endif;?>
          </li>
          <?php endforeach;?>
        </ul>
      </li>
      <?php endif;?>
      <?php if($this->config->global->flow != 'onlyStory' && $this->config->global->flow != 'onlyTest'):?>
      <li>
        <?php echo html::a($this->createLink('doc', 'allLibs', "type=project"), $lang->projectCommon, '', "class='text-muted'");?>
        <ul>
          <?php foreach($projects as $project):?>
          <li <?php if($this->methodName == 'objectlibs' && $type == 'project' && $object->id == $project->id) echo "class='active'";?>>
            <?php echo html::a($this->createLink('doc', 'objectLibs', "type=project&objectID=$project->id"), "<i class='icon icon-stack'></i> " . $project->name, '', "class='text-ellipsis' title='{$project->name}'");?>
            <?php if(isset($projectSubLibs[$project->id])):?>
            <ul>
              <?php foreach($projectSubLibs[$project->id] as $subLibID => $subLibName):?>
              <?php
              if($subLibID == 'files')
              {
                  $subLibLink  = inlink('showFiles', "type=project&objectID=$project->id");
                  $activeClass = ($this->methodName == 'showfiles' && $type == 'project' && $object->id == $project->id) ? "class='active'" : '';
                  $icon        = 'icon-paper-clip';
              }
              else 
              {
                  $subLibLink  = inlink('browse', "libID=$subLibID");
                  $activeClass = ($this->methodName == 'browse' && $browseType != 'bymodule' && $subLibID == $libID) ? "class='active'" : '';
                  $icon        = 'icon-folder-o';
              }
              ?>
              <li <?php echo $activeClass;?>>
                <?php echo html::a($subLibLink, "<i class='icon $icon'></i> " . $subLibName, '', "class='text-ellipsis' title='{$subLibName}'");?>
                <?php if(isset($allModules[$subLibID])):?>
                <ul>
                  <?php foreach($allModules[$subLibID] as $module):?>
                  <li <?php if($this->methodName == 'browse' && $browseType == 'bymodule' && $moduleID == $module->id) echo "class='active'";?>>
                    <?php echo html::a($this->createLink('doc', 'browse', "libID=$subLibID&browseType=byModule&param={$module->id}"), "<i class='icon icon-folder-outline'></i> " . $module->name, '', "class='text-ellipsis' title='{$module->name}'");?>
                  </li>
                  <?php endforeach;?>
                </ul>
                <?php endif;?>
              </li>
              <?php endforeach;?> 
            </ul>
            <?php endif;?>
          </li>
          <?php endforeach;?>
        </ul>
      </li>
      <?php endif;?>
      <li <?php if($this->methodName == 'alllibs' && $type == 'custom') echo "class='active'";?>>
        <?php echo html::a($this->createLink('doc', 'allLibs', "type=custom"), $lang->doc->custom, '', "class='text-muted'");?>
        <ul>
          <?php foreach($customLibs as $subLibID => $subLibName):?>
          <li <?php if($this->methodName == 'browse' && $browseType != 'bymodule' && $libID == $subLibID) echo "class='active'";?>>
            <?php echo html::a(inlink('browse', "libID=$subLibID"), "<i class='icon icon-folder-outline'></i> " . $subLibName, '', "class='text-ellipsis' title='{$subLibName}'");?>
            <?php if(isset($allModules[$subLibID])):?>
            <ul>
              <?php foreach($allModules[$subLibID] as $module):?>
              <li <?php if($this->methodName == 'browse' && $browseType == 'bymodule' && $moduleID == $module->id) echo "class='active'";?>>
                <?php echo html::a($this->createLink('doc', 'browse', "libID=$subLibID&browseType=byModule&param={$module->id}"), "<i class='icon icon-folder-outline'></i> " . $module->name, '', "class='text-ellipsis' title='{$module->name}'");?>
              </li>
              <?php endforeach;?>
            </ul>
            <?php endif;?>
          </li>
          <?php endforeach;?>
        </ul>
      </li>
    </ul>
  </div>
</div>
