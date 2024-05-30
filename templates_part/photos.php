<?php
    // Récupérer les custom fields avec ACF
    
    $type = get_field('type');
    $reference = get_field('reference');
    $date = get_field('date');

    // Champs de Taxonomies
    $taxo_categorie = get_the_terms(get_the_ID(), 'categorie'); 
    $taxo_format = get_the_terms(get_the_ID(), 'format');
    $taxo_annee = get_the_terms(get_the_ID(), 'annee'); 
?>

<div class="container-principal">
    <div class="container-principal__single">
        <div class="container-principal__single__details-photo">
            <h2><?php echo esc_html(get_the_title()); ?></h2>
            <div class="container-principal__single__details-photo__description">
                <?php 
                    if (isset($reference)) {
                        echo '<p>RÉFÉRENCE: ' . esc_html($reference) . '<br></p>';
                    }
                    
                    if ($taxo_categorie && isset($taxo_categorie[0])) {
                        echo '<p>CATÉGORIE: ' . esc_html($taxo_categorie[0]->name) . '<br></p>';
                    }

                    if ($taxo_format && isset($taxo_format[0])) {
                        echo '<p>FORMAT: ' . esc_html($taxo_format[0]->name) . '<br></p>';
                    }

                    if (isset($type) && is_string($type)) {
                        echo '<p>TYPE: ' . esc_html($type) . '<br></p>';
                    }

                    if ($taxo_annee && isset($taxo_annee[0]) && preg_match('/^\d{4}$/', $taxo_annee[0]->name)) {
                        echo '<p>ANNÉE: ' . esc_html($taxo_annee[0]->name) . '<br></p>';
                    }
                ?>
            </div>
        </div>
        <div class="img-single">
            <div class="img-single__overlay">
                <?php 
                the_content();
                ?>
                
                <div class="overlay-single">
                    <img src="<?php echo get_theme_file_uri() .'/images/fullscreen.png';?>" class="fullscreen-icon" alt="Voir en plein écran">
                </div>
            </div>
        </div>
    </div>

    <div class="container-contact">
        <div class="container-contact__contact-btn">
            <p> Cette photo vous intéresse ? </p>
            <button type="button" class="contact-link" data-reference="<?php echo $reference; ?>">Contact</button>
        </div>

        <?php
            //Flèches précédent et suivant
            $next_post = get_next_post();
            $previous_post = get_previous_post();

            // Si on est sur la dernière photo, définir $next_post comme le premier post
            if (empty($next_post)) {
                $args = array(
                    'posts_per_page' => 1,
                    'order'          => 'ASC',
                    'post_type'      => 'photos' // 
                );
                $first_post = get_posts($args);
                if (!empty($first_post)) {
                    $next_post = $first_post[0];
                }
            }

            // Si on est sur la première photo, définir $previous_post comme le dernier post
            if (empty($previous_post)) {
                $args = array(
                    'posts_per_page' => 1,
                    'order'          => 'DESC',
                    'post_type'      => 'photos' // 
                );
                $last_post = get_posts($args);
                if (!empty($last_post)) {
                    $previous_post = $last_post[0];
                }
            }
        ?>

        <div class="container-contact__navigation-arrows">
            <?php if (!empty($previous_post) || !empty($next_post)) { ?>
                
                <!-- Bloc pour la photo précédente -->
                <div class="arrow-block">
                    <div class="container-miniature container-miniature-previous">
                        <?php
                            if (!empty($previous_post)) {
                                $thumbnail_ID_prev = get_post_thumbnail_id($previous_post->ID);
                                if ($thumbnail_ID_prev) {
                                    echo wp_get_attachment_image($thumbnail_ID_prev, 'thumbnail', false, ['class' => 'container-miniature__img-arrows']);
                                }
                            }
                        ?>
                    </div>
                    <?php if (!empty($previous_post)) { ?>
                        <a href="<?php echo get_permalink($previous_post->ID) ?>"><img class="arrow-left" src="<?php echo get_theme_file_uri() .'/images/arrow-left.png';?>" alt="Flèche précédent"></a>
                    <?php } ?>
                </div>

                <!-- Bloc pour la photo suivante -->
                <div class="arrow-block">
                    <div class="container-miniature container-miniature-next">
                        <?php
                            if (!empty($next_post)) {
                                $thumbnail_ID_next = get_post_thumbnail_id($next_post->ID);
                                if ($thumbnail_ID_next) {
                                    echo wp_get_attachment_image($thumbnail_ID_next, 'thumbnail', false, ['class' => 'container-miniature__img-arrows']);
                                }
                            }
                        ?>
                    </div>
                    <?php if (!empty($next_post)) { ?>
                        <a href="<?php echo get_permalink($next_post->ID) ?>"><img class="arrow-right" src="<?php echo get_theme_file_uri() .'/images/arrow-right.png';?>" alt="Flèche suivant"></a>
                    <?php } ?>
                </div>
                
            <?php } ?>
        </div>
    </div>

    <div class="container-photo">
        <p class="container-photo__title"> vous aimerez aussi </p>
        <div class="container-photo-apparente">
            <?php
                $categories = get_the_terms(get_the_ID(), 'categorie');
                if ($categories && !is_wp_error($categories)) {
                    $category_ids = wp_list_pluck($categories, 'term_id');

                    $args = array(
                        'post_type' => 'photos',
                        'posts_per_page' => 2,
                        'orderby' => 'rand',
                        'post__not_in' => array(get_the_ID()),
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'categorie',
                                'field' => 'term_id',
                                'terms' => $category_ids,
                            ),
                        ),
                    );

                    $photos_query = new WP_Query($args);

                    if ($photos_query->have_posts()) {
                        while ($photos_query->have_posts()) {
                            $photos_query->the_post();
                            $photo_retrieval = get_field('photos');
                            ?>
                            
                            <?php get_template_part('templates-part/block-photo'); ?>

                            <?php
                        }
                        wp_reset_postdata();
                    }
                }
            ?>
        </div>

        <button type="button" id="redirection-catalogue" class="container-photo__all-btn">Toutes les photos</button>
    </div>
</div>