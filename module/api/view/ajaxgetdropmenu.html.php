<style>
#tabContent {margin-top: 5px; max-width: 220px}
.objectTree ul {list-style: none; margin: 0}
.objectTree .objects>ul>li>div {display: flex; flex-flow: row nowrap; justify-content: flex-start; align-items: center;}
.objectTree li>a, div.hide-in-search>a {display: block; padding: 2px 10px 2px 5px; overflow: hidden; line-height: 20px; text-overflow: ellipsis; white-space: nowrap; border-radius: 4px;}
.objectTree .tree li>.list-toggle {line-height: 24px;}
.objectTree .tree li.has-list.open:before {content: unset;}

#swapper li>div.hide-in-search>a:focus, #swapper li>div.hide-in-search>a:hover {color: #838a9d; cursor: default;}
#swapper li > a {margin-top: 4px; margin-bottom: 4px;}
#swapper li {padding-top: 0; padding-bottom: 0;}
#swapper .tree li>.list-toggle {top: -1px;}

#closed {width: 90px; height: 25px; line-height: 25px; background-color: #ddd; color: #3c495c; text-align: center; margin-left: 15px; border-radius: 2px;}
#gray-line {width: 230px;height: 1px; margin-left: 10px; margin-bottom:2px; background-color: #ddd;}
#dropMenu.has-search-text .hide-in-search {display: flex;}
#swapper li>.selected {color: #0c64eb!important; background: #e9f2fb!important;}
 .tree-product:last-child {border-bottom: 1px solid #eee;}
</style>
<?php
$normalObjectsHtml = '<ul class="tree noProgram">';
$closedObjectsHtml = '<ul class="tree noProgram">';
$link              = $this->createLink('api', 'index');
$selected          = $objectType == 'nolink' ? 'selected' : '';
$dataParams        = 'data-type="nolink" data-id="0"';

if(!empty($nolinkLibs)) $normalObjectsHtml .= "<li>" . html::a($link, $lang->api->noLinked, '', "class='clickable $selected' title='{$lang->api->noLinked}' $dataParams data-key='" . zget($objectsPinYin, $lang->api->noLinked, '') . "'") . '</li>';
foreach(array('product', 'project') as $moduleType)
{
    if(!empty($normalObjects[$moduleType]))
    {
        foreach($normalObjects[$moduleType] as $normalObjectID => $normalObjectName)
        {
            $dataParams         = "data-type='$moduleType' data-id='$normalObjectID'";
            $selected           = $normalObjectID == $objectID ? 'selected' : '';
            $normalObjectsHtml .= "<li>" . html::a($link, "<i class='icon icon-$moduleType'></i> $normalObjectName", '', "class='$selected clickable' title='{$normalObjectName}' $dataParams data-key='" . zget($objectsPinYin, $normalObjectName, '') . "'") . '</li>';
        }
    }

    if(!empty($closedObjects[$moduleType]))
    {
        foreach($closedObjects[$moduleType] as $closedObjectID => $closedObjectName)
        {
            $dataParams         = "data-type='$moduleType' data-id='$closedObjectID'";
            $selected           = $closedObjectID == $objectID ? 'selected' : '';
            $closedObjectsHtml .= '<li>' . html::a($link, "<i class='icon icon-$moduleType'></i> $closedObjectName", '', "class='$selected clickable' title='{$closedObjectName}' $dataParams data-key='" . zget($objectsPinYin, $closedObjectName, '') . "'") . '</li>';
        }
    }

    if($moduleType  == 'product')
    {
        if(!empty($normalObjects['project']) and (!empty($nolinkLibs) or !empty($normalObjects['product']))) $normalObjectsHtml .= '<li class="divider"></li>';
        if(!empty($closedObjects['project']) and !empty($normalObjects['product'])) $closedObjectsHtml .= '<li class="divider"></li>';
    }
}
$normalObjectsHtml .= '</ul>';
$closedObjectsHtml .= '</ul>';
?>

<div class="table-row">
<div class="table-col <?php if(!empty($closedObjects['product']) or !empty($closedObjects['project'])) echo 'col-left'?>">
    <div class='list-group'>
      <div class="tab-content objectTree" id="tabContent">
        <div class="tab-pane objects active">
          <?php echo $normalObjectsHtml;?>
        </div>
      </div>
    </div>
    <?php if(!empty($closedObjects['product']) or !empty($closedObjects['project'])):?>
    <div class="col-footer">
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->doc->closed?><i class='icon icon-angle-right'></i></a>
    </div>
    <?php endif;?>
  </div>
  <div id="gray-line" hidden></div>
  <div id="closed" hidden><?php echo $lang->doc->closed?></div>
  <div class="table-col col-right objectTree">
    <div class='list-group objects'><?php echo $closedObjectsHtml;?></div>
  </div>
</div>

<script>
$(function()
{
    $('#swapper [data-ride="tree"]').tree('expand');

    <?php if(isset($closedObjects[$objectID])):?>
    $('.col-footer .toggle-right-col').click(function(){ scrollToSelected(); })
    <?php else:?>
    scrollToSelected();
    <?php endif;?>

    $('.nav-tabs li span').hide();
    $('.nav-tabs li.active').find('span').show();

    $('.nav-tabs>li a').click(function()
    {
        if($('#swapper input[type="search"]').val() == '')
        {
            $(this).siblings().show();
            $(this).parent().siblings('li').find('span').hide();
        }
    })

    $('#swapper #dropMenu .search-box').on('onSearchChange', function(event, value)
    {
        if(value != '')
        {
            $('div.hide-in-search').siblings('i').addClass('hide-in-search');
            $('.nav-tabs li span').hide();
        }
        else
        {
            $('div.hide-in-search').siblings('i').removeClass('hide-in-search');
            $('li.has-list div.hide-in-search').removeClass('hidden');
            $('.nav-tabs li.active').find('span').show();
        }

        if($('.form-control.search-input').val().length > 0)
        {
            $('#closed').attr("hidden", false);
            $('#gray-line').attr("hidden", false);
        }
        else
        {
            $('#closed').attr("hidden", true);
            $('#gray-line').attr("hidden", true);
        }
    });

    $('#swapper #dropMenu').on('onSearchComplete', function()
    {
        var listItem = $(this).find('.has-list');
        listItem.each(function()
        {
            $(this).css('display','')
            var $hidden = $(this).find('.hidden');
            var $item   = $(this).find('.search-list-item');
            if($hidden.length == $item.length) $(this).css('display','none');
        });

        if($('.list-group.objects').height() == 0)
        {
            $('#closed').attr("hidden", true);
            $('#gray-line').attr("hidden", true);
        }
    });

    $('.objectTree a').on('click', function()
    {
        var objectType = $(this).data('type');
        var objectID   = $(this).data('id');
        $.cookie('objectType', objectType, {expires: config.cookieLife, path: config.webRoot});
        $.cookie('objectID', objectID, {expires: config.cookieLife, path: config.webRoot});
    });
})
</script>
