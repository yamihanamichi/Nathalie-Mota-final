



<div id="contact-popup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 9999;">
    <div class="contact-form-container" style="position: relative; background-color: #fff;">
        <img src="<?= esc_url(get_stylesheet_directory_uri()) ?>/images/Contact-header.png" alt="Header image">
        <?php echo do_shortcode('[contact-form-7 id="b8a7a23" title="nathalie contact"]'); ?>
        <button id="close-contact" style="position: absolute; top: 10px; right: 10px;">Fermer</button>
    </div>
</div>
