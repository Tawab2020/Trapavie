<template>
  <section class="section">
    <div class="container px-4" v-if="!loading">
      <h3 class="text-gray-800 font-semibold">{{ venue.name }}</h3>
      <p>{{ venue.city }}, {{venue.state}}, {{ venue.country }}</p>
      <div>
        <img
          class="w-100 d-block rounded-md banner-image"
          :src="bannerImage(venue.images) ? `${$FilePath}/venue/${bannerImage(venue.images)}` : '/assets/user/frontend/images/default.jpeg'"
          alt="banner image"
        />
      </div>
      <div class="mt-5 top-grid">
        <div>
          <div class="d-md-flex align-items-center justify-content-between pb-4 border-bottom">
            <div>
              <h4
                class="text-gray-800 mb-1 font-semibold"
              >{{propertyType(venue.property_type_id)["en"]}} Hosted By {{venue.user.fname + " " + venue.user.lname}}</h4>
              <div>
                <div
                  class="text-gray-600"
                >{{propertyType(venue.property_type_id)["en"]}} · {{venue.min_guests}} minimum guests · {{maxGuests}} maximum guests · {{venue.min_nights}} minimum nights</div>
              </div>
            </div>
            <router-link
              :to="{name: 'messages.index', params: {user: venue.user_id, senderType: 0, receiverType: 1}}"
            >
              <button
                v-if="!user || user && user.id != venue.user_id"
                class="btn btn-warning mt-4 mt-md-0"
              >Contact Host</button>
            </router-link>
          </div>
          <div class="mt-5">
            <div v-if="venue.is_shared == 0">
              <h6 class="font-semibold">Shared venue</h6>
              <p>The venue amenities and facilities such as dining area and kitchen may be shared with other retreat groups or venue guests.</p>
            </div>
            <div v-else>
              <h6 class="font-semibold">Entire venue</h6>
              <p>The venue amenities and facilities such as dining area and kitchen are in your hands only.</p>
            </div>
          </div>
          <div v-if="venue.meals.length" class="mb-3">
            <h6 class="mb-0 font-semibold">Meals Included</h6>
            <span class="font-semibold pointer text-warning" @click="goToPart('meals')">See details</span>
          </div>
          <div class="mb-3" v-if="retreatOrganizerText">
            <h6 class="mb-0 font-semibold">{{retreatOrganizerText}}</h6>
            <span class="font-semibold pointer text-warning" @click="goToPart('meals')">See details</span>
          </div>
          <div>
            <h6
              class="mb-0 font-semibold"
            >{{cpolicy.title ? cpolicy.title['en'] : 'Custom'}} Cancellation Policy</h6>
            <p
              class="mb-0"
            >{{cpolicy.refund_percentage}}% refund for cancellations made {{cpolicy.days_before_arrival}} days before arrival</p>
            <span
              class="font-semibold pointer text-warning"
              @click="goToPart('policies')"
            >See details</span>
          </div>

          <div>
            <div>
              <div class="mt-5 pt-5 border-top"></div>
              <p v-if="venue.description.length >= 500">
                {{$CF.trunucate(venue.description, 500)}}
                <span
                  class="font-semibold pointer text-warning"
                  data-bs-toggle="modal"
                  data-bs-target="#description-modal"
                >read more ...</span>
              </p>
              <p v-else>{{venue.description}}</p>
            </div>

            <div class="row mt-1"></div>

            <div class="row">
              <div class="col-12"></div>
            </div>
          </div>
        </div>
        <div>
          <div
            class="border p-4 rounded position-sticky d-none d-md-block"
            style="top: 100px; box-shadow: 0px 5px 25px -5px gainsboro"
          >
            <div class="mb-3">
              <h3
                class="d-inline text-gray-900 font-semibold"
              >{{currencySymbol + venue.price_per_night}}</h3>
              <span class="text-lg">per night</span>
            </div>
            <div>
              <div class="border rounded text-gray-600 position-relative cursor-pointer">
                <div
                  @click="showDatePicker = true"
                  style="display: grid; grid-template-columns: 1fr 1fr"
                >
                  <div class="p-2 text-sm" style="border-right: 1px solid #e9ecef">
                    <div class="mb-1">Check in</div>
                    <div>{{selectedDates.start ? $DateTime.fromISO(selectedDates.start).toLocaleString($DateTime.DATE_FULL) : "Add date"}}</div>
                  </div>
                  <div class="p-2 text-sm">
                    <div class="mb-1">Check out</div>
                    <div>{{selectedDates.end ? $DateTime.fromISO(selectedDates.end).toLocaleString($DateTime.DATE_FULL) : "Add date"}}</div>
                  </div>
                </div>
                <div
                  class="position-absolute check-date-picker"
                  v-show="showDatePicker"
                  id="check-date-picker"
                >
                  <div class="border-bottom mb-3 pb-2">
                    <h5 class="text-dark d-inline" v-if="nightsLeft">
                      {{ nightsLeft }} Night
                      <span v-if="nightsLeft > 1">s</span>
                    </h5>
                    <span v-if="selectedDates.end">
                      ({{ new Date(selectedDates.start).toLocaleDateString() }} -
                      {{ new Date(selectedDates.end).toLocaleDateString() }})
                    </span>
                    <span v-else>(Choose start date - end date)</span>
                  </div>
                  <Date-Picker
                    :columns="$screens({ default: 1, desktop: 2 })"
                    :from-page="{ month: 1, year: 2022 }"
                    v-model="selectedDates"
                    :model-config="modelConfig"
                    :min-date="$DateTime.now()"
                    :max-date="$DateTime.now().plus({years: 1})"
                    :disabled-dates="[...venue.blocked_dates, ...venue.booked_dates]"
                    is-range
                    mode="date"
                    timezone="UTC"
                  >
                    <template #header-title="{ monthLabel }">{{monthLabel}}</template>
                  </Date-Picker>
                  <div class="d-flex justify-content-end">
                    <button
                      class="btn btn-primary btn-sm"
                      @click.stop.prevent="closeDatePicker()"
                    >Close</button>
                  </div>
                </div>
              </div>
              <div class="mt-3">
                <label for class="label text-sm">Guests</label>
                <input
                  type="number"
                  min="1"
                  class="form-control"
                  v-model="bookingRequest.guests"
                  placeholder="The entire venue accommodates guests."
                />
              </div>
              <button
                class="btn btn-warning mt-3 w-100"
                :disabled="!bookingRequest.end_date"
                @click="reserveBooking()"
              >Reserve</button>
              <div
                class="text-sm text-gray-600 mt-2 text-center pb-4 border-bottom"
              >You won't be charged yet</div>
              <div class="pt-4 d-flex align-items-center justify-content-between">
                <div class="font-semibold text-lg text-gray-800">Total</div>
                <div class="text-gray-800">
                  <p
                    class="font-semibold text-lg mb-0"
                    v-if="bookingRequest.price"
                  >{{currencySymbol + bookingRequest.price.total_price}}</p>
                  <p v-else class="mb-0">Choose dates</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="border-top border-bottom pb-5 mt-5 pt-5 mt-5">
        <h4 class="text-gray-800 mb-4 font-semibold">Things to know</h4>
        <div class="row">
          <div class="col-md-4 mb-4 mb-md-0">
            <h6 class="text-gray-800 mb-2 font-semibold">Location</h6>
            <div class="d-flex align-items-center">
              <svg
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M10 11.1917C11.436 11.1917 12.6 10.0276 12.6 8.5917C12.6 7.15576 11.436 5.9917 10 5.9917C8.56408 5.9917 7.40002 7.15576 7.40002 8.5917C7.40002 10.0276 8.56408 11.1917 10 11.1917Z"
                  stroke="#292D32"
                  stroke-width="1.5"
                />
                <path
                  d="M3.01669 7.07508C4.65836 -0.141583 15.35 -0.13325 16.9834 7.08342C17.9417 11.3167 15.3084 14.9001 13 17.1168C11.325 18.7334 8.67502 18.7334 6.99169 17.1168C4.69169 14.9001 2.05836 11.3084 3.01669 7.07508Z"
                  stroke="#292D32"
                  stroke-width="1.5"
                />
              </svg>
              <div class="ms-2">Nashik, Maharashtra, India</div>
            </div>
          </div>
          <div class="col-md-4 mb-4 mb-md-0">
            <h6 class="text-gray-800 mb-2 font-semibold">Reviews</h6>
            <div class="d-flex align-items-center">
              <svg
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M11.4417 2.92495L12.9083 5.85828C13.1083 6.26662 13.6417 6.65828 14.0917 6.73328L16.75 7.17495C18.45 7.45828 18.85 8.69162 17.625 9.90828L15.5583 11.9749C15.2083 12.3249 15.0167 12.9999 15.125 13.4833L15.7167 16.0416C16.1833 18.0666 15.1083 18.8499 13.3167 17.7916L10.825 16.3166C10.375 16.0499 9.63332 16.0499 9.17499 16.3166L6.68332 17.7916C4.89999 18.8499 3.81665 18.0583 4.28332 16.0416L4.87499 13.4833C4.98332 12.9999 4.79165 12.3249 4.44165 11.9749L2.37499 9.90828C1.15832 8.69162 1.54999 7.45828 3.24999 7.17495L5.90832 6.73328C6.34999 6.65828 6.88332 6.26662 7.08332 5.85828L8.54999 2.92495C9.34999 1.33328 10.65 1.33328 11.4417 2.92495Z"
                  stroke="#292D32"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
              <div class="ms-2">There are no reviews yet.</div>
            </div>
          </div>
          <div class="col-md-4">
            <h6 class="text-gray-800 mb-2 font-semibold">Cancellation Policy</h6>
            <p>
              Deposit refund
              <br />100% refund for cancellations made 20+ days before arrival
            </p>
          </div>
        </div>
      </div>

      <nav id="menu-items" class="navbar bg-white details-menu border-bottom p-0">
        <ul class="nav d-block">
          <a
            ref="nav"
            class="nav-link menu-item pb-3 pt-4 me-5"
            @click.prevent="goToPart('venue-details')"
            href="#venue-details"
          >Venue details</a>

          <a
            class="nav-link menu-item pb-3 pt-4 me-5"
            @click.prevent="goToPart('price')"
            href="#price"
          >Price</a>

          <a
            class="nav-link menu-item pb-3 pt-4 me-5"
            @click.prevent="goToPart('accomodation')"
            href="#accomodation"
          >Accomodation</a>

          <a
            class="nav-link menu-item pb-3 pt-4 me-5"
            @click.prevent="goToPart('policies')"
            href="#policies"
          >Policies</a>

          <a
            class="nav-link menu-item pb-3 pt-4 me-5"
            @click.prevent="goToPart('reviews')"
            href="#reviews"
          >Reviews</a>

          <a
            class="nav-link menu-item pb-3 pt-4 me-5"
            @click.prevent="goToPart('location')"
            href="#location"
          >Location</a>

          <a
            class="nav-link menu-item pb-3 pt-4 me-5"
            @click.prevent="goToPart('arrival')"
            href="#arrival"
          >Arrival</a>
        </ul>
      </nav>

      <div id="details">
        <div class="image-grid mt-4 position-relative" id="venue-details">
          <div
            @click="showGallery = true"
            v-for="i in 5"
            :key="i"
            class="image pointer"
            :class="`image-${i}`"
          >
            <img
              class="w-100 h-100"
              :src="firstFiveImages(venue.images)[i-1] ? `${$FilePath}/venue/${firstFiveImages(venue.images)[i-1].image}` : '/assets/user/frontend/images/default.jpeg'"
              alt="banner image"
            />
          </div>
          <button
            class="gallery-btn btn btn-dark d-flex align-items-center gap-2 text-sm"
            @click="showGallery = true"
          >
            <i class="uil uil-image" style="font-size: 24px; line-height: 24px;"></i>
            Show Gallery
          </button>
        </div>
        <div class="border-bottom" id="features">
          <h4 class="text-gray-800 mb-4 mt-5 ps-0">Venue Features</h4>
          <div class="row ms-0">
            <div class="col-12 col-md-4 mt-3 mt-md-0 px-0">
              <h6 class="font-semibold mb-3">Amenities and Facilites</h6>
              <div class="row">
                <div class="col-6 pb-3" v-for="amenity in amenities.slice(0,6)" :key="amenity.id">
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="amenity.image ? `${$FilePath}/venue/data/${amenity.image.image}` : `${$FilePath}/venue/data/${vdcategories[0].image.image}`"
                      :alt="amenity.title['en']"
                    />
                    <span class="ms-3">{{amenity.title['en']}}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-4 border-md-left ps-md-4 pe-md-0 mt-3 mt-md-0">
              <h6 class="font-semibold mb-3">Surroundings</h6>
              <div class="row">
                <div
                  class="col-6 pb-3"
                  v-for="surrounding in surroundings.slice(0,6)"
                  :key="surrounding.id"
                >
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="surrounding.image ? `${$FilePath}/venue/data/${surrounding.image.image}` : `${$FilePath}/venue/data/${vdcategories[6].image.image}`"
                      :alt="surrounding.title['en']"
                    />
                    <span class="ms-3">{{surrounding.title['en']}}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-4 border-md-left ps-md-4 pe-md-0 mt-3 mt-md-0">
              <h6 class="font-semibold mb-3">Activities</h6>
              <div class="row">
                <div class="col-6 pb-3" v-for="thing in thingsToDo.slice(0,6)" :key="thing.id">
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="thing.image ? `${$FilePath}/venue/data/${thing.image.image}` : `${$FilePath}/venue/data/${vdcategories[7].image.image}`"
                      :alt="thing.title['en']"
                    />
                    <span class="ms-3">{{thing.title['en']}}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="mb-5">
            <button
              class="btn btn-outline-dark text-sm mt-4"
              data-bs-toggle="modal"
              data-bs-target="#features-modal"
            >Show all {{allFeaturesCount}} venue features</button>
          </div>
        </div>

        <div class="border-bottom" id="yoga">
          <h4 class="text-gray-800 mb-4 mt-5">Yoga Space</h4>
          <div class="row mb-4">
            <div class="col-12">
              <h6 class="text-lg font-semibold">Indoor</h6>
            </div>
            <div class="col-12">
              <h6 class="text-lg font-semibold">Outdoor</h6>
            </div>
          </div>
        </div>

        <div class="border-bottom" id="food">
          <h4 class="text-gray-800 mb-4 mt-5">Food</h4>
          <div class="row">
            <div class="col-12 col-md-6">
              <h6 class="text-lg font-semibold">Dietery options</h6>
              <div class="row">
                <div class="col-6 col-md-4 pb-3" v-for="diet in diets.slice(0,6)" :key="diet.id">
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="diet.image ? `${$FilePath}/venue/data/${diet.image.image}` : `${$FilePath}/venue/data/${vdcategories[9].image.image}`"
                      :alt="diet.title['en']"
                    />
                    <span class="ms-3">{{diet.title['en']}}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <h6 class="text-lg font-semibold">Kitchen facilities</h6>
              <div class="row">
                <div
                  class="col-6 col-md-4 pb-3"
                  v-for="facility in kitchenFacilities.slice(0,6)"
                  :key="facility.id"
                >
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="facility.image ? `${$FilePath}/venue/data/${facility.image.image}` : `${$FilePath}/venue/data/${vdcategories[10].image.image}`"
                      :alt="facility.title['en']"
                    />
                    <span class="ms-3">{{facility.title['en']}}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="border-bottom pb-5">
            <button
              v-if="diets.length > 6 || kitchenFacilities > 6"
              class="btn btn-outline-dark mt-4 text-sm"
              data-bs-toggle="modal"
              data-bs-target="#food-modal"
            >Show all {{foodOptionsCount}} food options</button>
          </div>
        </div>

        <div class="border-bottom" id="price">
          <h4 class="text-gray-800 mb-4 mt-5">Price</h4>
          <div class="row mb-4">
            <div class="col-md-6">
              <h5 class="text-lg font-semibold">Venue</h5>
              <p>
                From
                <span class="font-semibold">{{currencySymbol + venue.price_per_night}}</span> per night (taxes included)
              </p>
              <p class="pe-0 pe-md-5" v-if="venue.additional_guest_price">
                <span class="font-semibold">Note:</span>
                The price shown is for groups of up to {{venue.guests_included}} people. A fee of {{currencySymbol + venue.additional_guest_price}}/night applies to each additional guest after {{venue.guests_included}} people.
              </p>
              <div v-if="venue.length_of_stay_discounts.length > 0">
                <h6 class="font-semibold mt-2">Length of stay discounts</h6>
                <p v-for="discount in venue.length_of_stay_discounts" :key="discount.id">
                  {{discount.from}} to {{discount.to}} nights &rarr;
                  <span
                    class="font-semibold"
                  >{{currencySymbol}}{{calculateVenuePriceWithDiscount(discount.percentage)}}/night</span>
                </p>
              </div>
            </div>
            <div class="col-md-6 mt-3 mt-md-0" id="meals">
              <h5 class="text-lg font-semibold">Meals</h5>
              <div class="row p-0">
                <div
                  v-for="meal in venue.meals"
                  :key="meal.id"
                  class="col-6 col-md-4 align-items-center pb-2"
                >
                  <i class="uil uil-check-circle text-lg" v-if="meal.price ==0"></i>
                  <i class="uil uil-money-bill text-lg" v-else></i>
                  <span class="ms-2 font-semibold">{{meal.title['en']}}</span>
                  <span v-if="meal.price > 0">&nbsp;({{currencySymbol + meal.price}}/guest)</span>
                  <span v-else>&nbsp;(Free)</span>
                </div>
              </div>
              <p v-if="venue.free_meal_for_retreat_organizer == 1">
                <span class="font-semibold">Note:</span>
                Free meals for retreat organizer ({{freeMealForRetreatOrganizerRequirements}})
              </p>
              <div v-if="venue.meal_discounts.length > 0">
                <h6 class="font-semibold mt-2">Number of guests discounts</h6>
                <p v-for="discount in venue.meal_discounts" :key="discount.id">
                  {{discount.from}} to {{discount.to}} guests &rarr;
                  <span
                    class="font-semibold"
                  >%{{discount.percentage}} Discount</span>
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="border-bottom" id="accomodation">
          <h4 class="text-gray-800 mb-4 mt-5">Accomodation</h4>
          <div class="mb-5 row">
            <div class="col-md-6">
              <div class="d-inline">
                <div class="font-semibold text-lg d-block mb-1">Ocean View</div>
                <div class>Apartment (x2 Available)</div>
              </div>
              <div class="text-gray-600">3 Bedrooms · 4 Bathrooms · Max 6 guests</div>
            </div>
          </div>
        </div>

        <div class="border-bottom" id="policies">
          <h4 class="text-gray-800 mb-4 mt-5">{{cpolicy.title['en']}} Cancellation policy</h4>
          <div class="row mb-5">
            <div class="col-md-6 mb-4 mb-md-0">
              <u class="font-semibold text-lg d-block mb-1">Deposit {{cpolicy.deposit}}%</u>
              Remaining balance {{cpolicy.remaining_balance_in_days}} days before arrival
            </div>
            <div class="col-md-6">
              <u class="font-semibold text-lg d-block mb-1">Deposit refund</u>
              {{cpolicy.refund_percentage}}% refund for cancellations made {{cpolicy.days_before_arrival}}+ days before arrival
            </div>
          </div>
        </div>

        <div class="border-bottom" id="reviews">
          <h4 class="text-gray-800 mb-4 mt-5">Reviews</h4>
          <p class="text-gray-600 mb-5">There are no reviews for this venue yet</p>
        </div>

        <div class="border-bottom" id="location">
          <h4 class="text-gray-800 mb-4 mt-5">Location</h4>
          <p class="text-gray-600 mb-2">{{ venue.city }}, {{venue.state}}, {{ venue.country }}</p>
          <div id="map" class="mb-5">
            <GMapMap
              :center="{lat: Number(venue.lat), lng: Number(venue.lng)}"
              :zoom="10"
              map-type-id="roadmap"
              :options="mapOptions"
              ref="mapRef"
            >
              <GMapMarker
                :position="{lat: Number(venue.lat), lng: Number(venue.lng)}"
                :clickable="false"
                :draggable="false"
              />
            </GMapMap>
          </div>
        </div>

        <div class="border-bottom" id="arrival">
          <h4 class="text-gray-800 mb-4 mt-5">Arrival</h4>
          <p class="text-gray-600 mb-2">{{venue.arrival_info}}</p>
          <div class="row mb-5">
            <div
              v-for="airport in venue.airports"
              :key="airport.id"
              class="col-12 col-md-6 col-lg-4 pt-2"
            >
              <div class="card event-schedule rounded border">
                <div class="card-body">
                  <div class="d-flex">
                    <div class="d-flex align-items-center">
                      <i class="uil uil-plane-fly p-3 me-2" style="font-size: 3rem;"></i>
                    </div>
                    <div class="flex-1 content">
                      <h6 class="font-semibold">{{airport.name}}</h6>
                      <p class="text-muted location-time mb-1">
                        <span class="text-dark h6">Distance:</span>
                        {{airport.pivot.distance}} away
                        <br />
                      </p>
                      <p class="text-muted location-time">
                        <span class="text-dark h6">Transfer:</span>
                        {{transferAvailablity(airport.pivot)}}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="host">
          <h4 class="text-gray-800 mb-4 mt-5">About the host</h4>
          <div class="row mb-5">
            <div class="col-12 d-md-flex align-items-md-center">
              <div class="d-flex d-md-block align-items-center mb-3 mb-md-0">
                <img
                  :src="`${$FilePath}/user/profiles/${venue.user.image}`"
                  alt
                  class="img-fluid avatar mt-3 mt-sm-0 me-4 me-md-2 rounded-circle"
                  width="100"
                />
                <h6
                  class="font-semibold d-block d-md-none"
                >{{venue.user.fname + ' ' + venue.user.lname}}</h6>
              </div>
              <div class="ms-3">
                <h6
                  class="font-semibold d-md-block d-none"
                >{{venue.user.fname + ' ' + venue.user.lname}}</h6>
                <p
                  v-if="venue.user.user_detail.bio && venue.user.user_detail.bio.length >= 500"
                  style="white-space: pre-wrap;"
                >
                  {{$CF.trunucate(venue.user.user_detail.bio, 500)}}
                  <span
                    class="font-semibold pointer text-warning"
                    data-bs-toggle="modal"
                    data-bs-target="#bio-modal"
                  >read more ...</span>
                </p>
                <p v-else style="white-space: pre-wrap;">{{venue.user.user_detail.bio}}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- descriptoin modal start -->
      <div class="modal fade" id="description-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
              <h5 class="modal-title my-0 mx-auto" id="LoginForm-title">Venue Description</h5>
              <button
                type="button"
                class="btn-close p-0 m-0"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body py-2">
              <div class="p-4 row">
                <div class="col-12">{{venue.description}}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- description modal end -->

      <!-- descriptoin modal start -->
      <div class="modal fade" id="bio-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
              <h5 class="modal-title my-0 mx-auto" id="LoginForm-title">About venue host</h5>
              <button
                type="button"
                class="btn-close p-0 m-0"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body py-2">
              <div class="p-4 row">
                <div class="col-12" style="white-space: pre-wrap;">{{venue.user.user_detail.bio}}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- description modal end -->

      <!-- features modal start -->
      <div class="modal fade" id="features-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
          <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
              <h5 class="modal-title my-0 mx-auto" id="LoginForm-title">Property Features</h5>
              <button
                type="button"
                class="btn-close p-0 m-0"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body py-2">
              <div class="p-4 row">
                <h6 class="font-semibold">Key Property Features</h6>
                <div class="col-6 pb-3" v-for="feature in keyPropertyFeatures" :key="feature.id">
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="feature.image ? `${$FilePath}/venue/data/${feature.image.image}` : `${$FilePath}/venue/data/${vdcategories[0].image.image}`"
                      :alt="feature.title['en']"
                    />
                    <span class="ms-3">{{feature.title['en']}}</span>
                  </div>
                </div>

                <div class="mt-2 pt-2 pb-3 border-top"></div>

                <h6 class="font-semibold">General Amenities and Facilites</h6>
                <div class="col-6 pb-3" v-for="amenity in generalAmenities" :key="amenity.id">
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="amenity.image ? `${$FilePath}/venue/data/${amenity.image.image}` : `${$FilePath}/venue/data/${vdcategories[1].image.image}`"
                      :alt="amenity.title['en']"
                    />
                    <span class="ms-3">{{amenity.title['en']}}</span>
                  </div>
                </div>

                <div class="mt-2 pt-2 pb-3 border-top"></div>

                <h6 class="font-semibold">Indoor Amenities and Facilites</h6>
                <div class="col-6 pb-3" v-for="amenity in indoorAmenities" :key="amenity.id">
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="amenity.image ? `${$FilePath}/venue/data/${amenity.image.image}` : `${$FilePath}/venue/data/${vdcategories[2].image.image}`"
                      :alt="amenity.title['en']"
                    />
                    <span class="ms-3">{{amenity.title['en']}}</span>
                  </div>
                </div>

                <div class="mt-2 pt-2 pb-3 border-top"></div>

                <h6 class="font-semibold">Outdoor Amenities and Facilites</h6>
                <div class="col-6 pb-3" v-for="amenity in outdoorAmenities" :key="amenity.id">
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="amenity.image ? `${$FilePath}/venue/data/${amenity.image.image}` : `${$FilePath}/venue/data/${vdcategories[3].image.image}`"
                      :alt="amenity.title['en']"
                    />
                    <span class="ms-3">{{amenity.title['en']}}</span>
                  </div>
                </div>

                <div class="mt-2 pt-2 pb-3 border-top"></div>

                <h6 class="font-semibold">Surroundings</h6>
                <div class="col-6 pb-3" v-for="surrounding in surroundings" :key="surrounding.id">
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="surrounding.image ? `${$FilePath}/venue/data/${surrounding.image.image}` : `${$FilePath}/venue/data/${vdcategories[6].image.image}`"
                      :alt="surrounding.title['en']"
                    />
                    <span class="ms-3">{{surrounding.title['en']}}</span>
                  </div>
                </div>

                <div class="mt-2 pt-2 pb-3 border-top"></div>

                <h6 class="font-semibold">Things To Do</h6>
                <div class="col-6 pb-3" v-for="thing in thingsToDo" :key="thing.id">
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="thing.image ? `${$FilePath}/venue/data/${thing.image.image}` : `${$FilePath}/venue/data/${vdcategories[7].image.image}`"
                      :alt="thing.title['en']"
                    />
                    <span class="ms-3">{{thing.title['en']}}</span>
                  </div>
                </div>

                <div class="mt-2 pt-2 pb-3 border-top"></div>

                <h6 class="font-semibold">Rentals</h6>
                <div class="col-6 pb-3" v-for="rental in rentals" :key="rental.id">
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="rental.image ? `${$FilePath}/venue/data/${rental.image.image}` : `${$FilePath}/venue/data/${vdcategories[8].image.image}`"
                      :alt="rental.title['en']"
                    />
                    <span class="ms-3">{{rental.title['en']}}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- features modal end -->

      <!-- food modal start -->
      <div class="modal fade" id="food-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
          <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
              <h5 class="modal-title my-0 mx-auto" id="LoginForm-title">Food Options</h5>
              <button
                type="button"
                class="btn-close p-0 m-0"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body py-2">
              <div class="p-4 row">
                <h6 class="font-semibold">Kitchen Facilities</h6>
                <div class="col-6 pb-3" v-for="facility in kitchenFacilities" :key="facility.id">
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="facility.image ? `${$FilePath}/venue/data/${facility.image.image}` : `${$FilePath}/venue/data/${vdcategories[10].image.image}`"
                      :alt="facility.title['en']"
                    />
                    <span class="ms-3">{{facility.title['en']}}</span>
                  </div>
                </div>

                <div class="mt-2 pt-2 pb-3 border-top"></div>

                <h6 class="font-semibold">Food Diets</h6>
                <div class="col-6 pb-3" v-for="diet in diets" :key="diet.id">
                  <div class="d-flex align-items-center">
                    <img
                      class="feature-icon"
                      :src="diet.image ? `${$FilePath}/venue/data/${diet.image.image}` : `${$FilePath}/venue/data/${vdcategories[9].image.image}`"
                      :alt="diet.title['en']"
                    />
                    <span class="ms-3">{{diet.title['en']}}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- features modal end -->

      <!-- food modal start -->
      <div class="modal fade" id="meals-modal" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content rounded shadow border-0">
            <!-- <button type="button" class="btn-close m-2" data-bs-dismiss="modal" aria-label="Close"></button> -->
            <div class="modal-header border-bottom">
              <h5 class="modal-title my-0 mx-auto" id="LoginForm-title">Free Meals</h5>
              <button
                type="button"
                class="btn-close p-0 m-0"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body py-2">
              <div class="p-4 row">
                <div class="col-md-6" v-for="i in 10" :key="i">
                  <div class="d-flex align-items-center py-3">
                    <svg
                      width="23"
                      height="23"
                      viewBox="0 0 23 23"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M18.2436 4.89939C17.3382 3.9855 16.2588 3.27947 15.3408 2.8665C14.1351 2.32411 12.8192 2.04537 11.497 2.05162C9.74344 2.0597 8.01597 2.56462 6.50134 3.5118C6.29097 3.64333 6.22713 3.9205 6.35866 4.13087C6.49024 4.34124 6.76745 4.40516 6.97773 4.27354C8.35059 3.41504 9.91477 2.95738 11.5011 2.95005C13.615 2.93882 15.6253 3.71912 17.2112 5.15257L14.4454 7.91846C14.0085 7.95938 13.6111 8.04208 13.2539 8.16616C13.2712 8.07591 13.2849 7.98333 13.2939 7.88827C13.3976 6.79766 12.9002 5.69887 11.8155 4.62245C11.6404 4.44865 11.3578 4.44865 11.1826 4.62245C10.098 5.69887 9.60059 6.79766 9.70426 7.88827C9.71329 7.98337 9.72691 8.076 9.74425 8.16625C9.205 7.97892 8.57497 7.88477 7.85474 7.88728C7.60799 7.88823 7.40817 8.08804 7.40723 8.33479C7.40296 9.4525 7.63036 10.3555 8.08272 11.0336C8.00752 11.0317 7.93196 11.0301 7.85474 11.0305C7.60799 11.0314 7.40817 11.2312 7.40723 11.478C7.40283 12.6301 7.64379 13.5549 8.12441 14.2395L5.15804 17.2058C4.51211 16.4901 3.99514 15.6821 3.61888 14.798C2.53074 12.2412 2.7898 9.19366 4.3153 6.86877C4.45142 6.66136 4.3936 6.38285 4.18615 6.24674C3.97875 6.11062 3.70028 6.16844 3.56412 6.37589C1.87524 8.94982 1.58724 12.3186 2.79218 15.1498C4.28799 18.6644 7.84809 21.0255 11.6802 20.9466C16.7443 20.8485 20.9163 16.5391 20.9474 11.5028C20.9626 9.03495 20.0027 6.6938 18.2436 4.89939V4.89939ZM14.6786 8.79965C14.6115 9.80374 14.3035 10.5401 13.7602 10.9923C13.2627 11.4064 12.5856 11.5525 11.9521 11.5262C11.9343 11.0987 11.9845 10.3318 12.4746 9.73218C12.482 9.72342 12.4894 9.71466 12.4968 9.70577C12.9499 9.17025 13.6821 8.86613 14.6786 8.79965ZM8.31959 11.9428C9.32369 12.0099 10.06 12.3179 10.5122 12.8611C11.0139 13.4638 11.0641 14.2412 11.046 14.671C10.6188 14.6903 9.84999 14.6412 9.2435 14.14C8.69676 13.6883 8.38684 12.9503 8.31959 11.9428V11.9428ZM12.486 12.8611C12.9381 12.3178 13.6744 12.0098 14.6786 11.9428C14.6115 12.9469 14.3035 13.6833 13.7603 14.1354C13.1584 14.6364 12.3822 14.6871 11.9522 14.6693C11.9342 14.2385 11.9851 13.4628 12.486 12.8611V12.8611ZM11.4991 5.59631C12.1617 6.35374 12.4645 7.09226 12.4002 7.79609C12.3287 8.57697 11.8146 9.16217 11.4979 9.4533C11.0282 9.02439 10.6599 8.4475 10.5987 7.80323C10.5315 7.0972 10.8343 6.35626 11.4991 5.59631V5.59631ZM9.24346 10.9969C8.69676 10.5451 8.38684 9.80715 8.31959 8.79965C9.31592 8.86618 10.0483 9.17025 10.5013 9.70572C10.5087 9.71462 10.5161 9.72338 10.5235 9.73213C11.0144 10.3328 11.064 11.1014 11.046 11.5278C10.413 11.5565 9.74128 11.4083 9.24346 10.9969V10.9969ZM19.5801 14.2932C18.4105 17.6684 15.0476 20.0501 11.4796 20.0501C9.37175 20.05 7.36963 19.2693 5.79278 17.8417L8.74375 14.8907C9.47503 15.4572 10.331 15.5715 10.9023 15.5715C10.954 15.5715 11.0031 15.5705 11.0499 15.5689V17.5354C11.0499 17.7835 11.251 17.9846 11.4991 17.9846C11.7472 17.9846 11.9483 17.7835 11.9483 17.5354V15.5689C11.995 15.5705 12.0441 15.5715 12.0958 15.5715C12.6862 15.5715 13.5805 15.4495 14.327 14.8326C15.1715 14.1347 15.5968 13.006 15.591 11.4779C15.59 11.2312 15.3902 11.0314 15.1435 11.0304C15.1331 11.0304 15.1229 11.0304 15.1126 11.0304C15.0457 11.0304 14.9806 11.0322 14.9152 11.0339C15.3677 10.3558 15.5952 9.45268 15.591 8.33479C15.5906 8.25146 15.5672 8.17379 15.5274 8.10704L17.847 5.7875C19.2832 7.37427 20.0621 9.38538 20.0491 11.4973C20.043 12.4724 19.8852 13.4131 19.5801 14.2932Z"
                        fill="black"
                      />
                      <path
                        d="M19.6317 3.36829C17.4596 1.19622 14.5717 0 11.5 0C6.72862 0 2.33414 3.0581 0.694762 7.54566C0.232875 8.80994 0 10.1544 0 11.5C0 16.3424 3.29349 20.7482 7.81883 22.3571C8.99853 22.7765 10.2474 23 11.5 23C17.7616 23 23 17.7544 23 11.5C23 8.42829 21.8038 5.54039 19.6317 3.36829ZM20.9231 16.3614C19.1275 19.8347 15.4117 22.1016 11.5 22.1016C5.82879 22.1016 0.898438 17.1705 0.898438 11.5C0.898438 5.72076 5.72071 0.898438 11.5 0.898438C17.2792 0.898438 22.1016 5.72071 22.1016 11.5C22.1016 13.1903 21.6941 14.8713 20.9231 16.3614Z"
                        fill="black"
                      />
                      <path
                        d="M4.77766 5.25711C4.85111 5.43343 5.03403 5.54807 5.22531 5.53329C5.41268 5.51883 5.57476 5.38635 5.62516 5.20518C5.73158 4.82267 5.30231 4.49694 4.96157 4.70008C4.77299 4.81243 4.69334 5.05469 4.77766 5.25711Z"
                        fill="black"
                      />
                    </svg>
                    <div class="ms-3">Gluten Free</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- meals modal end -->
    </div>
  </section>

  <!--end section-->
  <image-gallery
    v-if="accommodationImages"
    :isOpen="showGallery"
    :categories="imageCategories"
    @close="showGallery = false"
  ></image-gallery>

  <div v-if="!loading" class="d-block d-md-none">
    <div class="floating-actions d-flex align-items-center justify-content-between px-4">
      <div @click="showFloatingPopup = !showFloatingPopup">
        <div>
          <span class="font-semibold">{{ currencySymbol + venue.price_per_night }}</span>
          night
        </div>
        <div class="text-gray-800">
          <p
            class="font-semibold text-lg mb-0"
            v-if="bookingRequest.price"
          >{{currencySymbol + bookingRequest.price.total_price}}</p>
          <p v-else class="mb-0">Choose dates</p>
        </div>
      </div>
      <button class="btn btn-dark" v-if="showFloatingPopup">Save</button>
      <button class="btn btn-warning" v-else @click="reserveBooking">Reserve</button>
    </div>
    <div
      class="floating-action-popup pt-3 px-3 d-flex flex-column"
      :class="{ open: showFloatingPopup }"
    >
      <div class="d-flex justify-content-end">
        <i @click="showFloatingPopup = false" class="uil uil-times-circle" style="font-size: 24px"></i>
      </div>
      <div class="d-flex justify-content-center flex-grow-1 align-items-center">
        <Date-Picker
          :rows="2"
          :from-page="{ month: 1, year: 2022 }"
          v-model="selectedDates"
          :model-config="modelConfig"
          :min-date="$DateTime.now()"
          :max-date="$DateTime.now().plus({years: 1})"
          :disabled-dates="[...venue.blocked_dates, ...venue.booked_dates]"
          is-range
          mode="date"
          timezone="UTC"
          is-expanded
        >
          <template #header-title="{ monthLabel }">{{monthLabel}}</template>
        </Date-Picker>
      </div>
    </div>
    <div class="dark-overlay" :class="{ open: showFloatingPopup }"></div>
  </div>
