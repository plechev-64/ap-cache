<?php
/*
 * Create cache with a dynamic key or an array of keys
 * */

$attrKey = json_decode([
	'some-attr-1' => 'attr-value',
	'some-attr-2' => 'attr-value'
]);

$cache = new APCache( $attrKey, 3600, 'sub-dir-name' );

if ( $cache->file_exists() && ! $cache->need_update() ) {
	return $cache->get();
}

$content = '<p>Something content</p>';

$cache->update( $content );

echo $content;

/*
 * Clear sub dir of cache
 * */
add_action('any-action-hook', function(){
	APCache::clear( 'sub-dir-name' );
});