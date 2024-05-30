// load more // 

jQuery(document).ready(function($) {
    var page = 2; // Commence à la page 2 car la première est déjà affichée
    var ajaxurl = "wp-admin/admin-ajax.php";
    var loading = false;

    $('#load-more-btn').click(function() {
        if (!loading) {
            loading = true;
            var data = {
                'action': 'load_more_photos',
                'page': page
            };

            $.post(ajaxurl, data, function(response) {
                $('#photo-grid').append(response);
                page++;
                loading = false;
                const imageContainers = document.querySelectorAll(".image-container");

                imageContainers.forEach(function(container) {
                    const lightbox = container.querySelector(".lightbox");
                    const btnDetails = container.querySelector(".btn-details");
                    const photoLink = container.querySelector(".photo-link");
                    const title = container.querySelector("h3");

                    container.addEventListener("mouseenter", function() {
                        lightbox.classList.add("active");
                        btnDetails.style.display = "block";
                        photoLink.style.display = "block";
                        title.style.display = "block";
                    });

                    container.addEventListener("mouseleave", function() {
                        lightbox.classList.remove("active");
                        btnDetails.style.display = "none";
                        photoLink.style.display = "none";
                        title.style.display = "none";
                    });
                    
                });
                setlightbox();
            });
        }
    });
});





jQuery(document).ready(function($) {
    // Lorsqu'une catégorie est sélectionnée
    $('.menu-bar .menu-item select').change(function() {
        // Récupérer la valeur sélectionnée dans le sélecteur de catégories
        var categorie = $(this).val();

        // Effectuer une requête AJAX pour charger les photos correspondantes à la catégorie sélectionnée
        $.ajax({
            url: "wp-admin/admin-ajax.php",
            type: 'POST',
            data: {
                action: 'filter_photos', // Action pour filtrer les photos
                categorie: categorie // Catégorie sélectionnée
            },
            success: function(response) {
                // Afficher les photos chargées
                $('.photo-grid').html(response);
                const imageContainers = document.querySelectorAll(".image-container");

                imageContainers.forEach(function(container) {
                    const lightbox = container.querySelector(".lightbox");
                    const btnDetails = container.querySelector(".btn-details");
                    const photoLink = container.querySelector(".photo-link");
                    const title = container.querySelector("h3");
            
                    container.addEventListener("mouseenter", function() {
                        lightbox.classList.add("active");
                        btnDetails.style.display = "block";
                        photoLink.style.display = "block";
                        title.style.display = "block";
                    });
            
                    container.addEventListener("mouseleave", function() {
                        lightbox.classList.remove("active");
                        btnDetails.style.display = "none";
                        photoLink.style.display = "none";
                        title.style.display = "none";
                    });
                    
                });
                setlightbox();
            }
        });
    });
});


// Variables globales pour suivre l'index de la photo actuellement affichée
let currentIndex = 0;

// LIGHTBOX //

document.addEventListener("DOMContentLoaded", function() {
    const imageContainers = document.querySelectorAll(".image-container");

    imageContainers.forEach(function(container) {
        const lightbox = container.querySelector(".lightbox");
        const btnDetails = container.querySelector(".btn-details");
        const photoLink = container.querySelector(".photo-link");
        const title = container.querySelector("h3");

        container.addEventListener("mouseenter", function() {
            lightbox.classList.add("active");
            btnDetails.style.display = "block";
            photoLink.style.display = "block";
            title.style.display = "block";
        });

        container.addEventListener("mouseleave", function() {
            lightbox.classList.remove("active");
            btnDetails.style.display = "none";
            photoLink.style.display = "none";
            title.style.display = "none";
        });
        
        
    });
    setlightbox();
});

// FLECHE POUR LE BOUTON AFFICHER EN PLEIN ECRAN // 

