<template>
  <section class="section">
    <div class="container" v-if="venue.images">
      <h3 class="text-gray-800 font-semibold">Complete your booking request</h3>
      <div id="booking-information" class="mt-4">
        <!-- Venue information -->
        <div id="venue-information" class="mb-5">
          <div class="row">
            <div class="col-12 col-md-4">
              <img
                class="w-100 d-block rounded-md banner-image"
                :src="bannerImage(venue.images) ? `${$FilePath}/venue/${bannerImage(venue.images)}` : '/assets/user/frontend/images/default.jpeg'"
                alt="banner image"
              />
            </div>
            <div class="col-12 col-md-8">
              <h5 class="font-bold">{{venue.name}}</h5>
              <h6
                class="fw-bold"
              >{{venue.street}}, {{venue.city}}, {{venue.state}}, {{venue.country}}</h6>
              <h6
                v-for="accommodation in venue.accommodations"
                :key="accommodation.id"
              >{{accommodation.name}}, {{accommodation.bedrooms.length}} bedrooms X {{accommodation.quantity}}</h6>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- Dates and guests -->
          <div id="dates-and-guests" class="col-12 col-md-6 mb-5">
            <h4 class="font-semibold">Dates and guests</h4>
            <span class="text-muted">I will change this design later.</span>
            <div class="row" :key="dateTimeKey">
              <div class="col-12 d-flex mt-3">
                <div class="me-3">
                  <h6>Check in</h6>
                  <div class="p-2 border text-sm" style="border-right: 1px solid #e9ecef">
                    <div>{{$DateTime.fromISO(booking.start_date).toLocaleString($DateTime.DATE_MED)}}</div>
                  </div>
                </div>
                <div class="me-3">
                  <h6>Check out</h6>
                  <div class="p-2 border text-sm" style="border-right: 1px solid #e9ecef">
                    <span>{{$DateTime.fromISO(booking.end_date).toLocaleString($DateTime.DATE_MED)}}</span>
                  </div>
                </div>
                <div class="me-3">
                  <h6>Guests</h6>
                  <div class="p-2 border text-sm" style="border-right: 1px solid #e9ecef">
                    <span>{{booking.guests}} Guests</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-5 pt-5 border-top d-block d-md-none"></div>
          <div class="col-12 col-md-6">
            <h4 class="font-semibold">Price Details</h4>
            <div class="row me-md-3 pe-md-3 me-lg-5 pe-lg-5 mt-3" v-if="booking.price">
              <div class="col-12">
                <span>{{currencySymbol}}{{pricePerNight}} x {{numberOfDays}} days</span>
                <span class="float-end">{{currencySymbol}}{{pricePerNight * numberOfDays}}</span>
              </div>
              <div class="col-12" v-if="booking.price.venue_price.discount_percentage > 0">
                <span
                  class="text-success"
                >{{booking.price.venue_price.discount_percentage}}% Length of stay discount</span>
                <span
                  class="float-end text-success"
                >-{{currencySymbol}}{{booking.price.venue_price.total_discount}}</span>
              </div>
              <div class="col-12" v-if="booking.price.season_pricing.total_days > 0">
                <span
                  :class="booking.price.season_pricing.total_price > 0 ? '' : 'text-success'"
                >Net season pricing for {{booking.price.season_pricing.total_days}} days</span>
                <span
                  class="float-end"
                  :class="booking.price.season_pricing.total_price > 0 ? '' : 'text-success'"
                >{{booking.price.season_pricing.total_price > 0 ? '' : '-'}} {{currencySymbol}}{{booking.price.season_pricing.total_price}}</span>
              </div>
              <div class="mt-3 pt-3 border-top"></div>
              <div class="col-12">
                <span>Meals Pricing</span>
                <span
                  class="float-end"
                >{{currencySymbol}}{{booking.price.meals_price.discount_percentage > 0 ? booking.price.meals_price.total_discount + booking.price.meals_price.total_price : booking.price.meals_price.total_price}}</span>
              </div>
              <div class="col-12" v-if="booking.price.meals_price.total_discount > 0">
                <span
                  class="text-success"
                >{{booking.price.meals_price.discount_percentage}}% number of guests discount</span>
                <span
                  class="float-end text-success"
                >-{{currencySymbol}}{{booking.price.meals_price.total_discount}}</span>
              </div>
              <div class="mt-3 pt-3 border-top"></div>
              <div class="col-12">
                <span>Service fees</span>
                <span
                  class="float-end"
                >{{currencySymbol}}{{booking.price.fees.admin_fees + booking.price.fees.transaction_fees}}</span>
              </div>
              <div class="mt-3 pt-3 border-top"></div>
              <div class="col-12">
                <span>Taxes (for design only)</span>
                <span class="float-end">{{currencySymbol}}50</span>
              </div>
              <div class="mt-3 pt-3 border-top"></div>
              <div class="col-12">
                <h6 class="d-inline-block font-bold">Total:</h6>
                <h6
                  class="d-inline-block font-bold float-end"
                >{{currencySymbol}}{{booking.price.total_price}}</h6>
              </div>
              <div class="col-12">
                <h5 class="d-inline-block font-bold">You Pay:</h5>
                <h5
                  class="d-inline-block font-bold float-end"
                >{{currencySymbol}}{{booking.price.price_to_secure_booking}}</h5>
                <span class="float-end me-2">({{depositPercentage}}%)</span>
              </div>
            </div>
          </div>
        </div>
        <!-- Message -->
        <div id="message-host">
          <h4 class="fw-bold">Message Host</h4>
          <div class="col-12">
            <span>Include any question or questions you may have for your stay.</span>
            <textarea name class="form-control shadow mt-2" id cols="30" rows="5" v-model="message"></textarea>
          </div>
        </div>
        <button
          class="btn btn-warning mt-3 float-end mb-4"
          :disabled="processing"
          @click="bookVenue()"
        >Send booking request</button>
        <br />
      </div>
    </div>
    <!-- Booking Request sent modal start -->
    <div class="modal fade" id="meals-modal" ref="requestSuccessModal" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content rounded shadow border-0">
          <!-- <button type="button" class="btn-close m-2" data-bs-dismiss="modal" aria-label="Close"></button> -->
          <div class="modal-header border-bottom">
            <h5 class="modal-title my-0 mx-auto" id="LoginForm-title">Request Sent</h5>
          </div>
          <div class="modal-body py-2">
            <div class="p-4 row">
              <div class="col-12">
                <p>Your booking request has been sent to the Venue Host. You will be notified once the venue host has confirmed your booking request.</p>
                <router-link :to="{name: 'home'}">
                  <button class="btn btn-outline-warning">Got it</button>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Booking request sent modal end -->
  </section>
