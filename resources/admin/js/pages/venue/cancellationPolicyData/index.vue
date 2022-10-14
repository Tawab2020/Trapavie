<template>
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <router-link :to="{name: 'cancellationPolicyData.save' }">
            <button type="button" class="btn-add btn btn-outline-success">
              <i class="dripicons-plus"></i> Add Cancellation Policy
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
            <li class="breadcrumb-item active">Cancellation Policies</li>
          </ol>
        </div>
        <h4 class="page-title">Manage Dynamic Property Types</h4>
      </div>
    </div>
  </div>
  <div class="row" v-if="cancellationPolicyData.length > 0">
    <template v-for="(cancellationPolicy, i) in cancellationPolicyData" :key="i">
      <div class="col-xl-6 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="card-title">
              <h4 class="mt-1 mb-1 d-inline-block">{{cancellationPolicy.title['en']}}</h4>
              <router-link
                :to="{name: 'cancellationPolicyData.save', params: {cancellationPolicy: cancellationPolicy.id || 1}}"
              >
                <button type="button" class="btn btn-outline-primary float-end">Edit</button>
              </router-link>
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
        <h4 class="mt-1 mb-1">No cancellation policies added</h4>
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
          <h4 class="modal-title" id="delete-confirmation-modalLabel">Delete Cancellatio Policy?</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 class="mt-0">Are you absolutely sure?</h5>
          <p>Are you sure you want to delete this Cancellation Policy? You won't be able to delete this if it is used in a venue! This action is irreversible in any ways.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button
            type="button"
            class="btn btn-danger"
            @click="deleteCancellationPolicyData(toBeDeletedCancellationPolicyID)"
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
      cancellationPolicyData: [],
      toBeDeletedCancellationPolicyID: null,
    };
  },
  methods: {
    deleteCancellationPolicyData(cancellationPolicyID) {
      axios
        .delete(`cancellationPolicyData/${cancellationPolicyID}`)
        .then((response) => {
          this.$store.commit("DELETE_PROPERTY_TYPE", cancellationPolicyID);
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
  mounted() {
    axios.get("cancellationPolicyData").then((response) => {
      this.cancellationPolicyData = response.data;
    });
  },
};
</script>

<style>
</style>
