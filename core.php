<?php
/**
 * Plugin Name: NetTantra Caldera Forms - Mautic Integration
 * Plugin URI: https://www.nettantra.com/wordpress/?utm_src=nettantra-caldera-forms-mautic-integration
 * Description: Send Caldera Forms submission data to Mautic using Mautic's REST API.
 * Version: 1.0.1
 * Author:     NetTantra
 * Author URI: https://www.nettantra.com/wordpress/?utm_src=nettantra-caldera-forms-mautic-integration
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: nettantra-caldera-forms-mautic-integration
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	die( 'We\'re sorry, but you can not directly access this file.' );
 }

include __DIR__ . '/vendor/autoload.php';
use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;

add_filter('caldera_forms_get_form_processors', 'ntt_cf_mautic_integration_register_processor');
/**
 * Add processor
 *
 * @uses "caldera_forms_get_form_processors" filter
 *
 * @since 1.0.0
 *
 * @return array Processors
 */
function ntt_cf_mautic_integration_register_processor($pr){
	$pr['ntt_cf_mautic_integration'] = array(
		"name"              =>  __('Mautic Integration'),
		"description"       =>  __("Send Caldera Forms submission data to Mautic using Mautic's REST API."),
		"author"            =>  'NetTantra',
		"author_url"        =>  'https://www.nettantra.com',
		"post_processor"    =>  'ntt_cf_mautic_integration_post_process',
		"template"          =>  plugin_dir_path(__FILE__) . "config.php",
	);

	return $pr;
}

/**
 * Callback function for the post processor
 *
 * @since 1.0.0
 *
 * @param array $config Processor settings. Key 'action' has action name.
 * @param array $form Form submission data.
 *
 * @return void|mixed
 */
function ntt_cf_mautic_integration_post_process( $config, $form){
  if( !isset( $config['cfmi_mautic_base_url'] ) || empty($config['cfmi_mautic_base_url']) ){
    return;
  }
  if( !isset( $config['cfmi_mautic_username'] ) || empty($config['cfmi_mautic_username']) ){
    return;
  }
  if( !isset( $config['cfmi_mautic_password'] ) || empty($config['cfmi_mautic_password']) ){
    return;
  }
  $mautic_field_aliases = $config['cfmi_mautic_field_mappings_alias'];
  $mautic_field_values_base = $config['cfmi_mautic_field_mappings_value'];
  $mautic_field_data = [];
  foreach($mautic_field_values_base as $k => $mautic_field_value) {
    $mautic_field_data[$mautic_field_aliases[$k]] = trim( Caldera_Forms::do_magic_tags($mautic_field_value) );
  }
  $mautic_settings = array(
    'userName'   => $config['cfmi_mautic_username'],
    'password'   => $config['cfmi_mautic_password'],
  );

  $initAuth = new ApiAuth();
  $auth = $initAuth->newAuth($mautic_settings, 'BasicAuth');
  $api = new MauticApi();
  $contactApi = $api->newApi("contacts", $auth, $config['cfmi_mautic_base_url']);

  $mautic_field_data['ipAddress'] = trim( Caldera_Forms::do_magic_tags('{ip}'));
  $http_query_mautic_field_data = http_build_query($mautic_field_data);
  $mautic_field_data_fixed = [];
  parse_str($http_query_mautic_field_data, $mautic_field_data_fixed);
  $response = $contactApi->create($mautic_field_data_fixed);
  $contact = $response[$contactApi->itemName()];
}

function ntt_cf_mautic_integration_activate( $network_wide ) {
  if(!function_exists('caldera_forms_load')) {
    die('The "Caldera Forms" Plugin must be activated before activating the "Caldera Forms - Mautic Integration" Plugin.');
  }
}

register_activation_hook(__FILE__, 'ntt_cf_mautic_integration_activate');
