<template>
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <router-link :to="{name: 'propertyType.save' }">
            <button type="button" class="btn-add btn btn-outline-success">
              <i class="dripicons-plus"></i> Add Property Type
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
            <li class="breadcrumb-item active">Property Types</li>
          </ol>
        </div>
        <h4 class="page-title">Manage Dynamic Property Types</h4>
      </div>
    </div>
  </div>
  <div class="row" v-if="propertyTypes.length > 0">
    <template v-for="(propertyType, i) in propertyTypes" :key="i">
      <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="card-title">
              <h4 class="mt-1 mb-1 d-inline-block">{{propertyType.title['en']}}</h4>
              <router-link
                :to="{name: 'propertyType.save', params: {propertyType: propertyType.id || 1}}"
              >
                <button type="button" class="btn btn-outline-primary float-end">Edit</button>
              </router-link>
              <div class="row mt-2">
                <div class="col-12">
                  <span
                    v-for="(accommodationType, index) in propertyType.accommodationTypes"
                    :key="index"
                    class="badge badge-outline-secondary venue-badge m-1"
                  >{{accommodationType.title['en']}}</span>
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
        <h4 class="mt-1 mb-1">No Property Types added</h4>
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
          <h4 class="modal-title" id="delete-confirmation-modalLabel">Delete Property Type?</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 class="mt-0">Are you absolutely sure?</h5>
          <p>Are you sure you want to delete this Property Type? You won't be able to delete this if it is used in a venue! This action is irreversible in any ways.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button
            type="button"
            class="btn btn-danger"
            @click="deleteVenueData(toBeDeletedPropertyTypeID)"
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
      toBeDeletedPropertyTypeID: null,
    };
  },
  methods: {
    deletePropertyType(propertyTypeID) {
      axios
        .delete(`propertyType/${propertyTypeID}`)
        .then((response) => {
          this.$store.commit("DELETE_PROPERTY_TYPE", propertyTypeID);
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
    propertyTypes() {
      if (this.$store.getters.propertyTypes[0]) {
        return this.$store.getters.propertyTypes;
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
