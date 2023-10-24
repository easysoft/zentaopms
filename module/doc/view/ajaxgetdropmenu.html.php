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
</style>
<?php
$normalObjectsHtml = '<ul class="tree noProgram">';
$closedObjectsHtml = '<ul class="tree noProgram">';
$params            = $method == 'showfiles' ||$objectType == 'custom' ? "type=$objectType&objectID=%s" : "objectID=%s";
$link              = $this->createLink($module, $method, $params);
foreach($normalObjects as $normalObjectID => $normalObjectName)
{
    $selected           = $normalObjectID == $objectID ? 'selected' : '';
    $normalObjectsHtml .= '<li>' . html::a(sprintf($link, $normalObjectID), $normalObjectName, '', "class='$selected clickable' title='{$normalObjectName}' data-key='" . zget($objectsPinYin, $normalObjectName, '') . "'") . '</li>';
}

foreach($closedObjects as $closedObjectID => $closedObjectName)
{
    $selected           = $closedObjectID == $objectID ? 'selected' : '';
    $closedObjectsHtml .= '<li>' . html::a(sprintf($link, $closedObjectID), $closedObjectName, '', "class='$selected clickable' title='{$closedObjectName}' data-key='" . zget($objectsPinYin, $closedObjectName, '') . "'") . '</li>';
}

$normalObjectsHtml .= '</ul>';
$closedObjectsHtml .= '</ul>';
?>

<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <div class="tab-content objectTree" id="tabContent">
        <div class="tab-pane objects active">
          <?php echo $normalObjectsHtml;?>
        </div>
      </div>
    </div>
    <div class="col-footer">
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->doc->closed?><i class='icon icon-angle-right'></i></a>
    </div>
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
})
</script>
