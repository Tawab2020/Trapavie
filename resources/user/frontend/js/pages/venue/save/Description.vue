<template>
  <div class="row align-items-center">
    <div class="col-12">
      <div class="card shadow rounded border-0">
        <div class="card-body py-5">
          <h4 class="card-title">Venue description and features</h4>
          <div class="custom-form mt-3">
            <form method="post" name="myForm" @submit.prevent="updateDescriptionFeatures()">
              <div id="simple-msg"></div>
              <div class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label" for="name">
                      Listing banner
                      <span class="text-danger">*</span>
                    </label>
                    <small
                      class="text-muted d-block"
                    >Select a picture for your listing’s banner. This picture will be the first thing the retreat organizers will see when they visit your listing.</small>
                    <div class="position-relative">
                      <div class="row mt-3">
                        <div class="col-md-3 col-12">
                          <input
                            @change="listingBannerChange($event.target.files)"
                            class="form-control"
                            id="listingBannerInput"
                            type="file"
                            accept="image/*"
                          />
                          <small
                            v-if="v$.listingBanner.$error"
                            class="text-danger"
                          >{{ v$.listingBanner.$errors[0].$message }}</small>
                        </div>
                        <div
                          v-if="listingBannerSrc"
                          class="col-md-4 col-12 mt-3 mt-md-0 text-center"
                        >
                          <div class="row d-flex justify-content-center align-content-center">
                            <div class="col-6">
                              <img
                                :src="listingBannerSrc"
                                class="listingBannerPreview float-md-left shadow"
                              />
                            </div>
                            <div class="col-2">
                              <label for class="form-label mx-2">Crop</label>
                              <br />
                              <button
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#crop-modal"
                                class="btn btn-sm btn-outline-danger p-0 px-2 mx-2 mt-md-2 h5"
                              >
                                <i class="uil uil-crop-alt"></i>
                              </button>
                            </div>
                            <div v-if="imagePreview" class="col-2">
                              <label for class="form-label mx-2">Cancel</label>
                              <br />
                              <button
                                type="button"
                                @click="removeListingBanner()"
                                class="btn btn-sm btn-outline-danger p-0 px-2 mx-2 mt-md-2 h5"
                              >
                                <i class="uil uil-times"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label" for="name">
                      Venue description
                      <span class="text-danger"></span>
                    </label>
                    <small
                      class="text-muted d-block"
                    >Use this space to describe your property. Add details about the space, amenities, surroundings, and activities offered (if any). You may talk about different food options and kitchen amenities. If your property has yoga/workshop space(s), make sure to include this in your description. You can also include a small paragraph about yourself, your vision, and values. Include any information that would appeal to retreat organizers, such as previous retreat hosting experience or any eco-friendly practices.</small>

                    <textarea
                      id="additional_info"
                      class="form-control mt-2"
                      cols="30"
                      rows="8"
                      :maxlength="9000"
                      v-model="venue.description"
                    ></textarea>
                    <small class="text-muted d-block">{{descriptionLength}} / 9000 Characters</small>
                    <small
                      v-if="v$.venue.description.$error"
                      class="text-danger"
                    >{{ v$.venue.description.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label" for="name">
                      Key Property Features
                      <span class="text-danger"></span>
                    </label>
                    <small class="text-muted d-block">Select all that apply.</small>
                    <div>
                      <tag-select
                        v-if="venue.key_property_features"
                        :options="keyPropertyFeatures"
                        :single="false"
                        :savedItems="venue.key_property_features"
                        @itemSelected="keyPropertyFeaturesSelected"
                      ></tag-select>
                    </div>
                    <small
                      v-if="v$.venue.key_property_features.$error"
                      class="text-danger"
                    >{{ v$.venue.key_property_features.$errors[0].$message }}</small>
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
                    @click="updateDescriptionFeatures(true)"
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
  <!-- Wishlist Popup Start -->
  <div
    ref="modal"
    class="modal fade"
    id="crop-modal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded shadow border-0">
        <div class="modal-header border-bottom">
          <h5 class="modal-title" id="LoginForm-title">Crop Listing Banner</h5>
          <button
            type="button"
            class="btn btn-icon btn-close"
            data-bs-dismiss="modal"
            id="close-modal"
          >
            <i class="uil uil-times fs-4 text-dark"></i>
          </button>
        </div>
        <div class="modal-body py-5">
          <div class="text-center">
            <div>
              <cropper
                ref="cropper"
                class="cropper"
                :src="toBeCropped"
                :stencil-props="{ aspectRatio: 16 / 9 }"
              />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            @click="crop()"
            class="btn btn-outline-primary"
            data-bs-dismiss="modal"
          >Crop</button>
          <button
            type="button"
            role="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Wishlist Popup End -->
</template>

<script>
import TagSelect from "./../../../components/TagSelect.vue";

import { Cropper } from "vue-advanced-cropper";
import "vue-advanced-cropper/dist/style.css";

import useValidate from "@vuelidate/core";
import { required, maxLength, helpers } from "@vuelidate/validators";
function listingBannerRequired() {
  if (
    this.venue.images.some((img) => img.type == 1) ||
    this.listingBanner ||
    this.imagePreview
  ) {
    return true;
  } else {
    return false;
  }
}
// const listingBannerRequired = (value) => console.log(this.listingBanner);
const isImage = (value) => (value ? value.type.startsWith("image") : true);
const sizeCheck = (value) => (value ? value.size < 5 * 1024 * 1024 : true);
export default {
  components: {
    Cropper,
    TagSelect,
  },
  data() {
    return {
      v$: useValidate(),
      image: null,
      listingBanner: null,
      imagePreview: null,
      cropResult: null,
    };
  },
  validations() {
    return {
      venue: {
        description: {
          required: helpers.withMessage("Please add a description.", required),
          maxLength: helpers.withMessage(
            "Description must be up to 9000 characters or less.",
            maxLength(9000)
          ),
        },
        key_property_features: {
          required: helpers.withMessage(
            "Please select at least one feature.",
            required
          ),
        },
      },
      listingBanner: {
        required: helpers.withMessage(
          "Please add a listing banner.",
          listingBannerRequired
        ),
        isImage: helpers.withMessage(
          "Please provide an image file only.",
          isImage
        ),
        sizeCheck: helpers.withMessage(
          "Image can't be larger than 5MB.",
          sizeCheck
        ),
      },
    };
  },
  methods: {
    updateDescriptionFeatures(exit = false) {
      this.v$.$validate();
      if (!this.v$.$error) {
        this.$Progress.start();
        let formData = new FormData();
        formData.append("_method", "PUT");

        if (this.imagePreview) {
          if (this.cropResult) {
            formData.append("image", this.cropResult);
          } else {
            formData.append("image", this.imagePreview);
          }
        }
        formData.append("description", this.venue.description);
        formData.append(
          "key_property_features",
          JSON.stringify(this.venue.key_property_features)
        );
        console.log(...formData);

        axios
          .post(`venue/${this.venue.id}/descriptionFeatures`, formData, {
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
                name: "saveVenue.facilities",
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
    crop() {
      const { canvas } = this.$refs.cropper.getResult();
      this.cropResult = canvas.toDataURL();
    },
    listingBannerChange(fileList) {
      if (fileList.length == 0) {
        this.listingBanner = null;
        this.imagePreview = null;
        return;
      }
      this.showImage(fileList[0]);
      var imageFile = fileList[0];
      this.listingBanner = imageFile;
      this.v$.listingBanner.$touch();
      return;
    },

    showImage(file) {
      var reader = new FileReader();
      if (reader) {
        reader.onload = (e) => {
          this.imagePreview = e.target.result;
        };
      }
      this.showCropModal();
      reader.readAsDataURL(file);
      this.v$.listingBanner.$touch();
    },

    removeListingBanner() {
      this.listingBanner = null;
      this.imagePreview = null;
      this.cropResult = null;
      document.getElementById("listingBannerInput").value = null;
    },

    showCropModal() {
      var myModal = new bootstrap.Modal(document.getElementById("crop-modal"), {
        keyboard: false,
      });

      myModal.show();
    },

    keyPropertyFeaturesSelected(selectedItems) {
      this.venue.key_property_features = [];
      selectedItems.forEach((item) => {
        this.venue.key_property_features.push(item);
      });
    },
  },
  computed: {
    venue() {
      return this.$store.getters.venueUnderSave;
    },
    keyPropertyFeatures() {
      return this.$store.getters.venueData(1);
    },
    previousListingBanner() {
      if (!this.venue.images) {
        return null;
      }
      const index = this.venue.images.findIndex((image) => image.type == 1);
      if (index >= 0) {
        return this.venue.images[index].image;
      } else {
        return null;
      }
    },
    toBeCropped() {
      if (this.listingBanner) {
        return this.imagePreview;
      } else if (this.previousListingBanner) {
        return `${this.$FilePath}/venue/${this.previousListingBanner}`;
      }
    },
    listingBannerSrc() {
      if (this.cropResult) {
        return this.cropResult;
      } else if (this.imagePreview) {
        return this.imagePreview;
      } else if (this.previousListingBanner) {
        return `${this.$FilePath}/venue/${this.previousListingBanner}`;
      } else {
        return null;
      }
    },
    descriptionLength() {
      return this.venue.description ? this.venue.description.length : 0;
    },
  },
  mounted() {
    if (!this.$route.params.venue) {
      this.$router.push({ name: "saveVenue.name_and_address" });
    }

    const { cropper, modal } = this.$refs;
    modal.addEventListener("shown.bs.modal", () => {
      if (cropper) {
        cropper.refresh();
      }
    });
  },
};
</script>

<style>
.listingBannerPreview {
  width: 150px;
  max-height: 84px;
  border-radius: 10px;
}
</style>
