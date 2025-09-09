<?php
declare(strict_types = 1);
class zaiTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('zai');
    }

    public function getTokenTest(): array
    {
        return $this->objectModel->getToken();
    }
}