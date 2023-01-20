<?php

require_once dirname( __FILE__ ) . '/setup-freemius-api.php';

/**
 * Get the latest deployed version from Freemius.
 *
 * @package freemius
 */
class Get_Latest_Version_Number extends Setup_Freemius_API {

	/**
	 * Class constructor.
	 *
	 * @return void
	 * @throws Freemius_Exception
	 */
	public function __construct() {

		global $argv;
		$sans_v = (bool) $arg = $argv[1] ?? '';

		$api            = $this->get_api();
		$deployed       = $api->Api( "plugins/{$this->plugin_id}/tags.json" );
		$version_number = trim( $deployed->tags[0]->version ?? '' );

		if ( empty( $version_number ) ) {
			echo '> Error: Couldn\'t find latest tag version.';
			exit( 1 );
		}

		if ( $sans_v === true ) {
			echo $version_number;
		} else {
			echo 'v' . $version_number;
		}
	}

}
new Get_Latest_Version_Number();
