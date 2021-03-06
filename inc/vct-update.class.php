<?php

class VCT_Update {
	protected $updatePath = 'http://updates.wpbakery.com/visual-composer-theme/index.html';

	public function __construct() {
		/** @see \VCT_Update::checkForUpdates */
		add_filter( 'pre_set_site_transient_update_themes',
		            array(
			            $this,
			            'checkForUpdates',
		            )
		);
	}

	public function checkForUpdates( $transient ) {
		// Extra check for 3rd plugins
		if ( isset( $transient->response[ VCT_SLUG ] ) ) {
			return $transient;
		}
		// Get the remote version
		$version = $this->getRemoteVersion();
		// If a newer version is available, add the update
		if ( version_compare( VCT_VERSION, $version, '<' ) ) {
			$theme                           = array();
			$theme['theme']                  = VCT_SLUG;
			$theme['new_version']            = $version;
			$theme['url']                    = 'http://updates.wpbakery.com/visual-composer-theme/changes.html';
			$theme['package']                = 'http://updates.wpbakery.com/visual-composer-theme/visual-composer-theme.zip';
			$transient->response[ VCT_SLUG ] = $theme;

		}

		return $transient;
	}

	protected function getRemoteVersion() {
		$request = wp_remote_get( $this->updatePath );
		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			return $request['body'];
		}

		return false;
	}
}
