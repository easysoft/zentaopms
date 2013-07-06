<?php 
$this->session->set('bugID', $bug->id);
if($this->session->bugType) die($this->locate($this->createLink('my', 'bug', "type={$this->session->bugType}")));
die($this->locate($this->createLink('bug', 'browse', "productID=$productID")));
?>