</template>

<script>
import "v-calendar/dist/style.css";
import { SetupCalendar, Calendar, DatePicker } from "v-calendar";
import ImageGallery from "../../components/ImageGallery.vue";

export default {
  components: {
    DatePicker,
    ImageGallery,
  },

  data() {
    return {
      venue: {},
      loading: true,
      observerRanFirstTime: false,
      activeMenuItem: null,
      showFloatingPopup: false,
      showDatePicker: false,
      showGallery: false,
      selectedDates: {
        start: "",
        end: "",
      },
      modelConfig: {
        type: "string",
        mask: "YYYY-MM-DD", // Uses 'iso' if missing
      },
      bookingRequest: {
        start_date: "",
        end_date: "",
        guests: 5,
      },
      totalPrice: 0,
      map: null,
      mapOptions: {
        zoomControl: true,
        mapTypeControl: true,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: true,
        fullscreenControl: false,
      },
    };
  },
  watch: {
    loading: function (newVal, oldVal) {
      if (this.$refs.mapRef) {
        this.$refs.mapRef.$mapPromise.then((map) => (this.map = map));
      }
    },
    selectedDates: function (newVal, oldVal) {
      this.bookingRequest.start_date = this.selectedDates.start;
      this.bookingRequest.end_date = this.selectedDates.end;
      this.getPrice();
      if (newVal.end != oldVal.end) {
        this.showDatePicker = false;
        console.log(this.showDatePicker);
      }
    },
    guests: function () {
      if (this.bookingRequest.start_date.length <= 0) {
        return;
      }
      this.getPrice();
    },
    showFloatingPopup(newVal, oldVal) {
      if (newVal) {
        document.body.classList.add("overflow-hidden");
      } else {
        document.body.classList.remove("overflow-hidden");
      }
    },
    activeMenuItem: function () {
      this.$nextTick(() => {
        this.scrollMenuItems();
      });
    },
    navRef: {
      deep: true,
      handler: function () {
        console.log(this.navRef);
      },
    },
  },
  methods: {
    getPrice() {
      axios
        .post(`venue/${this.venue.id}/booking/getPrice`, this.bookingRequest)
        .then((response) => {
          this.bookingRequest.price = response.data;
          this.$store.commit("UPDATE_BOOKING_REQUEST", this.bookingRequest);
        });
    },
    reserveBooking() {
      if (this.user.id == this.venue.user_id) {
        alert("you can not reserve your own venue");
        return;
      }
      this.$router.push({
        name: "venue.book",
        params: { venue: this.venue.id },
      });
    },

    firstFiveImages(images) {
      let fiveImages = [];
      for (let i = 0; i < images.length; i++) {
        if (i == 5) {
          return fiveImages;
        }
        if (images[i].type != 1) {
          fiveImages.push(images[i]);
        }
      }
      return fiveImages;
    },

    propertyType(propertyTypeId) {
      const index = this.propertyTypes.findIndex(
        (pt) => pt.id == propertyTypeId
      );
      return this.propertyTypes[index].title;
    },

    calculateVenuePriceWithDiscount(percentage) {
      return (
        this.venue.price_per_night -
        (this.venue.price_per_night * percentage) / 100
      );
    },

    goToPart(id) {
      let element = document.getElementById(id);
      this.activeMenuItem = id;
      window.scrollTo(0, element.offsetTop - 160);
    },

    scrollMenuItems() {
      let element = document.querySelector(`a[href="#${this.activeMenuItem}"]`);
      let list = document.querySelector("ul.nav");
      list.scrollTo({ left: element.offsetLeft - 20, behavior: "smooth" });
    },

    closeDatePicker() {
      this.showDatePicker = false;
    },
    transferAvailablity(airportPivot) {
      switch (airportPivot.type) {
        case 0:
          return "Not provided";
        case 1:
          return "Free";
        case 2:
          return `Additional ${
            this.currencySymbol + airportPivot.charge
          } per guest`;

        default:
          break;
      }
    },
  },
  computed: {
    navRef() {
      console.log(this.$refs.nav);
      return this.$refs.nav;
    },
    bannerImage() {
      return (images) =>
        images[images.findIndex((img) => img.type == 1)]
          ? images[images.findIndex((img) => img.type == 1)].image
          : "";
    },
    propertyTypes() {
      return this.$store.getters.propertyTypes;
    },
    maxGuests() {
      if (!this.venue.accommodations) {
        return 0;
      }
      let max_guests = 0;
      this.venue.accommodations.forEach((acc) => {
        max_guests += acc.quantity * acc.max_guests;
      });
      return max_guests;
    },
    guests() {
      return this.bookingRequest.guests;
    },
    user() {
      return this.$store.getters.user ? this.$store.getters.user.user : null;
    },
    foods() {
      return this.venue.venue_data.filter(
        (vd) => vd.venue_data_category_id == 10
      );
    },
    amenities() {
      return this.venue.venue_data.filter((vd) =>
        [1, 2, 3].includes(vd.venue_data_category_id)
      );
    },
    keyPropertyFeatures() {
      return this.venue.venue_data.filter(
        (vd) => vd.venue_data_category_id == 1
      );
    },
    generalAmenities() {
      return this.venue.venue_data.filter(
        (vd) => vd.venue_data_category_id == 2
      );
    },
    indoorAmenities() {
      return this.venue.venue_data.filter(
        (vd) => vd.venue_data_category_id == 3
      );
    },
    outdoorAmenities() {
      return this.venue.venue_data.filter(
        (vd) => vd.venue_data_category_id == 4
      );
    },
    surroundings() {
      return this.venue.venue_data.filter(
        (vd) => vd.venue_data_category_id == 7
      );
    },
    thingsToDo() {
      return this.venue.venue_data.filter(
        (vd) => vd.venue_data_category_id == 8
      );
    },
    rentals() {
      return this.venue.venue_data.filter(
        (vd) => vd.venue_data_category_id == 9
      );
    },
    diets() {
      return this.venue.venue_data.filter(
        (vd) => vd.venue_data_category_id == 10
      );
    },
    kitchenFacilities() {
      return this.venue.venue_data.filter(
        (vd) => vd.venue_data_category_id == 11
      );
    },
    allFeaturesCount() {
      return (
        this.rentals.length +
        this.thingsToDo.length +
        this.surroundings.length +
        this.amenities.length
      );
    },
    foodOptionsCount() {
      return this.diets.length + this.kitchenFacilities.length;
    },
    currencySymbol() {
      const index = this.$store.getters.currencies.findIndex(
        (curreny) => curreny.id == this.venue.currency_id
      );
      return this.$store.getters.currencies[index].symbol;
    },
    retreatOrganizerText() {
      if (
        this.venue.free_accommodation_for_retreat_organizer == 1 &&
        this.venue.free_meal_for_retreat_organizer == 1
      ) {
        return "Free accommodation and meals for retreat Organizer";
      } else if (
        this.venue.free_accommodation_for_retreat_organizer == 1 &&
        this.venue.free_meal_for_retreat_organizer == 0
      ) {
        return "Free accommodation for retreat organizer";
      } else if (
        this.venue.free_accommodation_for_retreat_organizer == 0 &&
        this.venue.free_meal_for_retreat_organizer == 1
      ) {
        return "Free meals for retreat organizer";
      } else {
        return false;
      }
    },
    freeMealForRetreatOrganizerRequirements() {
      if (
        this.venue.min_guests_for_free_meal > 0 &&
        this.venue.min_nights_for_free_meal > 0
      ) {
        return `Minimum ${this.venue.min_guests_for_free_meal} guests or minimum ${this.venue.min_nights_for_free_meal} nights`;
      } else if (this.venue.min_guests_for_free_meal > 0) {
        return `Minimum ${this.venue.min_guests_for_free_meal} guests`;
      } else if (this.venue.min_nights_for_free_meal > 0) {
        return `Minimum ${this.venue.min_nights_for_free_meal} nights`;
      }
    },
    cpolicy() {
      return this.venue.cancellation_policy.cancellation_policy_data_id
        ? this.venue.cancellation_policy.cancellation_policy_data
        : this.venue.cancellation_policy;
    },
    vdcategories() {
      return this.$store.getters.venueDataCategories;
    },

    /**
     * @returns String
     */
    nightsLeft() {
      const fromDate = new Date(this.selectedDates.start);
      const toDate = new Date(this.selectedDates.end);

      const diff = toDate.getTime() - fromDate.getTime();
      const nights = Math.round(diff / (1000 * 60 * 60 * 24));
      return nights;
    },

    accommodationImages() {
      if (!this.venue.accommodations) {
        return null;
      }
      let allImages = [];
      this.venue.accommodations.forEach(({ images }) => {
        allImages.push(...images);
      });
      return allImages;
    },
    featureImages() {
      return this.venue.images.filter((image) =>
        [2, 3, 4].includes(image.type)
      );
    },
    indoorYogaImages() {
      return this.venue.images.filter((image) => image.type == 5);
    },
    outdoorYogaImages() {
      return this.venue.images.filter((image) => image.type == 6);
    },
    surroundingImages() {
      return this.venue.images.filter((image) => image.type == 7);
    },
    thingsToDoImages() {
      return this.venue.images.filter((image) => image.type == 8);
    },
    rentalsImages() {
      return this.venue.images.filter((image) => image.type == 9);
    },
    foodImages() {
      return this.venue.images.filter((image) => image.type == 10);
    },
    kitchenItemImages() {
      return this.venue.images.filter((image) => image.type == 11);
    },

    imageCategories() {
      let categories = [];
      if (this.featureImages.length) {
        categories.push({
          code: "features",
          title: "Features",
          images: this.featureImages,
        });
      }
      if (this.indoorYogaImages.length) {
        categories.push({
          code: "indoorYoga",
          title: "Indoor Yoga",
          images: this.indoorYogaImages,
        });
      }
      if (this.outdoorYogaImages.length) {
        categories.push({
          code: "outdoorYoga",
          title: "Outdoor Yoga",
          images: this.outdoorYogaImages,
        });
      }
      if (this.surroundingImages.length) {
        categories.push({
          code: "surroundings",
          title: "Surroundings",
          images: this.surroundingImages,
        });
      }
      if (this.thingsToDoImages.length) {
        categories.push({
          code: "thingsToDo",
          title: "Things To Do",
          images: this.thingsToDoImages,
        });
      }
      if (this.rentalsImages.length) {
        categories.push({
          code: "rentals",
          title: "Rentals",
          images: this.rentalsImages,
        });
      }
      if (this.foodImages.length) {
        categories.push({
          code: "foods",
          title: "Foods",
          images: this.foodImages,
        });
      }
      if (this.kitchenItemImages.length) {
        categories.push({
          code: "kitchenItems",
          title: "Kitchen Items",
          images: this.kitchenItemImages,
        });
      }
      if (this.accommodationImages.length) {
        categories.push({
          code: "accommodations",
          title: "Accommodations",
          images: this.accommodationImages,
        });
      }
      return categories;
    },
  },
  mounted() {
    if (this.$route.params.venue) {
      axios
        .get(`venue/show/${this.$route.params.venue}`)
        .then(({ data }) => {
          this.venue = data;
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
          this.$router.push({ name: "home" });
        });
    }
  },
};
</script>

