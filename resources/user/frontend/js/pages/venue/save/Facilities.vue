<template>
  <div class="row align-items-center">
    <div class="col-12">
      <div class="card shadow rounded border-0">
        <div class="card-body py-5">
          <h4 class="card-title">Amenties and Facilities</h4>
          <div class="custom-form mt-3">
            <form method="post" name="myForm" @submit.prevent="updateGeneralFacilities()">
              <div id="simple-msg"></div>
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label" for="name">
                      General
                      <span class="text-danger"></span>
                    </label>
                    <small class="text-muted d-block">Select all that apply.</small>
                    <div>
                      <tag-select
                        v-if="venue.general_facilities"
                        :hasNewOptions="true"
                        :options="generalFacilities"
                        :savedItems="venue.general_facilities"
                        @itemSelected="generalFacilitiesSelected"
                      ></tag-select>
                    </div>
                    <small
                      v-if="v$.venue.general_facilities.$error"
                      class="text-danger"
                    >{{ v$.venue.general_facilities.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <small class="text-muted d-block">Add up to 6 general images</small>
                    <div class="position-relative">
                      <image-selector
                        v-if="generalImages"
                        :previousImages="generalImages"
                        @imageAdded="imageAdded($event, 2)"
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
                      Indoor
                      <span class="text-danger"></span>
                    </label>
                    <small class="text-muted d-block">Select all that apply.</small>
                    <div>
                      <tag-select
                        v-if="venue.indoor_facilities"
                        :hasNewOptions="true"
                        :options="indoorFacilities"
                        :savedItems="venue.indoor_facilities"
                        @itemSelected="indoorFacilitiesSelected"
                      ></tag-select>
                    </div>
                    <small
                      v-if="v$.venue.indoor_facilities.$error"
                      class="text-danger"
                    >{{ v$.venue.indoor_facilities.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <small class="text-muted d-block">Add indoor images</small>
                    <div class="position-relative">
                      <image-selector
                        v-if="indoorImages"
                        :previousImages="indoorImages"
                        @imageAdded="imageAdded($event, 3)"
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
                      Outdoor
                      <span class="text-danger"></span>
                    </label>
                    <small class="text-muted d-block">Select all that apply.</small>
                    <div>
                      <tag-select
                        v-if="venue.outdoor_facilities"
                        :hasNewOptions="true"
                        :options="outdoorFacilities"
                        :savedItems="venue.outdoor_facilities"
                        @itemSelected="outdoorFacilitiesSelected"
                      ></tag-select>
                    </div>
                    <small
                      v-if="v$.venue.outdoor_facilities.$error"
                      class="text-danger"
                    >{{ v$.venue.outdoor_facilities.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <small class="text-muted d-block">Add outdoor images</small>
                    <div class="position-relative">
                      <image-selector
                        v-if="outdoorImages"
                        :previousImages="outdoorImages"
                        @imageAdded="imageAdded($event, 4)"
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
                    @click="updateGeneralFacilities(true)"
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
import TagSelect from "./../../../components/TagSelect.vue";
import ImageSelector from "./../../../components/ImageSelector.vue";

import { Cropper, Preview } from "vue-advanced-cropper";
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
        general_facilities: {
          required: helpers.withMessage(
            "Please select at least one facility.",
            required
          ),
        },
        indoor_facilities: {
          required: helpers.withMessage(
            "Please select at least one facility.",
            required
          ),
        },
        outdoor_facilities: {
          required: helpers.withMessage(
            "Please select at least one facility.",
            required
          ),
        },
      },
    };
  },
  methods: {
    updateGeneralFacilities(exit = false) {
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
          "general_facilities",
          JSON.stringify(this.venue.general_facilities)
        );
        formData.append(
          "indoor_facilities",
          JSON.stringify(this.venue.indoor_facilities)
        );
        formData.append(
          "outdoor_facilities",
          JSON.stringify(this.venue.outdoor_facilities)
        );

        console.log(...formData);

        axios
          .post(`venue/${this.venue.id}/updateFacilities`, formData, {
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
                name: "saveVenue.yoga",
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
    generalFacilitiesSelected(selectedItems) {
      this.venue.general_facilities = [];
      selectedItems.forEach((item) => {
        this.venue.general_facilities.push(item);
      });
    },
    indoorFacilitiesSelected(selectedItems) {
      this.venue.indoor_facilities = [];
      selectedItems.forEach((item) => {
        this.venue.indoor_facilities.push(item);
      });
    },
    outdoorFacilitiesSelected(selectedItems) {
      this.venue.outdoor_facilities = [];
      selectedItems.forEach((item) => {
        this.venue.outdoor_facilities.push(item);
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
    generalFacilities() {
      return this.$store.getters.venueData(2);
    },
    indoorFacilities() {
      return this.$store.getters.venueData(3);
    },
    outdoorFacilities() {
      return this.$store.getters.venueData(4);
    },
    generalImages() {
      if (!this.venue.images) {
        return null;
      }
      return this.venue.images.filter((image) => image.type == 2);
    },
    indoorImages() {
      if (!this.venue.images) {
        return null;
      }
      return this.venue.images.filter((image) => image.type == 3);
    },
    outdoorImages() {
      if (!this.venue.images) {
        return null;
      }
      return this.venue.images.filter((image) => image.type == 4);
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
