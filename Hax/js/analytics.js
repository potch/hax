window.hacks = window.hacks || {};

/**
 * Returns true or false based on whether doNotTrack is enabled. It also takes into account the
 * anomalies, such as !bugzilla 887703, which effect versions of Fx 31 and lower. It also handles
 * IE versions on Windows 7, 8 and 8.1, where the DNT implementation does not honor the spec.
 * @see https://bugzilla.mozilla.org/show_bug.cgi?id=1217896 for more details
 * @params {string} [dnt] - An optional mock doNotTrack string to ease unit testing.
 * @params {string} [ua] - An optional mock userAgent string to ease unit testing.
 * @returns {boolean} true if enabled else false
 */
hacks.dntEnabled = function(dnt, ua) {
    'use strict';

    // for old version of IE we need to use the msDoNotTrack property of navigator
    // on newer versions, and newer platforms, this is doNotTrack but, on the window object
    // Safari also exposes the property on the window object.
    var dntStatus = dnt || navigator.doNotTrack || window.doNotTrack || navigator.msDoNotTrack;
    var userAgent = ua || navigator.userAgent;

    // List of Windows versions known to not implement DNT according to the standard.
    var anomalousWinVersions = ['Windows NT 6.1', 'Windows NT 6.2', 'Windows NT 6.3'];

    var fxMatch = userAgent.match(/Firefox\/(\d+)/);
    var ieRegEx = /MSIE|Trident/i;
    var isIE = ieRegEx.test(userAgent);
    // Matches from Windows up to the first occurance of ; un-greedily
    // http://www.regexr.com/3c2el
    var platform = userAgent.match(/Windows.+?(?=;)/g);

    // With old versions of IE, DNT did not exist so we simply return false;
    if (isIE && typeof Array.prototype.indexOf !== 'function') {
        return false;
    } else if (fxMatch && parseInt(fxMatch[1], 10) < 32) {
        // Can't say for sure if it is 1 or 0, due to Fx bug 887703
        dntStatus = 'Unspecified';
    } else if (isIE && platform && anomalousWinVersions.indexOf(platform.toString()) !== -1) {
        // default is on, which does not honor the specification
        dntStatus = 'Unspecified';
    } else {
        // sets dntStatus to Disabled or Enabled based on the value returned by the browser.
        // If dntStatus is undefined, it will be set to Unspecified
        dntStatus = { '0': 'Disabled', '1': 'Enabled' }[dntStatus] || 'Unspecified';
    }

    return dntStatus === 'Enabled' ? true : false;
};


(function(win, doc, $) {
    'use strict';

    // If doNotTrack is not enabled, it is ok to add Google Analytics
    // @see https://bugzilla.mozilla.org/show_bug.cgi?id=1217896 for more details
    if (typeof hacks.dntEnabled === 'function' && !hacks.dntEnabled()) {
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-35433268-8'],
                  ['_setAllowAnchor', true]);
        _gaq.push (['_gat._anonymizeIp']);
        _gaq.push(['_trackPageview']);
        _gaq.push( removeUtms );
        (function(d, k) {
          var ga = d.createElement(k); ga.type = 'text/javascript'; ga.async = true;
          ga.src = 'https://ssl.google-analytics.com/ga.js';
          var s = d.getElementsByTagName(k)[0]; s.parentNode.insertBefore(ga, s);
        })(document, 'script');
    }

    // Adding to globally available hacks object
    var analytics = hacks.analytics = {
        /*
            Tracks generic events passed to the method
        */
        trackEvent: function(eventArray, callback) {
            // Submit eventArray to GA and call callback only after tracking has
            // been sent, or if sending fails.
            //
            // callback is optional.
            //
            // Example usage:
            //
            // $(function() {
            //            var handler = function(e) {
            //                     var self = this;
            //                     e.preventDefault();
            //                     $(self).off('submit', handler);
            //                     gaTrack(
            //                            ['Newsletter Registration', 'submit'],
            //                            function() { $(self).submit(); }
            //                     );
            //            };
            //            $(thing).on('submit', handler);
            // });

            var _gaq = win._gaq;
            var timeout;
            var timedCallback;

            // Create the final event array
            eventArray  = ['_trackEvent'].concat(eventArray);

            // Create the timed callback if a callback function has been provided
            if (typeof(callback) == 'function') {
                timedCallback = function() {
                    clearTimeout(timeout);
                    callback();
                };
            }

            // If Analytics has loaded, go ahead with tracking
            if (_gaq && _gaq.push) {
                // Send event to GA
                _gaq.push(eventArray);
                // Only set up timeout and hitCallback if a callback exists.
                if (timedCallback) {
                    // Failsafe - be sure we do the callback in a half-second
                    // even if GA isn't able to send in our trackEvent.
                    timeout = setTimeout(timedCallback, 500);

                    // But ordinarily, we get GA to call us back immediately after
                    // it finishes sending our things.
                    // https://developers.google.com/analytics/devguides/collection/gajs/#PushingFunctions
                    // This is called after GA has sent the current pending data:
                    _gaq.push(timedCallback);
                }
            }
            else if(callback) {
                // GA disabled or blocked or something, make sure we still
                // call the caller's callback:
                callback();
            }
        },

        /*
            Track all outgoing links
        */
        trackOutboundLinks: function(target) {
            $(target).on('click', 'a', function (e) {
                // If we explicitly say not to track something, don't
                if($(this).hasClass('no-track')) {
                    return;
                }

                var host = this.hostname;

                if(host && host != location.hostname) {
                    var newTab = (this.target == '_blank' || e.metaKey || e.ctrlKey);
                    var href = this.href;
                    var callback = function() {
                        location = href;
                    };
                    var data = ['Outbound Links', href];

                    if (newTab) {
                        analytics.trackEvent(data);
                    } else {
                        e.preventDefault();
                        analytics.trackEvent(data, callback);
                    }
                }
            });
        },

        /*
            Track all socialshare clicks
        */
        trackSocialShareClicks: function() {
          $('.share').click(function(){
            if ($(this).find('.socialshare').hasClass('open')) {
              analytics.trackEvent(['socialshare', 'close']);
            } else {
              analytics.trackEvent(['socialshare', 'open']);
            }
          });
        }
    };

    $(doc).ready(function(){
      analytics.trackSocialShareClicks();
      analytics.trackOutboundLinks(doc.body);
    });

})(window, document, jQuery);

