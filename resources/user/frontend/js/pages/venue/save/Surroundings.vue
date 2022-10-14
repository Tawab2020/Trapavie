<template>
  <div class="row align-items-center">
    <div class="col-12">
      <div class="card shadow rounded border-0">
        <div class="card-body py-5">
          <h4 class="card-title">Surroundings and things to do</h4>
          <div class="custom-form mt-3">
            <form method="post" name="myForm" @submit.prevent="updateSurroundings()">
              <div id="simple-msg"></div>
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label" for="name">
                      Surroundings
                      <span class="text-danger"></span>
                    </label>
                    <small class="text-muted d-block">Select all that apply.</small>
                    <div>
                      <tag-select
                        v-if="venue.surroundings"
                        :hasNewOptions="true"
                        :options="surroundings"
                        :savedItems="venue.surroundings"
                        @itemSelected="surroundingSelected"
                      ></tag-select>
                    </div>
                    <small
                      v-if="v$.venue.surroundings.$error"
                      class="text-danger"
                    >{{ v$.venue.surroundings.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <small class="text-muted d-block">Add surroundings' images</small>
                    <div class="position-relative">
                      <image-selector
                        v-if="surroundingImages"
                        :previousImages="surroundingImages"
                        @imageAdded="imageAdded($event, 7)"
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
                      Things to do
                      <span class="text-danger"></span>
                    </label>
                    <small class="text-muted d-block">Select all that apply.</small>
                    <div>
                      <tag-select
                        v-if="venue.things_to_do"
                        :hasNewOptions="true"
                        :options="thingsToDo"
                        :savedItems="venue.things_to_do"
                        @itemSelected="thingsToDoSelected"
                      ></tag-select>
                    </div>
                    <small
                      v-if="v$.venue.things_to_do.$error"
                      class="text-danger"
                    >{{ v$.venue.things_to_do.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <small class="text-muted d-block">Add images regarding things to do</small>
                    <div class="position-relative">
                      <image-selector
                        v-if="thingsToDoImages"
                        :previousImages="thingsToDoImages"
                        @imageAdded="imageAdded($event, 8)"
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
                      Rentals
                      <span class="text-danger"></span>
                    </label>
                    <small class="text-muted d-block">Select all that apply.</small>
                    <div>
                      <tag-select
                        v-if="venue.rentals"
                        :hasNewOptions="true"
                        :options="rentals"
                        :savedItems="venue.rentals"
                        @itemSelected="rentalSelected"
                      ></tag-select>
                    </div>
                    <small
                      v-if="v$.venue.rentals.$error"
                      class="text-danger"
                    >{{ v$.venue.rentals.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <small class="text-muted d-block">Add images regarding rentals</small>
                    <div class="position-relative">
                      <image-selector
                        v-if="rentalImages"
                        :previousImages="rentalImages"
                        @imageAdded="imageAdded($event, 9)"
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
                    @click="updateSurroundings(true)"
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

import { Cropper } from "vue-advanced-cropper";
import "vue-advanced-cropper/dist/style.css";

import useValidate from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";

export default {
  components: {
    Cropper,
    TagSelect,
    ImageSelector,
  },
  data() {
    return {
      v$: useValidate(),
      imagesToBeUploaded: [],
      imagesToBeDeleted: [],
    };
  },
  validations() {
    return {
      venue: {
        surroundings: {
          required: helpers.withMessage(
            "Please select at least one option.",
            required
          ),
        },
        things_to_do: {
          required: helpers.withMessage(
            "Please select at least one option.",
            required
          ),
        },
        rentals: {
          required: helpers.withMessage(
            "Please select at least one option.",
            required
          ),
        },
      },
    };
  },
  methods: {
    updateSurroundings(exit = false) {
      this.v$.$validate();
      if (!this.v$.$error) {
        this.$Progress.start();

        let formData = new FormData();
        formData.append("_method", "PUT");

        if (this.imagesToBeUploaded.length > 0) {
          formData.append("images", JSON.stringify(this.imagesToBeUploaded));
        }
        if (this.imagesToBeDeleted.length > 0) {
          formData.append(
            "imagesToBeDeleted",
            JSON.stringify(this.imagesToBeDeleted)
          );
        }
        formData.append(
          "surroundings",
          JSON.stringify(this.venue.surroundings)
        );
        formData.append(
          "things_to_do",
          JSON.stringify(this.venue.things_to_do)
        );
        formData.append("rentals", JSON.stringify(this.venue.rentals));

        axios
          .post(`venue/${this.venue.id}/updateSurroundings`, formData, {
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
                name: "saveVenue.kitchen",
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
    surroundingSelected(selectedItems) {
      this.venue.surroundings = [];
      selectedItems.forEach((item) => {
        this.venue.surroundings.push(item);
      });
    },
    thingsToDoSelected(selectedItems) {
      this.venue.things_to_do = [];
      selectedItems.forEach((item) => {
        this.venue.things_to_do.push(item);
      });
    },
    rentalSelected(selectedItems) {
      this.venue.rentals = [];
      selectedItems.forEach((item) => {
        this.venue.rentals.push(item);
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
      return this.$store.getters.venueUnderSave;
    },
    surroundings() {
      return this.$store.getters.venueData(7);
    },
    thingsToDo() {
      return this.$store.getters.venueData(8);
    },
    rentals() {
      return this.$store.getters.venueData(9);
    },
    surroundingImages() {
      if (!this.venue.images) {
        return null;
      }
      return this.venue.images.filter((image) => image.type == 7);
    },
    thingsToDoImages() {
      if (!this.venue.images) {
        return null;
      }
      return this.venue.images.filter((image) => image.type == 8);
    },
    rentalImages() {
      if (!this.venue.images) {
        return null;
      }
      return this.venue.images.filter((image) => image.type == 9);
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
