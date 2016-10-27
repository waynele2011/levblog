jQuery(document).ready(function ($) {
    $("ul#static-block").sortable({
        connectWith: "ul.layout-position-container",
        //placeholder: "highlight",
        placeholder: "ui-state-highlight"
    });

    $("ul#content-filter").sortable({
        connectWith: "ul.layout-position-container",
        placeholder: "ui-state-highlight"
    });

    $(".layout-position-container").sortable({
        connectWith: "ul.layout-position-container",
        items: "li",
        placeholder: "highlight",
        receive: function (event, ui) {
            console.log(ui.item);
            //setValue(ui.item);
            setInput(ui.item);
        }
    });

    function setInput(element) {
        var position = $(element).parent().parent().attr('position');
        var identifier = $(element).attr('identifier');

        $(element).find('input').remove();
        $(element).find('a').remove();
        var li = $(element);

        var input = jQuery("<input>");
        input.attr('name', 'layoutdesign[design_frame][' + position + '][]');
        input.attr('value', identifier);
        input.attr('type', 'hidden');
        li.append(input);

        var a_rm = jQuery("<a></a>");
        a_rm.addClass('bt-remove-block');
        a_rm.text('X');
        a_rm.click(function () {
            jQuery(this).parent().remove();
        });
        li.append(a_rm);
    }
    jQuery('.bt-remove-block').click(function () {
        jQuery(this).parent().remove();
    });
});