<style scoped lang="scss">
.border-md-left {
  border-left: 1px solid #e9ecef !important;
}
.container {
  max-width: 1280px !important;
}

.text-gray-800 {
  color: #212529;
}

.text-gray-600 {
  color: #535b62;
}

.banner-image {
  height: 60vh;
  object-fit: cover;
}

.top-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  grid-gap: 1rem;
}

@media (max-width: 768px) {
  .top-grid {
    grid-template-columns: 1fr;
    grid-gap: 36px;
  }
  .banner-image {
    height: 26vh;
  }

  .image-grid {
    grid-template-areas:
      "image-1 image-2"
      "image-3 image-4"
      "image-5 image-5" !important;
    grid-template-columns: 1fr 1fr !important;
    grid-template-rows: 1fr 1fr 1fr !important;
  }

  .image-1,
  .image-2,
  .image-3,
  .image-4,
  .image-5 {
    border-radius: 0 !important;
  }

  .border-md-left {
    border-left: none !important;
  }
}

h5 {
  font-family: gilroy-medium !important;
}

h3 {
  line-height: 0.9;
}

.text-lg {
  font-size: 18px !important;
}

.text-sm {
  font-size: 14px;
}

p {
  max-width: 67ch;
}

svg.icon {
  height: 1.5rem;
  width: 1.5rem;
}

