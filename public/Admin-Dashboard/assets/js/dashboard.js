// ====================
// Chart 1 (Bar)
// ====================
var ctx = document.getElementById("chart-bars").getContext("2d");

new Chart(ctx, {
  type: "bar",
  data: {
    labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [
      {
        label: "Sales",
        tension: 0.4,
        borderWidth: 0,
        borderRadius: 4,
        borderSkipped: false,
        backgroundColor: "#fff",
        data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
        maxBarThickness: 6,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    interaction: { intersect: false, mode: "index" },
    scales: {
      y: {
        grid: { drawBorder: false, display: false, drawOnChartArea: false, drawTicks: false },
        ticks: {
          suggestedMin: 0,
          suggestedMax: 600,
          beginAtZero: true,
          padding: 15,
          font: { size: 14, family: "Open Sans", style: "normal", lineHeight: 2 },
          color: "#fff",
        },
      },
      x: {
        grid: { drawBorder: false, display: false, drawOnChartArea: false, drawTicks: false },
        ticks: { display: false },
      },
    },
  },
});

// ====================
// Chart 2 (Line)
// ====================
var ctx2 = document.getElementById("chart-line").getContext("2d");

var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);
gradientStroke1.addColorStop(1, "rgba(203,12,159,0.2)");
gradientStroke1.addColorStop(0.2, "rgba(72,72,176,0.0)");
gradientStroke1.addColorStop(0, "rgba(203,12,159,0)");

var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);
gradientStroke2.addColorStop(1, "rgba(20,23,39,0.2)");
gradientStroke2.addColorStop(0.2, "rgba(72,72,176,0.0)");
gradientStroke2.addColorStop(0, "rgba(20,23,39,0)");

