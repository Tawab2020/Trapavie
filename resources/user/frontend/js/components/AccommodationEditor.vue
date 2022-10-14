<template>
  <a
    href="javascript:void(0)"
    @click="openModal()"
    class="btn btn-outline-warning m-1"
  >Add accommodation</a>
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
              <div class="col-8">
                <label class="form-label" for="name">
                  Accommodation name
                  <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                  <input
                    id="name"
                    type="text"
                    class="form-control ps-3"
                    placeholder="Type something ..."
                  />
                  <!-- <small class="text-danger">Error Message</small> -->
                </div>
              </div>
              <div class="col-4">
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
                    :savedItem="4"
                    @itemSelected="accommodationTypeSelected"
                  ></tag-select>
                </div>
              </div>
            </div>
            <div
              v-if="accommodationType.ask_for_rooms == 1 || accommodationType.id == 0"
              class="row mb-3"
            >
              <div class="col-12 col-md-6">
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
              <div class="col-12 col-md-6">
                <label class="form-label" for="name">
                  How many bathrooms does {{accommodationType.title["en"] ? accommodationType.title["en"].toLowerCase() : accommodationType.title.toLowerCase()}} this have?
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
                </div>
              </div>
              <div v-if="bedroom.bedroom_arrangements" class="row mb-3">
                <div
                  v-for="(arrangement, index) in bedroom.bedroom_arrangements"
                  :key="index"
                  class="col-6 col-md-3 mt-3"
                >
                  <label class="form-label mb-1" for="name">{{arrangement.title['en']}}</label>
                  <input
                    id="name"
                    type="number"
                    v-model="arrangement.quantity"
                    class="form-control"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-4">
                  <label class="form-label" for="name">Bathroom</label>
                  <div>
                    <select class="form-control" v-model="bedroom.bathroom_type">
                      <option value="0">Ensuite</option>
                      <option value="1">Shared</option>
                    </select>
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
            data-bs-dismiss="modal"
          >Add Accommodation</button>
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
</template>

<script>
export default {
  methods: {
    openModal() {
      this.accommodationModal.show();
    },
  },
  computed: {
    accommodationModal() {
      return new bootstrap.Modal(this.$refs.modal);
    },
  },
};
</script>

<style>
</style>
