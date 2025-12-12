document.addEventListener("DOMContentLoaded", () => {
  const reveals = document.querySelectorAll(".reveal");
  const sections = document.querySelectorAll("section");

  // Animate elements when entering and leaving viewport
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
      } else {
        entry.target.classList.remove("visible");
      }
    });
  }, {
    threshold: 0.05,
    rootMargin: "0px 0px -10% 0px",
  });

  reveals.forEach((el) => revealObserver.observe(el));

  // Fade / lift sections as you scroll
  const sectionObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("section-active");
      } else {
        entry.target.classList.remove("section-active");
      }
    });
  }, { threshold: 0.3 });

  sections.forEach((section) => sectionObserver.observe(section));

  // Header scroll shadow
  const header = document.querySelector(".main-header");
  window.addEventListener("scroll", () => {
    header.classList.toggle("scrolled", window.scrollY > 50);
  });
});

const section5 = document.querySelector(".section5");
const p1 = document.querySelector(".point1");
const p2 = document.querySelector(".point2");
const p3 = document.querySelector(".point3");

window.addEventListener("scroll", () => {
  const rect = section5.getBoundingClientRect();

  if (rect.top < window.innerHeight - 200) {
    p1.classList.add("visible");
    p2.classList.add("visible");
    p3.classList.add("visible");
  }
});


