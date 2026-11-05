<form class="enquiry-form" method="post" action="{{ route('enquiries.store') }}">
  @csrf
  <h2 class="form-title" @if(!empty($headingId)) id="{{ $headingId }}" @endif>{{ $heading ?? 'Send an enquiry' }}</h2>
  <p class="form-note">We reply on WhatsApp and give you a reference number. Nothing is booked or paid here.</p>
  @if($errors->any())
    <div class="form-errors" role="alert">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  <div class="hp" aria-hidden="true">
    <label for="{{ $prefix }}-website">Website</label>
    <input id="{{ $prefix }}-website" name="website" type="text" tabindex="-1" autocomplete="off">
  </div>
  <div class="form-grid cols-2">
    <div class="field">
      <label for="{{ $prefix }}-service">Service</label>
      <select id="{{ $prefix }}-service" name="service_type" required>
        @foreach(['room' => 'Room', 'car' => 'Car', 'package' => 'Package', 'general' => 'General'] as $value => $label)
          <option value="{{ $value }}" @selected(old('service_type', $service ?? 'general') === $value)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
    <div class="field">
      <label for="{{ $prefix }}-item">Which one <span class="hint">(optional)</span></label>
      <input id="{{ $prefix }}-item" name="service_name" value="{{ old('service_name') }}" maxlength="120">
    </div>
    <div class="field">
      <label for="{{ $prefix }}-place">Destination</label>
      <select id="{{ $prefix }}-place" name="destination">
        <option value="">Poombarai / not sure</option>
        @foreach($destinations as $place)
          <option value="{{ $place->name }}" @selected(old('destination') === $place->name)>{{ $place->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="field">
      <label for="{{ $prefix }}-date">Travel date</label>
      <input id="{{ $prefix }}-date" name="travel_date" type="date" value="{{ old('travel_date') }}">
    </div>
    <div class="field">
      <label for="{{ $prefix }}-adults">Adults</label>
      <input id="{{ $prefix }}-adults" name="adults" type="number" min="1" max="20" value="{{ old('adults', 2) }}" required>
    </div>
    <div class="field">
      <label for="{{ $prefix }}-children">Children</label>
      <input id="{{ $prefix }}-children" name="children" type="number" min="0" max="12" value="{{ old('children', 0) }}">
    </div>
    <div class="field">
      <label for="{{ $prefix }}-name">Full name</label>
      <input id="{{ $prefix }}-name" name="name" value="{{ old('name') }}" autocomplete="name" required maxlength="80">
    </div>
    <div class="field">
      <label for="{{ $prefix }}-phone">Mobile number</label>
      <input id="{{ $prefix }}-phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" required maxlength="20">
    </div>
    <div class="field span-2">
      <label for="{{ $prefix }}-email">Email <span class="hint">(optional)</span></label>
      <input id="{{ $prefix }}-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" maxlength="120">
    </div>
    <div class="field span-2">
      <label for="{{ $prefix }}-notes">What do you need?</label>
      <textarea id="{{ $prefix }}-notes" name="requirements" maxlength="500" rows="3">{{ old('requirements') }}</textarea>
    </div>
    <p class="field span-2 preferred-reply">Preferred reply: WhatsApp</p>
    <input type="hidden" name="contact_method" value="whatsapp">
  </div>
  <div class="form-actions">
    <button type="submit" class="btn btn-wa">Send enquiry</button>
  </div>
</form>
