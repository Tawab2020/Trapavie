<template>
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <router-link :to="{name: 'venueData.save' }">
            <button type="button" class="btn-add btn btn-outline-success">
              <i class="dripicons-plus"></i> Add Venue Data
            </button>
          </router-link>
        </div>
        <div class="page-title-right mx-3">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Tripavie</a>
            </li>
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Admin</a>
            </li>
            <li class="breadcrumb-item active">Venue Data</li>
          </ol>
        </div>
        <h4 class="page-title">Manage Dynamic Venue Data</h4>
      </div>
    </div>
  </div>
  <div class="row" v-if="venueDataCategories.length > 0">
    <template v-for="(venueDataCategory, i) in venueDataCategories" :key="i">
      <div class="col-xl-6 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="card-title">
              <span v-if="venueDataCategory.image" class="float-start m-2 me-4">
                <img
                  :src="`${$FilePath}/venue/data/${venueDataCategory.image.image}`"
                  style="height: 100px;"
                  alt
                  class="rounded-circle img-thumbnail"
                />
              </span>
              <h4 class="mt-1 mb-1 d-inline-block">{{venueDataCategory.title['en']}}</h4>
              <router-link
                :to="{name: 'venueDataCategory.save', params: {venueDataCategory: venueDataCategory.id || 1}}"
              >
                <button type="button" class="btn btn-outline-primary float-end">Edit</button>
              </router-link>
              <router-link
                :to="{name: 'venueData.save', params: {venueDataCategory: venueDataCategory.id || 1}}"
              >
                <button type="button" class="btn btn-outline-success float-end mx-1">
                  <i class="dripicons-plus"></i>Add Data
                </button>
              </router-link>
              <div class="row mt-2">
                <div class="col-12">
                  <span
                    v-for="(venueData, index) in venueDataCategory.venue_data"
                    :key="index"
                    class="badge badge-outline-secondary venue-badge m-1"
                  >
                    <router-link :to="{name: 'venueData.save', params: {venueData: venueData.id}}">
                      <i class="dripicons-document-edit venue-badge-btn text-success d-none"></i>
                    </router-link>
                    {{venueData.title['en']}}
                    <i
                      class="dripicons-tag-delete venue-badge-btn text-danger d-none"
                      @click="toBeDeletedVenueDataId = venueData.id; toBeDeletedVenueDataCategoryId = venueDataCategory.id"
                      data-bs-toggle="modal"
                      data-bs-target="#delete-confirmation-modal"
                    ></i>
                  </span>
                </div>
              </div>
            </div>
            <!-- end div-->
          </div>
          <!-- end card-body-->
        </div>
      </div>
    </template>
  </div>
  <div class="row" v-else>
    <div class="card">
      <div class="card-body">
        <h4 class="mt-1 mb-1">No Venue Data Categories added</h4>
      </div>
    </div>
  </div>
  <!-- Delete Confirmation Modal -->
  <div
    id="delete-confirmation-modal"
    class="modal fade"
    tabindex="-1"
    role="dialog"
    aria-labelledby="delete-confirmation-modalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header modal-colored-header bg-danger">
          <h4 class="modal-title" id="delete-confirmation-modalLabel">Delete Venue Data?</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 class="mt-0">Are you absolutely sure?</h5>
          <p>Are you sure you want to delete this Venue Data? You won't be able to delete this if it is used in a venue! This action is irreversible in any ways.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button
            type="button"
            class="btn btn-danger"
            @click="deleteVenueData(toBeDeletedVenueDataCategoryId, toBeDeletedVenueDataId)"
            data-bs-dismiss="modal"
          >Delete</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</template>

<script>
export default {
  data() {
    return {
      toBeDeletedVenueDataId: null,
      toBeDeletedVenueDataCategoryId: null,
    };
  },
  methods: {
    deleteVenueData(venueDataCategoryID, venueDataId) {
      axios
        .delete(`venueData/${venueDataId}`)
        .then((response) => {
          this.$store.commit("DELETE_VENUE_DATA", {
            venueDataCategoryID,
            venueDataId,
          });
          this.$toast.success(response.data.message, {
            position: "top-right",
          });
        })
        .catch((response) => {
          this.$toast.error(response.data.message, {
            position: "top-right",
          });
        });
    },
  },
  computed: {
    venueDataCategories() {
      if (this.$store.getters.venueDataCategories[0]) {
        return this.$store.getters.venueDataCategories;
      }
      return [
        {
          title: {},
        },
      ];
    },
  },
};
</script>

<style>
.venue-badge {
  font-size: 14px;
}
.venue-badge:hover .venue-badge-btn {
  display: inline !important;
}
</style>