new Chart(ctx2, {
  type: "line",
  data: {
    labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [
      {
        label: "Mobile apps",
        tension: 0.4,
        borderWidth: 0,
        pointRadius: 0,
        borderColor: "#cb0c9f",
        borderWidth: 3,
        backgroundColor: gradientStroke1,
        fill: true,
        data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
        maxBarThickness: 6,
      },
      {
        label: "Websites",
        tension: 0.4,
        borderWidth: 0,
        pointRadius: 0,
        borderColor: "#3A416F",
        borderWidth: 3,
        backgroundColor: gradientStroke2,
        fill: true,
        data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
        maxBarThickness: 6,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    interaction: { intersect: false, mode: "index" },
    scales: {
      y: {
        grid: { drawBorder: false, display: true, drawOnChartArea: true, drawTicks: false, borderDash: [5, 5] },
        ticks: { display: true, padding: 10, color: "#b2b9bf", font: { size: 11, family: "Open Sans", style: "normal", lineHeight: 2 } },
      },
      x: {
        grid: { drawBorder: false, display: false, drawOnChartArea: false, drawTicks: false, borderDash: [5, 5] },
        ticks: { display: true, color: "#b2b9bf", padding: 20, font: { size: 11, family: "Open Sans", style: "normal", lineHeight: 2 } },
      },
    },
  },
});

// ====================
// Navbar Notifications Dropdown
// ====================
var dropdown_triggers = document.querySelectorAll("[dropdown-trigger]");
dropdown_triggers.forEach((dropdown_trigger) => {
  let dropdown_menu = dropdown_trigger.parentElement.querySelector("[dropdown-menu]");

  dropdown_trigger.addEventListener("click", function () {
    dropdown_menu.classList.toggle("opacity-0");
    dropdown_menu.classList.toggle("pointer-events-none");
    dropdown_menu.classList.toggle("before:-top-5");

    if (dropdown_trigger.getAttribute("aria-expanded") == "false") {
      dropdown_trigger.setAttribute("aria-expanded", "true");
      dropdown_menu.classList.remove("transform-dropdown");
      dropdown_menu.classList.add("transform-dropdown-show");
    } else {
      dropdown_trigger.setAttribute("aria-expanded", "false");
      dropdown_menu.classList.remove("transform-dropdown-show");
      dropdown_menu.classList.add("transform-dropdown");
    }
  });

  window.addEventListener("click", function (e) {
    if (!dropdown_menu.contains(e.target) && !dropdown_trigger.contains(e.target)) {
      if (dropdown_trigger.getAttribute("aria-expanded") == "true") {
        dropdown_trigger.click();
      }
    }
  });
});

// ====================
// Tabs Navigation
// ====================
var total = document.querySelectorAll("[nav-pills]");

function getEventTarget(e) {
  e = e || window.event;
  return e.target || e.srcElement;
}

total.forEach(function (item) {
  var moving_div = document.createElement("div");
  var first_li = item.querySelector("li:first-child [nav-link]");
  var tab = first_li.cloneNode();

  tab.innerHTML = "-";
  tab.classList.remove("bg-inherit");
  tab.classList.add("bg-white", "text-white", "shadow-soft-xxs");
  tab.style.animation = ".2s ease";

  moving_div.classList.add("z-10", "absolute", "text-slate-700", "rounded-lg", "bg-inherit", "flex-auto", "text-center", "bg-none", "border-0", "block");
  moving_div.setAttribute("moving-tab", "");
  moving_div.setAttribute("nav-link", "");
  moving_div.appendChild(tab);
  item.appendChild(moving_div);

  var list_length = item.getElementsByTagName("li").length;
  moving_div.style.boxShadow = "0 1px 5px 1px #ddd";
  moving_div.style.padding = "0px";
  moving_div.style.width = item.querySelector("li:nth-child(1)").offsetWidth + "px";
  moving_div.style.transform = "translate3d(0px, 0px, 0px)";
  moving_div.style.transition = ".5s ease";

  item.onmouseover = function (event) {
    let target = getEventTarget(event);
    let li = target.closest("li");
    if (li) {
      let nodes = Array.from(li.closest("ul").children);
      let index = nodes.indexOf(li) + 1;
      item.querySelector("li:nth-child(" + index + ") [nav-link]").onclick = function () {
        item.querySelectorAll("li").forEach(function (list_item) {
          list_item.firstElementChild.removeAttribute("active");
        });
        li.firstElementChild.setAttribute("active", "");
        moving_div = item.querySelector("[moving-tab]");

        let sum = 0;
        if (item.classList.contains("flex-col")) {
          for (var j = 1; j <= nodes.indexOf(li); j++) {
            sum += item.querySelector("li:nth-child(" + j + ")").offsetHeight;
          }
          moving_div.style.transform = "translate3d(0px," + sum + "px, 0px)";
          moving_div.style.height = item.querySelector("li:nth-child(" + j + ")").offsetHeight;
        } else {
          for (var j = 1; j <= nodes.indexOf(li); j++) {
            sum += item.querySelector("li:nth-child(" + j + ")").offsetWidth;
          }
          moving_div.style.transform = "translate3d(" + sum + "px, 0px, 0px)";
          moving_div.style.width = item.querySelector("li:nth-child(" + index + ")").offsetWidth + "px";
        }
      };
    }
  };
});

// Tabs Navigation Resize
window.addEventListener("resize", function () {
  total.forEach(function (item) {
    item.querySelector("[moving-tab]").remove();
    var moving_div = document.createElement("div");
    var tab = item.querySelector("[nav-link][active]").cloneNode();

    tab.innerHTML = "-";
    tab.classList.remove("bg-inherit");
    tab.classList.add("bg-white", "text-white", "shadow-soft-xxs");
    tab.style.animation = ".2s ease";

    moving_div.classList.add("z-10", "absolute", "text-slate-700", "rounded-lg", "bg-inherit", "flex-auto", "text-center", "bg-none", "border-0", "block");
    moving_div.setAttribute("moving-tab", "");
    moving_div.setAttribute("nav-link", "");
    moving_div.appendChild(tab);
    item.appendChild(moving_div);

    moving_div.style.boxShadow = "0 1px 5px 1px #ddd";
    moving_div.style.padding = "0px";
    moving_div.style.transition = ".5s ease";

    let li = item.querySelector("[nav-link][active]").parentElement;
    if (li) {
      let nodes = Array.from(li.closest("ul").children);
      let index = nodes.indexOf(li) + 1;

      let sum = 0;
      if (item.classList.contains("flex-col")) {
        for (var j = 1; j <= nodes.indexOf(li); j++) {
          sum += item.querySelector("li:nth-child(" + j + ")").offsetHeight;
        }
        moving_div.style.transform = "translate3d(0px," + sum + "px, 0px)";
        moving_div.style.width = item.querySelector("li:nth-child(" + index + ")").offsetWidth + "px";
        moving_div.style.height = item.querySelector("li:nth-child(" + j + ")").offsetHeight;
      } else {
        for (var j = 1; j <= nodes.indexOf(li); j++) {
          sum += item.querySelector("li:nth-child(" + j + ")").offsetWidth;
        }
        moving_div.style.transform = "translate3d(" + sum + "px, 0px, 0px)";
        moving_div.style.width = item.querySelector("li:nth-child(" + index + ")").offsetWidth + "px";
      }
    }
  });

  if (window.innerWidth < 991) {
    total.forEach(function (item) {
      if (!item.classList.contains("flex-col")) {
        item.classList.add("flex-col", "on-resize");
      }
    });
  } else {
    total.forEach(function (item) {
      if (item.classList.contains("on-resize")) {
        item.classList.remove("flex-col", "on-resize");
      }
    });
  }
});

// ====================
// Sidenav Burger
// ====================
var sidenav = document.querySelector("aside");
var sidenav_trigger = document.querySelector("[sidenav-trigger]");
var sidenav_close_button = document.querySelector("[sidenav-close]");
var burger = sidenav_trigger.firstElementChild;
var top_bread = burger.firstElementChild;
var bottom_bread = burger.lastElementChild;

sidenav_trigger.addEventListener("click", function () {
  if (page == "virtual-reality") sidenav.classList.toggle("xl:left-[18%]");
  sidenav_close_button.classList.toggle("hidden");
  sidenav.classList.toggle("translate-x-0");
  sidenav.classList.toggle("shadow-soft-xl");

  if (page == "rtl") {
    top_bread.classList.toggle("-translate-x-[5px]");
    bottom_bread.classList.toggle("-translate-x-[5px]");
  } else {
    top_bread.classList.toggle("translate-x-[5px]");
    bottom_bread.classList.toggle("translate-x-[5px]");
  }
});

sidenav_close_button.addEventListener("click", function () {
  sidenav_trigger.click();
});

window.addEventListener("click", function (e) {
  if (!sidenav.contains(e.target) && !sidenav_trigger.contains(e.target)) {
    if (sidenav.classList.contains("translate-x-0")) {
      sidenav_trigger.click();
    }
  }
});

// ====================
// Navbar Sticky
// ====================
var navbar = document.querySelector("[navbar-main]");
window.onscroll = function () {
  let blur = navbar.getAttribute("navbar-scroll");
  if (blur == "true") stickyNav();
};

function stickyNav() {
  if (window.scrollY >= 5) {
    navbar.classList.add("sticky", "top-[1%]", "backdrop-saturate-[200%]", "backdrop-blur-[30px]", "bg-[hsla(0,0%,100%,0.8)]", "shadow-blur", "z-110");
  } else {
    navbar.classList.remove("sticky", "top-[1%]", "backdrop-saturate-[200%]", "backdrop-blur-[30px]", "bg-[hsla(0,0%,100%,0.8)]", "shadow-blur", "z-110");
  }
}

// ====================
// Tooltips
// ====================
var buttons = document.querySelectorAll("[data-target='tooltip_trigger']");

buttons.forEach((button) => {
  var tooltip = button.nextElementSibling;
  var placement = button.getAttribute("data-placement");

  const popperInstance = Popper.createPopper(button, tooltip, {
    modifiers: [{ name: "offset", options: { offset: [0, 8] } }],
    placement: placement,
  });

  function show() {
    tooltip.classList.remove("hidden");
    tooltip.classList.add("block");
    popperInstance.setOptions((options) => ({
      ...options,
      modifiers: [...options.modifiers, { name: "eventListeners", enabled: true }],
    }));
    popperInstance.update();
  }

  function hide() {
    tooltip.classList.remove("block");
    tooltip.classList.add("hidden");
    popperInstance.setOptions((options) => ({
      ...options,
      modifiers: [...options.modifiers, { name: "eventListeners", enabled: false }],
    }));
  }

  const showEvents = ["mouseenter", "focus"];
  const hideEvents = ["mouseleave", "blur"];
  showEvents.forEach((event) => button.addEventListener(event, show));
  hideEvents.forEach((event) => button.addEventListener(event, hide));
});

// ====================
// navbar-collpse Scrollbar (Windows only)
// ====================
var expand_trigger = document.querySelector("[navbar-trigger]");
var bar1 = document.querySelector("[bar1]");
var bar2 = document.querySelector("[bar2]");
var bar3 = document.querySelector("[bar3]");
var navbar_sign_in_up = document.querySelector("[navbar-menu]");
const collapse_height = navbar_sign_in_up.scrollHeight;

expand_trigger.addEventListener("click", function () {
  elements = navbar_sign_in_up.querySelectorAll("a");
  if (navbar_sign_in_up.classList.contains("lg-max:max-h-0")) {
    navbar_sign_in_up.classList.remove("lg-max:max-h-0");
    navbar_sign_in_up.classList.add("lg-max:max-h-54");
    setTimeout(function () {
      elements.forEach((element) => {
        element.classList.remove("lg-max:opacity-0");
      });
    }, 50);
  } else {
    setTimeout(function () {
      elements.forEach((element) => {
        element.classList.add("lg-max:opacity-0");
      });
    }, 100);
    navbar_sign_in_up.classList.remove("lg-max:max-h-54");
    navbar_sign_in_up.classList.add("lg-max:max-h-0");
  }
  bar1.classList.toggle("rotate-45");
  bar1.classList.toggle("origin-10-10");
  bar1.classList.toggle("mt-1");

  bar2.classList.toggle("opacity-0");

  bar3.classList.toggle("-rotate-45");
  bar3.classList.toggle("origin-10-90");
  bar3.classList.toggle("mt-0.75");
  bar3.classList.toggle("mt-1.75");
});

// Navbar stick on scroll ++ styles

var navbar = document.querySelector("[navbar-main]");

window.onscroll = function () {
  let blur = navbar.getAttribute("navbar-scroll");
  if (blur == "true") stickyNav();
};

function stickyNav() {
  if (window.scrollY >= 5) {
    navbar.classList.add("sticky", "top-[1%]", "backdrop-saturate-[200%]", "backdrop-blur-[30px]", "bg-[hsla(0,0%,100%,0.8)]", "shadow-blur", "z-110");
  } else {
    navbar.classList.remove("sticky", "top-[1%]", "backdrop-saturate-[200%]", "backdrop-blur-[30px]", "bg-[hsla(0,0%,100%,0.8)]", "shadow-blur", "z-110");
  }
}

// ====================
// Perfect Scrollbar (Windows only)
// ====================
(function () {
  var isWindows = navigator.platform.indexOf("Win") > -1;
  if (isWindows) {
    if (document.querySelector("main")) {
      var mainpanel = document.querySelector("main");
      var ps = new PerfectScrollbar(mainpanel);
    }

    if (document.querySelectorAll(".overflow-auto")[0]) {
      var sidebar = document.querySelectorAll(".overflow-auto");
      var i = 0;
      var ps;
      sidebar.forEach((element) => {
        ps[i++] = new PerfectScrollbar(element);
      });
    }
    if (document.querySelectorAll(".overflow-y-auto")[0]) {
      var sidebar = document.querySelectorAll(".overflow-y-auto");
      var i = 0;
      var ps;
      sidebar.forEach((element) => {
        ps[i++] = new PerfectScrollbar(element);
      });
    }
    if (document.querySelectorAll(".overflow-x-auto")[0]) {
      var sidebar = document.querySelectorAll(".overflow-x-auto");
      var i = 0;
      var ps;
      sidebar.forEach((element) => {
        ps[i++] = new PerfectScrollbar(element);
      });
    }
  }
})();
