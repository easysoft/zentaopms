<?php include '../../common/view/m.header.html.php';?>
</div>
<?php foreach($stories as $story):?>
<?php if($story->status == 'closed') continue;?>
<div  data-role="collapsible-set">
  <div data-role="collapsible" data-collapsed="<?php echo $this->session->storyID == $story->id ? 'false' : 'true'?>">
    <?php if($this->session->storyID == $story->id) echo "<script>showDetail('story', $story->id);</script>";?>
    <h1 onClick="showDetail('story', <?php echo $story->id;?>)"><?php echo $story->title;?></h1>

    <div id='item<?php echo $story->id;?>'></div>
    <div data-role='navbar'>
      <ul>
        <?php
        if(!$story->deleted)
        {
            common::printIcon('story', 'review',     "storyID=$story->id", $story);
            common::printIcon('story', 'close',      "storyID=$story->id", $story, '', '', '', 'iframe');
            common::printIcon('story', 'activate',   "storyID=$story->id", $story, '', '', '', 'iframe');
            common::printIcon('story', 'delete', "storyID=$story->id", '', '', '', 'hiddenwin');
        }
        ?>
      </ul>
    </div>

  </div>
</div>
<?php endforeach;?>
<p><?php $pager->show('right', 'short')?></p>
<?php include '../../common/view/m.footer.html.php';?>
