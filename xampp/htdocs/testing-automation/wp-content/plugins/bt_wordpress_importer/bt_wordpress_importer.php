<?php
/*
Plugin Name: BoldThemes WordPress Importer
Description: Import posts, pages, comments, custom fields, categories, tags and more from a WordPress export file.
Author: BoldThemes
Author URI: http://codecanyon.net/user/boldthemes
Version: 2.7.4
*/

// https://github.com/humanmade/WordPress-Importer

require_once( 'class-logger.php' );

function bt_dummy_class() {
	if ( ! class_exists( 'BoldThemesFramework' ) ) {
		class BoldThemesFramework {
			static $pfx = BoldThemesPFX;
		}
	}
}
add_action( 'wp_loaded', 'bt_dummy_class' );

add_action( 'wp_ajax_bt_import_ajax', 'bt_import_ajax_callback' );

function bt_import_enqueue() {
	wp_enqueue_script( 'bt_import', plugins_url( 'script.js', __FILE__ ), array( 'jquery' ), '', true  );
	wp_enqueue_style( 'bt_import', plugins_url( 'style.css', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'bt_import_enqueue' );

function bt_import_menu() {
	add_submenu_page( 'tools.php', __( 'BT Import', 'bt_wordpress_importer' ), __( 'BT Import', 'bt_wordpress_importer' ), 'manage_options', 'bt_import', 'bt_import' );
}
add_action( 'admin_menu', 'bt_import_menu' );

function bt_import_js_init() {
	echo '<script>window.bt_import_ajax_url=\'' . admin_url( 'admin-ajax.php' ) . '\';window.bt_import_ajax_nonce=\'' . wp_create_nonce( 'bt-import-ajax-nonce' ) . '\';</script>';
}
add_action( 'admin_footer', 'bt_import_js_init' );

function bt_import() {
	?>
		<div class="wrap">
			<h2><?php _e( 'BoldThemes Import', 'bt_wordpress_importer' ); ?></h2>
			<?php
				
				$files = scandir( get_template_directory() . '/demo_data/' );
				$files = array_diff( $files, array( '.', '..' ) );
				$files = array_values( $files );
				
			?>
			
			<table class="form-table bt_wordpress_importer">
				<tbody>
				<tr>
					<th scope="row" class="bt_import_select_xml"><?php _e( 'Select XML file:', 'bt_wordpress_importer' ); ?></th>
				</tr>
				<tr>
					<td class="bt_import_xml_container"><?php
						$n = 0;
						foreach ( $files as $f ) {
							$n++;
							echo '<div class="bt_import_xml" data-file="' . $f . '">' . $n . '. ' . $f . '</div>';
						}
					?></td>
				</tr>
				<tr>
					<td>
						<div class="bt_import_progress"><?php _e( 'Importing...', 'bt_wordpress_importer' ); ?> <span>0%</span></div>
						<div class="bt_import_report"></div>
					</td>
				</tr>					
				</tbody>
			</table>
			
		</div>
	<?php
}

function bt_import_ajax_callback() {
	check_ajax_referer( 'bt-import-ajax-nonce' );
	if ( current_user_can( 'edit_posts' ) ) {
		$file = sanitize_file_name( $_POST['file'] );
		
		$step = intval( $_POST['step'] );

		$reader_index = intval( $_POST['reader_index'] );

		$force_download = intval( $_POST['force_download'] );

		if ( $force_download == 1 ) {
			$force_download = true;
		} else {
			$force_download = false;
		}
		
		$file = get_template_directory() . '/demo_data/' . $file;
		
		$import = new BT_WP_Import( $step, $reader_index, array(
			'update_attachment_guids' => true,
			'fetch_attachments'       => true,
			'aggressive_url_search'   => true,
			'force_download'   => $force_download,
		) );
		
		$import->import( $file );
	}
	die();
}

/***********************************************************/

/** Display verbose errors */
define( 'BT_IMPORT_DEBUG', true );

// Load Importer API
require_once ABSPATH . 'wp-admin/includes/import.php';

if ( ! class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) )
		require $class_wp_importer;
}

// include WXR file parsers
//require dirname( __FILE__ ) . '/parsers.php';

/**
 * WordPress Importer class for managing the import process of a WXR file
 *
 * @package WordPress
 * @subpackage Importer
 */
