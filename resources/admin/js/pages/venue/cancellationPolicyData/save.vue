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
            <li class="breadcrumb-item">
              <router-link
                :to="{name: 'cancellationPolicyData.index'}"
                href="javascript: void(0);"
              >Cancellation Policy Data</router-link>
            </li>
            <li v-if="!editMode" class="breadcrumb-item">Add</li>
            <li v-else class="breadcrumb-item">Edit</li>
          </ol>
        </div>
        <h4 v-if="!editMode" class="page-title">Add Cancellation Policy Data</h4>
        <h4 v-else class="page-title">Update Cancellation Policy Data</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="handleSubmit()">
            <template v-for="(language, index) in languages" :key="index">
              <h5 class="mb-4 text-uppercase">Cancellation Policy Data in {{ language.name }}</h5>
              <div class="row">
                <div
                  :class="{
                              'col-md-3': index == 0,
                              'col-md-12': index != 0,
                            }"
                >
                  <div class="mb-3">
                    <label
                      :for="'cancellationPolicyData.title_' + language.code"
                      class="form-label"
                    >Title in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="cancellationPolicyData.title[language.code]"
                      :required="index == 0"
                      :id="'cancellationPolicyData.title_' + language.code"
                      placeholder="Type something ..."
                      autofocus
                    />
                  </div>
                </div>
                <div v-if="index == 0" class="col-md-3">
                  <div class="mb-3">
                    <label
                      for="cancellationPolicyData.deposit"
                      class="form-label"
                    >Deposit to secure booking (%)</label>
                    <input
                      type="number"
                      class="form-control"
                      v-model="cancellationPolicyData.deposit"
                      required
                      id="cancellationPolicyData.deposit"
                      placeholder="Type a number ..."
                    />
                  </div>
                </div>
                <div v-if="index == 0" class="col-md-2">
                  <div class="mb-3">
                    <label
                      for="cancellationPolicyData.refund_percentage"
                      class="form-label"
                    >Refund Percentage</label>
                    <input
                      type="number"
                      class="form-control"
                      v-model="cancellationPolicyData.refund_percentage"
                      required
                      id="cancellationPolicyData.refund_percentage"
                      placeholder="Type a number ..."
                    />
                  </div>
                </div>
                <div v-if="index == 0" class="col-md-2">
                  <div class="mb-3">
                    <label
                      for="cancellationPolicyData.days_before_arrival"
                      class="form-label"
                    >Days Before Arrival</label>
                    <input
                      type="number"
                      class="form-control"
                      v-model="cancellationPolicyData.days_before_arrival"
                      required
                      id="cancellationPolicyData.days_before_arrival"
                      placeholder="Type a number ..."
                    />
                  </div>
                </div>
                <div v-if="index == 0" class="col-md-2">
                  <div class="mb-3">
                    <label
                      for="cancellationPolicyData.remaining_balance_in_days"
                      class="form-label"
                    >Remaining Balance in Days</label>
                    <input
                      type="number"
                      class="form-control"
                      v-model="cancellationPolicyData.remaining_balance_in_days"
                      required
                      id="cancellationPolicyData.remaining_balance_in_days"
                      placeholder="Type a number ..."
                    />
                  </div>
                </div>
              </div>
              <!-- end row -->
              <hr />
              <br />
            </template>

            <div class="text-end">
              <button type="submit" :disabled="busy" class="btn btn-success mt-2">
                <i class="mdi mdi-content-save"></i> Save
              </button>
            </div>
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
      editMode: false,
      cancellationPolicyData: {
        title: {},
      },
      errors: [],
      busy: false,
    };
  },
  methods: {
    handleSubmit() {
      this.busy = true;
      this.$Progress.start();
      if (this.editMode) {
        this.updateCancellationPolicyData();
      } else {
        this.createCancellationPolicyData();
      }
    },
    createCancellationPolicyData() {
      axios
        .post("cancellationPolicyData", this.cancellationPolicyData)
        .then((response) => {
          this.$toast.success(response.data.message, {
            position: "top-right",
          });
          this.$Progress.finish();
          this.$router.push({
            name: "cancellationPolicyData.index",
          });
          this.busy = false;
        })
        .catch((error) => {
          this.$toast.error(error.data.message, {
            position: "top-right",
          });
          this.$Progress.fail();
          this.busy = false;
        });
    },
    updateCancellationPolicyData() {
      axios
        .put(
          `cancellationPolicyData/${this.cancellationPolicyData.id}`,
          this.cancellationPolicyData
        )
        .then((response) => {
          this.$toast.success(response.data.message, {
            position: "top-right",
          });
          this.$Progress.finish();
          this.$router.push({
            name: "cancellationPolicyData.index",
          });
          this.busy = false;
        })
        .catch((error) => {
          this.$toast.error(error.data.message, {
            position: "top-right",
          });
          this.$Progress.fail();
          this.busy = false;
        });
    },
  },
  computed: {
    languages() {
      return this.$store.getters.languages;
    },
  },
  mounted() {
    if (this.$route.params.cancellationPolicy) {
      this.editMode = true;
      axios
        .get(`cancellationPolicyData/${this.$route.params.cancellationPolicy}`)
        .then(({ data }) => {
          this.cancellationPolicyData = data;
        })
        .catch((error) => {
          reject(error);
        });
    }
  },
};
</script>

