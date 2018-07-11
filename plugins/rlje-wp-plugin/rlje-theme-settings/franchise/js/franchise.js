var addToWatchlist = function(franchise) {
	$.post('/' + franchise, { franchise: franchise, action: "add" }, function() {
			$('#watchlistActionButton').attr('onclick', 'removeFromWatchlist(\'' + franchise + '\')').html('Remove from Watchlist');
	});
};

var removeFromWatchlist = function(franchise) {
	$.post('/' + franchise, { franchise: franchise, action: "remove" }, function() {
			$('#watchlistActionButton').attr('onclick', 'addToWatchlist(\'' + franchise + '\')').html('Add to Watchlist');
	});
};