.details-menu {
  position: sticky;
  top: 74px;
  z-index: 5;
  overflow: auto;
}
.details-menu .nav {
  white-space: nowrap;
  overflow-x: auto;
}

.details-menu .menu-item {
  display: inline-block;
  transition: all 0.2s ease;
  min-width: max-content;
  white-space: nowrap;
  cursor: pointer;
  border-bottom: 3px solid transparent;
  &:hover:not(:focus) {
    border-bottom: 3px solid #212121;
  }
}

.menu-item.active {
  border-bottom: 3px solid #212121;
}

.image-grid {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
  grid-template-rows: 1fr 1fr 1fr 1fr;
  gap: 10px 10px;
  height: 60vh;
  grid-template-areas:
    "image-1 image-1 image-1 image-2 image-2 image-3 image-3"
    "image-1 image-1 image-1 image-2 image-2 image-3 image-3"
    "image-1 image-1 image-1 image-4 image-4 image-5 image-5"
    "image-1 image-1 image-1 image-4 image-4 image-5 image-5";
  .image {
    background: lightblue;
    overflow: hidden;
    img {
      object-fit: cover;
      vertical-align: bottom;
    }
  }
}

.image-1 {
  grid-area: image-1;
  border-top-left-radius: 16px;
  border-bottom-left-radius: 16px;
}
.image-2 {
  grid-area: image-2;
}
.image-3 {
  grid-area: image-3;
  border-top-right-radius: 16px;
}
.image-4 {
  grid-area: image-4;
}
.image-5 {
  grid-area: image-5;
  border-bottom-right-radius: 16px;
}

