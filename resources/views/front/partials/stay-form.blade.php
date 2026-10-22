<form class="js-stay-form" novalidate>
  <h2 class="form-title">{{ $heading ?? 'Ask about a stay' }}</h2>
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
      <label for="{{ $prefix }}-in">Check-in</label>
      <input id="{{ $prefix }}-in" name="checkIn" data-field="checkIn" type="date" required>
      <p class="error-text" id="{{ $prefix }}-in-error" data-error-for="checkIn" hidden></p>
    </div>
    <div class="field">
      <label for="{{ $prefix }}-out">Check-out</label>
      <input id="{{ $prefix }}-out" name="checkOut" data-field="checkOut" type="date" required>
      <p class="error-text" id="{{ $prefix }}-out-error" data-error-for="checkOut" hidden></p>
    </div>
    <div class="field">
      <label for="{{ $prefix }}-adults">Adults</label>
      <input id="{{ $prefix }}-adults" name="adults" data-field="adults" type="number" min="1" max="20" value="2" inputmode="numeric" required>
      <p class="error-text" id="{{ $prefix }}-adults-error" data-error-for="adults" hidden></p>
    </div>
    <div class="field">
      <label for="{{ $prefix }}-children">Children</label>
      <input id="{{ $prefix }}-children" name="children" data-field="children" type="number" min="0" max="12" value="0" inputmode="numeric" required>
      <p class="error-text" id="{{ $prefix }}-children-error" data-error-for="children" hidden></p>
    </div>
    <div class="field span-2">
      <label for="{{ $prefix }}-room-type">Room type</label>
      <select id="{{ $prefix }}-room-type" name="roomType" data-field="roomType" data-options="room-types" required>
        <option value="Any">Any type</option>
      </select>
      <p class="error-text" id="{{ $prefix }}-room-error" data-error-for="roomType" hidden></p>
    </div>
    <div class="field span-2">
      <label for="{{ $prefix }}-notes">Special requests</label>
      <textarea id="{{ $prefix }}-notes" name="notes" data-field="notes" maxlength="400" rows="3"></textarea>
      <p class="error-text" id="{{ $prefix }}-notes-error" data-error-for="notes" hidden></p>
    </div>
  </div>
  <p class="visually-hidden" data-form-status aria-live="assertive"></p>
  <div class="form-actions">
    <button type="submit" class="btn btn-wa">Enquire on WhatsApp</button>
  </div>
  <p class="form-note">Your details stay on this device until you send them in WhatsApp. Nothing is booked until we reply.</p>
</form>
