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
        <h4 class="page-title">Manage Venues</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h4 class="header-title">All Venues</h4>
          <p class="text-muted font-14">List of all the venues for now.</p>
          <div class="row">
            <div class="col-md-12">
              <p class="text-info mb-0"><b>Filter by:</b></p>
              <small class="mt-0"><i class="fa fa-info"></i> Use the following options to filter the venues.</small>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="country" class="form-label">Country</label>
                <input type="text" v-model="filter.country" class="form-control" placeholder="search by Country"
                       id="country">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="property" class="form-label">Properties</label>
                <select v-model="filter.selected_type" id="property" class="form-control">
                  <option value="">All</option>
                  <template v-for="t in propertyTypes" :key="t.id">
                    <option :value="t.id">
                      <template v-for="x in t.title" :key="x">{{ x }},</template>
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
              <td class="table-user">{{ venue.name }}</td>
              <td>{{ venue.country }}</td>
              <td>{{ venue.city + ', ' + venue.state + ", " + venue.country }}</td>
              <td>{{ venueStatus(venue.status) }}</td>
              <td class="table-action text-center">
                <router-link v-if="venue.bookings.length" :to="{
                          name: 'venue.bookings',
                          params: { id: venue.id}
                }" tag="a" class="btn btn-sm btn-outline-secondary me-1">
                  Bookings <i class="badge badge-outline-info"> {{venue.bookings.length}}</i>
                </router-link> 
                <a
                  :href="`/venues/${venue.id}`"
                  target="blank"
                  class="btn btn-sm btn-outline-secondary me-1"
                >View</a>
                <button
                  v-if="venue.status == 1 || venue.status == 3"
                  type="button"
                  @click="toBeRejected = null; toBeApproved = venue.id; toBeUnApproved = null"
                  data-bs-toggle="modal"
                  data-bs-target="#confirm-action-modal"
                  class="btn btn-sm btn-outline-success me-1"
                >Approve
                </button>
                <button
                  v-if="venue.status == 1"
                  type="button"
                  @click="toBeRejected = venue.id; toBeApproved = null; toBeUnApproved = null"
                  data-bs-toggle="modal"
                  data-bs-target="#confirm-action-modal"
                  class="btn btn-sm btn-outline-danger me-1"
                >Reject
                </button>
                <button
                  v-if="venue.status == 2 || venue.status == 4"
                  type="button"
                  @click="toBeRejected = null; toBeApproved = null; toBeUnApproved = venue.id"
                  data-bs-toggle="modal"
                  data-bs-target="#confirm-action-modal"
                  class="btn btn-sm btn-outline-danger me-1"
                >UnApprove
                </button>
              </td>
            </tr>
            <tr v-if="venues.data.length == 0">
              <td colspan="5" align="center">No venues for this filter</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <Pagination :data="venues" :limit="3" :show-disabled="true" @pagination-change-page="getVenues">
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
                <i
                  v-if="toBeApproved"
                  class="uil uil-check align-middle text-success"
                  style="font-size: 8rem"
                ></i>
                <i
                  v-if="toBeRejected"
                  class="uil uil-keyhole-circle align-middle text-danger"
                  style="font-size: 6rem"
                ></i>
                <i
                  v-if="toBeUnApproved"
                  class="uil uil-keyhole-circle align-middle text-warning"
                  style="font-size: 6rem"
                ></i>
              </h1>
            </div>
            <div class="mt-4">
              <h4 style="text-transform: capitalize">{{ modalTitle }} Venue?</h4>
              <p class="text-muted text-lowercase">
                <span class="text-capitalize">Are</span>
                you absolutely sure you want to {{ modalTitle }} this venue listing?
              </p>
              <div class="mt-4">
                <a
                  v-if="toBeApproved"
                  href="javascript:void(0)"
                  @click="approveVenue()"
                  data-bs-dismiss="modal"
                  class="btn btn-outline-success"
                >Yes, Approve it it.</a>
                <a
                  v-if="toBeRejected"
                  href="javascript:void(0)"
                  @click="rejectVenue()"
                  data-bs-dismiss="modal"
                  class="btn btn-outline-danger"
                >Yes, Reject it.</a>
                <a
                  v-if="toBeUnApproved"
                  href="javascript:void(0)"
                  @click="unApproveVenue()"
                  data-bs-dismiss="modal"
                  class="btn btn-outline-warning"
                >Yes, unapprove it.</a>
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

      toBeApproved: null,
      toBeRejected: null,
      toBeUnApproved: null,
      venues: {data: []},
      propertyTypes: [],
      filter: {
        selected_type: null,
        country: '',
        price: ''
      }
    };
  },
  watch: {
    filter: {
      handler(n, o) {
        console.log('deep watch', n, o)
        this.getVenues()
      },
      deep: true,
    }
  },
  methods: {
    getVenues(page = 1) {
      axios.get("venues?page=" + page, {
        params: {
          'property_type_id': this.filter.selected_type,
          'country': this.filter.country,
          'price': this.filter.price
        }
      }).then((response) => {
        this.venues = response.data;
      });
    },
    approveVenue() {
      axios.post(`venue/${this.toBeApproved}/approve`).then(({data}) => {
        let index = this.venues.data.findIndex(
          (venue) => venue.id == this.toBeApproved
        );
        this.venues.data[index].status = 2;
        this.$toast.success(data.message, {
          position: "top-right",
        });
      });
    },
    rejectVenue() {
      axios.post(`venue/${this.toBeRejected}/reject`).then(({data}) => {
        let index = this.venues.data.findIndex(
          (venue) => venue.id == this.toBeRejected
        );
        this.venues.data[index].status = 3;
        this.$toast.success(data.message, {
          position: "top-right",
        });
      });
    },
    unApproveVenue() {
      axios.post(`venue/${this.toBeUnApproved}/unApprove`).then(({data}) => {
        let index = this.venues.data.findIndex(
          (venue) => venue.id == this.toBeUnApproved
        );
        this.venues.data[index].status = 1;
        this.$toast.success(data.message, {
          position: "top-right",
        });
      });
    },
  },
  computed: {
    modalTitle() {
      let title = "";
      this.toBeApproved
        ? (title = "approve")
        : this.toBeRejected
          ? (title = "reject")
          : this.toBeUnApproved
            ? (title = "unApprove")
            : "";
      return title;
    },
    venueStatus() {
      console.log(1);
      return (status) => {
        switch (status) {
          case 0:
            return "In Progress";
          case 1:
            return "Pending Approval";
          case 2:
            return "Approved";
          case 3:
            return "Rejected";
          default:
            break;
        }
      };
    },
  },
  created() {
    this.getVenues();
    axios.get("propertyType")
      .then(res => {
        this.propertyTypes = res.data;
      })
  },
};
</script>

<style>
</style>
