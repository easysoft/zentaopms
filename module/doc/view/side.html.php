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
    <ul id="docsTree" data-ride="tree" class="tree no-margin">
      <li class="open">
        <a class="text-muted tree-toggle"><?php echo $lang->doc->fast;?></a>
        <ul>
          <?php foreach($lang->doc->fastMenuList as $menuType => $menu):?>
          <li><?php echo html::a($this->createLink('doc', 'browse', "libID=0&browseType={$menuType}"), "<i class='icon {$lang->doc->fastMenuIconList[$menuType]}'></i> {$menu}");?>
          <?php endforeach;?>
        </ul>
      </li>
      <li class="open">
        <?php echo html::a($this->createLink('doc', 'allLibs', "type=product"), $lang->productCommon, '', "class='text-muted'");?>
        <ul>
          <?php foreach($products as $product):?>
          <li>
            <?php echo html::a($this->createLink('doc', 'objectLibs', "type=product&objectID=$product->id"), "<i class='icon icon-cube'></i> " . $product->name);?>
            <?php if(isset($productSubLibs[$product->id])):?>
            <ul>
              <?php foreach($productSubLibs[$product->id] as $subLibID => $subLibName):?>
              <?php
              if($subLibID == 'project')
              {
                  $subLibLink = inlink('allLibs', "type=project&product=$product->id");
                  $icon    = 'icon-stack';
              }
              elseif($subLibID == 'files')
              {
                  $subLibLink = inlink('showFiles', "type=product&objectID=$product->id");
                  $icon    = 'icon-paper-clip';
              }
              else  
              {
                  $subLibLink = inlink('browse', "libID=$subLibID");
                  $icon    = 'icon-folder-outline';
              }
              ?>
              <li>
                <?php echo html::a($subLibLink, "<i class='icon {$icon}'></i> " . $subLibName);?>
                <?php if(isset($allModules[$subLibID])):?>
                <ul>
                  <?php foreach($allModules[$subLibID] as $module):?>
                  <li><?php echo html::a($this->createLink('doc', 'browse', "libID=$subLibID&browseType=byModule&param={$module->id}"), "<i class='icon icon-folder-outline'></i> " . $module->name);?></li>
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
      <li>
        <?php echo html::a($this->createLink('doc', 'allLibs', "type=project"), $lang->projectCommon, '', "class='text-muted'");?>
        <ul>
          <?php foreach($projects as $project):?>
          <li>
            <?php echo html::a($this->createLink('doc', 'objectLibs', "type=project&objectID=$project->id"), "<i class='icon icon-stack'></i> " . $project->name);?>
            <?php if(isset($projectSubLibs[$project->id])):?>
            <ul>
              <?php foreach($projectSubLibs[$project->id] as $subLibID => $subLibName):?>
              <?php
              if($subLibID == 'files')
              {
                  $subLibLink = inlink('showFiles', "type=project&objectID=$project->id");
                  $icon = 'icon-paper-clip';
              }
              else 
              {
                  $subLibLink = inlink('browse', "libID=$subLibID");
                  $icon = 'icon-folder-o';
              }
              ?>
              <li>
                <?php echo html::a($subLibLink, "<i class='icon $icon'></i> " . $subLibName);?>
                <?php if(isset($allModules[$subLibID])):?>
                <ul>
                  <?php foreach($allModules[$subLibID] as $module):?>
                  <li><?php echo html::a($this->createLink('doc', 'browse', "libID=$subLibID&browseType=byModule&param={$module->id}"), "<i class='icon icon-folder-outline'></i> " . $module->name);?></li>
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
      <li>
        <?php echo html::a($this->createLink('doc', 'allLibs', "type=custom"), $lang->doc->custom, '', "class='text-muted'");?>
        <ul>
          <?php foreach($customLibs as $subLibID => $subLibName):?>
          <li>
            <?php echo html::a(inlink('browse', "libID=$subLibID"), "<i class='icon icon-folder-outline'></i> " . $subLibName);?>
            <?php if(isset($allModules[$subLibID])):?>
            <ul>
              <?php foreach($allModules[$subLibID] as $module):?>
              <li><?php echo html::a($this->createLink('doc', 'browse', "libID=$subLibID&browseType=byModule&param={$module->id}"), "<i class='icon icon-folder-outline'></i> " . $module->name);?></li>
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
