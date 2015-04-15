$(function(){
    $('.champion').hover(function(){
        $('.champion.hover').each(function(){
            $(this).removeClass('hover');
        });

        $(this).addClass('hover');
    });
});