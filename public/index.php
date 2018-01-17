<?php

use Framework\Http\Request;

chdir(dirname(__DIR__));
require 'src/Framework/Http/Request.php';

### Initialization

$request = (new Request())->withQueryParams($_GET)->withParsedBody($_POST);

### Action

$name = $request->getQueryParams()['name'] ?? 'Guest';
header('X-Developer: Julia');
echo 'Hello, ' . $name . '!';