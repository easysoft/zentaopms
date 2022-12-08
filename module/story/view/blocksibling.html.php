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
$class = isonlybody() ? 'showinonlybody' : 'iframe';

foreach($siblings as $sibling)
{
    $id         = $sibling->id;
    $title      = '#' . $id . ' '. $sibling->title;
    $branch     = isset($branches[$sibling->branch]) ? $branches[$sibling->branch] : '';
    $stage      = $lang->story->stageList[$sibling->stage];
    $labelClass = $story->branch == $sibling->branch ? 'label-primary' : '';

    echo "<li title='$title' class='sibling'>";
    echo "<span class='label {$labelClass} label-outline label-badge' title='$branch'>{$branch}</span> ";
    echo "<span class='label label-outline' title='{$stage}'>{$stage}</span> ";
    echo ($canViewLinkStory ? html::a($this->createLink('story', 'view', "id=$id", '', true), "$title", '', "class='$class viewlink' data-width='80%'") : "$title");
    if($canRelieved) echo "<a class='unlink hide' data-id='$id' data-toggle='popover'><i class='icon icon-unlink btn-info'></i></a>";
    echo "</li>";
}
?>
