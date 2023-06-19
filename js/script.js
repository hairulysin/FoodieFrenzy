navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   profile.classList.remove('active');
}

// function loader(){
//    document.querySelector('.loader').style.display = 'none';
// }

// function fadeOut(){
//    setInterval(loader, 2000);
// }

// window.onload = fadeOut;


document.querySelectorAll('input[type="number"]').forEach(numberInput => {
   numberInput.oninput = () =>{
      if(numberInput.value.length > numberInput.maxLength) numberInput.value = numberInput.value.slice(0, numberInput.maxLength);
   };
});

const swiper = new Swiper('.hero-slider', {
   // Optional parameters
   loop: true,
   autoplay: {
     delay: 1000, // Delay between transitions in milliseconds
     disableOnInteraction: false, // Prevent slider from stopping on user interaction
   },
   
      // Navigation arrows
      navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
      },
   
      // If we need pagination
      pagination: {
      el: '.swiper-pagination',
      },
   
      // And if we need scrollbar
      scrollbar: {
      el: '.swiper-scrollbar',
      },
   });

   