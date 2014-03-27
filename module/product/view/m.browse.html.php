<?php include '../../common/view/m.header.html.php';?>
</div>
<?php foreach($stories as $story):?>
<?php if($story->status == 'closed') continue;?>
<div  data-role="collapsible-set">
  <div data-role="collapsible" data-collapsed="<?php echo $this->session->storyID == $story->id ? 'false' : 'true'?>" class='collapsible'>
    <?php if($this->session->storyID == $story->id) echo "<script>showDetail('story', $story->id);</script>";?>
    <h1 onClick="showDetail('story', <?php echo $story->id;?>)"><?php echo $story->title;?></h1>

    <div id='item<?php echo $story->id;?>'></div>
    <div data-role='content' class='text-center'>
      <?php
      if(!$story->deleted)
      {
          common::printIcon('story', 'review',     "storyID=$story->id", $story);
          common::printIcon('story', 'close',      "storyID=$story->id", $story);
          common::printIcon('story', 'delete', "storyID=$story->id", '', '', '', 'hiddenwin');
      }
      ?>
    </div>

  </div>
</div>
<?php endforeach;?>
<?php $pager->show('left', 'mobile')?>
<?php include '../../common/view/m.footer.html.php';?>
