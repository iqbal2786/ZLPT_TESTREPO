<?php
namespace ZestyLemon\Gutenberg\Blocks\PropertyFoodAndDrink;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/single-property-food-and-drink", array(
		"attributes" => array(
            "icon" => array(
                "type"      => "string",
                "default"   => ""
            ),
            "headline_tag" => array(
                "type"      => "string",
                "default"   => "h2"
            ),
            "hide_title" => array(
                "type"      => "boolean",
                "default"   => false
            ),
            "food_and_drink_title" => array(
                "type"      => "string",
                "default"   => __("Food and drink ...", "zestylemon")
            ),
            "food_and_drink_1" => array(
                "type"      => "string",
                "default"   => ""
            ),
            "bg_color" => array(
                "type"      => "string",
                "default"   => "var(--accent)"
            ),
            "title_color" => array(
                "type"      => "string",
                "default"   => "var(--base-3)"
            ),
            "text_color" => array(
                "type"      => "string",
                "default"   => "var(--contrast)"
            ),
        ),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
    global $wp_query;
    $editor     = false;
    $bg_color   = $title_color = $text_color = $icon_slug = '';
    $icon       = array();
    
    if ( isset( $attributes['icon'] ) && ! empty( $attributes['icon'] ) ) {
        $icon_slug = esc_attr( $attributes['icon'] );
        $icon = zl_get_icon_from_library( $icon_slug );
    }

    if ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) {
        $editor = true;
        $args   = array(
            'post_type' => 'property',
            'tax_query' => array(
                    array(
                        'taxonomy'  => 'property_type',
                        'terms'     => array('hotel','resort'),
                        'field'     => 'slug',
                        'include_children' => true,
                        'operator' => 'IN'
                    )
                ),
        );
        $properties = new \WP_Query( $args );
        if ( $properties->have_posts() ) {
            $properties->the_post();
        }
    }
    
    $headline_tag = $attributes['headline_tag'];
    if ( empty( $headline_tag ) || ! in_array( $headline_tag, array('h1', 'h2', 'h3', 'h4', 'h5', 'h6') ) ) {
        $headline_tag = 'h2';
    }
    $food_and_drink_1 = '';
    if ( isset( $attributes['food_and_drink_1'] ) && ! empty( $attributes['food_and_drink_1'] ) ) {
        $food_and_drink_1 = get_field( esc_attr( $attributes['food_and_drink_1'] ) );
    }
    
    if ( $editor ) {
        if ( ! isset( $attributes['food_and_drink_title'] ) || empty( $attributes['food_and_drink_title'] ) ) {
            $attributes['food_and_drink_title'] = 'Dummy title';
        }
        if ( empty( $food_and_drink_1 ) ) {
            $food_and_drink_1 = array(
            							array(
	            							'f&b_name' => "Name",
	            							'f&b_description' => "Dummy Food and Drink 1",
            							)
            					);		
        }
    }
    if ( isset( $attributes['bg_color'] ) && ! empty( $attributes['bg_color'] ) ) {
        $bg_color = $attributes['bg_color'];
    }
    if ( isset( $attributes['title_color'] ) && ! empty( $attributes['title_color'] ) ) {
        $title_color = $attributes['title_color'];
    }
    if ( isset( $attributes['text_color'] ) && ! empty( $attributes['text_color'] ) ) {
        $text_color = $attributes['text_color'];
    }
    ob_start();

    if ( ! empty( $food_and_drink_1 )  ) {
        ?>
        <div class="zl-block-container">
            <?php
                $style = '';
                if ( ! empty( $bg_color ) ) {
                    $style = 'background-color: ' . esc_attr( $bg_color );
                }
            ?>
            <div class="zesty-lemon-single-property-food-and-drink" style="<?php echo $style; ?>">
                <div class="grid-container">
                    <?php
                        if ( isset( $attributes['food_and_drink_title'] ) && ! empty( $attributes['food_and_drink_title'] ) ) {
                            $style = '';
                            if ( ! empty( $title_color ) ) {
                                $style = 'color: ' . esc_attr( $title_color ).';';
                            }
                            
                            if( $attributes['hide_title'] ){
                                $style .= 'visibility: hidden; position: absolute;';   
                            }

                            echo '<' . $headline_tag . ' class="food-and-drink-title" style="' . $style . '">' . esc_attr( $attributes['food_and_drink_title'] ) . '</' . $headline_tag . '>';
                        }
                    ?>
					<div class="zl-food-and-drink-inner-wrapper">
	                    <?php
	                        $style = '';
	                        if ( ! empty( $text_color ) ) {
	                            $style = 'color: ' . esc_attr( $text_color );
	                        }

                            if ( ! empty( $icon ) ) {
                                echo '<div class="zl-food-and-drink-icon zl-food-and-drink-left-wrapper">';
                                    if ( $icon['use_svg'] && ! empty( $icon['icon_svg'] ) ) {
                                        echo $icon['icon_svg'];
                                    }
                                    if ( ! $icon['use_svg'] && ! empty( $icon['icon_image_url'] ) ) {
                                        echo '<img class="wp-image-' . esc_attr( $icon['icon_image'] ) . '" src="' . esc_url( $icon['icon_image_url'] ) . '" />';
                                    }
                                echo '</div>';
                            }
            
						?>
						<div class="zl-food-and-drink-right-wrapper">
		                    <ul class="food-and-drink-list" style="<?php echo $style; ?>">
		                        <?php
		                            if ( ! empty( $food_and_drink_1 ) && count($food_and_drink_1) > 0  ) {
		                            	foreach( $food_and_drink_1 as $key => $data ){
		                            		echo '<li>' . esc_attr( $data['f&b_name'] )  . ' - '.$data['f&b_description'].'</li>';
		                            	}
		                            }
		                        ?>
		                    </ul>
	                    </div>
	                </div>    
                </div>
            </div>
        </div>
        <?php
    }
    $output = ob_get_clean();
    if ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) {
        wp_reset_postdata();
    }
    return $output;
}