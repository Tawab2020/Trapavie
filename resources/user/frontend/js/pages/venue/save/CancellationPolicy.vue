<template>
  <div class="row align-items-center">
    <div class="col-12">
      <div class="card shadow rounded border-0">
        <div class="card-body py-5">
          <h4 class="card-title">Cancellation Policy</h4>
          <div class="custom-form mt-3">
            <form method="post" name="myForm" @submit.prevent="updateCancellationPolicy()">
              <div class="row mb-4">
                <label class="form-label d-block mb-1" for="name">Choose a Cancellation Policy</label>
              </div>
              <div v-for="(cp, index) in cancellationPolicies" :key="index" class="row mb-3">
                <div class="col-12 col-md-8">
                  <div
                    @click="selectPolicy(cp)"
                    :class="cp.id == cancellationPolicy.id ? 'cancellation-policy-selected custom-border no-select' : 'cancellation-policy custom-border no-select'"
                    role="button"
                  >
                    <h5>{{cp.title['en']}}</h5>
                    <div class="row mb-3">
                      <div class="col-12 col-md-4">
                        <h6>Deposit to secure the booking:</h6>
                      </div>
                      <div class="col-12 col-md-8">
                        <span>{{cp.deposit}}%</span>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-12 col-md-4">
                        <h6>Deposit refund:</h6>
                      </div>
                      <div class="col-12 col-md-8">
                        <span>{{cp.refund_percentage}}% refund for cancellations made {{cp.days_before_arrival}} days before arrival</span>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-12 col-md-4">
                        <h6>Remaining Balance:</h6>
                      </div>
                      <div class="col-12 col-md-8">
                        <span>{{cp.remaining_balance_in_days}} days before arrival</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-12 col-md-8">
                  <div
                    @click="selectCustomPolicy"
                    :class="customPolicy.isSelected ? 'cancellation-policy-selected custom-border no-select' : 'cancellation-policy custom-border no-select'"
                    role="button"
                  >
                    <h5 class="d-inline-block">Custom Policy</h5>
                    <button
                      type="button"
                      @click="displayCustomPolicyEditor"
                      class="btn btn-sm btn-outline-warning float-end"
                    >Click to edit</button>
                    <div
                      v-if="customPolicy.isEdited && !showCustomPolicyEditor"
                      class="custom-policy"
                    >
                      <div class="row mb-3">
                        <div class="col-12 col-md-4">
                          <h6>Deposit to secure the booking:</h6>
                        </div>
                        <div class="col-12 col-md-8">
                          <span>{{customPolicy.deposit}}%</span>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-12 col-md-4">
                          <h6>Deposit refund:</h6>
                        </div>
                        <div class="col-12 col-md-8">
                          <span
                            v-if="offersRefund"
                          >{{customPolicy.refund_percentage}}% refund for cancellations made {{customPolicy.days_before_arrival}} days before arrival</span>
                          <span v-else>Non Refundable</span>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-12 col-md-4">
                          <h6>Remaining Balance:</h6>
                        </div>
                        <div class="col-12 col-md-8">
                          <span>{{customPolicy.remaining_balance_in_days}} days before arrival</span>
                        </div>
                      </div>
                    </div>
                    <div v-if="showCustomPolicyEditor" class="custom-policy-editor">
                      <div class="row mb-3">
                        <div class="col-12 col-md-4">
                          <h6>Deposit to secure the booking:</h6>
                        </div>
                        <div class="col-12 col-md-8">
                          <input
                            type="range"
                            step="1"
                            min="1"
                            max="100"
                            class="form-range form-range-success"
                            v-model="customPolicy.deposit"
                          />
                          <span class="text-muted">{{customPolicy.deposit}}%</span>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-12 col-md-4">
                          <h6>Refund policy</h6>
                        </div>
                        <div class="col-12 col-md-8">
                          <div class="form-check form-switch">
                            <input
                              class="form-check-input check-toggle check-input-success"
                              type="checkbox"
                              id="refundableCheckBox"
                              v-model="offersRefund"
                            />
                            <label
                              class="form-label"
                              for="refundableCheckBox"
                            >{{offersRefund ? "Refundable" : "Non-Refundable"}}</label>
                          </div>
                        </div>
                      </div>
                      <div v-if="offersRefund" class="row mb-3">
                        <div class="col-12 col-md-4">
                          <h6>Percentage refund:</h6>
                        </div>
                        <div class="col-12 col-md-8">
                          <input
                            type="range"
                            step="1"
                            min="1"
                            max="100"
                            class="form-range form-range-success"
                            v-model="customPolicy.refund_percentage"
                          />
                          <span class="text-muted">{{customPolicy.refund_percentage}}%</span>
                        </div>
                      </div>
                      <div v-if="offersRefund" class="row mb-3">
                        <div class="col-12 col-md-4">
                          <h6>How many days before arrival:</h6>
                        </div>
                        <div class="col-12 col-md-8">
                          <input
                            type="range"
                            step="1"
                            min="1"
                            max="120"
                            class="form-range form-range-success"
                            v-model="customPolicy.days_before_arrival"
                          />
                          <span class="text-muted">{{customPolicy.days_before_arrival}}+ days</span>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-12 col-md-4">
                          <h6>Remaining balance:</h6>
                        </div>
                        <div class="col-12 col-md-8">
                          <input
                            type="range"
                            step="1"
                            min="1"
                            max="100"
                            class="form-range form-range-success"
                            v-model="customPolicy.remaining_balance_in_days"
                          />
                          <span class="text-muted">{{customPolicy.remaining_balance_in_days}} days</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <a
                            role="button"
                            class="btn btn-sm btn-outline-success float-end"
                            @click="saveCustomPolicy"
                          >Save Custom Policy</a>
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
                    @click="updateCancellationPolicy(true)"
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
import { flatten } from "lodash";
export default {
  data() {
    return {
      v$: useValidate(),
      customPolicy: {
        isEdited: false,
        isSelected: false,
        deposit: 50,
        refund_percentage: 50,
        days_before_arrival: 60,
        remaining_balance_in_days: 50,
      },
      cancellationPolicy: {},
      cancellationPolicies: [],
      offersRefund: true,
      showCustomPolicyEditor: false,
    };
  },
  validations() {
    return {};
  },
  methods: {
    updateCancellationPolicy(exit = false) {
      this.v$.$validate();
      if (!this.v$.$error) {
        this.$Progress.start();

        if (this.customPolicy.isSelected) {
          if (!this.offersRefund) {
            this.customPolicy.refund_percentage = 0;
            this.customPolicy.days_before_arrival = 0;
          }
          this.cancellationPolicy = this.customPolicy;
        }
        axios
          .put(
            `venue/${this.venue.id}/updateCancellationPolicy`,
            this.cancellationPolicy
          )
          .then(({ data }) => {
            this.$store.commit("UPDATE_VENUE_UNDER_SAVE", data.venue);
            this.$Progress.finish();
            this.$router.push({
              name: "hosting.venues",
            });
          })
          .catch((error) => {
            console.error(error);
            this.$Progress.fail();
          });
      }
    },
    selectPolicy(policy) {
      this.showCustomPolicyEditor = false;
      this.customPolicy.isSelected = false;
      this.cancellationPolicy = {
        id: policy.id,
      };
    },
    saveCustomPolicy() {
      this.showCustomPolicyEditor = false;
      this.customPolicy.isEdited = true;
      this.customPolicy.isSelected = true;
    },
    displayCustomPolicyEditor() {
      this.showCustomPolicyEditor = true;
      this.cancellationPolicy.isCustom = false;
    },
    selectCustomPolicy() {
      this.cancellationPolicy = {};
      if (this.customPolicy.isEdited) {
        this.customPolicy.isSelected = true;
      } else {
        this.displayCustomPolicyEditor();
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
        console.log(venue);
        if (venue.cancellation_policy) {
          if (venue.cancellation_policy.cancellation_policy_data_id) {
            this.cancellationPolicy = {
              id: venue.cancellation_policy.cancellation_policy_data_id,
            };
          } else {
            this.customPolicy = venue.cancellation_policy;
            if (this.customPolicy.refund_percentage == 0) {
              this.offersRefund = false;
            }
            this.customPolicy.isEdited = true;
            this.customPolicy.isSelected = true;
          }
        }
      },
      immediate: true,
    },
  },
  mounted() {
    if (!this.$route.params.venue) {
      this.$router.push({ name: "saveVenue.name_and_address" });
    }
    axios.get("cancellationPolicies").then(({ data }) => {
      this.cancellationPolicies = data;
    });
  },
};
</script>

<style>
.cancellation-policy-selected {
  border: 2px solid #2eca8b !important;
}
</style>
