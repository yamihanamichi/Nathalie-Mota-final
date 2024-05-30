<?php get_header(); ?>

<div class="banner">
    <img src="<?= esc_url(get_stylesheet_directory_uri()) ?>/images/nathalie-1.jpeg" alt="Banner Image">
    <div class="overlay">
        <img src="<?= esc_url(get_stylesheet_directory_uri()) ?>/images/titre_header.png" alt="Titre Header">
    </div>
</div>

<div class="menu-bar">
    <div class="menu-item">
        <?php wp_dropdown_categories('taxonomy=categorie&hide_empty=0&show_option_none=Catégories'); ?>
    </div>
    <div class="menu-item">
        <?php wp_dropdown_categories('taxonomy=format&hide_empty=0&show_option_none=Formats'); ?>
    </div>
    <div class="menu-item">
        <select name="order_by">
            <option value="">Trier par</option>
            <option value="newest">Nouveautés</option>
            <option value="oldest">Les plus anciens</option>
        </select>
    </div>
</div>

<div class="wrapper">
    <div class="photo-grid" id="photo-grid">
        <?php
        $args = array(
            'post_type' => 'photo',
            'posts_per_page' => 6 // Limite à 6 photos
        );
        $photos = new WP_Query($args);

        if ($photos->have_posts()) :
            while ($photos->have_posts()) : $photos->the_post();
                $permalink = get_permalink(); // Obtenir l'URL de la page unique de la photo
               
                $categories = get_the_category(); // Récupérer les catégories de la photo
        ?>
                <div class="col">
                    <div class="image-container">
                    
                        <div class="lightbox" style="display: none;">
                            <?php the_content(); ?> 
                            </div>

                            <a href="<?php echo esc_url($permalink); ?>" class="btn-details">
                                <img src="<?= esc_url(get_stylesheet_directory_uri()) ?>/images/eye.png" alt="Détails de la photo">
                            </a>
                            <a href="" class="photo-link">
                                <img src="<?= esc_url(get_stylesheet_directory_uri()) ?>/images/fullscreen.png" alt="Afficher la photo en plein écran">
                            </a>
                            
                            <h3><?php the_title(); ?></h3>
                            <?php the_content(); ?>

                            <div class="photo-category">
    <?php
    // Récupérer les catégories associées à la photo
    $categories = get_the_terms(get_the_ID(), 'categorie');

    // Vérifier si des catégories existent pour la photo
    if ($categories && !is_wp_error($categories)) {
        echo esc_html($categories[0]->name); // Afficher le nom de la première catégorie
    } else {
        echo 'Catégorie non définie'; // Message par défaut si aucune catégorie n'est définie
    }
    ?>
                            </div>
                        
                        </div>
                    </div>
                
        <?php
            endwhile;
        endif;
        wp_reset_postdata();
        ?>
             </div>
</div>


<div class="load-more">
    <button id="load-more-btn">
    <img src="<?= esc_url(get_stylesheet_directory_uri()) ?>/images/charger.png" alt="load more">
    </button>
</div>




<?php get_footer(); ?>
