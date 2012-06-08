<?php
public function hello2()
{
    echo $this->loadExtension('test')->hello();    // Load testMisc class from test.class.php in ext/model/class.
    return $this->testMisc->hello();               // After loading, can use $this->testMisc to call it.
}
