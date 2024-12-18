$(document).ready(function() {
    // Show the first iframe by default
    $('.gmap_canvas iframe:first').show();
  
    $(".info .one").click(function(){
      window.location.href = "http://www.bguh.gov.lb/pages/Introduction.aspx";


    });
     $(".info .two").click(function(){
      console.log("AUB")
      window.location.href = "https://aubmc.org.lb/pages/home.aspx";

    });
    $(".info .three").click(function(){
      window.location.href = "https://hhumc.org.lb/en/";

      
    });

    // Bind click event to the links
    $('.choices ul li a').click(function(e) {
      e.preventDefault();
      var id = $(this).attr('id');
      // Hide all iframes
      $('.gmap_canvas iframe').hide();
      // Show the iframe corresponding to the clicked link
      $('.gmap_canvas iframe#' + id).show();
      // Add selected class to the chosen item
      $(this).parent('li').addClass('selected').siblings().removeClass('selected');
      // Store the selected item's ID in local storage
      localStorage.setItem('selectedItem', id);
    });
  
  });
  function animateValue(obj, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
      if (!startTimestamp) startTimestamp = timestamp;
      const progress = Math.min((timestamp - startTimestamp) / duration, 1);
      const value = Math.floor(progress * (end - start) + start);
      obj.innerHTML = new Intl.NumberFormat().format(value);
      if (progress < 1) {
        window.requestAnimationFrame(step);
      }
    };
    window.requestAnimationFrame(step);
  }
  
  const animateOnScroll = (entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const target = entry.target;
        // Add custom animation logic based on the element's ID
        switch (target.id) {
          case 'drs':
            animateValue(target, 0, 120, 5000);
            break;
          case 'srv':
            animateValue(target, 0, 25000, 5000);
            break;
          case 'coun':
            animateValue(target, 0, 6, 5000);
            break;
          case 'users':
            animateValue(target, 0, 6500, 5000);
            break;
          default:
            break;
        }
        // Optionally, unobserve the target if you only want the animation to happen once
        observer.unobserve(target);
      }
    });
  };
  
  // Create an IntersectionObserver
  const observer = new IntersectionObserver(animateOnScroll, { threshold: 0.1 });
  
  // Target elements for observation
  const targets = document.querySelectorAll('.animate-me');
  targets.forEach(target => observer.observe(target));


  document.addEventListener("DOMContentLoaded", () => {
    const elements = document.querySelectorAll(".slide-up");

    const handleScroll = () => {
        elements.forEach(element => {
            const rect = element.getBoundingClientRect();
            const isVisible = rect.top <= window.innerHeight * 0.8;
            if (isVisible) {
                element.classList.add("visible");
            }
        });
    };

    window.addEventListener("scroll", handleScroll);

    // Initial check in case elements are already in view
    handleScroll();
});
$(".choices li").hover( function(){
  $(this).find("a").css("color", "blue");
}, function(){
  $(this).find("a").css("color", "white");
})