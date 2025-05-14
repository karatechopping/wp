$window = jQuery(window), jQuery(document).ready((function() {
    $window.width(), jQuery(".menu-item-has-children").each((function() {
        jQuery(this).find("a:first").append('<i class="fa fa-angle-down" />')
    })), jQuery("li .menu-item-has-children").each((function() {
        jQuery(this).find("a:first").append('<i class="fa fa-angle-right" />')
    })), jQuery(".menu-item-has-children").each((function() {
        jQuery(this).prepend('<i class="fa fa-chevron-down toggle-down"></i><i class="fa fa-chevron-up toggle-down"></i>')
    })), jQuery("#top-menu > li > .toggle-down").click((function() {
        if(jQuery(this).parent().hasClass("active")){
    	    jQuery(this).parent().toggleClass("active"); 
            jQuery(this).parent().find("ul.sub-menu:first").slideToggle(200);
        }else{
            jQuery('#top-menu > li').removeClass('active');
            jQuery('#top-menu > li ul.sub-menu').hide();
            jQuery(this).parent().toggleClass("active"); 
            jQuery(this).parent().find("ul.sub-menu:first").slideToggle(200);
        }
    })),jQuery("ul.sub-menu li > .toggle-down").click((function() {
        jQuery(this).parent().toggleClass("active"); 
        jQuery(this).parent().find("ul.sub-menu:first").slideToggle(200);
    })),    
     jQuery(window).scroll((function() {
        jQuery("#cp-header").hasClass("sticky_header") && (jQuery(window).scrollTop() >= 200 ? jQuery("#cp-header.sticky_header").addClass("active") : jQuery("#cp-header.sticky_header").removeClass("active"))
    })).scroll(), jQuery("ul.checklists li").prepend('<i class="fa fa-check" />'), jQuery(".carousel").carousel({
        interval: 5e3,
        pause: "hover"
    }), jQuery((function() {
        jQuery("a[href*=\\#]:not([href=\\#])").click((function() {
            if (location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") && location.hostname == this.hostname) {
                var e = jQuery(this.hash);
                if ((e = e.length ? e : jQuery("[name=" + this.hash.slice(1) + "]")).length) return jQuery("html,body").animate({
                    scrollTop: e.offset().top
                }, 1e3), !1
            }
        }))
    })), jQuery('[data-toggle="tooltip"]').tooltip(), jQuery(".microblading-section").mousemove((function(e) {
        var i = -1 * e.pageY / 20;
        jQuery(this).css("background-position", "0px " + i + "px")
    })), jQuery(".backtotop").css({
        right: "-50px"
    }), jQuery(window).scroll((function() {
        jQuery(window).scrollTop() > 500 ? jQuery(".backtotop").css({
            right: "0"
        }) : jQuery(".backtotop").css({
            right: "-50px"
        })
    }));
}));