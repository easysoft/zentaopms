<style>
#mainMenu #query.btn-group li {position: relative;}
#mainMenu #query.btn-group li a{margin-right:20px;}
#mainMenu #query.btn-group li .btn-delete{
  padding:0 7px;
  position: absolute;
  right: -10px;
  top: -5px;
  display: block;
  width: 20px;
  text-align: center;
}
</style>
<?php
if(isset($lang->custom->queryList))
{
    echo '<div class="btn-group" id="query">';
    $active  = '';
    $current = $menuItem->text;
    $dropdownHtml = "<ul class='dropdown-menu'>";
    foreach($lang->custom->queryList as $queryID => $queryTitle)
    {
        if($isBySearch and $queryID == $param)
        {
            $active  = 'btn-active-text';
            $current = "<span class='text'>{$queryTitle}</span> <span class='label label-light label-badge'>{$pager->recTotal}</span>";
        }
        $dropdownHtml .= '<li' . ($param == $queryID ? " class='active'" : '') . '>';
        $dropdownHtml .= html::a(sprintf($searchBrowseLink, $queryID), $queryTitle);
        $dropdownHtml .= html::a("###", "<i class='icon icon-close'></i>", '', "class='btn-delete' data-id={$queryID} onclick='removeQueryFromMenu(this)'");
        $dropdownHtml .= '</li>';
    }
    $dropdownHtml .= '</ul>';

    echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link $active'");
    echo $dropdownHtml;
    echo '</div>';
}
?>
<script>
function removeQueryFromMenu(obj)
{
    var $obj = $(obj);
    var link = createLink('search', 'ajaxRemoveMenu', "queryID=" + $obj.data('id'));
    $.get(link, function()
    {
        $obj.closest('li').remove();
        if($('#mainMenu #query.btn-group').find('li').length == 0) $('#mainMenu #query.btn-group').remove();
    })
    return false;
}
</script>
