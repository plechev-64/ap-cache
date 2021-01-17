<?php
/*
 * Create cache with a dynamic parameters
 * In this case to create a dir for cache files for the all cases of parameters
 * */

//The dynamic parameters can be changed
$dynamicArgs = [
	'some-attr-1' => 'attr-value',
	'some-attr-2' => 'attr-value'
];

$cache = new APCache( 'key-cache-kit', 3600, $dynamicArgs );

if ( $cache->file_exists() && ! $cache->need_update() ) {
	return $cache->get();
}

$content = '<p>Something content</p>';

$cache->update( $content );

echo $content;

/*
 * Delete files of cache by key
 * */
add_action('any-action-hook', function(){
	APCache::delete( 'key-cache-kit' );
});