jQuery(document).ready(function($) {

    setlightbox();

    function setlightbox() {
        var lightbox = $("<div>").addClass("custom-lightbox").css({
            "position": "fixed",
            "top": "0",
            "left": "0",
            "width": "100%",
            "height": "100%",
            "background-color": "rgba(0, 0, 0, 0.8)",
            "z-index": "9998",
            "overflow-y": "auto",
            "display": "none",
            "justify-content": "center",
            "align-items": "center",
            "color": "white" // Couleur du texte en blanc
        });

        var contentContainer = $("<div>").addClass("lightbox-content").css({
            "display": "flex",
            "flex-direction": "column",
            "align-items": "center",
            "position": "relative" // Position relative pour positionner les flèches absolument par rapport à l'image
        });
        lightbox.append(contentContainer);

        var prevButton = $("<button>").addClass('prev-button').css({
            "position": "absolute",
            "top": "50%",
            "left": "150px", // Ajustez cette valeur pour rapprocher la flèche de l'image
            "transform": "translateY(-50%)",
            "z-index": "9999",
            "background": "none",
            "border": "none",
            "cursor": "pointer",
            "padding": "0" // Éviter les marges et les remplissages qui pourraient déformer l'image
        }).html('<img src="' + themeVars.stylesheetDirectoryUri + '/images/gauche2.png" alt="Précédent" style="max-width: 150px; max-height: 40px;">').click(function() {
            if (currentIndex > 0) {
                currentIndex--;
                updateLightboxContent(currentIndex);
            }
        });

        var nextButton = $("<button>").addClass('next-button').css({
            "position": "absolute",
            "top": "50%",
            "right": "150px", // Ajustez cette valeur pour rapprocher la flèche de l'image
            "transform": "translateY(-50%)",
            "z-index": "9999",
            "background": "none",
            "border": "none",
            "cursor": "pointer",
            "padding": "0" // Éviter les marges et les remplissages qui pourraient déformer l'image
        }).html('<img src="' + themeVars.stylesheetDirectoryUri + '/images/droite2.png" alt="Suivant" style="max-width: 150px; max-height: 40px;">').click(function() {
            if (currentIndex < $(".col").length - 1) {
                currentIndex++;
                updateLightboxContent(currentIndex);
            }
        });

        var closeButton = $("<button>").addClass('Fermer').css({
            "position": "fixed",
            "top": "20px",
            "right": "20px",
            "z-index": "9999",
            "background": "none",
            "border": "none",
            "cursor": "pointer",
            "padding": "0" // Éviter les marges et les remplissages qui pourraient déformer l'image
        }).html('<img src="' + themeVars.stylesheetDirectoryUri + '/images/close.png" alt="Fermer" style="max-width: 150px; max-height: 40px;">').click(function() {
            lightbox.hide();
        });

        lightbox.append(prevButton).append(nextButton).append(closeButton);
        $("body").append(lightbox);

        $(".photo-link").click(function(e) {
            e.preventDefault();

            currentIndex = $(this).closest('.col').index();
            updateLightboxContent(currentIndex);
            lightbox.show();
        });

        function updateLightboxContent(index) {
            var container = $(".image-container").eq(index);
            var lightboxContent = container.find(".lightbox").html();
            var titleContent = container.find("h3").clone().wrap('<div>').parent().html(); // Cloner le h3 et l'entourer d'un div pour l'inclure dans le HTML

            var combinedContent = $('<div>').css({
                "display": "flex",
                "flex-direction": "column",
                "align-items": "center",
                "position": "relative"
            });
            var imageWrapper = $('<div>').css({
                "margin-bottom": "20px"
            }).html(lightboxContent);
            var titleWrapper = $('<div>').css({
                "text-align": "center",
                "color": "white" // Couleur du texte en blanc
            }).html(titleContent);

            combinedContent.append(imageWrapper).append(titleWrapper);
            contentContainer.html(combinedContent);
        }
    }
});




const contactPopup= document.querySelector('#contact-popup');
// contact //
const Contact_bouton=  document.querySelector('.open-popup-link');
Contact_bouton.addEventListener('click', function() {

           
            contactPopup.style.display = 'block';
       

        
    
});

window.addEventListener('click', function(event) {
    if (event.target === contactPopup) {
        contactPopup.style.display = 'none';
    }
});

// 2eme bouton contact dans single photo//
document.getElementById('bouton-contact-contenu').addEventListener('click', function() {
    // Ouvrir la pop-up de contact
    document.getElementById('contact-popup').style.display = 'block';
});

// récup mon id pour contact //

jQuery(document).ready(function($) {
    ref=$("#ref");
    if(ref!=null) {
        ref=ref.html();
        console.log(ref)
        $("input[name='ref']").val(ref)
    }
})

// prévisualisation //

document.addEventListener('DOMContentLoaded', function() {
    const leftArrow = document.querySelector('.arrow-left');
    const rightArrow = document.querySelector('.arrow-right');
    const leftThumbnail = document.querySelector('.thumbnail-left');
    const rightThumbnail = document.querySelector('.thumbnail-right');

    if (leftArrow && leftThumbnail) {
        leftArrow.addEventListener('mouseenter', function() {
            leftThumbnail.style.display = 'block';
        });
        leftArrow.addEventListener('mouseleave', function() {
            leftThumbnail.style.display = 'none';
        });
    }

    if (rightArrow && rightThumbnail) {
        rightArrow.addEventListener('mouseenter', function() {
            rightThumbnail.style.display = 'block';
        });
        rightArrow.addEventListener('mouseleave', function() {
            rightThumbnail.style.display = 'none';
        });
    }
});



jQuery(document).ready(function($) {
    // Lorsque le bouton est cliqué
    $("#fullscreen-btn").click(function() {
        // Afficher l'overlay semi-transparent
        $(".fullscreen-overlay").fadeIn();
        
        // Récupérer le contenu de la photo-description
        var content = $(".single-photo-content .photo-details .photo-info .main-content .photo-description").html();
        
        // Afficher le contenu en plein écran
        $(".fullscreen-content").html(content);
    });
    
    // Lorsque l'overlay est cliqué
    $(".fullscreen-overlay").click(function() {
        // Cacher l'overlay
        $(this).fadeOut();
    });
});






