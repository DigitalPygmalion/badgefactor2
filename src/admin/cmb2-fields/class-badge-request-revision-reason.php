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
 */

namespace BadgeFactor2\Admin\CMB2_Fields;

/**
 * CMB2 Badge Request Content Field.
 */
class Badge_Request_Revision_Reason {

	/**
	 * Init Hooks.
	 *
	 * @return void
	 */
	public static function init_hooks() {
		add_filter( 'cmb2_render_badge_request_revision_reason', array( self::class, 'render_badge_request_revision_reason' ), 10, 5 );
	}


	/**
	 * Render Badge Request Revision Reason.
	 *
	 * @param CMB2_Field $field Field.
	 * @param string     $field_escaped_value Field escaped value.
	 * @param string     $field_object_id Field object id.
	 * @param string     $field_object_type Field object type.
	 * @param CMB2_Types $field_type_object Field Type Object.
	 * @return void
	 */
	public static function render_badge_request_revision_reason( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {
		$badge_request_revision_reason = $field_escaped_value;
		$status                        = get_post_meta( $field_object_id, 'status', true );

		if ( $badge_request_revision_reason && 'requested' !== $status ) {
			echo sprintf( '<div style="margin-top: 6px">%s</div>', $field_escaped_value );
		} else {
			echo $field_type_object->textarea();
			echo sprintf( '<button data-confirm="%s" class="button button-primary" id="revise-badge">%s</button>', __( 'Request a revision of this badge request?', BF2_DATA['TextDomain'] ), __( 'Submit', BF2_DATA['TextDomain'] ) );
		}
	}
}


