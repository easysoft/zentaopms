<?php 
$this->session->set('bugID', $bug->id);
if($this->session->bugType) die(js::locate($this->createLink('my', 'bug', "type={$this->session->bugType}"), 'parent'));
die(js::locate($this->createLink('bug', 'browse', "productID=$productID"), 'parent'));
?>
