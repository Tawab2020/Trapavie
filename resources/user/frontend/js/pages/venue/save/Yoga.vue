<template>
  <div class="row align-items-center">
    <div class="col-12">
      <div class="card shadow rounded border-0">
        <div class="card-body py-5">
          <h4 class="card-title">Yoga and Workshop Space</h4>
          <div class="custom-form mt-3">
            <form method="post" name="myForm" @submit.prevent="updateYoga()">
              <div id="simple-msg"></div>
              <div class="row mb-2">
                <div class="col-12">
                  <label
                    class="form-label d-block"
                  >What type of yoga/workshop space does your property have?</label>
                </div>
                <div class="col-12 col-md-4">
                  <select v-model="venue.yoga_spaces" class="form-control">
                    <option value="1">Indoor Yoga/Workshop Space</option>
                    <option value="2">Outdoor Yoga/Workshop Space</option>
                    <option value="3">Both</option>
                    <option value="0">None</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div v-if="venue.yoga_spaces == 1 || venue.yoga_spaces == 3" class="mb-2 mt-2">
                    <label class="form-label category-label">
                      Indoor Yoga Workshop
                      <span class="text-danger"></span>
                    </label>
                    <small
                      class="text-muted d-block mb-2"
                    >You selected that your property has an indoor yoga/workshop space, specify the capacity and the equipment included.</small>
                    <div class="mb-2">
                      <div class="row">
                        <div class="col-12 col-md-4">
                          <label class="form-label" for="name">
                            Indoor Capacity
                            <span class="text-danger"></span>
                          </label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12 col-md-2">
                          <input
                            v-model="venue.indoor_capacity"
                            class="form-control"
                            type="number"
                            min="1"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="mb-2">
                      <tag-select
                        v-if="indoorYogaEquipments"
                        :hasNewOptions="true"
                        :options="indoorYogaEquipments"
                        :savedItems="venue.indoor_yoga_equipments"
                        @itemSelected="indoorYogaEquipmentsSelected"
                      ></tag-select>
                    </div>
                    <small
                      v-if="v$.venue.indoor_yoga_equipments.$error"
                      class="text-danger"
                    >{{ v$.venue.indoor_yoga_equipments.$errors[0].$message }}</small>
                    <div class="row mb-2">
                      <div class="col-md-12">
                        <small class="text-muted d-block">Add images for indoor yoga place</small>
                        <div class="position-relative">
                          <image-selector
                            v-if="indoorYogaImages"
                            :previousImages="indoorYogaImages"
                            @imageAdded="imageAdded($event, 5)"
                            @savedImageDeleted="savedImageDeleted"
                            @newImageDeleted="newImageDeleted"
                          ></image-selector>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div v-if="venue.yoga_spaces == 2 || venue.yoga_spaces == 3" class="mb-2 mt-4">
                    <label class="form-label category-label">
                      Outdoor Yoga Workshop
                      <span class="text-danger"></span>
                    </label>
                    <small
                      class="text-muted d-block mb-2"
                    >You selected that your property has an outdoor yoga/workshop space, specify the capacity and the equipment included.</small>
                    <div class="mb-2">
                      <div class="row">
                        <div class="col-12 col-md-4">
                          <label class="form-label" for="name">
                            Outdoor Capacity
                            <span class="text-danger"></span>
                          </label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12 col-md-2">
                          <input
                            class="form-control"
                            v-model="venue.outdoor_capacity"
                            type="number"
                            min="1"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="mb-2">
                      <tag-select
                        v-if="outdoorYogaEquipments"
                        :hasNewOptions="true"
                        :options="outdoorYogaEquipments"
                        :savedItems="venue.outdoor_yoga_equipments"
                        @itemSelected="outdoorYogaEquipmentSelected"
                      ></tag-select>
                    </div>

                    <div class="row mb-2">
                      <div class="col-md-12">
                        <small class="text-muted d-block">Add images for outdoor yoga place</small>
                        <div class="position-relative">
                          <image-selector
                            v-if="outdoorYogaImages"
                            :previousImages="outdoorYogaImages"
                            @imageAdded="imageAdded($event, 6)"
                            @savedImageDeleted="savedImageDeleted"
                            @newImageDeleted="newImageDeleted"
                          ></image-selector>
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
                    @click="updateYoga(true)"
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