.check-date-picker {
  top: 72px;
  right: 0;
  background-color: white;
  border: 1px solid #cbd5e0;
  padding: 1.6rem;
  border-radius: 10px;
  z-index: 9;
}

.vc-container {
  border: none !important;
}

.cursor-pointer {
  cursor: pointer;
}

.gallery-btn {
  position: absolute;
  right: 20px;
  bottom: 20px;
}
.feature-icon {
  width: 1.5rem;
}
#map {
  height: 300px;
}
.vue-map-container {
  height: 100%;
}

.floating-actions {
  position: fixed;
  bottom: 0;
  z-index: 99999;
  height: 80px;
  width: 100%;
  background-color: #fff;
  border-top: 1px solid #ebebeb;
}

.floating-action-popup {
  position: fixed;
  bottom: 0;
  z-index: 9999;
  height: calc(100vh - 120px);
  width: 100%;
  background-color: rgb(255, 255, 255);
  border-radius: 12px 12px 0 0;
  transition: all 150ms ease;
  transform: translateY(100%);
  &.open {
    transform: translateY(-80px);
  }
}

.dark-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 999;
  opacity: 0;
  transition: all 1s ease;
  &.open {
    opacity: 1;
    display: block;
  }
}
.nav-link {
  all: initial;
  * {
    all: unset;
  }
}
</style>
