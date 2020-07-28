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

namespace BadgeFactor2;

use WP_CLI;
use WP_CLI_Command;
use BadgeFactor2\BadgrUser;
use BadgeFactor2\Post_Types\BadgePage;

WP_CLI::add_command( 'bf2', BadgeFactor2_CLI::class );

/**
 * Manage Open Badges in Badge Factor 2.
 */
class BadgeFactor2_CLI extends WP_CLI_Command {

	/**
	 * Undocumented function.
	 *
	 * @param array $args Arguments.
	 * @param array $assoc_args Associative arguments.
	 * @return void
	 */
	public function list_issuers( $args, $assoc_args ) {
		if ( count( $args ) !== 0 ) {
			WP_CLI::error( 'Usage: list_issuers' );
		}

		$issuers = Issuer::all( -1 );
		if ( false === $issuers ) {
			WP_CLI::error( 'Error retrieving issuers' );
		}

		WP_CLI::success( 'Issuers successfully retrieved : ' . json_encode( $issuers, JSON_PRETTY_PRINT ) );
	}

	/**
	 * Undocumented function
	 *
	 * @param array $args Arguments.
	 * @param array $assoc_args Associative arguments.
	 * @return void
	 */
	public function create_badge_pages_from_badges( $args, $assoc_args ) {
		if ( count( $args ) !== 0 ) {
			WP_CLI::error( 'Usage: create_badge_pages_from_badges' );
		}

		$count = BadgePage::create_from_badges();

		if ( false === $count ) {
			WP_CLI::error( 'Migrating badges failed' );
		} else {
			WP_CLI::success( 'Finished migrating badgees: ' . $count . ' badge pages created' );
		}
	}
}
