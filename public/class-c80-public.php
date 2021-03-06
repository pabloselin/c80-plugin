<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    c80
 * @subpackage c80/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    c80
 * @subpackage c80/public
 * @author     Your Name <email@example.com>
 */
class c80_Public {

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
	 * @param      string    $c80       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $c80, $version ) {

		$this->c80 = $c80;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->c80, plugin_dir_url( __FILE__ ) . 'css/c80-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_script( $this->c80, plugin_dir_url( __FILE__ ) . 'js/c80-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Replaces classic content with special custom fields by paragraph
	 *
	 * @since    1.0.0
	 */
	public function c80_content( $content, $limit = NULL, $afterp = true ) {
		
		global $post;
		
		$post_type = get_post_type( $post );
		
		if($post_type == 'c80_cpt' && class_exists('RW_Meta_Box')) {
		
			if(rwmb_meta( 'c80_parrafo', $post->ID )) {
		
				//Reemplazo la variable de contenido
		
				$content = '';
				
		
				$parrafos = rwmb_meta('c80_parrafo', 'multiple=true', $post->ID );
		
				//var_dump($parrafos);
				
				foreach(  $parrafos as $key=>$parrafo ) {
					$extraclasses = '';
					
					$relids = $this->c80_relp( $this->c80_pid( $key, $post->ID ) );
					
					if($relids != 0) {
						$relids = implode($relids, ', ');
						$relids = ' data-relids="'. $relids . '"';
						$extraclasses = 'con-rel';
					} else {
						$relids = '';
					}
					//El ID de cada párrafo es una suma del ID del post más el orden en los campos personalizados
					//Con eso podemos buscar contenidos relacionados en base al ID del párrafo

					$content .= '<a class="c80_p ' . $extraclasses . '" href="#" id="'. $this->c80_name($post->ID, $key) .'" name="'. $this->c80_name($post->ID, $key) .'" data-pid="'. $this->c80_pid($key, $post->ID) . '" data-order="' . $key . '" ' . $relids . '" data-link="' . $this->c80_permalink($post->ID, $key) . '"><p>' . $parrafo . '</p></a>';
					if($afterp == true) {
						$content .= $this->c80_afterp( $post->ID, $key );
					}
				}
		
			}
		
		}
		
		return $content;
	}

		public function c80_rest_content( $id ) {
		
		/**
		 * Devuelve el contenido de C80 para el REST API en un array con IDS
		 */
		
		$content = array();
		
		if(class_exists('RW_Meta_Box')) {
		
			if(rwmb_meta( 'c80_parrafo', $id )) {
		
				//Reemplazo la variable de contenido
		
				$content = '';
				
		
				$parrafos = rwmb_meta('c80_parrafo', 'multiple=true', $id );
		
				//var_dump($parrafos);
				

				foreach(  $parrafos as $key=>$parrafo ) {
					$extraclasses = '';
					
					$relids = $this->c80_relp( $this->c80_pid( $key, $id ) );
					
					if($relids != 0) {
						$relids = implode($relids, ', ');
						$relids = ' data-relids="'. $relids . '"';
						$extraclasses = 'con-rel';
					} else {
						$relids = '';
					}
					//El ID de cada párrafo es una suma del ID del post más el orden en los campos personalizados
					//Con eso podemos buscar contenidos relacionados en base al ID del párrafo

					$content[ $key ] = $parrafo;
					

				}
		
			}
		

		}

		
		if( isset( $content ) ) {

			return $content;	

		}
		
	}

	public function c80_jsonpreparedata( $data, $post, $context ) {
			

			unset( $data->data['author'] );
			unset( $data->data['status'] );
			unset( $data->data['date'] );
			unset( $data->data['date_gmt'] );
			unset( $data->data['guid'] );
			unset( $data->data['modified'] );
			unset( $data->data['modified_gmt'] );
			unset( $data->data['guid'] );
			unset( $data->data['version_history'] );
			unset( $data->data['comment_status'] );
			unset( $data->data['ping_status'] );
			unset( $data->data['curies'] );
			unset( $data->data['wp:attachment'] );
			unset( $data->data['up'] );
			unset( $data->data['_links'] );
			unset( $data->data['ping_status'] );
			unset( $data->data['type'] );
  			
  			//Añado los párrafos de la constitución
  			$parrafos = $this->c80_rest_content( $data->data['id'] );
  			

  			if( !empty($parrafos) ) {

  				foreach($parrafos as $key => $parrafo) {

  					$data->data['contenido'][$key] =  $parrafo;

  				}
  				

  			}

			return $data;
		}

	/**
	 * Devuelve el ID del capítulo vinculado al número
	 */
	public function c80_jsonget( WP_REST_Request $request ) {

		$capno = $request['id'];
		$artno = $request['articulokey'];	

		//Construyo el objeto capítulo
		
		if( isset($capno) ) {

			$chapter_item = $this->c80_getchapter_bymeta($capno);	

			$chapter['title']     = $chapter_item->post_title;
			$chapter['subtitle']  = get_post_meta($chapter_item->ID, 'c80_subtartcap', true);

			$args = array(
				'post_type' 	=> 'c80_cpt',
				'post_parent' 	=> $chapter_item->ID,
				'numberposts' 	=> -1,
				'orderby'		=> 'menu_order',
				'order'			=> 'ASC'
				);

			$articulos = get_posts($args);

			foreach($articulos as $key => $articulo) {

				$contenido = rwmb_meta('c80_parrafo', 'multiple=true', $articulo->ID );

			//Llamo a subartículos si es que aplica

				$subargs = array(
					'post_type' => 'c80_cpt',
					'numberposts' => -1,
					'post_parent' => $articulo->ID,
					'orderby' => 'menu_order',
					'order' => 'ASC'
					);

				$subarticulos = get_posts($subargs);

				if($subarticulos) {

					foreach($subarticulos as $subarticulo) {

						$subcontenido = rwmb_meta('c80_parrafo', 'multiple=true', $subarticulo->ID );
						$subarticulos_filtrado[] = array(
							'title' => $subarticulo->post_title,
							'contenido' => $subcontenido
							);

					}

					$chapter['articulos'][$key] = array(
						'seccion' => $articulo->post_title,
						'articulos' => $subarticulos_filtrado
						);

				} else {
					
					$chapter['articulos'][$key] = array(
						'title' => $articulo->post_title,
						'contenido' => $contenido
						);


				}


			}



			return $chapter;

		} elseif( isset($artno) ) {

			$articulo_item = $this->c80_getarticle_bymeta( $artno );

			$articulo['title'] = $articulo_item->post_title;
			$articulo['contenido'] = rwmb_meta('c80_parrafo', 'multiple=true', $articulo_item->ID );

			return $articulo;

		} else {

			$error = array(
				'code' => 'rest_not_found',
				'message' => 'No se encontró contenido o no existe el capítulo',
				'data' => array(
					'status' => 404
					)
				);

			return $error;
		}

	}

	public function c80_getchapter_bymeta( $chapter ) {

		$args = array(
			'numberposts' => 1,
			'post_type' => 'c80_cpt',
			'meta_key'  => 'c80_capno',
			'meta_value' => $chapter
			);

		$cap_post = get_posts($args);
		
		if( !empty( $cap_post )):
			$cap_post_id = $cap_post[0]->ID;
			return $cap_post[0];

		endif;

	}

	/**
	 * Devuelve artículo por meta value
	 */
	public function c80_getarticle_bymeta( $article ) {

		$args = array(
			'numberposts' => 1,
			'post_type' => 'c80_cpt',
			'meta_key'  => 'c80_artno',
			'meta_value' => $article
			);

		$art_post = get_posts($args);
		
		if( !empty( $art_post )):
			$art_post_id = $art_post[0]->ID;
			return $art_post[0];

		endif;

	}

	/**
	 * Creates custom routes for REST API
	 */
	public function c80_restapi_init() {
		register_rest_route( 'constitucion1980/v1/', 'capitulo/(?P<id>\d+)', array(
			'methods'  => 'GET',
			'callback' => array( $this, 'c80_jsonget' ),
			'args' => array(
						'id' => array(
							'validate_callback' => function($param, $request, $key) {
								return is_numeric( $param );
							}

							)
						)
					)
			);
		register_rest_route('constitucion1980/v1/', '/articulo/(?P<articulokey>\w+)', array(
			'methods' => 'GET',
			'callback' => array( $this, 'c80_jsonget' ),
			'args' => array(
					'articulokey' => array(
						'validate_callback' => function($param, $request, $key) {
							return sanitize_text_field( $param );
						}
					)
				)
			)
		);
	}

	/**
	 * Generates paragraph permalink in article
	 *
	 * @since    1.0.0
	 */	
	public function c80_permalink( $postid, $key ) {
		return get_permalink($postid) . '#parrafo-' . $this->c80_pid($key, $postid);
	}

	/**
	 * Generates paragraph permalink in capitulo
	 *
	 * @since    1.0.0
	 */	
	public function c80_permalink_chapter( $postid, $key ) {
		$parents = get_post_ancestors( $postid );
		if($parents) {
			$parents = array_reverse($parents);
			$tops = $parents[0];
			return get_permalink($tops) . '#parrafo-' . $this->c80_pid($key, $postid);
		}
	}



	/**
	 * Generates paragraph name
	 *
	 * @since    1.0.0
	 */	
	public function c80_name( $postid, $key ) {
		return 'parrafo-' . $this->c80_pid($key, $postid);
	}

	/**
	 * Generates utilites after paragraph 
	 *
	 * @since    1.0.0
	 */
	public function c80_afterp( $postid, $key ) {
		
		$html = '<p class="afterp">';
		
		$html .= '<a href="#comment">Comentar</a> | <a href="#compartir">Compartir</a> | <a href="#citar">Citar</a> | <a href="' . $this->c80_permalink($postid, $key) . '">Permalink</a>';
		
		$html .= '</p>';

		$nothing = '';
		return $nothing;//$html;
	}

	/**
	 * Generates paragraph ID
	 *
	 * @since    1.0.0
	 */
	public function c80_pid( $key, $postid ) {
		return $key . '-' . $postid;
	}

	/**
	 * Devuelve un post ID de un Párrafo ID
	 */
	public function c80_invpid( $parrafoid ) {
		$parrafoid = explode('-', $parrafoid);
		//La segunda clave del array es la del ID del artículo
		return $parrafoid[1];
	}

	/**
	 * Fetches paragraph-related content
	 * Returns an array of related IDs or 0 in empty cases
	 *
	 * @since    1.0.0
	 */
	public function c80_relp( $parid ) {
		$args = array(
			'post_type' => 'any',
			'numberposts' => 100,
			'meta_query' => array(
				array(
					'key' => 'c80_parraforel',
					'value' => $parid
					)
				)
			);
		$prels = get_posts($args);
		if($prels) {
			$prelids = array();
			foreach($prels as $prel) {
				$prelids[] = $prel->ID;
			}
			return $prelids;	
		} else {
			return 0;
		}
	}

	/**
	 * Fetches article-related content
	 * Returns an array of related IDs or 0 in empty cases
	 *
	 * @since    1.0.0
	 */
	public function c80_relart( $artid ) {
		$args = array(
			'post_type' => 'any',
			'numberposts' => 100,
			'meta_query' => array(
				array(
					'key' => 'c80_artrel',
					'value' => $artid
					)
				)
			);
		$artrels = get_posts($args);
		if($artrels) {
			$artids = array();
			foreach($artrels as $artrel) {
				$artids[] = $artrel->ID;
			}
			return $artids;
		} else {
			return 0;
		}
	}
}