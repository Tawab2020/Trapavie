<template>
  <div class="row align-items-center">
    <div class="col-12">
      <div class="card shadow rounded border-0">
        <div class="card-body py-5">
          <h4 class="card-title">Check in and check out</h4>
          <div class="custom-form mt-3">
            <form method="post" name="myForm" @submit.prevent="updateCheckIn()">
              <div class="row mb-4">
                <label class="form-label d-block mb-1" for="name">Minimum number of nights</label>
                <small
                  class="text-muted"
                >What is the minimum number of nights you require for booking? If there is no minimum, select 1?</small>
                <div class="col-12 col-md-2 mt-2">
                  <input
                    type="number"
                    min="1"
                    max="365"
                    v-model="venue.min_nights"
                    class="form-control"
                  />
                  <small
                    v-if="v$.venue.min_nights.$error"
                    class="text-danger"
                  >{{ v$.venue.min_nights.$errors[0].$message }}</small>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-12 col-md-2 mt-2 mt-md-0">
                  <label class="form-label" for="name">Check-in time</label>
                  <input type="time" v-model="venue.check_in" class="form-control" />
                  <small
                    v-if="v$.venue.check_in.$error"
                    class="text-danger"
                  >{{ v$.venue.check_in.$errors[0].$message }}</small>
                </div>
                <div class="col-12 col-md-2 mt-2 mt-md-0">
                  <label class="form-label" for="name">Check-out time</label>
                  <input type="time" v-model="venue.check_out" class="form-control" />
                  <small
                    v-if="v$.venue.check_out.$error"
                    class="text-danger"
                  >{{ v$.venue.check_out.$errors[0].$message }}</small>
                </div>
              </div>
              <div class="row mb-3">
                <label class="form-label mb-1" for="name">Extra time between bookings (days)</label>
                <small
                  class="text-muted"
                >Do you require extra time between bookings? If not, put in 0.</small>
                <div class="col-12 col-md-2 mt-2">
                  <input
                    type="number"
                    min="0"
                    max="10"
                    v-model="venue.extra_time_between_bookings"
                    class="form-control"
                  />
                  <small
                    v-if="v$.venue.extra_time_between_bookings.$error"
                    class="text-danger"
                  >{{ v$.venue.extra_time_between_bookings.$errors[0].$message }}</small>
                </div>
              </div>
              <div class="row border-top mt-4 pt-4">
                <div class="col-12">
                  <button
                    @click="$router.back()"
                    type="button"
                    class="btn btn-outline-secondary me-3"
                  >Back</button>
                  <button
                    type="button"
                    class="btn btn-outline-warning"
                    @click="updateCheckIn(true)"
                  >Save and Exit</button>
                  <button type="submit" class="btn btn-warning float-end">Save</button>
                </div>
              </div>
            </form>
          </div>
          <!--end custom-form-->
        </div>
      </div>
    </div>
    <!--end col-->
  </div>
  <!--end row-->
</template>

<script>
import useValidate from "@vuelidate/core";

import { required, minValue, maxValue, helpers } from "@vuelidate/validators";
export default {
  data() {
    return {
      v$: useValidate(),
    };
  },
  validations() {
    return {
      venue: {
        min_nights: {
          required: helpers.withMessage(
            "Provide the minimum nights value.",
            required
          ),
          minValue: helpers.withMessage(
            `Provide a value higher than 0`,
            minValue(1)
          ),
          maxValue: helpers.withMessage(
            `Provide a value lower than 365`,
            maxValue(365)
          ),
        },
        check_in: {
          required: helpers.withMessage("Provide the check-in time.", required),
        },
        check_out: {
          required: helpers.withMessage(
            "Provide the check-out time.",
            required
          ),
        },
        extra_time_between_bookings: {
          required: helpers.withMessage("Provide the value.", required),
          minValue: helpers.withMessage(
            `Provide a valid number of days.`,
            minValue(0)
          ),
          maxValue: helpers.withMessage(
            `Provide number of days less than 11`,
            maxValue(10)
          ),
        },
      },
    };
  },
  methods: {
    updateCheckIn(exit = false) {
      this.v$.$validate();
      if (!this.v$.$error) {
        this.$Progress.start();

        let fd = new FormData();
        fd.append("_method", "PUT");

        fd.append("min_nights", this.venue.min_nights);
        fd.append("check_in", this.venue.check_in);
        fd.append("check_out", this.venue.check_out);
        fd.append(
          "extra_time_between_bookings",
          this.venue.extra_time_between_bookings
        );
        axios
          .post(`venue/${this.venue.id}/updateCheckIn`, fd, {
            headers: { "Content-Type": "application/json" },
          })
          .then(({ data }) => {
            this.$store.commit("UPDATE_VENUE_UNDER_SAVE", data.venue);
            this.$Progress.finish();
            if (exit) {
              this.$router.push({
                name: "hosting.venues",
              });
            } else {
              this.$router.push({
                name: "saveVenue.cancellation_policy",
                params: { venue: this.venue.id },
              });
            }
          })
          .catch((error) => {
            console.error(error);
            this.$Progress.fail();
          });
      }
    },
  },
  computed: {
    venue() {
      return this.$store.getters.venueUnderSave;
    },
  },
  mounted() {
    if (!this.$route.params.venue) {
      this.$router.push({ name: "saveVenue.name_and_address" });
    }
  },
};
</script>

<style>
</style>
