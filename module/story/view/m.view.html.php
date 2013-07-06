<?php $this->session->set('storyID', $story->id);?>
<?php die($this->locate($this->createLink('product', 'browse', "productID=$product->id")));?>
