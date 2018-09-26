<?php

require __DIR__.DIRECTORY_SEPARATOR.'../components/Autoload.php';

chdir('..'.DIRECTORY_SEPARATOR);

use \components\Kernel\App;

(new App())->Run();
