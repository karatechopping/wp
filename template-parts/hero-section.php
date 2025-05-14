<?php
$fields = get_field('hero_section'); ?>

<style>
/* Styles for the hero section */
.hero-section {
    background-image: url("<?=$fields['hero_image']['url']?>"); /* Replace with your image URL */
    background-size: cover;
    background-position: center center;
    position: relative;
    width: 100vw;
    height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    box-sizing: border-box;
    padding: 40px 20px;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.5));
}

.hero-section a:hover {
    text-decoration: none;
}

.hero-title {
    color: var(--white-color);
    font-size: 44px;
    position: relative;
    text-align: center;
    z-index: 1;
}

.hero-title::after {
    content: '';
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    bottom: -10px;
    height: 3px;
    width: 150px;
}

.hero-paragraph {
    color: var(--white-color);
    font-size: 20px;
    z-index: 1;
    text-align: center;
    margin: 20px 0 40px;
}

.cta-button {
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
    border: none;
    font-size: 18px;
    cursor: pointer;
    z-index: 1;
    text-decoration: none; /* In case it's an anchor link */
}
</style>

<?php
// Check if the 'hero_section' field group is populated and at least one subfield has a value
if( $fields && ( isset($fields['hero_title']) || isset($fields['hero_subtitle']) || isset($fields['hero_button_link']) || isset($fields['hero_button_text']) ) ):
?>
    <div class="hero-section included-section">
        <?php if( isset($fields['hero_title']) ): ?>
            <h1 class="hero-title"><?php echo esc_html($fields['hero_title']); ?></h1>
        <?php endif; ?>
        
        <?php if( isset($fields['hero_subtitle']) ): ?>
            <p class="hero-paragraph"><?php echo esc_html($fields['hero_subtitle']); ?></p>
        <?php endif; ?>
        
        <?php if( isset($fields['hero_button_link']) && isset($fields['hero_button_text']) ): ?>
            <a href="<?php echo esc_url($fields['hero_button_link']); ?>" class="cta-button"><?php echo esc_html($fields['hero_button_text']); ?></a>
        <?php endif; ?>
    </div>
<?php
endif;
?> 