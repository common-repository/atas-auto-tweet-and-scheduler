<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ATASWP_Importer
{
	
	/**
	 * Display all post custom posts.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function display_posts() {
		
		// store validation results in array
		$validation = array();
		
        global $wpdb, $post;	
		
		// get form data
		//$formData = $_POST['formData']; // array
		$formData  = isset( $_POST['formData'] ) ? $_POST['formData'] : ''; // array
		
		if ( empty( $formData ) ) {
		    echo ''; // should return empty
		} else {
			
			$categories = isset($formData)?array_map('sanitize_text_field',$formData):array();
			
			/*
			echo '<pre>';
			print_r( $categories );
			echo '</pre>';
			exit;
			*/
			
			// import posts from categories
			if ( !empty($categories) ) {
				foreach ( $categories as $data ) {
					//echo $data . '<br>';
					$import = explode(',',$data);
					
					$post_type = trim(str_replace('post_type:', '', $import['0']));
					$taxonomy  = trim(str_replace('taxonomy:', '', $import['1']));
					$term_id   = trim(str_replace('term_id:', '', $import['2']));
					
					$term = get_term( $term_id, $taxonomy );
					$term_name = $term->name;
					
				?>
				<div class="ataswp-is-blog-post-cat-checked display-block ataswp-check-blog-post-cat-<?php echo $term_id; ?>" style="line-height:2em;">
					<input class="ataswp-get-blog-post-category" type="checkbox" value="<?php echo $term_id; ?>" id="<?php echo $term_id; ?>" name="blog_post_cat[]" />
					<span style="font-weight:bold; line-height:1.6em; font-size:13px;"><?php echo esc_attr( $term_name . ' (' . $taxonomy . ') ' ); ?> </span>
				</div>
				<?php 
					
					//echo 'Post Type: ' . $post_type . ' Taxonomy: ' . $taxonomy . ' Term ID: ' . $term_id . '<br>';
					
					// get posts
					$args = array(
					'post_status' => 'publish',
					'order' => 'asc', 
					'orderby' => 'post_title', 
					'posts_per_page' => -1,
					'post_type' => $post_type,
					'tax_query' => array(
						array(
						'taxonomy' => $taxonomy,
						'field' => 'term_id',
						'terms' => $term_id
						 )
					  )
					);
					$query = new WP_Query( $args );
					
					$posts = $query->posts;
					
					foreach($posts as $post) {
						// import posts, set up meta data
						//echo $post->ID . '<br>';
						$post_id  = $post->ID;
						
						$ataswp_scheduler   = get_post_meta($post_id,'_ataswp_scheduler', true);
						
						// list only if not already added
						if ( $ataswp_scheduler != '1' ) {
						
					?>
					<div class="ataswp-is-blog-post-checked display-block ataswp-check-blog-post-<?php echo $term_id; ?>" style="line-height:2em;">
						<input class="ataswp-get-blog-post" type="checkbox" value="<?php echo $post_id; ?>" id="<?php echo $post_id; ?>" name="post_ids[]" />
						<span style="line-height:1.6em;"><?php echo $post->post_title; ?> </span>
					</div>
					<?php 
					
						}
	
					}
					
					// clean up after the query and pagination
					wp_reset_postdata(); 
	
				}
			} else {
				echo '';
			}
		}
		
		/*
		echo '<pre>';
		print_r( $post_id );
		echo '</pre>';
		*/
		
        exit; // don't forget to exit!	
		
	}
	
	/**
	 * Display all pages.
	 *
	 * @access public static
	 * @since  1.0.0
	 * @return void
	 */
	public static function display_pages() {
		?>
		<div class="ataswp-is-page-cat-checked display-block ataswp-check-page-cat" style="line-height:2em;">
			<input class="ataswp-get-page-category" type="checkbox" value="page" id="page" name="page_cat[]" />
			<span style="font-weight:bold; line-height:1.6em; font-size:13px;"><?php echo esc_attr( 'Pages' ); ?> </span>
		</div>
		<?php 
	    global $wpdb, $post;	
		
		// list all pages
		$args = array(
			'sort_order' => 'asc',
			'sort_column' => 'post_title',
			'hierarchical' => 1,
			'exclude' => '',
			'include' => '',
			'meta_key' => '',
			'meta_value' => '',
			'authors' => '',
			'child_of' => 0,
			'parent' => -1,
			'exclude_tree' => '',
			'number' => '',
			'offset' => 0,
			'post_type' => 'page',
			'post_status' => 'publish'
		); 
		$pages = get_pages($args);
		
		foreach ($pages as $page) {
			$page_id = $page->ID;
			$title   = $page->post_title; 
		
		?>
		<div class="ataswp-is-page-checked display-block ataswp-check-page-<?php echo 'page'; ?>" style="line-height:2em;">
			<input class="ataswp-get-page" type="checkbox" value="<?php echo $page_id; ?>" id="<?php echo $page_id; ?>" name="page_ids[]" />
			<span style="line-height:1.6em;"><?php echo $page->post_title; ?> </span>
		</div>
		<?php 
		
		}
		
	}
	
	/**
	 * Run posts importer.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function run_importer() {
		
		/* Start session and load lib */
		//session_start();
		
        global $wpdb, $post;	
		
		// get form data
		//$formData = $_POST['formData']; // array
		$formData  = isset( $_POST['formData'] ) ? $_POST['formData'] : ''; // array
		
		// store validation results in array
		$validation = array();
		
		if ( empty( $formData ) )
		return;
		
		// parse string
		parse_str($formData, $postdata);
		
		// Add nonce for security and authentication.
		$nonce_action = 'ataswp_run_importer_form_nonce';
       
		// Check if a nonce is set.
		if ( ! isset( $postdata['ataswp-run-importer-form-nonce'] ) )
			return;
		
		// Check if a nonce is valid.
		if ( wp_verify_nonce( $postdata['ataswp-run-importer-form-nonce'], $nonce_action ) ) {
			
			$post_ids         = isset($postdata['post_ids'])?array_map('sanitize_text_field',$postdata['post_ids']):array();
			$start_date       = isset($postdata['start_date'] ) ? sanitize_text_field( $postdata['start_date'] ) : '';
			$end_date         = isset($postdata['end_date'] ) ? sanitize_text_field( $postdata['end_date'] ) : '';
			$intervals        = isset($postdata['intervals'])?array_map('sanitize_text_field',$postdata['intervals']):array();
			$hours            = isset($postdata['hours'])?array_map('sanitize_text_field',$postdata['hours']):array();
			$minutes          = isset($postdata['minutes'])?array_map('sanitize_text_field',$postdata['minutes']):array();
			
			$page_ids         = isset($postdata['page_ids'])?array_map('sanitize_text_field',$postdata['page_ids']):array();
			
			$post_and_page_ids = array_merge($post_ids, $page_ids);
            
			if ( empty( $post_and_page_ids ) ) {

				// error message
				$validation[] = __('Please select at least one post.', 'ataswp_lang');
				// validation
				$print = ATASWP_Admin_Core::displayAjaxFormsValidationResult($validation, $type='info');
				echo json_encode(array('success'=>false, 'message'=>$print ));

			} elseif ( empty( $intervals ) ) {
				
				// error message
				$validation[] = __('Please select at least one interval.', 'ataswp_lang');
				// validation
				$print = ATASWP_Admin_Core::displayAjaxFormsValidationResult($validation, $type='info');
				echo json_encode(array('success'=>false, 'message'=>$print ));
				
			} elseif ( empty( $hours ) ) {

				// error message
				$validation[] = __('Please add time.', 'ataswp_lang');
				// validation
				$print = ATASWP_Admin_Core::displayAjaxFormsValidationResult($validation, $type='info');
				echo json_encode(array('success'=>false, 'message'=>$print ));

			} else {
				
				// create times array
				$index = 0; // default
				foreach($hours as $hour) {
					
				  if ( ! empty( $hours[$index] ) && ! empty( $minutes[$index] ) ) {
					  
					  $time = $hours[$index] . ':' . $minutes[$index];
	
					  $times[] = $time;
				  
				  }
				  
				  $index++; // should be at the end of the loop
				}
				
				$timezone_date_time = ATASWP_Scheduler::scheduler_date_time();
				$datetime_str       =  strtotime($timezone_date_time);
				$time_zone_date     = date('Y-m-d', $datetime_str);
				
				$data = array(
					"post_and_page_ids" => $post_and_page_ids, // array
					"start_date"	    => $start_date,
					"end_date"	        => $end_date,
					"intervals"		    => $intervals, // array
					"times"	            => $times, // array
					"next_run_date"	    => $time_zone_date
				);
				
				$post_counter = '0';
				
				#### success ###
				
				// run importer
				foreach($post_and_page_ids as $post_id) {
					ATASWP_Importer::import_posts_and_pages( $post_id, $data );
					$post_counter++;
				}
				
				
				// counter
				$count_in_total = $post_counter;
				
				// return json
				$validation[] = $count_in_total . __(' posts has been successfully imported.', 'ataswp_lang') . ' ';
				$print = ATASWP_Admin_Core::displayAjaxFormsValidationResult($validation, $type='success');
				echo json_encode(array('success'=>true, 'message'=>$print ));
				
				/*
				echo '<pre>';
				print_r( $data );
				echo '</pre>';
				*/
			}
			
		
		} else {
			// error message
			$validation[] = __('Form Validation failed!', 'ataswp_lang');
			// validation
			$print = ATASWP_Admin_Core::displayAjaxFormsValidationResult($validation, $type='error');
			echo json_encode(array('success'=>false, 'message'=>$print ));
		}
		
        exit; // don't forget to exit!	
		
	}
	
	/**
	 * Import posts and pages. Setup metas. 
	 * 
	 * @since  1.0.0
	 * @param int $post_id
	 * @param array $data
	 * @return array $data_arr
	 */
	public static function import_posts_and_pages( $post_id, $data ) {
		
		if ( empty( $post_id ) && empty( $data ) )
		return;
		
		$data_arr = array(
			"post_and_page_ids" => $data['post_and_page_ids'], // array
			"start_date"	    => $data['start_date'],
			"end_date"	        => $data['end_date'],
			"intervals"		    => $data['intervals'], // array
			"times"	            => $data['times'], // array
			"next_run_date"	    => $data['next_run_date']
		);
		
		$ataswp_scheduler   = get_post_meta($post_id,'_ataswp_scheduler', true);
		
		// add only if not active
		if ( $ataswp_scheduler != '1' ) {
			
			$timezone_date_time = ATASWP_Scheduler::scheduler_date_time(); // time zone date time
			
			$intervals = json_encode($data['intervals']); // json encode before save
			$times     = json_encode($data['times']); // json encode before save
			
			update_post_meta($post_id,'_ataswp_scheduler', '1'); // activate
			update_post_meta($post_id,'_ataswp_scheduler_counter', '0');
			update_post_meta($post_id,'_ataswp_scheduler_intervals', $intervals);
			update_post_meta($post_id,'_ataswp_scheduler_times', $times);
			update_post_meta($post_id,'_ataswp_scheduler_start_date', $data['start_date']);
			update_post_meta($post_id,'_ataswp_scheduler_end_date', $data['end_date']);
			
			update_post_meta($post_id,'_ataswp_scheduler_next_run_date', $data['next_run_date']);
			
			return $data_arr;
		}
		
	}
	
		
	
}

?>