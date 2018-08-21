$(document).ready(function () {

    const TO_SUBSCRIBE = "Subscribe again" ;
    const TO_UNSUBSCRIBE = "You're subscribed" ;

    $('#btn-ajax').click(function () {
        $.ajax({
            method: 'POST',
            url: '/follow/'+$(this).data('pseudo'),

            data: 'pseudo='+$(this).data('pseudo')

        }).done(function(jqXHR) {

            console.log(jqXHR);

            if(true === jqXHR) {
                $('#btn-ajax').text(TO_UNSUBSCRIBE);

                $('#btn-ajax').addClass('btn btn-success');
            } else {
                $('#btn-ajax').text(TO_SUBSCRIBE);
                $('#btn-ajax').removeClass('btn btn-success');
                $('#btn-ajax').addClass('btn btn-default');
            }

        });
    });

});