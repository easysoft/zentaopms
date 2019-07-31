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
$sideWidth = common::checkNotCN() ? '270' : '238';
?>
<div class="side-col" style="width:<?php echo $sideWidth;?>px" data-min-width="<?php echo $sideWidth;?>">
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
            <?php if(empty($sideLibs[$tabValue])):?>
            <li>
            <?php
            $text = zget($lang->doclib->create, $tabValue, '');
            if($text and common::hasPriv($tabValue, 'create')) echo html::a($this->createLink($tabValue, 'create'), $text, '', "class='text-ellipsis'");
            ?>
            </li>
            <?php else:?>
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
            <?php endif;?>
            <?php else:?>

            <?php if(empty($sideLibs[$tabValue])):?>
            <li>
            <?php
            $text = zget($lang->doclib->create, $tabValue, '');
            if($text and common::hasPriv('doc', 'createLib')) echo html::a($this->createLink('doc', 'createLib', "type={$tabValue}"), $text, '', "class='iframe' data-width='70%'");
            ?>
            </li>
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
            <?php endif;?>
          </ul>
        </div>
        <?php endforeach;?>
      </div>
    </div>
    <div class='side-footer clearfix'>
      <span id='customShowLibsBox'>
      <?php echo html::a('###', "<i class='icon-cog'></i> {$lang->doc->customShowLibs}", '', "class='setting text-secondary small' data-target='#settingModal' data-toggle='modal'");?>
      </span>
      <span id='orderBox'>
      <?php
      if(common::hasPriv('doc', 'sort'))
      {
          echo html::a('###', "<i class='icon-sort'></i> {$lang->doc->orderLib}", '', "id='orderLib' class='text-secondary small " . (($type != 'product' and $type !='project') ? '' : 'hidden') . "' style='padding-left:5px;'");
          echo html::a('javascript:saveOrder()', "<i class='icon-checked'></i> {$lang->save}", '', "id='saveOrder' class='text-secondary small hidden' style='padding-left:10px;'");
      }
      ?>
      </span>
    </div>
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
<script>
$(function()
{
    if($.cookie('docSideType'))
    {
        var type = $.cookie('docSideType');
        var $tabs = $('#mainRow .side-col .tabs');
        if($tabs.find('.tab-content .tab-pane#' + type).length >0)
        {
            $tabs.find('.nav-tabs li').removeClass('active');
            $tabs.find('.nav-tabs li a[href="#' + type + '"]').parent().addClass('active');
            $tabs.find('.tab-content .tab-pane').removeClass('active');
            $tabs.find('.tab-content .tab-pane#' + type).addClass('active');
        }
        $.cookie('docSideType', '');
        $('#mainRow .side-col .side-footer #orderLib').toggleClass('hidden', (type == 'product' || type == 'project'));
    }

    $('#mainRow .side-col .tabs .nav-tabs li a').click(function()
    {
        var href     = $(this).attr('href');
        var canOrder = !(href.indexOf('product') > 0 || href.indexOf('project') > 0);
        if(!canOrder)
        {
            $(this).closest('.side-col').find('.side-footer #orderLib').addClass('hidden');
            $(this).closest('.side-col').find('.side-footer #saveOrder').addClass('hidden');
        }

        var $orderLib  = $(this).closest('.side-col').find('.side-footer #orderLib');
        var $saveOrder = $(this).closest('.side-col').find('.side-footer #saveOrder');

        var execute = false;
        $(this).on('shown.zui.tab', function()
        {
            if(!execute)
            {
                var $tabPane   = $('#mainRow .side-col .tabs .tab-content .tab-pane.active');
                if($tabPane.find('.libs-group.sort').length > 0 && canOrder)
                {
                    $orderLib.addClass('hidden');
                    $saveOrder.removeClass('hidden');
                    execute = true;
                }
                if($tabPane.find('.libs-group.sort').length == 0 && canOrder)
                {
                    $orderLib.removeClass('hidden');
                    $saveOrder.addClass('hidden');
                    execute = true;
                }
            }
        });
    });

    $('#orderLib').click(function()
    {
        var $tabPane = $('#mainRow .side-col .tabs .tab-content .tab-pane.active');
        var type     = $tabPane.attr('id');
        $.get(createLink('doc', 'sort', "type=" + type), function(data)
        {
            $tabPane.html(data);
            $tabPane.closest('.side-col').find('.side-footer #orderBox #orderLib').addClass('hidden');
            $tabPane.closest('.side-col').find('.side-footer #orderBox #saveOrder').removeClass('hidden');
            $tabPane.find('.libs-group.sort').sortable(
            {
                trigger:  '.lib',
                selector: '.lib',
            });
        });
    });
});

function saveOrder()
{
    var $tabPane  = $('#mainRow .side-col .tabs .tab-content .tab-pane.active');
    var type      = $tabPane.attr('id');
    var orders    = {};
    var orderNext = 1;

    $tabPane.find('.libs-group.sort .lib').not('.files').not('.addbtn').each(function()
    {
        orders[$(this).data('id')] = orderNext ++;
    });

    $.post(createLink('doc', 'sort'), orders, function(data)
    {
        if(data.result == 'success')
        {
            $.cookie('docSideType', type);
            return location.reload();
        }
        else
        {
            bootbox.alert(data.message);
        }
    }, 'json');
}
</script>
</div>
