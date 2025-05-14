<?php if(get_field('body_text_color','option')):?>
body, body p{color: <?php the_field('body_text_color','option');?>;}
<?php endif; ?>
h1,h2,h3,h4,h5,h6 {
	color:<?php the_field('title_color','option');?>;
}
blockquote:before {
  color: <?php the_field('primary_color','option');?>;
}
a{
	color: <?php the_field('primary_color','option');?>;
}
a:focus{color: <?php the_field('secondary_color','option');?>;}
a:hover{
	color: <?php the_field('secondary_color','option');?>;
}
a:focus{outline: none;}
hr{background: #d1d1d1;}

.cp-color{
	color: <?php the_field('primary_color','option');?>;
}

.backtotop{background:<?php the_field('primary_color','option');?>;}

#cp-header .navbar-nav li a{color:<?php the_field('main_menu_txt_color','option');?> ;}

#cp-top .c-top-left ul li a{color: #fff;}
#cp-top .c-top-left ul li a:hover,#cp-top .c-top-left ul li a:hover i{color: <?php the_field('primary_color','option');?>;}
#cp-top .c-top-left ul li.email i{color: #fff;}
#cp-top .c-top-left ul li.phone i{color: #fff;}

#cp-top .c-top-left ul li a:hover,#cp-top .c-top-left ul li a:hover i{ color: <?php the_field('primary_color','option');?>;}

#cp-top .social ul li{color: #fff;}
#cp-top .social ul li:after{color: rgba(255,255,255,.5);}
#cp-top .social ul li a{color: rgba(255,255,255,.5);}
#cp-top .social ul li a:hover{color: #fff;}

.c-top-right a.account-link:hover{color: <?php the_field('primary_color','option');?>;}

#cp-top .c-top-right a.appointment{background: <?php the_field('primary_color','option');?>;color: #fff;}
#cp-top .c-top-right a.appointment:hover{ background: <?php the_field('secondary_color','option');?>;}

#cp-header a.navbar-brand:hover h2{color: <?php the_field('primary_color','option');?>;}

#cp-header .navbar-nav.submit-listing li a:hover{background: <?php the_field('secondary_color','option');?>; }

.banner-content .banner-btn a.global-btn:hover{color: <?php the_field('secondary_color','option');?>;}

a.global-btn{background: <?php the_field('primary_color','option');?>;}
a.global-btn:hover{border-color: <?php the_field('secondary_color','option');?>;color: <?php the_field('secondary_color','option');?>;}

button.global-btn{background: <?php the_field('primary_color','option');?>;}
button.global-btn:hover{border-color: <?php the_field('secondary_color','option');?>;color: <?php the_field('secondary_color','option');?>;}

a.global-btn.btn-solid{color: <?php the_field('primary_color','option');?>;background: none; border: solid 2px <?php the_field('primary_color','option');?>;}
a.global-btn.btn-solid:hover{color: #fff;background: none; border: solid 2px <?php the_field('primary_color','option');?>; background: <?php the_field('primary_color','option');?>;}

.banner-btn a.global-btn{background: #f5af02;}
.banner-btn a.global-btn:hover{background: <?php the_field('primary_color','option');?>;border-color: <?php the_field('primary_color','option');?>;color: #fff;}

.checklists li i{color: <?php the_field('secondary_color','option');?>;}

.cp-services h4 a:hover{color: <?php the_field('secondary_color','option');?>;}


.blog-item h3 a:hover{color: <?php the_field('primary_color','option');?>;}

.cp-header-title{background-color: <?php the_field('listing_bnbg_color','option');?>;}

.widget .widget-title:after{color: <?php the_field('primary_color','option');?>;}
.blog-date{color: <?php the_field('primary_color','option');?>;}
.widget ul li span{color: <?php the_field('primary_color','option');?>;}

.widget ul li a:hover{color: <?php the_field('primary_color','option');?>;}
.comments-area .submit{background: <?php the_field('primary_color','option');?>;}

.comments-area .submit:hover{background: <?php the_field('secondary_color','option');?>;}

.register-login .global-btn:hover, .acf-form .acf-button:hover{
	background:<?php the_field('primary_color','option');?>;
	border-color:<?php the_field('primary_color','option');?>;
}

.pricing .pricing-widget:hover .pricing-header .price-cost, .pricing .pricing-widget.active .pricing-header .price-cost {
    background-color: <?php the_field('primary_color','option');?>;
}

.pricing .pricing-widget:hover .pricing-content, .pricing .pricing-widget.active .pricing-content {
    background-color: <?php the_field('primary_color','option');?>;
}

.listing-comment .comments-area .submit{
	border:2px solid <?php the_field('primary_color','option');?>;
	background:<?php the_field('primary_color','option');?>;
}
#cp-header .navbar-nav li a:hover,#cp-header .navbar-nav li.current-menu-item a{color:<?php the_field('primary_color','option');?>;}
.listing-comment .comments-area .submit:hover{
	background:<?php the_field('secondary_color','option');?>;
	border-color:<?php the_field('secondary_color','option');?>;
}
.paypal-entry .item-field_price span{color: <?php the_field('secondary_color','option');?>;}
//#cp-header .navbar-nav li a{color:<?php the_field('primary_color','option');?>}

/**@media 991px**/@media ( max-width: 991px ){


	#cp-header .navbar-nav i.toggle-down{background: <?php the_field('primary_color','option');?>;}
	#cp-header .navbar-nav ul li a:hover{color:<?php the_field('primary_color','option');?>;}
	#cp-header .navbar-nav li,#cp-header .navbar-nav li a,
	//#cp-header .navbar-nav li.current-menu-item a{color:<?php the_field('primary_color','option');?> !important;}

}/**end @media 991px**/


body .listing_search button.btn[type="submit"] {
    background: <?php the_field('secondary_color','option');?>;
}
.page-template-pricing #page-banner, .page-template-pay #page-banner {
	background: <?php the_field('ppb_bg_color','option');?>;
}
#cp-top{ background: <?php the_field('menu_bg_color','option');?> !important; }

.cp-header-title span,
a.nlplink{background : <?php the_field('category_backg_color','option'); ?> ;}

.cp-header-title span:hover,
a.nlplink:hover{background : <?php the_field('category_bghover1_color','option'); ?> ;}

.cp-header-title span,
 a.nlplink,.featured-home h3, .featured-home span{color : <?php the_field('category_txt1_color','option'); ?> ;}

#page-banner h1 { color: <?php the_field('page_banner_txt','option');?>}

.cp-header-title span:hover,
a.nlplink:hover,.featured-home h3:hover,.featured-home span:hover{color : <?php the_field('category_txt1_color','option'); ?> !important;}


.hero-title::after,
.services-grid-title::after,
.banner-title::after,
.service-process-heading::after,
.services-grid-title::after,
.service-description-title::after {
	background: <?php the_field('primary_color','option');?>;
}
.cta-button {
	background: <?php the_field('primary_color','option');?>;
}

.custom-tab-wrapper #tab-btn-1:checked + label,
.custom-tab-wrapper #tab-btn-2:checked + label,
.custom-tab-wrapper #tab-btn-3:checked + label { background-color: <?php the_field('primary_color','option'); ?> !important; }