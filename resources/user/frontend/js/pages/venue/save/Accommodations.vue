<template>
  <div class="row align-items-center">
    <div class="col-12">
      <div class="card shadow rounded border-0">
        <div class="card-body py-5">
          <h4 class="card-title">Accommodations</h4>
          <div class="custom-form mt-3">
            <form method="post" name="myForm" @submit.prevent="updateAccommodations()">
              <div class="mb-3">
                <div class="row">
                  <div class="col-12 col-md-8">
                    <label class="form-label" for="name">
                      Minimum number of guests
                      <span class="text-danger"></span>
                    </label>
                    <small
                      class="text-muted d-block mb-2"
                    >What is the minimum number of guests you require for booking? If there is no minimum, select 1.</small>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-2">
                    <input v-model="venue.min_guests" class="form-control" type="number" min="1" />
                  </div>
                  <small
                    v-if="v$.venue.min_guests.$error"
                    class="text-danger"
                  >{{ v$.venue.min_guests.$errors[0].$message }}</small>
                </div>
              </div>
              <div class="mb-3">
                <div class="row">
                  <div class="col-12 col-md-8">
                    <label class="form-label" for="name">
                      Maximum number of guests
                      <span class="text-danger"></span>
                    </label>
                    <small
                      class="text-muted d-block mb-2"
                    >Calculated automatically as you add accommodations.</small>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-2">
                    <span class="input-like-span d-inline-block">{{max_guests}}</span>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <div
                  v-for="(accommodation, index) in venue.accommodations"
                  :key="index"
                  class="accommodation mt-3"
                >
                  <div class="row">
                    <div class="col-12 accommodation-info">
                      <div class="row">
                        <div class="col-9">
                          <h6>{{accommodation.name}}</h6>
                          <p>
                            <span>{{accommodationNameFromType(accommodation.accommodation_type_id)}} X {{accommodation.quantity}}</span>
                          </p>
                        </div>
                        <div class="col-3 d-none d-md-block">
                          <div class="row d-flex align-items-center">
                            <!-- <div class="col-4 mx-0 px-0">
                              <input
                                v-model="accommodation.quantity"
                                max="99"
                                min="1"
                                type="number"
                                class="form-control mx-0"
                                id
                              />
                            </div>-->
                            <div class="col-12">
                              <div class="row align-items-center">
                                <div class="col-6">
                                  <a
                                    href="javascript:void(0)"
                                    class="btn btn-outline-warning px-4 py-1 w-100"
                                    @click="openAccommodationModal(accommodation)"
                                  >Edit</a>
                                </div>
                                <div class="col-6">
                                  <a
                                    href="javascript:void(0)"
                                    data-bs-toggle="modal"
                                    @click="this.accommodationToBeDeletedId = accommodation.id"
                                    data-bs-target="#confirm-delete-modal"
                                    class="btn btn-outline-danger px-4 py-1 w-100"
                                  >Delete</a>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <p v-for="(bedroom, i) in accommodation.bedrooms" :key="i">
                        <span>Room #{{i + 1}}:</span>
                        {{bedroom.bathroom_type == 0 ? 'Ensuite' : 'Shared'}} Bathroom,
                        <span
                          v-for="(arrangement, j) in bedroom.bedroom_arrangements"
                          :key="j"
                        >{{arrangement.pivot.quantity}} {{arrangement.title['en']}}{{arrangement.pivot.quantity > 1 ? 's' : ''}}{{j + 1 == bedroom.bedroom_arrangements.length ? '.' : ',&nbsp;'}}</span>
                      </p>

                      <p>
                        <span class="font-weight-600">Description:</span>
                        {{accommodation.description}}
                      </p>

                      <p>
                        <span>Maximum guests:</span>
                        {{accommodation.max_guests}}
                      </p>

                      <p>
                        <span>Amenities:&nbsp;</span>
                        <span
                          v-for="(amenity, i) in accommodation.amenities"
                          :key="i"
                        >{{amenity.title['en']}}{{i+1 == accommodation.amenities.length ? '.' : ',&nbsp;'}}</span>
                      </p>
                    </div>
                    <div class="col-12">
                      <div class="mb-3">
                        <small class="text-muted d-block">Add accommodation images</small>
                        <div class="position-relative">
                          <image-selector
                            :key="accommodation.id"
                            v-if="accommodation.images"
                            :previousImages="Array.from(accommodation.images)"
                            @imageAdded="addImage($event, accommodation.id)"
                            @savedImageDeleted="deleteSavedImage($event, accommodation.id)"
                            @newImageDeleted="deleteImage($event, accommodation.id)"
                          ></image-selector>
                        </div>
                        <div
                          v-if="accommodation.imagesToBeUploaded || accommodation.imagesToBeDeleted"
                          class="col-lg-9 col-12 offset-lg-3 mt-3"
                        >
                          <button
                            :disabled="saveChangesButtonDisabled"
                            type="button"
                            @click="saveImages(accommodation.id, accommodation.imagesToBeUploaded, accommodation.imagesToBeDeleted)"
                            class="btn btn-outline-warning px-4 py-1"
                          >Save Changes</button>
                        </div>
                      </div>
                    </div>
                    <hr class="d-block d-md-none" />
                    <div class="col-12 d-block d-md-none">
                      <div class="row d-flex align-items-center">
                        <!-- <div class="col-4">
                          <input
                            v-model="accommodation.quantity"
                            max="99"
                            min="1"
                            type="number"
                            class="form-control mx-0"
                            id
                          />
                        </div>-->
                        <div class="col-12">
                          <div class="row align-items-center">
                            <div class="col-6">
                              <a
                                href="javascript:void(0)"
                                class="btn btn-outline-warning px-4 py-1 w-100"
                                @click="openAccommodationModal(accommodation)"
                              >Edit</a>
                            </div>
                            <div class="col-6">
                              <a
                                href="javascript:void(0)"
                                class="btn btn-outline-danger px-4 py-1 w-100"
                              >Delete</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-md-">
                  <a
                    href="javascript:void(0)"
                    @click="openAccommodationModal()"
                    class="btn btn-outline-warning m-1"
                  >Add accommodation</a>
                </div>
                <small
                  v-if="v$.venue.accommodations.$error"
                  class="text-danger"
                >{{ v$.venue.accommodations.$errors[0].$message }}</small>
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
                    @click="updateAccommodations(true)"
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
  <!-- Accommodation Modal Start -->
  <div
    ref="modal"
    class="modal fade"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    id="accommodation-modal"
    tabindex="-1"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content rounded shadow border-0">
        <div class="modal-header border-bottom">
          <h5 class="modal-title" id="LoginForm-title">Save Accommodation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="row mb-3">
              <div class="col-12">
                <label class="form-label" for="name">
                  Accommodation name
                  <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                  <input
                    id="name"
                    type="text"
                    class="form-control ps-3"
                    v-model="accommodation.name"
                    placeholder="Type something ..."
                  />
                  <small
                    v-if="v$.accommodation.name.$error"
                    class="text-danger"
                  >{{ v$.accommodation.name.$errors[0].$message }}</small>
                </div>
              </div>
            </div>

            <!--end row-->
            <div class="row mb-3">
              <div class="col-12">
                <label class="form-label mb-0" for="accommodation-type">
                  What best describes this accommodation?
                  <span class="text-danger">*</span>
                </label>
                <div class>
                  <tag-select
                    v-if="accommodationTypes"
                    :hasNewOptions="true"
                    :single="true"
                    :options="accommodationTypes"
                    :savedItem="savedAccommodationType"
                    @itemSelected="accommodationTypeSelected"
                  ></tag-select>
                </div>
                <small
                  v-if="v$.accommodation.accommodation_type_id.$error"
                  class="text-danger"
                >{{ v$.accommodation.accommodation_type_id.$errors[0].$message }}</small>
              </div>
            </div>
            <div
              v-if="accommodationType.ask_for_rooms == 1 || accommodationType.id == 0"
              class="row"
            >
              <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="name">
                  How many bedrooms does this {{accommodationType.title["en"] ? accommodationType.title["en"].toLowerCase() : accommodationType.title.toLowerCase()}} have?
                  <span
                    class="text-danger"
                  >*</span>
                </label>
                <div class="position-relative">
                  <input
                    id="name"
                    type="number"
                    min="1"
                    v-model="numberOfBedrooms"
                    class="form-control ps-3"
                  />
                  <!-- <small class="text-danger">Error Message</small> -->
                </div>
              </div>
              <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="name">
                  How many bathrooms does this {{accommodationType.title["en"] ? accommodationType.title["en"].toLowerCase() : accommodationType.title.toLowerCase()}} have?
                  <span
                    class="text-danger"
                  >*</span>
                </label>
                <div class="position-relative">
                  <input
                    id="name"
                    type="number"
                    min="1"
                    v-model="accommodation.number_of_bathrooms"
                    class="form-control ps-3"
                  />
                  <!-- <small class="text-danger">Error Message</small> -->
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="name">
                  Maximum number of guests
                  <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                  <input
                    id="name"
                    type="number"
                    min="1"
                    v-model="accommodation.max_guests"
                    class="form-control ps-3"
                  />
                  <!-- <small class="text-danger">Error Message</small> -->
                </div>
              </div>
              <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="name">
                  Quantity
                  <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                  <input
                    id="name"
                    type="number"
                    min="1"
                    v-model="accommodation.quantity"
                    class="form-control ps-3"
                  />
                  <small
                    class="text-muted"
                  >How many {{accommodationType.title ? accommodationType.title["en"] ? accommodationType.title["en"].toLowerCase() + 's' : accommodationType.title.toLowerCase() + 's' : "accommodations"}} like this does your venue have?</small>
                  <!-- <small class="text-danger">Error Message</small> -->
                </div>
              </div>
            </div>
            <div
              v-for="(bedroom, index) in accommodation.bedrooms"
              :key="index"
              class="custom-border mb-3"
            >
              <div class="row">
                <div class="col-12">
                  <label class="form-label" for="name">
                    Sleeping arrangements for bedroom {{index + 1}}
                    <span class="text-danger">*</span>
                  </label>
                  <div class>
                    <tag-select
                      v-if="bedroom.bedroom_arrangements"
                      :options="bedroomArrangements"
                      :savedItems="bedroom.bedroom_arrangements"
                      @itemSelected="bedroomArrangementSelected($event, index)"
                    ></tag-select>
                  </div>
                  <small
                    v-if="bedroomArrangementsError(index)"
                    class="text-danger"
                  >{{v$.accommodation.bedrooms.$each.$response.$errors[index].bedroom_arrangements[0].$message}}</small>
                </div>
              </div>
              <div v-if="bedroom.bedroom_arrangements" class="row mb-3">
                <div
                  v-for="(arrangement, i) in bedroom.bedroom_arrangements"
                  :key="i"
                  class="col-6 col-md-3 mt-3"
                >
                  <label class="form-label mb-1" for="name">{{arrangement.title['en']}}</label>
                  <input
                    id="name"
                    type="number"
                    v-model="arrangement.pivot.quantity"
                    class="form-control"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-4">
                  <label class="form-label" for="name">Bathroom</label>
                  <div>
                    <select class="form-control form-override" v-model="bedroom.bathroom_type">
                      <option value="0">Ensuite</option>
                      <option value="1">Shared</option>
                    </select>
                    <span
                      v-if="v$.accommodation.bedrooms.$each.$response.$errors[index].bathroom_type"
                    >
                      <small
                        v-if="v$.accommodation.bedrooms.$each.$response.$errors[index].bathroom_type.length"
                        class="text-danger"
                      >Has erro</small>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!--end row-->
            <div class="row mb-3">
              <div class="col-12">
                <label class="form-label mb-0" for="accommodation-type">
                  Accommodation Amenities
                  <span class="text-danger">*</span>
                </label>
                <div class>
                  <tag-select
                    v-if="accommodation.amenities"
                    :options="accommodationAmenities"
                    :savedItems="accommodation.amenities"
                    @itemSelected="accommodationAmenitySelected"
                  ></tag-select>
                </div>
                <small
                  v-if="v$.accommodation.amenities.$error"
                  class="text-danger"
                >{{ v$.accommodation.amenities.$errors[0].$message }}</small>
              </div>
            </div>

            <!--end row-->
            <div class="row mb-3">
              <div class="col-12">
                <label class="form-label mb-0" for="accommodation-type">
                  Accommodation Description
                  <span class="text-danger">*</span>
                </label>
                <div class>
                  <textarea
                    class="form-control"
                    v-model="accommodation.description"
                    rows="5"
                    maxlength="3000"
                    placeholder="Type something here"
                  ></textarea>
                  <small
                    class="text-muted d-block"
                  >{{accommodationDescriptionLength}} / 3000 Characters</small>
                </div>
                <small
                  v-if="v$.accommodation.description.$error"
                  class="text-danger"
                >{{ v$.accommodation.description.$errors[0].$message }}</small>
              </div>
            </div>
          </div>
          <!--end container-->
        </div>
        <div class="modal-footer">
          <button
            type="button"
            @click="handleAccommodationSave"
            class="btn btn-outline-warning"
          >{{this.accommodation.id ? "Update Accommodation" : "Add Accommodation"}}</button>
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
  <!-- Accommodation Modal End -->

  <!-- Confirm accommodation Deletion Modal Start -->
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
              <h4>Delete Accommodation?</h4>
              <p
                class="text-muted"
              >Are you absolutely sure you want to delete this accommodation? This action is irreversible!</p>
              <div class="mt-4">
                <a
                  href="javascript:void(0)"
                  @click="deleteAccommodation(this.accommodationToBeDeletedId)"
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
  <!-- Confirm accommodation Deletion Modal End -->
