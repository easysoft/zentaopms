<?php
js::set('relieveURL', inlink('ajaxRelieveSibling'));
$canViewLinkStory = common::hasPriv('story', 'view');
foreach($siblings as $id => $story)
{
    $title = $id . ' '. $story->title;
    echo "<li title='$title' class='sibling'>";
    echo "<span class='label label-outline label-badge'>{$branches[$story->branch]}</span> ";
    echo ($canViewLinkStory ? html::a($this->createLink('story', 'view', "id=$id", '', true), "$title", '', "class='iframe' data-width='80%'") : "$id $title");
    echo "<a class='unlink hide' data-id='$id' data-toggle='popover'><i class='icon icon-unlink'></i></a>";
    echo "</li>";
}
?>
