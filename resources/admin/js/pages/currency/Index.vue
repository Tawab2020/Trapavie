<template>
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <router-link :to="{name: 'currency.save' }">
            <button type="button" class="btn-add btn btn-outline-success">
              <i class="dripicons-plus"></i> Add Currency
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
            <li class="breadcrumb-item active">Currencies</li>
          </ol>
        </div>
        <h4 class="page-title">Manage Currencies</h4>
      </div>
    </div>
  </div>
  <div class="row" v-if="currencies.length > 0">
    <div v-for="(currency, index) in currencies" :key="index" class="col-xl-4 col-lg-6 col-md-4">
      <div class="card">
        <div class="card-body">
          <h3 class="float-start me-2 currency-symbol">{{currency.symbol}}</h3>
          <div>
            <h4 class="mt-1 mb-1">{{currency.name['en']}}</h4>
            <p class="font-13">{{currency.code}}</p>
            <router-link :to="{name: 'currency.save', params: {currency: currency.id}}">
              <button type="button" class="btn btn-sm btn-outline-primary float-end">Edit</button>
            </router-link>
            <button
              class="btn btn-sm btn-outline-danger float-end mx-2"
              @click="toBeDeletedId = currency.id"
              data-bs-toggle="modal"
              data-bs-target="#delete-confirmation-modal"
            >Delete</button>
          </div>
          <!-- end div-->
        </div>
        <!-- end card-body-->
      </div>
    </div>
  </div>
  <div class="row" v-else>
    <div class="card">
      <div class="card-body">
        <h4 class="mt-1 mb-1">
          No Currencies added
          <router-link to="currency/save">add here</router-link>
        </h4>
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
          <h4 class="modal-title" id="delete-confirmation-modalLabel">Delete Currency?</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 class="mt-0">Are you absolutely sure?</h5>
          <p>Are you sure you want to delete this currency. this action is irreversible in any ways.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button
            type="button"
            class="btn btn-danger"
            @click="deleteCurrency(toBeDeletedId)"
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
      currencies: [],
      toBeDeletedId: null,
    };
  },
  methods: {
    deleteCurrency(currencyId) {
      axios
        .delete(`currency/${currencyId}`)
        .then((response) => {
          const index = this.currencies.findIndex(
            (currency) => currency.id == currencyId
          );
          this.currencies.splice(index, 1);
          this.$toast.success(response.data.message, {
            position: "top-right",
          });
        })
        .catch((error) => {
          this.$toast.error(error.data.message, {
            position: "top-right",
          });
        });
    },
  },
  mounted() {
    axios
      .get("currency")
      .then((response) => {
        this.currencies = response.data;
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>

<style>
.currency-symbol {
  padding: 15px;
  border: 1px solid gray;
  border-radius: 30px;
}
</style>
