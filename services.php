<?php
/**
 *  
 * Template Name: Services Template
 *
 * Todo
 * 1. Replace the colors to use the colors set in the theme
 * 2. Replace the cta-button color
 * 3. .service-process-heading::after color php
 * 4. .banner-title::after color php
 */

get_header(); ?>

<style>
    :root {
    --white-color: #ffffff;
    --top-title: #131313;
    }

    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .main-content {
        max-width: 1200px;
        width: 100%;
        display: flex;
        flex-direction: column;
        padding: 20px;
        box-sizing: border-box;
    }

    .section {
        padding: 20px 0;
        border-bottom: 1px solid #ddd;
    }

    .cta-button:hover {
        text-decoration: none;
    }

</style>

<body>
    <div class="container">

        <?php get_template_part('template-parts/hero-section'); ?>
        
        <div class="main-content">
            
            <?php get_template_part('template-parts/services-grid'); ?>

            <?php get_template_part('template-parts/service-description'); ?>
  
            <?php get_template_part('template-parts/process-section'); ?>

        </div> <!-- END main-content -->

        <?php get_template_part('template-parts/banner-section'); ?>

    </div> 
</body>

<?php get_footer();