 @extends('customer::dashboard')

 @section('dashboard-view')
 <div class="container px-5 lg:mx-auto">
     <h2 class="text-2xl">My Wishlist</h2>
     <div>
         <wishlist-details />
     </div>
 </div>
 @endsection