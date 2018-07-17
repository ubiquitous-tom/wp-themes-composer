var episodePlayer = function(episodeId, setTimePosition) {
  var player = videojs('brightcove-episode-player'),
    isPlayingSet = function(item) {
      var isSet = false;
      if (
        docCookies.hasItem('playerOption') &&
        docCookies.getItem('playerOption') === item
      ) {
        isSet = true;
        docCookies.removeItem('playerOption', '/');
      }
      return isSet;
    },
    fromStart = isPlayingSet('playFromStart'),
    resume = isPlayingSet('playResume'),
    initTime = setTimePosition,
    strempositionInterval,
    $continueWatchingBlock = jQuery('.continueWatching'),
    $playNextEpisodeBlock = jQuery('.playNextEpisode'),
    showingExtBtn = true,
    showingNextEpisodePrompt = false,
    isCapturingStreamPosition = false,
    $overlay = jQuery('#nextEpisodeOverlay'),
    $overlayCloned = $overlay.clone();

  if (fromStart) {
    initTime = 0;
  }
  if (resume || fromStart) {
    player.autoplay(true);
    $continueWatchingBlock.hide();
    showingExtBtn = false;
  }

  player.on('loadedmetadata', function() {
    //Set the current time once the initial duration is loaded.
    if (setTimePosition) {
      player.currentTime(setTimePosition);
    }
    if (fromStart) {
      player.currentTime(initTime);
      setStreamPosition('STOP');
    }
  });

  player
    .on('play', function() {
      if (isPlayingSet('playFromStart')) {
        return;
      }
      ///Only fixes a Safari issue (sometimes the player doesn't load the current time).
      if (initTime > 0) {
        var isEnded = initTime > player.duration() - 10 ? true : false;
        if (isEnded) {
          initTime = 0;
        } ///if the video ended, it plays from beginer.
        if (player.currentTime() !== initTime) {
          player.currentTime(initTime);
          console.log('Player: Init time set.');
        }
        initTime = null;
      }
      ///End Safari fix.

      //Checking if the current User is Active
      var data = {
          action: 'is_user_active',
          token: episode_object.token
        },
        stopPlayer = function() {
          player.pause();
        },
        processData = function(response) {
          var data = response.data;
          if (data.isActive !== true) {
            stopPlayer();
            console.error('User not active');
          }
        };

      // This needs to be done differently
      jQuery.post(episode_object.ajax_url, data, processData).fail(function() {
        console.error('Error checking if the user is active');
      });
    })
    .on('playing', function() {
      isPlayingSet('playFromStart');
      if (!isCapturingStreamPosition) {
        isCapturingStreamPosition = true;
        strempositionInterval = setInterval(function() {
          setStreamPosition('PLAYING');
        }, 60000);
      }
      $continueWatchingBlock.hide();
    })
    .on('pause', function() {
      clearInterval(strempositionInterval);
      isCapturingStreamPosition = false;
      setStreamPosition('STOP');
      if (showingExtBtn) {
        $continueWatchingBlock.show();
        $playNextEpisodeBlock.hide();
      } else {
        showingExtBtn = true;
      }
    })
    .on('ended', function() {
      $continueWatchingBlock.hide();
      $playNextEpisodeBlock.show();
    })
    .on('timeupdate', function() {
      if (!player.paused()) {
        var showNextEpisode = player.currentTime() >= player.duration() - 45;
        if (showNextEpisode && !showingNextEpisodePrompt) {
          if (typeof $overlayCloned === 'undefined') {
            $overlayCloned = $overlay.clone();
          }
          if (typeof $overlayCloned[0] !== 'undefined') {
            $overlayCloned.show();
            player.el().appendChild($overlayCloned[0]);
            showingNextEpisodePrompt = true;
            console.log('showing nextEpisode prompt!');
          }
        } else if (!showNextEpisode && showingNextEpisodePrompt) {
          player.el().removeChild($overlayCloned[0]);
          showingNextEpisodePrompt = false;
          console.log('removing nextEpisode prompt!');
        }
      }
    });

  var setStreamPosition = function(lastKnownAction) {
    jQuery.ajax({
      type: 'POST',
      url: episode_object.ajax_url,
      data: {
        action: 'streamposition',
        token: episode_object.token,
        Position: parseInt(player.currentTime(), 10),
        EpisodeID: episodeId,
        LastKnownAction: lastKnownAction
      }
    });
  };
};

var endCookie = new Date();
endCookie.setMinutes(endCookie.getMinutes() + 2);

jQuery('#continueWatching, #brightcove-episode-player')
  .on('click', '.js-play-start', function() {
    docCookies.setItem('playerOption', 'playFromStart', endCookie);
  })
  .on('click', '.js-play-resume', function() {
    docCookies.setItem('playerOption', 'playResume', endCookie);
  });

jQuery('.js-player-start').on('click', function(e) {
  e.preventDefault();
  scrollToPlayer(this);
  docCookies.setItem('playerOption', 'playFromStart', endCookie);
  videojs('brightcove-episode-player')
    .currentTime(0)
    .play();
});

jQuery('.js-player-resume').on('click', function(e) {
  e.preventDefault();
  scrollToPlayer(this);
  videojs('brightcove-episode-player').play();
});

jQuery('.js-play-next').on('click', function(e) {
  docCookies.setItem('playerOption', 'playResume', endCookie);
  window.location = jQuery(this).data('next');
});

jQuery('.js-go-to').on('click', function(e) {
  window.location = jQuery(this).data('url');
});

jQuery('#brightcove-episode-player').on(
  'click',
  '.js-episode-loading',
  function(e) {
    jQuery('.imgOverlayContainer #play-episodes').hide();
    jQuery('.imgOverlayContainer .loading').show();
    setTimeout(function() {
      jQuery('.imgOverlayContainer #play-episodes').show();
      jQuery('.imgOverlayContainer .loading').hide();
    }, 60000);
  }
);
var scrollToPlayer = function(elm) {
  if (typeof jQuery(elm).data('no-scroll') === 'undefined') {
    var position = jQuery('#episode').position();
    window.scroll(0, position.top);
  }
};

(function($) {
  $(document).ready(function() {
    $('.acorntv-slogan button.js-play').on('click', function(e) {
      e.preventDefault();
				$('#brightcove-episode-trailer-player').removeClass('hidden');
				var player = videojs('brightcove-episode-trailer-player');
				player.play();
    });
  });
})(jQuery);
