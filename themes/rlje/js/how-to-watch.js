jQuery(document).ready(function ($) {
    var brightCoveVideo = jQuery(document.createElement('video')).attr({
        'id': 'umc-about',
        'data-video-id': about_video.bc_video_id,
        'data-account': about_video.bc_account_id,
        'data-player': about_video.bc_player_id,
        'data-embed': 'default'
    }).addClass('video-js embed-responsive-item').prop('controls', true);

    //jQuery(document.createElement('div')).addClass('embed-responsive embed-responsive-16by9').append(brightCoveVideo)
    //    .appendTo();
    $('section.intro').append(brightCoveVideo);
    initBrightCove();
});

function initBrightCove() {
    bc(document.getElementById('umc-about'));
    videojs('umc-about').ready(function() {
        myPlayer = this;
        myPlayer.pause();
    })
}