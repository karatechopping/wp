<style>
/* Styles for the Services Grid */
.services-grid {
    text-align: center;
}
@media(min-width: 1024px){
    .services-grid {
        padding: 60px 0;
    }
}

.services-grid-paragraph {
    color: var(--top-title);
    font-weight: 700;
    line-height: 1.5em;
}

.services-grid-title {
    font-size: 36px;
    position: relative;
    margin-bottom: 50px;
    text-align: center;
}

.services-grid-title::after {
    content: '';
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    bottom: -10px;
    height: 3px;
    width: 150px;
}

.flex-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    width: 100%;
}

.flex-block {
    margin: 10px;
    text-align: center;
    margin-bottom: 3rem;
}
@media(min-width: 1024px){
    .flex-block {
        width: calc(100% / 3 - 20px); /* Subtracting 20px to account for margins */
    }
}

.flex-block img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    margin-bottom: 20px;
}

.flex-block-title {
    font-size: 24px;
    margin-bottom: 15px;
}

.flex-block-description {
    font-size: 19px;
    margin-bottom: 20px;
    min-height: 10rem;
    min-height: 200px;
}
</style>
<?php
    // Fetch the 'services_grid' ACF group
    $services_grid = get_field('services_grid');

    if ($services_grid): ?>

    <div class="services-grid">

        <?php if (isset($services_grid['grid_small_title'])): ?>
            <p class="services-grid-paragraph"><?php echo esc_html($services_grid['grid_small_title']); ?></p>
        <?php endif; ?>

        <?php if (isset($services_grid['grid_title'])): ?>
            <h2 class="services-grid-title"><?php echo esc_html($services_grid['grid_title']); ?></h2>
        <?php endif; ?>

        <?php 
        // Access the repeater field from within the 'services_grid' group
        $grid_services = $services_grid['grid_services']; 
        if ($grid_services): ?>
            <div class="flex-container">
                
                <?php foreach ($grid_services as $service): ?>

                    <div class="flex-block">

                        <?php if (isset($service['service_icon'])): ?>
                            <img src="<?php echo esc_url($service['service_icon']); ?>" alt="<?php echo esc_attr($service['service_title']); ?>">
                        <?php endif; ?>

                        <?php if (isset($service['service_title'])): ?>
                            <h3 class="flex-block-title"><?php echo esc_html($service['service_title']); ?></h3>
                        <?php endif; ?>

                        <?php if (isset($service['service_text'])): ?>
                            <p class="flex-block-description"><?php echo strip_tags($service['service_text']); ?></p>
                        <?php endif; ?>

                        <?php if (isset($service['service_button_text']) && isset($service['service_button_link'])): ?>
                            <a href="<?php echo esc_url($service['service_button_link']); ?>" class="cta-button">
                                <?php echo esc_html($service['service_button_text']); ?>
                            </a>
                        <?php endif; ?>
                        
                    </div>

                <?php endforeach; ?>

            </div>
        <?php endif; ?>

    </div>

    <?php endif; ?>