<template>
  <section class="section">
    <div class="container">
      <h3 class="text-gray-800 font-semibold">Confirm and Pay for your booking</h3>
      <div id="booking-information" class="mt-4 p-3 p-md-5">
        <!-- Venue information -->
        <div id="venue-information" class="mb-2 mb-md-5" v-if="booking.venue.images">
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

              <h6
                class="font-semibold"
              >{{$DateTime.fromISO(booking.start_date).toLocaleString($DateTime.DATE_MED)}} to {{$DateTime.fromISO(booking.end_date).toLocaleString($DateTime.DATE_MED)}}</h6>
              <h6 class="font-semibold">{{booking.guests}} Guests</h6>
              <div class="mt-3 pt-3 border-top"></div>
            </div>
          </div>
        </div>

        <!-- Pricing and Payment -->
        <div class="row">
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
              <div class="col-12" v-if="booking.price.meals_price.discount_percentage > 0">
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
                <span class="float-end">{{currencySymbol}}{{booking.price.fees.admin_fees}}</span>
              </div>
              <div class="col-12">
                <span>Transaction fees</span>
                <span class="float-end">{{currencySymbol}}{{booking.price.fees.transaction_fees}}</span>
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
                <span class="float-end me-2">({{booking.cancellation_policy_log.deposit}}%)</span>
              </div>
            </div>
          </div>
          <div class="mt-5 pt-5 border-top d-block d-md-none"></div>

          <div class="col-12 col-md-6">
            <h4 class="font-semibold">Pay Here</h4>
            <div>
              <label for="card-element" class="mt-2 mb-1">Billing Address</label>
              <div class="row" v-if="user.userDetails">
                <div class="col-6">
                  <input
                    type="text"
                    class="form-control me-3 py-2"
                    v-model="user.fname"
                    placeholder="First Name"
                  />
                </div>
                <div class="col-6">
                  <input
                    type="text"
                    class="form-control py-2"
                    v-model="user.lname"
                    placeholder="Last Name"
                  />
                </div>
                <div class="col-12 mt-3">
                  <input
                    type="email"
                    class="form-control py-2"
                    v-model="user.email"
                    placeholder="Email"
                  />
                </div>
                <div class="col-12 col-md-6 mt-3">
                  <input
                    type="text"
                    class="form-control w-100 py-2"
                    v-model="user.userDetails.street"
                    placeholder="Street Address"
                  />
                </div>
                <div class="col-12 col-md-6 mt-3">
                  <input
                    type="text"
                    class="form-control w-100 py-2"
                    v-model="user.userDetails.city"
                    placeholder="City"
                  />
                </div>
                <div class="col-6 mt-3">
                  <input
                    type="text"
                    class="form-control w-100 py-2"
                    v-model="user.userDetails.state"
                    placeholder="State (Province)"
                  />
                </div>
                <div class="col-6 mt-3">
                  <input
                    type="text"
                    class="form-control w-100 py-2"
                    v-model="user.userDetails.country"
                    placeholder="Country"
                  />
                </div>
              </div>
              <label for="card-element" class="mt-3">Credit Card Info</label>
              <div id="card-element"></div>
              <button
                class="btn btn-warning float-end px-5"
                @click="processPayment"
                :disabled="paymentProcessing"
                v-text="paymentProcessing ? 'Processing' : 'Pay Now'"
              ></button>
            </div>
          </div>
        </div>
        <br />
      </div>
    </div>
  </section>
</template>

<script>
import { loadStripe } from "@stripe/stripe-js";

