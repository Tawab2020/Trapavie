<template>
  <section class="section">
    <div class="container">
      <h3 class="text-gray-800 font-semibold">Thank you for booking with Tripavie</h3>
      <h3 class="text-gray-800 font-semibold">Your booking has been paid in full successfully</h3>
      <div id="booking-information" class="mt-4 p-3 p-md-5">
        <!-- Venue information -->
        <div id="venue-information" class="mb-2 mb-md-5" v-if="booking.venue">
          <div class="row">
            <div class="col-12 col-md-4">
              <img
                class="w-100 d-block rounded-md banner-image"
                :src="bannerImage(booking.venue.images) ? `${$FilePath}/venue/${bannerImage(booking.venue.images)}` : '/assets/user/frontend/images/default.jpeg'"
                alt="banner image"
              />
            </div>
            <div class="col-12 col-md-8">
              <h5 class="font-bold mt-3 mt-md-0">{{booking.venue_log.name}}</h5>
              <span
                class="font-semibold"
              >{{booking.venue_log.street}}, {{booking.venue_log.city}}, {{booking.venue_log.state}}, {{booking.venue_log.country}}</span>
              <span
                class="d-block"
                v-for="accommodation in booking.venue.accommodations"
                :key="accommodation.id"
              >{{accommodation.name}}, {{accommodation.bedrooms.length}} bedrooms X {{accommodation.quantity}}</span>
              <div class="mt-3 pt-3 border-top"></div>
              <div class="row d-flex align-items-center">
                <div class="col-12 col-md-6">
                  <h6
                    class="font-semibold"
                  >{{$DateTime.fromISO(booking.start_date).toLocaleString($DateTime.DATE_MED)}} to {{$DateTime.fromISO(booking.end_date).toLocaleString($DateTime.DATE_MED)}}</h6>
                  <h6 class="font-semibold">{{booking.guests}} Guests</h6>
                </div>
                <div class="col-12 col-md-6">
                  <h4 class="font-semibold">Total: ${{booking.price.total_price}}</h4>
                  <h6
                    class="font-semibold"
                  >Initial Deposit: ${{booking.price.price_to_secure_booking}}</h6>
                  <h6
                    class="font-semibold"
                  >You paid (Remaining Balance): ${{booking.price.remaining_balance}}</h6>
                </div>
              </div>

              <div class="mt-3 pt-3 border-top"></div>
            </div>
          </div>
        </div>
        <br />
      </div>
      <div class="d-flex justify-content-center text-center mt-5">
        <router-link :to="{name: 'home'}">
          <button class="btn btn-warning">Go to homepage</button>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {};
  },
  methods: {
    bannerImage(images) {
      return images[images.findIndex((img) => img.type == 1)]
        ? images[images.findIndex((img) => img.type == 1)].image
        : "";
    },
  },
  computed: {
    booking() {
      let booking = this.$store.getters.venueBooking;
      if (booking.status != 3) {
        this.$router.push({ name: "home" });
      }
      return booking;
    },
    activeLanguage() {
      return this.$store.getters.activeLocale;
    },
  },
  mounted() {
    // only show this page once after the booking is successfull and paid for.
    if (!this.booking.id) {
      this.$router.push({ name: "home" });
    }
  },
};
</script>

<style>
</style>
