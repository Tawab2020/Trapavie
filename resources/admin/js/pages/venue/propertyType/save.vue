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
                :to="{name: 'propertyTypes.index'}"
                href="javascript: void(0);"
              >Property Type</router-link>
            </li>
            <li v-if="!editMode" class="breadcrumb-item">Add</li>
            <li v-else class="breadcrumb-item">Edit</li>
          </ol>
        </div>
        <h4 v-if="!editMode" class="page-title">Add Property Type</h4>
        <h4 v-else class="page-title">Update Property Type</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="handleSubmit()">
            <template v-for="(language, index) in languages" :key="index">
              <h5 class="mb-4 text-uppercase">Property Type in {{ language.name }}</h5>
              <div class="row">
                <div
                  :class="{
                              'col-md-4': index == 0,
                              'col-md-12': !index == 0,
                            }"
                >
                  <div class="mb-3">
                    <label
                      :for="'propertyType.title_' + language.code"
                      class="form-label"
                    >Property Type title in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="propertyType.title[language.code]"
                      :required="index == 0"
                      :id="'propertyType.title_' + language.code"
                      placeholder="Type something ..."
                      autofocus
                    />
                  </div>
                </div>
                <div v-if="index==0" class="col-8">
                  <label class="form-label">Select Accommodation Types</label>
                  <div>
                    <tag-select
                      v-if="propertyType.accommodation_types"
                      :options="accommodationTypes"
                      :savedItems="propertyType.accommodation_types"
                      @itemSelected="optionSelected"
                    ></tag-select>
                  </div>
                </div>
                <!-- <div v-if="index == 0" class="col-md-4">
                  <div class="mb-3">
                    <label
                      for="propertyType.accommodation_types"
                      class="form-label"
                    >Select Accommodation Options for this property type</label>
                  </div>
                  <select v-model="propertyType.accommodation_types" multiple class="form-control">
                    <option
                      v-for="accommodationType in accommodationTypes"
                      selected
                      :key="accommodationType.id"
                      :value="accommodationType.id"
                    >{{accommodationType.title['en']}}</option>
                  </select>
                </div>-->
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
import TagSelect from "./../../../components/TagSelect.vue";

export default {
  components: {
    TagSelect,
  },
  data() {
    return {
      editMode: false,
      propertyType: {
        title: {},
        accommodation_types: [],
      },
      errors: [],
      busy: false,
    };
  },
  methods: {
    optionSelected(items) {
      this.propertyType.accommodation_types = items;
    },
    handleSubmit() {
      this.busy = true;
      this.$Progress.start();
      if (this.editMode) {
        this.updatePropertyType();
      } else {
        this.createPropertyType();
      }
    },
    createPropertyType() {
      axios
        .post("propertyType", this.propertyType)
        .then((response) => {
          this.$store.commit("ADD_PROPERTY_TYPE", response.data);
          this.$toast.success("Property type was added, successfully.", {
            position: "top-right",
          });
          this.$Progress.finish();
          this.$router.push({
            name: "propertyTypes.index",
          });
          this.busy = false;
        })
        .catch((error) => {
          this.$toast.error(
            "Couldn't add Property Type. Fill the required data.",
            {
              position: "top-right",
            }
          );
          this.$Progress.fail();
          this.busy = false;
        });
    },
    updatePropertyType() {
      axios
        .put(`propertyType/${this.propertyType.id}`, this.propertyType)
        .then((response) => {
          this.$store.commit("UPDATE_PROPERTY_TYPE", response.data);
          this.$toast.success("Property type was updated, successfully.", {
            position: "top-right",
          });
          this.$Progress.finish();
          this.$router.push({
            name: "propertyTypes.index",
          });
          this.busy = false;
        })
        .catch((error) => {
          this.$toast.error("Couldn't update Property Type.", {
            position: "top-right",
          });
          this.$Progress.fail();
          this.busy = false;
        });
    },
  },
  computed: {
    accommodationTypes() {
      if (this.$store.getters.accommodationTypes[0]) {
        return this.$store.getters.accommodationTypes;
      }
      return [
        {
          title: {},
        },
      ];
    },
    languages() {
      return this.$store.getters.languages;
    },
  },
  mounted() {
    if (this.$route.params.propertyType) {
      this.editMode = true;
      axios
        .get(`propertyType/${this.$route.params.propertyType}`)
        .then(({ data }) => {
          this.propertyType = data;
        })
        .catch((error) => {
          reject(error);
        });
    }
  },
};
</script>
