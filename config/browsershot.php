<?php

return [
    'node' => env('BROWSERSHOT_NODE_BINARY', '/usr/bin/node'),
    'npm' => env('BROWSERSHOT_NPM_BINARY', '/usr/bin/npm'),
    'temp' => env('BROWSERSHOT_TEMP_DIR', '/tmp'),
    'bin' => env('BROWSERSHOT_CHROME_PATH', '/usr/bin/chromium-browser'),
    'timeout' => env('BROWSERSHOT_TIMEOUT', 60),
    'options' => [],
];