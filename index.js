AOS.init();

const menuBtn = document.getElementById("menu");
const closeBtn = document.getElementById("closeBtn");
const sidebar = document.querySelector(".sidebar");

menuBtn.onclick = () => {
  sidebar.classList.add("sidebar-open");
};

closeBtn.onclick = () => {
  sidebar.classList.remove("sidebar-open");
};
