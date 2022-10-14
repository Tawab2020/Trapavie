<template>
  <!-- Hero Start -->
  <section
    class="bg-profile d-table w-100 bg-primary"
    style="background: url('/assets/user/frontend/images/account/bg.png') center center;"
  >
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="card public-profile border-0 rounded shadow" style="z-index: 1;">
            <div class="card-body" style="padding-bottom: 180px">
              <div class="row justify-content-center">
                <!--end col-->
                <div class="col-lg-10 col-md-9">
                  <div class="row align-items-end">
                    <!-- <div class="col-md-7 text-md-start text-center mt-4 mt-sm-0"> -->
                    <!-- <h3 class="title mb-0"rted h6 me-2">Tripavie User</small> -->
                    <div>
                      <div class="wizard position-relative d-flex align-items-center">
                        <div class="connecting-line">
                          <div class="inner" :style="'width: ' + width + 'px'"></div>
                        </div>
                        <ul class="items d-flex align-items-start justify-content-between flex-1">
                          <li
                            class="d-flex flex-column align-items-center justify-content-center"
                            style="height: 100px; width: 100px"
                            v-for="step in steps"
                            :class="step.id == steps.length ? 'align-items-end' : ''"
                            :key="step.id"
                          >
                            <svg
                              class="svg"
                              xmlns="http://www.w3.org/2000/svg"
                              viewBox="-1 -1 34 34"
                            >
                              <circle cx="16" cy="16" r="15.9155" class="progress-bar__background" />
                              <circle
                                cx="16"
                                cy="16"
                                r="15.9155"
                                :style="'stroke-dashoffset:' + step.roundSize"
                                class="progress-bar__progress js-progress-bar"
                              />
                            </svg>
                            <div
                              class="item d-flex align-items-center justify-content-center"
                              :class="[step.active && 'active', step.completed && 'completed']"
                            >
                              <div>
                                <span class="d-block">{{ step.title }}</span>
                                <ul class="item-details">
                                  <li v-for="(subStep, i) in step.subSteps" :key="i">
                                    <!-- Because first step may not have the param -->
                                    <template v-if="subStep.link == 'name_and_address'">
                                      <!-- <router-link
                                        v-if="venue.id"
                                        :to="`/saveVenue/${venue.id}/` + subStep.link"
                                      >{{subStep.name}}</router-link>-->
                                      <div
                                        v-if="venue.id"
                                        @click="navigateTo(`/saveVenue/${venue.id}/` + subStep.link, subStep)"
                                        :class="subStep.parent == activeSubstep.parent && subStep.id == activeSubstep.id ? 'text-warning pointer' : 'pointer'"
                                      >{{subStep.name}}</div>
                                      <!-- <router-link
                                        v-else
                                        :to="`/saveVenue/` + subStep.link"
                                      >{{subStep.name}}</router-link>-->
                                      <div
                                        :class="subStep.parent == activeSubstep.parent && subStep.id == activeSubstep.id ? 'text-warning pointer' : 'pointer'"
                                        @click="navigateTo(`/saveVenue/` + subStep.link, subStep)"
                                        v-else
                                      >{{subStep.name}}</div>
                                    </template>
                                    <!-- For all other steps -->
                                    <template v-else>
                                      <!-- <router-link
                                        v-if="stepAvailable(subStep.link)"
                                        :to="`/saveVenue/${venue.id}/` + subStep.link"
                                      >{{subStep.name}}</router-link>-->
                                      <div
                                        :class="subStep.parent == activeSubstep.parent && subStep.id == activeSubstep.id ? 'text-warning pointer' : 'pointer'"
                                        v-if="stepAvailable(subStep.link)"
                                        @click="navigateTo(`/saveVenue/${venue.id}/` + subStep.link, subStep)"
                                      >{{subStep.name}}</div>
                                      <a
                                        href="javascript:void(0)"
                                        class="text-muted"
                                        v-else
                                      >{{subStep.name}}</a>
                                    </template>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <!-- <div>
                      <button class="btn btn-primary mt-5" @click="nextLevel()">Next</button>
                    </div>-->
                    <!-- Above will say venue host, or retreat organizer -->
                    <!-- </div> -->
                    <!--end col-->
                  </div>
                  <!--end row-->
                </div>
                <!--end col-->
              </div>
              <!--end row-->
            </div>
          </div>
        </div>
        <!--end col-->
      </div>
      <!--end row-->
    </div>
    <!--ed container-->
  </section>
  <!--end section-->

  <!-- Hero End -->
  <section class="section pb-0">
    <div class="container">
      <router-view :key="componentKey"></router-view>
    </div>
    <!--end container-->
  </section>
</template>

