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