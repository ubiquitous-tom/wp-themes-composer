var membership_cost = 49.99;
var membership_quantity = 1;
var stripe_token;
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
        initializeStripeElements(gift_vars.stripe_key);
        $('#purchase-gift').on('submit', function(event){
            errors = [];
            event.preventDefault();
            jQuery('.alert').remove();
            var email = $('#billing-email').val();
            var email_confirm = $('#billing-email-confirm').val();
            var name_on_card = jQuery('#card-name').val();
            
            stripe.createToken(cardNumber, {
                name: name_on_card
            }).then(function (result) {

                if (email !== email_confirm) {
                    errors.push('Email addresses don\'t match');
                }
                if (result.error) {
                    errors.push(result.error.message);
                } else {
                    stripe_token = result.token.id;
                }
                if(errors.length) {
                    console.log(errors);
                    errors.forEach( function(error) {
                        var alert = $(document.createElement('div')).addClass("alert alert-danger fade in").html(error);
                        alert.insertAfter($('header'));
                    } );
                } else {
                    $('#confirmPurchaseModal').modal();
                }
            });

        });
    });

    $('#confirmPurchase').on('click', purchaseGift);
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

function purchaseGift() {
    jQuery('#confirmPurchaseModal').modal('hide');
    var first_name = jQuery('#billing-first-name').val();
    var last_name = jQuery('#billing-last-name').val();
    var biling_country = jQuery('#billing-country').val();
    var billing_zip = jQuery('#billing-zip').val();
    var email = jQuery('#billing-email').val();
    var name_on_card = jQuery('#card-name').val();

    jQuery.post(
        gift_vars.ajax_url,
        {
            action: 'purchase_gift',
            email: email,
            first_name: first_name,
            last_name: last_name,
            country: biling_country,
            zip: billing_zip,
            card_name: name_on_card,
            stripe_token: stripe_token,
            quantity: membership_quantity
        },
        function (response) {
            if (response.success == false) {
                var alert = jQuery(document.createElement('div')).addClass("alert alert-danger fade in").html(response.error);
                alert.insertAfter(jQuery('header'));
            } else {
                var someul = jQuery(document.createElement('ul'));
                response.codes.forEach( function(promo_code) {
                    good_code = promo_code.GiftCode.Code;
                    someul.append(jQuery(document.createElement('li')).append(good_code));
                } )
                jQuery(document.createElement('div'))
                    .addClass("alert alert-success")
                    .append('Thank you for your purchase! Order id:' + response.order_id)
                    .append('<br>')
                    .append( 'These are your codes. We will send an email containing more information.' )
                    //.append('<br>')
                    .append(someul).insertAfter(jQuery('header'));
                jQuery('#purchase-gift').remove();
                jQuery('header').remove();
            }
        }
    )

}