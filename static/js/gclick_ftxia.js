; (function(document, $) {
	$(document).on("mouseup.addtklink", "a",
	function() {
		var $$ = $(this),
		url = $$.prop("href"),
		isrc;
		if (/^http\:\/\/click\.ftxia/.test(url) || !(/^http\:\/\/g\.click/.test(url))) {
			return;
		}
		$$.prop("target", "_blank");
		var link = "http://click.ftxia.com/gclick/item?url=" + encodeURIComponent(url);
		$$.prop("href", link);
	})
})