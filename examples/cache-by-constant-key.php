<?php
/*
 * Create cache with a constant key
 * */

$cache = new APCache( 'cache-key-example', 3600 );

if ( $cache->file_exists() && ! $cache->need_update() ) {
	return $cache->get();
}

$content = '<p>Something content</p>';

$cache->update( $content );

echo $content;

/*
 * Delete file of cache by key
 * */
add_action('any-action-hook', function(){
	APCache::delete( 'cache-key-example' );
});