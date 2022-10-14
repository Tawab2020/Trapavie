<template>
  <div class="row align-items-center">
    <div class="col-12">
      <div class="card shadow rounded border-0">
        <div class="card-body py-5">
          <h4 class="card-title">Venue Pricing</h4>
          <div class="custom-form mt-3">
            <form method="post" name="myForm" @submit.prevent="updatePricing()">
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label" for="name">
                      Fixed Pricing
                      <span class="text-danger"></span>
                    </label>
                    <div class="form-check form-switch">
                      <input
                        class="form-check-input check-toggle check-input-success"
                        type="checkbox"
                        id="fixedPriceTypeCheck"
                        v-model="hasFixedPricing"
                        @change="togglePricingType(1)"
                      />
                      <label class="info-label" for="fixedPriceTypeCheck">{{fixedPriceCheckLabel}}</label>
                    </div>
                    <div class="row" v-if="hasFixedPricing">
                      <label
                        class="info-lable"
                        for="name"
                      >price per night, regardless of the number of guests.</label>
                      <div class="col-12 col-md-3">
                        <input type="number" v-model="venue.price_per_night" class="form-control" />
                      </div>
                      <small
                        v-if="v$.venue.price_per_night.$error"
                        class="text-danger"
                      >{{ v$.venue.price_per_night.$errors[0].$message }}</small>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label" for="name">
                      Flexible Pricing
                      <span class="text-danger"></span>
                    </label>
                    <div class="form-check form-switch mb-3">
                      <input
                        class="form-check-input check-toggle check-input-success"
                        type="checkbox"
                        id="flexiblePriceTypeCheck"
                        v-model="hasFlexiblePricing"
                        @change="togglePricingType()"
                      />
                      <label
                        class="info-label"
                        for="flexiblePriceTypeCheck"
                      >{{flexiblePriceCheckLabel}}</label>
                    </div>
                    <div v-if="hasFlexiblePricing">
                      <div class="row mb-2">
                        <div class="col-12 col-md-3">
                          <label class="info-lable" for="name">Price per night</label>
                          <input type="number" v-model="venue.price_per_night" class="form-control" />
                          <small
                            v-if="v$.venue.price_per_night.$error"
                            class="text-danger"
                          >{{ v$.venue.price_per_night.$errors[0].$message }}</small>
                        </div>
                        <div class="col-12 col-md-3">
                          <label class="info-lable" for="name">For up to</label>
                          <input type="number" v-model="venue.guests_included" class="form-control" />
                          <small
                            v-if="v$.venue.guests_included.$error"
                            class="text-danger"
                          >{{ v$.venue.guests_included.$errors[0].$message }}</small>
                        </div>
                        <div class="col-12 col-md-3">
                          <label
                            class="info-lable"
                            for="name"
                          >Price per night after {{venue.guests_included}} guests</label>
                          <input
                            type="number"
                            v-model="venue.additional_guest_price"
                            class="form-control"
                          />
                          <small
                            v-if="v$.venue.additional_guest_price.$error"
                            class="text-danger"
                          >{{ v$.venue.additional_guest_price.$errors[0].$message }}</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" v-if="hasFixedPricing || hasFlexiblePricing">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label" for="name">Length of stay discounts</label>
                    <div class="form-check form-switch">
                      <input
                        class="form-check-input check-toggle check-input-success"
                        type="checkbox"
                        id="discountCheck"
                        v-model="venue.has_pricing_discounts"
                      />
                      <label class="info-label" for="discountCheck">{{discountLabel}}</label>
                    </div>
                    <small
                      v-if="v$.discounts.$error"
                      class="text-danger"
                    >{{ v$.discounts.$errors[0].$message }}</small>

                    <div v-if="venue.has_pricing_discounts">
                      <small class="text-muted">Enter the number of nights and discount percentage</small>
                      <div class="row mb-3">
                        <div class="col-12 col-md-2 mt-2 mt-md-0">
                          <label class="info-lable" for="name">From</label>
                          <input
                            type="number"
                            ref="from"
                            v-on:keydown.enter.prevent="addDiscount"
                            v-model="discount.from"
                            class="form-control"
                          />
                          <small
                            v-if="v$.discount.from.$error"
                            class="text-danger"
                          >{{ v$.discount.from.$errors[0].$message }}</small>
                        </div>
                        <div class="col-12 col-md-2 mt-2 mt-md-0">
                          <label class="info-lable" for="name">To</label>
                          <input
                            type="number"
                            ref="to"
                            v-model="discount.to"
                            v-on:keydown.enter.prevent="addDiscount"
                            class="form-control"
                          />
                          <small
                            v-if="v$.discount.to.$error"
                            class="text-danger"
                          >{{ v$.discount.to.$errors[0].$message }}</small>
                        </div>
                        <div class="col-12 col-md-2 mt-2 mt-md-0">
                          <label class="info-lable" for="name">Discount %</label>
                          <input
                            type="number"
                            v-model="discount.percentage"
                            min="1"
                            max="100"
                            class="form-control"
                            v-on:keydown.enter.prevent="addDiscount"
                          />
                          <small
                            v-if="v$.discount.percentage.$error"
                            class="text-danger"
                          >{{ v$.discount.percentage.$errors[0].$message }}</small>
                        </div>
                        <div class="col-12 col-md-3">
                          <a
                            @click="addDiscount"
                            class="btn btn-outline-warning mt-3 mt-md-4 text-center"
                            role="button"
                            href="javascript:void(0)"
                          >Add</a>
                        </div>
                      </div>
                      <div>
                        <div v-for="discount in discounts" :key="discount.id" class="row mb-3">
                          <div class="col-12 col-md-8 col-xl-5">
                            <div class="custom-border d-flex">
                              {{discount.percentage}}% discount for stays from {{discount.from}} to {{discount.to}} nights
                              <i
                                @click="this.discountToBeDeletedId = discount.id"
                                data-bs-toggle="modal"
                                data-bs-target="#confirm-delete-modal"
                                class="uil uil-trash d-flex align-items-center me-2 me-md-3 lead text-danger ms-auto"
                                role="button"
                              ></i>
                            </div>
                          </div>
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
                    @click="updatePricing(true)"
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
  <!-- Confirm discount Deletion Modal Start -->
  <div class="modal fade" id="confirm-delete-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded shadow border-0">
        <div class="modal-body py-5">
          <div class="text-center">
            <div
              class="icon d-flex align-items-center justify-content-center bg-soft-danger rounded-circle mx-auto"
              style="height: 95px; width:95px;"
            >
              <h1 class="mb-0">
                <i class="uil uil-trash align-middle"></i>
              </h1>
            </div>
            <div class="mt-4">
              <h4>Delete Discount?</h4>
              <p class="text-muted">Are you absolutely sure you want to delete this discount?</p>
              <div class="mt-4">
                <a
                  href="javascript:void(0)"
                  @click="deleteDiscount()"
                  data-bs-dismiss="modal"
                  class="btn btn-outline-danger"
                >Yes, Delete it.</a>
                <a
                  href="javascript:void(0)"
                  data-bs-dismiss="modal"
                  class="btn ms-4 btn-secondary"
                >Cancel</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Confirm discount Deletion Modal End -->
