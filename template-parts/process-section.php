<style>
/* Styles for the service-process section */
.service-process {
    max-width: 800px;
    margin: auto;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.service-process-heading {
    font-size: 36px;
    text-align: center;
    position: relative;
    margin-bottom: 50px;
}

.service-process-heading::after {
    content: '';
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    bottom: -10px;
    height: 3px;
    width: 120px;
}

.process-row {
    display: flex;
    align-items: center;
    margin-bottom: 30px; /* Spacing between each row */
}
.process-icon {
    width: 70px;
    height: auto;
    border-radius: 50%;
    margin-right: 20px; /* Spacing between the icon and text */
}

.process-content {
    max-width: 75%;
}
.process-content h3 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}

.process-content p {
    font-size: 19px;
}

/* Responsive styles */
@media (max-width: 768px) {
    .service-process-heading {
        font-size: 28px;
    }

    .process-icon {
        width: 50px;
        margin-right: 15px;
    }

    .process-content h3 {
        font-size: 20px;
    }

    .process-content p {
        font-size: 17px;
    }
}
</style>

<?php
// Check if the function exists and the fields are set
if (function_exists('get_field')):

$process_section = get_field('process_section');
?>

<div class="service-process">
    <?php 
    // Fetch the section title from within the process_section group
    $process_section_title = $process_section['section_process_title'];
    
    // Display the section title if it exists
    if ($process_section_title): ?>
        <h2 class="service-process-heading"><?php echo esc_html($process_section_title); ?></h2>
    <?php endif; ?>

    <?php 
    // Check if process items exist
    if (!empty($process_section['process_item'])):
        // Loop through each process item
        foreach ($process_section['process_item'] as $item):
            
            // Fetch the image and description from within the process_item subfield
            $process_icon = $item['process_icon'];
            $process_description = $item['process_description']; ?>
    
            <div class="process-row">
                <div class="process-image">
                    <?php 
                    // Display the icon if it exists
                    if ($process_icon): ?>
                        <img src="<?php echo esc_url($process_icon['sizes']['thumbnail']); ?>" alt="<?php echo esc_attr($process_icon['alt']); ?>" class="process-icon">
                    <?php endif; ?>
                </div>
                <div class="process-content">
                    <?php 
                    // Display the process description if it exists
                    if ($process_description): ?>
                        <?php echo $process_description; ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php 
        endforeach;
    endif; ?>
</div>

<?php
endif;
?>