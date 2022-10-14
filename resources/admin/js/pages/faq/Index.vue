<template>
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <router-link :to="{name: 'faqCat.save' }">
            <button type="button" class="btn-add btn btn-outline-success">
              <i class="dripicons-plus"></i> Add FAQ Category
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
            <li class="breadcrumb-item active">FAQ</li>
          </ol>
        </div>
        <h4 class="page-title">Manage FAQ Categories</h4>
      </div>
    </div>
  </div>
  <div class="row" v-if="parentCats.length > 0">
    <template v-for="(parent, i) in parentCats" :key="i">
      <div class="col-xl-3 col-lg-4 col-md-4">
        <div class="card">
          <div class="card-body">
            <div>
              <h4 class="mt-1 mb-1">{{parent.title['en']}} | (Parent)</h4>
              <router-link :to="{name: 'faqCat.save', params: {faqCat: parent.id}}">
                <button type="button" class="btn btn-outline-primary float-end">Edit</button>
              </router-link>
              <button
                class="btn btn-outline-danger float-end mx-2"
                @click="toBeDeletedId = parent.id"
                data-bs-toggle="modal"
                data-bs-target="#delete-confirmation-modal"
              >Delete</button>
            </div>
            <!-- end div-->
          </div>
          <!-- end card-body-->
        </div>
      </div>
      <span v-show="false">{{childCount = 0}}</span>
      <template v-for="(faqCat, index) in parent.children" :key="index">
        <div v-if="faqCat.parent_id == parent.id" class="col-xl-3 col-lg-4 col-md-4">
          <div class="card">
            <div class="card-body">
              <div>
                <h4 class="mt-1 mb-1">{{faqCat.title['en']}}</h4>
                <router-link :to="{name: 'faqCat.save', params: {faqCat: faqCat.id}}">
                  <button type="button" class="btn btn-sm btn-outline-primary float-end">Edit</button>
                </router-link>
                <button
                  class="btn btn-sm btn-outline-danger float-end mx-2"
                  @click="toBeDeletedId = faqCat.id"
                  data-bs-toggle="modal"
                  data-bs-target="#delete-confirmation-modal"
                >Delete</button>
                <router-link :to="{name: 'faqCat.show', params: {faqCat: faqCat.id}}">
                  <button type="button" class="btn btn-sm btn-outline-secondary float-end">View</button>
                </router-link>
              </div>
              <!-- end div-->
            </div>
            <!-- end card-body-->
          </div>
        </div>
      </template>
      <h6 v-if="parent.children.length < 1" class="mt-1 mb-1">
        This FAQ category has no children.
        <router-link :to="{name: 'faqCat.save'}">add here</router-link>
      </h6>
      <br />
      <hr />
    </template>
  </div>
  <div class="row" v-else>
    <div class="card">
      <div class="card-body">
        <h4 class="mt-1 mb-1">
          No Parent FAQ Categories added
          <router-link :to="{name: 'faqCat.save'}">add here</router-link>
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
          <h4 class="modal-title" id="delete-confirmation-modalLabel">Delete FAQ Category?</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 class="mt-0">Are you absolutely sure?</h5>
          <p>Are you sure you want to delete this FAQ Category. This will also delete all subcategories and questions related to this category. This action is irreversible in any ways.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button
            type="button"
            class="btn btn-danger"
            @click="deleteFaqCat(toBeDeletedId)"
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
      toBeDeletedId: null,
      childCount: 0,
      faqCats: [],
    };
  },
  methods: {
    deleteFaqCat(faqCatId) {
      axios
        .delete(`faqCat/${faqCatId}`)
        .then((response) => {
          this.$store.commit("DELETE_FAQ_CAT", faqCatId);
          this.getFaqCats();
          this.$toast.success(response.data.message, {
            position: "top-right",
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getFaqCats() {
      axios.get("faqCat").then(({ data }) => {
        this.faqCats = data;
      });
    },
  },
  computed: {
    parentCats() {
      return this.faqCats
        ? this.faqCats.filter((cat) => cat.parent_id == 0)
        : [];
    },
  },
  mounted() {
    this.getFaqCats();
  },
};
</script>

<style>
</style>
