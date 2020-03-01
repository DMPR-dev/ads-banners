<?php
namespace AdsBanners;

class Inputs
{
	/*
	 *
	 *
	 *	TEXT INPUT
	 *
	 *
	 */
	public static function TextInput( $post_id, $name, $label, $get_data = 'get_post_meta' )
	{
		$current_value = $get_data( $post_id, $name, true );
		ob_start();
		?>
			<label for="<?php echo esc_attr( $name ); ?>"> <?php echo esc_html( $label ); ?> </label>
			<input type="text" style="width: 100%;" id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $current_value ); ?>"/>
		<?php
		return ob_get_clean();
	}
	/*
	 *
	 *
	 *	END: TEXT INPUT
	 *
	 *
	 */
	/*
	 *
	 *
	 *	SELECT INPUT
	 *
	 *
	 */
	public static function SelectInput( $post_id, $name, $label, $values = array(), $get_data = 'get_post_meta' )
	{
		$current_value = $get_data( $post_id, $name, true );
		ob_start();
		?>
			<label for="<?php echo esc_attr( $name ); ?>"> <?php echo esc_html( $label ); ?> </label>
			<select style="width: 100%;" id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>">
				<?php
					foreach( $values as $key => $val )
					{
						?>
							<option value="<?php echo $key; ?>" <?php echo $key === esc_attr( $current_value ) ? "selected" : "" ?> > <?php echo $val; ?>
							</option> 
						<?php
					}
				?>
			</select>
		<?php
		return ob_get_clean();
	}
	/*
	 *
	 *
	 *	END: SELECT INPUT
	 *
	 *
	 */
	/*
	 *
	 *
	 *	Spoiler
	 *
	 *
	 */
	public static function SpoilerInput( $label, $contents = array(), $content_args = array(), $get_data = 'get_post_meta' )
	{
		ob_start();
		?>
			<details class="ads-banners-details">
				<summary><?php echo str_replace( ':', '', $label ); ?></summary>
				<div class="ads-banners-details-content">
					<?php
						$post_id = isset( $content_args["post_id"] ) ? intval( $content_args["post_id"] ) : -1;
						MetaBox::boxCallback( $post_id, $contents, $get_data );
					?>
				</div>
			</details>
		<?php
		return ob_get_clean();
	}
	/*
	 *
	 *
	 *	END: Spoiler
	 *
	 *
	 */
	/*
	 *
	 *
	 *	CATEGORIES INPUT
	 *
	 *
	*/
	public static function CategoriesListInput( $post_id, $name, $label )
	{
		$current_value = get_post_meta( $post_id, $name, true );

		$array = json_decode( $current_value );

		if( !is_array( $array ) )
		{
			$array = array();
		}

		$categories = get_categories();

		ob_start();

		self::RenderCurrentCategoriesList( $array, $label );
		?>

		<label for="<?php echo esc_attr( $name ); ?>-select" style="margin-top: 15px;"><?php echo __( "Add Categories" ); ?> </label>
		<select style="width: 100%;" id="<?php echo esc_attr( $name ); ?>-select" class="categories-select">
			<?php
				if( is_array( $categories ) )
				{
					foreach( $categories as $cat )
					{
						?>
							<option value="<?php echo $cat->term_id; ?>"> <?php echo $cat->name; ?> </option> 
						<?php
					}
				}
			?>
		</select>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" class="categories-hidden-input" id="<?php echo esc_attr( $name ); ?>" value="<?php echo str_replace('"', '', json_encode( $array ) ); ?>"/>
		<button class="components-button is-button is-default is-large add-category-btn" style="margin-top: 10px;">
			<?php echo __( "Link Category" ); ?>
		</button>
		<?php

		return ob_get_clean();
	}

	protected static function RenderCurrentCategoriesList( $array, $label )
	{
		?>
		<label> <?php echo esc_html( $label ); ?> </label>
		<div class="current-categories-list">
			<span class="category-sample categories-list-object-holder"> 
				<span class="cat-name">
					
				</span>
				<span class="remove-btn-holder" style="position: absolute; top: 0px; right: 5px; bottom:0px; display: flex; align-items: center; cursor: pointer;" data-category-id="-1"> 
					X 
				</span>
			</span>
			<?php
				if( sizeof( $array ) > 0)
				{
					foreach( $array as $category_id )
					{
						$cat_name = get_the_category_by_ID( $category_id );

						if( $cat_name !== FALSE )
						{
							?>
								<span class="categories-list-object-holder"> 
									<span class="cat-name">
										<?php
											echo $cat_name;
										?>
									</span>
									<span style="position: absolute; top: 0px; right: 5px; bottom:0px; display: flex; align-items: center; cursor: pointer;" class="remove-btn-holder" data-category-id="<?php echo $category_id; ?>"> 
										X 
									</span>
								</span>
							<?php
						}
					}
				}
				else
				{
					?>
					<span class="no-categories-found"> <?php echo __( "No categories linked." ); ?> </span>
					<?php
				}
			?>
		</div>
		<?php
	}
	/*
	 *
	 *
	 *	END: CATEGORIES INPUT
	 *
	 *
	*/
	/*
	 *
	 *
	 * POSTS INPUT
	 *
	 *
	 */
	public static function PostsListInput( $post_id, $name, $label )
	{
		$current_value = get_post_meta( $post_id, $name, true );

		$array = json_decode( $current_value );

		if( !is_array( $array ) )
		{
			$array = array();
		}

		$posts = get_posts( array( 
			"post_type" 		=> array( "post" ),
			"post_status"		=> array( "publish", "draft" ),
			"posts_per_page"	=> -1
		) );

		ob_start();

		self::RenderCurrentPostsList( $array, $label );

		$select_box_id = uniqid();
		?>

		<label for="<?php echo esc_attr( $name ); ?>-select" style="margin-top: 15px;"><?php echo __( "Add Posts" ); ?> </label>
		<div>
			<small><?php echo __( "Selected Post Title" ); ?></small>
			<input type="text" style="width: 100%; height: 20px; font-size: 13px; padding-left: 5px;" class="currently-selected-post" value="<<?php echo __( "SELECT POST" );?>>" disabled />
		</div>
		<div>
			<small><?php echo __( "Selected Post ID" ); ?></small>
			<input type="text" list="all-posts-list-<?php echo $select_box_id; ?>" style="width: 100%;" id="<?php echo esc_attr( $name ); ?>-select" class="posts-select"/>
			<datalist id="all-posts-list-<?php echo $select_box_id; ?>">
				<?php
					if( is_array( $posts ) )
					{
						foreach( $posts as $post )
						{
							?>
								<option value="<?php echo $post->ID; ?>"> <?php echo $post->post_title; ?> </option> 
							<?php
						}
					}
				?>
			</datalist>
		</div>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" class="posts-hidden-input" id="<?php echo esc_attr( $name ); ?>" value="<?php echo str_replace('"', '', json_encode( $array ) ); ?>"/>
		<button class="components-button is-button is-default is-large add-post-btn" style="margin-top: 10px;">
			<?php echo __( "Link Post" ); ?>
		</button>
		<?php

		return ob_get_clean();
	}

	protected static function RenderCurrentPostsList( $array, $label )
	{
		?>
		<label> <?php echo esc_html( $label ); ?> </label>
		<div class="current-posts-list">
			<span class="posts-sample posts-list-object-holder"> 
				<span class="cat-name">
					
				</span>
				<span class="remove-btn-holder" style="position: absolute; top: 0px; right: 5px; bottom:0px; display: flex; align-items: center; cursor: pointer;" data-post-id="-1"> 
					X 
				</span>
			</span>
			<?php
				if( sizeof( $array ) > 0)
				{
					foreach( $array as $post_id )
					{
						$post_obj = get_post( $post_id );

						if( property_exists( $post_obj, "ID" ) )
						{
							?>
								<span class="posts-list-object-holder"> 
									<span class="cat-name">
										<?php
											echo $post_obj->post_title;
										?>
									</span>
									<span style="position: absolute; top: 0px; right: 5px; bottom:0px; display: flex; align-items: center; cursor: pointer;" class="remove-btn-holder" data-post-id="<?php echo $post_id; ?>"> 
										X 
									</span>
								</span>
							<?php
						}
					}
				}
				else
				{
					?>
					<span class="no-posts-found"> <?php echo __( "No posts linked." ); ?> </span>
					<?php
				}
			?>
		</div>
		<?php
	}
	/*
	 *
	 *
	 *	END: POSTS INPUT
	 *
	 *
	 */

}