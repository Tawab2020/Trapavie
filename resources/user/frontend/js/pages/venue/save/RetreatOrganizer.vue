<template>
  <div class="row align-items-center">
    <div class="col-12">
      <div class="card shadow rounded border-0">
        <div class="card-body py-5">
          <h4 class="card-title">Retreat Organizer</h4>
          <div class="custom-form mt-3">
            <form method="post" name="myForm" @submit.prevent="updateRetreatOrganizer()">
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-4">
                    <h6>Do you offer free accommodation for the retreat organizer?</h6>
                    <div class="form-check form-switch">
                      <input
                        class="form-check-input check-toggle check-input-success"
                        type="checkbox"
                        id="freeAccommodationCheck"
                        v-model="venue.free_accommodation_for_retreat_organizer"
                      />
                      <label
                        class="form-label no-select"
                        for="freeAccommodationCheck"
                      >{{venue.free_accommodation_for_retreat_organizer ? "Yes" : "No"}}</label>
                    </div>
                    <!-- <small
                      v-if="v$.discounts.$error"
                      class="text-danger"
                    >{{ v$.discounts.$errors[0].$message }}</small>-->

                    <div v-if="venue.free_accommodation_for_retreat_organizer">
                      <small class="text-muted">Do you require minimum booking (guests/nights)?</small>
                      <div class="row mb-3">
                        <div class="col-12 col-md-3 mt-2 mt-md-0">
                          <label class="form-label" for="name">Minimum guests</label>
                          <input
                            type="number"
                            ref="from"
                            v-model="venue.min_guests_for_free_accommodation"
                            class="form-control"
                          />
                          <small
                            v-if="v$.venue.min_guests_for_free_accommodation.$error"
                            class="text-danger"
                          >{{ v$.venue.min_guests_for_free_accommodation.$errors[0].$message }}</small>
                        </div>
                        <div class="col-12 col-md-3 mt-2 mt-md-0">
                          <label class="form-label" for="name">Minimum nights</label>
                          <input
                            type="number"
                            v-model="venue.min_nights_for_free_accommodation"
                            class="form-control"
                          />
                          <small
                            v-if="v$.venue.min_nights_for_free_accommodation.$error"
                            class="text-danger"
                          >{{ v$.venue.min_nights_for_free_accommodation.$errors[0].$message }}</small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <h6>Do you offer free meals for the retreat organizer?</h6>
                    <div class="form-check form-switch">
                      <input
                        class="form-check-input check-toggle check-input-success"
                        type="checkbox"
                        id="freeMealCheck"
                        v-model="venue.free_meal_for_retreat_organizer"
                      />
                      <label
                        class="form-label no-select"
                        for="freeMealCheck"
                      >{{venue.free_meal_for_retreat_organizer ? "Yes" : "No"}}</label>
                    </div>

                    <div v-if="venue.free_meal_for_retreat_organizer">
                      <small class="text-muted">Do you require minimum booking (guests/nights)?</small>
                      <div class="row mb-3">
                        <div class="col-12 col-md-3 mt-2 mt-md-0">
                          <label class="form-label" for="name">Minimum guests</label>
                          <input
                            type="number"
                            ref="from"
                            v-model="venue.min_guests_for_free_meal"
                            class="form-control"
                          />
                          <small
                            v-if="v$.venue.min_guests_for_free_meal.$error"
                            class="text-danger"
                          >{{ v$.venue.min_guests_for_free_meal.$errors[0].$message }}</small>
                        </div>
                        <div class="col-12 col-md-3 mt-2 mt-md-0">
                          <label class="form-label" for="name">Minimum nights</label>
                          <input
                            type="number"
                            v-model="venue.min_nights_for_free_meal"
                            class="form-control"
                          />
                          <small
                            v-if="v$.venue.min_nights_for_free_meal.$error"
                            class="text-danger"
                          >{{ v$.venue.min_nights_for_free_meal.$errors[0].$message }}</small>
                        </div>
                      </div>
                    </div>
                  </div>
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
                    @click="updateRetreatOrganizer(true)"
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

import { required, minValue, helpers } from "@vuelidate/validators";
export default {
  data() {
    return {
      v$: useValidate(),
    };
  },
  validations() {
    let v = {
      venue: {},
    };
    if (this.venue.free_accommodation_for_retreat_organizer) {
      v.venue.min_guests_for_free_accommodation = {
        required: helpers.withMessage(
          "Provide the minimum guests value.",
          required
        ),
        minValue: helpers.withMessage(
          `Provide a value higher than 0`,
          minValue(1)
        ),
      };

      v.venue.min_nights_for_free_accommodation = {
        required: helpers.withMessage(
          "Provide the minimum nights value.",
          required
        ),
        minValue: helpers.withMessage(
          `Provide a value higher than 0`,
          minValue(1)
        ),
      };
    }
    if (this.venue.free_meal_for_retreat_organizer) {
      v.venue.min_guests_for_free_meal = {
        required: helpers.withMessage(
          "Provide the minimum guests value.",
          required
        ),
        minValue: helpers.withMessage(
          `Provide a value higher than 0`,
          minValue(1)
        ),
      };
      v.venue.min_nights_for_free_meal = {
        required: helpers.withMessage(
          "Provide the minimum nights value.",
          required
        ),
        minValue: helpers.withMessage(
          `Provide a value higher than 0`,
          minValue(1)
        ),
      };
    }
    return v;
  },
  methods: {
    updateRetreatOrganizer(exit = false) {
      this.v$.$validate();
      if (!this.v$.$error) {
        this.$Progress.start();

        let fd = new FormData();
        fd.append("_method", "PUT");

        fd.append(
          "free_accommodation_for_retreat_organizer",
          this.venue.free_accommodation_for_retreat_organizer ? 1 : 0
        );
        fd.append(
          "min_guests_for_free_accommodation",
          this.venue.min_guests_for_free_accommodation
        );
        fd.append(
          "min_nights_for_free_accommodation",
          this.venue.min_nights_for_free_accommodation
        );
        fd.append(
          "free_meal_for_retreat_organizer",
          this.venue.free_meal_for_retreat_organizer ? 1 : 0
        );
        fd.append(
          "min_guests_for_free_meal",
          this.venue.min_guests_for_free_meal
        );
        fd.append(
          "min_nights_for_free_meal",
          this.venue.min_nights_for_free_meal
        );
        axios
          .post(`venue/${this.venue.id}/updateRetreatOrganizer`, fd, {
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
                name: "saveVenue.check_in_check_out",
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
  watch: {
    venue: {
      handler: function (venue) {
        if (venue.free_meal_for_retreat_organizer) {
          this.venue.free_meal_for_retreat_organizer = true;
        }
        if (venue.free_accommodation_for_retreat_organizer) {
          this.venue.free_accommodation_for_retreat_organizer = true;
        }
      },
      immediate: true,
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
