<style>
/* Styles for the Service Description section */

@media(min-width: 1024px){
    .service-section {
        padding: 60px 0;
    }
}
.service-row {
    display: flex;
    align-items: center;
    flex-direction: row;
    flex-wrap: wrap; /* Wrap for mobile responsiveness */
    margin-bottom: 40px; /* Spacing between each row */
}

.service-content,
.service-image {
    flex: 1;
    padding: 15px; /* Providing some spacing */
    max-width: 50%;
    width: 50%;
}

.service-content h2,
.service-description-title {
    font-size: 36px;
    position: relative;
    margin-bottom: 20px;
    text-align: center;
    margin-bottom: 2.5rem;
}

.service-description-title::after {
    content: '';
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    bottom: -10px;
    height: 3px;
    width: 120px;
    background-color: <?php the_field('primary_color','option');?>;
}

.service-content h2::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    bottom: -10px;
    height: 3px;
    width: 130px;
    background-color: <?php the_field('primary_color','option');?>;
}

.service-content p {
    font-size: 19px;
    margin-bottom: 20px;
}

.service-button {
    padding: 10px 20px;
    background-color: #f06;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none; /* In case it's an anchor link */
}

.service-image img {
    width: 100%;
    height: auto;
    border-radius: 20px;
}

/* For mobile responsiveness */
@media (max-width: 768px) {
    .service-row {
        flex-direction: column;
    }
    .service-content,
    .service-image {
        order: 2;
    }
    .service-image {
        order: 1;
        margin-bottom: 20px; /* Space between image and content on mobile */
    }
    /* Swap the order for alternating layouts */
    .service-row:nth-child(even) .service-content {
        order: 2;
    }
    .service-row:nth-child(even) .service-image {
        order: 1;
    }
}
</style>

<?php
$servicesFields = get_field('service_description');

if ($servicesFields && isset($servicesFields['service_row']) && !empty($servicesFields['service_row'])): ?>

<div class="service-section">

    <?php if (isset($servicesFields['service_description_title'])): ?>
        <h2 class="service-description-title"><?php echo esc_html($servicesFields['service_description_title']); ?></h2>
    <?php endif; ?>
    
    <?php foreach ($servicesFields['service_row'] as $index => $row): ?>

        <div class="service-row">

            <!-- Depending on even or odd rows, we change the order of image and content -->
            <?php if ($index % 2 == 0): // Even rows ?>
            
                <div class="service-content">
                    <?php echo $row['service_description_item']; ?>
                </div>

                <?php if (isset($row['service_image']) && !empty($row['service_image'])): ?>
                    <div class="service-image">
                        <img src="<?php echo esc_url($row['service_image']['sizes']['medium_large']); ?>" alt="<?php echo esc_attr($row['service_image']['alt']); ?>">
                    </div>
                <?php endif; ?>

            <?php else: // Odd rows ?>

                <?php if (isset($row['service_image']) && !empty($row['service_image'])): ?>
                    <div class="service-image">
                        <img src="<?php echo esc_url($row['service_image']['url']); ?>" alt="<?php echo esc_attr($row['service_image']['alt']); ?>">
                    </div>
                <?php endif; ?>

                <div class="service-content">
                    <?php echo $row['service_description_item']; ?>
                </div>

            <?php endif; ?>

        </div>

    <?php endforeach; ?>

</div>

<?php endif; ?>