<script>
export default {
  data() {
    return {
      width: 214 / 2,
      roundSize: 100,
      activeSubstep: {
        id: null,
        parent: null,
      },
      componentKey: 1,
      steps: [
        {
          id: 1,
          name: "Step 1",
          link: "saveVenue/",
          title: "Information",
          active: true,
          completed: false,
          // the step is completed 30%
          roundSize: 100,
          subSteps: [
            {
              id: 1,
              total: 2,
              name: "Property name and address",
              link: "name_and_address",
              parent: 1,
            },
            {
              id: 2,
              total: 2,
              name: "Address and arrival info",
              link: "arrival",
              available: this.nameAvailable,
              parent: 1,
            },
          ],
        },
        {
          id: 2,
          name: "Step 2",
          link: "contact_details",
          title: "Description",
          active: false,
          completed: false,
          roundSize: 100,
          subSteps: [
            {
              id: 1,
              total: 5,
              name: "Description and features",
              link: "description_and_features",
              parent: 2,
            },
            {
              id: 2,
              total: 5,
              name: "Amenities and facilities",
              link: "amenities_and_facilities",
              parent: 2,
            },
            {
              id: 3,
              total: 5,
              name: "Yoga and workshop space",
              link: "yoga_and_workshop_space",
              parent: 2,
            },
            {
              id: 4,
              total: 5,
              name: "Surroundings and things to do",
              link: "surroundings_and_things_to_do",
              parent: 2,
            },
            {
              id: 5,
              total: 5,
              name: "Kitchen and food",
              link: "kitchen_and_food",
              parent: 2,
            },
          ],
        },
        {
          id: 3,
          name: "Step 3",
          link: "third_step",
          title: "Accommodation",
          active: false,
          completed: false,
          roundSize: 100,
          subSteps: [
            {
              id: 1,
              total: 1,
              name: "Add accommodation",
              link: "accommodations",
              parent: 3,
            },
          ],
        },
        {
          id: 4,
          name: "Step 4",
          link: "link_to_social_media",
          title: "Pricing",
          active: false,
          completed: false,
          roundSize: 100,
          subSteps: [
            {
              id: 1,
              total: 3,
              name: "Venue price",
              link: "venue_pricing",
              parent: 4,
            },
            {
              id: 2,
              total: 3,
              name: "Meals price",
              link: "meals_pricing",
              parent: 4,
            },
            {
              id: 3,
              total: 3,
              name: "Retreat organizer",
              link: "retreat_organizer",
              parent: 4,
            },
          ],
        },
        {
          id: 5,
          name: "Step 5",
          link: "link_to_zoom",
          title: "Policies",
          active: false,
          completed: false,
          roundSize: 100,
          subSteps: [
            {
              id: 1,
              total: 2,
              name: "Check in & check out",
              link: "check_in_check_out",
              parent: 5,
            },
            {
              id: 2,
              total: 2,
              name: "Cancellation policy",
              link: "cancellation_policy",
              parent: 5,
            },
          ],
        },
      ],
    };
  },
  computed: {
    user() {
      return this.$store.getters.user.user;
    },
    venue() {
      return this.$store.getters.venueUnderSave;
    },
    stepAvailable() {
      return (stepLink) => {
        switch (stepLink) {
          case "arrival":
            return this.venue.step >= 1;

          case "description_and_features":
            return this.venue.step >= 2;

          case "amenities_and_facilities":
            return this.venue.step >= 3;

          case "yoga_and_workshop_space":
            return this.venue.step >= 4;

          case "surroundings_and_things_to_do":
            return this.venue.step >= 5;

          case "kitchen_and_food":
            return this.venue.step >= 6;

          case "accommodations":
            return this.venue.step >= 7;

          case "venue_pricing":
            return this.venue.step >= 8;

          case "meals_pricing":
            return this.venue.step >= 9;

          case "retreat_organizer":
            return this.venue.step >= 10;

          case "check_in_check_out":
            return this.venue.step >= 11;

          case "cancellation_policy":
            return this.venue.step >= 12;

          default:
            break;
        }
      };
    },
    venueHasGeneralImage() {
      return this.venue.images
        ? this.venue.images.some((img) => img.type == 2)
          ? true
          : false
        : false;
    },
    venueHasSurroundingsImage() {
      return this.venue.images
        ? this.venue.images.some((img) => img.type == 7)
          ? true
          : false
        : false;
    },
    venuehasBanner() {
      return this.venue.images
        ? this.venue.images.some((img) => img.type == 1)
          ? true
          : false
        : false;
    },
    lastStepAvailable() {},
    nameAvailable() {
      return this.venue ? (this.venue.name ? true : false) : false;
    },
    currentStepLink() {
      return this.$route.path.substring(this.$route.path.lastIndexOf("/") + 1);
    },
    venueParam() {
      return this.$route.params.venue;
    },
  },
  methods: {
    // calculate inner width of the progress bar
    calculateWidth() {
      // TODO - I will fix this tomorrow
    },

    // calculateSVG(step, total) {
    //   return this.roundSize = 100 - (step * 100 / total);
    // },

    nextLevel() {
      const currentStep = this.steps.find((step) => step.active);
      currentStep.completed = true;

      const nextStep = this.steps.find(
        (step) => step.id === currentStep.id + 1
      );
      if (nextStep) {
        this.$router.push(nextStep.link);
        this.steps.forEach((step) => {
          step.active = false;
        });
        nextStep.active = true;

        this.calculateWidth();
      }
    },

    // navigate to route
    navigateTo(route, subStep) {
      this.activeSubstep = subStep;
      let activeStep = this.steps.find((step) => step.id == subStep.parent);
      for (const step of this.steps) {
        if (step.id > activeStep.id) {
          step.roundSize = 100;
        } else {
          step.roundSize = 0;
        }
      }
      activeStep.roundSize = 100 - (subStep.id * 100) / subStep.total;

      this.$router.push(route);
    },
    navigateToLastStep() {
      for (let i = this.steps.length - 1; i >= 0; i--) {
        for (let j = this.steps[i].subSteps.length - 1; j >= 0; j--) {
          if (this.stepAvailable(this.steps[i].subSteps[j].link)) {
            this.navigateTo(
              this.steps[i].subSteps[j].link,
              this.steps[i].subSteps[j]
            );
            console.log(this.steps[i].subSteps[j]);
          }
        }
      }
    },
  },
  watch: {
    nameAvailable: function (value) {
      this.steps[0].subSteps[1].available = value;
    },
    currentStepLink: {
      handler: function (currentStepLink) {
        this.steps.forEach((step) => {
          step.subSteps.forEach((subStep) => {
            if (subStep.link == currentStepLink) {
              this.navigateTo(subStep.link, subStep);
              this.activeSubstep = subStep;
            }
          });
        });
      },
      immediate: true,
    },
    venueParam: {
      handler: function (venueId) {
        if (!venueId) {
          console.log("here");
          this.$store.commit("CHANGE_VENUE_UNDER_SAVE", {});
          this.componentKey = 2;
        }
      },
    },
  },
  beforeCreate() {
    if (this.$route.params.venue) {
      axios
        .get(`venue/${this.$route.params.venue}`)
        .then(({ data }) => {
          this.$store.commit("UPDATE_VENUE_UNDER_SAVE", data);
        })
        .catch((error) => {
          console.log(error);
        });
    } else {
      console.log("here");
      this.$store.commit("CHANGE_VENUE_UNDER_SAVE", {});
      this.componentKey = 2;
    }
  },
  mounted() {
    // TODO: check if the url is not permited and then go to first step
    console.log(this.$route.params.venue);
    this.navigateTo(this.activeSubstep.link, this.activeSubstep);
  },
};
</script>

