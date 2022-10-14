<template>
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <router-link :to="{name: 'retreat.category.save' }">
            <button type="button" class="btn-add btn btn-outline-success">
              <i class="dripicons-plus"></i> Add Retreat Data
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
            <li class="breadcrumb-item active">Retreat Data</li>
          </ol>
        </div>
        <h4 class="page-title">Manage Dynamic Retreat Data</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <p class="text-info"> <i class="dripicons-information"></i>&nbsp;Use the following options to filter by:</p>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="date">Date</label>
        <input type="date" v-model="filter.date" class="form-control" id="date"/>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label> Status</label>
        <select class="form-control" v-model="filter.status">
          <option>All</option>
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>
    </div>
  </div>
  <div class="row" >
    <div class="col-md-12">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Category</th>
            <th>Title</th>
            <th>Status</th>
            <th>Created Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(r,index) in retreats" :key="index">
            <td>{{parseJson(r.category_title).en}}</td>
            <td>{{r.title?parseJson(r.title).en:''}}</td>
            <td>{{r.status??r.status}}</td>
            <td>{{r.created_at?toDate(r.created_at):''}}</td>
            <td>
              <router-link
                :to="{name: 'retreat.category.save', params: {id: r.cat_id || 1}}"
                class="btn btn-outline-primary">
                  Edit Category
              </router-link>
              &nbsp;
              <router-link :to="{name: 'retreat.save', params: {categoryId: r.cat_id}}" class="btn btn-outline-success">
                <i class="dripicons-plus"></i>Add Retreat
              </router-link>&nbsp;
             <template v-if="r.id">
               <select class="form-control-sm" @change="changeStatus(r.status,r.id)" v-model="r.status">
                 <option value="pending">Pending</option>
                 <option value="approved">Approved</option>
                 <option value="rejected">Rejected</option>
               </select>
               &nbsp;
               <router-link :to="{name: 'retreat.save', params: {retreatId: r.id}}" class="btn btn-outline-primary">
                 Edit Retreat
               </router-link>
               &nbsp;
               <button class="btn btn-sm btn-danger"   data-bs-toggle="modal"
                       data-bs-target="#delete-confirmation-modal" @click="toDeleteId=r.id;toDeleteIndex=index"><i class="dripicons-trash"></i></button>
             </template>
            </td>
          </tr>
        <tr v-if="retreats.length == 0">
          <td colspan="5" align="center">No DATA</td>
        </tr>
        </tbody>
      </table>
    </div>
    <template v-for="(cat, i) in categories" :key="i">
      <div class="col-xl-6 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="card-title">
              <span v-if="cat.icon" class="float-start m-2 me-4">
                <img
                  :src="'/' + filePath(cat.icon)"
                  style="height: 100px;"
                  alt
                  class="rounded-circle img-thumbnail"
                />
              </span>
              <h4 class="mt-1 mb-1 d-inline-block">{{parseJson(cat.title).en}}</h4>
              <router-link
                :to="{name: 'retreat.category.save', params: {id: cat.id || 1}}"
              >
                <button type="button" class="btn btn-outline-primary float-end">Edit</button>
              </router-link>
              <router-link
                :to="{name: 'retreat.save', params: {categoryId: cat.id || 1}}"
                class="btn btn-outline-success float-end mx-1"
              >
                <i class="dripicons-plus"></i>Add Retreat
              </router-link>
              <div class="row mt-2">
                <div class="col-12">
                  <span
                    v-for="(r, index) in cat.retreats"
                    :key="index+i"
                    class="badge badge-outline-secondary venue-badge m-1"
                  >
                    <router-link :to="{name: 'retreat.save', params: {retreatId: r.id}}">
                      <i class="dripicons-document-edit venue-badge-btn text-success d-none"></i>
                    </router-link>
                    {{parseJson(r.title).en}}
                    <i
                      class="dripicons-tag-delete venue-badge-btn text-danger d-none"
                      @click="deleteRecord(r.id,index)"
                      data-bs-toggle="modal"
                      data-bs-target="#delete-confirmation-modal"
                    ></i>
                  </span>
                </div>
              </div>
            </div>
            <!-- end div-->
          </div>
          <!-- end card-body-->
        </div>
      </div>
    </template>
  </div>
<!--  <div class="row" v-else>-->
<!--    <div class="card">-->
<!--      <div class="card-body">-->
<!--        <h4 class="mt-1 mb-1">No Venue Data Categories added</h4>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
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
          <h4 class="modal-title" id="delete-confirmation-modalLabel">Delete Venue Data?</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 class="mt-0">Are you absolutely sure?</h5>
          <p>Are you sure you want to delete this Venue Data? You won't be able to delete this if it is used in a venue! This action is irreversible in any ways.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal" @click="toDeleteIndex=null;toDeleteId=null;">Close</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" @click="deleteRecord()">Delete</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      filter:{
        date:"",
        status:''
      },
      toDeleteId:null,
      toDeleteIndex:null,
      retreats:[],
    };
  },
  watch:{
    filter: {
      handler() {

        this.fetchRetreats()
      },
      deep: true,
    }
  },
  methods: {
     parseJson(arg){
       return JSON.parse(arg);
     },
     filePath(pathStr){
       return pathStr.replace("public",'storage')
     },
    deleteRecord(){
       axios.delete(`retreats/delete/${this.toDeleteId}`)
      .then(res=>{
        this.fetchRetreats();
      })
    },
    fetchRetreats(){
      axios.get('retreats/',{params:{date:this.filter.date,status:this.filter.status}})
        .then(res=>{
          this.retreats =res.data;
        })
    },
    toDate(arg){
       let date= new Date(arg);
     date =  date.getDate()+
      "/"+(date.getMonth()+1)+
      "/"+date.getFullYear();
       return date
    },
    changeStatus(status,id){
       let form = new FormData();
       form.append('status',status)
       axios.post(`retreats/change-status/${id}`,form)
      .then(res=>{
        this.fetchRetreats();
      })
    }
  },
  created(){
    this.fetchRetreats();
  },

};
</script>

<style>
.venue-badge {
  font-size: 14px;
}
.venue-badge:hover .venue-badge-btn {
  display: inline !important;
}
</style>
