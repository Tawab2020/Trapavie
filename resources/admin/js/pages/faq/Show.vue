<template>
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <router-link :to="{name: 'faq.save', params: {faqCat: faqCat.id} }">
            <button type="button" class="btn-add btn btn-outline-success">
              <i class="dripicons-plus"></i> Add Question
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
        <h4 class="page-title">Manage FAQ</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <div>
        <h3>{{faqCat.title['en']}}</h3>
      </div>
    </div>
    <!-- end col -->
  </div>
  <div class="row pt-3 pb-3">
    <!-- Question/Answer -->
    <div v-for="(faq, index) in faqCat.faqs" :key="index" class="card col-lg-6 col-md-12">
      <div class="card-body">
        <div class="faq-question-q-box">Q.</div>
        <h4 class="faq-question" data-wow-delay=".1s">{{ faq.question['en'] }}</h4>
        <p class="faq-answer mb-4">{{ faq.answer['en'].substring(0,150)+".." }}</p>
        <router-link :to="{name: 'faq.save', params: {faqCat: faqCat.id, faq: faq.id}}">
          <button type="button" class="btn btn-sm btn-outline-primary float-end">Edit</button>
        </router-link>
        <button
          class="btn btn-sm btn-outline-danger float-end mx-2"
          @click="toBeDeletedId = faq.id"
          data-bs-toggle="modal"
          data-bs-target="#delete-confirmation-modal"
        >Delete</button>
      </div>
    </div>
  </div>
  <div class="row" v-if="faqCat.faqs.length < 1">
    <div class="card">
      <div class="card-body">
        <h4 class="mt-1 mb-1">
          No Questions added
          <router-link :to="{name: 'faq.save', params: {faqCat: faqCat.id}}">add here</router-link>
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
          <p>Are you sure you want to delete this Question? This action is irreversible!</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button
            type="button"
            class="btn btn-danger"
            @click="deleteFaq(toBeDeletedId)"
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
      faqCat: {
        id: 0,
        title: {},
        faqs: [],
      },
      toBeDeletedId: null,
    };
  },
  methods: {
    deleteFaq(faqId) {
      axios
        .delete(`faq/${faqId}`)
        .then((response) => {
          const index = this.faqCat.faqs.findIndex((faq) => faq.id == faqId);
          this.faqCat.faqs.splice(index, 1);
          this.$toast.success(response.data.message, {
            position: "top-right",
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    if (this.$route.params.faqCat) {
      this.editMode = true;
      axios
        .get(`faqCat/${this.$route.params.faqCat}`)
        .then(({ data }) => {
          this.faqCat = data;
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
