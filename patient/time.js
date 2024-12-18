const timeElement = document.querySelector(".time");
const dateElement = document.querySelector(".date");

/**
 * @param {Date} date
 */
function formatTime(date) {
  const hours12 = date.getHours() % 12 || 12;
  const minutes = date.getMinutes();
  const isAm = date.getHours() < 12;

  return `${hours12.toString().padStart(2, "0")}:${minutes
    .toString()
    .padStart(2, "0")} ${isAm ? "AM" : "PM"}`;
}

/**
 * @param {Date} date
 */
function formatDate(date) {
  const DAYS = [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday"
  ];
  const MONTHS = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December"
  ];

  return `${DAYS[date.getDay()]}, ${
    MONTHS[date.getMonth()]
  } ${date.getDate()} ${date.getFullYear()}`;
}

setInterval(() => {
  const now = new Date();

  timeElement.textContent = formatTime(now);
  dateElement.textContent = formatDate(now);
}, 200);

function updateBackgroundImage(x) {
  const $profileInfo = $("#profile-info");
  const $hosp = $("#hosp");

  // Fade out before changing the image
  $profileInfo.addClass("fade-out");

  // Change the background image and text after the fade-out animation
  setTimeout(() => {
    if (x == 1) {
      $("#hosp").text("American University of Beirut Medical Center");
      $("#profile-info").css('background-image', 'url("../img/AUBMChigh.jpg")');
    } else if (x == 2) {

      $("#hosp").text("Rafik Hariri University Hospital");
      $("#profile-info").css('background-image', 'url("../img/rhuh4.jpg")');
    } else if (x == 3) {
      $("#hosp").text("Hammoud Hospital University Medical Center");
      $("#profile-info").css('background-image', 'url("../img/hhmuc3.jpg")');
    }

    // Fade back in after changing the image
    $profileInfo.removeClass("fade-out");
  }, 500); // Match this delay to the CSS transition duration
}

function updateBackgroundImageAdap() {
  let x = Math.floor(Math.random() * 3 + 1);
  updateBackgroundImage(x);
}

updateBackgroundImageAdap()
setInterval(updateBackgroundImageAdap, 10000);


$("#profilemodal").on("click", function(e){
  e.preventDefault();
  $("#editModal").show();
})