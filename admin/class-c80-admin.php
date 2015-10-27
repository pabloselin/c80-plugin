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

		$screen = get_current_screen();
		if( 'page' == $screen->id ) {
			add_thickbox();
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_style( 'media-upload' );
		}

	}

	public function admin_menu() {
		add_options_page(
			apply_filters( $this->c80 . '-settings-page-title', 'Ajustes C80' ),
			apply_filters( $this->c80 . '-settings-menu-title', 'C 80' ),
			'manage_options',
			$this->c80,
			array( $this, 'opciones' )
			);
	}

	public function opciones() {
		// Muestra la página de opciones del plugin
		echo  '<h2>'. esc_html( get_admin_page_title() ) .'</h2>';

		?>
			<form method="post" action="options.php">
				<?php
					settings_fields( 'c80-options' );
					do_settings_sections( $this->c80 );
					submit_button( 'Guardar' );
				?>
			</form>
		<?php
	}

	public function register_settings() {
		// Registra los ajustes del plugin y genera los campos

		// register_setting( $option_group, $option_name, $sanitize_callback );
		register_setting(
			$this->c80 . '-options',
			$this->c80 . '-options',
			array( $this, 'validate_options')
			);

		// add_settings_section( $id, $title, $callback, $menu_slug );

		add_settings_section(
			$this->c80 . '-import-csv',
			apply_filters( $this->c80 . '-import-section-title', 'Importar' ),
			array( $this, 'import_section' ),
			$this->c80
			);

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

		add_settings_field(
			'import-file',
			apply_filters($this->c80 . '-import-file-label', 'Importar archivo (.csv)'),
			array($this, 'import_csv_field'),
			$this->c80,
			$this->c80 . '-import-csv');
			
	}

	public function validate_options( $input ) {
		$valid = array();
		if( isset( $input['import-file'] ) ) {

		}
		return $valid;
	}


	//Muestra la sección
	public function import_section( $params ) {
		echo '<p>'.$params['title'].'</p>';
	}

	public function import_csv_field() {
		$options = get_option( $this->c80 . '-options');
		$option = 0;
		if( ! empty ( $options['import-file']) ) {
			$option = $options['import-file'];
		}
			
			$html = '<input type="text" name="' . $this->c80 . '-options[import-file]" value="' . esc_url($option) . '" placeholder="No hay archivos subidos">';
			$html .= '<input id="upload_csv" type="button" name="upload_csv" class="button" value="Subir archivo CSV">';
			$html .= '<span class="description">Subir un archivo CSV para procesar sus contenidos</span>';
			echo $html;
	}

	public function text_url_field() {
			$options = get_option( $this->c80 . '-options');
			$option = 0;
			if( ! empty ( $options['text-file']) ) {
				$option = $options['text-file'];
			}
			$html =	'<input type="text" id="' . $this->c80 . '-options[import-file]" name="' . $this->c80 . '-options[import-file]" value="' . $options['text-file'] .'"></input>';
	}


	// Register Custom Post Type
	public function custom_content() {

			$labels = array(
				'name'                => _x( 'Artículos Constitución', 'Post Type General Name', 'c80' ),
				'singular_name'       => _x( 'Artículo Constitución', 'Post Type Singular Name', 'c80' ),
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
				'slug'                => 'constitucion',
				'with_front'          => true,
				'pages'               => true,
				'feeds'               => true,
			);
			$args = array(
				'label'               => __( 'Artículo', 'c80' ),
				'description'         => __( 'Constitución de 1980', 'c80' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'excerpt', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', ),
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

	public function custom_taxs() {

	}

	public function create_metaboxes( $meta_boxes ) {
			
			if(array_key_exists('post', $_GET)) {
				$postid = $_GET['post'];	
			}
			
			$prefix = $this->c80;

			//Subtítulo para capítulos y usos eventuales
			$meta_boxes[] = array(
				'id'=> 'subtitulo_artcap',
				'title' => 'Subtítulo',
				'pages' => array('c80_cpt'),
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					array(
						'name' => 'Subtítulo',
						'desc' => 'Subtítulo utilizado también para el nombre de los capítulos',
						'id' => $prefix . '_subtartcap',
						'type' => 'text'
						)
					)
				);


			//párrafos artículo
			$meta_boxes[] = array(
				'id' => 'parrafo_articulo',
				'title' => 'Contenidos Artículo (por párrafo)',
				'pages' => array('c80_cpt'),
				'context'=> 'normal',
				'priority' => 'high',
				'fields' => array(
					array(
						'name' => 'Párrafo',
						'desc' => 'Párrafos del artículo',
						'id' => $prefix . '_parrafo',
						'type' => 'textarea',
						'clone' => true
						)
					)
				);

			
			$args = array(
				'post_type' => 'c80_cpt',
				'numberposts' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
				);
			$prearticulos = get_posts($args);
			foreach($prearticulos as $prearticulo) {
				$argsch = array(
					'post_type' => 'c80_cpt',
					'post_parent' => $prearticulo->ID
					);
				$children = count( get_children( $argsch ) );
				
				if($children == 0) {
					$articulos[$prearticulo->ID] = $prearticulo->post_title;	
				}
			}
			
			$relfields = array();

			$relfields[] = array(
							'name' => 'Artículo a relacionar',
							'desc' => 'Artículo de la Constitución que se relaciona con este contenido.',
							'id' => $prefix . '_artrel',
							'type' => 'select',
							'options' => $articulos,
							'placeholder' => 'Escoge uno o más artículos relacionados...',
							'multiple' => true
							);


			if(isset($postid) && get_post_meta($postid, 'c80_artrel')) {
				$articulos_relacionados = rwmb_meta('c80_artrel', 'multiple=true&type=select', $postid);
				
				foreach($articulos_relacionados as $key=>$articulo_relacionado) {
				$artname = get_the_title($articulo_relacionado);

				$contenidos = rwmb_meta('c80_parrafo','multiple=true', $articulo_relacionado);
				$parrafos = array();
				
					foreach($contenidos[0] as $keyp=>$contenido_parrafo):
						$parcount = $keyp + 1;
						$parrafos[$keyp . '-' . $articulo_relacionado ] = 'Párrafo ' . $parcount  . ': ' . substr($contenido_parrafo,0, 70);
					endforeach;

					$relfields[] = array(
							'name' => 'Párrafos del ' . $artname,
							'desc' => 'Párrafo del artículo a relacionar, se puede seleccionar una vez escogido el artículo y guardado el contenido. Se pueden relacionar varios párrafos con la tecla Ctrl o Cmd, no es necesario tener un párrafo relacionado.',
							'id' => $prefix . '_parraforel',
							'type' => 'select',
							'options' => $parrafos,
							'placeholder' => 'Escoge un párrafo ...',
							'multiple' => true
							);
				
						
				}
			} else {
				$relfields[] = array(
							'name' => 'Párrafos a Relacionar',
							'desc' => 'Aún no se pueden seleccionar párrafos',
							'id' => $prefix . '_parraforel',
							'type' => 'select',
							'options' => array('0' => '0'),
							'placeholder' => 'Selecciona artículo para escoger párrafos',
							'multiple' => true
							);
			}

			//Relaciones
			$meta_boxes[] = array(
				'id' => 'articulo_relacionado',
				'title' => 'Artículo de la Constitución Relacionado',
				'pages' => array('post', 'columnas'),
				'context' => 'normal',
				'priority' => 'high',
				'fields' => $relfields
				);
			return $meta_boxes;
		}
}
