<?php
/*
 * Badge Factor 2
 * Copyright (C) 2019 ctrlweb
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**
 * @package Badge_Factor_2
 */

namespace BadgeFactor2;

class Badge
{
    public static function init_hooks()
    {
        add_action('init', array( Badge::class, 'init' ), 9966);
    }

    public static function init()
    {
        $labels = array(
            'name'               => __('Badges', 'badgefactor2'),
            'singular_name'      => __('Badge', 'badgefactor2'),
            'add_new_item'       => __('Add New Badge', 'badgefactor2'),
            'edit_item'          => __('Edit Badge', 'badgefactor2'),
            'search_items'       => __('Search Badges', 'badgefactor2'),
            'not_found'          => __('No badges found.', 'badgefactor2'),
            'not_found_in_trash' => __('No badges found in Trash.', 'badgefactor2'),
        );
        register_post_type(
            'badge',
            array(
                'labels'       => $labels,
                'public'       => true,
                'show_in_menu' => 'badgefactor2',
            )
        );
    }
}
