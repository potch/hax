<form id="newsletterForm" name="newsletter-form" class="newsletter block block--1 block--polite" action="https://www.mozilla.org/en-US/newsletter/" method="post">
  <h2 class="heading">Learn the best of web development</h2>
  <p class="newsletter__description">Sign up for the Mozilla Developer Newsletter:</p>
  <input id="fmt" name="fmt" value="H" type="hidden">
  <input id="newsletterNewslettersInput" name="newsletters" value="app-dev" type="hidden">

  <div id="newsletterErrors" class="newsletter__errors"></div>

  <div id="newsletterEmail" class="form__row">
    <label for="newsletterEmailInput" class="offscreen">E-mail</label>
    <input id="newsletterEmailInput" name="email" class="newsletter__input" required="" placeholder="you@example.com" size="30" type="email">
  </div>

  <div id="newsletterPrivacy" class="form__row form__fineprint">
    <input id="newsletterPrivacyInput" name="privacy" required="" type="checkbox">
    <label for="newsletterPrivacyInput">
      I'm okay with Mozilla handling my info as explained in this <a href="https://www.mozilla.org/privacy/">Privacy Policy</a>.
    </label>
  </div>
  <button id="newsletter-submit" type="submit" class="button positive">Sign up now</button>
</form>
<div id="newsletterThanks" class="newsletter newsletter--thanks block block--1 block--polite hidden">
  <h2 class="heading">Thanks! Please check your inbox to confirm your subscription.</h2>
  <p>If you haven’t previously confirmed a subscription to a Mozilla-related newsletter you may have to do so. Please check your inbox or your spam filter for an email from us.
  </p>
</div>
