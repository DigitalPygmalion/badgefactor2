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
 */

namespace BadgeFactor2\Models;

use BadgeFactor2\Badgr_Entity;
use BadgeFactor2\BadgrProvider;
use BadgeFactor2\WP_Sortable;

/**
 * Badge Class.
 */
class BadgeClass implements Badgr_Entity {

	use WP_Sortable;

	/**
	 * Badge Badgr Entity ID / Slug.
	 *
	 * @var string
	 */
	public $entity_id;


	/**
	 * Retrieve all badges from Badgr provider.
	 *
	 * @param int   $elements_per_page Elements per page.
	 * @param int   $paged Page number.
	 * @param array $filter Filter to use.
	 *
	 * @return array|boolean Badges array or false in case of error.
	 */
	public static function all( $elements_per_page = null, $paged = null, $filter = array() ) {
		if ( empty( $elements_per_page ) ) {
			$elements_per_page = $_GET['posts_per_page'] ?? 10;
		}
		if ( empty( $paged ) ) {
			$paged = $_GET['paged'] ?? 1;
		}
		$badges = BadgrProvider::get_all_badge_classes(
			array(
				'elements_per_page' => $elements_per_page,
				'paged'             => $paged,
			)
		);
		if ( isset( $filter['issuer'] ) ) {
			foreach ( $badges as $i => $badge ) {
				if ( $badge->issuer !== $filter['issuer'] ) {
					unset( $badges[ $i ] );
				}
			}
		}

		WP_Sortable::sort( $badges );

		return $badges;
	}


	/**
	 * Retrieve badge from Badgr provider.
	 *
	 * @param string $entity_id Badge ID.
	 *
	 * @return WP_Post Virtual WP_Post representation of the entity.
	 */
	public static function get( $entity_id ) {
		return BadgrProvider::get_badge_class_by_badge_class_slug( $entity_id );
	}


	/**
	 * Create Badge through Badgr provider.
	 *
	 * @param array $values Associated array of values of badge to create.
	 * @param array $files Files.
	 *
	 * @return string|boolean Id of created badge, or false on error.
	 */
	public static function create( $values, $files = null ) {
		if ( self::validate( $values, $files ) ) {
			return BadgrProvider::add_badge_class( $values['name'], $values['issuer_slug'], $values['description'], $files['image']['tmp_name'] );
		}
		return false;
	}


	/**
	 * Update badge through Badgr provider.
	 *
	 * @param string $entity_id Badge ID.
	 * @param array  $values Associative array of values to change.
	 *
	 * @return boolean Whether or not update has succeeded.
	 */
	public static function update( $entity_id, $values ) {

		$badge = BadgeClass::get( $entity_id );

		if ( $badge && self::validate( $values ) ) {
			if ( ! isset( $values['image'] ) ) {
				$values['image'] = null;
			}

			return BadgrProvider::update_badge_class( $entity_id, $values['name'], $values['description'], $values['image'] );
		}
		return false;

	}


	/**
	 * Delete an Badge through Badgr provider.
	 *
	 * @param string $entity_id Slug / Entity ID.
	 *
	 * @return boolean Whether or not deletion has succeeded.
	 */
	public static function delete( $entity_id ) {
		return BadgrProvider::delete_badge_class( $entity_id );
	}


	/**
	 * Undocumented function.
	 *
	 * @return array
	 */
	public static function get_columns() {
		return array(
			'name'      => __( 'Name', 'badgefactor2' ),
			'issuer'    => __( 'Issuer', 'badgefactor2' ),
			'image'     => __( 'Image', 'badgefactor2' ),
			'createdAt' => __( 'Created on', 'badgefactor2' ),
		);
	}


	/**
	 * Undocumented function.
	 *
	 * @return array
	 */
	public static function get_sortable_columns() {
		return array(
			'name'      => array( 'name', true ),
			'issuer'    => array( 'issuer', false ),
			'createdAt' => array( 'createdAt', false ),
		);
	}


	/**
	 * Undocumented function.
	 *
	 * @param array $values Values.
	 * @param array $files Files.
	 *
	 * @return bool
	 */
	public static function validate( $values, $files = null ) {
		// Not empty.
		if ( ! isset( $values['name'] ) || ! isset( $values['issuer_slug'] ) || ! isset( $values['description'] ) ) {
			return false;
		}
		// TODO File ok.
		if ( false ) {

		}
		// Right type.
		if ( ! is_string( $values['name'] ) || ! is_string( $values['issuer_slug'] ) || ! is_string( $values['description'] ) ) {
			return false;
		}
		// Not too big.
		if ( strlen( $values['name'] ) > 255 || strlen( $values['issuer_slug'] ) > 255 ) {
			return false;
		}
		// Not too small.
		if ( strlen( $values['name'] ) < 1 || strlen( $values['issuer_slug'] ) < 16 ) {
			return false;
		}

		return true;
	}
}
