=== Plugin Name ===
Contributors: CJ Chen
Donate link: https://www.demo-cj.net
Tags: speedup, optimize, performance, webpage, load, load-time, zj
Requires at least: 4.4
Tested up to: 5.9.1
Stable tag: 1.0.1
Requires PHP: 7.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Speed up your page loading with avoiding loading unnecessary plugins when visting specified url on your website.

== Description ==

The plugin purpose is to speed up the website page loading time by avoiding unnecessary plugins. 
When you browse some webpages on your website, WordPress will load all the plugins you installed, 
maybe tens of plugins, but not all of them will be used. Some plugins will work on the specified webpages. 
For example, if your website use wooCommerce, but your website home page does not use its function. 
The plugin still be loaded when you visit your website home page and take some time to load. 
Maybe it leads the slow website loading. The plugin of ZJ Page Speed Up will help you improve the 
performance by not loading the plugin wooCommerce when you visit your home page. 
There are many other optimizing plugins and the plugin can work with them to achieve the best performance.

== Installation ==

Installation procedure:

1. Deactivate plugin if you have the previous version installed.
2. Extract "zj-page-speedup.zip" archive content to the "/wp-content/plugins/zj-page-speedup" directory.
3. Activate "ZJ Page Speedup" plugin via 'Plugins' menu in WordPress admin menu. 
4. Go to the "Settings"-"ZJ Page Speedup" menu item and setup the url-to-plugins list to improve the page loading performance.

== Frequently Asked Questions ==
- Which tool can measure the loading time to check the performance improvement?
There are many profiling tools. For me, I use Code Profiler to measure the webpage loading time.

- Can I use the plugin with other speedup plugins?
ZJ Page Speed Up uses the method which avoids loading unnecessary plugins. Of course, you can use it with other plugin like cache, css optimizing...

== Screenshots ==

1. ZJ Page Speed Up setting page, easy to understand and very simple to use

== Changelog ==

= 1.0.1 =
The initial version.
