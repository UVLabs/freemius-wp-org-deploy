<?php

require_once dirname( __FILE__ ) . './setup-freemius-api.php';

/**
 * Download free plugin from Freemius API.
 *
 * @package freemius
 */
class Download_Free_Plugin extends Setup_Freemius_API {

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->download_from_freemius();
		$this->extract_plugin_archive();
	}

	/**
	 * Remove current (.) and parent (..) directories.
	 *
	 * @param string $dir
	 * @return array
	 */
	private function clean_scandir( string $dir ): array {
		return array_values( array_diff( scandir( $dir ), array( '..', '.' ) ) );
	}

	/**
	 * Download and save the plugin zip from Freemius.
	 *
	 * @return void
	 */
	private function download_from_freemius(): void {

		$api = $this->get_api();

		$deployed = $api->Api( "plugins/{$this->plugin_id}/tags.json" );

		$deployed = $deployed->tags[0];

		$zip = $api->GetSignedUrl( "plugins/{$this->plugin_id}/tags/{$deployed->id}.zip" ) . '&is_premium=false';

		$bytes = file_put_contents( 'plugin-free.zip', file_get_contents( $zip ) );

		if ( empty( $bytes ) ) {
			echo '> Error: Failed to save zip file';
			exit( 1 );
		}

		echo "> Successfully downloaded free version zip.\n";
	}

	/**
	 * Extact plugin zip so we can upload to SVN.
	 *
	 * @return void
	 */
	private function extract_plugin_archive(): void {

		$zip          = new ZipArchive();
		$extract_path = 'plugin-free';

		// Sanitity check to see if the previously downloaded zip exists.
		if ( $zip->open( 'plugin-free.zip' ) !== true ) {
			echo "> Error: Unable to open the zip file. Extraction cannot continue.\n";
			exit( 1 );
		}

		 // Extract the previously downloaded zip. It will contain the plugin folder.
		$extracted = $zip->extractTo( $extract_path );
		$zip->close();

		if ( $extracted ) {
			echo "> Successfully extracted free version zip.\n";
		}

		// Get the plugin folder name.
		$toplevel_folder = $this->clean_scandir( 'plugin-free' )[0] ?? '';

		if ( empty( $toplevel_folder ) ) {
			echo "> Error: Plugin folder not found.\n";
			exit( 1 );
		}

		// Move the plugin files into the top level directory 'plugin-free'
		$move_files = shell_exec( "rsync --remove-source-files -acP plugin-free/{$toplevel_folder}/ plugin-free" );

		if ( ! empty( $move_files ) ) {
			echo "> Successfully moved extracted files to top level directory.\n";
		}

		// Delete old top level folder
		$delete_old = shell_exec( "rm -rf plugin-free/{$toplevel_folder}" );

		if ( ! empty( $delete_old ) ) {
			echo "> Successfully normalized plugin directory.\n";
		}

		$files_directories = $this->clean_scandir( 'plugin-free' );
		$verbose           = array();
		$current_dir       = getcwd();

		if ( ! is_array( $files_directories ) ) {
			echo "> Error: There was an issue scanning the normalized plugin directory. It might not exist.\n";
			exit( 1 );
		}

		foreach ( $files_directories as $file_or_directory ) {
			$path = $current_dir . '/plugin-free/' . $file_or_directory;
			if ( is_dir( $path ) ) {
				$verbose[] = $file_or_directory . ' (folder)';
			} else {
				$verbose[] = $file_or_directory . ' (file)';
			}
		}

		echo "> Files and directories to be sent to SVN:\n";

		print_r( $verbose );

		// Uncomment below to see the files and folders that would be sent to SVN without actually sending them.
		// exit(1)
	}
}
new Download_Free_Plugin();
