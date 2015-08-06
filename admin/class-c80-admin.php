<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    c80
 * @subpackage c80/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    c80
 * @subpackage c80/admin
 * @author     Your Name <email@example.com>
 */
class c80_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $c80    The ID of this plugin.
	 */
	private $c80;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $c80       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $c80, $version ) {

		$this->c80 = $c80;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in c80_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The c80_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->c80, plugin_dir_url( __FILE__ ) . 'css/c80-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in c80_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The c80_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->c80, plugin_dir_url( __FILE__ ) . 'js/c80-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function main_admin_menu() {
		add_menu_page( 
			__('Opciones C80', 'c80'), 
			__('Configuración C80', 'c80') ,
			'manage_options',
			plugin_dir_url(__FILE__) . 'partials/c80-admin-display.php',
			'',
			'dashicons-book-alt',
			20
			);
	}

	// Register Custom Post Type
	public function custom_content() {

			$labels = array(
				'name'                => _x( 'Artículos', 'Post Type General Name', 'c80' ),
				'singular_name'       => _x( 'Artículo', 'Post Type Singular Name', 'c80' ),
				'menu_name'           => __( 'Constitución 1980', 'c80' ),
				'name_admin_bar'      => __( 'Constitución 1980', 'c80' ),
				'parent_item_colon'   => __( 'Artículo superior', 'c80' ),
				'all_items'           => __( 'Todos los artículos', 'c80' ),
				'add_new_item'        => __( 'Añadir nuevo artículo', 'c80' ),
				'add_new'             => __( 'Añadir nuevo', 'c80' ),
				'new_item'            => __( 'Nuevo artículo', 'c80' ),
				'edit_item'           => __( 'Editar artículo', 'c80' ),
				'update_item'         => __( 'Actualizar artículo', 'c80' ),
				'view_item'           => __( 'Ver artículo', 'c80' ),
				'search_items'        => __( 'Buscar artículo', 'c80' ),
				'not_found'           => __( 'No encontrado', 'c80' ),
				'not_found_in_trash'  => __( 'No encontrado en Papelera', 'c80' ),
			);
			$rewrite = array(
				'slug'                => 'articulo',
				'with_front'          => true,
				'pages'               => true,
				'feeds'               => true,
			);
			$args = array(
				'label'               => __( 'Artículo', 'c80' ),
				'description'         => __( 'Constitución de 1980', 'c80' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'editor', 'excerpt', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', ),
				'taxonomies'          => array( 'category', 'post_tag' ),
				'hierarchical'        => true,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 5,
				'menu_icon'           => 'dashicons-book-alt',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,		
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'rewrite'             => $rewrite,
				'capability_type'     => 'page',
			);
			register_post_type( 'c80_cpt', $args );

		}
}