</template>

<script>
import TagSelect from "../../../components/TagSelect.vue";
import ImageSelector from "../../../components/ImageSelector.vue";
import useValidate from "@vuelidate/core";
import {
  required,
  integer,
  maxValue,
  minValue,
  maxLength,
  helpers,
} from "@vuelidate/validators";
export default {
  components: {
    TagSelect,
    ImageSelector,
  },
  data() {
    return {
      v$: useValidate(),
      accommodation: {},
      accommodationType: {
        ask_for_rooms: false,
      },
      numberOfBedrooms: 1,
      accommodationToBeDeletedId: null,
      saveChangesButtonDisabled: false,
    };
  },
  validations() {
    return {
      venue: {
        min_guests: {
          required: helpers.withMessage(
            "Provide the minimum number of guests for this accommodation.",
            required
          ),
          minValue: helpers.withMessage(
            "Minimum number of guests should be less than maximum number of guests.",
            minValue(1)
          ),
          maxValue: helpers.withMessage(
            "Minimum number of guests should be less than maximum number of guests.",
            maxValue(this.max_guests)
          ),
        },
        accommodations: {
          required: helpers.withMessage(
            "Add at least one accommodation.",
            required
          ),
        },
      },
      accommodation: {
        name: {
          required: helpers.withMessage(
            "Provide the name for this accommodation.",
            required
          ),
        },
        max_guests: {
          required: helpers.withMessage(
            "Provide maximum number of guests.",
            required
          ),
          integer: helpers.withMessage("This should be a number.", integer),
        },
        accommodation_type_id: {
          required: helpers.withMessage(
            "Please choose an accommodation type.",
            required
          ),
        },
        number_of_bathrooms: {
          required: helpers.withMessage(
            "Provide the number of bathrooms.",
            required
          ),
          integer: helpers.withMessage("This should be a number.", integer),
        },
        bedrooms: {
          $each: helpers.forEach({
            bedroom_arrangements: {
              required: helpers.withMessage(
                "Select at least one arrangement for this bedroom.",
                required
              ),
              $each: helpers.forEach({
                quantity: {
                  required: helpers.withMessage(
                    "Select at least one arrangement for this bedroom.",
                    required
                  ),
                  integer: helpers.withMessage(
                    "This should be a number.",
                    integer
                  ),
                },
              }),
            },
            bathroom_type: {
              required: helpers.withMessage("Select bathroom type.", required),
            },
          }),
        },
        amenities: {
          required: helpers.withMessage(
            "Select at least one amenity for this accommodation.",
            required
          ),
        },
        description: {
          required: helpers.withMessage(
            "Write a description for this accommodation.",
            required
          ),
          maxLength: helpers.withMessage(
            "Description must be up to 3000 characters or less.",
            maxLength(3000)
          ),
        },
      },
    };
  },
  methods: {
    openAccommodationModal(accommodation = null) {
      this.v$.$reset();

      if (accommodation) {
        const index = this.accommodationTypes.findIndex(
          (accType) => accType.id == accommodation.accommodation_type_id
        );
        if (index >= 0) {
          this.accommodationType = this.accommodationTypes[index];
        }
        this.accommodation = accommodation;
      } else {
        this.resetAccommodation();
      }
      this.accommodationModal.show();
    },
    updateAccommodations(exit = false) {
      this.v$.venue.$touch();
      if (!this.v$.venue.$error) {
        this.$Progress.start();

        let fd = new FormData();
        fd.append("min_guests", this.venue.min_guests);
        axios
          .put(`venue/${this.venue.id}/updateAccommodations`, this.venue)
          .then(({ data }) => {
            this.$store.commit("UPDATE_VENUE_UNDER_SAVE", data.venue);
            this.$Progress.finish();
            if (exit) {
              this.$router.push({
                name: "hosting.venues",
              });
            } else {
              this.$router.push({
                name: "saveVenue.venue_pricing",
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
    accommodationTypeSelected(accommodationType) {
      // if new type is added by the user, we will send it with the accommodation, and save it as a new accommodationType in our database, and attach it to the accommodation
      if (accommodationType.id == 0) {
        accommodationType.property_type_id = this.venue.property_type_id;
        this.accommodation.accommodationType = accommodationType;
      }
      this.accommodation.accommodation_type_id = accommodationType.id;
      this.accommodationType = accommodationType;
    },
    bedroomArrangementSelected(selectedItems, bedroomIndex) {
      this.accommodation.bedrooms[bedroomIndex].bedroom_arrangements.forEach(
        (arrangement, arrangement_index) => {
          const index = selectedItems.findIndex(
            (item) => item.id == arrangement.id
          );
          if (index < 0) {
            this.accommodation.bedrooms[
              bedroomIndex
            ].bedroom_arrangements.splice(arrangement_index, 1);
          }
        }
      );
      selectedItems.forEach((item) => {
        if (
          !this.accommodation.bedrooms[bedroomIndex].bedroom_arrangements.some(
            (arrangement) => arrangement.id == item.id
          )
        ) {
          item.pivot = {
            quantity: 1,
          };
          this.accommodation.bedrooms[bedroomIndex].bedroom_arrangements.push(
            item
          );
        }
      });
    },
    accommodationAmenitySelected(selectedItems) {
      this.accommodation.amenities = [];
      selectedItems.forEach((item) => {
        this.accommodation.amenities.push(item);
      });
    },
    resetAccommodation() {
      this.accommodationType = {
        ask_for_rooms: false,
      };
      this.accommodation = {
        max_guests: 1,
        number_of_bathrooms: 1,
        bedrooms: [
          {
            bedroom_arrangements: [],
            bathroom_type: 0,
          },
        ],
        amenities: [],
        quantity: 1,
        images: [],
      };
    },
    handleAccommodationSave() {
      this.v$.accommodation.$touch();
      if (!this.v$.accommodation.$error) {
        this.accommodation.venue_id = this.venue.id;
        //save the accommodation

        if (!this.accommodation.id) {
          axios
            .put("venue/saveAccommodation", this.accommodation)
            .then(({ data }) => {
              this.venue.accommodations.push(data.accommodation);
            })
            .catch((error) => console.error(error));
          this.v$.$reset();
          this.resetAccommodation();
          this.accommodationModal.hide();

          return;
        } else {
          axios.put(
            `venue/saveAccommodation/${this.accommodation.id}`,
            this.accommodation
          );

          this.accommodationModal.hide();
          const index = this.venue.accommodations.findIndex(
            (acc) => acc.id == this.accommodation.id
          );
          this.venue.accommodations[index] = this.accommodation;
          this.v$.$reset();
          this.resetAccommodation();
        }
      }
    },
    addImage(image, accommodationId) {
      const index = this.venue.accommodations.findIndex(
        (acc) => acc.id == accommodationId
      );
      if (!this.venue.accommodations[index].imagesToBeUploaded) {
        this.venue.accommodations[index].imagesToBeUploaded = [];
      }
      const imgIndex = this.venue.accommodations[
        index
      ].imagesToBeUploaded.findIndex((img) => img.id == image.id);

      if (imgIndex >= 0) {
        this.venue.accommodations[index].imagesToBeUploaded[imgIndex] = image;
      } else {
        this.venue.accommodations[index].imagesToBeUploaded.push(image);
      }
    },
    deleteImage(imageId, accommodationId) {
      const index = this.venue.accommodations.findIndex(
        (acc) => acc.id == accommodationId
      );
      const imgIndex = this.venue.accommodations[
        index
      ].imagesToBeUploaded.findIndex((img) => img.id == imageId);
      this.venue.accommodations[index].imagesToBeUploaded.splice(imgIndex, 1);
    },
    deleteSavedImage(imageId, accommodationId) {
      const index = this.venue.accommodations.findIndex(
        (acc) => acc.id == accommodationId
      );
      const imgIndex = this.venue.accommodations[index].images.findIndex(
        (img) => img.id == imageId
      );
      if (!this.venue.accommodations[index].images[imgIndex].isNew) {
        if (!this.venue.accommodations[index].imagesToBeDeleted) {
          this.venue.accommodations[index].imagesToBeDeleted = [];
        }
        this.venue.accommodations[index].imagesToBeDeleted.push(
          this.venue.accommodations[index].images[imgIndex]
        );
      }
      this.venue.accommodations[index].images.splice(imgIndex, 1);
    },
    saveImages(accommodationId, imagesToBeUploaded, imagesToBeDeleted = null) {
      this.saveChangesButtonDisabled = true;
      let fd = new FormData();
      if (imagesToBeUploaded) {
        fd.append("images", JSON.stringify(imagesToBeUploaded));
      }
      if (imagesToBeDeleted) {
        fd.append("imagesToBeDeleted", JSON.stringify(imagesToBeDeleted));
      }
      fd.append("_method", "PUT");

      axios
        .post(`venue/saveAccommodationImages/${accommodationId}`, fd, {
          headers: { "Content-Type": "multipart/form-data" },
        })
        .then((response) => {
          const index = this.venue.accommodations.findIndex(
            (acc) => acc.id == accommodationId
          );
          this.saveChangesButtonDisabled = false;

          this.venue.accommodations[index].imagesToBeUploaded = null;
          this.venue.accommodations[index].imagesToBeDeleted = null;
        })
        .catch((error) => {
          console.error(error);
          this.saveChangesButtonDisabled = false;
        });
    },
    deleteAccommodation(accommodationId) {
      axios
        .delete(`venue/deleteAccommodation/${accommodationId}`)
        .then(({ data }) => {
          const index = this.venue.accommodations.findIndex(
            (acc) => acc.id == accommodationId
          );
          this.venue.accommodations.splice(index, 1);
        });
    },
  },
  computed: {
    venue() {
      let venue = this.$store.getters.venueUnderSave;
      if (venue.min_guests == null) {
        venue.min_guests = 0;
      }
      return venue;
    },
    max_guests() {
      if (!this.venue.accommodations) {
        return 0;
      }
      let max_guests = 0;
      this.venue.accommodations.forEach((acc) => {
        max_guests += acc.quantity * acc.max_guests;
      });
      return max_guests;
    },
    venuePropertyTypeIndex() {
      return this.$store.getters.propertyTypes.findIndex(
        (propertyType) => propertyType.id == this.venue.property_type_id
      );
    },
    accommodationTypes() {
      if (this.$store.getters.propertyTypes[this.venuePropertyTypeIndex]) {
        return this.$store.getters.propertyTypes[this.venuePropertyTypeIndex]
          .user_accommodation_types;
      }
      return [];
    },
    savedAccommodationType() {
      if (this.accommodation.accommodation_type_id) {
        const index = this.accommodationTypes.findIndex(
          (acc) => acc.id == this.accommodation.accommodation_type_id
        );
        return this.accommodationTypes[index];
      } else {
        return 0;
      }
    },
    accommodationAmenities() {
      return this.$store.getters.venueData(12);
    },
    bedroomArrangements() {
      return this.$store.getters.venueData(13);
    },
    accommodationModal() {
      return new bootstrap.Modal(this.$refs.modal);
    },
    bedroomArrangementsError() {
      return (index) => {
        if (
          this.v$.accommodation.bedrooms.$anyDirty &&
          this.v$.accommodation.bedrooms.$each.$response.$errors[index]
        ) {
          if (
            this.v$.accommodation.bedrooms.$each.$response.$errors[index]
              .bedroom_arrangements.length
          ) {
            return true;
          }
        }
        return false;
      };
    },
    accommodationNameFromType() {
      return (id) => {
        if (this.accommodationTypes.length > 0) {
          const index = this.accommodationTypes.findIndex(
            (acc) => acc.id == id
          );
          if (index >= 0) {
            return this.accommodationTypes[index].title["en"];
          }
        }
        return "";
      };
    },
    accommodationDescriptionLength() {
      return this.accommodation.description
        ? this.accommodation.description.length
        : 0;
    },
  },
  watch: {
    accommodationType: function (value) {
      if (!value.ask_for_rooms) {
        this.numberOfBedrooms = 1;
      }
    },
    numberOfBedrooms: function (value) {
      let difference = value - this.accommodation.bedrooms.length;
      if (difference > 0) {
        for (let i = 0; i < difference; i++) {
          this.accommodation.bedrooms.push({
            bedroom_arrangements: [],
            bathroom_type: 0,
          });
        }
      } else if (difference < 0) {
        for (let i = 0; i < Math.abs(difference); i++) {
          this.accommodation.bedrooms.pop();
        }
      }
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
.input-like-span {
  border: 1px solid #dee2e6;
  padding: 0.4rem 1.5rem;
  border-radius: 0.4rem;
}
.accommodation {
  border: 1px solid #dee2e6;
  padding: 1rem;
  border-radius: 1rem;
}
.delete-btn {
  font-size: 1.8rem;
}
</style>
