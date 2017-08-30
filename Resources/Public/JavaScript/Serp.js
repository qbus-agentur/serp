define(['jquery'], function($) {
	$.fn.serpSnippet = function(options) {
		// Establish our default settings
		var settings = $.extend({
			title: " -- No title set --",
			url: "www.example.com/example",
			description: "-- No description set --",
			search: ""
		}, options);

		var DESC_LENGHT = 156;

		function truncate(text, maxLength) {
			if (text.length > maxLength) {
				text = text.substr(0,maxLength-3) + "...";
			}
			return text;
		}

		function highlight(text, keyword) {
			var rgxp = new RegExp(keyword, 'g');
			var repl = '<strong>' + keyword + '</strong>';
			return text.replace(rgxp, repl);
		}

		return this.each( function() {
			// Create the html to insert on target element
			var html = "";
			html += "<div class='serpSnippet'>";
			html += "<div class='snippetContainer'>";

			html += "<div class='title'>" + "<a href='#'>" + highlight(settings.title, settings.search) + "</a>" + "</div>";
			html += "<div class='url'>" + settings.url + "</div>";
			html += "<div class='description'>" + truncate(settings.description, DESC_LENGHT) + "</div>";

			html += "</div>";
			html += "</div>";

			// Insert the html created on target element
			$(this).html(html);
		});
	}

	$('.t3js-serp-wizard').each(function() {
		var $this = $(this);
		var config = $this.data('config');

		var config = $.extend({
			title: 'title',
			description: null,
			titleOverride: null,
			titlePrefix: '',
			titleSuffix: '',
			defaultUrl: '',
		}, config);

		var fieldConfigs = {
			title: config.title,
			description: config.description,
			titleOverride: config.titleOverride
		};
		var fields = {};

		$.each(fieldConfigs, function(key, name) {
			if (name !== null) {
				fields[key] = $('.t3js-formengine-field-item *[data-formengine-input-name*="[' + name + ']"]');
				if (fields[key].prop('tagName') === 'INPUT') {
					fields[key + '_initial'] = $('input[type=hidden][name*="[' + name + ']"]');
				} else {
					fields[key + '_initial'] = fields[key];
				}
			} else {
				fields[key] = $();
			}
		});

		var func = function(event, suffix) {
			suffix = suffix || '';

			var title = config.defaultTitle;
			if (fields['title' + suffix].val()) {
				title = config.titlePrefix + fields['title' + suffix].val() + config.titleSuffix;
			}
			title = fields['titleOverride' + suffix].val() || title;

			var description = fields['description' + suffix].val() || config.defaultDescription

			$this.serpSnippet({
				title: title,
				description: fields['description' + suffix].val() || config.defaultDescription,
				url: config.defaultUrl
			});
		};

		fields.title.on('input change', func);
		fields.description.on('input change', func);
		fields.titleOverride.on('input change', func);
		func(null, '_initial');
	});
});
