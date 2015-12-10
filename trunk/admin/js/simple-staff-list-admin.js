(function ($) {
    'use strict';


    $(function () {

        // Fix broken table width on sort

        $('.sslp-order td').each(function () {
            $(this).css('width', $(this).width() + 'px');
        });


        // Sortable Table

        if ($('.sslp-order#sortable-table').length > 0) {
            $('.sslp-order#sortable-table tbody').sortable({
                axis: 'y',
                handle: '.column-order img',
                placeholder: 'ui-state-highlight',
                forcePlaceholderSize: true,
                update: function (event, ui) {
                    var theOrder = $(this).sortable('toArray');

                    var data = {
                        action: 'staff_member_update_post_order',
                        postType: $(this).attr('data-post-type'),
                        order: theOrder
                    };

                    $.post(ajaxurl, data);
                }
            }).disableSelection();
        }


        // Collapsible divs on templates page

        $(".content").hide();
        $(".heading").click(function () {
            $(this).next(".content").slideToggle(500);
        });

    });

})(jQuery);