<style lang="scss">
.wizard {
  ul.items {
    padding: 0;
    margin: 0;
    list-style: none;
    .item {
      border-radius: 50%;
      width: 48px;
      height: 48px;
      font-size: 14px;
      z-index: 9;
      transition: all 0.2s ease-in-out;
      position: relative;
      span {
        font-size: 0.7rem;
        font-weight: bold;
      }
      a {
        color: #292929;
      }
      &.active {
        // border: 2px solid rgb(0, 119, 255);
        a {
          color: #292929; //change this
        }
      }
      &.completed {
        background: rgb(0, 119, 255);
        border-color: rgb(0, 119, 255);
        a {
          color: white;
        }
      }
    }
  }
  .connecting-line {
    height: 3px;
    background-color: #b4b4b4;
    width: calc(100% - 32px);
    position: absolute;
    left: 16px;
    top: 48px;
    overflow: hidden;
    .inner {
      height: 2px;
      width: 0;
      transition: width 0.3s ease-in-out;
    }
  }
}

.item-details {
  position: absolute;
  left: -20px;
  list-style: none;
  padding: 0;
  min-width: 200px;
  top: 95px;
}

$progress-bar-stroke-width: 1.8;
$progress-bar-size: 100px;

svg.svg {
  height: $progress-bar-size;
  transform: rotate(-90deg);
  width: $progress-bar-size;
  position: absolute;
  background-color: white;
  border-radius: 100%;
}

.progress-bar__background {
  fill: none;
  stroke: #dcdcdc;
  stroke-width: $progress-bar-stroke-width;
}

.progress-bar__progress {
  fill: none;
  stroke: #dcdcdc;
  stroke: #ff7b00;
  stroke-dasharray: 100 100;
  // stroke-dashoffset: 100;
  stroke-linecap: round;
  stroke-width: $progress-bar-stroke-width;
  transition: stroke-dashoffset 1s ease-in-out;
}
</style>
