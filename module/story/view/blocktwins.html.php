<?php
$lang->story->currentBranch = sprintf($this->lang->story->currentBranch, $this->lang->product->branchName[$product->type]);
js::set('relieveURL',  inlink('ajaxRelieveTwins'));
js::set('storyID',     $story->id);
js::set('relieved',    $lang->story->relieved);
js::set('relievedTip', $lang->story->relievedTip);
js::set('cancel',      $lang->cancel);
$canViewLinkStory = common::hasPriv('story', 'view');
$canRelieved      = common::hasPriv('story', 'relieved');
array_unshift($twins, $story);
$class = isonlybody() ? 'showinonlybody' : 'iframe';

foreach($twins as $twin)
{
    $id         = $twin->id;
    $title      = '#' . $id . ' '. $twin->title;
    $branch     = isset($branches[$twin->branch]) ? $branches[$twin->branch] : '';
    $stage      = $lang->story->stageList[$twin->stage];
    $labelClass = $story->branch == $twin->branch ? 'label-primary' : '';
    $hide       = $this->app->rawMethod == 'edit' ? '' : 'hide';

    echo "<li title='$title' class='twins'>";
    echo "<span class='label {$labelClass} label-outline label-badge' title='$branch'>{$branch}</span> ";
    echo "<span class='label label-outline' title='{$stage}'>{$stage}</span> ";
    echo ($canViewLinkStory ? html::a($this->createLink('story', 'view', "id=$id", '', true), "$title", '', "class='$class viewlink' data-width='90%'") : "$title");
    if($canRelieved) echo "<a class='unlink $hide' data-id='$id' data-toggle='popover'><i class='icon icon-unlink btn-info'></i></a>";
    echo "</li>";
}
?>
