<?php

namespace ZJPS;

/**
 * AdminSpeedup
 */
final class AdminSpeedup
{
    static $_instance = null;
    /**
     * return the unique instance
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
     * get url-to-plugins mapping array from repository
     *
     * @return array
     */
    function url_mappings(): array
    {
        return get_option('zjps_speedup_url_plugins_list', []);
    }
    /**
     * show the plugin setting of the admin option_page
     *
     * @return void
     */
    function option_page()
    {
        define('ZJPS_SPEEDUP_TITLE', esc_html__('ZJ Page Speed Up', 'zj-page-speedup'));

        add_options_page(ZJPS_SPEEDUP_TITLE, ZJPS_SPEEDUP_TITLE, 'manage_options', ZJPS_PLUGIN_SLUG, function () {
            wp_enqueue_style('zjps-speedup', ZJPS_PLUGIN_URL . '/assets/admin.css');
            wp_enqueue_script('zjps-speedup', ZJPS_PLUGIN_URL . '/assets/admin.js');
            wp_add_inline_script('zjps-speedup', sprintf("window._url_mappings = '%s';", json_encode($this->url_mappings())), 'before');
            $website_plugins = get_option('active_plugins'); //wp_get_active_and_valid_plugins();
?>
            <h1><?php esc_html_e('ZJ Page Speed Up', 'zj-page-speedup'); ?></h1>
            <form id="speedup-form" action="<?php echo esc_attr(admin_url('options.php')); ?>" method="post">
                <?php settings_fields('zjps-speedup'); ?>
                <?php do_settings_sections('zjps_speedup_settings_page'); ?>
                <p><?php esc_html_e('You can add the website URL first and specify which plugins will avoid to load when loading the URL in the list.', 'zj-page-speedup');
                    esc_html_e('Once you finish the list of the url-to-plugins, you can try to visit the URLs you optimized.', 'zj-page-speedup'); ?></p>
                <div class="select2">
                    <table class="select2_table">
                        <thead>
                            <tr>
                                <td colspan="2" class="select2_grid">
                                    <div class="add_to_list">
                                        <label for="url_adding"><?php esc_html_e('Url:', 'zj-page-speedup'); ?></label>
                                        <input id="url_adding" name="url_adding" type="text" class="url_input">
                                        <button id="add_to_list"><?php esc_html_e('Add to List', 'zj-page-speedup'); ?></button>
                                    </div>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="select2_grid" style="width: 50%;">
                                    <div class="urls_container">
                                        <ul id="urls" class="urls_list"></ul>
                                    </div>
                                </td>
                                <td class="select2_grid" style="width: 50%;">
                                    <table>
                                        <?php
                                        foreach ($website_plugins as $index => $plugin) { ?>
                                            <tr>
                                                <td>
                                                    <label for="<?php echo esc_attr('plugin_' . $index); ?>">
                                                        <input type="checkbox" class="check_plugins" id="<?php echo esc_attr('plugin_' . $index); ?>" name="plugins[]" value="<?php echo esc_attr($plugin); ?>">
                                                        <?php echo esc_html($plugin); ?>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="select2_grid" colspan="2">
                                    <div class="button_list">
                                        <div class="footer_button">
                                            <button id="btn_remove_url"><?php esc_html_e('Remove Selected Url', 'zj-page-speedup'); ?></button>
                                        </div>
                                        <div class="footer_button">
                                            <button id="btn_uncheck_all"><?php esc_html_e('Uncheck All Plugins', 'zj-page-speedup'); ?></button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php submit_button(); ?>
            </form>
        <?php
        });
    }

    /**
     * init admin before the plugin setting
     *
     * @return void
     */
    function admin_init()
    {
        if (!current_user_can('manage_options')) return;
        register_setting('zjps-speedup', 'zjps_speedup_enable');
        register_setting('zjps-speedup', 'zjps_speedup_url_plugins_list');

        add_filter('pre_update_option_zjps_speedup_enable', function ($value, $old_value, $option) {
            if ('on' !== sanitize_text_field($value)) {
                $value = 'off';
            }
            return $value;
        }, 10, 3);

        add_filter('pre_update_option_zjps_speedup_url_plugins_list', function ($value, $old_value, $option) {
            $optimized_urls = json_decode(html_entity_decode(stripslashes($value)), true);
            if ('array' !== gettype($optimized_urls)) return $old_value;
            foreach ($optimized_urls as $url => $plugins) {
                if (!filter_var($url, FILTER_SANITIZE_URL)) {
                    return $old_value;
                }
                if ('array' !== gettype($plugins)) return $old_value;
                foreach ($plugins as $plugin) {
                    if (!filter_var($plugin, FILTER_DEFAULT)) {
                        return $old_value;
                    }
                }
            }
            return $optimized_urls;
        }, 10, 3);

        add_settings_section(
            'zjps_speedup_settings_section',
            __(''),
            function () {
        ?>
            <div style="margin-top: 30px;">
                <h4><?php echo esc_html(ZJPS_SPEEDUP_TITLE) . esc_html__(' Settings', 'zj-page-speedup'); ?></h4>
            </div>
        <?php
            },
            'zjps_speedup_settings_page'
        );

        add_settings_field(
            'zjps_speedup_enable',
            __('Enable', 'zj-page-speedup'),
            function () {
        ?>
            <input type="checkbox" id="zjps_speedup_enable" name="zjps_speedup_enable" <?php if ('on' == get_option('zjps_speedup_enable', 'off')) echo esc_attr('checked'); ?> />
<?php
            },
            'zjps_speedup_settings_page',
            'zjps_speedup_settings_section'
        );
    }
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
    }
}
