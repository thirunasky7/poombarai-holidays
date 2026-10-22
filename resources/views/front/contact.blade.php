@extends('layouts.site')

@push('head')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'FAQPage',
  'mainEntity' => $faqs->map(fn ($faq) => [
      '@type' => 'Question',
      'name' => $faq->question,
      'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
  ])->values(),
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
  <div class="page-banner">
    <img src="{{ $page->bannerUrl() }}" alt="{{ $page->banner_alt }}" width="1600" height="900" decoding="async">
  </div>
  <header class="page-intro">
    <div class="wrap">
      <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> <span aria-hidden="true">/</span> <span aria-current="page">Contact</span></nav>
      <p class="eyebrow">{{ $page->eyebrow }}</p>
      <h1>{{ $page->h1 }}</h1>
      <p class="lede">{{ $page->intro }}</p>
    </div>
  </header>
  <section class="band" style="padding-top:0.5rem" aria-labelledby="contact-form-heading">
    <div class="wrap contact-layout">
      <div>
        <div class="contact-actions">
          <a class="btn btn-wa" data-enquiry-type="general" data-item-name="Contact WhatsApp button" href="https://wa.me/{{ preg_replace('/\D+/', '', $site['whatsapp_number']) }}">WhatsApp</a>
          <a class="btn btn-clay" data-bind-href="tel" href="tel:{{ preg_replace('/[^\d+]/', '', $site['phone']) }}">Call <span data-bind="phone">{{ $site['phone'] }}</span></a>
          <a class="btn btn-line" data-bind-href="mail" href="mailto:{{ $site['email'] }}">Email <span data-bind="email">{{ $site['email'] }}</span></a>
        </div>
        <div class="enquiry-card">
          <form class="js-contact-form" novalidate>
            <h2 id="contact-form-heading" class="form-title">General enquiry</h2>
            <p class="lang-status" data-lang-status>WhatsApp message: English</p>
            <div class="form-grid">
              <div class="field"><label for="k-name">Full name</label><input id="k-name" data-field="name" autocomplete="name" required maxlength="80"><p class="error-text" data-error-for="name" hidden></p></div>
              <div class="field"><label for="k-phone">Mobile number</label><input id="k-phone" data-field="phone" type="tel" required maxlength="16"><p class="error-text" data-error-for="phone" hidden></p></div>
              <div class="field"><label for="k-email">Email <span class="hint">(optional)</span></label><input id="k-email" data-field="email" type="email" maxlength="120"><p class="error-text" data-error-for="email" hidden></p></div>
              <div class="field"><label for="k-message">Message</label><textarea id="k-message" data-field="message" required maxlength="500" rows="4" placeholder="Tell us your dates, group size, or the train you are arriving on."></textarea><p class="error-text" data-error-for="message" hidden></p></div>
            </div>
            <p class="visually-hidden" data-form-status aria-live="assertive"></p>
            <div class="form-actions"><button type="submit" class="btn btn-wa">Send on WhatsApp</button></div>
            <p class="form-note">The form does not email us by itself. It opens WhatsApp with your message ready to send.</p>
          </form>
        </div>
      </div>
      <div>
        <article class="hours-card">
          <h2 class="form-title">Visit and hours</h2>
          <p data-bind="address">{{ $site['address'] }}</p>
          <p><strong data-bind="hours">{{ $site['hours'] }}</strong></p>
          <p class="summary">Check-in from <span data-bind="checkin">{{ $site['check_in'] }}</span>. Check-out by <span data-bind="checkout">{{ $site['check_out'] }}</span>.</p>
          <p><a data-maps-link href="{{ $site['maps_link'] }}">Open Poombarai in Google Maps</a></p>
        </article>
        <iframe class="map-frame" style="margin-top:1rem" data-map title="Map of Poombarai village, Kodaikanal" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="{{ $site['maps_embed_url'] }}"></iframe>
      </div>
    </div>
  </section>
  <section class="band" style="background:#fff" aria-labelledby="faq-heading">
    <div class="wrap" style="max-width:46rem">
      <p class="eyebrow">Questions</p>
      <h2 id="faq-heading" class="section-title">Before you write</h2>
      <div class="faq-list">
        @foreach($faqs as $index => $faq)
          <div class="faq-item">
            <button type="button" class="faq-q" id="faq-button-{{ $index }}" aria-expanded="false" aria-controls="faq-panel-{{ $index }}">{{ $faq->question }}</button>
            <div class="faq-a" id="faq-panel-{{ $index }}" role="region" aria-labelledby="faq-button-{{ $index }}" hidden>
              <p>{{ $faq->answer }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endsection
