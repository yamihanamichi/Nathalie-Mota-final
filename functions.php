<?php


function enqueue_my_styles() {
    // styles.css 
    wp_enqueue_style( 'my-custom-styles', esc_url(get_stylesheet_directory_uri())  . '/style.css' );
    wp_enqueue_script( 
        'script', 
        get_stylesheet_directory_uri() ."/scripts.js",
        array( 'jquery' ), 
        '1.0', 
        true
    );
}
add_action( 'wp_enqueue_scripts', 'enqueue_my_styles' );

add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');
add_theme_support( 'post-thumbnails' );
add_post_type_support( 'photo', 'thumbnail' );
function create_post_type() {
    register_post_type( 'photo',
        array(
            'labels' => array(
                'name' => __( 'Photos' ),
                'singular_name' => __( 'photo' )
            ),
            'public' => true,
            'has_archive' => true
        )
    );
}
add_action( 'init', 'create_post_type' );

function load_more_photos() {
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 6,
        'paged' => $_POST['page']
    );
    $photos = new WP_Query($args);

    if ($photos->have_posts()) :
        while ($photos->have_posts()) : $photos->the_post();
        $permalink = get_permalink();
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
                
            </div>
    <?php
        endwhile;
    endif;
    wp_reset_postdata();
    die();
}

function filter_photos() {
    // Récupérer la catégorie sélectionnée
    $categorie = $_POST['categorie'];

    // Arguments de requête pour récupérer les photos de la catégorie sélectionnée
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'categorie',
                'field' => 'id',
                'terms' => $categorie
            )
        )
    );

    // Exécuter la requête WordPress
    $photos = new WP_Query($args);

    // Boucle pour afficher les photos du front page
    if ($photos->have_posts()) :
        while ($photos->have_posts()) : $photos->the_post();
            $permalink = get_permalink(); // Obtenir l'URL de la page unique de la photo
           
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
                
            
            </div>
            <?php
        endwhile;
    else :
        // Aucune photo trouvée
        echo 'Aucune photo trouvée';
    endif;

    // Réinitialiser les requêtes WordPress
    wp_reset_postdata();

    // Arrêter le script WordPress
    wp_die();
}

add_action('wp_ajax_filter_photos', 'filter_photos'); // Pour les utilisateurs connectés
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos'); // Pour les utilisateurs non connectés

// Ajouter jQuery dans le thème WordPress
function add_jquery() {
    // Désenregistrer la version jQuery par défaut incluse dans WordPress
    wp_deregister_script('jquery');

    // Enregistrer la nouvelle version de jQuery depuis Google CDN
    wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', array(), null, true);

    // Charger jQuery
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'add_jquery');

function enqueue_custom_script() {
    wp_enqueue_script('custom-lightbox-script', get_stylesheet_directory_uri() . '/twentytwentyfour-child/script.js', array('jquery'), null, true);
    wp_localize_script('custom-lightbox-script', 'themeVars', array(
        'stylesheetDirectoryUri' => get_stylesheet_directory_uri()
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_script');