export default {
  data() {
    return {
      stripe: {},
      cardElement: {},
      booking: {
        venue: {},
      },
      user: {
        userDetails: {},
      },
      paymentProcessing: false,
    };
  },
  computed: {
    activeLanguage() {
      return this.$store.getters.activeLocale;
    },
    computedUser() {
      return this.$store.getters.user.user;
    },
    billingDetails() {
      return {
        name: this.user.fname + " " + this.user.lname,
        email: this.user.email,
        address: {
          line1: this.user.userDetails.street,
          city: this.user.userDetails.city,
          state: this.user.userDetails.state,
          country: this.user.userDetails.TwoCharacterCountry,
          postal_code: this.user.userDetails.zip,
        },
      };
    },
    numberOfDays() {
      var a = this.$DateTime.fromISO(this.booking.start_date);
      var b = this.$DateTime.fromISO(this.booking.end_date);
      return b.diff(a, "days").toObject().days;
    },
    pricePerNight() {
      return this.booking.venue.guests_included > 0 &&
        this.booking.guests > this.booking.venue.guests_included
        ? this.booking.venue.additional_guest_price
        : this.booking.venue.price_per_night;
    },
    currencySymbol() {
      return this.booking.price.currency.symbol;
    },
  },
  methods: {
    test() {
      alert("came");
    },
    bannerImage(images) {
      return images[images.findIndex((img) => img.type == 1)]
        ? images[images.findIndex((img) => img.type == 1)].image
        : "";
    },
    async processPayment() {
      this.paymentProcessing = true;
      const { paymentMethod, error } = await this.stripe.createPaymentMethod(
        "card",
        this.cardElement,
        {
          billing_details: this.billingDetails,
        }
      );

      if (error) {
        this.paymentProcessing = false;
        console.error(error);
      } else {
        this.user.payment_method_id = paymentMethod.id;
        this.user.amount = this.booking.price.price_to_secure_booking * 100; // converting into cents
        this.user.currency = this.booking.price.currency.code; // passing the currency from the booking request
        axios
          .post("user/chargeUser", this.user)
          .then((response) => {
            this.paymentProcessing = false;
            this.changeBookingStatusToDepositPaid();
            this.$toast.success("Transaction completed.", {
              position: "top-right",
            });
          })
          .catch((error) => {
            if (error.data.next == "3ds") {
              this.stripe
                .confirmCardPayment(error.data.client_secret, {
                  payment_method: error.data.payment_method,
                })
                .then((result) => {
                  if (result.error) {
                    this.$toast.error(
                      "We could not verify your card details.",
                      {
                        position: "top-right",
                      }
                    );
                  } else {
                    this.changeBookingStatusToDepositPaid();
                    this.$toast.success("Transaction completed.", {
                      position: "top-right",
                    });
                  }
                  this.paymentProcessing = false;
                });
            } else {
              this.paymentProcessing = false;
              this.$toast.error(
                "The transaction was declined, please try again.",
                {
                  position: "top-right",
                }
              );
            }
          });
      }
    },
    changeBookingStatusToDepositPaid() {
      axios
        .get(
          `venue/booking/${this.booking.id}/changeBookingStatusToDepositPaid`
        )
        .then((response) => {
          //updated booking in response
          this.booking.status = 2;
          console.log(this.booking);
          this.$store.commit("UPDATE_VENUE_BOOKING", this.booking);
          this.$router.push({
            name: "venue.booking.confirmationSuccess",
            params: { venueBooking: response.data.id },
          });
        })
        .catch((error) => {
          this.$toast.warning(
            "We recieved the payment, but the booking was not confirmed. Please contact us.",
            {
              position: "top-right",
            }
          );
        });
    },
  },
  watch: {
    computedUser: {
      handler: function (user) {
        if (user.id) {
          this.user = user;
          axios.get(`userDetail/${user.id}`).then((response) => {
            this.user.userDetails = response.data;
            axios
              .get(
                `country/${this.user.userDetails.country}/getTwoCharacterName`
              )
              .then((response) => {
                this.user.userDetails.TwoCharacterCountry = response.data;
              });
          });
        }
      },
      immediate: true,
    },
  },
  async mounted() {
    if (this.$route.params.venueBooking) {
      axios
        .get(`venue/booking/${this.$route.params.venueBooking}`)
        .then(({ data }) => {
          this.booking = data;
        })
        .catch((error) => {
          this.$router.push({ name: "home" });
        });
    }
    this.stripe = await loadStripe(process.env.MIX_STRIPE_KEY);
    const elements = this.stripe.elements();
    this.cardElement = elements.create("card", {
      classes: {
        base: "form-control p-3 mb-3 mt-1",
      },
    });
    this.cardElement.mount("#card-element");
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
