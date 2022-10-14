<template>
  <!-- Hero End -->
  <section class="section venues-list">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 text-center">
          <div class="section-title mb-4 pb-2">
            <h4 class="mb-4 float-start">{{venues.length}} Venues</h4>
            <router-link
              :to="{name: 'saveVenue.name_and_address'}"
              class="btn btn-outline-secondary float-end"
            >+ Add Venue</router-link>
          </div>
        </div>
        <!--end col-->
      </div>
      <!--end row-->

      <div class="row" v-if="venues.length">
        <div class="col-12">
          <table class="table table-hover mb-0 table-center">
            <thead>
              <tr class="font-semibold">
                <th scope="col" class="border-bottom">Listing</th>
                <th scope="col" class="d-none d-md-table-cell border-bottom">Location</th>
                <th scope="col" class="d-none d-md-table-cell border-bottom">Status</th>
                <th scope="col" class="d-none d-md-table-cell border-bottom">Last Modified</th>
                <th scope="col" class="border-bottom">Actions</th>
              </tr>
            </thead>
            <tbody :key="tableKey">
              <tr v-for="venue in venues" :key="venue.id">
                <td>
                  <img
                    :src="bannerImage(venue.images) ? `${$FilePath}/venue/${bannerImage(venue.images)}` : '/assets/user/frontend/images/default.jpeg'"
                    class="img-fluid rounded my-2"
                    style="height: 52px;"
                    width="80"
                  />
                  {{venue.name}}
                </td>
                <td
                  class="d-none d-md-table-cell"
                >{{venue.city + ', ' + venue.state + ", " + venue.country}}</td>
                <td class="d-none d-md-table-cell">{{venueStatus(venue.status)}}</td>
                <td
                  class="d-none d-md-table-cell"
                >{{new Date(venue.updated_at).getYear() == new Date().getYear() ? $DateTime.fromISO(venue.updated_at).toLocaleString($DateTime.DATE_MED) : $DateTime.fromISO(venue.updated_at).toLocaleString($DateTime.DATE_FULL)}}</td>
                <td>
                  <i @click="chosenVenueId = venue.id; openCalendarModal()">
                    <h4
                      class="uil uil-calendar-alt d-inline pointer"
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      @click="removeTooltip()"
                      title="Manage Dates"
                      alt
                    ></h4>
                  </i>
                  <router-link
                    :to="{name: 'saveVenue.name_and_address', params: {venue: venue.id}}"
                  >
                    <h4
                      class="uil uil-pen d-inline pointer"
                      style="color: #212529;"
                      @click="removeTooltip()"
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      title="Edit Venue"
                      alt
                    ></h4>
                  </router-link>
                  <i
                    @click="toBePaused = null; toBeActivated = venue.id; toBeDeleted = null; toBeDeletedBlockedDates = null; toBeDeletedSeasonPricing = null;"
                    data-bs-toggle="modal"
                    data-bs-target="#confirm-action-modal"
                  >
                    <h4
                      v-if="venue.is_paused == 1"
                      class="uil uil-play-circle d-inline pointer"
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      @click="removeTooltip()"
                      title="Activate Venue"
                      alt
                    ></h4>
                  </i>
                  <i
                    @click="toBePaused = venue.id; toBeActivated = null; toBeDeleted = null; toBeDeletedBlockedDates = null; toBeDeletedSeasonPricing = null;"
                    data-bs-toggle="modal"
                    data-bs-target="#confirm-action-modal"
                  >
                    <h4
                      v-if="venue.is_paused == 0"
                      class="uil uil-pause-circle d-inline pointer"
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      @click="removeTooltip()"
                      title="Pause Venue"
                      alt
                    ></h4>
                  </i>
                  <i
                    @click="toBePaused = null; toBeActivated = null; toBeDeleted = venue.id; toBeDeletedBlockedDates = null; toBeDeletedSeasonPricing = null;"
                    data-bs-toggle="modal"
                    data-bs-target="#confirm-action-modal"
                  >
                    <h4
                      class="uil uil-trash-alt d-inline pointer"
                      data-bs-toggle="tooltip"
                      @click="removeTooltip()"
                      data-bs-placement="top"
                      title="Delete Venue"
                      alt
                    ></h4>
                  </i>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!--end row-->
      <div class="row" v-else>
        <div class="col-12 text-center">
          <h5>You have no venues posted.</h5>
        </div>
      </div>
    </div>
    <!--end container-->
  </section>
  <!--end section-->

  <!-- Calendar Modal Start -->
  <div class="modal fade" id="calendar-modal" ref="calendarModal" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content rounded shadow border-0">
        <!-- <button type="button" class="btn-close m-2" data-bs-dismiss="modal" aria-label="Close"></button> -->
        <div class="modal-header border-bottom">
          <h5
            class="modal-title my-0 mx-auto"
            id="LoginForm-title"
            v-if="chosenVenue"
          >Manage Dates for {{chosenVenue.name}}</h5>
          <button
            type="button"
            class="btn-close p-0 m-0"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body py-2">
          <div class="p-4">
            <div class="row">
              <div class="col-lg-12">
                <ul
                  class="nav nav-pills nav-justified flex-column flex-sm-row rounded"
                  id="pills-tab"
                  role="tablist"
                >
                  <li class="nav-item">
                    <a
                      class="calendar-nav-item active"
                      id="pills-cloud-tab"
                      data-bs-toggle="pill"
                      href="#pills-cloud"
                      role="tab"
                      aria-controls="pills-cloud"
                      aria-selected="false"
                    >
                      <div class="text-center py-2">
                        <h6 class="mb-0">Block Dates</h6>
                      </div>
                    </a>
                    <!--end nav link-->
                  </li>
                  <!--end nav item-->

                  <li class="nav-item">
                    <a
                      class="calendar-nav-item"
                      id="pills-smart-tab"
                      data-bs-toggle="pill"
                      href="#pills-smart"
                      role="tab"
                      aria-controls="pills-smart"
                      aria-selected="false"
                    >
                      <div class="text-center py-2">
                        <h6 class="mb-0">High / Low Season Pricing</h6>
                      </div>
                    </a>
                    <!--end nav link-->
                  </li>
                  <!--end nav item-->
                </ul>
                <!--end nav pills-->
              </div>
              <!--end col-->
            </div>
            <!--end row-->

            <div class="row pt-2">
              <div class="col-12">
                <div class="tab-content" id="pills-tabContent">
                  <div
                    class="tab-pane fade show active"
                    id="pills-cloud"
                    role="tabpanel"
                    aria-labelledby="pills-cloud-tab"
                  >
                    <div class="row">
                      <div class="col-12">
                        <h5 class="text-center mb-3">Block Dates</h5>
                      </div>
                      <div class="col-12 col-lg-6">
                        <h6>Manage blocked dates for venueName.</h6>
                        <div class="blocked-dates custom-border">
                          <div
                            v-for="blockedDatesItem in blockedDatesList"
                            :key="blockedDatesItem.id"
                            class="blocked-date-item my-3"
                          >
                            <span>{{$DateTime.fromISO(blockedDatesItem.start).toLocaleString($DateTime.DATE_FULL)}} to {{$DateTime.fromISO(blockedDatesItem.end).toLocaleString($DateTime.DATE_FULL)}}</span>
                            <h4
                              @click="toBePaused = null; toBeActivated = null; toBeDeleted = null; toBeDeletedBlockedDates = blockedDatesItem.id; openConfirmModal()"
                              class="uil uil-trash float-end pointer"
                            ></h4>
                          </div>
                          <div v-if="blockedDatesList.length <= 0" class="blocked-date-item my-3">
                            <span>You have not blocked any dates for this venue.</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-lg-6">
                        <h6>Select the dates you want to block for venueName.</h6>
                        <div class="date-picker-wrappe d-flex justify-content-center">
                          <Date-Picker
                            style="width: 100%;"
                            :key="calendarKey"
                            :columns="$screens({ default: 1, desktop: 2 })"
                            v-model="blockedDates"
                            :min-date="new Date()"
                            :locale="activeLanguage"
                            :select-attribute="attribute"
                            :drag-attribute="attribute"
                            is-range
                            mode="date"
                            :model-config="modelConfig"
                            :disabled-dates="blockedDatesList"
                            timezone="UTC"
                          ></Date-Picker>
                        </div>

                        <div class="row mt-2">
                          <div class="col-12 d-flex justify-content-center align-items-center">
                            <u>
                              <b
                                @click="resetDates()"
                                class="pointer"
                                style="font-weight: 600"
                              >Clear dates</b>
                            </u>
                            <button
                              @click="blockDates()"
                              :disabled="blockedDates.end ?  !blockedDates.end.length : ''"
                              class="btn border-secondary bg-transparent ms-3"
                            >Block</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--end teb pane-->

                  <div
                    class="tab-pane fade"
                    id="pills-smart"
                    role="tabpanel"
                    aria-labelledby="pills-smart-tab"
                  >
                    <div class="row">
                      <div class="col-12">
                        <h5 class="text-center mb-3">High / Low Season Pricing</h5>
                      </div>
                      <div class="col-12 col-lg-6">
                        <h6>Manage high/low season pricing.</h6>
                        <div class="blocked-dates custom-border">
                          <div
                            v-for="seasonPricingItem in seasonPricings"
                            :key="seasonPricingItem.id"
                            class="blocked-date-item my-3"
                          >
                            <span>
                              <i
                                v-if="seasonPricingItem.percentage < 0"
                                class="uil uil-arrow-down text-danger"
                              ></i>
                              <i
                                v-if="seasonPricingItem.percentage > 0"
                                class="uil uil-arrow-up text-success"
                              ></i>
                              {{$DateTime.fromISO(seasonPricingItem).toLocaleString($DateTime.DATE_MED)}} to {{$DateTime.fromISO(seasonPricingItem.end).toLocaleString($DateTime.DATE_MED)}},
                              <h5
                                class="d-inline"
                              >{{calculateAdjustedPrice(seasonPricingItem.percentage)}} / night</h5>
                            </span>
                            <h4
                              @click="toBePaused = null; toBeActivated = null; toBeDeleted = null; toBeDeletedBlockedDates = null; toBeDeletedSeasonPricing = seasonPricingItem.id; openConfirmModal()"
                              class="uil uil-trash float-end pointer"
                            ></h4>
                          </div>
                          <div v-if="seasonPricings.length <= 0" class="blocked-date-item my-3">
                            <span>You have not added a season pricing yet.</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-lg-6">
                        <h6>Select the dates you want to adjust the pricing for.</h6>
                        <div class="date-picker-wrappe d-flex justify-content-center">
                          <Date-Picker
                            style="width: 100%;"
                            :key="calendarKey"
                            :columns="$screens({ default: 1, desktop: 2 })"
                            v-model="seasonPricingDates"
                            :from-page="{ month: 1, year: 2022 }"
                            min-date="2022-01-01"
                            max-date="2022-12-31"
                            :locale="activeLanguage"
                            :select-attribute="attribute"
                            :drag-attribute="attribute"
                            is-range
                            mode="date"
                            :model-config="modelConfig"
                            :disabled-dates="seasonPricings"
                            timezone="UTC"
                          >
                            <template #header-title="{ monthLabel }">{{monthLabel}}</template>
                          </Date-Picker>
                          <br />
                        </div>
                        <div class="row mt-2">
                          <div class="col-12 col-lg-6">
                            <div class="flex flex-col items-center w-full space-y-2">
                              <div>
                                <input
                                  type="range"
                                  v-model="seasonPricing.percentage"
                                  min="-99"
                                  max="99"
                                  step="1"
                                />
                              </div>
                              <div>
                                <p class="text-gray-500 text-b2">{{ seasonPricing.percentage }}%</p>
                              </div>
                            </div>
                          </div>
                          <div class="col-12 col-lg-6">
                            Price:
                            <h5
                              class="d-inline"
                            >{{calculateAdjustedPrice(seasonPricing.percentage)}}</h5>&nbsp;
                            <del
                              class="text-danger"
                            >{{chosenVenue ? chosenVenue.price_per_night : ''}}</del>
                          </div>
                        </div>
                        <div class="row mt-2">
                          <div class="col-12 d-flex justify-content-center align-items-center">
                            <u>
                              <b
                                @click="resetDates()"
                                class="pointer"
                                style="font-weight: 600"
                              >Clear dates</b>
                            </u>
                            <button
                              @click="addSeasonPricing()"
                              :disabled="seasonPricingDates.end ? !seasonPricingDates.end.length : ''"
                              class="btn border-secondary bg-transparent ms-3"
                            >Add</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--end teb pane-->
                </div>
                <!--end tab content-->
              </div>
              <!--end col-->
            </div>
            <!--end row-->
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Calendar Modal End -->

  <!-- Confirm Modal Start -->
  <div class="modal shadow-lg fade" ref="cam" id="confirm-action-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded shadow border-0">
        <div class="modal-body py-5">
          <div class="text-center">
            <div
              class="icon d-flex align-items-center justify-content-center rounded-circle mx-auto"
              :class="{'bg-soft-success': toBeActivated, 'bg-soft-warning': toBePaused, 'bg-soft-danger': toBeDeleted || toBeDeletedBlockedDates}"
              style="height: 95px; width:95px;"
            >
              <h1 class="mb-0">
                <i
                  class="uil align-middle"
                  :class="{'uil-play': toBeActivated, 'uil-pause': toBePaused, 'uil-trash':toBeDeleted  || toBeDeletedBlockedDates}"
                ></i>
              </h1>
            </div>

            <div class="mt-4">
              <h4>{{modalTitle}}</h4>
              <p class="text-muted">{{modalDescription}}</p>
              <div class="mt-4">
                <a
                  v-if="toBePaused"
                  href="javascript:void(0)"
                  @click="pauseVenue(toBePaused)"
                  data-bs-dismiss="modal"
                  class="btn btn-outline-warning"
                >Yes, Pause it.</a>
                <a
                  v-if="toBeActivated"
                  href="javascript:void(0)"
                  @click="activateVenue(toBeActivated)"
                  data-bs-dismiss="modal"
                  class="btn btn-outline-success"
                >Yes, Activate it.</a>
                <a
                  v-if="toBeDeleted"
                  href="javascript:void(0)"
                  @click="deleteVenue(toBeDeleted)"
                  data-bs-dismiss="modal"
                  class="btn btn-outline-danger"
                >Yes, Delete it.</a>
                <a
                  v-if="toBeDeletedBlockedDates"
                  href="javascript:void(0)"
                  @click="deleteBlockedDates(toBeDeletedBlockedDates)"
                  data-bs-dismiss="modal"
                  class="btn btn-outline-danger"
                >Yes, Delete it.</a>
                <a
                  v-if="toBeDeletedSeasonPricing"
                  href="javascript:void(0)"
                  @click="deleteSeasonPricing(toBeDeletedSeasonPricing)"
                  data-bs-dismiss="modal"
                  class="btn btn-outline-danger"
                >Yes, Delete it.</a>
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
  <!-- Confirm Modal End -->
