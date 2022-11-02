<?php
$lang->story->currentBranch = sprintf($this->lang->story->currentBranch, $this->lang->product->branchName[$product->type]);
js::set('relieveURL',  inlink('ajaxRelieveSibling'));
js::set('storyID',     $story->id);
js::set('relieved',    $lang->story->relieved);
js::set('relievedTip', $lang->story->relievedTip);
js::set('cancel',      $lang->cancel);
$canViewLinkStory = common::hasPriv('story', 'view');
$canRelieved      = common::hasPriv('story', 'relieved');
array_unshift($siblings, $story);

foreach($siblings as $sibling)
{
    $id = $sibling->id;
    $title = $id . ' '. $sibling->title;
    $branch = $story->branch == $sibling->branch ? $lang->story->currentBranch : $branches[$sibling->branch];
    echo "<li title='$title' class='sibling'>";
    echo "<span class='label label-outline label-badge'>{$branch}</span> ";
    echo ($canViewLinkStory ? html::a($this->createLink('story', 'view', "id=$id", '', true), "$title", '', "class='iframe' data-width='80%'") : "$id $title");
    if($canRelieved) echo "<a class='unlink hide' data-id='$id' data-toggle='popover'><i class='icon icon-unlink btn-info'></i></a>";
    echo "</li>";
}
?>
