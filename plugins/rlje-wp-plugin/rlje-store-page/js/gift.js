var membership_cost = 49.99;
var membership_quantity = 1;
jQuery(document).ready(function ($) {
    $('#membership-cost').html(membership_cost);
    $('.checkout-total span').html($('#gift-quantity').val() * membership_cost);

    $('#gift-quantity').change(function (event) {
        membership_quantity = Number($(this).val());
        $('.checkout-total span').html(membership_quantity * membership_cost);
    });

    $('.checkout-total button').on('click', function() {
        var page_body = $('.page-body .container');
        page_body.remove();
        //console.log(tmpl("tmpl-demo"));
        $('.page-body').html(tmpl("tmpl-demo", {
            "cost": membership_cost,
            "quantity": membership_quantity
        }));
        initializeStripeElements(gift_vars.stripe_key)
    });
});

function initializeStripeElements(stripeKey) {
    stripe = Stripe(stripeKey);
    var style = {
        base: {
            lineHeight: '20px',
            fontFamily: 'Avenir, Nunito, sans-serif',
            fontSize: '14px',
        }
    }
    var elements = stripe.elements();

    cardNumber = elements.create('cardNumber', { 
        style: style
    });
    cardNumber.mount('#card-number');

    cardExpiray = elements.create('cardExpiry', {
        style: style
    });
    cardExpiray.mount('#card-expiration');

    cardCvc = elements.create('cardCvc', {
        style: style
    });
    cardCvc.mount('#card-cvc');

    cardNumber.addEventListener('change', function (event) {
        console.log(event);
    })

}