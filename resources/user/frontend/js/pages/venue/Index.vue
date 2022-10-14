<template>
  <!-- Rooms Start -->
  <section class="section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-8 text-center">
          <div class="section-title">
            <div id="search" class="mb-4">
              <div class="row gx-5 border top-search">
                <div class="col d-flex align-items-center">
                  <v-select
                    autocomplete="off"
                    v-model="search.country"
                    @input="$emit('input', $event)"
                    :options="countries"
                    @option:selected="countrySelected()"
                    label="country"
                    :placeholder="$t('country_ph')"
                    style="width: 100%"
                  ></v-select>
                </div>
                <div class="col d-flex align-items-center">
                  <input type="date" class="form-control border-0" v-model="search.start_date" />
                </div>

                <div class="col d-flex align-items-center">
                  <input type="date" class="form-control border-0" v-model="search.end_date" />
                </div>
                <div class="col d-flex align-items-center">
                  <input
                    type="number"
                    class="form-control border-0"
                    v-model="search.guests"
                    :placeholder="$t('guests_ph')"
                  />
                </div>
                <div class="col d-flex align-items-center">
                  <button
                    class="btn search-btn btn-warning w-100"
                    @click="searchVenues()"
                  >{{$t('search_btn')}}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--end col-->
      </div>
      <!--end row-->

      <h4 class="title mb-2 text-start">{{$t('browse_venues_title')}}</h4>
      <p class="text-muted text-start mb-0">{{$t('browse_venues_subtitle')}}</p>

      <div class="row mb-5">
        <div id="loading" v-if="loading">
          <div class="col-4 mt-4 pt-2" v-for="i in 3" :key="i">
            <content-loader
              viewBox="0 0 456 448"
              :speed="3"
              primaryColor="#f3f3f3"
              secondaryColor="#ecebeb"
            >
              <rect x="0" y="0" rx="22" ry="22" width="456" height="256" />
              <rect x="0" y="311" rx="10" ry="10" width="362" height="24" />
              <rect x="0" y="350" rx="10" ry="10" width="354" height="20" />
              <rect x="0" y="401" rx="10" ry="10" width="92" height="18" />
              <rect x="167" y="401" rx="10" ry="10" width="121" height="18" />
              <rect x="393" y="401" rx="10" ry="10" width="62" height="18" />
              <rect x="0" y="387" rx="0" ry="0" width="456" height="2" />
            </content-loader>
          </div>
        </div>
        <div v-if="venues.length == 0" class="text-center text-dark">
          <h5>
            {{$t('no_venues_to_show')}}
            <i class="uil uil-meh"></i>
          </h5>
        </div>

        <div
          v-else
          v-for="venue in venues"
          :key="venue.id"
          class="col-lg-3 col-md-6 col-12 mt-4 pt-2"
        >
          <div class="card blog rounded border-0 shadow overflow-hidden">
            <div class="position-relative">
              <img
                :src="bannerImage(venue.images) ? `${$FilePath}/venue/${bannerImage(venue.images)}` : '/assets/user/frontend/images/default.jpeg'"
                class="card-img-top"
                alt="..."
              />
              <div class="overlay bg-dark"></div>
              <div class="course-fee bg-white text-center shadow-lg rounded-circle">
                <h6 class="text-primary fw-bold fee pointer">
                  <i class="uil uil-heart"></i>
                </h6>
              </div>
            </div>
            <div class="position-relative">
              <div class="shape overflow-hidden text-white" style="bottom: -4px;">
                <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z"
                    fill="currentColor"
                  />
                </svg>
              </div>
            </div>
            <div class="card-body content">
              <h6
                class="font-semibold"
              >{{getCurrencySymbol(venue.currency_id)}}{{venue.price_per_night}}/night</h6>
              <router-link
                :to="{name: 'venue.show', params: {venue: venue.id}}"
                class="title text-dark h5"
              >{{venue.name}}</router-link>
              <p class="text-muted mt-2">{{venue.city + ", " + venue.state + ", " + venue.country}}</p>
              <ul class="list-unstyled d-flex justify-content-between border-top mt-3 pt-3 mb-0">
                <li class="text-muted small">
                  <i class="uil uil-bed"></i>
                  {{$t('x_beds', {count: numberOfBeds(venue.accommodations)})}}
                </li>
                <li class="text-muted small ms-3">
                  <i class="uil uil-bed-double"></i>
                  {{$t('x_rooms', {count: numberOfRooms(venue.accommodations)})}}
                </li>
                <li class="text-muted small ms-3">
                  <i class="uil uil-star text-warning"></i> 5 Stars
                </li>
              </ul>
            </div>
          </div>
          <!--end card / course-blog-->
        </div>
        <!--end col-->
      </div>
      <!--end row-->
    </div>
    <!--end container-->
  </section>
  <!--end section-->
  <!-- Rooms End -->
</template>

<script>
import vSelect from "vue-select";
import { ContentLoader } from "vue-content-loader";

export default {
  components: {
    vSelect,
    ContentLoader,
  },
  data() {
    return {
      venues: [],
      countries: [],
      search: {},
      loading: false,
    };
  },
  methods: {
    countrySelected() {},
    searchVenues() {
      this.loading = true;
      axios
        .post("venues/search", this.search)
        .then((response) => {
          this.venues = response.data;
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getCurrencySymbol(currencyId) {
      const index = this.currencies.findIndex(
        (curreny) => curreny.id == currencyId
      );
      return this.currencies[index].symbol;
    },
    numberOfBeds(accommodations) {
      let beds = 0;
      accommodations.forEach((accommodation) => {
        accommodation.bedrooms.forEach((bedroom) => {
          beds += bedroom.bedroom_arrangements.length;
        });
      });
      return beds;
    },
    numberOfRooms(accommodations) {
      let rooms = 0;
      accommodations.forEach((accommodation) => {
        rooms += accommodation.bedrooms.length;
      });
      return rooms;
    },
  },
  computed: {
    user() {
      return this.$store.getters.user.user;
    },
    bannerImage() {
      return (images) => {
        return images[images.findIndex((img) => img.type == 1)]
          ? images[images.findIndex((img) => img.type == 1)].image
          : "";
      };
    },
    currencies() {
      return this.$store.getters.currencies;
    },
  },
  watch: {},
  mounted() {
    axios
      .get(`venues/homepageVenues`)
      .then(({ data }) => {
        this.venues = data;
      })
      .catch((error) => {
        console.log(error);
      });
    axios
      .get("getCountries")
      .then(({ data }) => {
        this.countries = data;
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>

<style scoped lang="scss">
input[type="date"] {
  color: #6c757d;
}

.top-search {
  border-radius: 60px;
  padding: 9px;
  .col {
    border-right: 1px solid #e9ecef;
  }
  .col:last-child,
  .col:nth-child(4) {
    border-right: none;
  }
  .col:last-child {
    padding: 0 !important;
  }
}

.top-search .search-btn {
  border-radius: 60px;
  height: 48px;
}
</style>
