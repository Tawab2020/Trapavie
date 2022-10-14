<template>
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <router-link :to="{name: 'team.save' }">
            <button type="button" class="btn-add btn btn-outline-success">
              <i class="dripicons-plus"></i> Add Team Member
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
            <li class="breadcrumb-item active">Team Members</li>
          </ol>
        </div>
        <h4 class="page-title">Manage Team Members</h4>
      </div>
    </div>
  </div>
  <div class="row" v-if="teamMembers.length > 0">
    <div v-for="(teamMember, index) in teamMembers" :key="index" class="col-xl-4 col-lg-6 col-md-4">
      <div class="card">
        <div class="card-body">
          <span class="float-start m-2 me-4">
            <img
              :src="`${$FilePath}/team/${teamMember.image}`"
              style="height: 100px;"
              alt
              class="rounded-circle img-thumbnail"
            />
          </span>
          <div>
            <h4 class="mt-1 mb-1">{{teamMember.name['en']}}</h4>
            <p class="font-13">{{teamMember.title['en']}}</p>
            <router-link :to="{name: 'team.save', params: {teamMember: teamMember.id}}">
              <button type="button" class="btn btn-sm btn-outline-primary float-end">Edit</button>
            </router-link>
            <button
              class="btn btn-sm btn-outline-danger float-end mx-2"
              @click="toBeDeletedId = teamMember.id"
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
          No Team Members added
          <router-link to="team/save">add here</router-link>
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
          <h4 class="modal-title" id="delete-confirmation-modalLabel">Delete Team Member?</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 class="mt-0">Are you absolutely sure?</h5>
          <p>Are you sure you want to delete this team member. this action is irreversible in any ways.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button
            type="button"
            class="btn btn-danger"
            @click="deleteTeamMember(toBeDeletedId)"
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
      teamMembers: [],
      toBeDeletedId: null,
    };
  },
  computed: {
    // filePath() {
    //   return this.$store.getters.filePath;
    // },
  },
  methods: {
    deleteTeamMember(teamMemberId) {
      axios
        .delete(`teamMember/${teamMemberId}`)
        .then((response) => {
          const index = this.teamMembers.findIndex(
            (teamMember) => teamMember.id == teamMemberId
          );
          this.teamMembers.splice(index, 1);
          this.$toast.success("Team member was deleted successfully.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    axios
      .get("teamMember")
      .then((response) => {
        this.teamMembers = response.data;
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>

<style>
</style>
