<template>
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Tripavie</a>
            </li>
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Admin</a>
            </li>
            <li class="breadcrumb-item active">Fees</li>
          </ol>
        </div>
        <h4 class="page-title">Update Fees</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="updateFee()" enctype="multipart/form-data">
            <h5 class="mb-4 text-uppercase">Update admin and transaction fees</h5>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="admin_fees_percentage" class="form-label">Admin Fee Percentage</label>
                  <input
                    type="number"
                    step="0.01"
                    class="form-control"
                    v-model="fee.admin_fees_percentage"
                    required
                    id="admin_fees_percentage"
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="transaction_fees_percentage" class="form-label">Transaction Percentage</label>
                  <input
                    type="number"
                    step="0.01"
                    class="form-control"
                    v-model="fee.transaction_fees_percentage"
                    required
                    id="transaction_fees_percentage"
                  />
                </div>
              </div>

              <!-- end col -->
            </div>
            <!-- end row -->

            <div class="text-end">
              <button :disabled="busy" type="submit" class="btn btn-success mt-2">
                <i class="mdi mdi-content-save"></i> Save
              </button>
            </div>
            <hr />
            <br />
          </form>
        </div>
        <!-- end card body -->
      </div>
      <!-- end card -->
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fee: {},
      errors: [],
      imageErrors: [],
      busy: false,
    };
  },
  methods: {
    updateFee() {
      this.busy = true;
      this.$Progress.start();

      axios
        .put("fee/update", this.fee)
        .then(({ data }) => {
          this.$toast.success("Fees were updated.", {
            position: "top-right",
          });
          this.busy = false;
          this.$Progress.finish();
        })
        .catch((error) => {
          this.errors = error.data.errors;
          this.$toast.error("Could not update fees.", {
            position: "top-right",
          });
          this.$Progress.finish();
          this.busy = false;
        });
    },
  },
  computed: {},
  mounted() {
    axios
      .get("fee")
      .then(({ data }) => {
        this.fee = data;
      })
      .catch((error) => console.error(error));
  },
};
</script>

<style></style>
