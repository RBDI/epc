<?php

// Used updated code from plugin "Restore Automatic Update (ru_RU)"
function rau_welcome_back($options) {
	foreach ( (array) $options->updates as $key => $value ) {
		// WordPress 3.1 and below
		if ( !empty($value->url) )
			$value->url = strpos($value->url, 'wordpress.org') === false ? 'http://lecactus.ru/' : $value->url;
		if ( !empty($value->package) )
			$value->package = preg_replace('/http:\/\/.*\/(wordpress-.*-ru_RU\.zip)+?/', 'http://lecactus.ru/download/$1', $value->package);

		// WordPress 3.2+
		if ( !empty($value->download) )
			$value->download = preg_replace('/http:\/\/.*\/(wordpress-.*-ru_RU\.zip)+?/', 'http://lecactus.ru/download/$1', $value->download);
		if ( !empty($value->packages) && !empty($value->packages->full) )
			$value->packages->full = preg_replace('/http:\/\/.*\/(wordpress-.*-ru_RU\.zip)+?/', 'http://lecactus.ru/download/$1', $value->packages->full);

		$options->updates[$key] = $value;
	}
	return $options;
}
add_filter('option_update_core', 'rau_welcome_back');
add_filter('transient_update_core', 'rau_welcome_back');
add_filter('site_transient_update_core', 'rau_welcome_back');
add_filter('pre_update_site_option__transient_update_core', 'rau_welcome_back');
add_filter('pre_update_site_option__site_transient_update_core', 'rau_welcome_back');

function ru_extend_menu() {  ?>


<style type="text/css">
/* исправление ширины колонки для текста nav-menus.php */
.menu-settings dd { 
float: left;
margin: 0px 0px 0px;
padding-left: 240px;
width: 60%;
}
</style>
<?php
}


function russian_mu_dropdown($output) {
global $locale;
foreach ( $output as $language => $options ) {
$output[$language] = str_replace('Russian', 'Русский', $output[$language]);
}
	return $output;
}
add_filter('mu_dropdown_languages', 'russian_mu_dropdown');
add_action('admin_head', 'ru_extend_menu');
?>