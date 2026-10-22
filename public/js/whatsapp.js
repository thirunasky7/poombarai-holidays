/**
 * WhatsApp enquiry helper.
 * buildWhatsAppLink(message) -> https://wa.me/<number>?text=<encoded message>
 */
(function () {
  const LANG_KEY = "kodai_msg_lang";
  const DRAFT_KEY = "kodai_enquiry_draft";
  const saveTimers = new WeakMap();

  function cfg() {
    return window.SITE_CONFIG || {};
  }

  function business() {
    return cfg().BUSINESS_NAME || "our team";
  }

  function lang() {
    try {
      return localStorage.getItem(LANG_KEY) === "ta" ? "ta" : "en";
    } catch (err) {
      return "en";
    }
  }

  function todayISO() {
    const d = new Date();
    const m = String(d.getMonth() + 1).padStart(2, "0");
    const day = String(d.getDate()).padStart(2, "0");
    return d.getFullYear() + "-" + m + "-" + day;
  }

  function toISO(date) {
    const m = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return date.getFullYear() + "-" + m + "-" + day;
  }

  function addDays(iso, days) {
    const date = new Date(iso + "T00:00:00");
    date.setDate(date.getDate() + days);
    return toISO(date);
  }

  function addMonths(iso, months) {
    const date = new Date(iso + "T00:00:00");
    date.setMonth(date.getMonth() + months);
    return toISO(date);
  }

  function oneLine(value) {
    return String(value == null ? "" : value).replace(/\s+/g, " ").trim();
  }

  function countLabel(value, one, many) {
    const n = Number(value) || 0;
    return n + " " + (n === 1 ? one : many);
  }

  function isValidPhone(value) {
    const digits = String(value || "").replace(/\D/g, "");
    if (digits.length === 10) return /^[6-9]\d{9}$/.test(digits);
    if (digits.length === 12 && digits.startsWith("91")) return /^[6-9]\d{9}$/.test(digits.slice(2));
    return false;
  }

  function isValidEmail(value) {
    if (!value) return true;
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function waNumber() {
    return String(cfg().WHATSAPP_NUMBER || "").replace(/\D/g, "");
  }

  function buildWhatsAppLink(message) {
    return "https://wa.me/" + waNumber() + "?text=" + encodeURIComponent(message);
  }

  function pageLabel() {
    const label = document.body.dataset.pageLabel || "website";
    if (lang() !== "ta") return label;
    const map = {
      Home: "முகப்பு",
      "Room Stay": "தங்குமிடம்",
      "Car and guide": "கார் மற்றும் வழிகாட்டி",
      "Tourist places": "சுற்றுலா இடங்கள்",
      Contact: "தொடர்பு",
    };
    return map[label] || label;
  }

  function typeLabel(type) {
    const map = {
      Room: ["Room", "அறை"],
      Cottage: ["Cottage", "காட்டேஜ்"],
      Homestay: ["Homestay", "ஹோம்ஸ்டே"],
      "car rental": ["car rental", "கார் வாடகை"],
      "tour package": ["tour package", "சுற்றுலா தொகுப்பு"],
      "short video": ["short video", "குறும்படம்"],
      Any: ["Any", "எதுவும்"],
    };
    const pair = map[type] || [type || "stay", type || "தங்குமிடம்"];
    return lang() === "ta" ? pair[1] : pair[0];
  }

  function tripLabel(value) {
    const map = {
      local: ["Local sightseeing", "உள்ளூர் சுற்றுலா"],
      outstation: ["Outstation", "வெளியூர் பயணம்"],
      transfer: ["Airport / station transfer", "விமான நிலையம் / ரயில் நிலைய இடமாற்றம்"],
    };
    const pair = map[value] || [value || "—", value || "—"];
    return lang() === "ta" ? pair[1] : pair[0];
  }

  function guideLabel(value) {
    if (value === "no") return lang() === "ta" ? "இல்லை" : "No";
    return lang() === "ta" ? "ஆம்" : "Yes";
  }

  function placeLine(name) {
    const list = (window.SITE_DATA && window.SITE_DATA.destinations) || [];
    const found = list.find(function (place) { return place.name === name; });
    if (lang() === "ta" && found && found.nameTa) return name + " (" + found.nameTa + ")";
    return name;
  }

  function itemMessage(name, type, price) {
    if (lang() === "ta") {
      const priceLine = price ? " கட்டணம்: " + oneLine(price) + "." : "";
      return "வணக்கம் " + business() + ", *" + oneLine(name) + "* (" + typeLabel(type) + ") பற்றி தெரிந்து கொள்ள விரும்புகிறேன்." + priceLine + " காலியாக உள்ளதா என்று விவரங்களை அனுப்பவும்.";
    }
    const priceLine = price ? " Price: " + oneLine(price) + "." : "";
    return "Hello " + business() + ", I'm interested in *" + oneLine(name) + "* (" + typeLabel(type) + ")." + priceLine + " Please share availability and details.";
  }

  function generalMessage() {
    if (lang() === "ta") {
      return "வணக்கம் " + business() + ", உங்கள் " + pageLabel() + " பக்கத்திலிருந்து எழுதுகிறேன். தங்குமிடம் அல்லது கார் பயணம் பற்றி விவரங்களை அனுப்பவும்.";
    }
    return "Hello " + business() + ", I'm on your " + pageLabel() + " page and would like to enquire. Please share availability and details.";
  }

  function stayMessage(data) {
    const guestsEn = countLabel(data.adults, "adult", "adults") + ", " + countLabel(data.children, "child", "children");
    const guestsTa = countLabel(data.adults, "பெரியவர்", "பெரியவர்கள்") + ", " + countLabel(data.children, "குழந்தை", "குழந்தைகள்");
    if (lang() === "ta") {
      return [
        "*புதிய தங்குமிட விசாரணை*",
        "பெயர்: " + oneLine(data.name),
        "தொலைபேசி: " + oneLine(data.phone),
        "செக்-இன்: " + oneLine(data.checkIn),
        "செக்-அவுட்: " + oneLine(data.checkOut),
        "விருந்தினர்கள்: " + guestsTa,
        "அறை வகை: " + typeLabel(data.roomType || "Any"),
        "குறிப்புகள்: " + (oneLine(data.notes) || "—"),
      ].join("\n");
    }
    return [
      "*New Stay Enquiry*",
      "Name: " + oneLine(data.name),
      "Phone: " + oneLine(data.phone),
      "Check-in: " + oneLine(data.checkIn),
      "Check-out: " + oneLine(data.checkOut),
      "Guests: " + guestsEn,
      "Room type: " + (data.roomType || "Any"),
      "Notes: " + (oneLine(data.notes) || "—"),
    ].join("\n");
  }

  function carMessage(data) {
    const places = (data.places || []).map(placeLine).join(", ") || "—";
    if (lang() === "ta") {
      return [
        "*புதிய கார் முன்பதிவு*",
        "பெயர்: " + oneLine(data.name),
        "தொலைபேசி: " + oneLine(data.phone),
        "பிக்அப் இடம்: " + oneLine(data.pickup),
        "டிராப் இடம்: " + (oneLine(data.drop) || "பிக்அப் இடமே"),
        "தேதி: " + oneLine(data.date),
        "நேரம்: " + oneLine(data.time),
        "வாகனம்: " + oneLine(data.vehicle),
        "பயண வகை: " + tripLabel(data.tripType),
        "பயணிகள்: " + oneLine(data.passengers),
        "வழிகாட்டி வேண்டுமா: " + guideLabel(data.needGuide),
        "செல்லும் இடங்கள்: " + places,
      ].join("\n");
    }
    return [
      "*New Car Booking Enquiry*",
      "Name: " + oneLine(data.name),
      "Phone: " + oneLine(data.phone),
      "Pickup: " + oneLine(data.pickup),
      "Drop: " + (oneLine(data.drop) || "Same as pickup"),
      "Date: " + oneLine(data.date),
      "Time: " + oneLine(data.time),
      "Vehicle: " + oneLine(data.vehicle),
      "Trip type: " + tripLabel(data.tripType),
      "Passengers: " + oneLine(data.passengers),
      "Guide needed: " + guideLabel(data.needGuide),
      "Places to visit: " + places,
    ].join("\n");
  }

  function contactMessage(data) {
    if (lang() === "ta") {
      return [
        "*புதிய தொடர்பு விசாரணை*",
        "பெயர்: " + oneLine(data.name),
        "தொலைபேசி: " + oneLine(data.phone),
        "மின்னஞ்சல்: " + (oneLine(data.email) || "—"),
        "செய்தி: " + oneLine(data.message),
      ].join("\n");
    }
    return [
      "*New Contact Enquiry*",
      "Name: " + oneLine(data.name),
      "Phone: " + oneLine(data.phone),
      "Email: " + (oneLine(data.email) || "—"),
      "Message: " + oneLine(data.message),
    ].join("\n");
  }

  function matchingPlan(names) {
    const plans = (window.SITE_DATA && window.SITE_DATA.tourPlans) || [];
    const key = (names || []).slice().sort().join("|");
    if (!key) return null;
    return plans.find(function (plan) {
      return plan.places.slice().sort().join("|") === key;
    }) || null;
  }

  function placesMessage(data) {
    const names = data.places || [];
    const lines = names.map(function (name) { return "- " + placeLine(name); }).join("\n");
    const plan = matchingPlan(names);
    if (lang() === "ta") {
      return [
        "*சுற்றுலா கார் கோரிக்கை*",
        "பெயர்: " + oneLine(data.name),
        "தொலைபேசி: " + oneLine(data.phone),
        "விருப்ப தேதி: " + (oneLine(data.date) || "—"),
        plan ? "திட்டம்: " + plan.title : "",
        "இடங்கள்:",
        lines,
        "இந்த இடங்களுக்கு கார் மற்றும் வழிகாட்டி விவரங்களை அனுப்பவும்.",
      ].filter(Boolean).join("\n");
    }
    return [
      "*Sightseeing car request*",
      "Name: " + oneLine(data.name),
      "Phone: " + oneLine(data.phone),
      "Preferred date: " + (oneLine(data.date) || "—"),
      plan ? "Plan: " + plan.title : "",
      "Places:",
      lines,
      "Please share a car and guide option for these places.",
    ].filter(Boolean).join("\n");
  }

  function track(itemName, type) {
    window.dataLayer = window.dataLayer || [];
    if (typeof window.gtag !== "function") {
      window.gtag = function () { window.dataLayer.push(arguments); };
    }
    window.gtag("event", "whatsapp_click", {
      page_path: window.location.pathname || "/",
      page_title: document.title,
      item_name: itemName || "General enquiry",
      enquiry_type: type || "general",
    });
  }

  function showFallback(url) {
    let box = document.getElementById("wa-fallback");
    if (!box) {
      box = document.createElement("div");
      box.id = "wa-fallback";
      box.className = "wa-fallback";
      box.setAttribute("role", "status");
      box.setAttribute("aria-live", "polite");
      const text = document.createElement("p");
      text.className = "wa-fallback-text";
      const link = document.createElement("a");
      link.className = "wa-fallback-link";
      link.target = "_blank";
      link.rel = "noopener noreferrer";
      link.textContent = "Tap here to open WhatsApp";
      const close = document.createElement("button");
      close.type = "button";
      close.className = "wa-fallback-close";
      close.textContent = "Close";
      close.addEventListener("click", function () { box.hidden = true; });
      box.append(text, link, close);
      document.body.appendChild(box);
    }
    const text = box.querySelector(".wa-fallback-text");
    const link = box.querySelector(".wa-fallback-link");
    if (!url) {
      text.textContent = "Add a WhatsApp number in js/config.js before sending.";
      link.hidden = true;
    } else {
      text.textContent = "WhatsApp didn't open.";
      link.hidden = false;
      link.href = url;
      link.textContent = "Tap here to open WhatsApp";
    }
    box.hidden = false;
    if (!link.hidden) link.focus();
  }

  function openWhatsApp(message, meta) {
    const itemName = meta && meta.itemName;
    const type = meta && meta.type;
    track(itemName, type);
    const number = waNumber();
    if (!number) {
      showFallback("");
      return "";
    }
    const url = buildWhatsAppLink(message);
    let opened = null;
    try {
      opened = window.open(url, "_blank");
    } catch (err) {
      opened = null;
    }
    if (opened) {
      try { opened.opener = null; } catch (err) { /* ignore */ }
    } else {
      showFallback(url);
    }
    return url;
  }

  function loadDraft() {
    try {
      const raw = localStorage.getItem(DRAFT_KEY);
      const parsed = raw ? JSON.parse(raw) : {};
      if (!parsed || typeof parsed !== "object" || Array.isArray(parsed)) return {};
      return parsed;
    } catch (err) {
      return {};
    }
  }

  function saveDraft(partial) {
    const next = Object.assign({}, loadDraft());
    Object.keys(partial || {}).forEach(function (key) {
      next[key] = partial[key];
    });
    try {
      localStorage.setItem(DRAFT_KEY, JSON.stringify(next));
    } catch (err) { /* private mode */ }
  }

  function placeBoxes(form) {
    if (!form || form.id !== "places-form") return [];
    return Array.from(document.querySelectorAll("[data-place-select]"));
  }

  function readForm(form) {
    const data = {};
    let sawPlaces = false;
    Array.from(form.elements).forEach(function (control) {
      if (!control.dataset || !control.dataset.field || control.disabled) return;
      const key = control.dataset.field;
      if (control.type === "checkbox" && key === "places") {
        sawPlaces = true;
        data.places = data.places || [];
        if (control.checked) data.places.push(control.value);
        return;
      }
      if (control.type === "radio") {
        if (control.checked) data[key] = control.value;
        else if (data[key] == null) data[key] = "";
        return;
      }
      data[key] = String(control.value || "").trim();
    });
    const external = placeBoxes(form);
    if (external.length) {
      sawPlaces = true;
      data.places = external.filter(function (box) { return box.checked; }).map(function (box) { return box.value; });
    }
    if (!sawPlaces) delete data.places;
    return data;
  }

  function restoreForm(form) {
    const saved = loadDraft();
    const today = todayISO();
    const max = addMonths(today, 18);
    Array.from(form.elements).forEach(function (control) {
      if (!control.dataset || !control.dataset.field) return;
      const key = control.dataset.field;
      const value = saved[key];
      if (value == null) return;
      if (control.type === "checkbox" && key === "places") {
        control.checked = Array.isArray(value) && value.indexOf(control.value) !== -1;
        return;
      }
      if (control.type === "radio") {
        control.checked = control.value === value;
        return;
      }
      if (control.type === "date") {
        if (typeof value === "string" && value >= today && value <= max) control.value = value;
        return;
      }
      if (typeof value === "string" && control.type !== "checkbox") {
        const maxLen = control.maxLength > 0 ? control.maxLength : 500;
        control.value = value.slice(0, maxLen);
      }
    });
    const checkIn = form.querySelector('[data-field="checkIn"]');
    const checkOut = form.querySelector('[data-field="checkOut"]');
    if (checkIn && checkOut && checkIn.value && checkOut.value && checkOut.value <= checkIn.value) {
      checkOut.value = "";
    }
    if (form.id === "places-form" && Array.isArray(saved.places)) {
      document.querySelectorAll("[data-place-select]").forEach(function (box) {
        box.checked = saved.places.indexOf(box.value) !== -1;
      });
    }
  }

  function controlsFor(form, key) {
    const list = Array.from(form.elements).filter(function (control) {
      return control.dataset && control.dataset.field === key;
    });
    if (key === "places") {
      placeBoxes(form).forEach(function (box) {
        if (list.indexOf(box) === -1) list.push(box);
      });
    }
    return list;
  }

  function clearFieldError(form, key) {
    const errorEl = form.querySelector('[data-error-for="' + key + '"]');
    if (errorEl) {
      errorEl.hidden = true;
      errorEl.textContent = "";
    }
    controlsFor(form, key).forEach(function (control) {
      control.removeAttribute("aria-invalid");
    });
    const group = form.querySelector('[data-field-group="' + key + '"]');
    if (group) group.removeAttribute("aria-invalid");
  }

  function showErrors(form, errors, order) {
    form.querySelectorAll("[data-error-for]").forEach(function (el) {
      el.hidden = true;
      el.textContent = "";
    });
    Array.from(form.elements).forEach(function (control) {
      control.removeAttribute("aria-invalid");
    });
    form.querySelectorAll("[data-field-group]").forEach(function (group) {
      group.removeAttribute("aria-invalid");
    });
    const keys = order.filter(function (key) { return errors[key]; });
    keys.forEach(function (key) {
      const errorEl = form.querySelector('[data-error-for="' + key + '"]');
      const inputs = controlsFor(form, key);
      inputs.forEach(function (input) {
        input.setAttribute("aria-invalid", "true");
        if (errorEl && errorEl.id) input.setAttribute("aria-describedby", errorEl.id);
      });
      const group = form.querySelector('[data-field-group="' + key + '"]');
      if (group) group.setAttribute("aria-invalid", "true");
      if (errorEl) {
        errorEl.hidden = false;
        errorEl.textContent = errors[key];
      }
    });
    const status = form.querySelector("[data-form-status]");
    if (status) status.textContent = keys.length ? "Please fix the highlighted fields." : "";
    if (!keys.length) return;
    const first = controlsFor(form, keys[0]).find(function (control) {
      return typeof control.focus === "function";
    });
    if (first) first.focus();
  }

  function requireName(data, errors) {
    if (!data.name || data.name.length < 2) errors.name = "Please enter your name.";
  }

  function requirePhone(data, errors) {
    if (!isValidPhone(data.phone)) errors.phone = "Enter a 10-digit Indian mobile number.";
  }

  function dateInRange(value) {
    const today = todayISO();
    return Boolean(value) && value >= today && value <= addMonths(today, 18);
  }

  function validateStay(data) {
    const errors = {};
    const today = todayISO();
    const max = addMonths(today, 18);
    requireName(data, errors);
    requirePhone(data, errors);
    if (!data.checkIn) errors.checkIn = "Choose a check-in date.";
    else if (data.checkIn < today) errors.checkIn = "Check-in can't be in the past.";
    else if (data.checkIn > max) errors.checkIn = "Choose a check-in within the next 18 months.";
    if (!data.checkOut) errors.checkOut = "Choose a check-out date.";
    else if (data.checkIn && data.checkOut <= data.checkIn) errors.checkOut = "Check-out must be after check-in.";
    else if (data.checkOut < today) errors.checkOut = "Check-out can't be in the past.";
    else if (data.checkOut > addDays(max, 1)) errors.checkOut = "Choose a check-out within the next 18 months.";
    if (!/^\d+$/.test(String(data.adults || "")) || Number(data.adults) < 1 || Number(data.adults) > 20) {
      errors.adults = "Add at least one adult.";
    }
    if (!/^\d+$/.test(String(data.children == null ? "" : data.children)) || Number(data.children) < 0 || Number(data.children) > 12) {
      errors.children = "Enter 0 if there are no children.";
    }
    if (!data.roomType) errors.roomType = "Choose a room type.";
    if (data.notes && data.notes.length > 400) errors.notes = "Keep the note under 400 characters.";
    return errors;
  }

  function vehicleByName(name) {
    const list = (window.SITE_DATA && window.SITE_DATA.vehicles) || [];
    return list.find(function (item) { return item.name === name; }) || null;
  }

  function validateCar(data) {
    const errors = {};
    const today = todayISO();
    requireName(data, errors);
    requirePhone(data, errors);
    if (!data.pickup || data.pickup.length < 2) errors.pickup = "Tell us where to pick you up.";
    if (!data.tripType) errors.tripType = "Choose a trip type.";
    if ((data.tripType === "outstation" || data.tripType === "transfer") && !data.drop) {
      errors.drop = "Tell us the drop location.";
    }
    if (!data.date) errors.date = "Choose a travel date.";
    else if (data.date < today) errors.date = "Travel date can't be in the past.";
    else if (!dateInRange(data.date)) errors.date = "Choose a date within the next 18 months.";
    if (!data.time) errors.time = "Choose a pickup time.";
    if (!data.vehicle) errors.vehicle = "Choose a vehicle.";
    if (!/^\d+$/.test(String(data.passengers || "")) || Number(data.passengers) < 1) {
      errors.passengers = "Enter the number of passengers.";
    } else {
      const vehicle = vehicleByName(data.vehicle);
      if (vehicle && Number(data.passengers) > vehicle.seats) {
        errors.passengers = vehicle.name + " takes up to " + vehicle.seats + " passengers. Choose a larger vehicle or reduce the number.";
      }
    }
    if (!data.needGuide) errors.needGuide = "Tell us if you need a guide.";
    if (data.tripType === "local" && (!data.places || !data.places.length)) {
      errors.places = "Choose at least one place, or switch the trip type.";
    }
    return errors;
  }

  function validateContact(data) {
    const errors = {};
    requireName(data, errors);
    requirePhone(data, errors);
    if (!isValidEmail(data.email)) errors.email = "Enter a valid email, or leave it blank.";
    if (!data.message || data.message.length < 5) errors.message = "Write a short message so we know how to help.";
    if (data.message && data.message.length > 500) errors.message = "Keep the message under 500 characters.";
    return errors;
  }

  function validatePlaces(data) {
    const errors = {};
    const today = todayISO();
    requireName(data, errors);
    requirePhone(data, errors);
    if (data.date && data.date < today) errors.date = "Travel date can't be in the past.";
    else if (data.date && !dateInRange(data.date)) errors.date = "Choose a date within the next 18 months.";
    if (!data.places || !data.places.length) errors.places = "Select at least one place to visit.";
    return errors;
  }

  function applyDateLimits(form) {
    const today = todayISO();
    const max = addMonths(today, 18);
    form.querySelectorAll('input[type="date"]').forEach(function (input) {
      input.min = input.dataset.field === "checkOut" ? addDays(today, 1) : today;
      input.max = input.dataset.field === "checkOut" ? addDays(max, 1) : max;
    });
    const checkIn = form.querySelector('[data-field="checkIn"]');
    const checkOut = form.querySelector('[data-field="checkOut"]');
    if (!checkIn || !checkOut) return;
    const sync = function () {
      checkOut.min = checkIn.value ? addDays(checkIn.value, 1) : addDays(today, 1);
    };
    checkIn.addEventListener("change", sync);
    sync();
  }

  function fillRoomTypes(form) {
    const select = form.querySelector("[data-options='room-types']");
    if (!select || select.dataset.filled === "true") return;
    select.dataset.filled = "true";
    select.replaceChildren();
    ["Any", "Room", "Cottage", "Homestay"].forEach(function (value) {
      const option = document.createElement("option");
      option.value = value;
      option.textContent = value === "Any" ? "Any type" : value;
      select.appendChild(option);
    });
  }

  function fillVehicles(form) {
    const select = form.querySelector("[data-options='vehicles']");
    if (!select || select.dataset.filled === "true") return;
    select.dataset.filled = "true";
    select.replaceChildren();
    const placeholder = document.createElement("option");
    placeholder.value = "";
    placeholder.textContent = "Select a vehicle";
    select.appendChild(placeholder);
    ((window.SITE_DATA && window.SITE_DATA.vehicles) || []).forEach(function (vehicle) {
      const option = document.createElement("option");
      option.value = vehicle.name;
      option.textContent = vehicle.name + " · up to " + vehicle.seats + " passengers · " + (vehicle.ac ? "AC" : "Non-AC");
      select.appendChild(option);
    });
  }

  function fillPlaceChoices(form) {
    const box = form.querySelector("[data-options='places']");
    if (!box || box.dataset.filled === "true") return;
    box.dataset.filled = "true";
    ((window.SITE_DATA && window.SITE_DATA.destinations) || []).forEach(function (place) {
      const label = document.createElement("label");
      label.className = "choice";
      const input = document.createElement("input");
      input.type = "checkbox";
      input.value = place.name;
      input.dataset.field = "places";
      const span = document.createElement("span");
      span.textContent = place.name;
      label.append(input, span);
      box.appendChild(label);
    });
  }

  function scheduleSave(form) {
    const prev = saveTimers.get(form);
    if (prev) window.clearTimeout(prev);
    saveTimers.set(form, window.setTimeout(function () {
      saveDraft(readForm(form));
    }, 150));
  }

  function updateSelection() {
    const names = Array.from(document.querySelectorAll("[data-place-select]:checked")).map(function (el) {
      return el.value;
    });
    const label = names.length
      ? names.length + (names.length === 1 ? " place selected" : " places selected")
      : "No places selected yet";
    document.querySelectorAll("[data-selected-count]").forEach(function (el) {
      el.textContent = label;
    });
    document.querySelectorAll("[data-active-plan]").forEach(function (el) {
      const plan = matchingPlan(names);
      el.textContent = plan ? "Plan added: " + plan.title : "";
    });
    const bar = document.getElementById("selection-bar");
    if (bar) {
      bar.hidden = names.length === 0;
      const count = bar.querySelector("[data-selection-count]");
      if (count) count.textContent = String(names.length);
    }
    document.body.classList.toggle("has-place-selection", names.length > 0);
  }

  function setLang(next) {
    const value = next === "ta" ? "ta" : "en";
    try { localStorage.setItem(LANG_KEY, value); } catch (err) { /* ignore */ }
    document.documentElement.dataset.msgLang = value;
    document.querySelectorAll("[data-lang]").forEach(function (btn) {
      btn.setAttribute("aria-pressed", btn.dataset.lang === value ? "true" : "false");
    });
    const text = value === "ta" ? "WhatsApp message: Tamil" : "WhatsApp message: English";
    document.querySelectorAll("[data-lang-status]").forEach(function (node) {
      node.textContent = text;
    });
    refreshGeneralLinks();
  }

  function refreshGeneralLinks() {
    if (!waNumber()) return;
    const href = buildWhatsAppLink(generalMessage());
    document.querySelectorAll('a[data-enquiry-type="general"]').forEach(function (anchor) {
      anchor.href = href;
    });
  }

  function rememberChoice(el) {
    const type = el.dataset.enquiryType;
    if (type === "Room" || type === "Cottage" || type === "Homestay") {
      document.querySelectorAll('[data-field="roomType"]').forEach(function (select) {
        if (Array.from(select.options).some(function (opt) { return opt.value === type; })) select.value = type;
      });
      saveDraft({ roomType: type });
    }
    if (el.dataset.vehicleName) {
      document.querySelectorAll('[data-field="vehicle"]').forEach(function (select) {
        if (Array.from(select.options).some(function (opt) { return opt.value === el.dataset.vehicleName; })) {
          select.value = el.dataset.vehicleName;
        }
      });
      saveDraft({ vehicle: el.dataset.vehicleName });
    }
  }

  function bindEnquiryClicks() {
    document.addEventListener("click", function (event) {
      const origin = event.target instanceof Element ? event.target : event.target && event.target.parentElement;
      const el = origin && origin.closest("[data-enquiry-type]");
      if (!el || el.closest("form")) return;
      if (el.tagName === "A") event.preventDefault();
      rememberChoice(el);
      const type = el.dataset.enquiryType;
      const name = el.dataset.itemName || "General enquiry";
      const price = el.dataset.price || "";
      const message = type === "general" ? generalMessage() : itemMessage(name, type, price);
      openWhatsApp(message, { itemName: name, type: type });
    });
  }

  function bindForm(form, kind) {
    if (form.dataset.bound === "true") return;
    form.dataset.bound = "true";
    fillRoomTypes(form);
    fillVehicles(form);
    fillPlaceChoices(form);
    applyDateLimits(form);
    restoreForm(form);
    const orders = {
      stay: ["name", "phone", "checkIn", "checkOut", "adults", "children", "roomType", "notes"],
      car: ["name", "phone", "pickup", "drop", "date", "time", "vehicle", "tripType", "passengers", "needGuide", "places"],
      contact: ["name", "phone", "email", "message"],
      places: ["name", "phone", "date", "places"],
    };
    const validators = { stay: validateStay, car: validateCar, contact: validateContact, places: validatePlaces };
    const builders = { stay: stayMessage, car: carMessage, contact: contactMessage, places: placesMessage };
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      const data = readForm(form);
      const errors = validators[kind](data);
      showErrors(form, errors, orders[kind]);
      if (Object.keys(errors).length) return;
      saveDraft(data);
      const itemName = kind === "stay" ? "Stay enquiry" : kind === "car" ? (data.vehicle || "Car booking") : kind === "places" ? "Sightseeing plan" : "Contact form";
      openWhatsApp(builders[kind](data), { itemName: itemName, type: kind });
    });
  }

  function mountChrome() {
    if (!document.getElementById("wa-fab")) {
      const fab = document.createElement("button");
      fab.type = "button";
      fab.id = "wa-fab";
      fab.className = "wa-fab";
      fab.dataset.enquiryType = "general";
      fab.dataset.itemName = "Floating WhatsApp button";
      fab.setAttribute("aria-label", "Enquire on WhatsApp");
      const glyph = document.createElement("span");
      glyph.className = "fab-glyph";
      glyph.setAttribute("aria-hidden", "true");
      fab.appendChild(glyph);
      document.body.appendChild(fab);
    }
    if (!document.getElementById("mobile-bar")) {
      const bar = document.createElement("div");
      bar.id = "mobile-bar";
      bar.className = "mobile-bar";
      const wa = document.createElement("button");
      wa.type = "button";
      wa.className = "mobile-bar-wa";
      wa.dataset.enquiryType = "general";
      wa.dataset.itemName = "Mobile WhatsApp bar";
      wa.textContent = "WhatsApp";
      const call = document.createElement("a");
      call.className = "mobile-bar-call";
      call.dataset.bindHref = "tel";
      call.textContent = "Call";
      bar.append(wa, call);
      document.body.appendChild(bar);
    }
  }

  function init() {
    setLang(lang());
    mountChrome();
    document.querySelectorAll("[data-lang]").forEach(function (btn) {
      btn.addEventListener("click", function () { setLang(btn.dataset.lang); });
    });
    bindEnquiryClicks();
    document.querySelectorAll(".js-stay-form").forEach(function (form) { bindForm(form, "stay"); });
    document.querySelectorAll(".js-car-form").forEach(function (form) { bindForm(form, "car"); });
    document.querySelectorAll(".js-contact-form").forEach(function (form) { bindForm(form, "contact"); });
    document.querySelectorAll(".js-places-form").forEach(function (form) { bindForm(form, "places"); });
    document.addEventListener("input", function (event) {
      const el = event.target;
      if (!(el instanceof HTMLElement) || !el.dataset || !el.dataset.field) return;
      const form = el.form || document.getElementById(el.getAttribute("form") || "") || el.closest("form");
      if (!form) return;
      clearFieldError(form, el.dataset.field);
      scheduleSave(form);
      if (el.dataset.field === "places" || el.hasAttribute("data-place-select")) updateSelection();
    });
    document.addEventListener("click", function (event) {
      const jump = event.target.closest("[data-scroll]");
      if (!jump) return;
      const target = document.querySelector(jump.getAttribute("data-scroll"));
      if (!target) return;
      const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
      target.scrollIntoView({ behavior: reduce ? "auto" : "smooth", block: "start" });
    });
    updateSelection();
    refreshGeneralLinks();
  }

  window.buildWhatsAppLink = buildWhatsAppLink;
  window.KodaiWhatsApp = {
    buildWhatsAppLink: buildWhatsAppLink,
    openWhatsApp: openWhatsApp,
    init: init,
    updateSelection: updateSelection,
    itemMessage: itemMessage,
    stayMessage: stayMessage,
    carMessage: carMessage,
    placesMessage: placesMessage,
    setLang: setLang,
    lang: lang,
  };
})();
