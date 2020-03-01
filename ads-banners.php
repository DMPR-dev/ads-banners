<?php
/*
Plugin Name: Ads Banners
Description: Manage your ads!
Version:     1.0.0
Author:      Dmytro Proskurin
 *
 */
namespace AdsBanners;

require_once plugin_dir_path( __FILE__ ) . 'classes/cpt.php';

require_once plugin_dir_path( __FILE__ ) . 'classes/metabox.php';

require_once plugin_dir_path( __FILE__ ) . 'classes/scripts.php';

require_once plugin_dir_path( __FILE__ ) . 'classes/styles.php';

require_once plugin_dir_path( __FILE__ ) . 'classes/settings.php';

require_once plugin_dir_path( __FILE__ ) . 'classes/wp_post/ad.php';

require_once plugin_dir_path( __FILE__ ) . 'blocks/index.php';

require_once plugin_dir_path( __FILE__ ) . 'functions.php';

class AdsBanners
{
	public function __construct()
	{
		$this->init();
	}
	protected function init()
	{
		CPT::register();

		Metabox::register();

		Ad::register();

		Scripts::register();

		Styles::register();

		Settings::register();
	}
}

new AdsBanners();