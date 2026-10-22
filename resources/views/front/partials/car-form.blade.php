<form class="js-car-form" novalidate>
  <h2 class="form-title">{{ $heading ?? 'Book a car and guide' }}</h2>
  <p class="lang-status" data-lang-status>WhatsApp message: English</p>
  <div class="form-grid cols-2">
    <div class="field">
      <label for="{{ $prefix }}-name">Full name</label>
      <input id="{{ $prefix }}-name" name="name" data-field="name" autocomplete="name" required maxlength="80">
      <p class="error-text" id="{{ $prefix }}-name-error" data-error-for="name" hidden></p>
    </div>
    <div class="field">
      <label for="{{ $prefix }}-phone">Mobile number</label>
      <input id="{{ $prefix }}-phone" name="phone" data-field="phone" type="tel" inputmode="tel" autocomplete="tel" required maxlength="16">
      <p class="error-text" id="{{ $prefix }}-phone-error" data-error-for="phone" hidden></p>
    </div>
    <div class="field">
      <label for="{{ $prefix }}-pickup">Pickup location</label>
      <input id="{{ $prefix }}-pickup" name="pickup" data-field="pickup" list="pickup-list" required maxlength="120">
      <p class="error-text" id="{{ $prefix }}-pickup-error" data-error-for="pickup" hidden></p>
    </div>
    <div class="field">
      <label for="{{ $prefix }}-drop">Drop location</label>
      <input id="{{ $prefix }}-drop" name="drop" data-field="drop" list="pickup-list" maxlength="120">
      <p class="hint">Needed for outstation and airport transfers.</p>
      <p class="error-text" id="{{ $prefix }}-drop-error" data-error-for="drop" hidden></p>
    </div>
    <div class="field">
      <label for="{{ $prefix }}-date">Date</label>
      <input id="{{ $prefix }}-date" name="date" data-field="date" type="date" required>
      <p class="error-text" id="{{ $prefix }}-date-error" data-error-for="date" hidden></p>
    </div>
    <div class="field">
      <label for="{{ $prefix }}-time">Pickup time</label>
      <input id="{{ $prefix }}-time" name="time" data-field="time" type="time" required>
      <p class="error-text" id="{{ $prefix }}-time-error" data-error-for="time" hidden></p>
    </div>
    <div class="field">
      <label for="{{ $prefix }}-vehicle">Vehicle</label>
      <select id="{{ $prefix }}-vehicle" name="vehicle" data-field="vehicle" data-options="vehicles" required>
        <option value="">Select a vehicle</option>
      </select>
      <p class="error-text" id="{{ $prefix }}-vehicle-error" data-error-for="vehicle" hidden></p>
    </div>
    <div class="field">
      <label for="{{ $prefix }}-trip">Trip type</label>
      <select id="{{ $prefix }}-trip" name="tripType" data-field="tripType" required>
        <option value="">Select trip type</option>
        <option value="local">Local sightseeing</option>
        <option value="outstation">Outstation</option>
        <option value="transfer">Airport / station transfer</option>
      </select>
      <p class="error-text" id="{{ $prefix }}-trip-error" data-error-for="tripType" hidden></p>
    </div>
    <div class="field span-2">
      <label for="{{ $prefix }}-passengers">Passengers</label>
      <input id="{{ $prefix }}-passengers" name="passengers" data-field="passengers" type="number" min="1" max="20" value="2" inputmode="numeric" required>
      <p class="error-text" id="{{ $prefix }}-passengers-error" data-error-for="passengers" hidden></p>
    </div>
    <fieldset class="field span-2">
      <legend>Need a tourist guide?</legend>
      <div class="radio-row">
        <label class="choice"><input type="radio" name="{{ $prefix }}-guide" data-field="needGuide" value="yes" checked> <span>Yes</span></label>
        <label class="choice"><input type="radio" name="{{ $prefix }}-guide" data-field="needGuide" value="no"> <span>No</span></label>
      </div>
      <p class="error-text" id="{{ $prefix }}-guide-error" data-error-for="needGuide" hidden></p>
    </fieldset>
    <fieldset class="field span-2" data-field-group="places">
      <legend>Places to visit</legend>
      <div class="choice-grid" data-options="places"></div>
      <p class="hint">Required for local sightseeing. Optional for transfers.</p>
      <p class="error-text" id="{{ $prefix }}-places-error" data-error-for="places" hidden></p>
    </fieldset>
  </div>
  <datalist id="pickup-list">
    <option value="My stay in Poombarai"></option>
    <option value="Poombarai village centre"></option>
    <option value="Kodaikanal bus stand"></option>
    <option value="Kodai Lake boat house"></option>
    <option value="Madurai Airport"></option>
    <option value="Madurai Junction"></option>
  </datalist>
  <p class="visually-hidden" data-form-status aria-live="assertive"></p>
  <div class="form-actions">
    <button type="submit" class="btn btn-wa">Enquire on WhatsApp</button>
  </div>
  <p class="form-note">Fares on this site are starting estimates. The WhatsApp reply is the fare that counts.</p>
</form>
