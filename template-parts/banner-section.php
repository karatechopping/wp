<style>
/* Styles for the banner section */
.banner-section {
    background-image: url('path_to_your_background_image.jpg'); /* Replace with your image URL */
    background-size: cover;
    background-position: center center;
    position: relative;
    width: 100vw;
    padding: 60px 0; /* Adjust based on your preferred vertical spacing */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.banner-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.5));
    z-index: 1; /* Ensuring it's below the content */
}

.banner-title {
    color: var(--white-color);
    font-size: 36px;
    z-index: 2;
    position: relative;
    text-align: center;
    margin-bottom: 20px;
}

.banner-title::after {
    content: '';
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    bottom: -10px;
    height: 3px;
    width: 120px;
}

.banner-paragraph {
    color: var(--white-color);
    font-size: 20px;
    z-index: 2;
    text-align: center;
    max-width: 80%; /* Adjust based on your preferred width */
    margin-bottom: 20px;
}

</style>

<?php
// Fetch the field group data and store in a variable
$bannerFields = get_field('banner_section');

// Check if the field group has values before outputting the HTML
if($bannerFields):
?>
<div class="banner-section" style="background-image: url('<?php echo esc_url($bannerFields['banner_image']['url']); ?>')">

    <?php if(isset($bannerFields['banner_title'])): ?>
        <h2 class="banner-title"><?php echo esc_html($bannerFields['banner_title']); ?></h2>
    <?php endif; ?>

    <?php if(isset($bannerFields['banner_subtitle'])): ?>
        <p class="banner-paragraph"><?php echo esc_html($bannerFields['banner_subtitle']); ?></p>
    <?php endif; ?>

    <?php if(isset($bannerFields['banner_button_text']) && isset($bannerFields['banner_button_link'])): ?>
        <a href="<?php echo esc_url($bannerFields['banner_button_link']['url']); ?>" target="<?php echo $bannerFields['banner_button_link']['target']; ?>" class="cta-button">
            <?php echo esc_html($bannerFields['banner_button_text']); ?>
        </a>
    <?php endif; ?>

</div>
<?php
endif;
?>