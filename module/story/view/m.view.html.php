<?php $this->session->set('storyID', $story->id);?>
<?php die(js::locate($this->createLink('product', 'browse', "productID=$product->id"), 'parent'));?>
