<?php

add_filter("option_active_plugins",  function ($value, $option) {
    if ('on' == get_option('zjps_speedup_enable', 'off')) {
        $optimized_urls = get_option('zjps_speedup_url_plugins_list', []);

        $server_url = sanitize_url($_SERVER['SCRIPT_URI']);
        if (filter_var($server_url, FILTER_VALIDATE_URL)) {
            $def_plugins = $optimized_urls[urldecode($server_url)];
            if (isset($def_plugins)) {
                foreach ($value as $index => $plugin) {
                    if (in_array($plugin, $def_plugins))
                        unset($value[$index]);
                }
            }
        }
    }
    return $value;
}, 10, 2);
