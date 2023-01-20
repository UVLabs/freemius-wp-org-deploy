<?php

// Downloaded dynamically by github action.
require_once dirname( __FILE__ ) . '/freemius-sdk/freemius/Freemius.php';

/**
 * Setup the Freemius API.
 *
 * The Freemius_Api class file is downloaded automatically when the github action is being run.
 *
 * @package freemius
 */
class Setup_Freemius_API {

	/**
	 * Developer ID from freemius settings.
	 *
	 * @var int
	 */
	protected int $developer_id;

	/**
	 * Plugin ID for the product this repo manages.
	 *
	 * @var int
	 */
	protected int $plugin_id;

	/**
	 * Public Key from freemius settings.
	 *
	 * @var string
	 */
	protected string $public_key;


	/**
	 * Secret Key from freemius settings.
	 *
	 * @var string
	 */
	protected string $secret_key;

	/**
	 * Get instance of Freemius API.
	 *
	 * The API SDK repo is downloaded dynamically by the github action.
	 *
	 * @return Freemius_Api
	 */
	protected function get_api(): Freemius_Api {

		$this->developer_id = getenv( 'FS_USER_ID' );

		$this->plugin_id = getenv( 'FS_PLUGIN_ID' );

		$this->public_key = getenv( 'FS_PUBLIC_KEY' );

		$this->secret_key = getenv( 'FS_SECRET_KEY' );

		// Init SDK
		return new Freemius_Api( 'developer', $this->developer_id, $this->public_key, $this->secret_key );
	}

}
