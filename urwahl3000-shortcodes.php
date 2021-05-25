<?php

/**
 * Plugin Name: Urwahl3000 Shortcodes
 * Description: Shortcodes aus dem Urwahl3000 Theme
 * Version: 1.0.0
 * Requires PHP: 7.4.0
 * Author: David Fuhr
 * Author URI: https://github.com/davidfuhr/urwahl3000-shortcodes
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0
 */

define('U3KSC_PLUGIN', __FILE__);

function u3ksc_plugin_url($path = '')
{
    $url = plugins_url($path, U3KSC_PLUGIN);

    if (is_ssl() && 'http:' == substr($url, 0, 5)
    ) {
        $url = 'https:' . substr($url, 5);
    }

    return $url;
}

add_action('wp_enqueue_scripts', 'u3ksc_styles');
function u3ksc_styles()
{
    wp_enqueue_style('u3ksc-styles', u3ksc_plugin_url('styles.css'), '', '', 'screen');
}

add_image_size('square', 400, 400, true);
function u3ksc_sitemap($atts)
{
    extract(shortcode_atts(array(
        'personen'        => 'nein',
    ), $atts));

    //get current page ID
    $the_id = get_the_ID();

    $smargs = array(
        'child_of'     => $the_id,
        'title_li'     => '',
        'parent'       => $the_id,
        'sort_order'    => 'ASC',
        'sort_column'    => 'menu_order'
    );
    $smitem = get_pages($smargs);
    $personenclass = ($personen === 'nein') ? '' : 'sitemap-persons';

    $children = '';
    foreach ($smitem as $value) {
        $thumb = get_the_post_thumbnail($value->ID, 'square', $attr = '');
        $children .= "<li>";
        $children .= "<h4>";
        if ($thumb) {
            $children .= "<a href='" . $value->post_name . "'>" . $thumb . "</a>";
        }
        $children .= "<a href='" . $value->post_name . "' >" .  $value->post_title . "</a>";
        $children .= "</h4>";
        if ($value->post_excerpt) {
            $children .= "<p>" .  $value->post_excerpt . "</p>";
        }
        $children .= "</li>";
    }

    return '<nav class="unterseiten"><ul class="sitemap sitemap-thumb ' . $personenclass . '">' . $children . '</ul></nav>';
}
add_shortcode('unterseiten', 'u3ksc_sitemap');
