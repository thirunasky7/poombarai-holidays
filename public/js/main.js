(function () {
  const AMENITY_SVG = {
    "Wi-Fi": '<path d="M5 12.5a10 10 0 0 1 14 0"/><path d="M8.2 15.4a6 6 0 0 1 7.6 0"/><path d="M12 18.5h.01"/>',
    Parking: '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 16V8h3.6a2.4 2.4 0 0 1 0 4.8H9"/>',
    Breakfast: '<path d="M4 19h16"/><path d="M7 19c.6-4 2.2-7 5-7s4.4 3 5 7"/><path d="M12 5v3"/>',
    "Hot water": '<path d="M7 6c1.6 1.6 1.6 2.6 0 4.2S5.4 13 7 14.6"/><path d="M12 6c1.6 1.6 1.6 2.6 0 4.2s-1.6 2.6 0 4.2"/><path d="M17 6c1.6 1.6 1.6 2.6 0 4.2s-1.6 2.6 0 4.2"/>',
    Heater: '<path d="M12 3v8"/><rect x="8" y="11" width="8" height="5" rx="1"/><path d="M9 20h6"/><path d="M10 16v4M14 16v4"/>',
    "Mountain view": '<path d="M3 18 9 8l4 6 2-3 6 7H3z"/>',
    Kitchenette: '<path d="M4 10h16v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8z"/><path d="M8 10V7h8v3"/>',
    Fireplace: '<path d="M6 20V9l6-4 6 4v11"/><path d="M4 20h16"/><path d="M12 20v-5"/>',
    TV: '<rect x="3" y="6" width="18" height="12" rx="2"/><path d="M8 21h8"/><path d="M12 18v3"/>',
    Caretaker: '<circle cx="12" cy="8" r="3"/><path d="M5.5 19.5c1-3 3.2-4.5 6.5-4.5s5.5 1.5 6.5 4.5"/>',
    default: '<circle cx="12" cy="12" r="2.2"/>',
  };

  function el(tag, className, text) {
    const node = document.createElement(tag);
    if (className) node.className = className;
    if (text != null) node.textContent = text;
    return node;
  }

  function makeImg(image) {
    const img = document.createElement("img");
    img.src = image.src;
    if (image.srcset) {
      img.srcset = image.srcset;
      img.sizes = "(min-width: 1280px) 30vw, (min-width: 768px) 45vw, 92vw";
    }
    img.alt = image.alt || "";
    img.width = 800;
    img.height = 600;
    img.loading = "lazy";
    img.decoding = "async";
    return img;
  }

  function amenityIcon(name) {
    const wrap = document.createElement("span");
    wrap.className = "amenity-icon";
    wrap.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' + (AMENITY_SVG[name] || AMENITY_SVG.default) + "</svg>";
    return wrap;
  }

  function galleryButton(kind, id, index) {
    const button = document.createElement("button");
    button.type = "button";
    button.className = "media-btn";
    button.dataset.galleryKind = kind;
    button.dataset.galleryId = id;
    button.dataset.galleryIndex = String(index);
    return button;
  }

  function gallery(item, kind, wide) {
    const images = item.images ? item.images.slice() : item.image ? [item.image] : [];
    const wrap = el("div", "gallery");
    if (!images.length) return wrap;
    const main = el("div", wide ? "media media-wide" : "media");
    const button = galleryButton(kind, item.id, 0);
    button.appendChild(makeImg(images[0]));
    if (images.length > 1) {
      const count = el("span", "photo-count", images.length + " photos");
      count.setAttribute("aria-hidden", "true");
      button.appendChild(count);
    }
    main.appendChild(button);
    wrap.appendChild(main);
    if (images.length > 1) {
      const thumbs = el("div", "thumbs");
      images.forEach(function (image, index) {
        const frame = el("div", "media");
        const thumb = galleryButton(kind, item.id, index);
        thumb.appendChild(makeImg(image));
        frame.appendChild(thumb);
        thumbs.appendChild(frame);
      });
      wrap.appendChild(thumbs);
    }
    return wrap;
  }

  function enquireButton(label, type, name, price, extra) {
    const button = el("button", "btn btn-wa", label);
    button.type = "button";
    button.dataset.enquiryType = type;
    button.dataset.itemName = name;
    if (price) button.dataset.price = price;
    Object.keys(extra || {}).forEach(function (key) { button.dataset[key] = extra[key]; });
    return button;
  }

  function nameList(names) {
    const ul = el("ul", "mini-list");
    names.forEach(function (name) { ul.appendChild(el("li", null, name)); });
    return ul;
  }

  function fact(term, detail) {
    const wrap = el("div");
    wrap.append(el("dt", null, term), el("dd", null, detail));
    return wrap;
  }

  function roomCard(room, compact) {
    const article = el("article", "stay-card");
    article.dataset.roomType = room.type;
    if (!compact) article.id = room.id;
    if (compact) {
      const media = el("div", "media");
      media.appendChild(makeImg(room.images[0]));
      article.appendChild(media);
    } else {
      article.appendChild(gallery(room, "room"));
    }
    const body = el("div", "card-body");
    body.append(
      el("p", "kicker", room.type),
      el("h3", null, room.name),
      el("p", "location", room.location),
      el("p", "summary", room.summary)
    );
    const amenities = el("ul", "amenity-list");
    room.amenities.forEach(function (name) {
      const li = el("li");
      li.append(amenityIcon(name), el("span", null, name));
      amenities.appendChild(li);
    });
    body.appendChild(amenities);
    const meta = el("div", "card-meta");
    const priceWrap = el("div");
    priceWrap.append(el("p", "price", room.priceLabel), el("p", "price-note", "Confirmed on WhatsApp"));
    meta.append(priceWrap, el("p", "occupancy", "Up to " + room.occupancy + " guests"));
    body.append(
      meta,
      enquireButton("Enquire on WhatsApp", room.type, room.name, room.priceLabel)
    );
    if (compact) {
      const more = el("a", "text-link", "View details");
      more.href = "rooms.html#" + room.id;
      body.appendChild(more);
    }
    article.appendChild(body);
    return article;
  }

  function vehicleCard(vehicle) {
    const article = el("article", "info-card");
    article.id = vehicle.id;
    article.appendChild(gallery(vehicle, "vehicle", true));
    const body = el("div", "card-body");
    const badges = el("div", "badge-row");
    badges.append(
      el("span", "badge", "Up to " + vehicle.seats + " passengers"),
      el("span", "badge", vehicle.ac ? "AC" : "Non-AC"),
      el("span", "badge", "Driver included")
    );
    const rates = el("dl", "rate-list");
    vehicle.rates.forEach(function (rate) {
      const row = el("div", "rate-row");
      row.append(el("dt", null, rate.label), el("dd", null, rate.detail ? rate.value + " · " + rate.detail : rate.value));
      rates.appendChild(row);
    });
    body.append(
      el("p", "kicker", "Car rental"),
      el("h3", null, vehicle.name),
      el("p", "location", vehicle.example),
      badges,
      el("p", "summary", vehicle.blurb),
      rates,
      enquireButton("Enquire for driver & guide", "car rental", vehicle.name, vehicle.priceLabel, { vehicleName: vehicle.name })
    );
    article.appendChild(body);
    return article;
  }

  function packageCard(item) {
    const article = el("article", "info-card");
    article.id = item.id;
    article.appendChild(gallery(item, "package", true));
    const body = el("div", "card-body");
    const meta = el("div", "card-meta");
    meta.append(el("p", "price", item.fare), el("p", "occupancy", item.duration));
    body.append(
      el("p", "kicker", "Sightseeing"),
      el("h3", null, item.name),
      el("p", "summary", item.summary),
      nameList(item.places),
      meta,
      el("p", "price-note", "Sedan estimate. Final fare on WhatsApp."),
      enquireButton("Enquire on WhatsApp", "tour package", item.name, item.priceLabel)
    );
    article.appendChild(body);
    return article;
  }

  function popularCard(place) {
    const link = document.createElement("a");
    link.className = "popular-card";
    link.href = "places.html#" + place.id;
    const media = el("div", "media");
    media.appendChild(makeImg(place.image));
    const body = el("div", "popular-body");
    body.append(el("h3", null, place.name), el("p", null, place.timeNeeded));
    link.append(media, body);
    return link;
  }

  function directoryCard(place) {
    const article = el("article", "place-card");
    article.id = place.id;
    article.appendChild(gallery(place, "place"));
    const body = el("div", "card-body");
    const facts = el("dl", "facts");
    facts.append(fact("Best time", place.bestTime), fact("Time needed", place.timeNeeded));
    const label = document.createElement("label");
    label.className = "choice";
    const input = document.createElement("input");
    input.type = "checkbox";
    input.setAttribute("form", "places-form");
    input.setAttribute("data-place-select", "");
    input.dataset.field = "places";
    input.value = place.name;
    label.append(input, el("span", null, "Include " + place.name + " in my car enquiry"));
    body.append(el("h3", null, place.name), el("p", "summary", place.description), facts, label);
    article.appendChild(body);
    return article;
  }

  function planCard(plan) {
    const article = el("article", "plan-card");
    article.append(el("p", "kicker", plan.duration), el("h3", null, plan.title), el("p", "summary", plan.summary));
    if (plan.days && plan.days.length) {
      const days = el("div", "plan-days");
      plan.days.forEach(function (day) {
        const block = el("div");
        block.append(el("h4", null, day.label), nameList(day.places));
        days.appendChild(block);
      });
      article.appendChild(days);
    } else {
      article.appendChild(nameList(plan.places));
    }
    const actions = el("div", "plan-actions");
    const add = el("button", "btn btn-line", "Add this plan");
    add.type = "button";
    add.dataset.planSelect = plan.id;
    const send = el("button", "btn btn-wa", "Book this plan on WhatsApp");
    send.type = "button";
    send.dataset.planSend = plan.id;
    actions.append(add, send);
    article.appendChild(actions);
    return article;
  }

  function reviewCard(review) {
    const article = el("article", "review-card");
    const stars = el("p", "stars", "★★★★★☆☆☆☆☆".slice(5 - review.stars, 10 - review.stars));
    stars.setAttribute("aria-label", review.stars + " out of 5 stars");
    const quote = document.createElement("blockquote");
    quote.className = "quote";
    quote.textContent = review.quote;
    article.append(stars, quote, el("p", "review-name", review.name), el("p", "review-detail", review.detail));
    return article;
  }

  function targets(name) {
    return document.querySelectorAll('[data-render="' + name + '"]');
  }

  function renderRooms() {
    targets("rooms").forEach(function (container) {
      let rooms = (window.SITE_DATA.rooms || []).slice();
      if (container.dataset.featured === "true") rooms = rooms.filter(function (room) { return room.featured; });
      const limit = Number(container.dataset.limit || 0);
      if (limit) rooms = rooms.slice(0, limit);
      const compact = container.dataset.variant === "compact";
      container.replaceChildren.apply(container, rooms.map(function (room) { return roomCard(room, compact); }));
    });
  }

  function renderVehicles() {
    targets("vehicles").forEach(function (container) {
      const limit = Number(container.dataset.limit || 0);
      let items = (window.SITE_DATA.vehicles || []).slice();
      if (limit) items = items.slice(0, limit);
      container.replaceChildren.apply(container, items.map(vehicleCard));
    });
  }

  function renderPackages() {
    targets("packages").forEach(function (container) {
      const limit = Number(container.dataset.limit || 0);
      let items = (window.SITE_DATA.packages || []).slice();
      if (limit) items = items.slice(0, limit);
      container.replaceChildren.apply(container, items.map(packageCard));
    });
  }

  function renderDestinations() {
    targets("destinations").forEach(function (container) {
      let places = (window.SITE_DATA.destinations || []).slice();
      const mode = container.dataset.mode || "directory";
      if (mode === "popular") places = places.filter(function (place) { return place.popular; });
      container.replaceChildren.apply(container, places.map(function (place) {
        return mode === "popular" ? popularCard(place) : directoryCard(place);
      }));
    });
  }

  function renderTourPlans() {
    targets("plans").forEach(function (container) {
      container.replaceChildren.apply(container, (window.SITE_DATA.tourPlans || []).map(planCard));
    });
  }

  function renderReviews() {
    targets("reviews").forEach(function (container) {
      container.replaceChildren.apply(container, (window.SITE_DATA.reviews || []).map(reviewCard));
    });
  }

  function renderFaqs() {
    targets("faqs").forEach(function (container) {
      const items = (window.SITE_DATA.faqs || []).map(function (faq, index) {
        const wrap = el("div", "faq-item");
        const button = el("button", "faq-q", faq.q);
        button.type = "button";
        button.id = "faq-button-" + index;
        button.setAttribute("aria-expanded", "false");
        button.setAttribute("aria-controls", "faq-panel-" + index);
        const panel = el("div", "faq-a");
        panel.id = "faq-panel-" + index;
        panel.setAttribute("role", "region");
        panel.setAttribute("aria-labelledby", button.id);
        panel.hidden = true;
        panel.appendChild(el("p", null, faq.a));
        button.addEventListener("click", function () {
          const open = button.getAttribute("aria-expanded") === "true";
          button.setAttribute("aria-expanded", open ? "false" : "true");
          panel.hidden = open;
        });
        wrap.append(button, panel);
        return wrap;
      });
      container.replaceChildren.apply(container, items);
    });
  }

  function renderPage() {
    if (!window.SITE_DATA) return;
    renderRooms();
    renderVehicles();
    renderPackages();
    renderDestinations();
    renderTourPlans();
    renderReviews();
    renderFaqs();
  }

  function telHref(phone) {
    return "tel:" + String(phone || "").replace(/[^\d+]/g, "");
  }

  function applyConfig() {
    const c = window.SITE_CONFIG || {};
    const text = {
      name: c.BUSINESS_NAME,
      phone: c.PHONE,
      email: c.EMAIL,
      address: c.ADDRESS,
      hours: c.BUSINESS_HOURS,
      checkin: c.CHECK_IN_TIME,
      checkout: c.CHECK_OUT_TIME,
    };
    Object.keys(text).forEach(function (key) {
      document.querySelectorAll('[data-bind="' + key + '"]').forEach(function (node) {
        node.textContent = text[key] || "";
      });
    });
    document.querySelectorAll('[data-bind-href="tel"]').forEach(function (node) {
      node.href = telHref(c.PHONE);
    });
    document.querySelectorAll('[data-bind-href="mail"]').forEach(function (node) {
      const subject = encodeURIComponent("Enquiry for " + (c.BUSINESS_NAME || "Kodaikanal"));
      node.href = "mailto:" + (c.EMAIL || "") + "?subject=" + subject;
    });
    document.querySelectorAll("[data-map]").forEach(function (frame) {
      if (c.GOOGLE_MAPS_EMBED_URL) frame.src = c.GOOGLE_MAPS_EMBED_URL;
    });
    document.querySelectorAll("[data-maps-link]").forEach(function (node) {
      if (c.MAPS_LINK) node.href = c.MAPS_LINK;
    });
    const year = document.querySelector("[data-year]");
    if (year) year.textContent = String(new Date().getFullYear());
  }

  function updateSchema() {
    const node = document.getElementById("local-business-schema");
    const c = window.SITE_CONFIG;
    if (!node || !c) return;
    node.textContent = JSON.stringify({
      "@context": "https://schema.org",
      "@type": ["LocalBusiness", "LodgingBusiness"],
      name: c.BUSINESS_NAME,
      url: c.SITE_URL,
      telephone: c.PHONE,
      email: c.EMAIL,
      image: c.HERO_IMAGE,
      address: {
        "@type": "PostalAddress",
        streetAddress: c.ADDRESS,
        addressLocality: c.LOCALITY || "Poombarai",
        addressRegion: c.REGION || "Tamil Nadu",
        postalCode: c.POSTAL_CODE || "624103",
        addressCountry: "IN",
      },
      geo: { "@type": "GeoCoordinates", latitude: c.LAT, longitude: c.LNG },
      openingHours: c.BUSINESS_HOURS,
      priceRange: c.PRICE_RANGE || "₹₹",
      areaServed: [
        { "@type": "Place", name: c.LOCALITY || "Poombarai" },
        { "@type": "Place", name: "Kodaikanal" },
      ],
    });
  }

  function initAnalytics() {
    const id = (window.SITE_CONFIG && window.SITE_CONFIG.GA_MEASUREMENT_ID) || "G-XXXXXXXXXX";
    window.dataLayer = window.dataLayer || [];
    window.gtag = window.gtag || function gtag() { window.dataLayer.push(arguments); };
    window.gtag("js", new Date());
    window.gtag("config", id);
    const script = document.createElement("script");
    script.async = true;
    script.src = "https://www.googletagmanager.com/gtag/js?id=" + encodeURIComponent(id);
    document.head.appendChild(script);
  }

  function initNav() {
    const toggle = document.querySelector(".nav-toggle");
    const panel = document.getElementById("mobile-nav");
    if (!toggle || !panel) return;
    const label = toggle.querySelector(".nav-toggle-label");
    function closeMenu() {
      panel.hidden = true;
      toggle.setAttribute("aria-expanded", "false");
      toggle.setAttribute("aria-label", "Open menu");
      toggle.classList.remove("is-open");
      document.body.classList.remove("no-scroll");
      if (label) label.textContent = "Menu";
    }
    function openMenu() {
      panel.hidden = false;
      toggle.setAttribute("aria-expanded", "true");
      toggle.setAttribute("aria-label", "Close menu");
      toggle.classList.add("is-open");
      document.body.classList.add("no-scroll");
      if (label) label.textContent = "Close menu";
      const first = panel.querySelector("a");
      if (first) first.focus();
    }
    toggle.addEventListener("click", function () {
      if (panel.hidden) openMenu();
      else {
        closeMenu();
        toggle.focus();
      }
    });
    panel.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", closeMenu);
    });
    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape" && !panel.hidden) {
        closeMenu();
        toggle.focus();
      }
    });
    window.addEventListener("resize", function () {
      if (window.innerWidth >= 960) closeMenu();
    });
  }

  function imagesFor(kind, id) {
    const data = window.SITE_DATA || {};
    const lists = { room: data.rooms, vehicle: data.vehicles, package: data.packages, place: data.destinations };
    const item = (lists[kind] || []).find(function (entry) { return entry.id === id; });
    if (!item) return [];
    if (item.images) return item.images;
    if (item.image) return [item.image];
    return [];
  }

  function initLightbox() {
    const dialog = el("div", "lightbox");
    dialog.hidden = true;
    dialog.setAttribute("role", "dialog");
    dialog.setAttribute("aria-modal", "true");
    dialog.setAttribute("aria-label", "Photo gallery");
    const panel = el("div", "lightbox-panel");
    const toolbar = el("div", "lightbox-toolbar");
    const prev = el("button", null, "Previous");
    const next = el("button", null, "Next");
    const close = el("button", null, "Close");
    prev.type = next.type = close.type = "button";
    prev.setAttribute("aria-label", "Previous photo");
    next.setAttribute("aria-label", "Next photo");
    close.setAttribute("aria-label", "Close gallery");
    toolbar.append(prev, next, close);
    const img = document.createElement("img");
    img.alt = "Gallery photo";
    const caption = el("p", "lightbox-caption");
    const count = el("p", "lightbox-count");
    count.setAttribute("aria-live", "polite");
    panel.append(toolbar, img, caption, count);
    dialog.appendChild(panel);
    document.body.appendChild(dialog);

    let images = [];
    let index = 0;
    let lastFocus = null;

    function show(nextIndex) {
      if (!images.length) return;
      index = (nextIndex + images.length) % images.length;
      img.src = images[index].src;
      img.alt = images[index].alt || "";
      caption.textContent = images[index].alt || "";
      count.textContent = (index + 1) + " of " + images.length;
    }

    function closeLightbox() {
      dialog.hidden = true;
      document.body.classList.remove("no-scroll");
      if (lastFocus && typeof lastFocus.focus === "function") lastFocus.focus();
    }

    function openLightbox(list, start, opener) {
      if (!list.length) return;
      images = list;
      lastFocus = opener;
      dialog.hidden = false;
      document.body.classList.add("no-scroll");
      show(start || 0);
      close.focus();
    }

    prev.addEventListener("click", function () { show(index - 1); });
    next.addEventListener("click", function () { show(index + 1); });
    close.addEventListener("click", closeLightbox);
    dialog.addEventListener("click", function (event) {
      if (event.target === dialog) closeLightbox();
    });
    let startX = 0;
    dialog.addEventListener("touchstart", function (event) {
      startX = event.changedTouches[0].clientX;
    }, { passive: true });
    dialog.addEventListener("touchend", function (event) {
      const delta = event.changedTouches[0].clientX - startX;
      if (delta > 50) show(index - 1);
      if (delta < -50) show(index + 1);
    }, { passive: true });
    document.addEventListener("keydown", function (event) {
      if (dialog.hidden) return;
      if (event.key === "Escape") {
        event.preventDefault();
        closeLightbox();
      } else if (event.key === "ArrowRight") {
        event.preventDefault();
        show(index + 1);
      } else if (event.key === "ArrowLeft") {
        event.preventDefault();
        show(index - 1);
      } else if (event.key === "Tab") {
        const focusable = [prev, next, close];
        const first = focusable[0];
        const last = focusable[focusable.length - 1];
        if (event.shiftKey && document.activeElement === first) {
          event.preventDefault();
          last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
          event.preventDefault();
          first.focus();
        }
      }
    });
    document.addEventListener("click", function (event) {
      const opener = event.target.closest("[data-gallery-id]");
      if (!opener) return;
      openLightbox(imagesFor(opener.dataset.galleryKind, opener.dataset.galleryId), Number(opener.dataset.galleryIndex || 0), opener);
    });
  }

  function initFilters() {
    const buttons = document.querySelectorAll("[data-filter]");
    if (!buttons.length) return;
    function apply(filter) {
      let shown = 0;
      document.querySelectorAll("[data-room-type]").forEach(function (card) {
        const match = filter === "all" || card.dataset.roomType === filter;
        card.hidden = !match;
        if (match) shown += 1;
      });
      buttons.forEach(function (btn) {
        const on = btn.dataset.filter === filter;
        btn.setAttribute("aria-pressed", on ? "true" : "false");
        btn.classList.toggle("is-active", on);
      });
      const count = document.querySelector("[data-filter-count]");
      if (count) count.textContent = shown === 1 ? "Showing 1 stay" : "Showing " + shown + " stays";
      const empty = document.querySelector("[data-empty]");
      if (empty) empty.hidden = shown !== 0;
    }
    buttons.forEach(function (btn) {
      btn.addEventListener("click", function () { apply(btn.dataset.filter); });
    });
    apply("all");
  }

  function initCarousel() {
    const root = document.querySelector("[data-carousel]");
    if (!root) return;
    const track = root.querySelector("[data-carousel-track]");
    const prev = root.querySelector("[data-carousel-prev]");
    const next = root.querySelector("[data-carousel-next]");
    if (!track || !prev || !next) return;
    const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    function scrollByDir(dir) {
      track.scrollBy({ left: dir * Math.max(track.clientWidth * 0.86, 240), behavior: reduce ? "auto" : "smooth" });
    }
    prev.addEventListener("click", function () { scrollByDir(-1); });
    next.addEventListener("click", function () { scrollByDir(1); });
    let timer = null;
    function stop() {
      if (timer) window.clearInterval(timer);
      timer = null;
    }
    function start() {
      if (reduce) return;
      stop();
      timer = window.setInterval(function () {
        const max = track.scrollWidth - track.clientWidth - 8;
        if (track.scrollLeft >= max) track.scrollTo({ left: 0, behavior: reduce ? "auto" : "smooth" });
        else scrollByDir(1);
      }, 7000);
    }
    root.addEventListener("mouseenter", stop);
    root.addEventListener("mouseleave", start);
    root.addEventListener("focusin", stop);
    root.addEventListener("focusout", function (event) {
      if (!root.contains(event.relatedTarget)) start();
    });
    document.addEventListener("visibilitychange", function () {
      if (document.hidden) stop();
      else start();
    });
    start();
  }

  function initTabs() {
    document.querySelectorAll('[role="tablist"]').forEach(function (group) {
      const tabs = Array.from(group.querySelectorAll('[role="tab"]'));
      function activate(index, focus) {
        tabs.forEach(function (tab, i) {
          const on = i === index;
          tab.setAttribute("aria-selected", on ? "true" : "false");
          tab.tabIndex = on ? 0 : -1;
          const panel = document.getElementById(tab.getAttribute("aria-controls"));
          if (panel) panel.hidden = !on;
        });
        if (focus && tabs[index]) tabs[index].focus();
      }
      tabs.forEach(function (tab, index) {
        tab.addEventListener("click", function () { activate(index, false); });
        tab.addEventListener("keydown", function (event) {
          if (event.key !== "ArrowRight" && event.key !== "ArrowLeft") return;
          event.preventDefault();
          const delta = event.key === "ArrowRight" ? 1 : -1;
          activate((index + delta + tabs.length) % tabs.length, true);
        });
      });
    });
  }

  function initPlans() {
    document.addEventListener("click", function (event) {
      const selectBtn = event.target.closest("[data-plan-select]");
      const sendBtn = event.target.closest("[data-plan-send]");
      const btn = selectBtn || sendBtn;
      if (!btn || !window.SITE_DATA) return;
      const id = selectBtn ? selectBtn.dataset.planSelect : sendBtn.dataset.planSend;
      const plan = (window.SITE_DATA.tourPlans || []).find(function (item) { return item.id === id; });
      if (!plan) return;
      document.querySelectorAll("[data-place-select]").forEach(function (box) {
        box.checked = plan.places.indexOf(box.value) !== -1;
        box.dispatchEvent(new Event("input", { bubbles: true }));
      });
      if (window.KodaiWhatsApp) window.KodaiWhatsApp.updateSelection();
      const form = document.getElementById("places-form");
      if (!form) return;
      if (sendBtn) {
        if (typeof form.requestSubmit === "function") form.requestSubmit();
        else form.dispatchEvent(new Event("submit", { bubbles: true, cancelable: true }));
      } else {
        const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
        form.scrollIntoView({ behavior: reduce ? "auto" : "smooth", block: "start" });
        const name = form.querySelector('[data-field="name"]');
        if (name && !name.value) name.focus();
      }
    });
  }

  function scrollToHash() {
    if (!window.location.hash || window.location.hash.length < 2) return;
    window.setTimeout(function () {
      try {
        const node = document.querySelector(window.location.hash);
        if (node) node.scrollIntoView();
      } catch (err) { /* ignore invalid selectors */ }
    }, 0);
  }

  function initHomeBanner() {
    const root = document.querySelector("[data-home-banner]");
    if (!root) return;
    const slides = Array.from(root.querySelectorAll("[data-banner-slide]"));
    const dots = Array.from(root.querySelectorAll("[data-banner-dot]"));
    if (slides.length < 2) return;
    let index = 0;
    let timer = null;
    const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    function show(next) {
      index = (next + slides.length) % slides.length;
      slides.forEach(function (slide, i) {
        const on = i === index;
        slide.hidden = !on;
        slide.setAttribute("aria-hidden", on ? "false" : "true");
      });
      dots.forEach(function (dot, i) {
        if (i === index) dot.setAttribute("aria-current", "true");
        else dot.removeAttribute("aria-current");
      });
    }

    function stop() {
      if (timer) window.clearInterval(timer);
      timer = null;
    }

    function start() {
      if (reduce) return;
      stop();
      timer = window.setInterval(function () { show(index + 1); }, 6500);
    }

    const prev = root.querySelector("[data-banner-prev]");
    const next = root.querySelector("[data-banner-next]");
    if (prev) prev.addEventListener("click", function () { show(index - 1); start(); });
    if (next) next.addEventListener("click", function () { show(index + 1); start(); });
    dots.forEach(function (dot) {
      dot.addEventListener("click", function () {
        show(Number(dot.dataset.bannerDot));
        start();
      });
    });
    root.addEventListener("mouseenter", stop);
    root.addEventListener("mouseleave", start);
    root.addEventListener("focusin", stop);
    root.addEventListener("focusout", function (event) {
      if (!root.contains(event.relatedTarget)) start();
    });
    start();
  }

  function initShorts() {
    const videos = Array.from(document.querySelectorAll("[data-short-video]"));
    if (!videos.length) return;
    const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (!reduce && "IntersectionObserver" in window) {
      const watcher = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          const video = entry.target;
          if (entry.isIntersecting && entry.intersectionRatio >= 0.65) {
            const play = video.play();
            if (play && play.catch) play.catch(function () {});
          } else {
            video.pause();
          }
        });
      }, { threshold: [0.65] });
      videos.forEach(function (video) { watcher.observe(video); });
    }
    document.querySelectorAll("[data-short-mute]").forEach(function (button) {
      button.addEventListener("click", function () {
        const video = button.parentElement.querySelector("[data-short-video]");
        if (!video) return;
        video.muted = !video.muted;
        const muted = video.muted;
        button.setAttribute("aria-pressed", muted ? "true" : "false");
        button.setAttribute("aria-label", muted ? "Unmute video" : "Mute video");
      });
    });
  }

  function initFaqs() {
    document.querySelectorAll(".faq-q").forEach(function (button) {
      const panel = document.getElementById(button.getAttribute("aria-controls"));
      button.addEventListener("click", function () {
        const open = button.getAttribute("aria-expanded") === "true";
        button.setAttribute("aria-expanded", open ? "false" : "true");
        if (panel) panel.hidden = open;
      });
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    initAnalytics();
    initNav();
    renderPage();
    if (window.KodaiWhatsApp) window.KodaiWhatsApp.init();
    applyConfig();
    initLightbox();
    initFilters();
    initCarousel();
    initTabs();
    initPlans();
    initHomeBanner();
    initShorts();
    initFaqs();
    updateSchema();
    scrollToHash();
  });
})();