</template>

<script>
import "v-calendar/dist/style.css";
import { SetupCalendar, Calendar, DatePicker } from "v-calendar";

export default {
  components: {
    DatePicker,
  },
  data() {
    return {
      venue: {},
      processing: false,
      message: "",
    };
  },

  methods: {
    bookVenue() {
      this.processing = true;
      axios
        .post(`venue/${this.venue.id}/book`, this.booking)
        .then((response) => {
          this.$toast.success("Booking request sent.", {
            position: "top-right",
          });
          this.openSuccessModal();
          this.processing = false;
          axios
            .post(`venue/${this.venue.id}/sendMessage`, {
              message: this.message,
            })
            .then((response) => {
              console.log(response.data);
            });
        })
        .catch((error) => {
          this.$toast.error("Could not send booking request.", {
            position: "top-right",
          });
          this.processing = false;
        });
    },
    bannerImage(images) {
      return images[images.findIndex((img) => img.type == 1)]
        ? images[images.findIndex((img) => img.type == 1)].image
        : "";
    },
    openSuccessModal() {
      let modal = new bootstrap.Modal(this.$refs.requestSuccessModal, {
        backdrop: "static",
        keyboard: false,
      });
      modal.show();
    },
  },

  computed: {
    booking() {
      return this.$store.getters.bookingRequest;
    },
    numberOfDays() {
      var a = this.$DateTime.fromISO(this.booking.start_date);
      var b = this.$DateTime.fromISO(this.booking.end_date);
      return b.diff(a, "days").toObject().days;
    },
    pricePerNight() {
      return this.venue.guests_included
        ? this.venue.additional_guest_price
        : this.venue.price_per_night;
    },
    depositPercentage() {
      return this.venue.cancellation_policy.cancellation_policy_data_id > 0
        ? this.venue.cancellation_policy.cancellation_policy_data.deposit
        : this.venue.cancellation_policy.deposit;
    },
    currencySymbol() {
      return this.booking.price.currency.symbol;
    },
    dateTimeKey() {
      return this.$store.getters.dateTimeKey;
    },
  },

  mounted() {
    if (this.$route.params.venue) {
      axios
        .get(`venue/show/${this.$route.params.venue}`)
        .then(({ data }) => {
          this.venue = data;
          if (!this.booking.guests) {
            this.$router.push({
              name: "venue.show",
              params: { venue: this.venue.id },
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
};
</script>

<style>
#booking-information {
  border: 1px solid #dee2e6;
  border-radius: 1rem;
  padding: 3rem;
}
</style>
