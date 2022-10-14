<template>
  <div class="row align-items-center">
    <div class="col-12">
      <div class="card shadow rounded border-0">
        <div class="card-body py-5">
          <h4 class="card-title">How to get there?</h4>
          <div class="custom-form mt-3">
            <form method="post" name="myForm" @submit.prevent="updateArrival()">
              <div id="simple-msg"></div>
              <div class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label" for="name">
                      Airport Info
                      <span class="text-danger">*</span>
                    </label>
                    <small
                      class="text-muted d-inline-block"
                    >Provide details on how to reach your property. Select the airports that are near your property and whether you provide pick-up service. Also specify if there are any additional costs.</small>
                    <div class="position-relative">
                      <div class>
                        <div v-for="airport in airports" :key="`${airport.id}`">
                          <div class="my-1">
                            <input
                              @change="selectAirport(airport)"
                              type="checkbox"
                              :id="`airport-${airport.id}`"
                              class="form-check-input me-2"
                              :checked="airportSelected(airport)"
                            />
                            <label
                              :for="`airport-${airport.id}`"
                              class="form-check-label airport-name"
                            >
                              {{ airport.name }}
                              <span
                                class="text-gray-400"
                              >({{ airport.distance }} KM)</span>
                            </label>
                          </div>
                          <div class="mt-1 mb-3 ms-4" v-if="airportSelected(airport)">
                            <div class>
                              <div class>
                                <input
                                  v-model="
                        venue.airports[getAirportIndex(airport)]['pivot'].type
                      "
                                  type="radio"
                                  :name="`type-${airport.id}`"
                                  value="0"
                                  :id="`not_provided-${airport.id}`"
                                  class="form-check-input me-2"
                                />
                                <label
                                  :for="`not_provided-${airport.id}`"
                                  class="form-check-label arrival-type-label"
                                >Not Provided</label>
                              </div>
                              <div class>
                                <input
                                  v-model="
                        venue.airports[getAirportIndex(airport)]['pivot'].type
                      "
                                  type="radio"
                                  value="1"
                                  :name="`type-${airport.id}`"
                                  :id="`free-${airport.id}`"
                                  class="form-check-input me-2"
                                />
                                <label
                                  :for="`free-${airport.id}`"
                                  class="form-check-label arrival-type-label"
                                >Free</label>
                              </div>
                              <div class>
                                <input
                                  type="radio"
                                  v-model="
                        venue.airports[getAirportIndex(airport)]['pivot']['type']
                      "
                                  :name="`type-${airport.id}`"
                                  :id="`extra-${airport.id}`"
                                  value="2"
                                  class="form-check-input me-2"
                                />
                                <label
                                  :for="`extra-${airport.id}`"
                                  class="form-check-label arrival-type-label"
                                >Extra Charge</label>
                              </div>
                            </div>
                            <div
                              v-if="
                    venue.airports[getAirportIndex(airport)]['pivot']['type'] ==
                    2
                  "
                              class="col-xs-12 col-md-4"
                            >
                              <input
                                v-model.number="
                      venue.airports[getAirportIndex(airport)]['pivot']['charge']
                    "
                                type="text"
                                class="form-control"
                                placeholder="$"
                              />
                            </div>
                          </div>
                        </div>
                        <small
                          v-if="v$.venue.airports.$error"
                          class="text-danger"
                        >{{ v$.venue.airports.$errors[0].$message }}</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--end row-->
              <div class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label" for="name">
                      Additional Info
                      <span class="text-danger"></span>
                    </label>
                    <small
                      class="text-muted d-block"
                    >Provide details on how to reach your property (by car, train, bus, etc.)</small>
                    <textarea
                      id="additional_info"
                      class="form-control mt-2"
                      :maxlength="3000"
                      cols="30"
                      rows="8"
                      v-model="venue.arrival_info"
                    ></textarea>
                    <small class="text-muted d-block">{{additionalInfoCount}} / 3000 Characters</small>
                    <small
                      v-if="v$.venue.arrival_info.$error"
                      class="text-danger"
                    >{{ v$.venue.arrival_info.$errors[0].$message }}</small>
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
                    @click="updateArrival(true)"
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
import { required, maxLength, helpers } from "@vuelidate/validators";
export default {
  data() {
    return {
      v$: useValidate(),
      // venue: {
      //   airports: [],
      // },
      airports: [],
      editMode: false,
    };
  },
  validations() {
    return {
      venue: {
        airports: {
          required: helpers.withMessage(
            "Please select at least one airport.",
            required
          ),
        },
        arrival_info: {
          required: helpers.withMessage(
            "Please write some information on how to arrive.",
            required
          ),
          maxLength: helpers.withMessage(
            "Addiontal information must be up to 3000 characters.",
            maxLength(3000)
          ),
        },
      },
    };
  },
  methods: {
    updateArrival(exit = false) {
      this.v$.$validate();
      if (!this.v$.$error) {
        this.$Progress.start();
        axios
          .put(`venue/${this.venue.id}/arrival`, this.venue)
          .then(({ data }) => {
            this.$store.commit("UPDATE_VENUE_UNDER_SAVE", data.venue);
            this.$Progress.finish();
            if (exit) {
              this.$router.push({
                name: "hosting.venues",
              });
            } else {
              this.$router.push({
                name: "saveVenue.description",
                params: { venue: data.venue.id },
              });
            }
          })
          .catch((error) => {
            console.error(error);
            this.$Progress.fail();
          });
      }
    },
    selectAirport(airport) {
      if (this.venue.airports.some((a) => a.id === airport.id)) {
        this.venue.airports.splice(
          this.venue.airports.findIndex((a) => a.id === airport.id),
          1
        );
      } else {
        this.venue.airports.push({
          id: airport.id,
          pivot: {
            distance: `${airport.distance} KM`,
            type: "",
            charge: "",
          },
        });
      }
    },
  },
  computed: {
    venue() {
      return this.$store.getters.venueUnderSave;
    },
    airportSelected() {
      return (airport) => this.venue.airports.some((a) => a.id === airport.id);
    },
    getAirportIndex() {
      return (airport) =>
        this.venue.airports.findIndex((a) => a.id === airport.id);
    },
    additionalInfoCount() {
      return this.venue.arrival_info ? this.venue.arrival_info.length : 0;
    },
  },
  beforeCreate() {
    if (!this.$route.params.venue) {
      this.$router.push({ name: "saveVenue.name_and_address" });
    }
  },
  mounted() {
    if (!this.$route.params.venue) {
      this.$router.push({ name: "saveVenue.name_and_address" });
    }
    axios
      .get(`venue/${this.$route.params.venue}/getAirports`)
      .then(({ data }) => {
        this.airports = data;
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>

<style>
.vue-map-container {
  height: 100%;
}
.airport-name {
  display: inline;
  font-weight: 600;
}
.arrival-type-label {
  font-weight: 500;
}
</style>
