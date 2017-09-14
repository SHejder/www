/***************************************************************************/
/********       Scroll Top        ********/
/***************************************************************************/
$(document).on('click', '#scrollTop', function () {
 $("body:not(:animated)").animate({ scrollTop: 0 }, 500);
 $("html").animate({ scrollTop: 0 }, 500);
 return false;
});

$(window).scroll(function(){    
    if ($(this).scrollTop()>400 && true ){
        $("#scrollTop").css({"display":"block"});
    } else {
  $("#scrollTop").css({"display":"none"});
    }
});


/***************************************************************************/
/********       Bigpig        ********/
/***************************************************************************/
if($('.bigpic').length){
  $('.bigpic').each(function(){
	$(this).parent().css('-o-transition','all 0s ease 0s');
	$(this).parent().css('-moz-transition','all 0s ease 0s');
	$(this).parent().css('-webkit-transition','all 0s ease 0s');
	$(this).parent().css('transition','all 0s ease 0s');
	$(this).parent().on('click', function(){
    return hs.expand(this);
   })
  })
 }


/***************************************************************************/
/*******      Images to backgound       ********/
/***************************************************************************/
if($('.slide')){
  $('.slide').css('background-image', 'url(' + $('.slide').find('img').attr('src') + ')' );
  // Дополнительно в стилях для .slide:
  // background-position: center center;
  // background-size: cover;
  $('.slide').find('img').css('opacity', '0' );
};


if($('.inner .news')){
 $('.inner .news .image ').each(function(){
  $(this).css('background-image', 'url(' + $(this).find('img').attr('src') + ')' );
  $(this).css('background-position', 'center center' );
  $(this).css('background-size', 'cover' );
  $(this).find('img').css('opacity', '0' );
 });
};
$(document).ready(function () {
    $('body').on('submit', 'form[action][method].ajax', function (e) {
        e.preventDefault();

        var $form = $(this);

        if ($form.data('submitted')) {
            return;
        }

        ajaxSubmit($form.data('submitted', true));
    });

    $('body').on('click', '.ajax_hidden_form', function (e) {
        var form_id = $(this).data('form-id');
        $('#'+form_id).submit();
        return false;
    });
});

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

// Constants
var NOTY_TIMEOUT = 1000;

// Functions
var notify = function (text, type) {
    if (!text) {
        return;
    }

    type = type || 'success';

    new Noty({
        text:    Translator.trans(text),
        type:    type,
        timeout: NOTY_TIMEOUT
    }).show();
};

var onAjaxFail = function (jqXHR) {
    var translation = 'error.' + jqXHR.status;
    var text = Translator.trans(translation);

    notify(text !== translation ? text : [jqXHR.status, jqXHR.statusText].join(' '), 'error');
};

var ajaxSubmit = function (form) {
    var $form = $(form);

    var $submit       = $form.find('button[type="submit"]:first');
    var submitContent = null;

    $.ajax({
        url:         $form.attr('action'),
        type:        $form.attr('method'),
        data:        new FormData($form[0]),
        processData: false,
        contentType: false
    }).done(
        /**
         * @param data.html
         * @param data.message
         * @param data.redirectUrl
         * @param data.success
         */
        function (data) {
            notify(data.message, data.success ? 'success' : 'error');

            if (data.redirectUrl) {
                setTimeout(function () {
                    location.href = data.redirectUrl;
                }, NOTY_TIMEOUT);

                return;
            }
            if (data.success) {
                var event = new $.Event('app.ajaxSubmit');

                $form.trigger(event, data);

                if (event.isDefaultPrevented()) {
                    return;
                }
            }
            if (data.html) {
                var $html = $(data.html);
                var $replacement = $html.is('form') ? $html : $html.find('form:first');

                $form.replaceWith($replacement.length ? $replacement : $html);
            }
        }
    ).always(function () {
        $form.removeData('submitted');

        if ($submit.length) {
            $submit.html(submitContent);
        }
    }).fail(onAjaxFail);
};
