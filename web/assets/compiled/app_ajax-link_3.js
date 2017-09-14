$(document).ready(function () {
    $('body')
        .on('click', 'a[href].ajax', function (e) {
            e.preventDefault();


            var $link = $(this);
            // var $head = document.h1.innerHTML;
            // var $item = document.getElementById('app_product_form_order_message');
            // $item.value =$head;


            if ($link.data('clicked')) {
                return;
            }

            var linkContent = $link.html();
            $link.data('clicked', true);

            $.ajax({
                url: $link.attr('href')
            }).done(function (html) {

                $('body').append(
                    '<div class="window" id="window">' +
                        '<div class="container" id="window-container">' +
                            '<a href="#" class="wind_close"><img src="/assets/images/wind_close.png" alt="" title=""/></a>' +
                            html +
                        '</div>' +
                    '</div>'+
                    '<div class="overlay_dark"></div>'
                );
                $('#app_product_form_order_message').val($('h1').text());

            }).always(function () {
                $link.removeData('clicked').html(linkContent);
            }).fail(onAjaxFail);
        })
        .on('click', '#window', function (e) {
            if (e.target === this) {
                $(this).find('.wind_close:first').trigger('click');
            }
        })
        .on('click', '#window .wind_close', function (e) {
            e.preventDefault();

            $('#window').remove();

            $('.overlay_dark').remove();

        });

});
