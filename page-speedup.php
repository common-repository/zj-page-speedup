<?php

/**
 * Plugin Name:       ZJ Page Speedup
 * Plugin URI:        https://demo-cj.net/zj-page-speed-up-plugin/
 * Description:       Speed up your page loading with avoiding loading unnecessary plugins when visting specified urls on your website.
 * Version:           1.0.1
 * Author:            DS workshop
 * Author URI:        https://www.demo-cj.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       zj-page-speedup
 * Domain Path:       /languages
 */

if (!class_exists('ZJPS_PageSpeedUp')) {
    define('ZJPS_PLUGIN_SLUG', basename(__DIR__));
    define('ZJPS_PLUGIN_DIR', __DIR__);
    define('ZJPS_PLUGIN_URL', plugins_url(ZJPS_PLUGIN_SLUG));

    /**
     * ZJPS_PageSpeedUp
     */
    class ZJPS_PageSpeedUp
    {
        /**
         * admin instance
         *
         * @var AdminSpeedup
         */
        private $admin;

        static $_instance = null;

        /**
         * get unique instance
         *
         * @return self
         */
        static function instance(): self
        {
            if (!isset($instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        /**
         * get_plugin_basename
         *
         * @return string
         */
        protected function get_plugin_basename(): string
        {
            $_base = plugin_basename(__FILE__);
            return $_base;
        }
        /**
         * return the plugin settings url of the admin options
         *
         * @return string
         */
        protected function get_settings_url(): string
        {
            return admin_url('options-general.php?page=zj-page-speedup');
        }
        /**
         * add settings url link for the plugin
         * 
         * @param  array $links
         * @return array
         */
        protected function add_plugin_links(array $links): array
        {
            // Display settings url if setup is complete otherwise link to get started page
            $_link = sprintf(
                '<a href="%1$s">%2$s</a>',
                esc_attr($this->get_settings_url()),
                esc_html__('Settings')
            );
            array_unshift($links, $_link);
            // Add new links to the beginning
            esc_html('');
            return $links;
        }
        /**
         * return admin object
         *
         * @return ZJPS\AdminSpeedup
         */
        function admin(): \ZJPS\AdminSpeedup
        {
            if (!$this->admin)
                $this->admin = \ZJPS\AdminSpeedup::instance();
            return $this->admin;
        }
        /**
         * loader which the plugin class need to load
         *
         * @param  string $classname
         * @return void
         */
        static function loader(string $classname)
        {
            if ('ZJPS\\AdminSpeedup' == $classname) {
                require_once ZJPS_PLUGIN_DIR . '/admin/admin.php';
                return true;
            }
            return false;
        }
        /**
         * __construct
         *
         * @return void
         */
        function __construct()
        {
            spl_autoload_register(array($this, 'loader'));
            add_action(
                "plugin_action_links_{$this->get_plugin_basename()}",
                function ($links) {
                    return $this->add_plugin_links($links);
                }
            );
            add_action('plugins_loaded', function () {
                $basedir = dirname(plugin_basename(__FILE__));
                load_plugin_textdomain(
                    'zj-page-speedup',
                    false,
                    $basedir . '/languages/'
                );
            });
            add_action('admin_init', function () {
                $this->admin()->admin_init();
            });
            add_action('admin_menu', function () {
                $this->admin()->option_page();
            });
        }
    }
    (function () {
        register_activation_hook(__FILE__, function () {
            if (!file_exists(WPMU_PLUGIN_DIR))
                mkdir(WPMU_PLUGIN_DIR);
            $from = trailingslashit(dirname(__FILE__)) . 'zj-speedup.php';
            $to = trailingslashit(WPMU_PLUGIN_DIR) . 'zj-speedup.php';
            copy($from, $to);
        });

        register_deactivation_hook(__FILE__, function () {
            $to = trailingslashit(WPMU_PLUGIN_DIR) . 'zj-speedup.php';
            unlink($to);
        });

        $start = ZJPS_PageSpeedUp::instance();
    })();
}
