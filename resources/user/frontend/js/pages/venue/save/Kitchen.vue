<template>
  <div class="row align-items-center">
    <div class="col-12">
      <div class="card shadow rounded border-0">
        <div class="card-body py-5">
          <h4 class="card-title">Kitchen and Food</h4>
          <div class="custom-form mt-3">
            <form method="post" name="myForm" @submit.prevent="updateKitchen()">
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label" for="name">
                      Food
                      <span class="text-danger"></span>
                    </label>
                    <div class="form-check form-switch">
                      <input
                        class="form-check-input check-toggle check-input-success"
                        type="checkbox"
                        id="foodCheckbox"
                        v-model="hasFood"
                      />
                      <label class="info-label" for="foodCheckbox">{{foodCheckLabel}}</label>
                    </div>
                    <div v-if="hasFood">
                      <small
                        class="text-muted d-block"
                      >What kinds of diets can your property cater to? Select all that apply?</small>
                      <tag-select
                        v-if="venue.food"
                        :hasNewOptions="true"
                        :options="food"
                        :savedItems="venue.food"
                        @itemSelected="foodSelected"
                      ></tag-select>
                    </div>
                    <small
                      v-if="v$.venue.food.$error"
                      class="text-danger"
                    >{{ v$.venue.food.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <div v-if="hasFood" class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <small class="text-muted d-block">Add food images</small>
                    <div class="position-relative">
                      <image-selector
                        v-if="foodImages"
                        :previousImages="foodImages"
                        @imageAdded="imageAdded($event, 10)"
                        @savedImageDeleted="savedImageDeleted"
                        @newImageDeleted="newImageDeleted"
                      ></image-selector>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label" for="name">
                      Kitchen
                      <span class="text-danger"></span>
                    </label>
                    <div class="form-check form-switch">
                      <input
                        class="form-check-input check-toggle check-input-success"
                        type="checkbox"
                        id="kitchenCheckBox"
                        v-model="hasKitchen"
                      />
                      <label class="info-label" for="kitchenCheckBox">{{kitchenCheckLabel}}</label>
                    </div>
                    <div v-if="hasKitchen">
                      <div class="mb-2">
                        <div class="row">
                          <div class="col-12 col-md-4">
                            <label class="form-label" for="name">
                              Dining Capacity
                              <span class="text-danger"></span>
                            </label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12 col-md-2">
                            <input
                              v-model="venue.dining_capacity"
                              class="form-control"
                              type="number"
                              min="1"
                            />
                            <small
                              v-if="v$.venue.dining_capacity.$error"
                              class="text-danger"
                            >{{ v$.venue.dining_capacity.$errors[0].$message }}</small>
                          </div>
                        </div>
                      </div>
                      <small class="text-muted d-block">Select all that apply.</small>
                      <tag-select
                        v-if="venue.kitchen"
                        :hasNewOptions="true"
                        :options="kitchen"
                        :savedItems="venue.kitchen"
                        @itemSelected="kitchenSelected"
                      ></tag-select>
                    </div>
                    <small
                      v-if="v$.venue.kitchen.$error"
                      class="text-danger"
                    >{{ v$.venue.kitchen.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <div v-if="hasKitchen" class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <small class="text-muted d-block">Add kitchen images</small>
                    <div class="position-relative">
                      <image-selector
                        v-if="kitchenImages"
                        :previousImages="kitchenImages"
                        @imageAdded="imageAdded($event, 11)"
                        @savedImageDeleted="savedImageDeleted"
                        @newImageDeleted="newImageDeleted"
                      ></image-selector>
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
                    @click="updateKitchen(true)"
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
import TagSelect from "../../../components/TagSelect.vue";
import ImageSelector from "../../../components/ImageSelector.vue";

import useValidate from "@vuelidate/core";
import { requiredIf, helpers } from "@vuelidate/validators";

export default {
  components: {
    TagSelect,
    ImageSelector,
  },
  data() {
    return {
      v$: useValidate(),
      imagesToBeUploaded: [],
      imagesToBeDeleted: [],
      hasFood: false,
      hasKitchen: false,
    };
  },
  validations() {
    return {
      venue: {
        food: {
          requiredIf: helpers.withMessage(
            "Please select at least one diet.",
            requiredIf(this.hasFood)
          ),
        },
        kitchen: {
          requiredIf: helpers.withMessage(
            "Please select at least one.",
            requiredIf(this.hasKitchen)
          ),
        },
        dining_capacity: {
          requiredIf: helpers.withMessage(
            "Please provide the dining capacity.",
            requiredIf(this.hasKitchen)
          ),
        },
      },
    };
  },
  methods: {
    updateKitchen(exit = false) {
      this.v$.$validate();
      if (!this.v$.$error) {
        let formData = new FormData();
        formData.append("_method", "PUT");

        if (!this.hasFood) {
          this.foodImages.forEach((image) => {
            this.deleteImage(image);
          });
          this.venue.food = [];
        }
        if (!this.hasKitchen) {
          this.kitchenImages.forEach((image) => {
            this.deleteImage(image);
          });
          this.venue.kitchen = [];
          this.venue.dining_capacity = 0;
        }
        if (this.imagesToBeUploaded.length > 0) {
          formData.append("images", JSON.stringify(this.imagesToBeUploaded));
        }
        if (this.imagesToBeDeleted.length > 0) {
          formData.append(
            "imagesToBeDeleted",
            JSON.stringify(this.imagesToBeDeleted)
          );
        }

        formData.append("kitchen", JSON.stringify(this.venue.kitchen));
        formData.append("dining_capacity", this.venue.dining_capacity);

        formData.append("food", JSON.stringify(this.venue.food));

        axios
          .post(`venue/${this.venue.id}/updateKitchen`, formData, {
            headers: { "Content-Type": "multipart/form-data" },
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
                name: "saveVenue.accommodations",
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
    deleteImage(image) {
      if (!this.imagesToBeDeleted.some((img) => img.id == image.id)) {
        this.imagesToBeDeleted.push(image.id);
      } else {
        return;
      }
    },
    kitchenSelected(selectedItems) {
      this.venue.kitchen = [];
      selectedItems.forEach((item) => {
        this.venue.kitchen.push(item);
      });
    },
    foodSelected(selectedItems) {
      this.venue.food = [];
      selectedItems.forEach((item) => {
        this.venue.food.push(item);
      });
    },
    imageAdded(image, type) {
      image.type = type;
      const index = this.imagesToBeUploaded.findIndex(
        (img) => img.id == image.id
      );
      if (index >= 0) {
        this.imagesToBeUploaded[index] = image;
      } else {
        this.imagesToBeUploaded.push(image);
      }
    },
    savedImageDeleted(toBeDeleted) {
      this.imagesToBeDeleted.push(toBeDeleted);
    },
    newImageDeleted(imageId) {
      const index = this.imagesToBeUploaded.findIndex(
        (img) => img.id == imageId
      );
      this.imagesToBeUploaded.splice(index, 1);
    },
  },
  computed: {
    venue() {
      let venue = this.$store.getters.venueUnderSave;
      if (venue.dining_capacity == null) {
        venue.dining_capacity = 0;
      }
      return venue;
    },
    food() {
      return this.$store.getters.venueData(10);
    },
    kitchen() {
      return this.$store.getters.venueData(11);
    },
    foodImages() {
      if (!this.venue.images) {
        return null;
      }
      return this.venue.images.filter((image) => image.type == 10);
    },
    kitchenImages() {
      if (!this.venue.images) {
        return null;
      }
      return this.venue.images.filter((image) => image.type == 11);
    },
    foodCheckLabel() {
      return this.hasFood
        ? "Your property offers catering options"
        : "Your property does not offer catering options";
    },
    kitchenCheckLabel() {
      return this.hasKitchen
        ? "Your property provides a kitchen for organizers and guests to use."
        : "Your property does not provide a kitchen for organizers and guests to use.";
    },
  },
  watch: {
    venue: {
      handler: function (venue) {
        if (venue.food) {
          if (venue.food.length > 0) {
            this.hasFood = true;
          }
        }
        if (venue.dining_capacity > 0) {
          this.hasKitchen = true;
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
