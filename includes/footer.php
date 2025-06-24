 <footer class="main-footer">
     <div class="footer-left">
         <a href="#">Manthan Mistry</a></a>
     </div>
     <div class="footer-right">
     </div>
 </footer>
 </div>
 </div>
 <!-- General JS Scripts -->
 <script src="assets/js/app.min.js"></script>
 <!-- JS Libraies -->
 <!-- Page Specific JS File -->
 <script src="assets/bundles/izitoast/js/iziToast.min.js"></script>
 <!-- Template JS File -->
 <script src="assets/js/scripts.js"></script>
 <!-- Custom JS File -->
 <script src="assets/js/custom.js"></script>
 
 <script>
     //  var jQuery341 = jQuery.noConflict(true);
 </script>
 <!-- data table-->
 <script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

 <script>
     $(document).ready(function() {
         $('#dttable').DataTable();

         setInterval(function() {
             $(".show-tost").fadeOut();
         }, 2500);
     });
 </script>

 </body>


 </html>