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
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Venues</a>
            </li>
            <li class="breadcrumb-item active">Bookings</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <p>
            <router-link :to="{name:'venue.index'}" class="btn btn-sm btn-primary">
              <i class="mdi mdi-arrow-left"></i> Back
            </router-link>
          </p>
          <h4 class="header-title">All Booking of</h4>
          <p class="text-muted font-14">List of all bookings for venue <b>{{venue.name}}</b>.</p>
          <table class="table table-bordered table-centered mb-0">
            <thead>
            <tr>
              <th>Start/End date</th>
              <th>Guests</th>
              <th>Currency</th>
              <th>price</th>
              <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="b in venue.bookings" :key="b.id">
              <td class="table-user">
                <p>Start date: {{b.start_date}}</p>
                <p>End date: {{b.end_date}}</p>

              </td>
              <td>{{ b.guests }}</td>
              <td>{{b.price.currency.code }}</td>
              <td>{{b.price }}</td>
              <td>{{getStatus( b.status)}}</td>
            </tr>
            <tr v-if="venue.bookings.length == 0">
              <td colspan="5" align="center">No Bookings for this Venue</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "index",
  props:{
    id:{
      required:true,
    }
  },
  data(){
    return {
      venue: {bookings:[]}
    }
  },
  created(){

    axios.get('bookings/' + this.id)
    .then(res=>{
      this.venue = res.data;
    })
  },
  methods:{
    getStatus(status){
      switch (status){
        case 0: return "REQUESTED";
        case 1: return "CONFIRMED"
        case 2: return "DEPOSIT PAID";
        case 3: return "FULLY PAID";
        case 4: return "REJECTED by Venue Host";
        case 5: return " Cancelled by Venue Host";
        case 6: return "Cancelled by Retreat Organizer";
        case 7: return "Refunded"
      }
    }
  }
}
</script>

<style scoped>

</style>
