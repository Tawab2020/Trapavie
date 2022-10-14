<template>
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right mx-3">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Tripavie</a>
            </li>
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Admin</a>
            </li>
            <li class="breadcrumb-item active">Venues</li>
          </ol>
        </div>
        <h4 class="page-title">Rejected Venues</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h4 class="header-title">Rejected Venues</h4>
          <p class="text-muted font-14">Manage published venues.</p>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="country" class="form-label">Country</label>
                <input type="text" v-model="filter.country" class="form-control" placeholder="search by Country" id="country">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="property" class="form-label">Properties</label>
                <select v-model="filter.selected_type" id="property" class="form-control">
                  <option value="">All</option>
                  <template v-for="t in propertyTypes" :key="t.id">
                    <option :value="t.id">
                      <template v-for="x in t.title" :key="x">{{x}},</template>
                    </option>
                  </template>
                </select>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="price" class="form-label">Price</label>
                <input type="text" v-model="filter.price" id="price" class="form-control" placeholder="Ex. 400,600">
              </div>
            </div>
          </div>
          <br>
          <table class="table table-bordered table-centered mb-0">
            <thead>
              <tr>
                <th>Venue Title</th>
                <th>Country</th>
                <th>Address</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="venue in venues.data" :key="venue.id">
                <td class="table-user">{{venue.name}}</td>
                <td>{{venue.country}}</td>
                <td>{{venue.city + ', ' + venue.state + ", " + venue.country}}</td>
                <td>
                  <i class="uil uil-ban text-danger" style="font-size:0.7rem;"></i> rejected
                </td>
                <td class="table-action text-center">
                  <a
                    :href="`/venues/${venue.id}`"
                    target="blank"
                    class="btn btn-sm btn-outline-secondary me-1"
                  >View</a>
                  <button
                    type="button"
                    @click="toBeApproved = venue.id;"
                    data-bs-toggle="modal"
                    data-bs-target="#confirm-action-modal"
                    class="btn btn-sm btn-outline-success me-1"
                  >Approve</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <Pagination
    :data="venues"
    :limit="3"
    :show-disabled="true"
    @pagination-change-page="getVenues"
  >
    <template #prev-nav>
      <span>&lt; Previous</span>
    </template>
    <template #active-nav>
      <span>Next &gt;</span>
    </template>
    <template #next-nav>
      <span>Next &gt;</span>
    </template>
  </Pagination>

  <!-- Confirm Venue Action Modal Start -->
  <div class="modal fade" id="confirm-action-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded shadow border-0">
        <div class="modal-body py-5">
          <div class="text-center">
            <div
              class="icon d-flex align-items-center justify-content-center bg-soft-danger rounded-circle mx-auto"
              style="height: 95px; width:95px;"
            >
              <h1 class="mb-0">
                <i class="uil uil-check align-middle text-success" style="font-size: 8rem"></i>
              </h1>
            </div>
            <div class="mt-4">
              <h4 style="text-transform: capitalize">Approve Venue?</h4>
              <p class="text-muted text-lowercase">
                <span class="text-capitalize">Are</span>
                you absolutely sure you want to Approve this venue listing?
              </p>
              <div class="mt-4">
                <a
                  href="javascript:void(0)"
                  @click="approveVenue()"
                  data-bs-dismiss="modal"
                  class="btn btn-outline-success"
                >Yes, Approve it it.</a>
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
  <!-- Confirm Venue Action Modal End -->
</template>

<script>
import LaravelVuePagination from "laravel-vue-pagination";

export default {
  components: {
    Pagination: LaravelVuePagination,
  },
  data() {
    return {
      venues: {data:[]},
      propertyTypes:[],
      filter: {
        selected_type: null,
        country: '',
        price:''
      },
      toBeApproved: null,
    };
  },
  methods: {
    getVenues(page = 1) {
      axios.get("venues?page=" + page, {
        params: {
          'property_type_id': this.filter.selected_type,
          'country': this.filter.country,
          'price':this.filter.price,
          'status':3
        }
      }).then((response) => {
        this.venues = response.data;
      });
    },
    approveVenue() {
      axios.post(`venue/${this.toBeApproved}/approve`).then(({ data }) => {
        let index = this.venues.data.findIndex(
          (venue) => venue.id == this.toBeApproved
        );
        this.venues.data.splice(index, 1);
        this.$toast.success(data.message, {
          position: "top-right",
        });
      });
    },
  },
  watch:{
    filter:{
      handler(n,o){
        console.log('deep watch',n,o)
        this.getVenues()
      },
      deep:true,
    }
  },
  computed: {},
  created() {
    this.getVenues();
    axios.get("propertyType")
      .then(res=>{
        this.propertyTypes = res.data;
      })
  },
};
</script>

<style>
</style>
