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
              <router-link :to="{name: 'currency.index'}" href="javascript: void(0);">Currency</router-link>
            </li>
            <li v-if="!editMode" class="breadcrumb-item">Add</li>
            <li v-else class="breadcrumb-item">Edit</li>
          </ol>
        </div>
        <h4 v-if="!editMode" class="page-title">Add Currency</h4>
        <h4 v-else class="page-title">Update Currency</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="handleSubmit()" enctype="multipart/form-data">
            <template v-for="(language, index) in languages" :key="index">
              <h5 class="mb-4 text-uppercase">Currency Details in {{ language.name }}</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label
                      :for="'currency.name_' + language.code"
                      class="form-label"
                    >Name in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="currency.name[language.code]"
                      :id="'currency.name_' + language.code"
                      placeholder="Type something ..."
                      :required="index == 0"
                    />
                  </div>
                </div>
                <div class="col-md-3" v-if="index == 0">
                  <div class="mb-3">
                    <label for="currency.code" class="form-label">Currency Code</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="currency.code"
                      id="currency.code"
                      placeholder="Type something ..."
                      required
                    />
                  </div>
                </div>
                <div class="col-md-3" v-if="index == 0">
                  <div class="mb-3">
                    <label for="currency.symbol" class="form-label">Currency Symbol</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="currency.symbol"
                      id="currency.symbol"
                      placeholder="Type something ..."
                      required
                    />
                  </div>
                </div>
                <!-- end col -->
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
      currency: {
        name: {},
      },
      errors: [],
      busy: false,
    };
  },
  methods: {
    handleSubmit() {
      this.$Progress.start();
      this.busy = true;
      if (this.editMode) {
        this.updateCurrency();
      } else {
        this.createCurrency();
      }
      this.busy = false;
    },
    createCurrency() {
      axios
        .post("currency", this.currency)
        .then((response) => {
          this.$router.push({ name: "currency.index" });
          this.$toast.success("Currency was added, successfully.", {
            position: "top-right",
          });
          this.$Progress.finish();
          this.busy = false;
        })
        .catch((error) => {
          this.$toast.error("Couldn't add currency.", {
            position: "top-right",
          });
          this.$Progress.fail();
          this.busy = false;
        });
    },
    updateCurrency() {
      axios
        .put(`currency/${this.currency.id}`, this.currency)
        .then((response) => {
          this.$toast.success("Currency was updated, successfully.", {
            position: "top-right",
          });
          this.$router.push({ name: "currency.index" });
          this.$Progress.finish();
          this.busy = false;
        })
        .catch((error) => {
          this.$toast.error("Couldn't update currency.", {
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
    if (this.$route.params.currency) {
      this.editMode = true;
      axios
        .get(`currency/${this.$route.params.currency}`)
        .then(({ data }) => {
          this.currency = data;
        })
        .catch((error) => {
          reject(error);
        });
    }
  },
};
</script>

<style>
</style>