</template>

<script>
import "v-calendar/dist/style.css";
import { SetupCalendar, Calendar, DatePicker } from "v-calendar";

export default {
  components: {
    DatePicker,
  },
  data() {
    return {
      attribute: {
        highlight: {
          start: {
            style: {
              backgroundColor: "#45AD95", // green
            },
            contentStyle: {
              color: "#ffffff", // color of the text
            },
          },
          base: {
            style: {
              backgroundColor: "#daefea", // light green
            },
          },
          end: {
            style: {
              backgroundColor: "#45AD95", // green
            },
            contentStyle: {
              color: "#ffffff", // color of the text
            },
          },
        },
      },
      modelConfig: {
        type: "string",
        mask: "YYYY-MM-DD", // Uses 'iso' if missing
      },
      blockedDatesList: [],
      blockedDates: {
        start: "",
        end: "",
      },
      seasonPricing: {
        percentage: 5,
      },
      seasonPricingDates: {
        start: "",
        end: "",
      },
      venues: [],
      chosenVenueId: null,
      tableKey: 0,
      toBePaused: null,
      toBeActivated: null,
      toBeDeleted: null,
      toBeDeletedBlockedDates: null,
      toBeDeletedSeasonPricing: null,
      seasonPricings: [],
      calendarKey: 0,
    };
  },
  methods: {
    removeTooltip() {
      let tooltip = document.getElementsByClassName("tooltip");
      tooltip[0].parentNode.removeChild(tooltip[0]);
    },
    openConfirmModal() {
      let modal = new bootstrap.Modal(this.$refs.cam);
      modal.show();

      // adjust the second modal backdrop's z-index
      var myModalEl = document.getElementById("confirm-action-modal");
      myModalEl.addEventListener("shown.bs.modal", function (event) {
        let backDrop = document.getElementsByClassName("modal-backdrop")[1]; // the second backDrop for the second modal
        backDrop.style.zIndex = 1064;
        myModalEl.style.zIndex = 1065;
      });
    },
    openCalendarModal() {
      const index = this.venues.findIndex(
        (venue) => venue.id == this.chosenVenueId
      );
      this.seasonPricing.percentage = 5;
      this.blockedDatesList = this.venues[index].blocked_dates;
      this.seasonPricings = this.venues[index].season_pricings;
      let modal = new bootstrap.Modal(this.$refs.calendarModal, {
        backdrop: "static",
        keyboard: false,
      });
      modal.show();
    },

    pauseVenue(venueId) {
      this.$Progress.start();
      axios
        .put(`hosting/venues/${venueId}/pauseVenue`)
        .then((response) => {
          const index = this.venues.findIndex((venue) => venue.id == venueId);
          this.venues[index].is_paused = 1;
          this.$Progress.finish();
          this.$toast.success("Listing was paused.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          this.$Progress.fail();
          this.$toast.error("An error occurred.", {
            position: "top-right",
          });
        });
    },

    activateVenue(venueId) {
      this.$Progress.start();
      axios
        .put(`hosting/venues/${venueId}/activateVenue`)
        .then((response) => {
          const index = this.venues.findIndex((venue) => venue.id == venueId);
          this.venues[index].is_paused = 0;
          this.$Progress.finish();
          this.$toast.success("Listing was activated.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          this.$Progress.fail();
          this.$toast.error("An error occurred.", {
            position: "top-right",
          });
        });
    },

    deleteVenue(venueId) {
      this.$Progress.start();
      axios
        .put(`hosting/venues/${venueId}/deleteVenue`)
        .then((response) => {
          const index = this.venues.findIndex((venue) => venue.id == venueId);
          this.venues.splice(index, 1);
          this.$Progress.finish();
          this.$toast.success("Listing was deleted.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          this.$Progress.fail();
          this.$toast.error("An error occurred.", {
            position: "top-right",
          });
        });
    },

    deleteBlockedDates(toBeDeletedBlockedDates) {
      this.$Progress.start();
      axios
        .put(`hosting/venues/deleteBlockedDates/${toBeDeletedBlockedDates}`)
        .then((response) => {
          const index = this.blockedDatesList.findIndex(
            (bd) => bd.id == toBeDeletedBlockedDates
          );
          this.blockedDatesList.splice(index, 1);
          this.$Progress.finish();
          this.$toast.success("Blocked dates were deleted.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          this.$Progress.fail();
          this.$toast.error("An error occurred.", {
            position: "top-right",
          });
        });
    },

    resetDates() {
      this.blockedDates.start = null;
      this.blockedDates.end = null;
      this.seasonPricingDates.start = null;
      this.seasonPricingDates.end = null;
      this.calendarKey++;
    },

    blockDates() {
      if (this.blockedDates.start > this.blockedDates.end) {
        let temp = this.blockedDates.start;
        this.blockedDates.start = this.blockedDates.end;
        this.blockedDates.end = temp;
      }

      this.blockedDates.venue_id = this.chosenVenueId;
      axios
        .post("hosting/venues/blockDates", this.blockedDates)
        .then((response) => {
          this.blockedDatesList.push(response.data);
          this.resetDates();
          this.$Progress.finish();
          this.$toast.success("Selected dates were blocked.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          this.$Progress.fail();
          this.$toast.error("An error occurred.", {
            position: "top-right",
          });
        });
    },

    addSeasonPricing() {
      if (this.seasonPricingDates.start > this.seasonPricingDates.end) {
        let temp = this.seasonPricingDates.start;
        this.seasonPricingDates.start = this.seasonPricingDates.end;
        this.seasonPricingDates.end = temp;
      }
      this.seasonPricing.venue_id = this.chosenVenueId;
      Object.assign(this.seasonPricing, this.seasonPricingDates);
      axios
        .post("hosting/venues/addSeasonPricing", this.seasonPricing)
        .then((response) => {
          this.resetDates();
          this.seasonPricing.percentage = 5;
          this.seasonPricings.push(response.data);
          this.$Progress.finish();
          this.$toast.success("Season Pricings were added.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          this.$Progress.fail();
          this.$toast.error("An error occurred.", {
            position: "top-right",
          });
        });
    },

    deleteSeasonPricing(toBeDeletedSeasonPricing) {
      this.$Progress.start();
      axios
        .put(`hosting/venues/deleteSeasonPricing/${toBeDeletedSeasonPricing}`)
        .then((response) => {
          const index = this.seasonPricings.findIndex(
            (bd) => bd.id == toBeDeletedSeasonPricing
          );
          this.seasonPricings.splice(index, 1);
          this.$Progress.finish();
          this.$toast.success("Season Pricings were deleted.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          this.$Progress.fail();
          this.$toast.error("An error occurred.", {
            position: "top-right",
          });
        });
    },

    calculateAdjustedPrice(percentage) {
      const index = this.venues.findIndex(
        (venue) => venue.id == this.chosenVenueId
      );
      if (index >= 0) {
        const price_per_night = this.venues[index].price_per_night;
        let ajdustedPrice =
          price_per_night + (percentage / 100) * price_per_night;
        ajdustedPrice = +ajdustedPrice.toFixed(2);

        return ajdustedPrice;
      }
      return 0;
    },
  },
  computed: {
    HeaderTitle() {
      return "header-title";
    },
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
    venueStatus() {
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
    activeLanguage() {
      return this.$store.getters.activeLocale;
    },
    modalTitle() {
      let title = "";
      this.toBePaused
        ? (title = "Pause Venue?")
        : this.toBeActivated
        ? (title = "Activate Venue?")
        : this.toBeDeleted
        ? (title = "Delete Venue?")
        : this.toBeDeletedBlockedDates
        ? (title = "Delete Bloced Dates?")
        : this.toBeDeletedSeasonPricing
        ? (title = "Delete Season Pricing?")
        : "";
      return title;
    },
    modalDescription() {
      let description = "";
      this.toBePaused
        ? (description =
            "Are you sure you want to pause this listing? Paused listings do not appear in searches and are not visible to retreat organizers.")
        : this.toBeActivated
        ? (description =
            "Are you sure you want to activate this listing? Active listings appear in searches and are visible to retreat organizers.")
        : this.toBeDeleted
        ? (description =
            "Are you sure you want to delete this listing? Deleting a listing will not affect the bookings that are made already.")
        : this.toBeDeletedBlockedDates
        ? (description =
            "Are you sure you want to delete the this blocked dates range?")
        : this.toBeDeletedSeasonPricing
        ? (description = "Are you sure you want to delete this season pricing?")
        : "";
      return description;
    },
    percentageWatcher() {
      return this.seasonPricing.percentage;
    },
    chosenVenue() {
      const index = this.venues.findIndex(
        (venue) => venue.id == this.chosenVenueId
      );
      return this.venues[index];
    },
  },
  watch: {
    percentageWatcher: function (newPercentage, oldPercentage) {
      if (newPercentage == 0) {
        if (oldPercentage > 0) {
          this.seasonPricing.percentage = -1;
        } else {
          this.seasonPricing.percentage = 1;
        }
      }
    },
  },
  mounted() {
    axios
      .get(`hosting/venues/listUserVenues`)
      .then(({ data }) => {
        this.venues = data;
      })
      .catch((error) => {
        console.log(error);
      });
  },
  updated() {
    var tooltipTriggerList = [].slice.call(
      document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  },
};
</script>

<style>
.venues-list td {
  vertical-align: middle;
}
.calendar-nav-item {
  color: #212529 !important;
}
.calendar-nav-item.active h6 {
  position: relative !important;
}
.calendar-nav-item.active h6::before {
  position: absolute;
  content: "";
  height: 2px;
  bottom: -5px;
  margin: 0 auto;
  left: 0;
  right: 0;
  width: 10%;
  border-radius: 2px;
  background: #212529;
  transition: 0.5s;
}
#calendar-modal .nav-pills {
  background: #f6f6f6 !important;
}
input[type="range"] {
  appearance: none;
  width: 100%;
  height: 10px;
  border-radius: 8px;
  outline: none;
  background: rgba(229, 231, 235);
}

input[type="range"]::-webkit-slider-thumb {
  appearance: none;
  height: 20px;
  width: 20px;
  cursor: pointer;
  border-radius: 9999px;
  outline: none;
  border: none;
  background: rgba(67, 173, 150);
}

input[type="range"]::-moz-range-thumb {
  appearance: none;
  height: 20px;
  width: 20px;
  cursor: pointer;
  border-radius: 9999px;
  outline: none;
  border: none;
  background: rgba(67, 173, 150);
}
</style>
