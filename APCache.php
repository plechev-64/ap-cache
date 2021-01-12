<?php

define('AP_CACHE', true);

class APCache {

	public $key_hash = '';
	public $sourse_dir;
	public $custom_dir;
	public $time_cache;
	public $filepath;
	public $cachepath;

	function __construct( $key, $timecache = 86400, $dirName = '' ) {

		$this->key_hash = md5( $key );

		if ( $timecache ) {
			$this->time_cache = $timecache;
		}

		$upload_dir       = wp_upload_dir();
		$this->sourse_dir = $upload_dir['path'] . '/ap-caсhe/';
		$this->custom_dir = $dirName;
		$this->cachepath  = $this->sourse_dir;

		if ( $this->custom_dir ) {
			$this->cachepath .= trailingslashit( $this->custom_dir );
		}

		$this->filepath = $this->cachepath . $this->key_hash . '.txt';

	}

	function file_exists() {
		if(!AP_CACHE)
			return false;
		return file_exists( $this->filepath ) ? true : false;
	}

	function need_update() {

		if(!$this->time_cache)
			return false;

		return ( filemtime( $this->filepath ) + $this->time_cache < time() ) ? true : false;
	}

	function get() {

		if(!AP_CACHE)
			return false;

		if ( ! $this->file_exists() ) {
			return false;
		}

		return file_get_contents( $this->filepath );
	}

	function update( $content ) {

		if(!AP_CACHE)
			return false;

		if ( ! file_exists( $this->cachepath ) ) {

			if ( $this->custom_dir && ! file_exists( $this->sourse_dir ) ) {
				mkdir( $this->sourse_dir );
				chmod( $this->sourse_dir, 0755 );
			}

			mkdir( $this->cachepath );
			chmod( $this->cachepath, 0755 );
		}

		$f = fopen( $this->filepath, 'w+' );
		fwrite( $f, $content );
		fclose( $f );

		return $content;
	}

	static function get_dir( $dirName = '' ) {

		$cachepath = wp_upload_dir()['path'] . '/ap-caсhe/';

		if ( $dirName ) {
			$cachepath .= trailingslashit( $dirName );
		}

		return $cachepath;
	}

	static function clear( $dirName = '' ) {

		if(!AP_CACHE)
			return false;

		self::remove_dir( self::get_dir( $dirName ) );

	}

	static function delete_file( $key, $dirName = '' ) {

		if(!AP_CACHE)
			return false;

		$filename = self::get_dir( $dirName ) . md5( $key ) . '.txt';

		if ( file_exists( $filename ) ) {
			unlink( $filename );
		}

	}

	static function remove_dir( $dir ) {
		$dir = untrailingslashit( $dir );
		if ( ! is_dir( $dir ) ) {
			return false;
		}
		if ( $objs = glob( $dir . "/*" ) ) {
			foreach ( $objs as $obj ) {
				is_dir( $obj ) ? self::remove_dir( $obj ) : unlink( $obj );
			}
		}
		rmdir( $dir );
	}

}

