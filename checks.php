<?php  
 $system_info = array(
            'web_server' => $_SERVER["SERVER_SOFTWARE"],
            'php_version' => PHP_VERSION,
            'zlib' => function_exists('gzclose') ?  '正常' : '异常',
            'curl' => function_exists("curl_getinfo") ? '正常' : '异常',
        );

		exit(print_r($system_info));
		?>