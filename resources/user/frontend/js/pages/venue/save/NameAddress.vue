<template>
  <div class="row align-items-center">
    <div class="col-12">
      <div class="card shadow rounded border-0">
        <div class="card-body py-5">
          <h4 class="card-title">Property Name and Address</h4>
          <div class="custom-form mt-3">
            <form method="post" name="myForm" @submit.prevent="handleSubmit()">
              <p id="error-msg" class="mb-0"></p>
              <div id="simple-msg"></div>
              <div class="row mb-2">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label" for="name">
                      Property Name
                      <span class="text-danger">*</span>
                    </label>
                    <div class="position-relative">
                      <input
                        id="name"
                        type="text"
                        class="form-control ps-3"
                        placeholder="Type something ..."
                        v-model="venue.name"
                      />
                      <small
                        v-if="v$.venue.name.$error"
                        class="text-danger"
                      >{{ v$.venue.name.$errors[0].$message }}</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label" for="property_type_id">
                      Property Type
                      <span class="text-danger">*</span>
                    </label>
                    <div class="position-relative">
                      <select
                        class="form-select form-control"
                        id="property_type_id"
                        aria-label="Currency Select"
                        v-model="venue.property_type_id"
                      >
                        <option value="null" disabled>Select Property Type</option>
                        <option
                          v-for="(propertyType, index) in propertyTypes"
                          :key="index"
                          :value="propertyType.id"
                        >{{propertyType.title['en']}}</option>
                      </select>
                      <small
                        v-if="v$.venue.property_type_id.$error"
                        class="text-danger"
                      >{{ v$.venue.property_type_id.$errors[0].$message }}</small>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label" for="is_shared">
                      Entire or Shared?
                      <span class="text-danger">*</span>
                    </label>
                    <div class="position-relative">
                      <div class="row">
                        <div class="col-md-6 custom-control custom-radio custom-control-inline">
                          <div class="form-check mb-0">
                            <input
                              class="form-check-input"
                              type="radio"
                              id="entire"
                              value="1"
                              v-model="venue.is_shared"
                            />
                            <label class="form-check-label" for="entire">Entire Venue</label>
                            <small
                              class="text-muted d-inline-block"
                            >The entire venue will be at the exclusive use of the retreat leader and attendees.</small>
                          </div>
                        </div>
                        <div class="col-md-6 custom-control custom-radio custom-control-inline">
                          <div class="form-check mb-0">
                            <input
                              class="form-check-input"
                              type="radio"
                              id="shared"
                              value="0"
                              v-model="venue.is_shared"
                            />
                            <label class="form-check-label" for="shared">Shared Venue</label>
                            <small
                              class="text-muted d-inline-block"
                            >The venue amenities and facilities such as dining area and kitchen may be shared with other retreat groups or venue guests.</small>
                          </div>
                        </div>
                      </div>
                      <small
                        v-if="v$.venue.is_shared.$error"
                        class="text-danger"
                      >{{ v$.venue.is_shared.$errors[0].$message }}</small>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <label class="form-label">
                  Property Address
                  <span class="text-danger">*</span>
                </label>
                <div class="col-md-6">
                  <div class="mb-3">
                    <v-select
                      autocomplete="off"
                      v-model="venue.country"
                      @input="$emit('input', $event)"
                      :options="countries"
                      @option:selected="countrySelected()"
                      label="country"
                      placeholder="Country"
                    ></v-select>
                    <small
                      v-if="v$.venue.country.$error"
                      class="text-danger"
                    >{{ v$.venue.country.$errors[0].$message }}</small>
                  </div>
                  <div class="mb-3">
                    <div class="position-relative">
                      <GMapAutocomplete
                        id="street"
                        type="text"
                        class="form-control ps-3"
                        placeholder="Street address line 1"
                        v-model="venue.street"
                        :value="venue.street"
                        :disabled="!venue.country"
                        :componentRestrictions="{ country: selectedCountryCode ? selectedCountryCode : 'af' }"
                        :options="{strictBounds: true}"
                        @place_changed="placeSelected($event)"
                      ></GMapAutocomplete>
                      <small
                        v-if="v$.venue.street.$error"
                        class="text-danger"
                      >{{ v$.venue.street.$errors[0].$message }}</small>
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="form-icon position-relative">
                      <v-select
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Select Country First"
                        v-model="venue.state"
                        :disabled="!venue.country"
                        @option:selected="stateSelected()"
                        :options="states"
                        label="state"
                        :placeholder="statePlaceholder"
                      ></v-select>
                      <small
                        v-if="v$.venue.state.$error"
                        class="text-danger"
                      >{{ v$.venue.state.$errors[0].$message }}</small>
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="form-icon position-relative">
                      <v-select
                        v-model="venue.city"
                        :disabled="!venue.state"
                        :options="cities"
                        taggable
                        label="city"
                        :placeholder="cityPlaceholder"
                      ></v-select>
                      <small
                        v-if="v$.venue.city.$error"
                        class="text-danger"
                      >{{ v$.venue.city.$errors[0].$message }}</small>
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="position-relative">
                      <input
                        id="street2"
                        type="text"
                        class="form-control ps-3"
                        placeholder="Street address line 2"
                        v-model="venue.street2"
                      />
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="form-icon position-relative">
                      <input
                        v-model="venue.zip"
                        id="first"
                        type="text"
                        class="form-control ps-3"
                        placeholder="Zip Code"
                      />
                      <small
                        v-if="v$.venue.zip.$error"
                        class="text-danger"
                      >{{ v$.venue.zip.$errors[0].$message }}</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div :class="blurMap ? 'h-100 blur-map' :'h-100'">
                    <GMapMap
                      :center="marker.position"
                      :zoom="16"
                      map-type-id="roadmap"
                      :options="mapOptions"
                      style="backdrop-filter: blur(5px);"
                      ref="mapRef"
                    >
                      <GMapMarker
                        :position="marker.position"
                        :clickable="true"
                        :draggable="true"
                        @dragend="markerDragged($event)"
                      />
                    </GMapMap>
                  </div>
                  <br />
                  <div class="row mt-3">
                    <div class="col-12 col-md-5 text-center">
                      <div v-if="showLoader" class="lds-ripple">
                        <div></div>
                        <div></div>
                      </div>
                      <span
                        v-else
                        class="btn btn-outline-secondary"
                        @click="getUserLocation()"
                      >Get my location</span>
                    </div>
                    <div class="col-12 col-md-7 d-flex align-items-center">
                      <label for="updateLocation">Update location as the marker moves</label>
                      <input
                        type="checkbox"
                        class="form-check-input ms-2"
                        name="updateLocation"
                        id="updateLocation"
                        v-model="updateLocation"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label" for="currency_id">
                      Currency for this Venue
                      <span class="text-danger">*</span>
                    </label>
                    <div class="position-relative">
                      <b>{{currencyPlaceholder}}</b> will be used as the currency.
                      <small
                        v-if="v$.venue.currency_id.$error"
                        class="text-danger"
                      >{{ v$.venue.currency_id.$errors[0].$message }}</small>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row border-top mt-4 pt-4">
                <div class="col-12">
                  <router-link
                    :to="{name: 'hosting.venues'}"
                    type="button"
                    class="btn btn-outline-secondary me-3"
                  >Exit</router-link>
                  <button
                    type="button"
                    class="btn btn-outline-warning"
                    @click="handleSubmit(true)"
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
import vSelect from "vue-select";

