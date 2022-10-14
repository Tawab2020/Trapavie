<template>
  <!-- Hero End -->
  <section class="section venues-list">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 text-center">
          <div class="section-title mb-4 pb-2">
            <h4
              class="mb-4 float-start"
              v-if="venueBookings.length"
            >{{venueBookings.length}} Bookings</h4>
          </div>
        </div>
        <!--end col-->
      </div>
      <!--end row-->

      <div class="row" v-if="venueBookings.length">
        <div class="col-12 col-md-4">
          <ul
            class="nav nav-pills nav-justified flex-column flex-sm-row rounded"
            id="pills-tab"
            role="tablist"
          >
            <li class="nav-item">
              <a
                class="bookings-typ-nav-link active"
                id="pills-upcoming-tab"
                data-bs-toggle="pill"
                href="#pills-upcoming"
                role="tab"
                aria-controls="pills-upcoming"
                aria-selected="false"
              >
                <div class="text-center py-2">
                  <h6 class="mb-0">Upcoming</h6>
                </div>
              </a>
              <!--end nav link-->
            </li>
            <!--end nav item-->

            <li class="nav-item">
              <a
                class="booking-type-nav-link"
                id="pills-past-tab"
                data-bs-toggle="pill"
                href="#pills-past"
                role="tab"
                aria-controls="pills-past"
                aria-selected="false"
              >
                <div class="text-center py-2">
                  <h6 class="mb-0">Past</h6>
                </div>
              </a>
              <!--end nav link-->
            </li>
            <!--end nav item-->

            <li class="nav-item">
              <a
                class="booking-type-nav-link"
                id="pills-cancelled-tab"
                data-bs-toggle="pill"
                href="#pills-cancelled"
                role="tab"
                aria-controls="pills-cancelled"
                aria-selected="false"
              >
                <div class="text-center py-2">
                  <h6 class="mb-0">Cancelled</h6>
                </div>
              </a>
              <!--end nav link-->
            </li>
            <!--end nav item-->
          </ul>
        </div>
        <div class="col-12">
          <div class="tab-content" id="pills-tabContent">
            <div
              class="tab-pane fade show active"
              id="pills-upcoming"
              role="tabpanel"
              aria-labelledby="pills-upcoming-tab"
            >
              <table
                class="table table-hover mb-0 table-center"
                v-if="upcomingVenueBookings.length"
              >
                <thead>
                  <tr class="font-semibold">
                    <th scope="col" class="border-bottom">Retreat Leader</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Guests</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Property Name</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Dates</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Booked on</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Total Payout</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Status</th>
                    <th scope="col" class="border-bottom">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="booking in upcomingVenueBookings" :key="booking.id">
                    <td
                      class="d-none d-md-table-cell"
                    >{{booking.user.fname + " " + booking.user.lname}}</td>
                    <td class="d-none d-md-table-cell">{{booking.guests}}</td>
                    <td class="d-none d-md-table-cell">{{booking.venue_log.name}}</td>
                    <td class="d-none d-md-table-cell">
                      {{booking.start_date}}
                      <br />
                      {{booking.end_date}}
                    </td>
                    <td
                      class="d-none d-md-table-cell"
                      :key="dateTimeKey"
                    >{{$DateTime.fromISO(booking.created_at).toLocaleString($DateTime.DATE_MED)}}</td>
                    <td
                      class="d-none d-md-table-cell"
                      v-show="booking.price.currency.symbol"
                    >{{booking.price.currency.symbol}}{{booking.price.total_price}}</td>
                    <td class="d-none d-md-table-cell">{{bookingStatus(booking)}}</td>
                    <td class="d-none d-md-table-cell">
                      <!-- drop down -->
                      <div class="dropdown dropdown-primary d-none d-md-block">
                        <div
                          class="border border-white d-flex bg-white justify-content-center align-items-center rounded-pill p-2 pointer"
                          style="width: 64px;"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i
                            class="uil uil-bars text-dark"
                            style="font-size: 1.4rem; line-height:0;"
                          ></i>
                        </div>
                        <div
                          class="dropdown-menu dd-menu shadow-lg dropdown-menu-end bg-white shadow rounded border-0 mt-3 py-3"
                          style="width: 200px;"
                        >
                          <router-link
                            :to="{name: 'hosting.booking.details', params: {venueBooking: booking.id}}"
                            class="dropdown-item text-dark"
                          >
                            <i class="uil uil-file-info-alt align-middle me-1"></i>
                            Details
                          </router-link>

                          <span
                            class="dropdown-item pointer"
                            @click="confirmVenueBooking(booking.id)"
                            v-if="booking.status == 0"
                          >
                            <i class="uil uil-check align-middle me-1"></i>
                            Confirm Booking
                          </span>

                          <span
                            href="javascript:void(0)"
                            class="dropdown-item pointer"
                            @click="rejectVenueBooking(booking.id)"
                            v-if="booking.status == 0"
                          >
                            <i class="uil uil-times align-middle me-1"></i>
                            Reject Booking
                          </span>

                          <span class="dropdown-item pointer" @click="contactHost(booking.user_id)">
                            <i class="uil uil-envelope align-middle me-1"></i>
                            Contact Host
                          </span>
                        </div>
                      </div>
                      <!-- drop down end -->
                    </td>
                  </tr>
                </tbody>
              </table>
              <h5 v-else class="text-center">No upcoming bookings.</h5>
            </div>
            <!--end teb pane-->

            <div
              class="tab-pane fade"
              id="pills-past"
              role="tabpanel"
              aria-labelledby="pills-past-tab"
            >
              <table class="table table-hover mb-0 table-center" v-if="pastVenueBookings.length">
                <thead>
                  <tr class="font-semibold">
                    <th scope="col" class="border-bottom">Retreat Leader</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Guests</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Property Name</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Dates</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Booked on</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Total Payout</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Status</th>
                    <th scope="col" class="border-bottom">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="booking in pastVenueBookings" :key="booking.id">
                    <td
                      class="d-none d-md-table-cell"
                    >{{booking.user.fname + " " + booking.user.lname}}</td>
                    <td class="d-none d-md-table-cell">{{booking.guests}}</td>
                    <td class="d-none d-md-table-cell">{{booking.venue_log.name}}</td>
                    <td class="d-none d-md-table-cell">
                      {{booking.start_date}}
                      <br />
                      {{booking.end_date}}
                    </td>
                    <td
                      class="d-none d-md-table-cell"
                      :key="dateTimeKey"
                    >{{new Date(booking.created_at).getYear() == new Date().getYear() ? this.$DateTime.fromISO(booking.created_at).toLocaleString($DateTime.DATE_MED) : this.$DateTime.fromISO(booking.created_at).toLocaleString($DateTime.DATE_SHORT)}}</td>
                    <td
                      class="d-none d-md-table-cell"
                      v-show="booking.price.currency.symbol"
                    >{{booking.price.currency.symbol}}{{booking.price.total_price}}</td>
                    <td class="d-none d-md-table-cell">{{bookingStatus(booking)}}</td>
                    <td class="d-none d-md-table-cell">
                      <a
                        href="javascript:void(0)"
                        class="btn btn-sm btn-secondary"
                        @click="contactHost(booking.user_id)"
                      >Contact Host</a>
                    </td>
                  </tr>
                </tbody>
              </table>
              <h5 v-else class="text-center">No past bookings.</h5>
            </div>
            <!--end teb pane-->

            <div
              class="tab-pane fade"
              id="pills-cancelled"
              role="tabpanel"
              aria-labelledby="pills-cancelled-tab"
            >
              <table
                class="table table-hover mb-0 table-center"
                v-if="cancelledVenueBookings.length"
              >
                <thead>
                  <tr class="font-semibold">
                    <th scope="col" class="border-bottom">Retreat Leader</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Guests</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Property Name</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Dates</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Booked on</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Total Payout</th>
                    <th scope="col" class="d-none d-md-table-cell border-bottom">Status</th>
                    <th scope="col" class="border-bottom">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="booking in cancelledVenueBookings" :key="booking.id">
                    <td
                      class="d-none d-md-table-cell"
                    >{{booking.user.fname + " " + booking.user.lname}}</td>
                    <td class="d-none d-md-table-cell">{{booking.guests}}</td>
                    <td class="d-none d-md-table-cell">{{booking.venue_log.name}}</td>
                    <td class="d-none d-md-table-cell">
                      {{booking.start_date}}
                      <br />
                      {{booking.end_date}}
                    </td>
                    <td
                      class="d-none d-md-table-cell"
                      :key="dateTimeKey"
                    >{{new Date(booking.created_at).getYear() == new Date().getYear() ? this.$DateTime.fromISO(booking.created_at).toLocaleString($DateTime.DATE_MED) : $DateTime.fromISO(booking.created_at).toLocaleString($DateTime.DATE_SHORT)}}</td>
                    <td
                      class="d-none d-md-table-cell"
                      v-show="booking.price.currency.symbol"
                    >{{booking.price.currency.symbol}}{{booking.price.total_price}}</td>
                    <td class="d-none d-md-table-cell">{{bookingStatus(booking)}}</td>
                    <td class="d-none d-md-table-cell">Confirm</td>
                  </tr>
                </tbody>
              </table>
              <h5 v-else class="text-center">No cancelled bookings.</h5>
            </div>
            <!--end teb pane-->
          </div>
          <!--end tab content-->
        </div>
      </div>
      <!--end row-->

      <div class="row" v-else>
        <div class="col-12 text-center">
          <h5>There are no bookings for your venues.</h5>
        </div>
      </div>
    </div>
    <!--end container-->
  </section>
  <!--end section-->
