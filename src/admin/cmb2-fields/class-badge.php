<?php
/**
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
 *
 * @package Badge_Factor_2
 *
 * @phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralDomain
 * @phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
 */

namespace BadgeFactor2\Admin\CMB2_Fields;

use BadgeFactor2\Models\BadgeClass;

/**
 * CMB2 Badge Field.
 */
class Badge {

	/**
	 * Init Hooks.
	 *
	 * @return void
	 */
	public static function init_hooks() {
		add_filter( 'cmb2_render_badge', array( self::class, 'render_badge' ), 10, 5 );
	}


	/**
	 * Render Badge.
	 *
	 * @param CMB2_Field $field Field.
	 * @param string     $field_escaped_value Field escaped value.
	 * @param string     $field_object_id Field object id.
	 * @param string     $field_object_type Field object type.
	 * @param CMB2_Types $field_type_object Field Type Object.
	 * @return void
	 */
	public static function render_badge( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {
		$badge_id = $field_escaped_value;

		$badge = BadgeClass::get( $badge_id );

		if ( $badge ) {
			echo sprintf( '<div style="margin-top: 6px"><a href="/wp-admin/admin.php?page=badges&action=edit&entity_id=%s">%s</a></div>', $badge->entityId, $badge->name );
			echo sprintf( '<input type="hidden" name="badge" value="%s">', $badge->entityId );
		} else {
			echo __( 'Badgr connection inactive!', BF2_DATA['TextDomain'] );
		}
	}
}
