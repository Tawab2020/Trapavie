<template>
  <div class="row align-items-center">
    <div class="col-12">
      <div class="card shadow rounded border-0">
        <div class="card-body py-5">
          <h4 class="card-title">Meals Pricing</h4>
          <h6>Are meals included in the price? Please specify.</h6>
          <div class="custom-form mt-3">
            <form method="post" name="myForm" @submit.prevent="updateMeals()">
              <span>If meals are extra, please input the daily price per guest.</span>
              <div
                v-for="(meal, index) in meals"
                :key="index"
                class="row mb-3 d-flex align-items-center mt-2"
              >
                <div class="col-6 col-md-3 col-xl-2">
                  <span
                    :class="selectedMeals.some(m => m.title['en'].toLowerCase() == meal.title['en'].toLowerCase()) ? 'badge badge-md bg-custom-success w-75' : 'badge badge-md badge-soft-white w-75'"
                    @click="toggleMeal(meal.title['en'])"
                  >{{meal.title['en']}}</span>
                  <i
                    class="uil uil-trash text-danger ms-2 lead w-25"
                    role="button"
                    v-if="meal.isNew"
                    @click="deleteMeal(meal.title['en'])"
                  ></i>
                </div>
                <div
                  v-if="selectedMeals.some(m => m.title['en'].toLowerCase() == meal.title['en'].toLowerCase())"
                  class="col-3 col-md-1"
                >
                  <div class="row">
                    <div
                      class="col-md-6 col-12 p-0 d-flex justify-content-center justify-content-md-end"
                    >
                      <div class="form-check form-switch">
                        <input
                          class="form-check-input check-toggle check-input-success"
                          type="checkbox"
                          :id="`${index}-mealCheck`"
                          v-model="meals[index].included"
                        />
                      </div>
                    </div>
                    <div class="col-md-6 col-12 p-0 text-center text-md-start">
                      <label
                        class="info-label no-select"
                        :for="`${index}-mealCheck`"
                      >{{meals[index].included ? 'Included' : 'Extra'}}</label>
                    </div>
                  </div>
                </div>
                <div v-if="!meals[index].included" class="col-3 col-md-2 ms-md-3">
                  <input
                    type="text"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                    v-model="meals[index].price"
                    min="1"
                    class="form-control form-control-inline py-0"
                  />
                  <small
                    v-if="priceRequiredError(index)"
                    class="text-danger d-block"
                  >{{v$.meals.$each.$response.$errors[index].price[0].$message}}</small>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-8 col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    v-model="newMeal"
                    v-on:keydown.enter.prevent="addMeal"
                  />
                  <small
                    v-if="v$.newMeal.$error"
                    class="text-danger"
                  >{{ v$.newMeal.$errors[0].$message }}</small>
                </div>

                <div class="col-4 col-md-1">
                  <a role="button" class="btn btn-outline-warning" @click="addMeal">Add</a>
                </div>
              </div>
              <div v-if="venue.has_meal_discounts || selectedMeals.length" class="row">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label no-select" for="name">Number of guests discount</label>
                    <div class="form-check form-switch">
                      <input
                        class="form-check-input check-toggle check-input-success"
                        type="checkbox"
                        id="discountCheck"
                        v-model="venue.has_meal_discounts"
                      />
                      <label class="info-label no-select" for="discountCheck">{{discountLabel}}</label>
                    </div>
                    <small
                      v-if="v$.discounts.$error"
                      class="text-danger"
                    >{{ v$.discounts.$errors[0].$message }}</small>

                    <div v-if="venue.has_meal_discounts">
                      <small class="text-muted">Enter the number of guests and discount percentage</small>
                      <div class="row mb-3">
                        <div class="col-12 col-md-2 mt-2 mt-md-0">
                          <label class="info-lable" for="name">From</label>
                          <input
                            type="number"
                            ref="from"
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
                          <input type="number" v-model="discount.to" class="form-control" />
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
                              {{discount.percentage}}% discount for {{discount.from}} to {{discount.to}} guests
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
                    @click="updateMeals(true)"
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
              <p class="text-muted">Are you absolutely sure you want to delete this accommodation?</p>
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
      meals: [
        {
          included: true,
          title: {
            en: "Breakfast",
          },
          price: 0,
        },
        {
          included: true,
          title: {
            en: "Lunch",
          },
          price: 0,
        },
        {
          included: true,
          title: {
            en: "Dinner",
          },
          price: 0,
        },
      ],
      selectedMeals: [],
      newMeal: "",
      discounts: [],
      discount: {
        type: 1,
      },
      discountsToBeDeleted: [],
      discountToBeDeletedId: null,
    };
  },
  validations() {
    return {
      newMeal: {
        required: helpers.withMessage("Provide the meal title.", required),
      },
      meals: {
        $each: helpers.forEach({
          price: {
            maxValue: helpers.withMessage(
              "Provide a reasonable price.",
              maxValue(9999)
            ),
            requiredIf: helpers.withMessage(
              "Provide the price for this meal.",
              requiredIf(this.priceRequired)
            ),
          },
        }),
      },
      discounts: {
        requiredIf: helpers.withMessage(
          "You chose that your venue provides length of stay discounts, add some below.",
          requiredIf(this.venue.has_meal_discounts == true)
        ),
      },
      discount: {
        from: {
          required: helpers.withMessage("Provide the from value.", required),
          minValue: helpers.withMessage(
            `Provide a value higher than or equal to the previous discount's to (${this.highest_to})`,
            minValue(this.highest_to)
          ),
          maxValue: helpers.withMessage(
            `Provide a value lower than or equal to the maximum guests for the venue. (${this.max_guests})`,
            maxValue(this.max_guests)
          ),
        },
        to: {
          required: helpers.withMessage("Provide the to value.", required),
          minValue: helpers.withMessage(
            "Provide a value higher than the from value",
            minValue(this.discount.from + 1)
          ),
          maxValue: helpers.withMessage(
            `Provide a value lower than the maximum guests for the venue. (${this.max_guests})`,
            maxValue(this.max_guests)
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
  },
  methods: {
    updateMeals(exit = false) {
      this.v$.meals.$touch();
      this.v$.discounts.$touch();
      if (!this.v$.meals.$error && !this.v$.discounts.$error) {
        this.$Progress.start();

        this.selectedMeals.forEach((meal) => {
          if (meal.included) {
            meal.price = 0;
          }
        });

        let fd = new FormData();
        fd.append("_method", "PUT");
        fd.append("meals", JSON.stringify(this.selectedMeals));
        this.venue.has_meal_discounts
          ? fd.append("has_meal_discounts", 1)
          : fd.append("has_meal_discounts", 0);

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
          .post(`venue/${this.venue.id}/updateMeals`, fd, {
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
                name: "saveVenue.retreat_organizer",
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
    toggleMeal(mealTitle) {
      const index = this.selectedMeals.findIndex(
        (meal) => meal.title["en"].toLowerCase() == mealTitle.toLowerCase()
      );
      const i = this.meals.findIndex(
        (meal) => meal.title["en"].toLowerCase() == mealTitle.toLowerCase()
      );
      if (index >= 0) {
        this.meals[i].included = true;
        this.selectedMeals.splice(index, 1);
      } else {
        this.selectedMeals.push(this.meals[i]);
      }
    },
    addMeal() {
      this.v$.newMeal.$touch();
      if (!this.v$.newMeal.$error) {
        if (
          !this.selectedMeals.some(
            (meal) =>
              meal.title["en"].toString().toLowerCase() ==
              this.newMeal.toString().toLowerCase()
          )
        ) {
          let meal = {
            title: { en: this.newMeal },
            isNew: true,
            price: 0,
          };
          this.newMeal = "";
          this.meals.push(meal);
          this.selectedMeals.push(meal);
        } else {
          this.newMeal = "";
          return;
        }
        this.v$.$reset();
      }
    },
    deleteMeal(mealTitle) {
      const index = this.selectedMeals.findIndex(
        (meal) => meal.title["en"].toLowerCase() == mealTitle.toLowerCase()
      );
      this.selectedMeals.splice(index, 1);
      const i = this.meals.findIndex(
        (meal) => meal.title["en"].toLowerCase() == mealTitle.toLowerCase()
      );
      this.meals.splice(i, 1);
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
        this.$refs.from.focus();
      }
    },
    deleteDiscount() {
      const index = this.discounts.findIndex(
        (discount) => discount.id == this.discountToBeDeletedId
      );
      if (!this.discounts[index].isNew) {
        this.discountsToBeDeleted.push(this.discountToBeDeletedId);
      }
      this.discounts.splice(index, 1);
    },
  },
  computed: {
    venue() {
      return this.$store.getters.venueUnderSave;
    },
    mealSelected() {
      return (mealTitle) => {
        this.selectedMeals.some(
          (meal) =>
            meal.title.toString().toLowerCase() ==
            mealTitle.toString().toLowerCase()
        );
      };
    },
    discountLabel() {
      return this.venue.has_meal_discounts
        ? "Your property offers number of guests discount."
        : "Your property does not offer number of guests discount.";
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
    priceRequired() {
      this.meals.forEach((meal) => {
        if (!meal.included && meal.price) {
          return false;
        }
      });
      return true;
    },
    priceRequiredError() {
      return (index) => {
        if (
          this.v$.meals.$anyDirty &&
          this.v$.meals.$each.$response.$errors[index]
        ) {
          if (this.v$.meals.$each.$response.$errors[index].price.length) {
            return true;
          }
        }
        return false;
      };
    },
  },
  watch: {
    venue: {
      handler: function (venue) {
        if (venue.meals) {
          if (venue.meals.length > 0) {
            venue.meals.forEach((meal) => {
              const index = this.meals.findIndex(
                (m) =>
                  m.title["en"].toLowerCase() == meal.title["en"].toLowerCase()
              );
              if (meal.price > 0) {
                meal.included = false;
              } else {
                meal.included = true;
              }
              if (index >= 0) {
                this.meals[index] = meal;
              } else {
                this.meals.push(meal);
              }
              this.selectedMeals.push(meal);
            });
          }
        }

        this.venue.has_meal_discounts != 0
          ? (this.venue.has_meal_discounts = true)
          : (this.venue.has_meal_discounts = false);
        console.log(this.venue.has_meal_discounts);
        if (venue.discounts) {
          let discounts = this.venue.discounts.filter(
            (discount) => discount.type == 1
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