if ( class_exists( 'WP_Importer' ) ) {
class BT_WP_Import extends WP_Importer {
	/**
	 * Maximum supported WXR version
	 */
	const MAX_WXR_VERSION = 1.2;

	/**
	 * Regular expression for checking if a post references an attachment
	 *
	 * Note: This is a quick, weak check just to exclude text-only posts. More
	 * vigorous checking is done later to verify.
	 */
	const REGEX_HAS_ATTACHMENT_REFS = '!
		(
			# Match anything with an image or attachment class
			class=[\'"].*?\b(wp-image-\d+|attachment-[\w\-]+)\b
		|
			# Match anything that looks like an upload URL
			src=[\'"][^\'"]*(
				[0-9]{4}/[0-9]{2}/[^\'"]+\.(jpg|jpeg|png|gif)
			|
				content/uploads[^\'"]+
			)[\'"]
		)!ix';

	/**
	 * Version of WXR we're importing.
	 *
	 * Defaults to 1.0 for compatibility. Typically overridden by a
	 * `<wp:wxr_version>` tag at the start of the file.
	 *
	 * @var string
	 */
	protected $version = '1.0';

	// information to import from WXR file
	protected $categories = array();
	protected $tags = array();
	protected $base_url = '';

	// TODO: REMOVE THESE
	protected $processed_terms = array();
	protected $processed_posts = array();
	protected $processed_menu_items = array();
	protected $menu_item_orphans = array();
	protected $missing_menu_items = array();

	// NEW STYLE
	protected $mapping = array();
	protected $requires_remapping = array();
	protected $exists = array();
	protected $user_slug_override = array();

	protected $url_remap = array();
	protected $featured_images = array();

	var $step = 0;
	
	var $last_id = 0;

	var $total = 1;
	
	var $end = false;

	var $reader_index = -1;

	var $next_reader_index = -1;

	function bt_status( $post_id ) {
		if ( $this->last_id == $post_id ) {
			$this->end = true;
			echo 'bt_import_end';
		}
	}

	/**
	 * Logger instance.
	 *
	 * @var WP_Importer_Logger
	 */
	protected $logger;

	/**
	 * Constructor
	 *
	 * @param array $options {
	 *     @var bool $prefill_existing_posts Should we prefill `post_exists` calls? (True prefills and uses more memory, false checks once per imported post and takes longer. Default is true.)
	 *     @var bool $prefill_existing_comments Should we prefill `comment_exists` calls? (True prefills and uses more memory, false checks once per imported comment and takes longer. Default is true.)
	 *     @var bool $prefill_existing_terms Should we prefill `term_exists` calls? (True prefills and uses more memory, false checks once per imported term and takes longer. Default is true.)
	 *     @var bool $update_attachment_guids Should attachment GUIDs be updated to the new URL? (True updates the GUID, which keeps compatibility with v1, false doesn't update, and allows deduplication and reimporting. Default is false.)
	 *     @var bool $fetch_attachments Fetch attachments from the remote server. (True fetches and creates attachment posts, false skips attachments. Default is false.)
	 *     @var bool $aggressive_url_search Should we search/replace for URLs aggressively? (True searches all posts' content for old URLs and replaces, false checks for `<img class="wp-image-*">` only. Default is false.)
	 *     @var int $default_author User ID to use if author is missing or invalid. (Default is null, which leaves posts unassigned.)
	 * }
	 */
	public function __construct( $step, $next_reader_index, $options = array() ) {

		$this->step = $step;

		$this->next_reader_index = $next_reader_index + 1;

		// Initialize some important variables
		$empty_types = array(
			'post'    => array(),
			'comment' => array(),
			'term'    => array(),
			'user'    => array(),
		);

		$this->mapping = $empty_types;
		$this->mapping['user_slug'] = array();
		$this->mapping['term_id'] = array();
		$this->requires_remapping = $empty_types;
		$this->exists = $empty_types;

		$this->options = wp_parse_args( $options, array(
			'prefill_existing_posts'    => true,
			'prefill_existing_comments' => true,
			'prefill_existing_terms'    => true,
			'update_attachment_guids'   => false,
			'fetch_attachments'         => false,
			'aggressive_url_search'     => false,
			'default_author'            => null,
			'force_download'			=> false,
		) );

		$this->internal_file = false;

		$this->logger = new WP_Importer_Logger();

	}

	public function set_logger( $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Get a stream reader for the file.
	 *
	 * @param string $file Path to the XML file.
	 * @return XMLReader|WP_Error Reader instance on success, error otherwise.
	 */
	protected function get_reader( $file ) {
		// Avoid loading external entities for security
		$old_value = null;
		if ( function_exists( 'libxml_disable_entity_loader' ) ) {
			// $old_value = libxml_disable_entity_loader( true );
		}

		$reader = new XMLReader();
		$status = $reader->open( $file );

		if ( ! is_null( $old_value ) ) {
			// libxml_disable_entity_loader( $old_value );
		}

		if ( ! $status ) {
			return new WP_Error( 'wxr_importer.cannot_parse', __( 'Could not open the file for parsing', 'bt_wordpress_importer' ) );
		}

		return $reader;
	}

	/**
	 * The main controller for the actual import stage.
	 *
	 * @param string $file Path to the WXR file for importing
	 */
	public function get_preliminary_information( $file ) {
		// Let's run the actual importer now, woot
		$reader = $this->get_reader( $file );
		if ( is_wp_error( $reader ) ) {
			return $reader;
		}

		// Set the version to compatibility mode first
		$this->version = '1.0';

		// Start parsing!
		$data = new WXR_Import_Info();
		while ( $reader->read() ) {
			// Only deal with element opens
			if ( $reader->nodeType !== XMLReader::ELEMENT ) {
				continue;
			}

			switch ( $reader->name ) {
				case 'wp:wxr_version':
					// Upgrade to the correct version
					$this->version = $reader->readString();

					if ( version_compare( $this->version, self::MAX_WXR_VERSION, '>' ) ) {
						$this->logger->warning( sprintf(
							__( 'This WXR file (version %s) is newer than the importer (version %s) and may not be supported. Please consider updating.', 'bt_wordpress_importer' ),
							$this->version,
							self::MAX_WXR_VERSION
						) );
					}

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'generator':
					$data->generator = $reader->readString();
					$reader->next();
					break;

				case 'title':
					$data->title = $reader->readString();
					$reader->next();
					break;

				case 'wp:base_site_url':
					$data->siteurl = $reader->readString();
					$reader->next();
					break;

				case 'wp:base_blog_url':
					$data->home = $reader->readString();
					$reader->next();
					break;

				case 'wp:author':
					$node = $reader->expand();

					$parsed = $this->parse_author_node( $node );
					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					$data->users[] = $parsed;

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'item':
					$node = $reader->expand();
					$parsed = $this->parse_post_node( $node );
					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					if ( $parsed['data']['post_type'] === 'attachment' ) {
						$data->media_count++;
					} else {
						$data->post_count++;
					}
					$data->comment_count += count( $parsed['comments'] );

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'wp:category':
				case 'wp:tag':
				case 'wp:term':
					$data->term_count++;

					// Handled everything in this node, move on to the next
					$reader->next();
					break;
			}
		}

		$data->version = $this->version;

		return $data;
	}

	/**
	 * The main controller for the actual import stage.
	 *
	 * @param string $file Path to the WXR file for importing
	 */
	public function parse_authors( $file ) {
		// Let's run the actual importer now, woot
		$reader = $this->get_reader( $file );
		if ( is_wp_error( $reader ) ) {
			return $reader;
		}

		// Set the version to compatibility mode first
		$this->version = '1.0';

		// Start parsing!
		$authors = array();
		while ( $reader->read() ) {
			// Only deal with element opens
			if ( $reader->nodeType !== XMLReader::ELEMENT ) {
				continue;
			}

			switch ( $reader->name ) {
				case 'wp:wxr_version':
					// Upgrade to the correct version
					$this->version = $reader->readString();

					if ( version_compare( $this->version, self::MAX_WXR_VERSION, '>' ) ) {
						$this->logger->warning( sprintf(
							__( 'This WXR file (version %s) is newer than the importer (version %s) and may not be supported. Please consider updating.', 'bt_wordpress_importer' ),
							$this->version,
							self::MAX_WXR_VERSION
						) );
					}

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'wp:author':
					$node = $reader->expand();

					$parsed = $this->parse_author_node( $node );
					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					$authors[] = $parsed;

					// Handled everything in this node, move on to the next
					$reader->next();
					break;
			}
		}

		return $authors;
	}

	/**
	 * The main controller for the actual import stage.
	 *
	 * @param string $file Path to the WXR file for importing
	 */
	public function import( $file ) {
		add_filter( 'import_post_meta_key', array( $this, 'is_valid_meta_key' ) );
		add_filter( 'http_request_timeout', array( &$this, 'bump_request_timeout' ) );

		$result = $this->import_start( $file );
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Let's run the actual importer now, woot
		$reader = $this->get_reader( $file );
		if ( is_wp_error( $reader ) ) {
			return $reader;
		}

		// BT [

		while( $reader->read() ) {
			
			if ( $reader->name == 'item' ) {
				if ( $reader->nodeType !== XMLReader::ELEMENT ) {
					continue;
				}
				$node = $reader->expand();

				$parsed = $this->parse_post_node( $node );
				
				if ( isset( $parsed['data']['post_id'] ) ) {
					$this->last_id = $parsed['data']['post_id'];
				}

				if ( isset( $parsed['data']['post_type'] ) && $parsed['data']['post_type'] == 'attachment' ) {
					$this->total++;
				}
			}
		}

		$this->base_url = '';

		// BT ]

		// Let's run the actual importer now, woot
		$reader = $this->get_reader( $file );
		if ( is_wp_error( $reader ) ) {
			return $reader;
		}

		for ( $i = 0; $i < $this->next_reader_index; $i++ ) {
			$reader->read();
			$this->reader_index++;
			// Only deal with element opens
			if ( $reader->nodeType !== XMLReader::ELEMENT ) {
				continue;
			}
			if ( $reader->name == 'wp:base_site_url' ) {
				$this->base_url = $reader->readString();
			}
			if ( $reader->name == 'wp:wxr_version' || $reader->name == 'wp:base_site_url' || $reader->name == 'item' || $reader->name == 'wp:author' || $reader->name == 'wp:category' || $reader->name == 'wp:tag' || $reader->name == 'wp:term' ) {
				$reader->next();
			}
		}

		// Set the version to compatibility mode first
		$this->version = '1.0';

		// Reset other variables

		// Start parsing!
		while ( $reader->read() ) {

			$this->reader_index++;

			// Only deal with element opens
			if ( $reader->nodeType !== XMLReader::ELEMENT ) {
				continue;
			}

			switch ( $reader->name ) {
				case 'wp:wxr_version':
					// Upgrade to the correct version
					$this->version = $reader->readString();

					if ( version_compare( $this->version, self::MAX_WXR_VERSION, '>' ) ) {
						$this->logger->warning( sprintf(
							__( 'This WXR file (version %s) is newer than the importer (version %s) and may not be supported. Please consider updating.', 'bt_wordpress_importer' ),
							$this->version,
							self::MAX_WXR_VERSION
						) );
					}

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'wp:base_site_url':
					$this->base_url = $reader->readString();

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'item':
					$node = $reader->expand();
					$parsed = $this->parse_post_node( $node );
					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					$r = $this->process_post( $parsed['data'], $parsed['meta'], $parsed['comments'], $parsed['terms'] );
					if ( $r == 'break' ) {
						echo json_encode( array( 'reader_index' => $this->reader_index, 'progress' => round( 100 * $this->step / $this->total, 1 ) . '%' ) );
						break( 2 );
					}

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'wp:author':
					$node = $reader->expand();

					$parsed = $this->parse_author_node( $node );
					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					$status = $this->process_author( $parsed['data'], $parsed['meta'] );
					if ( is_wp_error( $status ) ) {
						$this->log_error( $status );
					}

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'wp:category':
					$node = $reader->expand();

					$parsed = $this->parse_term_node( $node, 'category' );
					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					$status = $this->process_term( $parsed['data'], $parsed['meta'] );

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'wp:tag':
					$node = $reader->expand();

					$parsed = $this->parse_term_node( $node, 'tag' );
					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					$status = $this->process_term( $parsed['data'], $parsed['meta'] );

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'wp:term':
					$node = $reader->expand();

					$parsed = $this->parse_term_node( $node );
					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					$status = $this->process_term( $parsed['data'], $parsed['meta'] );

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				default:
					// Skip this node, probably handled by something already
					break;
			}
		}

		// Now that we've done the main processing, do any required
		// post-processing and remapping.
		$this->post_process();

		if ( $this->options['aggressive_url_search'] ) {
			$this->replace_attachment_urls_in_content();
		}
		// $this->remap_featured_images();
		
		if ( $this->end ) {
			$this->import_end();
		}
	}

	/**
	 * Log an error instance to the logger.
	 *
	 * @param WP_Error $error Error instance to log.
	 */
	protected function log_error( WP_Error $error ) {
		$this->logger->warning( $error->get_error_message() );

		// Log the data as debug info too
		$data = $error->get_error_data();
		if ( ! empty( $data ) ) {
			$this->logger->debug( var_export( $data, true ) );
		}
	}

	/**
	 * Parses the WXR file and prepares us for the task of processing parsed data
	 *
	 * @param string $file Path to the WXR file for importing
	 */
	protected function import_start( $file ) {
		if ( ! is_file( $file ) ) {
			return new WP_Error( 'wxr_importer.file_missing', __( 'The file does not exist, please try again.', 'bt_wordpress_importer' ) );
		}

		global $bt_import_data;
		$bt_import_data = file_get_contents( $file );
		
		/*if ( strpos( $bt_import_data, '[woocommerce' ) !== false && ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			echo '<p><strong>' . __( 'Error. This demo contains WooCommerce data. Please install and activate WooCommerce.', 'bt_wordpress_importer' ) . '</strong><br />';
			$this->end = true;
			die();
		}*/

		// Suspend bunches of stuff in WP core
		wp_defer_term_counting( true );
		wp_defer_comment_counting( true );
		wp_suspend_cache_invalidation( true );

		// Prefill exists calls if told to
		if ( $this->options['prefill_existing_posts'] ) {
			$this->prefill_existing_posts();
		}
		if ( $this->options['prefill_existing_comments'] ) {
			$this->prefill_existing_comments();
		}
		if ( $this->options['prefill_existing_terms'] ) {
			$this->prefill_existing_terms();
		}

		/**
		 * Begin the import.
		 *
		 * Fires before the import process has begun. If you need to suspend
		 * caching or heavy processing on hooks, do so here.
		 */
		do_action( 'import_start' );
	}

	/**
	 * Performs post-import cleanup of files and the cache
	 */
	protected function import_end() {
		// Re-enable stuff in core
		wp_suspend_cache_invalidation( false );
		wp_cache_flush();
		foreach ( get_taxonomies() as $tax ) {
			delete_option( "{$tax}_children" );
			_get_term_hierarchy( $tax );
		}

		wp_defer_term_counting( false );
		wp_defer_comment_counting( false );

		/**
		 * Complete the import.
		 *
		 * Fires after the import process has finished. If you need to update
		 * your cache or re-enable processing, do so here.
		 */
		do_action( 'import_end' );
	}

	/**
	 * Set the user mapping.
	 *
	 * @param array $mapping List of map arrays (containing `old_slug`, `old_id`, `new_id`)
	 */
	public function set_user_mapping( $mapping ) {
		foreach ( $mapping as $map ) {
			if ( empty( $map['old_slug'] ) || empty( $map['old_id'] ) || empty( $map['new_id'] ) ) {
				$this->logger->warning( __( 'Invalid author mapping', 'bt_wordpress_importer' ) );
				$this->logger->debug( var_export( $map, true ) );
				continue;
			}

			$old_slug = $map['old_slug'];
			$old_id   = $map['old_id'];
			$new_id   = $map['new_id'];

			$this->mapping['user'][ $old_id ]        = $new_id;
			$this->mapping['user_slug'][ $old_slug ] = $new_id;
		}
	}

	/**
	 * Set the user slug overrides.
	 *
	 * Allows overriding the slug in the import with a custom/renamed version.
	 *
	 * @param string[] $overrides Map of old slug to new slug.
	 */
	public function set_user_slug_overrides( $overrides ) {
		foreach ( $overrides as $original => $renamed ) {
			$this->user_slug_override[ $original ] = $renamed;
		}
	}

	/**
	 * Parse a post node into post data.
	 *
	 * @param DOMElement $node Parent node of post data (typically `item`).
	 * @return array|WP_Error Post data array on success, error otherwise.
	 */
	protected function parse_post_node( $node ) {
		$data = array();
		$meta = array();
		$comments = array();
		$terms = array();

		foreach ( $node->childNodes as $child ) {
			// We only care about child elements
			if ( $child->nodeType !== XML_ELEMENT_NODE ) {
				continue;
			}

			switch ( $child->tagName ) {
				case 'wp:post_type':
					$data['post_type'] = $child->textContent;
					break;

				case 'title':
					$data['post_title'] = $child->textContent;
					break;

				case 'guid':
					$data['guid'] = $child->textContent;
					break;

				case 'dc:creator':
					$data['post_author'] = $child->textContent;
					break;

				case 'content:encoded':
					$data['post_content'] = $child->textContent;
					break;

				case 'excerpt:encoded':
					$data['post_excerpt'] = $child->textContent;
					break;

				case 'wp:post_id':
					$data['post_id'] = $child->textContent;
					break;

				case 'wp:post_date':
					$data['post_date'] = $child->textContent;
					break;

				case 'wp:post_date_gmt':
					$data['post_date_gmt'] = $child->textContent;
					break;

				case 'wp:comment_status':
					$data['comment_status'] = $child->textContent;
					break;

				case 'wp:ping_status':
					$data['ping_status'] = $child->textContent;
					break;

				case 'wp:post_name':
					$data['post_name'] = $child->textContent;
					break;

				case 'wp:status':
					$data['post_status'] = $child->textContent;

					if ( $data['post_status'] === 'auto-draft' ) {
						// Bail now
						return new WP_Error(
							'wxr_importer.post.cannot_import_draft',
							__( 'Cannot import auto-draft posts' ),
							$data
						);
					}
					break;

				case 'wp:post_parent':
					$data['post_parent'] = $child->textContent;
					break;

				case 'wp:menu_order':
					$data['menu_order'] = $child->textContent;
					break;

				case 'wp:post_password':
					$data['post_password'] = $child->textContent;
					break;

				case 'wp:is_sticky':
					$data['is_sticky'] = $child->textContent;
					break;

				case 'wp:attachment_url':
					$data['attachment_url'] = $child->textContent;
					break;

				case 'wp:postmeta':
					$meta_item = $this->parse_meta_node( $child );
					if ( ! empty( $meta_item ) ) {
						$meta[] = $meta_item;
					}
					break;

				case 'wp:comment':
					$comment_item = $this->parse_comment_node( $child );
					if ( ! empty( $comment_item ) ) {
						$comments[] = $comment_item;
					}
					break;

				case 'category':
					$term_item = $this->parse_category_node( $child );
					if ( ! empty( $term_item ) ) {
						$terms[] = $term_item;
					}
					break;
			}
		}

		return compact( 'data', 'meta', 'comments', 'terms' );
	}

	/**
	 * Create new posts based on import information
	 *
	 * Posts marked as having a parent which doesn't exist will become top level items.
	 * Doesn't create a new post if: the post type doesn't exist, the given post ID
	 * is already noted as imported or a post with the same title and date already exists.
	 * Note that new/updated terms, comments and meta are imported for the last of the above.
	 */
	protected function process_post( $data, $meta, $comments, $terms ) {
		/**
		 * Pre-process post data.
		 *
		 * @param array $data Post data. (Return empty to skip.)
		 * @param array $meta Meta data.
		 * @param array $comments Comments on the post.
		 * @param array $terms Terms on the post.
		 */
		$data = apply_filters( 'wxr_importer.pre_process.post', $data, $meta, $comments, $terms );
		if ( empty( $data ) ) {
			return false;
		}

		$original_id = isset( $data['post_id'] )     ? (int) $data['post_id']     : 0;
		$parent_id   = isset( $data['post_parent'] ) ? (int) $data['post_parent'] : 0;
		$author_id   = isset( $data['post_author'] ) ? (int) $data['post_author'] : 0;

		$user_login = $data['post_author'];

		// Have we already processed this?
		if ( isset( $this->mapping['post'][ $original_id ] ) ) {
			return;
		}

		$post_type_object = get_post_type_object( $data['post_type'] );

		// Is this type even valid?
		if ( ! $post_type_object ) {
			$this->logger->warning( sprintf(
				__( 'Failed to import "%s": Invalid post type %s', 'bt_wordpress_importer' ),
				$data['post_title'],
				$data['post_type']
			) );

			$this->bt_status( $data['post_id'] );

			return false;
		}

		$post_exists = $this->post_exists( $data );
		if ( $post_exists ) {
			$this->logger->info( sprintf(
				__( '%s "%s" already exists.', 'bt_wordpress_importer' ),
				$post_type_object->labels->singular_name,
				$data['post_title']
			) );

			/**
			 * Post processing already imported.
			 *
			 * @param array $data Raw data imported for the post.
			 */
			do_action( 'wxr_importer.process_already_imported.post', $data );

			// Even though this post already exists, new comments might need importing
			$this->process_comments( $comments, $original_id, $data, $post_exists );

			$this->bt_status( $data['post_id'] );

			if ( 'attachment' === $data['post_type'] ) {
				return 'break';
			}

			return false;
		}

		// Map the parent post, or mark it as one we need to fix
		$requires_remapping = false;
		if ( $parent_id ) {
			if ( isset( $this->mapping['post'][ $parent_id ] ) ) {
				$data['post_parent'] = $this->mapping['post'][ $parent_id ];
			} else {
				$meta[] = array( 'key' => '_wxr_import_parent', 'value' => $parent_id );
				$requires_remapping = true;

				$data['post_parent'] = 0;
			}
		}

		// Map the author, or mark it as one we need to fix
		$author = sanitize_user( $data['post_author'], true );
		if ( empty( $author ) ) {
			// Missing or invalid author, use default if available.
			$data['post_author'] = $this->options['default_author'];
		} elseif ( isset( $this->mapping['user_slug'][ $author ] ) ) {
			$data['post_author'] = $this->mapping['user_slug'][ $author ];
		} else {
			$meta[] = array( 'key' => '_wxr_import_user_slug', 'value' => $author );
			$requires_remapping = true;

			$data['post_author'] = (int) get_current_user_id();
		}

		// Does the post look like it contains attachment images?
		if ( preg_match( self::REGEX_HAS_ATTACHMENT_REFS, $data['post_content'] ) ) {
			$meta[] = array( 'key' => '_wxr_import_has_attachment_refs', 'value' => true );
			$requires_remapping = true;
		}

		// Whitelist to just the keys we allow
		$postdata = array(
			'import_id' => $data['post_id'],
		);
		$allowed = array(
			'post_author'    => true,
			'post_date'      => true,
			'post_date_gmt'  => true,
			'post_content'   => true,
			'post_excerpt'   => true,
			'post_title'     => true,
			'post_status'    => true,
			'post_name'      => true,
			'comment_status' => true,
			'ping_status'    => true,
			'guid'           => true,
			'post_parent'    => true,
			'menu_order'     => true,
			'post_type'      => true,
			'post_password'  => true
		);
		foreach ( $data as $key => $value ) {
			if ( ! isset( $allowed[ $key ] ) ) {
				continue;
			}

			$postdata[ $key ] = $data[ $key ];
		}

		$postdata = apply_filters( 'wp_import_post_data_processed', $postdata, $data );

		if ( 'attachment' === $postdata['post_type'] ) {
			if ( ! $this->options['fetch_attachments'] ) {
				$this->logger->notice( sprintf(
					__( 'Skipping attachment "%s", fetching attachments disabled' ),
					$data['post_title']
				) );
				/**
				 * Post processing skipped.
				 *
				 * @param array $data Raw data imported for the post.
				 * @param array $meta Raw meta data, already processed by {@see process_post_meta}.
				 */
				do_action( 'wxr_importer.process_skipped.post', $data, $meta );
				return false;
			}
			$remote_url = ! empty( $data['attachment_url'] ) ? $data['attachment_url'] : $data['guid'];

			$post_id = $this->process_attachment( $postdata, $meta, $remote_url );
		} else {
			global $wpdb;

			$users = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->base_prefix}users WHERE user_login=%s", $user_login ), OBJECT );
			if ( is_array( $users ) && count( $users ) > 0 ) {
				$postdata['post_author'] = $users[0]->ID;
			}
			$post_id = wp_insert_post( $postdata, true );
			do_action( 'wp_import_insert_post', $post_id, $original_id, $postdata, $data );
		}

		if ( is_wp_error( $post_id ) ) {
			$this->logger->error( sprintf(
				__( 'Failed to import "%s" (%s)', 'bt_wordpress_importer' ),
				$data['post_title'],
				$post_type_object->labels->singular_name
			) );
			$this->logger->debug( $post_id->get_error_message() );

			/**
			 * Post processing failed.
			 *
			 * @param WP_Error $post_id Error object.
			 * @param array $data Raw data imported for the post.
			 * @param array $meta Raw meta data, already processed by {@see process_post_meta}.
			 * @param array $comments Raw comment data, already processed by {@see process_comments}.
			 * @param array $terms Raw term data, already processed.
			 */
			do_action( 'wxr_importer.process_failed.post', $post_id, $data, $meta, $comments, $terms );
			return false;
		}

		// Ensure stickiness is handled correctly too
		if ( $data['is_sticky'] === '1' ) {
			stick_post( $post_id );
		}

		// map pre-import ID to local ID
		$this->mapping['post'][ $original_id ] = (int) $post_id;
		if ( $requires_remapping ) {
			$this->requires_remapping['post'][ $post_id ] = true;
		}
		$this->mark_post_exists( $data, $post_id );

		$this->logger->info( sprintf(
			__( 'Imported "%s" (%s)', 'bt_wordpress_importer' ),
			$data['post_title'],
			$post_type_object->labels->singular_name
		) );
		$this->logger->debug( sprintf(
			__( 'Post %d remapped to %d', 'bt_wordpress_importer' ),
			$original_id,
			$post_id
		) );

		// Handle the terms too
		$terms = apply_filters( 'wp_import_post_terms', $terms, $post_id, $data );
		if ( ! empty( $terms ) ) {
			//$term_ids = array();
			foreach ( $terms as $term ) {

				$taxonomy = $term['taxonomy'];
				$slug = $term['slug'];

				$existing_term = get_term_by( 'slug', $slug, $taxonomy );

				if ( $existing_term ) {
					wp_set_post_terms( $post_id, array( intval( $existing_term->term_id ) ), $taxonomy, true );
				}

				/*if ( isset( $this->mapping['term'][ $key ] ) ) {
					$term_ids[ $taxonomy ][] = (int) $this->mapping['term'][ $key ];
				} else {
					$meta[] = array( 'key' => '_wxr_import_term', 'value' => $term );
					$requires_remapping = true;
				}*/

				if ( $taxonomy == 'post_format' ) {
					set_post_format( $post_id, str_replace( 'post-format-', '', $slug ) );
				}

			}

			wp_remove_object_terms( $post_id, 'uncategorized', 'category' );

			/*foreach ( $term_ids as $tax => $ids ) {
				$tt_ids = wp_set_post_terms( $post_id, $ids, $tax );
				do_action( 'wp_import_set_post_terms', $tt_ids, $ids, $tax, $post_id, $data );
			}*/

		}

		$this->process_comments( $comments, $post_id, $data );
		$this->process_post_meta( $meta, $post_id, $data );

		if ( 'nav_menu_item' === $data['post_type'] ) {
			$this->process_menu_item_meta( $post_id, $data, $meta );
		}

		/**
		 * Post processing completed.
		 *
		 * @param int $post_id New post ID.
		 * @param array $data Raw data imported for the post.
		 * @param array $meta Raw meta data, already processed by {@see process_post_meta}.
		 * @param array $comments Raw comment data, already processed by {@see process_comments}.
		 * @param array $terms Raw term data, already processed.
		 */
		do_action( 'wxr_importer.processed.post', $post_id, $data, $meta, $comments, $terms );

		$this->bt_status( $data['post_id'] );

		if ( 'attachment' === $data['post_type'] ) {
			return 'break';
		}

	}

	/**
	 * Attempt to create a new menu item from import data
	 *
	 * Fails for draft, orphaned menu items and those without an associated nav_menu
	 * or an invalid nav_menu term. If the post type or term object which the menu item
	 * represents doesn't exist then the menu item will not be imported (waits until the
	 * end of the import to retry again before discarding).
	 *
	 * @param array $item Menu item details from WXR file
	 */
	protected function process_menu_item_meta( $post_id, $data, $meta ) {

		$item_type = get_post_meta( $post_id, '_menu_item_type', true );
		$original_object_id = get_post_meta( $post_id, '_menu_item_object_id', true );
		$object_id = null;

		$this->logger->debug( sprintf( 'Processing menu item %s', $item_type ) );

		$requires_remapping = false;
		switch ( $item_type ) {
			case 'taxonomy':
				if ( isset( $this->mapping['term_id'][ $original_object_id ] ) ) {
					$object_id = $this->mapping['term_id'][ $original_object_id ];
				} else {
					add_post_meta( $post_id, '_wxr_import_menu_item', wp_slash( $original_object_id ) );
					$requires_remapping = true;
				}
				break;

			case 'post_type':
				$object = get_post_meta( $post_id, '_menu_item_object', true );
				if ( $object == 'product' && ! class_exists( 'woocommerce' ) ) {
					wp_delete_post( $post_id, true );
				} else {
					if ( isset( $this->mapping['post'][ $original_object_id ] ) ) {
						$object_id = $this->mapping['post'][ $original_object_id ];
					} else {
						add_post_meta( $post_id, '_wxr_import_menu_item', wp_slash( $original_object_id ) );
						$requires_remapping = true;
					}
				}
				break;

			case 'custom':
				// Custom refers to itself, wonderfully easy.
				$object_id = $post_id;
				break;

			default:
				// associated object is missing or not imported yet, we'll retry later
				//$this->missing_menu_items[] = $item;
				//$this->logger->debug( 'Unknown menu item type' );
				break;
		}

		if ( $requires_remapping ) {
			$this->requires_remapping['post'][ $post_id ] = true;
		}

		if ( empty( $object_id ) ) {
			// Nothing needed here.
			return;
		}

		$this->logger->debug( sprintf( 'Menu item %d mapped to %d', $original_object_id, $object_id ) );
		update_post_meta( $post_id, '_menu_item_object_id', wp_slash( $object_id ) );
	}

	/**
	 * If fetching attachments is enabled then attempt to create a new attachment
	 *
	 * @param array $post Attachment post details from WXR
	 * @param string $url URL to fetch attachment from
	 * @return int|WP_Error Post ID on success, WP_Error otherwise
	 */
	protected function process_attachment( $post, $meta, $remote_url ) {
		// try to use _wp_attached file for upload folder placement to ensure the same location as the export site
		// e.g. location is 2003/05/image.jpg but the attachment post_date is 2010/09, see media_handle_upload()


	if ( strpos( parse_url($remote_url, PHP_URL_HOST), parse_url($this->base_url, PHP_URL_HOST) ) !== false ) {
			$this->internal_file = true;
		} else {
			$this->internal_file = false;
		}

		$post['upload_date'] = $post['post_date'];
		foreach ( $meta as $meta_item ) {
			if ( $meta_item['key'] !== '_wp_attached_file' ) {
				continue;
			}

			if ( preg_match( '%^[0-9]{4}/[0-9]{2}%', $meta_item['value'], $matches ) ) {
				$post['upload_date'] = $matches[0];
			}
			break;
		}

		// if the URL is absolute, but does not contain address, then upload it assuming base_site_url
		
		if ( $this->options['force_download'] || $this->internal_file ) {
		
			if ( preg_match( '|^/[\w\W]+$|', $remote_url ) ) {
				$remote_url = rtrim( $this->base_url, '/' ) . $remote_url;
			}
				
			$upload = $this->fetch_remote_file( $remote_url, $post );
			if ( is_wp_error( $upload ) ) {
				return $upload;
			}

			$info = wp_check_filetype( $upload['file'] );
			if ( ! $info ) {
				return new WP_Error( 'attachment_processing_error', __( 'Invalid file type', 'bt_wordpress_importer' ) );
			}

			$post['post_mime_type'] = $info['type'];

			// WP really likes using the GUID for display. Allow updating it.
			// See https://core.trac.wordpress.org/ticket/33386

		
			if ( $this->options['update_attachment_guids'] ) {
				$post['guid'] = $upload['url'];
			}
			
			// as per wp-admin/includes/upload.php
			$post_id = wp_insert_attachment( $post, $upload['file'] );
			if ( is_wp_error( $post_id ) ) {
				return $post_id;
			}

			$attachment_metadata = wp_generate_attachment_metadata( $post_id, $upload['file'] );
			wp_update_attachment_metadata( $post_id, $attachment_metadata );
			
			// Map this image URL later if we need to
			$this->url_remap[ $remote_url ] = $upload['url'];

			// If we have a HTTPS URL, ensure the HTTP URL gets replaced too
			if ( substr( $remote_url, 0, 8 ) === 'https://' ) {
				$insecure_url = 'http' . substr( $remote_url, 5 );
				$this->url_remap[ $insecure_url ] = $upload['url'];
			}
		} else {
			
			$mime_type_of_the_image = "";

			foreach ( $meta as $meta_item ) {
				if ( $meta_item['key'] !== '_wp_attachment_metadata' ) {
					continue;
				}

				$meta_array_bt = maybe_unserialize($meta_item['value']);
				$mime_type_of_the_image = $meta_array_bt['sizes']['thumbnail']['mime-type'];

				break;
			}
			
			if ( $mime_type_of_the_image == "" ) {
				$bt_response = wp_remote_head( $remote_url );
				if ( is_array( $bt_response ) && isset( $bt_response['headers']['content-type'] ) ) {
					$mime_type_of_the_image = $bt_response['headers']['content-type'];
				}
			}

			$bt_attachment = array(
				'import_id' => $post["import_id"],
				'guid' => $remote_url,
				'post_mime_type' => $mime_type_of_the_image,
				'post_title' => preg_replace( '/\.[^.]+$/', '', wp_basename( $remote_url ) ),
			);

			$post_id = wp_insert_attachment( $bt_attachment );

			if ( is_wp_error( $post_id ) ) {
					return $post_id;
			}

			// Map this image URL later if we need to
			$this->url_remap[ $remote_url ] = $remote_url;

			// If we have a HTTPS URL, ensure the HTTP URL gets replaced too
			if ( substr( $remote_url, 0, 8 ) === 'https://' ) {
				$insecure_url = 'http' . substr( $remote_url, 5 );
				$this->url_remap[ $insecure_url ] = $remote_url;
			}
		}

		if ( $this->options['aggressive_url_search'] ) {
			// remap resized image URLs, works by stripping the extension and remapping the URL stub.
			/*if ( preg_match( '!^image/!', $info['type'] ) ) {
				$parts = pathinfo( $remote_url );
				$name = basename( $parts['basename'], ".{$parts['extension']}" ); // PATHINFO_FILENAME in PHP 5.2

				$parts_new = pathinfo( $upload['url'] );
				$name_new = basename( $parts_new['basename'], ".{$parts_new['extension']}" );

				$this->url_remap[$parts['dirname'] . '/' . $name] = $parts_new['dirname'] . '/' . $name_new;
			}*/
		}

		return $post_id;
	}

	/**
	 * Parse a meta node into meta data.
	 *
	 * @param DOMElement $node Parent node of meta data (typically `wp:postmeta` or `wp:commentmeta`).
	 * @return array|null Meta data array on success, or null on error.
	 */
	protected function parse_meta_node( $node ) {
		foreach ( $node->childNodes as $child ) {
			// We only care about child elements
			if ( $child->nodeType !== XML_ELEMENT_NODE ) {
				continue;
			}

			switch ( $child->tagName ) {
				case 'wp:meta_key':
					$key = $child->textContent;
					break;

				case 'wp:meta_value':
					$value = $child->textContent;
					break;
			}
		}

		if ( empty( $key ) || empty( $value ) ) {
			return null;
		}

		return compact( 'key', 'value' );
	}

	/**
	 * Process and import post meta items.
	 *
	 * @param array $meta List of meta data arrays
	 * @param int $post_id Post to associate with
	 * @param array $post Post data
	 * @return int|WP_Error Number of meta items imported on success, error otherwise.
	 */
	protected function process_post_meta( $meta, $post_id, $post ) {
		if ( empty( $meta ) ) {
			return true;
		}

		foreach ( $meta as $meta_item ) {
			/**
			 * Pre-process post meta data.
			 *
			 * @param array $meta_item Meta data. (Return empty to skip.)
			 * @param int $post_id Post the meta is attached to.
			 */
			$meta_item = apply_filters( 'wxr_importer.pre_process.post_meta', $meta_item, $post_id );
			if ( empty( $meta_item ) ) {
				return false;
			}

			$key = apply_filters( 'import_post_meta_key', $meta_item['key'], $post_id, $post );
			$value = false;

			if ( '_edit_last' === $key ) {
				$value = intval( $meta_item['value'] );
				if ( ! isset( $this->mapping['user'][ $value ] ) ) {
					// Skip!
					continue;
				}

				$value = $this->mapping['user'][ $value ];
			}

			if ( $key ) {
				// export gets meta straight from the DB so could have a serialized string
				if ( ! $value ) {
					$value = maybe_unserialize( $meta_item['value'] );
				}
				
				if ( !$this->options['force_download'] || $this->internal_file ) {
					if ( in_array( $key, array( '_wp_attachment_metadata' ) ) ) {
						$value['file'] = basename($value['file']);
					}
				}

				add_post_meta( $post_id, $key, $value );
				do_action( 'import_post_meta', $post_id, $key, $value );

				// if the post has a featured image, take note of this in case of remap
				if ( '_thumbnail_id' === $key ) {
					$this->featured_images[ $post_id ] = (int) $value;
				}
			}
		}

		return true;
	}

	/**
	 * Parse a comment node into comment data.
	 *
	 * @param DOMElement $node Parent node of comment data (typically `wp:comment`).
	 * @return array Comment data array.
	 */
	protected function parse_comment_node( $node ) {
		$data = array(
			'commentmeta' => array(),
		);

		foreach ( $node->childNodes as $child ) {
			// We only care about child elements
			if ( $child->nodeType !== XML_ELEMENT_NODE ) {
				continue;
			}

			switch ( $child->tagName ) {
				case 'wp:comment_id':
					$data['comment_id'] = $child->textContent;
					break;
				case 'wp:comment_author':
					$data['comment_author'] = $child->textContent;
					break;

				case 'wp:comment_author_email':
					$data['comment_author_email'] = $child->textContent;
					break;

				case 'wp:comment_author_IP':
					$data['comment_author_IP'] = $child->textContent;
					break;

				case 'wp:comment_author_url':
					$data['comment_author_url'] = $child->textContent;
					break;

				case 'wp:comment_user_id':
					$data['comment_user_id'] = $child->textContent;
					break;

				case 'wp:comment_date':
					$data['comment_date'] = $child->textContent;
					break;

				case 'wp:comment_date_gmt':
					$data['comment_date_gmt'] = $child->textContent;
					break;

				case 'wp:comment_content':
					$data['comment_content'] = $child->textContent;
					break;

				case 'wp:comment_approved':
					$data['comment_approved'] = $child->textContent;
					break;

				case 'wp:comment_type':
					$data['comment_type'] = $child->textContent;
					break;

				case 'wp:comment_parent':
					$data['comment_parent'] = $child->textContent;
					break;

				case 'wp:commentmeta':
					$meta_item = $this->parse_meta_node( $child );
					if ( ! empty( $meta_item ) ) {
						$data['commentmeta'][] = $meta_item;
					}
					break;
			}
		}

		return $data;
	}

	/**
	 * Process and import comment data.
	 *
	 * @param array $comments List of comment data arrays.
	 * @param int $post_id Post to associate with.
	 * @param array $post Post data.
	 * @return int|WP_Error Number of comments imported on success, error otherwise.
	 */
	protected function process_comments( $comments, $post_id, $post, $post_exists = false ) {

		$comments = apply_filters( 'wp_import_post_comments', $comments, $post_id, $post );
		if ( empty( $comments ) ) {
			return 0;
		}

		$num_comments = 0;

		// Sort by ID to avoid excessive remapping later
		usort( $comments, array( $this, 'sort_comments_by_id' ) );

		foreach ( $comments as $key => $comment ) {
			/**
			 * Pre-process comment data
			 *
			 * @param array $comment Comment data. (Return empty to skip.)
			 * @param int $post_id Post the comment is attached to.
			 */
			$comment = apply_filters( 'wxr_importer.pre_process.comment', $comment, $post_id );
			if ( empty( $comment ) ) {
				return false;
			}

			$original_id = isset( $comment['comment_id'] )      ? (int) $comment['comment_id']      : 0;
			$parent_id   = isset( $comment['comment_parent'] )  ? (int) $comment['comment_parent']  : 0;
			$author_id   = isset( $comment['comment_user_id'] ) ? (int) $comment['comment_user_id'] : 0;

			// if this is a new post we can skip the comment_exists() check
			// TODO: Check comment_exists for performance
			if ( $post_exists ) {
				$existing = $this->comment_exists( $comment );
				if ( $existing ) {

					/**
					 * Comment processing already imported.
					 *
					 * @param array $comment Raw data imported for the comment.
					 */
					do_action( 'wxr_importer.process_already_imported.comment', $comment );

					$this->mapping['comment'][ $original_id ] = $existing;
					continue;
				}
			}

			// Remove meta from the main array
			$meta = isset( $comment['commentmeta'] ) ? $comment['commentmeta'] : array();
			unset( $comment['commentmeta'] );

			// Map the parent comment, or mark it as one we need to fix
			$requires_remapping = false;
			if ( $parent_id ) {
				if ( isset( $this->mapping['comment'][ $parent_id ] ) ) {
					$comment['comment_parent'] = $this->mapping['comment'][ $parent_id ];
				} else {
					// Prepare for remapping later
					$meta[] = array( 'key' => '_wxr_import_parent', 'value' => $parent_id );
					$requires_remapping = true;

					// Wipe the parent for now
					$comment['comment_parent'] = 0;
				}
			}

			// Map the author, or mark it as one we need to fix
			if ( $author_id ) {
				if ( isset( $this->mapping['user'][ $author_id ] ) ) {
					$comment['user_id'] = $this->mapping['user'][ $author_id ];
				} else {
					// Prepare for remapping later
					$meta[] = array( 'key' => '_wxr_import_user', 'value' => $author_id );
					$requires_remapping = true;

					// Wipe the user for now
					$comment['user_id'] = 0;
				}
			}

			// Run standard core filters
			$comment['comment_post_ID'] = $post_id;
			$comment = wp_filter_comment( $comment );

			// wp_insert_comment expects slashed data
			$comment_id = wp_insert_comment( wp_slash( $comment ) );
			$this->mapping['comment'][ $original_id ] = $comment_id;
			if ( $requires_remapping ) {
				$this->requires_remapping['comment'][ $comment_id ] = true;
			}
			$this->mark_comment_exists( $comment, $comment_id );

			/**
			 * Comment has been imported.
			 *
			 * @param int $comment_id New comment ID
			 * @param array $comment Comment inserted (`comment_id` item refers to the original ID)
			 * @param int $post_id Post parent of the comment
			 * @param array $post Post data
			 */
			do_action( 'wp_import_insert_comment', $comment_id, $comment, $post_id, $post );

			// Process the meta items
			foreach ( $meta as $meta_item ) {
				$value = maybe_unserialize( $meta_item['value'] );
				add_comment_meta( $comment_id, wp_slash( $meta_item['key'] ), wp_slash( $value ) );
			}

			/**
			 * Post processing completed.
			 *
			 * @param int $post_id New post ID.
			 * @param array $comment Raw data imported for the comment.
			 * @param array $meta Raw meta data, already processed by {@see process_post_meta}.
			 * @param array $post_id Parent post ID.
			 */
			do_action( 'wxr_importer.processed.comment', $comment_id, $comment, $meta, $post_id );

			$num_comments++;
		}

		return $num_comments;
	}

	protected function parse_category_node( $node ) {
		$data = array(
			// Default taxonomy to "category", since this is a `<category>` tag
			'taxonomy' => 'category',
		);
		$meta = array();

		if ( $node->hasAttribute( 'domain' ) ) {
			$data['taxonomy'] = $node->getAttribute( 'domain' );
		}
		if ( $node->hasAttribute( 'nicename' ) ) {
			$data['slug'] = $node->getAttribute( 'nicename' );
		}

		$data['name'] = $node->textContent;

		if ( empty( $data['slug'] ) ) {
			return null;
		}

		// Just for extra compatibility
		if ( $data['taxonomy'] === 'tag' ) {
			$data['taxonomy'] = 'post_tag';
		}

		return $data;
	}

	/**
	 * Callback for `usort` to sort comments by ID
	 *
	 * @param array $a Comment data for the first comment
	 * @param array $b Comment data for the second comment
	 * @return int
	 */
	public static function sort_comments_by_id( $a, $b ) {
		if ( empty( $a['comment_id'] ) ) {
			return 1;
		}

		if ( empty( $b['comment_id'] ) ) {
			return -1;
		}

		return $a['comment_id'] - $b['comment_id'];
	}

	protected function parse_author_node( $node ) {
		$data = array();
		$meta = array();
		foreach ( $node->childNodes as $child ) {
			// We only care about child elements
			if ( $child->nodeType !== XML_ELEMENT_NODE ) {
				continue;
			}

			switch ( $child->tagName ) {
				case 'wp:author_login':
					$data['user_login'] = $child->textContent;
					break;

				case 'wp:author_id':
					$data['ID'] = $child->textContent;
					break;

				case 'wp:author_email':
					$data['user_email'] = $child->textContent;
					break;

				case 'wp:author_display_name':
					$data['display_name'] = $child->textContent;
					break;

				case 'wp:author_first_name':
					$data['first_name'] = $child->textContent;
					break;

				case 'wp:author_last_name':
					$data['last_name'] = $child->textContent;
					break;
			}
		}

		return compact( 'data', 'meta' );
	}

	protected function process_author( $data, $meta ) {
		/**
		 * Pre-process user data.
		 *
		 * @param array $data User data. (Return empty to skip.)
		 * @param array $meta Meta data.
		 */
		$data = apply_filters( 'wxr_importer.pre_process.user', $data, $meta );
		if ( empty( $data ) ) {
			return false;
		}

		// Have we already handled this user?
		$original_id = isset( $data['ID'] ) ? $data['ID'] : 0;
		$original_slug = $data['user_login'];

		if ( isset( $this->mapping['user'][ $original_id ] ) ) {
			$existing = $this->mapping['user'][ $original_id ];

			// Note the slug mapping if we need to too
			if ( ! isset( $this->mapping['user_slug'][ $original_slug ] ) ) {
				$this->mapping['user_slug'][ $original_slug ] = $existing;
			}

			return false;
		}

		if ( isset( $this->mapping['user_slug'][ $original_slug ] ) ) {
			$existing = $this->mapping['user_slug'][ $original_slug ];

			// Ensure we note the mapping too
			$this->mapping['user'][ $original_id ] = $existing;

			return false;
		}

		// Allow overriding the user's slug
		$login = $original_slug;
		if ( isset( $this->user_slug_override[ $login ] ) ) {
			$login = $this->user_slug_override[ $login ];
		}

		$userdata = array(
			'user_login'   => sanitize_user( $login, true ),
			'user_pass'    => wp_generate_password(),
		);

		$allowed = array(
			'user_email'   => true,
			'display_name' => true,
			'first_name'   => true,
			'last_name'    => true,
		);
		foreach ( $data as $key => $value ) {
			if ( ! isset( $allowed[ $key ] ) ) {
				continue;
			}

			$userdata[ $key ] = $data[ $key ];
		}

		$user_id = wp_insert_user( wp_slash( $userdata ) );

		if ( is_multisite() ) {
			global $wpdb;
			$users = $wpdb->get_results( "SELECT * FROM {$wpdb->base_prefix}users", OBJECT );
			$blog_id = get_current_blog_id();
			$role = 'editor';
			foreach( $users as $user ) {
				if ( $user->user_login == $userdata['user_login'] ) {
					add_user_to_blog( $blog_id, $user->ID, $role );
				}
			}
		}

		if ( is_wp_error( $user_id ) ) {
			$this->logger->error( sprintf(
				__( 'Failed to import user "%s"', 'bt_wordpress_importer' ),
				$userdata['user_login']
			) );
			$this->logger->debug( $user_id->get_error_message() );

			/**
			 * User processing failed.
			 *
			 * @param WP_Error $user_id Error object.
			 * @param array $userdata Raw data imported for the user.
			 */
			do_action( 'wxr_importer.process_failed.user', $user_id, $userdata );
			return false;
		}

		if ( $original_id ) {
			$this->mapping['user'][ $original_id ] = $user_id;
		}
		$this->mapping['user_slug'][ $original_slug ] = $user_id;

		$this->logger->info( sprintf(
			__( 'Imported user "%s"', 'bt_wordpress_importer' ),
			$userdata['user_login']
		) );
		$this->logger->debug( sprintf(
			__( 'User %d remapped to %d', 'bt_wordpress_importer' ),
			$original_id,
			$user_id
		) );

		// TODO: Implement meta handling once WXR includes it
		/**
		 * User processing completed.
		 *
		 * @param int $user_id New user ID.
		 * @param array $userdata Raw data imported for the user.
		 */
		do_action( 'wxr_importer.processed.user', $user_id, $userdata );
	}

	protected function parse_term_node( $node, $type = 'term' ) {
		$data = array();
		$meta = array();

		$tag_name = array(
			'id'          => 'wp:term_id',
			'taxonomy'    => 'wp:term_taxonomy',
			'slug'        => 'wp:term_slug',
			'parent'      => 'wp:term_parent',
			'name'        => 'wp:term_name',
			'description' => 'wp:term_description',
		);
		$taxonomy = null;

		// Special casing!
		switch ( $type ) {
			case 'category':
				$tag_name['slug']        = 'wp:category_nicename';
				$tag_name['parent']      = 'wp:category_parent';
				$tag_name['name']        = 'wp:cat_name';
				$tag_name['description'] = 'wp:category_description';
				$tag_name['taxonomy']    = null;

				$data['taxonomy'] = 'category';
				break;

			case 'tag':
				$tag_name['slug']        = 'wp:tag_slug';
				$tag_name['parent']      = null;
				$tag_name['name']        = 'wp:tag_name';
				$tag_name['description'] = 'wp:tag_description';
				$tag_name['taxonomy']    = null;

				$data['taxonomy'] = 'post_tag';
				break;
		}

		foreach ( $node->childNodes as $child ) {
			// We only care about child elements
			if ( $child->nodeType !== XML_ELEMENT_NODE ) {
				continue;
			}

			$key = array_search( $child->tagName, $tag_name );
			if ( $key ) {
				$data[ $key ] = $child->textContent;
			}
		}

		if ( empty( $data['taxonomy'] ) ) {
			return null;
		}

		// Compatibility with WXR 1.0
		if ( $data['taxonomy'] === 'tag' ) {
			$data['taxonomy'] = 'post_tag';
		}

		return compact( 'data', 'meta' );
	}

	protected function process_term( $data, $meta ) {
		/**
		 * Pre-process term data.
		 *
		 * @param array $data Term data. (Return empty to skip.)
		 * @param array $meta Meta data.
		 */
		$data = apply_filters( 'wxr_importer.pre_process.term', $data, $meta );
		if ( empty( $data ) ) {
			return false;
		}

		$original_id = isset( $data['id'] )      ? (int) $data['id']      : 0;
		$parent_id   = isset( $data['parent'] )  ? (int) $data['parent']  : 0;

		$parent_slug = isset( $data['parent'] )  ? $data['parent']  : '';

		$mapping_key = sha1( $data['taxonomy'] . ':' . $data['slug'] );
		$existing = $this->term_exists( $data );
		if ( $existing ) {

			/**
			 * Term processing already imported.
			 *
			 * @param array $data Raw data imported for the term.
			 */
			do_action( 'wxr_importer.process_already_imported.term', $data );

			$this->mapping['term'][ $mapping_key ] = $existing;
			$this->mapping['term_id'][ $original_id ] = $existing;
			return false;
		}

		// WP really likes to repeat itself in export files
		if ( isset( $this->mapping['term'][ $mapping_key ] ) ) {
			return false;
		}

		$termdata = array();
		$allowed = array(
			'slug' => true,
			'description' => true,
		);

		// Map the parent comment, or mark it as one we need to fix
		// TODO: add parent mapping and remapping
		/*$requires_remapping = false;
		if ( $parent_id ) {
			if ( isset( $this->mapping['term'][ $parent_id ] ) ) {
				$data['parent'] = $this->mapping['term'][ $parent_id ];
			} else {
				// Prepare for remapping later
				$meta[] = array( 'key' => '_wxr_import_parent', 'value' => $parent_id );
				$requires_remapping = true;

				// Wipe the parent for now
				$data['parent'] = 0;
			}
		}*/

		foreach ( $data as $key => $value ) {
			if ( ! isset( $allowed[ $key ] ) ) {
				continue;
			}

			$termdata[ $key ] = $data[ $key ];
		}

		$has_parent = false;
		if ( $parent_slug != '' ) {
			$parent_term = term_exists( $parent_slug, $data['taxonomy'] );
			if ( $parent_term ) {
				$has_parent = true;
				$termdata['parent'] = (int) $parent_term['term_id'];
			}
		}

		$result = wp_insert_term( $data['name'], $data['taxonomy'], $termdata );

		if ( is_wp_error( $result ) ) {
			$this->logger->warning( sprintf(
				__( 'Failed to import %s %s', 'bt_wordpress_importer' ),
				$data['taxonomy'],
				$data['name']
			) );
			$this->logger->debug( $result->get_error_message() );
			do_action( 'wp_import_insert_term_failed', $result, $data );

			/**
			 * Term processing failed.
			 *
			 * @param WP_Error $result Error object.
			 * @param array $data Raw data imported for the term.
			 * @param array $meta Meta data supplied for the term.
			 */
			do_action( 'wxr_importer.process_failed.term', $result, $data, $meta );
			return false;
		}

		$term_id = $result['term_id'];

		delete_option( $data['taxonomy'] . '_children' );
		wp_cache_flush();	

		$this->mapping['term'][ $mapping_key ] = $term_id;
		$this->mapping['term_id'][ $original_id ] = $term_id;

		$this->logger->info( sprintf(
			__( 'Imported "%s" (%s)', 'bt_wordpress_importer' ),
			$data['name'],
			$data['taxonomy']
		) );
		$this->logger->debug( sprintf(
			__( 'Term %d remapped to %d', 'bt_wordpress_importer' ),
			$original_id,
			$term_id
		) );

		do_action( 'wp_import_insert_term', $term_id, $data );

		/**
		 * Term processing completed.
		 *
		 * @param int $term_id New term ID.
		 * @param array $data Raw data imported for the term.
		 */
		do_action( 'wxr_importer.processed.term', $term_id, $data );
	}

	/**
	 * Attempt to download a remote file attachment
	 *
	 * @param string $url URL of item to fetch
	 * @param array $post Attachment details
	 * @return array|WP_Error Local file location details on success, WP_Error otherwise
	 */
	function fetch_remote_file( $url, $post ) {
		// Extract the file name from the URL.
		$file_name = basename( parse_url( $url, PHP_URL_PATH ) );

		if ( ! $file_name ) {
			$file_name = md5( $url );
		}

		$tmp_file_name = wp_tempnam( $file_name );
		if ( ! $tmp_file_name ) {
			return new WP_Error( 'import_no_file', __( 'Could not create temporary file.', 'wordpress-importer' ) );
		}

		// Fetch the remote URL and write it to the placeholder file.
		$remote_response = wp_safe_remote_get( $url, array(
			'timeout'    => 300,
			'stream'     => true,
			'filename'   => $tmp_file_name,
			'headers'    => array(
				'Accept-Encoding' => 'identity',
			),
		) );

		if ( is_wp_error( $remote_response ) ) {
			@unlink( $tmp_file_name );
			return new WP_Error(
				'import_file_error',
				sprintf(
					/* translators: 1: The WordPress error message. 2: The WordPress error code. */
					__( 'Request failed due to an error: %1$s (%2$s)', 'wordpress-importer' ),
					esc_html( $remote_response->get_error_message() ),
					esc_html( $remote_response->get_error_code() )
				)
			);
		}

		$remote_response_code = (int) wp_remote_retrieve_response_code( $remote_response );

		// Make sure the fetch was successful.
		if ( 200 !== $remote_response_code ) {
			@unlink( $tmp_file_name );
			return new WP_Error(
				'import_file_error',
				sprintf(
					/* translators: 1: The HTTP error message. 2: The HTTP error code. */
					__( 'Remote server returned the following unexpected result: %1$s (%2$s)', 'wordpress-importer' ),
					get_status_header_desc( $remote_response_code ),
					esc_html( $remote_response_code )
				)
			);
		}

		$headers = wp_remote_retrieve_headers( $remote_response );

		// Request failed.
		if ( ! $headers ) {
			@unlink( $tmp_file_name );
			return new WP_Error( 'import_file_error', __('Remote server did not respond', 'wordpress-importer') );
		}

		$filesize = (int) filesize( $tmp_file_name );

		if ( 0 === $filesize ) {
			@unlink( $tmp_file_name );
			return new WP_Error( 'import_file_error', __('Zero size file downloaded', 'wordpress-importer') );
		}

		if ( ! isset( $headers['content-encoding'] ) && isset( $headers['content-length'] ) && $filesize !== (int) $headers['content-length'] ) {
			@unlink( $tmp_file_name );
			return new WP_Error( 'import_file_error', __('Downloaded file has incorrect size', 'wordpress-importer' ) );
		}

		$max_size = (int) $this->max_attachment_size();
		if ( ! empty( $max_size ) && $filesize > $max_size ) {
			@unlink( $tmp_file_name );
			return new WP_Error( 'import_file_error', sprintf(__('Remote file is too large, limit is %s', 'wordpress-importer' ), size_format($max_size) ) );
		}

		// Override file name with Content-Disposition header value.
		if ( ! empty( $headers['content-disposition'] ) ) {
			$file_name_from_disposition = self::get_filename_from_disposition( (array) $headers['content-disposition'] );
			if ( $file_name_from_disposition ) {
				$file_name = $file_name_from_disposition;
			}
		}

		// Set file extension if missing.
		$file_ext = pathinfo( $file_name, PATHINFO_EXTENSION );
		if ( ! $file_ext && ! empty( $headers['content-type'] ) ) {
			$extension = self::get_file_extension_by_mime_type( $headers['content-type'] );
			if ( $extension ) {
				$file_name = "{$file_name}.{$extension}";
			}
		}

		// Handle the upload like _wp_handle_upload() does.
		$wp_filetype     = wp_check_filetype_and_ext( $tmp_file_name, $file_name );
		$ext             = empty( $wp_filetype['ext'] ) ? '' : $wp_filetype['ext'];
		$type            = empty( $wp_filetype['type'] ) ? '' : $wp_filetype['type'];
		$proper_filename = empty( $wp_filetype['proper_filename'] ) ? '' : $wp_filetype['proper_filename'];

		// Check to see if wp_check_filetype_and_ext() determined the filename was incorrect.
		if ( $proper_filename ) {
			$file_name = $proper_filename;
		}

		if ( ( ! $type || ! $ext ) && ! current_user_can( 'unfiltered_upload' ) ) {
			return new WP_Error( 'import_file_error', __( 'Sorry, this file type is not permitted for security reasons.', 'wordpress-importer' ) );
		}

		$uploads = wp_upload_dir( $post['upload_date'] );
		if ( ! ( $uploads && false === $uploads['error'] ) ) {
			return new WP_Error( 'upload_dir_error', $uploads['error'] );
		}

		// Move the file to the uploads dir.
		$file_name     = wp_unique_filename( $uploads['path'], $file_name );
		$new_file      = $uploads['path'] . "/$file_name";
		$move_new_file = copy( $tmp_file_name, $new_file );

		if ( ! $move_new_file ) {
			@unlink( $tmp_file_name );
			return new WP_Error( 'import_file_error', __( 'The uploaded file could not be moved', 'wordpress-importer' ) );
		}

		// Set correct file permissions.
		$stat  = stat( dirname( $new_file ) );
		$perms = $stat['mode'] & 0000666;
		chmod( $new_file, $perms );

		$upload = array(
			'file'  => $new_file,
			'url'   => $uploads['url'] . "/$file_name",
			'type'  => $wp_filetype['type'],
			'error' => false,
		);

		// keep track of the old and new urls so we can substitute them later
		$this->url_remap[$url] = $upload['url'];
		$this->url_remap[$post['guid']] = $upload['url']; // r13735, really needed?
		// keep track of the destination if the remote url is redirected somewhere else
		if ( isset($headers['x-final-location']) && $headers['x-final-location'] != $url )
			$this->url_remap[$headers['x-final-location']] = $upload['url'];

		return $upload;
	}

	protected function post_process() {
		// Time to tackle any left-over bits
		if ( ! empty( $this->requires_remapping['post'] ) ) {
			$this->post_process_posts( $this->requires_remapping['post'] );
		}
		if ( ! empty( $this->requires_remapping['comment'] ) ) {
			$this->post_process_comments( $this->requires_remapping['comment'] );
		}
	}

	protected function post_process_posts( $todo ) {
		foreach ( $todo as $post_id => $_ ) {
			$this->logger->debug( sprintf(
				// Note: title intentionally not used to skip extra processing
				// for when debug logging is off
				__( 'Running post-processing for post %d', 'bt_wordpress_importer' ),
				$post_id
			) );

			$data = array();

			$parent_id = get_post_meta( $post_id, '_wxr_import_parent', true );
			if ( ! empty( $parent_id ) ) {
				// Have we imported the parent now?
				if ( isset( $this->mapping['post'][ $parent_id ] ) ) {
					$data['post_parent'] = $this->mapping['post'][ $parent_id ];
				} else {
					$this->logger->warning( sprintf(
						__( 'Could not find the post parent for "%s" (post #%d)', 'bt_wordpress_importer' ),
						get_the_title( $post_id ),
						$post_id
					) );
					$this->logger->debug( sprintf(
						__( 'Post %d was imported with parent %d, but could not be found', 'bt_wordpress_importer' ),
						$post_id,
						$parent_id
					) );
				}
			}

			$author_slug = get_post_meta( $post_id, '_wxr_import_user_slug', true );
			if ( ! empty( $author_slug ) ) {
				// Have we imported the user now?
				if ( isset( $this->mapping['user_slug'][ $author_slug ] ) ) {
					$data['post_author'] = $this->mapping['user_slug'][ $author_slug ];
				} else {
					$this->logger->warning( sprintf(
						__( 'Could not find the author for "%s" (post #%d)', 'bt_wordpress_importer' ),
						get_the_title( $post_id ),
						$post_id
					) );
					$this->logger->debug( sprintf(
						__( 'Post %d was imported with author "%s", but could not be found', 'bt_wordpress_importer' ),
						$post_id,
						$author_slug
					) );
				}
			}

			$has_attachments = get_post_meta( $post_id, '_wxr_import_has_attachment_refs', true );
			if ( ! empty( $has_attachments ) ) {
				$post = get_post( $post_id );
				$content = $post->post_content;

				// Replace all the URLs we've got
				$new_content = str_replace( array_keys( $this->url_remap ), $this->url_remap, $content );
				if ( $new_content !== $content ) {
					$data['post_content'] = $new_content;
				}
			}

			if ( get_post_type( $post_id ) === 'nav_menu_item' ) {
				$this->post_process_menu_item( $post_id );
			}

			// Do we have updates to make?
			if ( empty( $data ) ) {
				$this->logger->debug( sprintf(
					__( 'Post %d was marked for post-processing, but none was required.', 'bt_wordpress_importer' ),
					$post_id
				) );
				continue;
			}

			// Run the update
			$data['ID'] = $post_id;
			$result = wp_update_post( $data, true );
			if ( is_wp_error( $result ) ) {
				$this->logger->warning( sprintf(
					__( 'Could not update "%s" (post #%d) with mapped data', 'bt_wordpress_importer' ),
					get_the_title( $post_id ),
					$post_id
				) );
				$this->logger->debug( $result->get_error_message() );
				continue;
			}

			// Clear out our temporary meta keys
			delete_post_meta( $post_id, '_wxr_import_parent' );
			delete_post_meta( $post_id, '_wxr_import_user_slug' );
			delete_post_meta( $post_id, '_wxr_import_has_attachment_refs' );
		}
	}

	protected function post_process_menu_item( $post_id ) {
		$menu_object_id = get_post_meta( $post_id, '_wxr_import_menu_item', true );
		if ( empty( $menu_object_id ) ) {
			// No processing needed!
			return;
		}

		$menu_item_type = get_post_meta( $post_id, '_menu_item_type', true );
		switch ( $menu_item_type ) {
			case 'taxonomy':
				if ( isset( $this->mapping['term_id'][ $menu_object_id ] ) ) {
					$menu_object = $this->mapping['term_id'][ $menu_object_id ];
				}
				break;

			case 'post_type':
				if ( isset( $this->mapping['post'][ $menu_object_id ] ) ) {
					$menu_object = $this->mapping['post'][ $menu_object_id ];
				}
				break;

			default:
				// Cannot handle this.
				return;
		}

		if ( ! empty( $menu_object ) ) {
			update_post_meta( $post_id, '_menu_item_object_id', wp_slash( $menu_object ) );
		} else {
			$this->logger->warning( sprintf(
				__( 'Could not find the menu object for "%s" (post #%d)', 'bt_wordpress_importer' ),
				get_the_title( $post_id ),
				$post_id
			) );
			$this->logger->debug( sprintf(
				__( 'Post %d was imported with object "%d" of type "%s", but could not be found', 'bt_wordpress_importer' ),
				$post_id,
				$menu_object_id,
				$menu_item_type
			) );
		}

		delete_post_meta( $post_id, '_wxr_import_menu_item' );
	}


	protected function post_process_comments( $todo ) {
		foreach ( $todo as $comment_id => $_ ) {
			$data = array();

			$parent_id = get_comment_meta( $comment_id, '_wxr_import_parent', true );
			if ( ! empty( $parent_id ) ) {
				// Have we imported the parent now?
				if ( isset( $this->mapping['comment'][ $parent_id ] ) ) {
					$data['comment_parent'] = $this->mapping['comment'][ $parent_id ];
				} else {
					$this->logger->warning( sprintf(
						__( 'Could not find the comment parent for comment #%d', 'bt_wordpress_importer' ),
						$comment_id
					) );
					$this->logger->debug( sprintf(
						__( 'Comment %d was imported with parent %d, but could not be found', 'bt_wordpress_importer' ),
						$comment_id,
						$parent_id
					) );
				}
			}

			$author_id = get_comment_meta( $comment_id, '_wxr_import_user', true );
			if ( ! empty( $author_id ) ) {
				// Have we imported the user now?
				if ( isset( $this->mapping['user'][ $author_id ] ) ) {
					$data['user_id'] = $this->mapping['user'][ $author_id ];
				} else {
					$this->logger->warning( sprintf(
						__( 'Could not find the author for comment #%d', 'bt_wordpress_importer' ),
						$comment_id
					) );
					$this->logger->debug( sprintf(
						__( 'Comment %d was imported with author %d, but could not be found', 'bt_wordpress_importer' ),
						$comment_id,
						$author_id
					) );
				}
			}

			// Do we have updates to make?
			if ( empty( $data ) ) {
				continue;
			}

			// Run the update
			$data['comment_ID'] = $comment_ID;
			$result = wp_update_comment( wp_slash( $data ) );
			if ( empty( $result ) ) {
				$this->logger->warning( sprintf(
					__( 'Could not update comment #%d with mapped data', 'bt_wordpress_importer' ),
					$comment_id
				) );
				continue;
			}

			// Clear out our temporary meta keys
			delete_comment_meta( $comment_id, '_wxr_import_parent' );
			delete_comment_meta( $comment_id, '_wxr_import_user' );
		}
	}

	/**
	 * Use stored mapping information to update old attachment URLs
	 */
	protected function replace_attachment_urls_in_content() {
		global $wpdb;
		// make sure we do the longest urls first, in case one is a substring of another
		uksort( $this->url_remap, array( $this, 'cmpr_strlen' ) );

		foreach ( $this->url_remap as $from_url => $to_url ) {
			// remap urls in post_content
			$query = $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)", $from_url, $to_url );
			$wpdb->query( $query );

			// remap enclosure urls
			$query = $wpdb->prepare( "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key='enclosure'", $from_url, $to_url );
			$result = $wpdb->query( $query );
		}
	}

	/**
	 * Update _thumbnail_id meta to new, imported attachment IDs
	 */
	function remap_featured_images() {
		// cycle through posts that have a featured image
		foreach ( $this->featured_images as $post_id => $value ) {
			if ( isset( $this->processed_posts[ $value ] ) ) {
				$new_id = $this->processed_posts[ $value ];

				// only update if there's a difference
				if ( $new_id !== $value ) {
					update_post_meta( $post_id, '_thumbnail_id', $new_id );
				}
			}
		}
	}

	/**
	 * Decide if the given meta key maps to information we will want to import
	 *
	 * @param string $key The meta key to check
	 * @return string|bool The key if we do want to import, false if not
	 */
	public function is_valid_meta_key( $key ) {
		// skip attachment metadata since we'll regenerate it from scratch
		// skip _edit_lock as not relevant for import
		if ( $this->options['force_download'] || $this->internal_file ) {
			if ( in_array( $key, array( '_wp_attached_file', '_wp_attachment_metadata', '_edit_lock' ) ) ) {
				return false;
			} 
		} else {
			if ( in_array( $key, array( '_wp_attached_file', '_edit_lock' ) ) ) {
				return false;
			}
		}

		return $key;
	}

	/**
	 * Decide what the maximum file size for downloaded attachments is.
	 * Default is 0 (unlimited), can be filtered via import_attachment_size_limit
	 *
	 * @return int Maximum attachment file size to import
	 */
	protected function max_attachment_size() {
		return apply_filters( 'import_attachment_size_limit', 0 );
	}

	/**
	 * Added to http_request_timeout filter to force timeout at 60 seconds during import
	 *
	 * @access protected
	 * @return int 60
	 */
	function bump_request_timeout($val) {
		return 60;
	}

	// return the difference in length between two strings
	function cmpr_strlen( $a, $b ) {
		return strlen( $b ) - strlen( $a );
	}

	/**
	 * Prefill existing post data.
	 *
	 * This preloads all GUIDs into memory, allowing us to avoid hitting the
	 * database when we need to check for existence. With larger imports, this
	 * becomes prohibitively slow to perform SELECT queries on each.
	 *
	 * By preloading all this data into memory, it's a constant-time lookup in
	 * PHP instead. However, this does use a lot more memory, so for sites doing
	 * small imports onto a large site, it may be a better tradeoff to use
	 * on-the-fly checking instead.
	 */
	protected function prefill_existing_posts() {
		global $wpdb;
		$posts = $wpdb->get_results( "SELECT ID, guid FROM {$wpdb->posts}" );

		foreach ( $posts as $item ) {
			$this->exists['post'][ $item->guid ] = $item->ID;
		}
	}

	/**
	 * Does the post exist?
	 *
	 * @param array $data Post data to check against.
	 * @return int|bool Existing post ID if it exists, false otherwise.
	 */
	protected function post_exists( $data ) {
		// Constant-time lookup if we prefilled
		$exists_key = $data['guid'];

		if ( $this->options['prefill_existing_posts'] ) {
			return isset( $this->exists['post'][ $exists_key ] ) ? $this->exists['post'][ $exists_key ] : false;
		}

		// No prefilling, but might have already handled it
		if ( isset( $this->exists['post'][ $exists_key ] ) ) {
			return $this->exists['post'][ $exists_key ];
		}

		// Still nothing, try post_exists, and cache it
		$exists = post_exists( $data['post_title'], $data['post_content'], $data['post_date'] );
		$this->exists['post'][ $exists_key ] = $exists;

		return $exists;
	}

	/**
	 * Mark the post as existing.
	 *
	 * @param array $data Post data to mark as existing.
	 * @param int $post_id Post ID.
	 */
	protected function mark_post_exists( $data, $post_id ) {
		$exists_key = $data['guid'];
		$this->exists['post'][ $exists_key ] = $post_id;
	}

	/**
	 * Prefill existing comment data.
	 *
	 * @see self::prefill_existing_posts() for justification of why this exists.
	 */
	protected function prefill_existing_comments() {
		global $wpdb;
		$posts = $wpdb->get_results( "SELECT comment_ID, comment_author, comment_date FROM {$wpdb->comments}" );

		foreach ( $posts as $item ) {
			$exists_key = sha1( $item->comment_author . ':' . $item->comment_date );
			$this->exists['comment'][ $exists_key ] = $item->comment_ID;
		}
	}

	/**
	 * Does the comment exist?
	 *
	 * @param array $data Comment data to check against.
	 * @return int|bool Existing comment ID if it exists, false otherwise.
	 */
	protected function comment_exists( $data ) {
		$exists_key = sha1( $data['comment_author'] . ':' . $data['comment_date'] );

		// Constant-time lookup if we prefilled
		if ( $this->options['prefill_existing_comments'] ) {
			return isset( $this->exists['comment'][ $exists_key ] ) ? $this->exists['comment'][ $exists_key ] : false;
		}

		// No prefilling, but might have already handled it
		if ( isset( $this->exists['comment'][ $exists_key ] ) ) {
			return $this->exists['comment'][ $exists_key ];
		}

		// Still nothing, try comment_exists, and cache it
		$exists = comment_exists( $data['comment_author'], $data['comment_date'] );
		$this->exists['comment'][ $exists_key ] = $exists;

		return $exists;
	}

	/**
	 * Mark the comment as existing.
	 *
	 * @param array $data Comment data to mark as existing.
	 * @param int $comment_id Comment ID.
	 */
	protected function mark_comment_exists( $data, $comment_id ) {
		$exists_key = sha1( $data['comment_author'] . ':' . $data['comment_date'] );
		$this->exists['comment'][ $exists_key ] = $comment_id;
	}

	/**
	 * Prefill existing term data.
	 *
	 * @see self::prefill_existing_posts() for justification of why this exists.
	 */
	protected function prefill_existing_terms() {
		global $wpdb;
		$query = "SELECT t.term_id, tt.taxonomy, t.slug FROM {$wpdb->terms} AS t";
		$query .= " JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id";
		$terms = $wpdb->get_results( $query );

		foreach ( $terms as $item ) {
			$exists_key = sha1( $item->taxonomy . ':' . $item->slug );
			$this->exists['term'][ $exists_key ] = $item->term_id;
		}
	}

	/**
	 * Does the term exist?
	 *
	 * @param array $data Term data to check against.
	 * @return int|bool Existing term ID if it exists, false otherwise.
	 */
	protected function term_exists( $data ) {
		$exists_key = sha1( $data['taxonomy'] . ':' . $data['slug'] );

		// Constant-time lookup if we prefilled
		if ( $this->options['prefill_existing_terms'] ) {
			return isset( $this->exists['term'][ $exists_key ] ) ? $this->exists['term'][ $exists_key ] : false;
		}

		// No prefilling, but might have already handled it
		if ( isset( $this->exists['term'][ $exists_key ] ) ) {
			return $this->exists['term'][ $exists_key ];
		}

		// Still nothing, try comment_exists, and cache it
		$exists = term_exists( $data['slug'], $data['taxonomy'] );
		if ( is_array( $exists ) ) {
			$exists = $exists['term_id'];
		}

		$this->exists['term'][ $exists_key ] = $exists;

		return $exists;
	}

	/**
	 * Mark the term as existing.
	 *
	 * @param array $data Term data to mark as existing.
	 * @param int $term_id Term ID.
	 */
	protected function mark_term_exists( $data, $term_id ) {
		$exists_key = sha1( $data['taxonomy'] . ':' . $data['slug'] );
		$this->exists['term'][ $exists_key ] = $term_id;
	}
}

} // class_exists( 'WP_Importer' )




/**
 * Export / Import
 */

if ( ! function_exists( 'bt_export_import' ) ) {
	function bt_export_import() {
	 
		/**
		 * Export
		 */
		 
		 if ( ! function_exists( 'bt_export' ) ) {
			function bt_export() {

				do_action( 'boldthemes_export_start' );
				
				$export = array();
				
				// home page / blog page
				
				$export[ 'show_on_front' ] = get_option( 'show_on_front' );
				
				$page_on_front_id = get_option( 'page_on_front' );
				$page_for_posts_id = get_option( 'page_for_posts' );

				if ( $page_on_front_id > 0 ) {
					$page_on_front = get_post( $page_on_front_id );
					$export[ 'page_on_front' ] = $page_on_front->post_name;
				}
				if ( $page_for_posts_id > 0 ) {
					$page_for_posts = get_post( $page_for_posts_id );
					$export[ 'page_for_posts' ] = $page_for_posts->post_name;
				}
				
				// widgets
				
				global $wp_registered_widgets;
				$sidebars_widgets = get_option( 'sidebars_widgets' );
				
				$export[ 'sidebars_widgets' ] = $sidebars_widgets;
				
				foreach ( $sidebars_widgets as $sidebar ) {
					if ( is_array( $sidebar ) ) {
						foreach ( $sidebar as $id ) {
							$callback = $wp_registered_widgets[ $id ]['callback'];
							$option_name = $callback[0]->option_name;
							$export[ $option_name ] = get_option( $option_name );
						}
					}
				}
				
				// permalink
				
				$export[ 'permalink_structure' ] = get_option( 'permalink_structure' );				
				
				// customize
				
				$export[ BoldThemesFramework::$pfx . '_theme_options' ] = get_option( BoldThemesFramework::$pfx . '_theme_options' );
				
				// menu locations
				
				$theme_slug = get_option( 'stylesheet' );
				
				$locations = get_nav_menu_locations();
				foreach ( $locations as $location => $menu_id ) {
					$menu_object = wp_get_nav_menu_object( $menu_id );
					if ( is_object( $menu_object ) ) {
						$locations[ $location ] = $menu_object->slug;
					} else {
						$locations[ $location ] = null;
					}
				}
				$export[ 'nav_menu_locations' ] = $locations;

				// category meta

				$cat_meta = array();
	
				$all_cat = get_categories();
				foreach ( $all_cat as $cat ) {
					$term_meta = get_term_meta( $cat->term_id );
					if ( $term_meta ) {
						$cat_meta[ $cat->slug ] = $term_meta;
					}
				}
				
				$export[ 'cat_meta' ] = $cat_meta;
				
				// product category meta
				
				$product_cat_meta = array();
	
				$all_cat = get_terms( array(
					'taxonomy' => 'product_cat'
				) );
				foreach ( $all_cat as $cat ) {
					$term_meta = get_term_meta( $cat->term_id );
					if ( $term_meta ) {
						$product_cat_meta[ $cat->slug ] = $term_meta;
					}
				}

				$export[ 'product_cat_meta' ] = $product_cat_meta;
				
				// BB custom CSS
				
				$bt_bb_custom_css = get_option( 'bt_bb_custom_css' );
				if ( $bt_bb_custom_css ) {
					$export[ 'bt_bb_custom_css' ] = $bt_bb_custom_css;
				}
				
				// Customizer Additional CSS
				
				$bt_bb_ccustom_css = wp_get_custom_css();
				if ( $bt_bb_ccustom_css ) {
					$export[ 'bt_bb_ccustom_css' ] = $bt_bb_ccustom_css;
				}
				
				// output
				
				echo "\t" . '<bt_data>' . base64_encode( serialize( $export ) ) . '</bt_data>';

			}
			add_action( 'rss2_head', 'bt_export' );
		}

		/**
		 * Import start
		 */
		 
		/*if ( ! function_exists( 'bt_import_start' ) ) {
			function bt_import_start() {
				$file = get_attached_file( (int) $_POST['import_id'] );
				global $bt_import_data;
				$bt_import_data = file_get_contents( $file );
			}
			add_action( 'import_start', 'bt_import_start' );
		}*/
		
		
		/**
		 * Import end
		 */
		 
		if ( ! function_exists( 'bt_import_end' ) ) {
			function bt_import_end() {

				global $bt_import_data;
				
				preg_match( '/<bt_data>(.*?)<\/bt_data>/', $bt_import_data, $match );
		
				if ( $match ) {
					$data_arr = unserialize( base64_decode( $match[1] ) );
	
					foreach ( $data_arr as $key => $value ) {
						if ( $key != 'page_on_front' && $key != 'page_for_posts' && $key != BoldThemesFramework::$pfx . '_theme_options' && $key != 'nav_menu_locations' ) {
							update_option( $key, $value );
						}
					}
					
					global $wpdb;
					if ( isset( $data_arr['page_on_front'] ) ) {
						$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_type = 'page' AND post_name LIKE %s", $wpdb->esc_like( $data_arr['page_on_front'] ) . '%'  ) );
						update_option( 'page_on_front', $results[0]->ID );
					}
					
					if ( isset( $data_arr['page_for_posts'] ) ) {
						$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_type = 'page' AND post_name LIKE %s", $wpdb->esc_like( $data_arr['page_for_posts'] ) . '%' ) );
						update_option( 'page_for_posts', $results[0]->ID );
					}
					
					if ( isset( $data_arr[ BoldThemesFramework::$pfx . '_theme_options' ] ) ) {
						$arr = $data_arr[ BoldThemesFramework::$pfx . '_theme_options' ];
						foreach ( $arr as $key => $value ) {
							$ext = substr( $value, -4 );
							if ( $ext == '.jpg' || $ext == '.jpeg' || $ext == '.png' || $ext == 'apng' || $ext == '.gif' || $ext == '.bmp' || $ext == '.svg' ) {
								$name = basename( $value );
								$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE guid LIKE %s", '%' . $wpdb->esc_like( $name ) ) );
								if ( isset( $results[0] ) ) {
									$arr[ $key ] = wp_get_attachment_url( $results[0]->ID );
								}
							}
						}
						update_option( BoldThemesFramework::$pfx . '_theme_options', $arr );
					}
					
					if ( isset( $data_arr['nav_menu_locations'] ) ) {
						$locations = $data_arr['nav_menu_locations'];
						foreach ( $locations as $location => $menu_slug ) {
							$menu_object = wp_get_nav_menu_object( $menu_slug );
							if ( is_object( $menu_object ) ) {
								$locations[ $location ] = $menu_object->term_id;
							}
						}			
						set_theme_mod( 'nav_menu_locations', $locations );
					}

					if ( isset( $data_arr['cat_meta'] ) ) {
						$cat_meta = $data_arr['cat_meta'];
						foreach ( $cat_meta as $cat_slug => $cat_meta_item ) {
							$term = get_term_by( 'slug', $cat_slug, 'category' );
							foreach( $cat_meta_item as $cat_meta_key => $cat_meta_val ) {
								update_term_meta( $term->term_id, $cat_meta_key, $cat_meta_val[0] );
							}
						}
					}
					
					if ( isset( $data_arr['product_cat_meta'] ) ) {
						$product_cat_meta = $data_arr['product_cat_meta'];
						foreach ( $product_cat_meta as $cat_slug => $cat_meta_item ) {
							$term = get_term_by( 'slug', $cat_slug, 'product_cat' );
							if ( $term ) {
								foreach( $cat_meta_item as $cat_meta_key => $cat_meta_val ) {
									update_term_meta( $term->term_id, $cat_meta_key, $cat_meta_val[0] );
								}
							}
						}
					}					
					
					if ( isset( $data_arr['bt_bb_custom_css'] ) ) {
						update_option( 'bt_bb_custom_css', $data_arr['bt_bb_custom_css'] );
					}
					
					if ( isset( $data_arr['bt_bb_ccustom_css'] ) ) {
						wp_update_custom_css_post( $data_arr['bt_bb_ccustom_css'] );
					}
								
				}
				
				if ( class_exists( 'woocommerce' ) ) {
					if ( function_exists( 'boldthemes_get_id_by_slug' ) ) {
						$shop_page_slug = boldthemes_get_id_by_slug( 'shop' );
						if ( $shop_page_slug ) {
							update_option( 'woocommerce_shop_page_id', $shop_page_slug );
						}
					}
				}

			}
			add_action( 'import_end', 'bt_import_end' );
		}		
		
	}
	add_action( 'admin_init', 'bt_export_import' );
}