<style>
#navTabs {position: sticky; top: 0; background: #fff; z-index: 950;}
#navTabs>li {padding: 0px 10px; display: inline-block}
#navTabs>li>span {display: inline-block;}
#navTabs>li>a {margin: 0!important; padding: 8px 0px; display: inline-block}

#tabContent {margin-top: 5px; z-index: 900; max-width: 220px}
.executionTree ul {list-style: none; margin: 0}
.executionTree .executions>ul>li>div {display: flex; flex-flow: row nowrap; justify-content: flex-start; align-items: center;}
.executionTree .executions>ul>li label {background: rgba(255,255,255,0.5); line-height: unset; color: #838a9d; border: 1px solid #d8d8d8; border-radius: 2px; padding: 1px 4px;}
.executionTree li a i.icon {font-size: 15px !important;}
.executionTree li a i.icon:before {min-width: 16px !important;}
.executionTree li .label {position: unset; margin-bottom: 0;}
.executionTree li>a, div.hide-in-search>a {display: block; padding: 2px 10px 2px 5px; overflow: hidden; line-height: 20px; text-overflow: ellipsis; white-space: nowrap; border-radius: 4px;}
.executionTree .tree li>.list-toggle {line-height: 24px;}
.executionTree .tree li.has-list.open:before {content: unset;}

#swapper li>div.hide-in-search>a:focus, #swapper li>div.hide-in-search>a:hover {color: #838a9d; cursor: default;}
#swapper li > a {margin-top: 4px; margin-bottom: 4px;}
#swapper li {padding-top: 0; padding-bottom: 0;}
#swapper .tree li>.list-toggle {top: -1px;}

#closed {width: 90px; height: 25px; line-height: 25px; background-color: #ddd; color: #3c495c; text-align: center; margin-left: 15px; border-radius: 2px;}
#gray-line {width: 230px;height: 1px; margin-left: 10px; margin-bottom:2px; background-color: #ddd;}
#dropMenu.has-search-text .hide-in-search {display: flex;}
#swapper li>.selected {color: #0c64eb!important; background: #e9f2fb!important;}
#dropMenu .col-left   {padding-bottom: 0px;}
</style>
<?php
$dimensionNames = array();
foreach($dimensions as $id => $dimension) $dimensionNames[$id] = $dimension->name;
$dimensionsPinYin = common::convert2Pinyin($dimensionNames);

$dimensionsHtml = '<ul class="tree tree-angles" data-ride="tree">';
foreach($dimensions as $id => $dimension)
{
    $dimensionLink = 'javascript:;';
    $moduleMethod  = $currentModule . '-' . $currentMethod;
    if(isset($config->dimension->changeDimensionLink[$moduleMethod]))
    {
        $linkArray = explode('|', $config->dimension->changeDimensionLink[$moduleMethod]);
        if(count($linkArray) != 3) continue;

        list($linkModule, $linkMethod, $linkParams) = $linkArray;
        $linkParams = $moduleMethod == 'tree-browsegroup' ? sprintf($linkParams, $id, $type) : sprintf($linkParams, $id);
        $dimensionLink = $this->createLink($linkModule, $linkMethod, $linkParams);
    }

    $selected        = $id == $dimensionID ? 'selected' : '';
    $dimensionsHtml .= '<li>' . html::a($dimensionLink, $dimensionNames[$id], '', "class='$selected clickable' title='{$dimensionNames[$id]}' data-key='" . zget($dimensionsPinYin, $dimensionNames[$id], '') . "' data-app='{$app->tab}'") . '</li>';
}
$dimensionsHtml .= '</ul>';
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <div class="tab-content dimensionTree" id="tabContent">
        <div class="tab-pane dimensions active">
          <?php echo $dimensionsHtml;?>
        </div>
      </div>
    </div>
  </div>
</div>