import useValidate from "@vuelidate/core";
import { required, maxLength, helpers } from "@vuelidate/validators";
export default {
  components: {
    vSelect,
  },
  data() {
    return {
      v$: useValidate(),
      venue: {
        currency_id: null,
        property_type_id: null,
        is_shared: null,
      },
      map: null,
      countries: [],
      selectedCountryCode: null,
      states: [],
      cities: [],
      mapOptions: {
        zoomControl: false,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: true,
        fullscreenControl: false,
      },
      marker: {
        position: {
          lat: 51.093048,
          lng: 6.84212,
        },
      },
      blurMap: true,
      updateLocation: true,
      editMode: false,
      showLoader: false,
    };
  },
  validations() {
    return {
      venue: {
        name: {
          required: helpers.withMessage("Please enter a name.", required),
        },
        currency_id: {
          required: helpers.withMessage("Please select a currency.", required),
        },
        property_type_id: {
          required: helpers.withMessage(
            "Please select a property type.",
            required
          ),
        },
        is_shared: {
          required: helpers.withMessage(
            "Please select either shared or entire venue..",
            required
          ),
        },
        zip: {
          required: helpers.withMessage("Please enter a zip code..", required),
          maxLengthValue: helpers.withMessage(
            "Please enter a valid zip code.",
            maxLength(10)
          ),
        },
        country: {
          required: helpers.withMessage("Please select a country.", required),
        },
        state: {
          required: helpers.withMessage("Please select a state.", required),
        },
        city: {
          required: helpers.withMessage("Please enter a city name.", required),
        },
        street: {
          required: helpers.withMessage(
            "Please enter a street address.",
            required
          ),
        },
      },
    };
  },
  methods: {
    handleSubmit(exit = false) {
      this.venue.user_id = this.user.id;
      this.v$.$validate();
      if (!this.v$.$error) {
        this.$Progress.start();

        this.venue.lat = this.marker.position.lat;
        this.venue.lng = this.marker.position.lng;

        // sending only required data
        let fd = new FormData();
        fd.append("currency_id", this.venue.currency_id);
        fd.append("property_type_id", this.venue.property_type_id);
        fd.append("user_id", this.venue.user_id);
        fd.append("name", this.venue.name);
        fd.append("is_shared", this.venue.is_shared);
        fd.append("country", this.venue.country);
        fd.append("street", this.venue.street);
        fd.append("street2", this.venue.street2);
        fd.append("state", this.venue.state);
        fd.append("city", this.venue.city);
        fd.append("zip", this.venue.zip);
        fd.append("lat", this.venue.lat);
        fd.append("lng", this.venue.lng);

        if (!this.editMode) {
          axios
            .post("venue/store/nameAddress", fd, {
              headers: { "Content-Type": "application/json" },
            })
            .then(({ data }) => {
              this.$store.commit("UPDATE_VENUE_UNDER_SAVE", data.venue);
              this.$store.commit("UPDATE_USER_TYPE", data.user_type);
              this.$Progress.finish();
              if (exit) {
                this.$router.push({
                  name: "hosting.venues",
                });
              } else {
                this.$router.push({
                  name: "saveVenue.arrival",
                  params: { venue: data.venue.id },
                });
              }
            })
            .catch((error) => {
              this.$Progress.fail();
            });
        } else {
          fd.append("_method", "PUT");
          axios
            .post(`venue/${this.venue.id}/updateNameAddress`, fd, {
              headers: { "Content-Type": "application/json" },
            })
            .then(({ data }) => {
              this.$Progress.finish();
              this.$store.commit("UPDATE_VENUE_UNDER_SAVE", data.venue);
              if (exit) {
                this.$router.push({
                  name: "hosting.venues",
                });
              } else {
                this.$router.push({
                  name: "saveVenue.arrival",
                  params: { venue: data.venue.id },
                });
              }
            })
            .catch((error) => {
              this.$Progress.fail();
            });
        }
      }
    },

    countrySelected() {
      this.venue.state = null;
      this.venue.city = null;
      this.venue.street = null;
      axios
        .post(`country/${this.venue.country}/getShortname`)
        .then((response) => {
          this.selectedCountryCode = response.data;
        });
      this.getStates();
      this.getCurrency();
    },

    placeSelected(place) {
      this.marker.position.lat = place.geometry.location.lat();
      this.marker.position.lng = place.geometry.location.lng();
      this.setPlace(place);
    },

    setPlace(place) {
      const addressComp = place.address_components.reduce(
        (seed, { long_name, types }) => (
          types.forEach((t) => (seed[t] = long_name)), seed
        ),
        {}
      );
      if (addressComp.country) {
        this.venue.country = addressComp.country;
        this.countrySelected();
      }
      if (addressComp.administrative_area_level_1) {
        this.venue.state = addressComp.administrative_area_level_1;
        this.stateSelected();
      }
      if (addressComp.locality || addressComp.administrative_area_level_2) {
        this.venue.city = addressComp.locality
          ? addressComp.locality
          : "" || addressComp.administrative_area_level_2
          ? addressComp.administrative_area_level_2
          : "";
      }
      if (addressComp.postal_code) {
        this.venue.zip = addressComp.postal_code;
      }
      var street = "";
      if (addressComp.street_number && addressComp.route) {
        street = addressComp.street_number + " " + addressComp.route;
      } else if (addressComp.route) {
        street = addressComp.route;
      }
      this.venue.street = street;
      document.getElementById("street").value = street;

      this.blurMap = false;
    },

    getCurrency() {
      axios
        .post("getCurrency", { country: this.venue.country })
        .then(({ data }) => {
          let otherCurrencySelected = false;
          this.currencies.forEach((currency) => {
            if (currency.code == data) {
              this.venue.currency_id = currency.id;
              otherCurrencySelected = true;
            }
          });
          if (!otherCurrencySelected) {
            this.venue.currency_id = this.usdCurrency.id;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    getStates() {
      axios
        .post("getStates", { country: this.venue.country })
        .then(({ data }) => {
          this.states = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    stateSelected() {
      this.venue.city = null;
      this.getCities();
    },

    getCities() {
      axios
        .post("getCities", {
          country: this.venue.country,
          state: this.venue.state,
        })
        .then(({ data }) => {
          this.cities = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    getUserLocation() {
      this.showLoader = true;
      this.$getLocation()
        .then((coordinates) => {
          this.marker.position.lat = coordinates.lat;
          this.marker.position.lng = coordinates.lng;
          this.getReadableLocation(coordinates.lat, coordinates.lng);
          this.showLoader = false;
        })
        .catch((error) => {
          console.log(error);
          this.showLoader = false;
        });
    },

    getReadableLocation(lat, lng) {
      axios
        .get(
          "https://maps.googleapis.com/maps/api/geocode/json?latlng=" +
            lat +
            "," +
            lng +
            "&key=AIzaSyA1rWHl-0ttgQhYCtkwaFfzmWIBooL1gJM"
        )
        .then((response) => {
          this.setPlace(response.data.results[0]);
        });
    },

    markerDragged(location) {
      let lat = location.latLng.lat();
      let lng = location.latLng.lng();
      if (this.updateLocation) {
        this.getReadableLocation(lat, lng);
      }
      this.marker.position.lat = lat;
      this.marker.position.lng = lng;
    },
  },
  computed: {
    user() {
      return this.$store.getters.user.user;
    },
    currencies() {
      return this.$store.getters.currencies;
    },
    usdCurrency() {
      const index = this.currencies.findIndex(
        (currency) => currency.code == "USD"
      );
      return this.currencies[index];
    },
    currencyPlaceholder() {
      if (!this.venue.currency_id && this.usdCurrency) {
        return this.usdCurrency.code;
      } else if (this.venue.currency_id) {
        const index = this.currencies.findIndex(
          (currency) => currency.id == this.venue.currency_id
        );
        if (this.currencies[index]) {
          return this.currencies[index].code;
        }
      }
    },
    propertyTypes() {
      return this.$store.getters.propertyTypes;
    },
    statePlaceholder() {
      return this.venue.country
        ? "State / Province"
        : "State / Province (select country first)";
    },
    cityPlaceholder() {
      return this.venue.state ? "City" : "City (select state first)";
    },
    venueCountry() {
      return this.venue.country;
    },
  },
  watch: {
    venueCountry: function (val) {
      if (this.venue.country == null) {
        this.venue.state =
          this.venue.city =
          this.venue.street =
          this.venue.street2 =
          this.venue.zip =
            "";
        document.getElementById("street").value = null;
      }
    },
  },
  mounted() {
    if (this.$route.params.venue) {
      this.editMode = true;
      axios
        .get(`venue/${this.$route.params.venue}`)
        .then(({ data }) => {
          this.venue = data;
          this.marker.position.lat = Number(data.lat);
          this.marker.position.lng = Number(data.lng);
          this.blurMap = false;
          axios
            .post(`country/${this.venue.country}/getShortname`)
            .then((response) => {
              this.selectedCountryCode = response.data;
            });
        })
        .catch((error) => {
          console.log(error);
        });
    } else {
      this.$store.commit("CHANGE_VENUE_UNDER_SAVE", {});
      this.venue = {};
    }
    this.$refs.mapRef.$mapPromise.then((map) => (this.map = map));
    axios
      .get("getCountries")
      .then(({ data }) => {
        this.countries = data;
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
.blur-map {
  filter: blur(10px);
  pointer-events: none;
}
</style>
