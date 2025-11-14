#!/usr/bin/env php
<?php

/**

title=测试 fileModel::cropImage();
timeout=0
cid=16496

- 执行fileTest模块的cropImageTest方法，参数是'/valid/image.jpg', '/target/output.jpg', 10, 10, 100, 100  @exception
- 执行fileTest模块的cropImageTest方法，参数是'/valid/image.jpg', '/target/output.jpg', 0, 0, 50, 50, 200, 150  @exception
- 执行fileTest模块的cropImageTest方法，参数是'/nonexistent/image.jpg', '/target/output.jpg', 10, 10, 100, 100  @exception
- 执行fileTest模块的cropImageTest方法，参数是'/valid/image.jpg', '/target/output.jpg', -5, -5, 100, 100  @exception
- 执行fileTest模块的cropImageTest方法，参数是'', '', 0, 0, 0, 0  @exception

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';

su('admin');

$fileTest = new fileTest();

r($fileTest->cropImageTest('/valid/image.jpg', '/target/output.jpg', 10, 10, 100, 100)) && p() && e('exception');
r($fileTest->cropImageTest('/valid/image.jpg', '/target/output.jpg', 0, 0, 50, 50, 200, 150)) && p() && e('exception');
r($fileTest->cropImageTest('/nonexistent/image.jpg', '/target/output.jpg', 10, 10, 100, 100)) && p() && e('exception');
r($fileTest->cropImageTest('/valid/image.jpg', '/target/output.jpg', -5, -5, 100, 100)) && p() && e('exception');
r($fileTest->cropImageTest('', '', 0, 0, 0, 0)) && p() && e('exception');