</template>

<script>
export default {
  data() {
    return {
      venueBookings: [],
    };
  },
  methods: {
    contactHost(userId) {
      this.$router.push({
        name: "messages.index",
        params: { user: userId, senderType: 1, receiverType: 0 },
      });
    },
    confirmVenueBooking(bookingId) {
      axios
        .get(`hosting/bookings/confirmVenueBooking/${bookingId}`)
        .then((response) => {
          const index = this.venueBookings.findIndex(
            (venueBooking) => venueBooking.id == bookingId
          );
          this.venueBookings[index] = response.data;
          this.$toast.success("Booking confirmed.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          console.log(error);
          this.$toast.error("Booking confirmation failed.", {
            position: "top-right",
          });
        });
    },
    rejectVenueBooking(bookingId) {
      axios
        .get(`hosting/bookings/rejectVenueBooking/${bookingId}`)
        .then((response) => {
          const index = this.venueBookings.findIndex(
            (venueBooking) => venueBooking.id == bookingId
          );
          this.venueBookings[index] = response.data;
          this.$toast.warning("Booking was rejected.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          console.log(error);
          this.$toast.error("Booking could not be rejected.", {
            position: "top-right",
          });
        });
    },
    bookingStatus(booking) {
      switch (booking.status) {
        case 0:
          return "Requested";
        case 1:
          return "Confirmed";
        case 2:
          return "Deposit Paid";
        case 3:
          return "Fully Paid";
        case 4:
          return "Rejected";
        case 5:
          return "Cancelled by";
        case 6:
          return "Cancelled by RO";
        case 7:
          return "Refunded";
        default:
          break;
      }
    },
    getCurrencySymbol(currencyId) {
      const index = this.currencies.findIndex(
        (curreny) => curreny.id == currencyId
      );
      return this.currencies[index].symbol;
    },
  },
  computed: {
    activeLanguage() {
      return this.$store.getters.activeLocale;
    },
    currencies() {
      return this.$store.getters.currencies;
    },
    dateTimeKey() {
      return this.$store.getters.dateTimeKey;
    },
    upcomingVenueBookings() {
      return this.venueBookings.filter(
        (booking) =>
          this.$DateTime.fromISO(booking.start_date) > this.$DateTime.now()
      );
    },
    pastVenueBookings() {
      return this.venueBookings.filter(
        (booking) =>
          this.$DateTime.fromISO(booking.start_date) < this.$DateTime.now()
      );
    },
    cancelledVenueBookings() {
      return this.venueBookings.filter(
        (booking) => booking.status == 5 || booking.status == 6
      );
    },
  },
  mounted() {
    axios.get(`hosting/bookings/listVenueBookings`).then((response) => {
      this.venueBookings = response.data;
    });
  },
};
</script>

<style>
.nav-pills a {
  color: black !important;
}

.nav-pills a.active {
  display: inline-block;
  border-bottom: 2px solid black;
}
</style>
