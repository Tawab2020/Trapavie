<template>
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <router-link :to="{name: 'language.save' }">
            <button type="button" class="btn-add btn btn-outline-success">
              <i class="dripicons-plus"></i> Add Language
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
            <li class="breadcrumb-item active">Language</li>
          </ol>
        </div>
        <h4 class="page-title">Manage Languages</h4>
      </div>
    </div>
  </div>
  <div class="row" v-if="languages.length > 0">
    <div v-for="(language, index) in languages" :key="index" class="col-xl-4 col-lg-6 col-md-4">
      <div class="card">
        <div class="card-body">
          <span class="float-start m-2 me-4">
            <img
              :src="`${$FilePath}/languages/${language.icon}`"
              style="height: 100px;"
              alt
              class="rounded-circle img-thumbnail"
            />
          </span>
          <div>
            <h4 class="mt-1 mb-1">{{language.name}}</h4>
            <p class="font-13">{{language.code}}</p>
            <router-link :to="{name: 'language.translate', params: {language: language.code}}">
              <button
                type="button"
                class="btn btn-sm btn-outline-secondary mx-2 float-end"
              >{{language.code == "en" ? 'Update text' : 'Translate'}}</button>
            </router-link>
            <router-link :to="{name: 'language.save', params: {language: language.id}}">
              <button type="button" class="btn btn-sm btn-outline-primary float-end">Edit</button>
            </router-link>
            <button
              v-if="language.is_active == 1 && language.code != 'en'"
              class="btn btn-sm btn-outline-danger float-end mx-2"
              @click="toBePassedId = language.id; action = 'Deactivate'"
              data-bs-toggle="modal"
              data-bs-target="#delete-confirmation-modal"
            >Deactivate</button>
            <button
              v-else-if="language.code != 'en'"
              class="btn btn-sm btn-outline-success float-end mx-2"
              @click="toBePassedId = language.id; action = 'Activate'"
              data-bs-toggle="modal"
              data-bs-target="#delete-confirmation-modal"
            >Activate</button>
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
          No Languages added
          <router-link to="language/save">add here</router-link>
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
          <h4 class="modal-title" id="delete-confirmation-modalLabel">{{action}} Language?</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 class="mt-0">Are you absolutely sure?</h5>
          <p>{{promptMessage}}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button
            v-if="action == 'Activate'"
            type="button"
            class="btn btn-danger"
            @click="activateLanguage(toBePassedId)"
            data-bs-dismiss="modal"
          >Continue</button>
          <button
            v-else
            type="button"
            class="btn btn-danger"
            @click="deactivateLanguage(toBePassedId)"
            data-bs-dismiss="modal"
          >Continue</button>
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
      toBePassedId: null,
      action: "Deactivate",
    };
  },
  methods: {
    deactivateLanguage(languageId) {
      axios
        .put(`language/${languageId}/deactivate`)
        .then((response) => {
          const index = this.languages.findIndex(
            (language) => language.id == languageId
          );
          this.languages[index].is_active = 0;
          this.$toast.warning("Language was deactivated successfully.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
    activateLanguage(languageId) {
      axios
        .put(`language/${languageId}/activate`)
        .then((response) => {
          const index = this.languages.findIndex(
            (language) => language.id == languageId
          );
          this.languages[index].is_active = 1;
          this.$toast.success("Language was activated successfully.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  computed: {
    promptMessage() {
      return this.action == "Deactivate"
        ? "Are you sure you want to deactivate this language? Doing so will hide this language from accross all pages of Tripavie."
        : "Are you sure you want to activate this language? Doing so will show this language accross all pages of Tripavie.";
    },
    languages() {
      return this.$store.getters.languages;
    },
  },
};
</script>

<style>
</style>
