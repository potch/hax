(function () {
  var newsletterEl = document.querySelector('#newsletterForm');
  var thanksEl = document.querySelector('#newsletterThanks');

  newsletterEl.addEventListener('submit', submitNewsletter);

  function submitNewsletter(e) {
    var skipXHR = newsletterEl.getAttribute('data-skip-xhr');
    if (skipXHR) {
      return true;
    }
    e.preventDefault();
    e.stopPropagation();

    newsletterEl.classList.add('waiting');

    var fmt = newsletterEl.querySelector('#fmt').value;
    var email = newsletterEl.querySelector('#newsletterEmailInput').value
    var newsletter = newsletterEl.querySelector('#newsletterNewslettersInput').value;
    var privacy = newsletterEl.querySelector('input[name="privacy"]:checked') ? '&privacy=true' : '';
    var params = 'email=' + encodeURIComponent(email) +
                 '&newsletters=' + newsletter +
                 privacy +
                 '&fmt=' + fmt +
                 '&source_url=' + encodeURIComponent(window.location.href);

    var xhr = new XMLHttpRequest();

    var errors = [];

    xhr.onload = function(r) {
      if (r.target.status >= 200 && r.target.status < 300) {
        // response is null if handled by service worker
        if(response === null) {
          newsletterError();
          return;
        }
        var response = r.target.response;
        if (response.success === true) {
          newsletterThanks();
        } else {
          if(response.errors) {
            for (var i = 0, l = response.errors.length; i < l; i++) {
              errors.push(response.errors[i]);
            }
          }
          newsletterError();
        }
      } else {
        newsletterError();
      }
    };

    xhr.onerror = function() {
      newsletterError();
    };

    var url = newsletterEl.getAttribute('action');

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With','XMLHttpRequest');
    xhr.timeout = 5000;
    xhr.ontimeout = newsletterError;
    xhr.responseType = 'json';
    xhr.send(params);

    return false;
  }

  function newsletterError(errors) {
    // Incomplete and expedient.
    newsletterEl.setAttribute('data-skip-xhr', true);
    newsletterEl.submit();
  }

  function newsletterThanks() {
    newsletterEl.classList.add('hidden');
    newsletterEl.setAttribute('disabled', 'disabled');
    thanksEl.classList.remove('hidden');
  }
})();
