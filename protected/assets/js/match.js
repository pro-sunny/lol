$(function(){
    $('.champion').click(function(){
        if ($(this).hasClass('disabled')) {
            return false;
        }
        $('.champion').each(function(){
            $(this).find('.card').removeClass('z-depth-4');
        });
        $('.champion.selected').removeClass('selected');

        $(this).addClass('selected');
        $(this).find('.card').addClass('z-depth-4');
    });

    $('.lock_in').click(function(){

        if( !$('.champion.selected').length ){
            return false;
        }

        var id = $('.champion.selected').attr('id');

        $('.champion').each(function(){
            if( !$(this).hasClass('selected') ){
                $(this).addClass('disabled');
            }
        });

        $.post('match/checkAnswer', {id:id}, function(data){
            var counter = 4;
            var i = setInterval(function(){
                $('.rank').html(counter - 1).show();
                counter--;
                if(counter === 0) {
                    clearInterval(i);

                    $('.lock_in').hide();
                    $('.next_fight').show();
                    $('.rank').html(data.rank);
                    $('.status_'+data.status).show();
                    $('.message').html('<em>'+data.message+'</em>');
                }
            }, 1000);


        }, 'json');
        return false;
    })
})