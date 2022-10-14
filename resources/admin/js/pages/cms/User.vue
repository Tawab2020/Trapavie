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
            <li class="breadcrumb-item active">Users</li>
          </ol>
        </div>
        <h4 class="page-title">Manage Users</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h4 class="header-title">All User</h4>
          <p class="text-muted font-14">List of all the venues for now.</p>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="fname" class="form-label">First name</label>
                <input type="text" v-model="filter.fname" class="form-control" placeholder="search First name"
                       id="fname">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="lname" class="form-label">Last name</label>
                <input type="text" v-model="filter.lname" class="form-control" placeholder="search Last name"
                       id="lname">
              </div>
            </div>
            <div class="col-md-3">
              <label for="type">User Type</label>
              <select v-model="filter.type" id="type" class="form-control">
                <option value="">All</option>
                <option v-for="i in [0,1,2,3]" :value="i">{{ getType(i) }}</option>
              </select>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="country" class="form-label">Country</label>
                <input type="text" v-model="filter.country" class="form-control" placeholder="search by Country"
                       id="country">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="from" class="form-label">From</label>
                <input type="date" v-model="filter.from" id="from" class="form-control" placeholder="From date">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="price" class="form-label">To </label>
                <input type="date" v-model="filter.to" id="price" class="form-control" placeholder="To date">
              </div>
            </div>
          </div>
          <table class="table table-bordered table-centered mb-0">
            <thead>
            <tr>
              <th>Image</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Country</th>
              <th>Type</th>
              <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users.data" :key="user.id">
              <td class="table-user">
                <im :src="'/' + user.image" class="img img-thumbnail" v-if="user.image"/>
              </td>
              <td>{{ user.fname }}</td>
              <td>{{ user.lname }}</td>
              <td>{{ user.country }}</td>
              <td>{{ getType(user.type) }}</td>
              <td>
                <div>
                  <div class="form-group">
                    <label  :for="'block' + user.id" class="form-label">{{user.is_block?'Blocked':'Unblocked'}}  </label>
                    <input type="checkbox" class="check-toggle" @click="blockUser(index,user.id)"
                           :checked="user.is_block" :id="'block' + user.id">
                  </div>
                </div>
                <div v-if="user.id_document != null">
                  <a :href="'/storage/user/id/'+user.id_document" target="_blank">
                    View Identity
                  </a>
                  <button @click="verifyUser(index,user.id)" v-if="user.verified_at == null">
                    Verify
                  </button>
                  <p v-else>{{user.verified_at}}</p>
                </div>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <Pagination :data="users" :limit="3" :show-disabled="true" @pagination-change-page="getUsers">
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


</template>

<script>
import LaravelVuePagination from "laravel-vue-pagination";

export default {
  components: {
    Pagination: LaravelVuePagination,
  },
  data() {
    return {
      users: {data: []},

      filter: {
        fname: '',
        lname: '',
        type: null,
        country: '',
        from: '',
        to: ''
      }
    };
  },
  watch: {
    filter: {
      handler() {
        this.getUsers()
      },
      deep: true,
    }
  },
  created() {
    this.getUsers();

  },
  methods: {
    verifyUser(index,id){
      axios.patch('verify/user/'+id)
      .then(res=>{
        this.users.data[index].verified_at= res.data;
      })
    },
    getType(t) {
      switch (t) {
        case 0:
          return "normal guest";
        case 1:
          return "Venue Host";
        case 2:
          return "Retreat Organizer";
        case 3:
          return "Venue Host & Retreat Organizer";
      }
    },
    getUsers(page = 1) {
      axios.get("users?page=" + page, {
        params: {
          'fname': this.filter.fname,
          'lname': this.filter.lname,
          'type': this.filter.type,
          'country': this.filter.country,
          'from': this.filter.from,
          'to': this.filter.to,
        }
      }).then((response) => {
        this.users = response.data;
      });
    },

    blockUser(index, id) {
      axios.patch("block-unblock/" + id)
        .then(res => {
          this.users.data[index] = res.data;
        })
    }

  },


};
</script>

<style>
</style>