import { Cropper } from "vue-advanced-cropper";
import "vue-advanced-cropper/dist/style.css";

import useValidate from "@vuelidate/core";
import { requiredIf, helpers } from "@vuelidate/validators";

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
        indoor_yoga_equipments: {
          requiredIf: helpers.withMessage(
            "Please select at least one equipment.",
            requiredIf(
              this.venue.yoga_spaces == 1 || this.venue.yoga_spaces == 3
            )
          ),
        },
        indoor_capacity: {
          requiredIf: helpers.withMessage(
            "Please provide the capacity.",
            requiredIf(
              this.venue.yoga_spaces == 1 || this.venue.yoga_spaces == 3
            )
          ),
        },
        outdoor_yoga_equipments: {
          requiredIf: helpers.withMessage(
            "Please select at least one equipment.",
            requiredIf(
              this.venue.yoga_spaces == 2 || this.venue.yoga_spaces == 3
            )
          ),
        },
        outdoor_capacity: {
          requiredIf: helpers.withMessage(
            "Please provide the capacity.",
            requiredIf(
              this.venue.yoga_spaces == 2 || this.venue.yoga_spaces == 3
            )
          ),
        },
      },
    };
  },
  methods: {
    updateYoga(exit = false) {
      this.v$.$validate();
      if (!this.v$.$error) {
        this.$Progress.start();

        let formData = new FormData();
        formData.append("_method", "PUT");

        // delete indoor data, if not selected
        if (this.venue.yoga_spaces == 0 || this.venue.yoga_spaces == 2) {
          this.venue.indoor_capacity = 0;
          this.venue.indoor_yoga_equipments = [];
          this.indoorYogaImages.forEach((image) => {
            this.deleteImage(image);
          });
        }
        // delete outdoor data, if not selected
        if (this.venue.yoga_spaces == 0 || this.venue.yoga_spaces == 1) {
          this.venue.outdoor_capacity = 0;
          this.venue.outdoor_yoga_equipments = [];
          this.outdoorYogaImages.forEach((image) => {
            this.deleteImage(image);
          });
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

        formData.append("yoga_spaces", this.venue.yoga_spaces);
        formData.append("indoor_capacity", this.venue.indoor_capacity);
        formData.append("outdoor_capacity", this.venue.outdoor_capacity);

        formData.append(
          "outdoor_yoga_equipments",
          JSON.stringify(this.venue.outdoor_yoga_equipments)
        );
        formData.append(
          "indoor_yoga_equipments",
          JSON.stringify(this.venue.indoor_yoga_equipments)
        );

        axios
          .post(`venue/${this.venue.id}/updateYoga`, formData, {
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
                name: "saveVenue.surroundings",
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
    indoorYogaEquipmentsSelected(selectedItems) {
      this.venue.indoor_yoga_equipments = [];
      selectedItems.forEach((item) => {
        this.venue.indoor_yoga_equipments.push(item);
      });
    },
    outdoorYogaEquipmentSelected(selectedItems) {
      this.venue.outdoor_yoga_equipments = [];
      selectedItems.forEach((item) => {
        this.venue.outdoor_yoga_equipments.push(item);
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
      if (venue.yoga_spaces == null) {
        venue.yoga_spaces = 0;
      }
      return venue;
    },
    indoorYogaEquipments() {
      return this.$store.getters.venueData(5);
    },
    outdoorYogaEquipments() {
      return this.$store.getters.venueData(6);
    },
    indoorYogaImages() {
      if (!this.venue.images) {
        return null;
      }
      return this.venue.images.filter((image) => image.type == 5);
    },
    outdoorYogaImages() {
      if (!this.venue.images) {
        return null;
      }
      return this.venue.images.filter((image) => image.type == 6);
    },
  },
  watch: {},
  mounted() {},
};
</script>

<style>
</style>