</template>

<script>
import useValidate from "@vuelidate/core";
import {
  required,
  integer,
  minValue,
  maxValue,
  requiredIf,
  helpers,
} from "@vuelidate/validators";
export default {
  data() {
    return {
      v$: useValidate(),
      hasFixedPricing: false,
      hasFlexiblePricing: false,
      hasDiscounts: true,
      discounts: [],
      discount: {},
      discountsToBeDeleted: [],
      discountToBeDeletedId: null,
    };
  },
  validations() {
    let validations = {
      venue: {
        price_per_night: {
          required: helpers.withMessage(
            "Provide the price per night for this venue.",
            required
          ),
          minValue: helpers.withMessage(
            "Provide a value higher than 1",
            minValue(1)
          ),
        },
      },
      discounts: {
        requiredIf: helpers.withMessage(
          "You chose that your venue provides length of stay discounts, add some below.",
          requiredIf(this.venue.has_pricing_discounts)
        ),
      },
      discount: {
        from: {
          required: helpers.withMessage("Provide the from value.", required),
        },
        to: {
          required: helpers.withMessage("Provide the to value.", required),
          minValue: helpers.withMessage(
            "Provide a value higher than the from value",
            minValue(this.discount.from + 1)
          ),
        },
        percentage: {
          required: helpers.withMessage("Provide the percentage.", required),
          minValue: helpers.withMessage(
            "Provide a value between 1 and 100",
            minValue(1)
          ),
          maxValue: helpers.withMessage(
            "Provide a value between 1 and 100",
            maxValue(100)
          ),
        },
      },
    };
    if (this.hasFlexiblePricing) {
      validations.venue.guests_included = {
        required: helpers.withMessage(
          "Provide the number of guests included in the pricing",
          required
        ),
        minValue: helpers.withMessage(
          "Provide a value higher than 1",
          minValue(1)
        ),
        maxValue: helpers.withMessage(
          `Provide a value lower than the maximum guests for the venue. (${this.max_guests})`,
          maxValue(this.max_guests)
        ),
      };
      validations.venue.additional_guest_price = {
        required: helpers.withMessage(
          "Provide the additional guest price.",
          required
        ),
        minValue: helpers.withMessage(
          "Provide a value higher than 1",
          minValue(1)
        ),
      };
    }
    return validations;
  },
  methods: {
    updatePricing(exit = false) {
      this.v$.venue.$touch();
      this.v$.discounts.$touch();
      if (!this.v$.venue.$error && !this.v$.discounts.$error) {
        this.$Progress.start();

        if (this.hasFixedPricing) {
          this.venue.additional_guest_price = 0;
          this.venue.guests_included = 0;
        }
        let fd = new FormData();
        fd.append("price_per_night", this.venue.price_per_night);
        fd.append("guests_included", this.venue.guests_included);
        fd.append("_method", "PUT");
        fd.append("additional_guest_price", this.venue.additional_guest_price);
        this.venue.has_pricing_discounts
          ? fd.append("has_pricing_discounts", 1)
          : fd.append("has_pricing_discounts", 0);

        fd.append(
          "discounts",
          JSON.stringify(
            this.discounts.filter((discount) => discount.isNew == true)
          )
        );
        fd.append(
          "discounts_to_be_deleted",
          JSON.stringify(this.discountsToBeDeleted)
        );
        axios
          .post(`venue/${this.venue.id}/updatePricing`, fd, {
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
                name: "saveVenue.meals_pricing",
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
    togglePricingType(i) {
      if (i == 1) {
        this.hasFlexiblePricing = !this.hasFixedPricing;
      } else {
        this.hasFixedPricing = !this.hasFlexiblePricing;
      }
    },
    addDiscount() {
      this.v$.discount.$touch();
      if (!this.v$.discount.$error) {
        this.discount.isNew = true;
        this.discounts.push(this.discount);
        this.v$.$reset();
        this.discount = {
          from: this.discount.to + 1,
          to: null,
          percentage: null,
        };
        this.$refs.to.focus();
      }
    },
    deleteDiscount() {
      const index = this.discounts.findIndex(
        (discount) => discount.id == this.discountToBeDeletedId
      );
      this.discounts.splice(index, 1);
      this.discountsToBeDeleted.push(this.discountToBeDeletedId);
    },
  },
  computed: {
    venue() {
      let venue = this.$store.getters.venueUnderSave;

      return venue;
    },
    fixedPriceCheckLabel() {
      return this.hasFixedPricing
        ? "Your property has a fixed pricing."
        : "Your property does not have a fixed pricing";
    },
    flexiblePriceCheckLabel() {
      return !this.hasFixedPricing
        ? "Your property has a flexible pricing."
        : "Your property does not have a flexible pricing";
    },
    discountLabel() {
      return this.venue.has_pricing_discounts
        ? "Your property offers length of stay discounts."
        : "Your property does not offer length of stay discounts.";
    },
    max_guests() {
      let max_guests = 0;
      if (!this.venue.accommodations) {
        return max_guests;
      }
      this.venue.accommodations.forEach((accommodation) => {
        max_guests += accommodation.quantity * accommodation.max_guests;
      });
      return max_guests;
    },
    highest_to() {
      if (this.discounts.length < 1) {
        return 0;
      }
      return this.discounts[this.discounts.length - 1].to;
    },
    max_guests() {
      if (!this.venue.accommodations) {
        return 0;
      }
      let max_guests = 0;
      this.venue.accommodations.forEach((acc) => {
        max_guests += acc.quantity * acc.max_guests;
      });
      if (!this.venue.guests_included || this.venue.guests_included == 0) {
        this.venue.guests_included = max_guests;
      }
      return max_guests;
    },
  },
  watch: {
    venue: {
      handler: function (venue) {
        if (venue.additional_guest_price == 0) {
          this.hasFixedPricing = true;
          this.hasFlexiblePricing = false;
        } else {
          this.hasFlexiblePricing = true;
          this.hasFixedPricing = false;
        }
        venue.has_pricing_discounts != 0
          ? (venue.has_pricing_discounts = true)
          : (venue.has_pricing_discounts = false);
        if (venue.discounts) {
          let discounts = venue.discounts.filter(
            (discount) => discount.type == 0
          );
          this.discounts = discounts;
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
