jQuery(document).ready(function () {
    jQuery("ol.sub-menu-taxonomies li.parent").mouseenter(function () {
        jQuery(this).find("ul.children").show();
    });
    jQuery("ol.sub-menu-taxonomies li.parent").mouseleave(function () {
        jQuery(this).find("ul.children").hide();
    });
});
