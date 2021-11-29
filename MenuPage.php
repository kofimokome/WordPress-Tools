<?php
/**
 * Created by PhpStorm.
 * User: kofi
 * Date: 6/5/19
 * Time: 11:59 AM
 * @version 1.0.0
 * @author kofi mokome
 */


class MenuPage {
	private $page_title;
	private $menu_title;
	private $capability;
	private $menu_slug;
	private $icon_url;
	private $position;
	private $function;
	private $sub_menu_pages;

	/**
	 * @param string $page_title The text to be displayed in the title tags of the page when the menu is selected.
	 * @param string $menu_title The text to be used for the menu.
	 * @param string $capability The capability required for this menu to be displayed to the user.
	 * @param string $menu_slug The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                             include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                             with sanitize_key().
	 * @param callable $function The function to be called to output the content for this page.
	 * @param string $icon_url The URL to the icon to be used for this menu.
	 *                             * Pass a base64-encoded SVG using a data URI, which will be colored to match
	 *                               the color scheme. This should begin with 'data:image/svg+xml;base64,'.
	 *                             * Pass the name of a Dashicons helper class to use a font icon,
	 *                               e.g. 'dashicons-chart-pie'.
	 *                             * Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS.
	 * @param int $position The position in the menu order this item should appear.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $page_title, $menu_title, $capability, $menu_slug, $icon_url = '', $position = null, $function = '' ) {
		$this->page_title = $page_title;
		$this->menu_title = $menu_title;
		$this->capability = $capability;
		$this->menu_slug  = $menu_slug;
		$this->icon_url   = $icon_url;
		$this->position   = $position;
		$this->function   = $function == '' ? array( $this, 'default_function' ) : $function;

		$this->sub_menu_pages = array();
	}

	/**
	 * @since 1.0.0
	 */
	public function run() {
		add_action( 'admin_menu', array( $this, 'create_menu_page' ) );
	}

	/**
	 * @since 1.0.0
	 */
	public function create_menu_page() {
		add_menu_page(
			$this->page_title,
			$this->menu_title,
			$this->capability,
			$this->menu_slug,
			$this->function,
			$this->icon_url,
			$this->position

		);

		foreach ( $this->sub_menu_pages as $sub_menu_page ) {
			$sub_menu_page->run();
		}
	}

	/**
	 * @since 1.0.0
	 */
	public function default_function() {
		echo "";
	}

	/**
	 * @since 1.0.0
	 */
	public function get_menu_slug() {
		return $this->menu_slug;
	}

	/**
	 * @param SubMenuPage $sub_menu_page
	 * @since 1.0.0
	 */
	public function add_sub_menu_page( $sub_menu_page ) {
		array_push( $this->sub_menu_pages, $sub_menu_page );
	}

}
