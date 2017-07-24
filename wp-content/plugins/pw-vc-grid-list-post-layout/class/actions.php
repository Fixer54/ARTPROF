<?php
	add_action('wp_ajax_pw_pl_fetch_taxonomy', 'pw_pl_fetch_taxonomy');
	add_action('wp_ajax_nopriv_pw_pl_fetch_taxonomy', 'pw_pl_fetch_taxonomy');
	function pw_pl_fetch_taxonomy() {
		$post_name=$_POST['post_name'];
		$all_tax=get_object_taxonomies( $post_name );

		$param_line='';
		$current_value=$_POST['current_value'];
		foreach ( $all_tax as $tax ) {
			$taxonomy=get_taxonomy($tax);	
			$values=$tax;
			$label=$taxonomy->label;

			$checked='';
			if ( in_array($values, explode(",", $current_value)) ) $checked = ' checked="checked"';
			
			$param_line .= ' <input style="width: auto !important;" value="' . $values . '" class="pw_taxonomy_checkbox" name="pw_taxonomy_checkbox" type="checkbox" '.$checked.'> ' . $label;
			
		}
		
		$param_line.='<script type="text/javascript">
							jQuery(document).ready(function(){				
											
								jQuery(".pw_taxonomy_checkbox").click(function(){
									var selected = [];
									jQuery("#taxonomy_fetch input:checked").each(function() {
										selected.push(jQuery(this).val());
									});
									jQuery("#pw_taxonomy").val(selected.join());
								});
							});
						</script>	
						';
		
		echo $param_line;
		exit(0);
	}
	
	add_action('wp_ajax_pw_pl_list_load_more', 'pw_pl_list_load_more');
	add_action('wp_ajax_nopriv_pw_pl_list_load_more', 'pw_pl_list_load_more');
	function pw_pl_list_load_more()
	{
		global $VCExtendAddonClass_GRID,$output;
		extract ( $_POST );	
		
		$img_id=array();
		$img_counter = 0;
		$output = '';
		$post_counter = 0;
		$pw_grid_id = $rand_id = $pw_mixitup_grid_id;
		
		$query=$pw_query;
		$query=explode('|',$query);
		
		$query_posts_per_page=10;
		$query_post_type='post';
		$query_meta_key='';
		$query_orderby='date';
		$query_order='ASC';
		$order_by='';
		$order_value='';
		
		$query_posts_per_page=10;
		$query_post_type='post';
		$query_meta_key='';
		$query_orderby='date';
		$query_order='ASC';
		
		$query_by_id='';
		$query_by_id_not_in='';
		$query_by_id_in='';
		
		$query_categories='';
		$query_cat_not_in='';
		$query_cat_in='';
		
		$query_tags='';
		$query_tags_in='';
		$query_tags_not_in='';
		
		$query_author='';
		$query_author_in='';
		$query_author_not_in='';
		
		$query_tax_query='';
		
		$z_index = 9999;
		
		foreach($query as $query_part)
		{
			$q_part=explode(':',$query_part);
			switch($q_part[0])
			{
				case 'post_type':
					$query_post_type=explode(',',$q_part[1]);
				break;
				
				case 'size':
					$query_posts_per_page=($q_part[1]=='All' ? -1:$q_part[1]);
				break;
				
				case 'order_by':
					
					$query_meta_key='';
					$query_orderby='';
					
					$public_orders_array=array('ID','date','author','title','modified','rand','comment_count','menu_order');
					if(in_array($q_part[1],$public_orders_array))
					{
						$query_orderby=$q_part[1];
					}else
					{
						$query_meta_key=$q_part[1];
						$query_orderby='meta_value_num';
					}
					$order_by=$q_part[1];
					
				break;
				
				case 'order':
					$query_order=$q_part[1];
				break;
				
				case 'by_id':
						$query_by_id=explode(',',$q_part[1]);
						$query_by_id_not_in=array();
						$query_by_id_in=array();
						foreach($query_by_id as $ids)
						{
							if($ids<0)
							{
								$query_by_id_not_in[]=abs($ids);
							}else
							{
								$query_by_id_in[]=$ids;
							}
						}
					break;
				
				case 'categories':
					$query_categories=explode(',',$q_part[1]);
					$query_cat_not_in=array();
					$query_cat_in=array();
					foreach($query_categories as $cat)
					{
						if($cat<0)
						{
							$query_cat_not_in[]=abs($cat);
						}else
						{
							$query_cat_in[]=$cat;
						}
					}
				break;
				
				case 'tags':
					$query_tags=explode(',',$q_part[1]);
					$query_tags_not_in=array();
					$query_tags_in=array();
					foreach($query_tags as $tags)
					{
						if($tags<0)
						{
							$query_tags_not_in[]=abs($tags);
						}else
						{
							$query_tags_in[]=$tags;
						}
					}
				break;
				
				case 'authors':
					$query_author=explode(',',$q_part[1]);
					$query_author_not_in=array();
					$query_author_in=array();
					foreach($query_author as $author)
					{
						if($tags<0)
						{
							$query_author_not_in[]=abs($author);
						}else
						{
							$query_author_in[]=$author;
						}
					}
					
				break;

				case 'tax_query':
					$all_tax=get_object_taxonomies( $query_post_type );

					$tax_query=array();
					$query_tax_query=array('relation' => 'OR');
					foreach ( $all_tax as $tax ) {
						$values=$tax;
						$query_taxs_in=array();
						$query_taxs_not_in=array();
						
						$query_taxs=explode(',',$q_part[1]);
						foreach($query_taxs as $taxs)
						{
							if($taxs<0)
							{
								$query_taxs_not_in[]=abs($taxs);
							}else
							{
								$query_taxs_in[]=$taxs;
							}
						}

						if(count($query_taxs_not_in)>0)
						{
							$query_tax_query[]=array(
								'taxonomy' => $tax,
								'field'    => 'id',
								'terms'    => $query_taxs_not_in,
								'operator' => 'NOT IN',
							);
						}else if(count($query_taxs_in)>0)
						{
							$query_tax_query[]=array(
								'taxonomy' => $tax,
								'field'    => 'id',
								'terms'    => $query_taxs_in,
								'operator' => 'IN',
							);
						}
						
						
						
						
						//break;
					}	
					
					
					/*$query_tax_query=array(
						'relation' => 'AND',
						$tax_query,
					);*/
					//die(print_r($query_tax_query));
				break;
			}
		}
		
		if(($pw_grid_page_navigation=='infinite_scroll' || $pw_grid_page_navigation=='show_more_btn' ) && $pw_action_type=='swap_view_type')
		{
			$query_posts_per_page=$paged*$query_posts_per_page;
			$paged=1;
		}
		
		if($pw_grid_hide_recentpost=='yes'){
			$query_final=array('post_type' => $query_post_type,
					'post_status'=>'publish',
					'posts_per_page'=>$pw_grid_num_hide_recentpost,
					'meta_key' => $query_meta_key,
					'orderby' => 'date',
					'order' => 'DESC',
					'paged'=>1,
					
					'category__in'=>$query_cat_in,
					'category__not_in'=>$query_cat_not_in,
					
					'tag__in'=>$query_tags_in,
					'tag__not_in'=>$query_tags_not_in,
					
					'author__in'=>$query_author_in,
					'author__not_in'=>$query_author_not_in,
					
					'tax_query'=>$query_tax_query
				 );
			$my_query = new WP_Query($query_final);	
			$recent_posts=array();
			while ( $my_query->have_posts() ) {
				$my_query->the_post();
				$recent_posts[]=get_the_ID();
			}
			
			if(is_array($query_by_id_not_in))
				$query_by_id_not_in=array_merge($query_by_id_not_in,$recent_posts);
			else	
				$query_by_id_not_in=$recent_posts;
		}
		
		
		//$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$query_final=array('post_type' => $query_post_type,
						'post_status'=>'publish',
						'posts_per_page'=>$query_posts_per_page,
						'meta_key' => $query_meta_key,
						'orderby' => $query_orderby,
						'order' => $query_order,
						'paged'=>$paged,
						
						'post__in'=>$query_by_id_in,
						'post__not_in'=>$query_by_id_not_in,
						
						'category__in'=>$query_cat_in,
						'category__not_in'=>$query_cat_not_in,
						
						'tag__in'=>$query_tags_in,
						'tag__not_in'=>$query_tags_not_in,
						
						'author__in'=>$query_author_in,
						'author__not_in'=>$query_author_not_in,
						
						'tax_query'=>$query_tax_query
					 );
		$my_query = new WP_Query($query_final);	

		$pw_list_id = $rand_id = $pw_mixitup_grid_id;
		while ( $my_query->have_posts() ) {
			
				$wp_comment_count = 0;
				$my_query->the_post(); // Get post from query
				$post = new stdClass(); // Creating post object.
				$post->id = get_the_ID();
				$post->link = get_permalink($post->id);
				$img_id[]=get_post_meta( $post->id , '_thumbnail_id' ,true );
				
				$post_thumbnail = wpb_getImageBySize(array( 'post_id' => $post->id, 'thumb_size' => $pw_image_thumb_size ));
				$current_img_large = $post_thumbnail['thumbnail'];
				$current_img_full = wp_get_attachment_image_src( $img_id[$img_counter++] , 'full' );
				
				$post_type = get_post_type( $post->id );
				$post_taxonomies = get_object_taxonomies($post_type);
				$tax_counter = 0;
					
				
				if ($pw_list_type=='pl-list-t1'){ 
                $output .= 
				'<div class="pl-blogcnt pl-listlayout '. $pw_list_type .' pl_list_id_'.$pw_list_id.'">
					<div class="pl-col-md-6 pl-col-xs-12">
						<div class="pl-itemcnt">
							<div class="pl-thumbcnt '. $pw_image_effect .'">
                                <a href="'. $post->link .'" target="'. $pw_link_target  .'" >'.$current_img_large.'</a>';
								if(isset($pw_show_overlay) && $pw_show_overlay=='no')
								{
									$output.='
									<div class="pl-overally fadein-eff">
										<div class="pl-icon-cnt '. $pw_icon_effect.' '.$pw_icon_type.'">';
											if (isset($pw_show_zoom_icon) && ($pw_show_zoom_icon=='no')){
												$output .= '<a href="'. $current_img_full[0] .'" class="pl-zoom-icon example-image" data-lightbox="image-set"></a>';
											}
											if (isset($pw_show_link_icon) && ($pw_show_link_icon=='no')){
												$output .= '<a href="'. $post->link .'" class="pl-link-icon" target="'. $pw_link_target  .'" ></a>';
											}
										$output .= '</div><!--pl-icon-cnt -->
									</div>';
								}
							$output .= '	
							</div>
						</div>		
					</div>
					<div class="pl-col-md-6 pl-col-xs-12">
							<div class="pl-detailcnt">
								<h4 class="pl-title left-txt">';
									if (isset($pw_hide_date) && ($pw_hide_date=='off')){
										$output .= '<span class="pl-date">'. get_the_date($pw_date_format).'</span>';
									}
									$output .= '<a href="'. $post->link .'" target="'. $pw_link_target  .'">'. get_the_title() .'</a></h4>';
								
								//if($pw_grid_author=='no')
								//{
									$output.='	
										<div class="pl-postmeta">
										<i class="fa fa-pencil"></i>'.get_the_author().'</div>';
								//}
								
								//if($pw_grid_tags=='no')
								//{
									$output .= pw_get_post_meta($post->id , $post_taxonomies);
								//}
								
								
								
								if($pw_grid_text_type=='full_text')
								{
									$output .='<p class="pl-text left-txt">'. $VCExtendAddonClass_GRID->excerpt(get_the_content(),$pw_excerpt_length,$pw_grid_text_type).' </p>';
								}else
								{
									$output .='<p class="pl-text left-txt">'. $VCExtendAddonClass_GRID->excerpt(get_the_excerpt(),$pw_excerpt_length,$pw_grid_text_type).' </p>';
								}
								
								//if($pw_grid_show_num_comment=='no')
								//{
									$output .= '
								<div class="pl-postcomment"><a href="'. get_comments_link() .'"><i class="fa fa-comments"></i>'.get_comments_number( '0', '1', '% responses' ) .'</a></div>';
								//}
								$output .= '
								<a class="'.$pw_readmore_type.'" href="'. $post->link .'" target="'. $pw_link_target .'" >'.$pw_readmore_translate.'<i class="fa fa-angle-right"></i></a>
							</div>
					</div>
				</div>';
                }//end if 
                else if ($pw_list_type=='pl-list-t2'){//print_r($pw_teasr_layout_img); 
				 	$output .= '<div class="pl-blogcnt  pl-listlayout '. $pw_list_type .' pl_list_id_'.$pw_list_id.'">';
				 	$post_orders = explode(',',$pw_teasr_layout_img);
					foreach ( $post_orders as $order ){
						$order_type = explode('|',$order);
						switch ($order_type[0]){
						case 'image':
						
									$thumbnail=$current_img_large;
									if(isset($order_type[1]) && $order_type[1]=='link_post')
									{
										$thumbnail='<a href="'. $post->link .'" target="'. $pw_link_target  .'" >'.$thumbnail.'</a>';
									}
									
									$output .= '<div class="pl-itemcnt">
											<div class="pl-thumbcnt '. $pw_image_effect .'">
												'.$thumbnail;
												
											if(isset($pw_show_overlay) && $pw_show_overlay=='no')
											{
												if(isset($order_type[1]) && $order_type[1]=='link_post')
												{
													$output.='<a href="'. $post->link .'" target="'. $pw_link_target  .'" >';
												}
												
												$output.='	
												<div class="pl-overally fadein-eff">
													<div class="pl-icon-cnt '. $pw_icon_effect.' '.$pw_icon_type.'">';
														if (isset($pw_show_zoom_icon) && ($pw_show_zoom_icon=='no')){
															$output .= '<a href="'. $current_img_full[0] .'" class="pl-zoom-icon example-image" data-lightbox="image-set"></a>';
														}
														if (isset($pw_show_link_icon) && ($pw_show_link_icon=='no')){
															$output .= '<a href="'. $post->link .'" class="pl-link-icon" target="'. $pw_link_target  .'" ></a>';
														}
													$output .= '</div><!--pl-icon-cnt -->
												</div><!-- pl-overally -->';
												
												if(isset($order_type[1]) && $order_type[1]=='link_post')
												{
													$output.='</a>';
												}
											}
										$output .= '	
										</div><!-- pl-thumbcnt -->
									</div><!--pl-itemcnt -->';
									 break;
						case 'title':
									$output .= '<div class="pl-detailcnt">
												<h4 class="pl-title left-txt">';
														if (isset($pw_hide_date) && ($pw_hide_date=='off')){
															$output .= '<span class="pl-date">'. get_the_date($pw_date_format).'</span>';
														}
														if(isset($order_type[1]) && $order_type[1]=='link_post')
														{
															$output .= '<a href="'. $post->link .'" target="'. $pw_link_target  .'">'. get_the_title().'</a>';
														}else{
															$output .=  get_the_title();
														}
														
														$output .= '</h4>
											</div>';
											
										//if($pw_grid_author=='no')
										//{
											$output.='	
											<div class="pl-detailcnt">
												<div class="pl-postmeta">
												<i class="fa fa-pencil"></i>'.get_the_author().'</div>';
										//}
											
										//if($pw_grid_tags=='no')
										//{	
											$output .= pw_get_post_meta($post->id , $post_taxonomies);
											$output .='</div>';
										//}
									break;
						case 'text':
									$content=$VCExtendAddonClass_GRID->excerpt(get_the_excerpt(),$pw_excerpt_length,'excerpt');
									if(isset($order_type[1]) && $order_type[1]=='text')
									{
										$content=$VCExtendAddonClass_GRID->excerpt(get_the_content(),$pw_excerpt_length,'full_text');
									}
								
									$output .= '<div class="pl-detailcnt">
												<p class="pl-text left-txt">'. $content.'</p>';
												//if($pw_grid_show_num_comment=='no')
												//{
													$output .= '
												<div class="pl-postcomment"><a href="'. get_comments_link() .'"><i class="fa fa-comments"></i>'.get_comments_number( '0', '1', '% responses' ).'</a></div>';
												//}
									$output .= '
										  </div>';
									break;
						case 'link':
									$output .= '<div class="pl-detailcnt">
												<a class="'.$pw_readmore_type.'" href="'. $post->link .'" target="'. $pw_link_target .'" >'.$pw_readmore_translate.'<i class="fa fa-angle-right"></i></a>
										  </div>';
									break;
						}//end switch
					}//end foreach
				 
                $output .= '</div>';
			 }//end else 
		}
		wp_reset_query();
		echo $output;
		exit(0);
	}
	
	add_action('wp_ajax_pw_pl_grid_list_swap', 'pw_pl_grid_list_swap' );
	add_action('wp_ajax_nopriv_pw_pl_grid_list_swap', 'pw_pl_grid_list_swap');
	function pw_pl_grid_list_swap() 
	{
		global $VCExtendAddonClass_GRID,$output,$classs;
		extract ( $_POST );
		
		

		$img_id=array();
		$img_counter = 0;
		$output = '';
		$post_counter = 0;
		$pw_grid_id = $rand_id = $pw_mixitup_grid_id;
		
		$query=$pw_query;
		$query=explode('|',$query);
		
		$query_posts_per_page=10;
		$query_post_type='post';
		$query_meta_key='';
		$query_orderby='date';
		$query_order='ASC';
		
		$query_by_id='';
		$query_by_id_not_in='';
		$query_by_id_in='';
		
		$query_categories='';
		$query_cat_not_in='';
		$query_cat_in='';
	
		$query_tags='';
		$query_tags_in='';
		$query_tags_not_in='';
		
		$query_author='';
		$query_author_in='';
		$query_author_not_in='';
		
		$query_tax_query='';
		
		$order_by='';
		$order_value='';
		$z_index = 9999;
				
		
		foreach($query as $query_part)
		{
			$q_part=explode(':',$query_part);
			switch($q_part[0])
			{
				case 'post_type':
					$query_post_type=explode(',',$q_part[1]);
				break;
				
				case 'size':
					$query_posts_per_page=($q_part[1]=='All' ? -1:$q_part[1]);
				break;
				
				case 'order_by':
					
					$query_meta_key='';
					$query_orderby='';
					
					$public_orders_array=array('ID','date','author','title','modified','rand','comment_count','menu_order');
					if(in_array($q_part[1],$public_orders_array))
					{
						$query_orderby=$q_part[1];
					}else
					{
						$query_meta_key=$q_part[1];
						$query_orderby='meta_value_num';
					}
					$order_by=$q_part[1];
					
				break;
				
				case 'order':
					$query_order=$q_part[1];
				break;
				
				case 'by_id':
						$query_by_id=explode(',',$q_part[1]);
						$query_by_id_not_in=array();
						$query_by_id_in=array();
						foreach($query_by_id as $ids)
						{
							if($ids<0)
							{
								$query_by_id_not_in[]=abs($ids);
							}else
							{
								$query_by_id_in[]=$ids;
							}
						}
					break;
				
				case 'categories':
					$query_categories=explode(',',$q_part[1]);
					$query_cat_not_in=array();
					$query_cat_in=array();
					foreach($query_categories as $cat)
					{
						if($cat<0)
						{
							$query_cat_not_in[]=abs($cat);
						}else
						{
							$query_cat_in[]=$cat;
						}
					}
				break;
				
				case 'tags':
					$query_tags=explode(',',$q_part[1]);
					$query_tags_not_in=array();
					$query_tags_in=array();
					foreach($query_tags as $tags)
					{
						if($tags<0)
						{
							$query_tags_not_in[]=abs($tags);
						}else
						{
							$query_tags_in[]=$tags;
						}
					}
				break;
				
				case 'authors':
					$query_author=explode(',',$q_part[1]);
					$query_author_not_in=array();
					$query_author_in=array();
					foreach($query_author as $author)
					{
						if($tags<0)
						{
							$query_author_not_in[]=abs($author);
						}else
						{
							$query_author_in[]=$author;
						}
					}
					
				break;

				case 'tax_query':
					$all_tax=get_object_taxonomies( $query_post_type );

					$tax_query=array();
					$query_tax_query=array('relation' => 'OR');
					foreach ( $all_tax as $tax ) {
						$values=$tax;
						$query_taxs_in=array();
						$query_taxs_not_in=array();
						
						$query_taxs=explode(',',$q_part[1]);
						foreach($query_taxs as $taxs)
						{
							if($taxs<0)
							{
								$query_taxs_not_in[]=abs($taxs);
							}else
							{
								$query_taxs_in[]=$taxs;
							}
						}

						if(count($query_taxs_not_in)>0)
						{
							$query_tax_query[]=array(
								'taxonomy' => $tax,
								'field'    => 'id',
								'terms'    => $query_taxs_not_in,
								'operator' => 'NOT IN',
							);
						}else if(count($query_taxs_in)>0)
						{
							$query_tax_query[]=array(
								'taxonomy' => $tax,
								'field'    => 'id',
								'terms'    => $query_taxs_in,
								'operator' => 'IN',
							);
						}
						
						
						
						
						//break;
					}	
					
					
					/*$query_tax_query=array(
						'relation' => 'AND',
						$tax_query,
					);*/
					//die(print_r($query_tax_query));
				break;
			}
		}
		
		if(($pw_grid_page_navigation=='infinite_scroll' || $pw_grid_page_navigation=='show_more_btn' ) && $pw_action_type=='swap_view_type')
		{
			$query_posts_per_page=$paged*$query_posts_per_page;
			$paged=1;
		}
		
		if($pw_grid_hide_recentpost=='yes'){
			$query_final=array('post_type' => $query_post_type,
					'post_status'=>'publish',
					'posts_per_page'=>$pw_grid_num_hide_recentpost,
					'meta_key' => $query_meta_key,
					'orderby' => 'date',
					'order' => 'DESC',
					'paged'=>1,
					
					'category__in'=>$query_cat_in,
					'category__not_in'=>$query_cat_not_in,
					
					'tag__in'=>$query_tags_in,
					'tag__not_in'=>$query_tags_not_in,
					
					'author__in'=>$query_author_in,
					'author__not_in'=>$query_author_not_in,
					
					'tax_query'=>$query_tax_query
				 );
			$my_query = new WP_Query($query_final);	
			$recent_posts=array();
			while ( $my_query->have_posts() ) {
				$my_query->the_post();
				$recent_posts[]=get_the_ID();
			}
			
			if(is_array($query_by_id_not_in))
				$query_by_id_not_in=array_merge($query_by_id_not_in,$recent_posts);
			else	
				$query_by_id_not_in=$recent_posts;
		}
		
		
		//$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$query_final=array('post_type' => $query_post_type,
						'post_status'=>'publish',
						'posts_per_page'=>$query_posts_per_page,
						'meta_key' => $query_meta_key,
						'orderby' => $query_orderby,
						'order' => $query_order,
						'paged'=>$paged,
						
						'post__in'=>$query_by_id_in,
						'post__not_in'=>$query_by_id_not_in,
						
						'category__in'=>$query_cat_in,
						'category__not_in'=>$query_cat_not_in,
						
						'tag__in'=>$query_tags_in,
						'tag__not_in'=>$query_tags_not_in,
						
						'author__in'=>$query_author_in,
						'author__not_in'=>$query_author_not_in,
						
						'tax_query'=>$query_tax_query
					 );
		//print_r($query_final);
		$my_query = new WP_Query($query_final);
		
		//$my_query=$VCExtendAddonClass_GRID->pw_get_loop($pw_query);
		$pw_list_type='pl-list-t1';
		$pw_list_id = $rand_id = $pw_mixitup_grid_id;

		while ( $my_query->have_posts() ) {
			if ($pw_grid_type=='pl-standard-grid' || $pw_grid_type=='pl-mixitup-grid')
			{
				if($view_type=='list')
				{	
					$wp_comment_count = 0;
					$my_query->the_post(); // Get post from query
					$post = new stdClass(); // Creating post object.
					$post->id = get_the_ID();
					$post->link = get_permalink($post->id);
					
					/////////////FILTER AND ORDER PARAM//////
					$data_filter='';
					if(isset($pw_grid_filter_by) && $pw_grid_filter_by=='yes' && $pw_grid_type=='pl-mixitup-grid')
					{
						$taxs=explode(',',$pw_taxonomy);
						if(is_array($taxs) && count($taxs)>1)
						{
							foreach($taxs as $tax)
							{
								$data_filters=wp_get_post_terms($post->id, $tax, array("fields" => "ids"));
								$data_filter.=implode(' ',$data_filters).' ';
							}
						}else if($pw_taxonomy!='')
						{
							$data_filter=wp_get_post_terms($post->id, $pw_taxonomy, array("fields" => "ids"));
							$data_filter=implode(' ',$data_filter);
						}
					}
					
					if(isset($pw_grid_order_by) && $pw_grid_order_by=='yes' && $pw_grid_type=='pl-mixitup-grid')
					{
						$public_orders_array=array('ID','date','author','title','modified','rand','comment_count','menu_order');
						if(in_array($order_by,$public_orders_array))
						{
							switch($order_by)
							{
								case 'ID':
									$order_value=get_the_id();
								break;
								
								case 'date':
									$order_value=get_the_date('Y/m/d');
								break;
								
								case 'author':
									$order_value=get_the_author();
								break;
								
								case 'title':
									$order_value=get_the_title();
								break;
								
								case 'modified':
									$order_value=get_the_modified_date();
								break;
								
								case 'rand':
									$order_value=rand(0,1000);
								break;
								
								case 'comment_count':
									$order_value=wp_count_comments($post->id);
									$order_value=$order_value->total_comments ;
								break;
								
								case 'menu_order':
									$thispost = get_post($post->id);
									$order_value=$thispost->menu_order;
								break;
							}
						}
						else
							$order_value=get_post_meta($post->id, $order_by , true);
					}
					
					/////////////END FILTER PARAM///////////
					
					$img_id[]=get_post_meta( $post->id , '_thumbnail_id' ,true );
					
					$post_thumbnail = wpb_getImageBySize(array( 'post_id' => $post->id, 'thumb_size' => $pw_image_thumb_size ));
					$current_img_large = $post_thumbnail['thumbnail'];
					$current_img_full = wp_get_attachment_image_src( $img_id[$img_counter++] , 'full' );
					
					$post_type = get_post_type( $post->id );
					$post_taxonomies = get_object_taxonomies($post_type);
					$tax_counter = 0;
					
					 
					/////////////MIXITUP MODE OR GENERAL MODE///////////////
					if($pw_grid_type=='pl-mixitup-grid')
					{
						$output .= 
						'<div class="pl-blogcnt pl-listlayout mix '.$data_filter.' mix_all '. $pw_list_type .' pl_list_id_'.$pw_list_id.'" data-'.$order_by.'-'.$pw_grid_id.'="'.$order_value.'">';
					}else if($pw_grid_type=='pl-standard-grid')
					{
						$output .= 
						'<div class="pl-blogcnt pl-listlayout '. $pw_list_type .' pl_list_id_'.$pw_list_id.'">';
					}
					//////////////////////////////////////////
					 
					$output .='
							<div class="pl-col-md-6 pl-col-xs-12">
								<div class="pl-itemcnt">
									<div class="pl-thumbcnt '. $pw_image_effect .'">
										<a href="'. $post->link .'" target="'. $pw_link_target  .'" >'.$current_img_large.'</a>';
											if(isset($pw_show_overlay) && $pw_show_overlay=='no')
											{
												$output.='
												<div class="pl-overally fadein-eff">
													<div class="pl-icon-cnt '. $pw_icon_effect.' '.$pw_icon_type.'">';
														if (isset($pw_show_zoom_icon) && ($pw_show_zoom_icon=='no')){
															$output .= '<a href="'. $current_img_full[0] .'" class="pl-zoom-icon example-image" data-lightbox="image-set"></a>';
														}
														if (isset($pw_show_link_icon) && ($pw_show_link_icon=='no')){
															$output .= '<a href="'. $post->link .'" class="pl-link-icon" target="'. $pw_link_target  .'" ></a>';
														}
													$output .= '</div><!--pl-icon-cnt -->
												</div>';
											}
									$output .= '		
									</div>
								</div>		
							</div>
							<div class="pl-col-md-6 pl-col-xs-12">
									<div class="pl-detailcnt">
										<h4 class="pl-title left-txt">';
										if (isset($pw_hide_date) && ($pw_hide_date=='off')){
											$output .= '<span class="pl-date">'. get_the_date($pw_date_format).'</span>';
										}
										$output .= '<a href="'. $post->link .'" target="'. $pw_link_target  .'">'. get_the_title() .'</a></h4>';
										if($pw_grid_author=='no')
										{
											$output.='	
												<div class="pl-postmeta">
												<i class="fa fa-pencil"></i>'.get_the_author().'</div>';
										}
										
										if($pw_grid_tags=='no')
										{	
											$output .= pw_get_post_meta($post->id , $post_taxonomies);	
										}
										$output .= '<p class="pl-text left-txt">'. $VCExtendAddonClass_GRID->excerpt(get_the_excerpt(),$pw_excerpt_length).' </p>';
										if($pw_grid_show_num_comment=='no')
										{
											$output .= '
										<div class="pl-postcomment"><a href="'. get_comments_link() .'"><i class="fa fa-comments"></i>'.get_comments_number( '0', '1', '% responses' ) .'</a></div>';
										}
										$output .= '
										<a class="'.$pw_readmore_type.'" href="'. $post->link .'" target="'. $pw_link_target .'" >'.$pw_readmore_translate.'<i class="fa fa-angle-right"></i></a>
									</div>
							</div>
						</div>';
						
				}else
				{
					$my_query->the_post(); // Get post from query
					$post = new stdClass(); // Creating post object.
					$post->id = get_the_ID();
					$post->link = get_permalink($post->id);
					$img_id[]=get_post_meta( $post->id , '_thumbnail_id' ,true );
					$post_thumbnail = wpb_getImageBySize(array( 'post_id' => $post->id, 'thumb_size' => isset($pw_image_thumb_size) ? $pw_image_thumb_size : '' ));
					$current_img_large = $post_thumbnail['thumbnail'];
					$current_img_full = wp_get_attachment_image_src( $img_id[$img_counter++] , 'full' );
					
					$post_type = get_post_type( $post->id );
					$post_taxonomies = get_object_taxonomies($post_type);
					$tax_counter = 0;
					/////////////FILTER AND ORDER PARAM//////
					$data_filter='';
					if(isset($pw_grid_filter_by) && $pw_grid_filter_by=='yes' && $pw_grid_type=='pl-mixitup-grid')
					{
						$taxs=explode(',',$pw_taxonomy);
						if(is_array($taxs) && count($taxs)>1)
						{
							foreach($taxs as $tax)
							{
								$data_filters=wp_get_post_terms($post->id, $tax, array("fields" => "ids"));
								$data_filter.=implode(' ',$data_filters).' ';
							}
						}else if($pw_taxonomy!='')
						{
							$data_filter=wp_get_post_terms($post->id, $pw_taxonomy, array("fields" => "ids"));
							$data_filter=implode(' ',$data_filter);
						}
					}
					
					if(isset($pw_grid_order_by) && $pw_grid_order_by=='yes' && $pw_grid_type=='pl-mixitup-grid')
					{
						$public_orders_array=array('ID','date','author','title','modified','rand','comment_count','menu_order');
						if(in_array($order_by,$public_orders_array))
						{
							switch($order_by)
							{
								case 'ID':
									$order_value=get_the_id();
								break;
								
								case 'date':
									$order_value=get_the_date('Y/m/d');
								break;
								
								case 'author':
									$order_value=get_the_author();
								break;
								
								case 'title':
									$order_value=get_the_title();
								break;
								
								case 'modified':
									$order_value=get_the_modified_date();
								break;
								
								case 'rand':
									$order_value=rand(0,1000);
								break;
								
								case 'comment_count':
									$order_value=wp_count_comments($post->id);
									$order_value=$order_value->total_comments ;
								break;
								
								case 'menu_order':
									$thispost = get_post($post->id);
									$order_value=$thispost->menu_order;
								break;
							}
						}
						else
							$order_value=get_post_meta($post->id, $order_by , true);
					}
					/////////////END FILTER PARAM///////////
					
					
					if ($pw_skin_type=='pl-gridskin-one'){
						
						/////////////MIXITUP MODE OR GENERAL MODE///////////////
						if($pw_grid_type=='pl-mixitup-grid')
						{
							$output .= '<div  class=" mix '.$data_filter.' mix_all ' .$pw_grid_desktop_columns_count.' ' . $pw_grid_tablet_columns_count . ' ' . $pw_grid_mobile_columns_count .' pl-col" data-'.$order_by.'-'.$pw_grid_id.'="'.$order_value.'" >
											<div class="all-detail-cnt">';
						}else if ($pw_grid_type=='pl-standard-grid')
						{
							$output .= '<div  class="' .$pw_grid_desktop_columns_count.' ' . $pw_grid_tablet_columns_count . ' ' . $pw_grid_mobile_columns_count .' pl-col">
											<div class="all-detail-cnt">';
						}
						/////////////////////////////////////////////////////
							
						$post_orders = explode(',',$pw_teasr_layout_img);
							foreach ( $post_orders as $order ){
								$order_type = explode('|',$order);
								switch ($order_type[0]){
								case 'image':
								
											$thumbnail=$current_img_large;
											if(isset($order_type[1]) && $order_type[1]=='link_post')
											{
												$thumbnail='<a href="'. $post->link .'" target="'. $pw_link_target  .'" >'.$thumbnail.'</a>';
											}
											
											$output .= '<div class="pl-itemcnt">
													<div class="pl-thumbcnt '. $pw_image_effect .'">
														'.$thumbnail;
														
														if(isset($pw_show_overlay) && $pw_show_overlay=='no')
														{
															if(isset($order_type[1]) && $order_type[1]=='link_post')
															{
																$output.='<a href="'. $post->link .'" target="'. $pw_link_target  .'" >';
															}
															
															$output.='
															<div class="pl-overally fadein-eff">
																<div class="pl-icon-cnt '. $pw_icon_effect.' '.$pw_icon_type.'">';
																if (isset($pw_show_zoom_icon) && ($pw_show_zoom_icon=='no')){
																	$output .= '<a href="'. $current_img_full[0] .'" class="pl-zoom-icon example-image" data-lightbox="image-set"></a>';
																}
																if (isset($pw_show_link_icon) && ($pw_show_link_icon=='no')){
																	$output .= '<a href="'. $post->link .'" class="pl-link-icon" target="'. $pw_link_target  .'" ></a>';
																}
															$output .= '</div><!--pl-icon-cnt -->
															</div><!-- pl-overally -->';
															
															if(isset($order_type[1]) && $order_type[1]=='link_post')
															{
																$output.='</a>';
															}
														}
													$output .= '	
													</div><!-- pl-thumbcnt -->
												</div><!--pl-itemcnt -->';
											 break;
								case 'title':
											$output .= '<div class="pl-detailcnt">
														<h4 class="pl-title left-txt">';
														if (isset($pw_hide_date) && ($pw_hide_date=='off')){
															$output .= '<span class="pl-date">'. get_the_date($pw_date_format).'</span>';
														}
														
														if(isset($order_type[1]) && $order_type[1]=='link_post')
														{
															$output .= '<a href="'. $post->link .'" target="'. $pw_link_target  .'">'. get_the_title().'</a>';
														}else{
															$output .=  get_the_title();
														}
														$output .= '</h4>
													</div>';
										
											$output .= '<div class="pl-detailcnt">';
											if($pw_grid_author=='no')
											{
												$output.='	
													<div class="pl-postmeta">
														<i class="fa fa-pencil"></i>'.get_the_author().'</div>';
											}
											if($pw_grid_tags=='no')
											{
												$output .= pw_get_post_meta($post->id , $post_taxonomies);	
											}
											$output .= '</div>';
										break;
								case 'text':
											$content=$VCExtendAddonClass_GRID->excerpt(get_the_excerpt(),$pw_excerpt_length,'excerpt');
											if(isset($order_type[1]) && $order_type[1]=='text')
											{
												$content=$VCExtendAddonClass_GRID->excerpt(get_the_content(),$pw_excerpt_length,'full_text');
											}
											
											$output .= '<div class="pl-detailcnt">
														<p class="pl-text left-txt">'. $content.'</p>';
														if($pw_grid_show_num_comment=='no')
														{
															$output .= '
														<div class="pl-postcomment"><a href="'. get_comments_link() .'"><i class="fa fa-comments"></i>'.get_comments_number( '0', '1', '% responses' ).'</a></div>';
														}
											$output .= '			
												  </div>';
												  
												  
										  
												  
								break;
								case 'link':
											$output .= '<div class="pl-detailcnt">
														<a class="'.$pw_readmore_type.'" href="'. $post->link .'" target="'. $pw_link_target .'" >'.$pw_readmore_translate.'<i class="fa fa-angle-right"></i></a>
												  </div>';
											break;
								}//end switch
							}//end foreach
					   $output .=  '
					   			</div><!--all-detail-cnt -->
							</div><!--col -->';
					}//end if
					else if ($pw_skin_type=='pl-gridskin-two'){
						
						/////////////MIXITUP MODE OR GENERAL MODE///////////////
						if($pw_grid_type=='pl-mixitup-grid')
						{
						
						$output .= '<div class="mix '.$data_filter.' mix_all '.$pw_grid_desktop_columns_count. ' ' . $pw_grid_tablet_columns_count . ' ' . $pw_grid_mobile_columns_count .' pl-col" data-'.$order_by.'-'.$pw_grid_id.'="'.$order_value.'" >';
						}else if($pw_grid_type=='pl-standard-grid')
						{
							$output .= '<div class="'.$pw_grid_desktop_columns_count. ' ' . $pw_grid_tablet_columns_count . ' ' . $pw_grid_mobile_columns_count .' pl-col">';
						}
						////////////////////////////////////////
						
						$output .= '
									<div class="pl-view '.$pw_grid_skin_effect.'">
										'.$current_img_large.'
										<div class="pl-mask">
											<h4><a href="'. $post->link .'" target="'. $pw_link_target  .'">'. get_the_title().'</a></h4>
											<p>'. $VCExtendAddonClass_GRID->excerpt(get_the_excerpt(),$pw_excerpt_length).'</p>';
											if ($pw_grid_skin_effect=='pl-gst-effect-1' || $pw_grid_skin_effect=='pl-gst-effect-2' || $pw_grid_skin_effect=='pl-gst-effect-3' || $pw_grid_skin_effect=='pl-gst-effect-4' ){
												$output.='<a class="pl-permalink" href="'. $post->link .'" target="'. $pw_link_target  .'" >'.$pw_readmore_translate.'<i class="fa fa-arrow-circle-right"></i></a>';
											}
											else {
												$output .= '<a class="link-hoverset2" href="'. $post->link .'" target="'. $pw_link_target  .'" >'.$pw_readmore_translate.'</a>';
											}
							$output .='</div>
									</div>
							</div><!--col -->
							';
					}//end elseif
				
				}
			}
			else if ($pw_grid_type=='pl-masonry-grid')
			{
				$wp_comment_count = 0;
				$my_query->the_post(); // Get post from query
				$post = new stdClass(); // Creating post object.
				$post->id = get_the_ID();
				$post->link = get_permalink($post->id);
				$img_id[]=get_post_meta( $post->id , '_thumbnail_id' ,true );
				
				$post_thumbnail = wpb_getImageBySize(array( 'post_id' => $post->id, 'thumb_size' => isset($pw_image_thumb_size) ? $pw_image_thumb_size : '' ));
				$current_img_large = $post_thumbnail['thumbnail'];
				$current_img_full = wp_get_attachment_image_src( $img_id[$img_counter++] , 'full' );
				
				$post_type = get_post_type( $post->id );
				$post_taxonomies = get_object_taxonomies($post_type);
				$tax_counter = 0;
					
				if ($pw_skin_type=='pl-gridskin-one'){
					
					$output .= '<div class="' .$pw_grid_desktop_columns_count.' ' . $pw_grid_tablet_columns_count . ' ' . $pw_grid_mobile_columns_count .' pl-col" >
									<div class="all-detail-cnt">';
					$post_orders = explode(',',$pw_teasr_layout_img);
						foreach ( $post_orders as $order ){
							$order_type = explode('|',$order);
							switch ($order_type[0]){
							case 'image':
										$thumbnail=$current_img_large;
										if(isset($order_type[1]) && $order_type[1]=='link_post')
										{
											$thumbnail='<a href="'. $post->link .'" target="'. $pw_link_target  .'" >'.$thumbnail.'</a>';
										}
							
										$output .= '<div class="pl-itemcnt">
												<div class="pl-thumbcnt '. $pw_image_effect .'">
													'.$thumbnail;
													
													if(isset($pw_show_overlay) && $pw_show_overlay=='no')
													{
														
														if(isset($order_type[1]) && $order_type[1]=='link_post')
														{
															$output.='<a href="'. $post->link .'" target="'. $pw_link_target  .'" >';
														}
														
														$output.='
														<div class="pl-overally fadein-eff">
															<div class="pl-icon-cnt '. $pw_icon_effect.' '.$pw_icon_type.'">';
																if (isset($pw_show_zoom_icon) && ($pw_show_zoom_icon=='no')){
																	$output .= '<a href="'. $current_img_full[0] .'" class="pl-zoom-icon example-image" data-lightbox="image-set"></a>';
																}
																if (isset($pw_show_link_icon) && ($pw_show_link_icon=='no')){
																	$output .= '<a href="'. $post->link .'" class="pl-link-icon" target="'. $pw_link_target  .'" ></a>';
																}
															$output .= '</div><!--pl-icon-cnt -->
														</div><!-- pl-overally -->';
														
														if(isset($order_type[1]) && $order_type[1]=='link_post')
														{
															$output.='</a>';
														}
													}
												$output .= '	
												</div><!-- pl-thumbcnt -->
											</div><!--pl-itemcnt -->';
										 break;
							case 'title':
										$output .= '<div class="pl-detailcnt">
													<h4 class="pl-title left-txt">';
														if (isset($pw_hide_date) && ($pw_hide_date=='off')){
															$output .= '<span class="pl-date">'. get_the_date($pw_date_format).'</span>';
														}
														
														if(isset($order_type[1]) && $order_type[1]=='link_post')
														{
															$output .= '<a href="'. $post->link .'" target="'. $pw_link_target  .'">'. get_the_title().'</a>';
														}else{
															$output .=  get_the_title();
														}
														$output .= '</h4>
												</div>';
										
										$output .= '<div class="pl-detailcnt">';
										if($pw_grid_author=='no')
										{
											$output.='	
												<div class="pl-postmeta">
													<i class="fa fa-pencil"></i>'.get_the_author().'</div>';
										}
										if($pw_grid_tags=='no')
										{
											$output .= pw_get_post_meta($post->id , $post_taxonomies);	
										}
										$output .= '</div>';
										break;
							case 'text':
										$content=$VCExtendAddonClass_GRID->excerpt(get_the_excerpt(),$pw_excerpt_length,'excerpt');
											if(isset($order_type[1]) && $order_type[1]=='text')
											{
												$content=$VCExtendAddonClass_GRID->excerpt(get_the_content(),$pw_excerpt_length,'full_text');
											}
										
										$output .= '<div class="pl-detailcnt">
													<p class="pl-text left-txt">'. $content.'</p>';
													if($pw_grid_show_num_comment=='no')
													{
														$output .= '
													<div class="pl-postcomment"><a href="'. get_comments_link() .'"><i class="fa fa-comments"></i>'.get_comments_number( '0', '1', '% responses' ).'</a></div>';
													}
										$output .= '			
											  </div>';
										break;
							case 'link':
										$output .= '<div class="pl-detailcnt">
													<a class="pl-permalink" href="'. $post->link .'" target="'. $pw_link_target  .'" >'.$pw_readmore_translate.'<i class="fa fa-arrow-circle-right"></i></a>
											  </div>';
										break;
							}//end switch
						}//end foreach
				   $output .=  '
				   			</div>
						</div>';
				}//end if
				else if ($pw_skin_type=='pl-gridskin-two'){
					
					$output .= '
							<div class="'.$pw_grid_desktop_columns_count. ' ' . $pw_grid_tablet_columns_count . ' ' . $pw_grid_mobile_columns_count .' pl-col" >
									<div class="pl-view '.$pw_grid_skin_effect.'">
										'.$current_img_large.'
										<div class="pl-mask">
											<h4><a href="'. $post->link .'" target="'. $pw_link_target  .'">'. get_the_title().'</a></h4>
											<p>'. $VCExtendAddonClass_GRID->excerpt(get_the_excerpt(),$pw_excerpt_length).'</p>
											<a class="pl-permalink" href="'. $post->link .'" target="'. $pw_link_target  .'" >'.$pw_readmore_translate.'<i class="fa fa-arrow-circle-right"></i></a>
										</div>
									</div>
							</div><!--col -->
							';
					
				}//end elseif
            
			}//emd if masonry	
		}
		
		echo $output;
		exit(0);
		//print_r($_POST);

	}
	
?>