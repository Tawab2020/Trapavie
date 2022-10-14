<template>
  <!-- Hero End -->
  <section class="section venues-list">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 text-center">
          <div class="section-title mb-4 pb-2">
            <h4 class="mb-4 float-start">{{booking.venue_log.name}} booking details</h4>
          </div>
        </div>
        <!--end col-->
      </div>
      <!--end row-->
      <div class="row">
        <div class="col-12">
          <h5 class="font-semibold">{{booking.user.fname + ' ' + booking.user.lname}}</h5>
          <p>
            <span class="font-semibold">{{booking.start_date + ' to ' + booking.end_date}}</span>
          </p>
          <p>
            <span class="font-semibold">{{booking.guests}} Guests</span>
          </p>
          <p>
            <span class="font-semibold">Date Confirmed:&nbsp;</span>
            <span
              v-if="booking.confirmed_at"
            >{{$DateTime.fromISO(booking.confirmed_at).toLocaleString($DateTime.DATETIME_MED )}}</span>
            <span v-if="!booking.confirmed_at">Not confirmed yet.</span>
          </p>
          <p>
            <span class="font-semibold" :key="dateTimeKey">Total Amount:&nbsp;</span>
            <span>{{booking.price.currency.symbol + totalAmount }}</span>
          </p>
          <p>
            <span class="font-semibold">Cancellation Policy:&nbsp;</span>
            <span
              v-if="booking.cancellation_policy_log.title"
            >{{JSON.parse(booking.cancellation_policy_log.title).en }}</span>
          </p>
        </div>
        <!-- Retreat Organizer Details -->
        <div class="mt-4">
          <h5 class="font-semibold">Retreat Organizer Contact Details</h5>
          <p>
            <a :href="'mailto:' + booking.user.email" class="text-dark d-flex align-items-center">
              <span class="uil uil-envelope me-1" style="font-size: 1.7rem;"></span>
              <span>{{booking.user.email}}</span>
            </a>
          </p>
          <p>
            <a :href="'tel:' + booking.user.phone" class="text-dark d-flex align-items-center">
              <span class="uil uil-phone-volume me-1" style="font-size: 1.7rem;"></span>
              <span>{{booking.user.phone}}</span>
            </a>
          </p>
          <button class="btn btn-warning">Send a message</button>
        </div>

        <!-- Retreat Organizer Payment Details -->
        <div class="mt-4">
          <h5 class="font-semibold">Retreat Organizer Payment Details</h5>
          <p>
            <span class="font-semibold">First Deposit:&nbsp;</span>
            <span>{{booking.price.currency.symbol + deposit}}</span>
          </p>
          <p>
            <span class="font-semibold">Paid at:&nbsp;</span>
            <span
              v-if="booking.deposit_paid_at"
            >{{$DateTime.fromISO(booking.deposit_paid_at).toLocaleString($DateTime.DATETIME_MED )}}</span>
            <span v-else>Not paid yet.</span>
          </p>
        </div>

        <!-- Venue Host Payment Details -->
        <div class="mt-4">
          <h5 class="font-semibold">Venue Host Payment Details</h5>
          <p>
            <span class="font-semibold">First Deposit:&nbsp;</span>
            <span>{{booking.price.currency.symbol + deposit}}</span>
          </p>
          <p>
            <span class="font-semibold">Tripavie Service Fee:&nbsp;</span>
            <span>{{booking.price.currency.symbol + adminFees(deposit)}}</span>
          </p>
          <p>
            <span class="font-semibold">Transaction Fee:&nbsp;</span>
            <span>{{booking.price.currency.symbol + transactionFees(deposit)}}</span>
          </p>
          <p>
            <span class="font-semibold">Net Amount:&nbsp;</span>
            <span>{{booking.price.currency.symbol + (deposit - adminFees(deposit) - transactionFees(deposit))}}</span>
          </p>
          <p>
            <span class="font-semibold">Paid at:&nbsp;</span>
            <span>Not paid yet.</span>
          </p>
        </div>

        <!-- Actions -->
        <div class="mt-4">
          <button class="btn btn-outline-success">Send or Request Money</button>
          <br />
          <button class="btn btn-danger mt-3">Cancel Booking</button>
        </div>
      </div>
      <!--end row-->
    </div>
    <!--end container-->
  </section>
  <!--end section-->
</template>

<script>
export default {
  data() {
    return {
      booking: {
        venue_log: {},
        user: {},
        price: {
          currency: {},
          fees: {},
        },
        cancellation_policy_log: {},
      },
    };
  },
  methods: {
    adminFees(amount) {
      return Math.round(
        amount * (this.booking.price.fees.admin_fees_percentage / 100),
        2
      );
    },
    transactionFees(amount) {
      return Math.round(
        amount * (this.booking.price.fees.transaction_fees_percentage / 100),
        2
      );
    },
  },
  computed: {
    dateTimeKey() {
      return this.$store.getters.dateTimeKey;
    },
    totalAmount() {
      return (
        this.booking.price.total_price +
        this.adminFees(this.booking.price.price_to_secure_booking) +
        this.transactionFees(this.booking.price.price_to_secure_booking)
      );
    },
    deposit() {
      return (
        this.booking.price.price_to_secure_booking +
        this.adminFees(this.booking.price.price_to_secure_booking) +
        this.transactionFees(this.booking.price.price_to_secure_booking)
      );
    },
  },
  mounted() {
    if (this.$route.params.venueBooking) {
      axios
        .get(`hosting/booking/${this.$route.params.venueBooking}`)
        .then((response) => {
          this.booking = response.data;
        })
        .catch((error) => {
          this.$router.push({ name: "home" });
        });
    } else {
      this.$router.push({ name: "home" });
    }
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
