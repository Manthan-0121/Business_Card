// let imageInputCounter = 0;

// function addImageInput() {
//     let inputHtml = `
//     <div class="input-group mb-2">
//         <input type="file" name="other_images[]" class="form-control image-input" accept="image/*">
//         <button type="button" class="btn btn-danger" onclick="removeInput(this)">
//             <i class="bi bi-trash"></i>
//         </button>
//     </div>`;
//     document.getElementById('image_container').insertAdjacentHTML('beforeend', inputHtml);
// }

// function removeInput(button) {
//     const inputGroup = button.closest('.input-group');
//     inputGroup.remove();
//     updateCarousel();
// }

// function updateCarousel() {
//     const carouselInner = document.querySelector('#carouselExampleIndicators3 .carousel-inner');
//     const inputs = document.querySelectorAll('#image_container input.image-input');
//     const existingImgs = document.getElementById('image_container').querySelectorAll('img');
//     const selectedFiles = Array.from(inputs).filter(input => input.files && input.files.length > 0);

//     if (selectedFiles.length > 0) {
//         // Clear existing carousel items
//         carouselInner.innerHTML = '';

//         // Add new items from selected files
//         selectedFiles.forEach((input, index) => {
//             const reader = new FileReader();
//             reader.onload = function (event) {
//                 const isActive = index === 0 ? 'active' : '';
//                 const carouselItem = `
//                     <div class="carousel-item ${isActive}">
//                         <img class="d-block w-75 mlc" src="${event.target.result}" alt="Selected image ${index + 1}">
//                     </div>`;
//                 carouselInner.insertAdjacentHTML('beforeend', carouselItem);

//                 // Initialize/reinitialize carousel after first image is added
//                 if (index === 0) {
//                     $('#carouselExampleIndicators3').carousel();
//                 }
//             };
//             reader.readAsDataURL(input.files[0]);
//         });
//     } else {
//         // Restore original slider images when no files are selected
//         carouselInner.innerHTML = `
//             <div class="carousel-item active">
//                 <img class="d-block w-75 mlc" src="assets/templates/img/slider/img1.png" alt="First slide">
//             </div>
//             <div class="carousel-item">
//                 <img class="d-block w-75 mlc" src="assets/templates/img/slider/img2.png" alt="Second slide">
//             </div>
//             <div class="carousel-item">
//                 <img class="d-block w-75 mlc" src="assets/templates/img/slider/img3.png" alt="Third slide">
//             </div>`;
//         $('#carouselExampleIndicators3').carousel();
//     }
// }

// // Event listener for file inputs
// document.getElementById('image_container').addEventListener('change', function (e) {
//     if (e.target && e.target.matches('input.image-input[type="file"]')) {
//         updateCarousel();
//     }
// });

// // Initialize carousel with original images on page load
// document.addEventListener('DOMContentLoaded', function () {
//     $('#carouselExampleIndicators3').carousel();
// });


